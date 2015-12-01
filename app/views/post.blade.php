<?php
	$post_info = Post::find($post_id);
?>

@include('includes.head')

	@include('includes.navbar')

	<div ng-controller="PostController" ng-include="ENV.baseUrl + 'partials/post.html'">

	</div>

@include('includes.foot')