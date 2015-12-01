<?php

class SearchController extends Controller {

	//this encompasses both normal search and search on collections
	public function postGetPosts()
	{
		$search_term = Input::get('search_term');
		$search_values = preg_split('/\s+/', $search_term);

		$sort = Input::get('sort');
		$latitude = Input::get('latitude');
		$longitude = Input::get('longitude');

		$type = Input::get('type');
		$tags = Input::get('tags');

		$is_collection = Input::get('isCollection');
		$collection_slug = Input::get('collection');

		if ($is_collection)
		{
			$the_posts = Collection::with(['posts' => function ($q) use ($search_values, $sort, $latitude, $longitude) {
					$q->with('post_type')
						->where('approved', '=', true)
						->join('users', 'posts.user_id', '=', 'users.id');
					
					$select_statement = 'posts.approved, posts.created_at, posts.updated_at, posts.deleted_at, posts.expiration_date, posts.id, posts.latitude, posts.longitude, posts.title, posts.likes, posts.user_id, posts.thumbnail_sm, posts.post_type_id, posts.city, posts.state, posts.contact_name, posts.contact_email, posts.contact_phone, posts.contact_website, posts.summary';

					foreach ($search_values as $value)
					{
						$q = $q->where(function ($query) use ($value) {
							$query->where('posts.title', 'LIKE', '%'.$value.'%');
							$query->orWhere('posts.description', 'LIKE', '%'.$value.'%');
							$query->orWhere('posts.location', 'LIKE', '%'.$value.'%');
							$query->orWhere('posts.address', 'LIKE', '%'.$value.'%');
							$query->orWhere('posts.city', 'LIKE', '%'.$value.'%');
							$query->orWhere('posts.state', 'LIKE', '%'.$value.'%');
							$query->orWhere('posts.summary', 'LIKE', '%'.$value.'%');
							$query->orWhere('users.first_name', 'LIKE', '%'.$value.'%');
							$query->orWhere('users.last_name', 'LIKE', '%'.$value.'%');
						});
					}

					//do sort
					if ($sort == 'nearest' || $sort == '')
					{
						$q = $q->orderBy('distance');

						//add on to the select
						$select_statement .= ', 6371 * acos( cos( radians('.$latitude.') ) * cos( radians( posts.latitude ) ) * cos( radians( posts.longitude ) - radians('.$longitude.') ) + sin( radians('.$latitude.') ) * sin( radians( posts.latitude ) ) )  AS distance';
					}
					if ($sort == 'recent')
					{
						$q = $q->orderBy('updated_at', 'desc');
					}
					else if ($sort == 'helpful')
					{
						$q = $q->orderBy('likes', 'desc');
					}

					//add on the select statement
					$q = $q->select(DB::raw($select_statement));
				}])->where('slug', '=', $collection_slug)
			->first()->posts;
		}
		else
		{
			//set up the base query
			$full_query = Post::with('post_type')
								->join('users', 'posts.user_id', '=', 'users.id')
								->where('approved', '=', true)
								;

			//set up the select (which may be appended to later)
			$select_statement = 'posts.approved, posts.created_at, posts.updated_at, posts.deleted_at, posts.expiration_date, posts.id, posts.latitude, posts.longitude, posts.title, posts.likes, posts.user_id, posts.thumbnail_sm, posts.post_type_id, posts.city, posts.state, posts.contact_name, posts.contact_email, posts.contact_phone, posts.contact_website, posts.summary';

			//limit to certain post type
			if ($type != '')
			{
				//if event, also get network events (type 5)
				if ($type == 3)
				{
					$full_query = $full_query->whereIn('post_type_id', array(3, 5));
				}
				else
				{
					$full_query = $full_query->where('post_type_id', '=', $type);
				}
			}

			//search by tags selected
			if ($tags && count($tags))
			{
				$tagNames = array();

				foreach ($tags as $category => $list)
				{
					foreach ($list as $tag)
					{
						$full_query = $full_query->whereHas('tags', function($q) use($tag) {
							$q->where('tag_id', '=', $tag['id']);
						});
					}
				}
			}

			//search on all text values entered as search
			foreach ($search_values as $value)
			{
				$full_query = $full_query->where(function ($query) use ($value) {
					$query->where('posts.title', 'LIKE', '%'.$value.'%');
					$query->orWhere('posts.description', 'LIKE', '%'.$value.'%');
					$query->orWhere('posts.location', 'LIKE', '%'.$value.'%');
					$query->orWhere('posts.address', 'LIKE', '%'.$value.'%');
					$query->orWhere('posts.city', 'LIKE', '%'.$value.'%');
					$query->orWhere('posts.state', 'LIKE', '%'.$value.'%');
					$query->orWhere('posts.summary', 'LIKE', '%'.$value.'%');
					$query->orWhere('users.first_name', 'LIKE', '%'.$value.'%');
					$query->orWhere('users.last_name', 'LIKE', '%'.$value.'%');
				});
			}
			//do sort
			if ($sort == 'nearest' || $sort == '')
			{
				$full_query = $full_query->orderBy('distance');

				//add on to the select
				$select_statement .= ', 6371 * acos( cos( radians('.$latitude.') ) * cos( radians( posts.latitude ) ) * cos( radians( posts.longitude ) - radians('.$longitude.') ) + sin( radians('.$latitude.') ) * sin( radians( posts.latitude ) ) )  AS distance';
			}
			if ($sort == 'recent')
			{
				$full_query = $full_query->orderBy('updated_at', 'desc');
			}
			else if ($sort == 'helpful')
			{
				$full_query = $full_query->orderBy('likes', 'desc');
			}

			//add on the select statement
			$full_query = $full_query->select(DB::raw($select_statement));

			//get the results
			$the_posts = $full_query->get();
		}

		//send back array of all tags that are still enabled
		$enabled_tags = array();
		if (count($the_posts) > 0)
		{
			$post_ids = array();
			//grab all the post ids
			foreach ($the_posts as $post)
			{
				$post_ids[] = $post->id;
			}

			$post_tags = TagPost::whereIn('post_id', $post_ids)->groupBy('tag_id')->get();
			$enabled_tags_by_cat_id = array();
			foreach($post_tags as $post_tag)
			{
				$tag_by_id = Tag::find($post_tag->tag_id);

				$enabled_tags_by_cat_id[$tag_by_id->category_id][] = $tag_by_id->slug;
			}
			foreach($enabled_tags_by_cat_id as $cat_id => $enabled_tag)
			{
				$cat_name = TagCategory::where('id', '=', $cat_id)->first()->name;

				$enabled_tags[$cat_name] = $enabled_tags_by_cat_id[$cat_id];
			}
		}

		$posts['posts'] = $the_posts;
		$posts['count'] = count($the_posts);
		$posts['tags'] = $enabled_tags;

		return $posts;
	}

	public function getActiveCollections()
	{
		return Collection::where('active', '=', 1)->get();
	}

	public function getAllCollections()
	{
		return Collection::get();
	}

	public function getPostsCollection($collection_name)
	{
		$collection = array();

		$collection = Collection::with(['posts' => function ($q) {
			$q->with('post_type')
				->where('approved', '=', true)
				->orderBy('title', 'asc');
		}])->where('slug', '=', $collection_name)
			->first();

		if (!is_null($collection))
		{
			$collection['count'] = count($collection['posts']);
		}

		return $collection;
	}

	public function getNetworkEvents()
	{
		$events = array();

		//first get current events
		$events['posts'] = Post::with('post_type')
						->where('approved', '=', true)
						->where('post_type_id', '=', '5')
						->whereRaw('DATE(`expiration_date`) >= CURDATE()')
						->orderBy('expiration_date', 'asc')
						->get();
		//strip all html from the post description
		foreach ($events['posts'] as $index=>$post)
		{
			$events['posts'][$index]->description = strip_tags($events['posts'][$index]->description);
		}

		//then get archived events
		$archived = Post::with('post_type')
						->where('approved', '=', true)
						->where('post_type_id', '=', '5')
						->whereRaw('DATE(`expiration_date`) < CURDATE()')
						->orderBy('expiration_date', 'desc')
						->get();
		//add archived events to the posts to send back
		foreach ($archived as $index=>$archive)
		{
			$archived[$index]->archived = true;
			$archived[$index]->description = strip_tags($archived[$index]->description);
			$events['posts'][] = $archive;
		}		

		if (!is_null($events))
		{
			$events['count'] = count($events['posts']);
		}

		return $events;
	}

	public function getPostsWithinRadius($latitude, $longitude, $radius)
	{
		$latitude = floatval($latitude);
		$longitude = floatval($longitude);
		$radius = intval($radius);

		$posts['posts'] = Post::with('post_type')
						->where('approved', '=', true)
						->select(DB::raw('posts.id, posts.latitude, posts.longitude, posts.title, posts.post_type_id, 6371 * acos( cos( radians('.$latitude.') ) * cos( radians( posts.latitude ) ) * cos( radians( posts.longitude ) - radians('.$longitude.') ) + sin( radians('.$latitude.') ) * sin( radians( posts.latitude ) ) )  AS distance'))
						->having('distance', '<', $radius)
						->orderBy('distance')
						->get();
		$posts['count'] = count($posts['posts']);

		return $posts;
	}

	public function getTags()
	{
		$categories = TagCategory::orderby('order')->get();

		$tags = array();
		foreach ($categories as $category)
		{
			$tags[$category->name] = Tag::where('category_id', '=', $category->id)
					->where('pending', '=', 0)
					->orderby('name')
					->get(array('id', 'name', 'slug', 'category_id'));
		}

		return $tags;
	}
}