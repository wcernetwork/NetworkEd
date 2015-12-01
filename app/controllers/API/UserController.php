<?php

/**
** Used for actions on the user profile
**/

class UserController extends Controller {

	public function getProfile($user_id)
	{
		if (Auth::check() && Auth::user()->id == $user_id)
		{
			$user = User::with(['posts' => function ($q) {
				$q->with('post_type')
					->orderBy('created_at', 'desc')
					->take(15);
			}])->find($user_id);

			//unserialize roles array (for backwards compatibility)
			$serialized = @unserialize($user->role_title);
			if ($serialized !== false) {
				$user->role_title = $serialized;
			}

			//get the total count of this user's posts
			$all_user_posts = Post::where('user_id', '=', $user_id)
										->get();
			$user->num_posts = count($all_user_posts);

			//get first 10 favorites
			$user->favorites = Favorite::with('post.post_type')
								->where('user_id', '=', $user_id)
								->orderBy('created_at', 'desc')
								->take(15)
								->get();

			//if this is an admin/moderator, also get the approval queue
			if (Auth::user()->is_moderator)
			{
				$user->approval_queue = Post::with('user', 'post_type')
									->where('approved', '=', false)
									->orderBy('created_at', 'desc')
									->take(15)
									->get();
				//get total count of posts in approval queue
				$user->approval_queue_count = count(Post::where('approved', '=', false)->get());
				//for each post in approval queue, retrieve any tags that are currently unapproved to display in list
				if (count($user->approval_queue) > 0)
				{
					foreach($user->approval_queue as $key => $post) {
						$user->approval_queue[$key]['unapproved_tags'] = TagPost::join('tags', 'tags.id', '=', 'tag_posts.tag_id')
								->with('tag.tag_category')
								->where('tags.pending', '=', 1)
								->where('post_id', '=', $post->id)
								->get();
					}
				}
			}

			return $user;
		}
		else
		{
			$user = User::with(['posts' => function ($q) {
				$q->with('post_type')
					->where('approved', '=', true)
					->orderBy('created_at', 'desc')
					->take(15);
			}])->select('bio', 'city', 'email', 'first_name', 'last_name', 'id', 'organization', 'photo', 'role_id', 'role_title', 'role_title_other', 'state', 'zip')
				->find($user_id);

			//unserialize roles array (for backwards compatibility)
			$serialized = @unserialize($user->role_title);
			if ($serialized !== false) {
				$user->role_title = $serialized;
			}

			//get the total count of this user's posts
			$all_user_posts = Post::where('approved', '=', true)
										->where('user_id', '=', $user_id)
										->get();
			$user->num_posts = count($all_user_posts);

			return $user;
		}
	}

	/* returns paginated set of a user's own posts */
	public function getUserPosts($user_id, $offset, $num)
	{
		if (Auth::check() && Auth::user()->id == $user_id)
		{
			return Post::with('post_type')
					->where('user_id', '=', $user_id)
					->orderBy('created_at', 'desc')
					->skip($offset)
					->take($num)
					->get();
		}
		else
		{
			return Post::with('post_type')
					->where('approved', '=', true)
					->where('user_id', '=', $user_id)
					->orderBy('created_at', 'desc')
					->skip($offset)
					->take($num)
					->get();
		}
	}

	/* returns paginated set of a user's favorite posts */
	public function getUserFavorites($user_id, $offset, $num)
	{
		if (Auth::check() && Auth::user()->id == $user_id)
		{
			return Favorite::with('post.post_type')
					->where('user_id', '=', $user_id)
					->orderBy('created_at', 'desc')
					->take($num)
					->skip($offset)
					->get();
		}
	}

	/* returns paginated set of the approval queue */
	public function getApprovalQueue($user_id, $offset, $num)
	{
		if (Auth::check() && Auth::user()->is_moderator)
		{
			$posts = Post::with('user', 'post_type')
							->where('approved', '=', false)
							->orderBy('created_at', 'desc')
							->take($num)
							->skip($offset)
							->get();

			//for each post in approval queue, retrieve any tags that are currently unapproved to display in list
			if (count($posts) > 0)
			{
				foreach($posts as $key => $post) {
					$posts[$key]['unapproved_tags'] = TagPost::join('tags', 'tags.id', '=', 'tag_posts.tag_id')
							->with('tag.tag_category')
							->where('tags.pending', '=', 1)
							->where('post_id', '=', $post->id)
							->get();
				}
			}

			return $posts;
		}
	}

	public function postRequestModeratorRole()
	{
		if (Auth::check() && Auth::user()->moderator_requested != -1)
		{
			Auth::user()->moderator_requested = 1;
			Auth::user()->save();
		}
	}

	public function postUploadPhoto()
	{
		try {
		    // Trying upload
		    $result = Bookcase::upload(array(
	            'input' => 'file',
	            'libraryPath' => 'public/user_photos/'
	        ));

	        list($width, $height) = getimagesize($result->path);
			$theimage = imagecreatefromjpeg($result->path);

			if ($width > $height) {
				$y = 0;
				$x = ($width - $height) / 2;
				$smallestSide = $height;
			} else {
				$x = 0;
				$y = ($height - $width) / 2;
				$smallestSide = $width;
			}

			$thumbSizeBig = 500;
			if ($smallestSide < $thumbSizeBig)
			{
				$thumbSizeBig = $smallestSide;
			}

			$thumbnailBig = imagecreatetruecolor($thumbSizeBig, $thumbSizeBig);
			imagecopyresampled($thumbnailBig, $theimage, 0, 0, $x, $y, $thumbSizeBig, $thumbSizeBig, $smallestSide, $smallestSide);
			
			$uploaded_image_path = str_replace('../public/user_photos/', '', $result->path);

			$substr_image_path = str_replace('.jpg', '', $uploaded_image_path);

			//final output
			header('Content-type: image/jpeg');
			imagejpeg($thumbnailBig, '../public/user_photos/'.$substr_image_path.'_thumb.jpg');

			$paths = array(
				'path' => $uploaded_image_path,
				'thumbnail' => $substr_image_path.'_thumb.jpg'
			);

			return json_encode($paths);
		} catch (Exception $e) {
		    return 'Error: ' . $e->getMessage();
		}
	}

}