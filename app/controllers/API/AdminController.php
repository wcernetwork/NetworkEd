<?php

/**
** Used for actions in the admin panel
**/

class AdminController extends Controller {

	public function getData()
	{
		$adminData = array();

		$adminData['approval_queue'] = Post::with('user', 'post_type')
							->where('approved', '=', false)
							->orderBy('created_at', 'desc')
							->take(15)
							->get();
		//get total count of posts in approval queue
		$adminData['approval_queue_count'] = Post::where('approved', '=', false)->count();
		//for each post in approval queue, retrieve any tags that are currently unapproved to display in list
		if (count($adminData['approval_queue']) > 0)
		{
			foreach($adminData['approval_queue'] as $key => $post) {
				$adminData['approval_queue'][$key]['unapproved_tags'] = TagPost::join('tags', 'tags.id', '=', 'tag_posts.tag_id')
						->with('tag.tag_category')
						->where('tags.pending', '=', 1)
						->where('post_id', '=', $post->id)
						->get();
			}
		}

		$adminData['collections'] = Collection::get();

		$adminData['moderator_queue'] = User::where('moderator_requested', '=', 1)->get();
		foreach($adminData['moderator_queue'] as $index=>$mod)
		{
			$adminData['moderator_queue'][$index]->num_posts = $mod->posts()->count();

			//unserialize roles array (for backwards compatibility)
			$serialized = @unserialize($adminData['moderator_queue'][$index]->role_title);
			if ($serialized !== false) {
				$adminData['moderator_queue'][$index]->role_title = $serialized;
			}
		}

		$adminData['total_num_moderators'] = User::where('role_id', '=', 2)->get()->count();
		$adminData['moderator_list'] = User::where('role_id', '=', 2)
									->orderBy('first_name', 'asc')
									->orderBy('last_name', 'asc')
									->get();
		foreach($adminData['moderator_list'] as $index=>$a_user)
		{
			$adminData['moderator_list'][$index]->num_posts = $a_user->posts()->count();

			//unserialize roles array (for backwards compatibility)
			$serialized = @unserialize($adminData['moderator_list'][$index]->role_title);
			if ($serialized !== false) {
				$adminData['moderator_list'][$index]->role_title = $serialized;
			}
		}

		$adminData['total_num_users'] = User::get()->count();
		$adminData['user_list'] = User::take(15)
									->orderBy('first_name', 'asc')
									->orderBy('last_name', 'asc')
									->get();
		foreach($adminData['user_list'] as $index=>$a_user)
		{
			$adminData['user_list'][$index]->num_posts = $a_user->posts()->count();

			//unserialize roles array (for backwards compatibility)
			$serialized = @unserialize($adminData['user_list'][$index]->role_title);
			if ($serialized !== false) {
				$adminData['user_list'][$index]->role_title = $serialized;
			}
		}

		$adminData['about_content'] = StaticContent::where('name', '=', 'about')->first()['content'];
		$adminData['fellows_content'] = StaticContent::where('name', '=', 'fellows')->first()['content'];

		//get tags
		$categories = TagCategory::whereNotIn('name', ['Format'])->orderBy('order')->get();
		$tags = array();
		foreach ($categories as $cat_index=>$category)
		{
			$tags[$cat_index] = array(
					'category' => $category,
					'tags' => Tag::where('category_id', '=', $category->id)->orderby('name')->get()
				);

			foreach($tags as $index=>$tagArray)
			{
				foreach($tagArray['tags'] as $index2=>$tag)
				{
					$tags[$index]['tags'][$index2]['count'] = TagPost::selectRaw('count(*) as count')
							->where('tag_id', '=', $tag->id)
							->first();
				}
			}
		}
		$adminData['tags'] = $tags;

		//get tags waiting for approval
		$tag_queue = Tag::with('tag_category')->where('pending', '=', 1)->orderby('name')->get();
		foreach($tag_queue as $index=>$tag)
		{
			$tag_queue[$index]['count'] = TagPost::selectRaw('count(*) as count')->where('tag_id', '=', $tag->id)->first();
		}
		$adminData['tag_queue'] = $tag_queue;

		return $adminData;
	}

	public function getUserList($offset, $num)
	{
		$validator = Validator::make(
			array('offset' => $offset, 'num' => $num),
			array('offset' => array('integer'), 'num' => array('integer'))
		);
		if ($validator->fails())
		{
			return array();
		}

		$user_list = User::take($num)
						->skip($offset)
						->orderBy('first_name', 'asc')
						->orderBy('last_name', 'asc')
						->get();

		foreach($user_list as $index=>$a_user)
		{
			$user_list[$index]->num_posts = $a_user->posts()->count();

			//unserialize roles array (for backwards compatibility)
			$serialized = @unserialize($user_list[$index]->role_title);
			if ($serialized !== false) {
				$user_list[$index]->role_title = $serialized;
			}
		}

		return $user_list;
	}

	/* returns paginated set of the approval queue */
	public function getApprovalQueue($offset, $num)
	{
		$validator = Validator::make(
			array('offset' => $offset, 'num' => $num),
			array('offset' => array('integer'), 'num' => array('integer'))
		);
		if ($validator->fails())
		{
			return array();
		}

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

	public function postSaveStaticContent()
	{
		$page = Input::get('page');
		$content = Input::get('content');

		$static_content = StaticContent::where('name', '=', $page)->first();
		if ($static_content)
		{
			$static_content->content = $content;
			$static_content->save();
		}
	}

	//upload an image
	public function postUploadStaticContentMedia()
	{
		try {
		    // Trying upload
		    $result = Bookcase::upload(array(
	            'input' => 'file',
	            'libraryPath' => 'public/uploads/'
	        ));

	        $uploaded_image_path = str_replace('../public/uploads/', '', $result->path);
			$substr_image_path = str_replace('.jpg', '', $uploaded_image_path);

			$thumbs = Image::makeThumbs($result->path, '../public/uploads/', $substr_image_path);

			return $thumbs['big'];

		} catch (Exception $e) {
		    return 500;
		}
	}

	public function postSaveCollection()
	{
		$input = Input::get('collection');

		if ($input)
		{
			//create the slug
			$input['name'] = trim($input['name']);
			$slug = preg_replace('/[^\p{L}\p{Nd}]+/u', '-', $input['name']);
			$slug = mb_strtolower($slug, 'UTF-8');

			//save the data
			if (isset($input['id']) && $input['id'] != '')
			{
				//check that another collection doesn't already exist with this name
				$existingCollection = Collection::where('slug', '=', $slug)->whereNotIn('id', [$input['id']])->first();
				if (!is_null($existingCollection))
				{
					return '409';
				}

				$collection = Collection::find($input['id']);
				if ($collection)
				{
					$collection->name = $input['name'];
					$collection->active = $input['activeVal'];
					$collection->thumbnail = $input['thumbnail'];
					$collection->slug = $slug;
					$collection->save();
					return $collection;
				}
			}
			else
			{
				//check that another collection doesn't already exist with this name
				$existingCollection = Collection::where('slug', '=', $slug)->first();
				if (!is_null($existingCollection))
				{
					return '409';
				}

				//make new collection
				$collection = Collection::create(
					array(
						'name' => $input['name'],
						'active' => $input['activeVal'],
						'thumbnail' => $input['thumbnail'],
						'slug' => $slug
					));
				return $collection;
			}
		}
	}

	//batch add
	public function postBatchAddToCollection()
	{
		$collection_slug = Input::get('collection_slug');
		$collection_name = Input::get('collection_name');
		$posts = Input::get('posts');

		$collection = Collection::where('slug', '=', $collection_slug)->first();

		if ($collection)
		{
			foreach ($posts as $post_id)
			{
				//check if any of these posts already exist in the collection
				$existingPost = CollectionPost::where('post_id', '=', $post_id)->where('collection_id', '=', $collection->id)->first();

				//only add if doesn't exist already
				if (is_null($existingPost))
				{
					CollectionPost::create(
						array(
							'post_id' => $post_id,
							'collection_id' => $collection->id,
							'order' => 0,
						));
				}
			}
		}
	}

	public function getAllUsers()
	{
		return User::orderBy('first_name', 'asc')
					->orderBy('last_name', 'asc')
					->get(['id', 'email', 'first_name', 'last_name', 'city', 'state', 'bio', 'organization']);
	}

	public function postUpdatePostOwner()
	{
		$post_id = Input::get('post_id');
		$user_id = Input::get('user_id');

		$validator = Validator::make(
			array('post' => $post_id, 'user' => $user_id),
			array('post' => array('required', 'integer'), 'user' => array('required', 'integer'))
		);
		if ($validator->fails())
		{
			return;
		}

		$post = Post::find($post_id);
		$user = User::find($user_id);

		if ($post && $user)
		{
			$post->user_id = $user_id;
			$post->save();

			//only send the email if their email is validated
			if ($user->confirmed)
			{
				Mail::queue('emails.posts.owner', array('postid'=>$post->id, 'title'=>$post->title, 'firstname'=>$user->first_name), function($message) use($user) {
				        $message->to($user->email, $user->first_name.' '.$user->last_name)->subject('NetworkEd Post Ownership');
				    });
			}

			return Post::with(
				array(
					'user'=>function($query){
						$query->select('id', 'first_name', 'last_name', 'city', 'state', 'role_title', 'organization', 'photo');
					}
				))->find($post_id);
		}
	}

	public function postDeleteUser()
	{
		$user_id = Input::get('id');
		$admin_user = Auth::user()->id;

		$validator = Validator::make(
			array('user' => $user_id),
			array('user' => array('required', 'integer'))
		);
		if ($validator->fails())
		{
			return 500;
		}

		//only allow deleting users who are not admins
		$delete_user = User::find($user_id);
		if (!$delete_user->is_admin)
		{
			//reassign all posts to the admin removing the user
			Post::where('user_id', '=', $user_id)->update(array('user_id' => $admin_user));

			Favorite::where('user_id', '=', $user_id)->forceDelete();

			Like::where('user_id', '=', $user_id)->forceDelete();

			User::find($user_id)->delete();
		}
		else
		{
			return 500;
		}
	}

	public function postMakeModerator()
	{
		$user_id = Input::get('id');

		$validator = Validator::make(
			array('user' => $user_id),
			array('user' => array('required', 'integer'))
		);
		if ($validator->fails())
		{
			return 500;
		}

		$user = User::find($user_id);
		if (!$user->is_moderator && !$user->is_admin)
		{
			$user->role_id = 2;
			$user->save();

			Mail::queue('emails.auth.moderator', array('firstname'=>$user->first_name), function($message) use($user) {
			        $message->to($user->email, $user->first_name.' '.$user->last_name)->subject('You\'re Now a Moderator on NetworkEd');
			    });
		}
		else
		{
			return 500;
		}
	}

	public function postRemoveModerator()
	{
		$user_id = Input::get('id');

		$validator = Validator::make(
			array('user' => $user_id),
			array('user' => array('required', 'integer'))
		);
		if ($validator->fails())
		{
			return 500;
		}

		User::find($user_id)->update(array('role_id' => 3));
	}

	public function postResendUserCode()
	{
		$user_id = Input::get('id');

		$validator = Validator::make(
			array('user' => $user_id),
			array('user' => array('required', 'integer'))
		);
		if ($validator->fails())
		{
			return 500;
		}

		$user = User::find($user_id);

		if ($user)
		{
			Mail::queue('emails.auth.confirmation', array('firstname'=>$user->first_name,'confirmation_code'=>$user->confirmation_code), function($message) use($user)
			{
		        $message->to(strtolower($user->email), $user->first_name.' '.$user->last_name)->subject('NetworkEd: Verify your account');
		    });
		}
	}

	public function postRenameCategory()
	{
		$category_id = Input::get('id');
		$new_name = trim(Input::get('new'));
		$new_slug = TagUtil::slug($new_name);

		$validator = Validator::make(
			array('category' => $category_id),
			array('category' => array('required', 'integer'))
		);
		if ($validator->fails())
		{
			return 500;
		}

		if (TagCategory::where('slug', '=', $new_slug)
				->whereNotIn('id', [$category_id])
				->first())
		{
			//category already exists with this name
			return 500;
		}
		else
		{
			$category = TagCategory::find($category_id)->update(array('slug'=>$new_slug, 'name'=>$new_name));
		}
	}

	public function postMoveTag()
	{
		$tag_id = Input::get('tag_id');
		$category_id = Input::get('category_id');

		$validator = Validator::make(
			array('tag' => $tag_id, 'category' => $category_id),
			array('tag' => array('required', 'integer'), 'category' => array('required', 'integer'))
		);
		if ($validator->fails())
		{
			return '400';
		}

		$tag = Tag::find($tag_id);
		$category = TagCategory::find($category_id);

		if($tag && $category){
			$tag->category_id = $category_id;
			$tag->save();

			return '200';
		}
		else{
			return '400';
		}
	}

	public function postDeleteTag()
	{
		$id = Input::get('id');

		$validator = Validator::make(
			array('id' => $id),
			array('id' => array('required', 'integer'))
		);
		if ($validator->fails())
		{
			return 500;
		}

		TagPost::where('tag_id', '=', $id)->delete();

		Tag::find($id)->delete();
	}

	public function postRenameTag()
	{
		$id = Input::get('id');

		$validator = Validator::make(
			array('id' => $id),
			array('id' => array('required', 'integer'))
		);
		if ($validator->fails())
		{
			return 500;
		}

		$newName = trim(Input::get('newName'));
		$newSlug = TagUtil::slug($newName);

		$search_tag = Tag::with('tag_category')->where('slug', '=', $newSlug)
				->whereNotIn('id', [$id])
				->first();

		if ($search_tag)
		{
			//tag already exists with this name
			return array(
					'response' => 500,
					'tag' => $search_tag
				);
		}
		else
		{
			$tag = Tag::find($id)->update(array('slug'=>$newSlug, 'name'=>$newName));
		}
	}

	public function postAddNewTag()
	{
		$category_id = Input::get('id');

		$validator = Validator::make(
			array('category' => $category_id),
			array('category' => array('required', 'integer'))
		);
		if ($validator->fails())
		{
			return 500;
		}

		$new_tag = trim(Input::get('name'));
		$new_slug = TagUtil::slug($new_tag);

		$category = TagCategory::find($category_id);

		$search_tag = Tag::with('tag_category')->where('slug', '=', $new_slug)->first();

		if (!$category || $search_tag)
		{
			//tag already exists with this name
			return array(
					'response' => 500,
					'tag' => $search_tag
				);
		}
		else
		{
			$tag = Tag::create(
				array(
					'category_id' => $category->id,
					'name' => $new_tag,
					'slug' => $new_slug,
					'pending' => 0
				));

			return $tag;
		}
	}

	public function postApproveTag()
	{
		$id = Input::get('id');

		$validator = Validator::make(
			array('id' => $id),
			array('id' => array('required', 'integer'))
		);
		if ($validator->fails())
		{
			return 500;
		}

		Tag::find($id)->update(array('pending'=>0));
	}

	public function postAddToCollection()
	{
		$collection_slug = Input::get('collection_slug');
		$collection_name = Input::get('collection_name');
		$post_id = Input::get('id');

		$validator = Validator::make(
			array('post' => $post_id),
			array('post' => array('required', 'integer'))
		);
		if ($validator->fails())
		{
			return 500;
		}

		$collection = Collection::where('slug', '=', $collection_slug)->first();

		if ($collection)
		{
			//check that this post doesn't already exist in the collection
			$existingPost = CollectionPost::where('post_id', '=', $post_id)->where('collection_id', '=', $collection->id)->first();
			if (!is_null($existingPost))
			{
				return '409';
			}

			CollectionPost::create(
				array(
					'post_id' => $post_id,
					'collection_id' => $collection->id,
					'order' => 0,
				));
		}
	}

	public function postRemovePostFromCollection()
	{
		$post_id = Input::get('post_id');
		$collection_id = Input::get('collection_id');

		$validator = Validator::make(
			array('post' => $post_id, 'collection' => $collection_id),
			array('post' => array('required', 'integer'), 'collection' => array('required', 'integer'))
		);
		if ($validator->fails())
		{
			return 500;
		}

		CollectionPost::where('collection_id', '=', $collection_id)
					->where('post_id', '=', $post_id)
					->delete();
	}

	public function postDeleteCollection()
	{
		$id = Input::get('id');

		$validator = Validator::make(
			array('id' => $id),
			array('id' => array('required', 'integer'))
		);
		if ($validator->fails())
		{
			return 500;
		}

		//first remove all posts in the collection
		$collectionPosts = CollectionPost::where('collection_id', '=', $id)->delete();

		//then delete the collection
		$collection = Collection::find($id);
		$collection->delete();

	}

	public function postApproveModerator()
	{
		$id = Input::get('id');

		$validator = Validator::make(
			array('id' => $id),
			array('id' => array('required', 'integer'))
		);
		if ($validator->fails())
		{
			return 500;
		}

		$user = User::find($id);
		$user->moderator_requested = 0;
		$user->role_id = 2;
		$user->save();

		Mail::queue('emails.auth.moderator', array('firstname'=>$user->first_name), function($message) use($user) {
		        $message->to($user->email, $user->first_name.' '.$user->last_name)->subject('You\'re Now a Moderator on NetworkEd');
		    });
	}

	public function postRejectModerator()
	{
		$id = Input::get('id');

		$validator = Validator::make(
			array('id' => $id),
			array('id' => array('required', 'integer'))
		);
		if ($validator->fails())
		{
			return 500;
		}

		$user = User::find($id);
		$user->moderator_requested = -1;
		$user->save();
	}

	public function postUploadCollectionImage()
	{
		try {
		    // Trying upload
		    $result = Bookcase::upload(array(
	            'input' => 'file',
	            'libraryPath' => 'public/uploads/collections/'
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
			
			$uploaded_image_path = str_replace('../public/uploads/collections/', '', $result->path);

			$substr_image_path = str_replace('.jpg', '', $uploaded_image_path);

			//final output
			header('Content-type: image/jpeg');
			imagejpeg($thumbnailBig, '../public/uploads/collections/'.$substr_image_path.'_thumb.jpg');

			//only use the thumb, so delete the original upload
			unlink($result->path);

			$paths = array(
				'thumbnail' => $substr_image_path.'_thumb.jpg'
			);

			return json_encode($paths);
		} catch (Exception $e) {
		    return 'Error: ' . $e->getMessage();
		}
	}



}