<?php

Blade::setEscapedContentTags('[[', ']]');
Blade::setContentTags('[[[', ']]]');

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', array('as' => '/', function()
{
	JavaScript::put([
		'user' => Auth::user()
	]);

	return View::make('home')
		->with('search_page', false)
	    ->with('fixed_nav', false);
}));

Route::get('home', array('as' => 'home', function()
{
	$action = Session::get( 'action' );
	$passwordResetToken = Session::get('passwordResetToken');

	JavaScript::put([
		'user' => Auth::user(),
		'resetPassword' => ($passwordResetToken != '')
	]);

	return View::make('home')
		->with('action', $action)
		->with('search_page', false)
	    ->with('fixed_nav', false);
}));

Route::get('search', array('as' => 'search', function()
{
	$action = Session::get( 'action' );

	$search_term = Input::get( 'search' );
	$search_tags = Input::get( 'tags' );

	JavaScript::put([
		'user' => Auth::user(),
		'search_term' => $search_term,
		'isCollection' => false,
		'showTags' => $search_tags
	]);

	return View::make('search')
		->with('action', $action)
		->with('search_page', true)
	    ->with('fixed_nav', true);
}));

//route to dump all posts into one page..
Route::get('posts/{offset?}/{sort?}/{search?}', array('as' => 'posts', function($offset = 0, $sort = null, $search = null)
{
	$validator = Validator::make(
		array('offset' => $offset),
		array('offset' => array('integer'))
	);
	if ($validator->fails())
	{
		return Redirect::route('posts');
	}

	JavaScript::put([
		'user' => Auth::user(),
		'all_posts_search_term' => $search
	]);

	return View::make('all-posts')
		->with('search_page', false)
		->with('fixed_nav', true)
		->with('offset', $offset)
		->with('search', $search)
		->with('sort', $sort);
}));

Route::get('post/{post_id}', array('as' => 'post', function($post_id)
{
	$validator = Validator::make(
		array('id' => $post_id),
		array('id' => array('integer'))
	);
	if ($validator->fails())
	{
		return Redirect::route('search');
	}

	JavaScript::put([
		'user' => Auth::user(),
		'post_id' => $post_id
	]);

	return View::make('post')
		->with('post_id', $post_id)
		->with('search_page', false)
	    ->with('fixed_nav', true);
}));

Route::get('profile', array('before' => 'auth', 'as' => 'profile', function()
{
	JavaScript::put([
		'user' => Auth::user(),
		'profile_id' => Auth::user()->id
	]);

	return View::make('profile')
		->with('profile_id', Auth::user()->id)
		->with('search_page', false)
	    ->with('fixed_nav', true);
}));

Route::get('profile/{user_id}', array('as' => 'profile', function($user_id)
{
	$validator = Validator::make(
		array('id' => $user_id),
		array('id' => array('integer'))
	);
	if ($validator->fails())
	{
		return Redirect::route('home');
	}

	JavaScript::put([
		'user' => Auth::user(),
		'profile_id' => $user_id
	]);

	return View::make('profile')
		->with('profile_id', $user_id)
		->with('search_page', false)
	    ->with('fixed_nav', true);
}));

Route::group(array('before' => 'admin'), function()
{
	Route::get('admin/{tab?}', array('as' => 'admin', function($tab = null) {
		JavaScript::put([
			'user' => Auth::user(),
			'admin_page' => true,
			'tab' => $tab
		]);

		return View::make('admin')
			->with('search_page', false)
			->with('fixed_nav', true);
	}));
});

Route::get('login', array('as' => 'login', function()
{
	JavaScript::put([
		'user' => Auth::user()
	]);

	return Redirect::route('home')
	    ->with('action', 'login');
}));

Route::get('collection/{collection_slug?}', array('as' => 'collection', function($collection_slug = null)
{
	if (is_null($collection_slug))
	{
		return Redirect::route('search');
	}

	$collection = Collection::where('slug', '=', $collection_slug)->first();

	if (is_null($collection))
	{
		return Redirect::route('search');
	}

	JavaScript::put([
		'user' => Auth::user(),
		'search_term' => '',
		'isCollection' => true,
		'collection_slug' => $collection_slug,
		'collection_name' => $collection->name
	]);

	return View::make('search')
		->with('search_page', true)
	    ->with('fixed_nav', true);
}));

Route::get('privacy', array('as' => 'privacy', function()
{
	JavaScript::put([
		'user' => Auth::user()
	]);

	return View::make('privacy')
			->with('search_page', false)
			->with('fixed_nav', true);
}));

Route::controller('auth', 'AuthController');

Route::controller('password', 'RemindersController');

Route::controller('export', 'ExportController');

Route::group(array('before' => 'admin', 'prefix' => 'api'), function()
{
	Route::controller('admin', 'AdminController');
});

Route::group(array('prefix' => 'api'), function() {
	Route::controller('posts', 'PostsController');
	Route::controller('search', 'SearchController');
	Route::controller('user', 'UserController');
});