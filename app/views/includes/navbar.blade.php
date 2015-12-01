<div id="top_bar" class="@if ($fixed_nav) fixed fixed-scroll-override @endif" ng-class="{'expanded': actions.showTags || actions.showLogin @if (!$fixed_nav) , 'fixed': actions.showAboutPages || ($state.current.name != 'main' && $state.current.name != '') @endif || actions.showLogin, 'fixed-scroll-override': actions.showLogin || actions.showAboutPages || ($state.current.name != 'main' && $state.current.name != '') }" show-navbar modal-open>
	<h2 class="logo">
		<a href="{{ENV.baseUrl}}"><span class="black">Network</span><span class="red">Ed</span></a>
	</h2>

	<div id="top_bar_search">
		<div class="search_tag_container">
			<form id="search_form" action="@if(!$search_page) {{ENV.baseUrl + 'search'}} @endif" method="get">
				<div class="input-group">
			    	<input class="form-control" name="search" type="text" placeholder="Go exploring! Enter a subject, person, or place..." ng-model="filters.searchTerm" />
			    	<input name="tags" id="search_tags" value="false" type="hidden" />
			    	<span class="input-group-btn">
			    		@if($search_page)
			    		<a class="btn"><i class="fa fa-search" title="Search"></i></a>
			    		@else
			        	<button class="btn" type="submit"><i class="fa fa-search" title="Search"></i></button>
			        	@endif
			    	</span>
			    </div>
			    <h2 class="red" ng-if="page_type != 'collection'">
					<a class="circle-icon gray tag-icon" ng-class="{'active' : actions.showTags}" ng-click="@if($search_page) actions.showTags = ! actions.showTags; actions.showLogin = false; @else searchWithTags() @endif" title="Search with Tags"></a>
				</h2>
			</form>
		</div>
	</div>

	<h2 class="red">
		@if (Auth::user())
			<a href="{{ENV.baseUrl}}auth/logout" class="sign-out">Sign<span class="hidden-xs"> </span>Out</a><!--
			--><a class="circle-icon" href="{{ENV.baseUrl}}profile" title="View My Profile">
				<img class="user-icon" ng-src="{{current_user != '' && current_user.photo != '' && ENV.baseUrl+'user_photos/'+current_user.photo || ENV.baseUrl+'assets/images/profile.png'}}" />
			</a><!--
			--><a class="circle-icon plus-icon" ui-sref="post({postID:'add'})" title="Add a Post"></a><!--
			--><a class="circle-icon menu-icon" ng-click="actions.showLogin = false; actions.showTags = false; actions.showAboutPages = ! actions.showAboutPages; actions.aboutPageActive = 'about'" title="Learn More About The Network" ng-class="{'active':actions.showAboutPages}"></a>
		@else
			<div class="circle-icon login-icon person-icon" ng-click="actions.showLogin = ! actions.showLogin; actions.showTags = false;" ng-class="{'active' : actions.showLogin}" title="Join Us or Log In" @if($errors->login->has() || $errors->register->has() || $errors->passwordreset->has() || (isset($action) && $action == 'login')) ng-init="actions.showLogin = ! actions.showLogin;" @endif></div><!--
			--><a class="circle-icon menu-icon" ng-click="actions.showLogin = false; actions.showTags = false; actions.showAboutPages = ! actions.showAboutPages; actions.aboutPageActive = 'about'" title="Learn More About The Network" ng-class="{'active':actions.showAboutPages}"></a>
		@endif
	</h2>
</div>