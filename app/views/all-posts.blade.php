<?php

	$take = 25;

	if(Auth::check() && Auth::user()->is_admin){
		$search_terms = preg_split('/\s+/', $search);

		$select_statement = 'posts.approved, posts.created_at, posts.updated_at, posts.last_viewed_at, posts.deleted_at, posts.expiration_date, posts.id, posts.description, posts.title, posts.likes, posts.num_views, posts.user_id, posts.thumbnail, posts.post_type_id, posts.city, posts.state';

		$query = Post::with('post_type', 'favorited')
						->join('users', 'posts.user_id', '=', 'users.id')		
						->where('approved', '=', true);
		if ($search_terms){
			foreach($search_terms as $value) {
				$query = $query->where(function($q) use($value){
					$q->where('posts.title', 'LIKE', '%'.$value.'%');
					$q->orWhere('posts.description', 'LIKE', '%'.$value.'%');
					$q->orWhere('posts.location', 'LIKE', '%'.$value.'%');
					$q->orWhere('posts.address', 'LIKE', '%'.$value.'%');
					$q->orWhere('posts.city', 'LIKE', '%'.$value.'%');
					$q->orWhere('posts.state', 'LIKE', '%'.$value.'%');
					$q->orWhere('users.first_name', 'LIKE', '%'.$value.'%');
					$q->orWhere('users.last_name', 'LIKE', '%'.$value.'%');
				});
			}
		}

		if($sort == 'likes'){
			$query = $query->orderBy('posts.likes', 'desc');
		}
		else if($sort == 'favorites'){
			$select_statement .= ', favorites.post_id, count(favorites.post_id) as aggregate';
			$query = $query->leftJoin('favorites', 'posts.id', '=', 'favorites.post_id')
							->groupby('posts.id')
							->orderby('aggregate', 'desc');
		}
		else if($sort=='views'){
			$query = $query->orderBy('posts.num_views', 'desc');
		}
		else if($sort=='lastviewed'){
			$query = $query->orderBy('posts.last_viewed_at', 'desc');
		}
		else{
			$query = $query->orderBy('posts.updated_at', 'desc');
		}

		$query = $query->select(DB::raw($select_statement));

		//get total pre-paginated count
		$num_all_posts = $query->count();

		//get the paginated results
		$query = $query->take($take)
					   ->skip($offset);
	    $paginated_posts = $query->get();
	}
	else{
		$num_all_posts = Post::where('approved', '=', true)->count();

		$paginated_posts = Post::with('post_type', 'favorited')
								->where('approved', '=', true)
								->take($take)
								->skip($offset)
								->orderBy('updated_at', 'desc')
								->get();
	}

	function constructUrl($offset, $sort, $search){
		if (!Auth::check() || (Auth::check() && !Auth::user()->is_admin)){
			return route('posts', [$offset]);
		}
		else if($search){
			if(!$sort){
				$sort = 'recent';
			}
			return route('posts', [$offset, $sort, $search]);
		}
		else if($sort){
			return route('posts', [$offset, $sort]);
		}
		else{
			return route('posts', [$offset]);
		}
	}
?>

@include('includes.head')

	@include('includes.navbar')

	<div class="container" style="padding: 0px 50px; background-color: #fdfdfd;">
		<h1>All NetworkEd Posts</h1>
		<p class="caps">A directory of innovation in education in Wisconsin and beyond</p>

		<p class="text-left">Welcome to NetworkEd! Explore the site, connect with education enthusiasts, and contribute to this ever growing directory of education innovations in Wisconsin and beyond. Go ahead, look through the list of posts below, or just search for a person, location, or area of interest in the search bar.</p>

		<br />

		<div class="row">
			@if ($offset > 0)
			<a href="<?php echo constructUrl(0, $sort, $search); ?>" class="btn btn-sm btn-red pull-left"><<</a> 
			<a href="<?php echo constructUrl($offset-$take, $sort, $search); ?>" class="btn btn-sm btn-red pull-left"><</a>
			@endif

			Page: <?php echo floor($offset/$take)+1; ?> | Total Posts: <?php echo $num_all_posts; ?>

			@if ($offset+$take < $num_all_posts) 
			<a href="<?php echo constructUrl(($take*floor($num_all_posts/$take)-$take), $sort, $search); ?>" class="btn btn-sm btn-red pull-right">>></a> 
			<a href="<?php echo constructUrl($offset+$take, $sort, $search); ?>" class="btn btn-sm btn-red pull-right">></a>
			@endif
			<div class="clearfix"></div>
		</div>
		<hr />

		@if(Auth::check() && Auth::user()->is_admin)
		<div class="row">
			<div class="col-sm-6">
				<form ng-submit="allPostsSearch(<?php echo $offset; ?>, '<?php echo $sort; ?>')">
					<div class="input-group">
						<input class="form-control" ng-model="allPostsSearchTerm" type="text" placeholder="Search" />
						<span class="input-group-btn">
				        	<button class="btn" type="submit" style="height: 43px;"><i class="fa fa-search" title="Search"></i></button>
				    	</span>
					</div>
				</form>
			</div>
			<div class="col-sm-6 text-right">
				<strong class="caps">Sort:</strong> 
				<a href="<?php echo constructUrl(0, 'recent', $search); ?>"<?php if($sort == 'recent' || !$sort) echo 'class="black bold"'; ?>>Most Recent</a> | 
				<a href="<?php echo constructUrl(0, 'lastviewed', $search); ?>"<?php if($sort == 'lastviewed') echo 'class="black bold"'; ?>>Last Viewed</a> | 
				<a href="<?php echo constructUrl(0, 'likes', $search); ?>"<?php if($sort == 'likes') echo 'class="black bold"'; ?>>Likes</a> | 
				<a href="<?php echo constructUrl(0, 'views', $search); ?>"<?php if($sort == 'views') echo 'class="black bold"'; ?>>Views</a> | 
				<a href="<?php echo constructUrl(0, 'favorites', $search); ?>"<?php if($sort == 'favorites') echo 'class="black bold"'; ?>>Favorites</a>
			</div>
		</div>
		<hr />
		@endif

		<?php
			foreach($paginated_posts as $post){
		?>

		<div class="row text-left">
			<div class="black row-fluid">
				<div class="col-sm-3 col-xs-4">
					<div class="info-window-image" ng-style="{'background-image':'url(<?php echo asset('uploads/'.$post->thumbnail); ?>)'}"></div>
					<br />
					<div class="post-type-icon center">
						<img src="<?php echo asset('assets/images/icons/'.$post->post_type->icon_file.'.png'); ?>" /> &nbsp;
						<span class="cap-emphasis red"><?php echo $post->post_type->name; ?></span>
					</div>
				</div>
				<div class="col-sm-9 col-xs-8" title="<?php echo $post->title; ?>">
					<h2><a href="<?php echo route('post', $post->id); ?>" target="_blank" class="black row-fluid"><?php echo $post->title; ?></a></h2>
					<strong>Author:</strong> <a href="<?php echo route('profile', $post->user->id); ?>"><?php echo $post->user->first_name; ?> <?php echo $post->user->last_name; ?></a><br />
					<strong>Location:</strong> <?php echo $post->city; ?>, <?php echo $post->state; ?><br />
					<strong>Description:</strong> <?php echo substr(strip_tags($post->description), 0, 300); ?><?php echo strlen(strip_tags($post->description)) > 200 ? '...' : ''; ?>
					@if(Auth::check() && Auth::user()->is_admin)
					<br /><br />
					<strong>Statistics</strong><br />
					<table class="table table-bordered table-condensed">
						<tr>
							<td><strong>Likes</strong></td>
							<td><?php echo $post->likes; ?></td>
						</tr>
						<tr>
							<td><strong>Favorites</strong></td>
							<td><?php echo count($post->favorited); ?></td>
						</tr>
						<tr>
							<td><strong>Views</strong></td>
							<td><?php echo $post->num_views; ?></td>
						</tr>
						<tr>
							<td><strong>Last Viewed</strong></td>
							<td><?php if ($post->last_viewed_at != '0000-00-00 00:00:00') { echo date('M j, Y', strtotime($post->last_viewed_at)); } ?></td>
						</tr>
						<tr>
							<td><strong>Last Updated</strong></td>
							<td><?php if ($post->updated_at != '0000-00-00 00:00:00') { echo date('M j, Y', strtotime($post->updated_at)); } ?></td>
						</tr>
					</table>
					@endif
				</div>
			</div>
		</div>
		<hr />

		<?php
			}
		?>

		<div class="row">
			@if ($offset > 0)
			<a href="<?php echo constructUrl(0, $sort, $search); ?>" class="btn btn-sm btn-red pull-left"><<</a> 
			<a href="<?php echo constructUrl($offset-$take, $sort, $search); ?>" class="btn btn-sm btn-red pull-left"><</a>
			@endif

			Page: <?php echo floor($offset/$take)+1; ?> | Total Posts: <?php echo $num_all_posts; ?>

			@if ($offset+$take < $num_all_posts) 
			<a href="<?php echo constructUrl(($take*floor($num_all_posts/$take)-$take), $sort, $search); ?>" class="btn btn-sm btn-red pull-right">>></a> 
			<a href="<?php echo constructUrl($offset+$take, $sort, $search); ?>" class="btn btn-sm btn-red pull-right">></a>
			@endif
			<div class="clearfix"></div>
		</div>
		<hr />
	</div>

@include('includes.foot')