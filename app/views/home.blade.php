@include('includes.head')

	<div id="map_canvas_wrapper">
		<div id="map_canvas_overlay_text" ng-click="clickMapOverlay($event)">
			<h2 id="map_canvas_options" class="pull-right red">
				@if (Auth::user())
					<a class="circle-icon circle-big" href="profile" title="View My Profile">
						<img class="user-icon" ng-src="{{current_user != '' && current_user.photo != '' && 'user_photos/'+current_user.photo || 'assets/images/profile.png'}}" />
					</a>
				@else
					<a class="circle-icon circle-big person-icon" href="" anchor-smooth-scroll="top_bar" ng-click="actions.showLogin = ! actions.showLogin;" title="Join Us or Log In"></a>
				@endif
				@if (Auth::user())
				<a class="circle-icon circle-big plus-icon" ui-sref="post({postID:'add'})" title="Add a Post"></a>
				@endif
				<a class="circle-icon circle-big menu-icon" ng-click="actions.showAboutPages = true; actions.aboutPageActive = 'about';" title="About Us"></a>
			</h2>

			<div id="network_circle_logo">
				<div>
					<span class="white">Network</span><span class="black">Ed</span>
				</div>
			</div>
			<div class="cap-letters-bigger white" style="width: 100%; max-width: 400px;">
				A Directory of Innovation in Education in Wisconsin and beyond
			</div>
			<div id="map_canvas_search" class="search_tag_container">
				<form action="search" method="get" id="search_form">
					<div class="input-group">
				    	<input class="form-control" name="search" type="text" value="" placeholder="Go exploring! Enter a subject, person, or place..." />
				    	<input name="tags" id="search_tags" value="false" type="hidden" />
				    	<span class="input-group-btn">
				        	<button class="btn" type="submit" title="Search"><i class="fa fa-search"></i></button>
				    	</span>
				    </div>
				    <h2 class="red">
						<a class="circle-icon gray tag-icon" href="javascript: void(0)" ng-click="searchWithTags()" title="Search with Tags"></a>
					</h2>
				</form>
			</div>
			<div class="browse_posts cap-letters white">
				<a href="" anchor-smooth-scroll="browse">
					<div>Browse Posts</div>
					<div><i class="fa fa-angle-down"></i></div>
				</a>
			</div>
		</div>
		<div id="map_canvas_overlay_bg"></div>
		
		<div ng-controller="HomeController">

			<div resize-map map-ready="'true'" map-page="'home'" window-height="windowHeight" window-width="windowWidth">
				<ui-gmap-google-map id="map_canvas" center='map.center' zoom='map.zoom'>
					<ui-gmap-markers models="allMarkers" coords="'coordinates'" icon="'icon'" click="'onClick'" doCluster="true" clusterOptions="map.clusterOptions"> </ui-gmap-markers>
				</ui-gmap-google-map>
			</div>
		</div>

		<div id="browse"></div>
	</div>

	@include('includes.navbar')

	<div class="content-section background-image">

		<h1 class="red capitals">Connect + Engage → Transform</h1>

		<div class="narrow-text">Welcome to NetworkEd! Explore the site, connect with education enthusiasts, and contribute to this ever growing directory of education innovations in Wisconsin and beyond. Go ahead, click on the collections below to see our highlighted posts, or just search for a person, location, or area of interest in the search bar.</div>

		<div class="join-link cap-letters-bigger">
			<a href="login">
				<strong>Join Our Community</strong>
			</a>
		</div>

		<div class="browse_posts cap-letters white">
			<a href="" anchor-smooth-scroll="collections">
				<div>View Collections</div>
				<div><i class="fa fa-angle-down"></i></div>
			</a>
		</div>

	</div>

	<div class="content-section" ng-controller="CollectionsController">

		<div id="collections"> </div>
		
		<br /><br />

		<h1>Curated Collections</h1>

		<div class="collections-wrapper">
			<div class="col-sm-4" ng-repeat="collection in collections">
				<a ng-href="collection/{{collection.slug}}" class="collection-box" ng-style="{'background-image':'url('+ENV.baseUrl+'uploads/collections/'+collection.thumbnail+')'}">
					<div class="collection-name">
						{{collection.name}}
					</div>
				</a>
			</div>
			<div class="clearfix"> </div>
		</div>
	</div>

	<div class="content-section gray">
		<h1>
			About Our Community
		</h1>

		<div>
			<a ng-click="actions.showAboutPages = true; actions.aboutPageActive = 'about'" class="about-img-link about">
				<div class="about-img-text">About</div>
			</a>
			<a ng-click="actions.showAboutPages = true; actions.aboutPageActive = 'fellows'" class="about-img-link fellows">
				<div class="about-img-text">Fellows</div>
			</a>
			<a ng-click="actions.showAboutPages = true; actions.aboutPageActive = 'events'" class="about-img-link events">
				<div class="about-img-text">Events</div>
			</a>
			
		</div>
	</div>

	<div class="content-section">
		<h1>
			Our Partners
		</h1>

		<div class="row">
			<div class="col-md-4 cap-letters">
				<a href="http://dpi.wi.gov/" target="_blank"><img src="assets/images/WDPI.png" style="margin-bottom: 10px;" /></a>
			</div>
			<div class="col-md-4 cap-letters">
				<a href="https://www.education.wisc.edu/" target="_blank"><img src="assets/images/SOE.png" style="margin-bottom: 10px;" /></a>
			</div>
			<div class="col-md-4 cap-letters">
				<a href="http://www.wcer.wisc.edu/" target="_blank"><img src="assets/images/WCER.png" style="margin-bottom: 10px;" /></a>
			</div>
			<div class="clearfix"> </div>
		</div>
		<div class="clearfix"> </div>
	</div>

	<div id="footer">
		<div ng-include="ENV.baseUrl + 'partials/footer.html'"></div>
	</div>

@include('includes.foot')