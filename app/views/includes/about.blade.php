<?php

	$about_content = StaticContent::where('name', '=', 'about')->first();
	$fellows_content = StaticContent::where('name', '=', 'fellows')->first();

?>

<div id="about_pages" ng-show="actions.showAboutPages" class="view-post-modal ng-hide" ng-controller="AboutController">

	<div class="position-relative">
	  <div class="margin-top">

	    <a class="close" ng-click="actions.showAboutPages = false"><i class="fa fa-times"></i></a>
	    <div class="clearfix"> </div>

		<tabset>
			<tab active="tabs[0].active">
				<tab-heading>{{tabs[0].title}}</tab-heading>
				<div class="container">
					<?php echo $about_content['content']; ?>
			        <br /><br />
			    </div>
			</tab>
			<tab active="tabs[1].active">
				<tab-heading>{{tabs[1].title}}</tab-heading>
				<div class="container">
					<?php echo $fellows_content['content']; ?>
			      	<br /><br />
			    </div>
			</tab>
			<tab active="tabs[2].active">
				<tab-heading>{{tabs[2].title}}</tab-heading>
				<div class="container center" ng-controller="NetworkEventsController">

					<p>In addition to events posted by you, our community, the Network hosts the following events:</p>

					<strong class="red cap-letters" ng-if="networkEvents.length == 0">There are no NetworkEd events yet!</strong>

					<div ng-repeat="post in networkEvents" class="slidebox-item event-item" ng-class="{ 'active' : hover || post.show }" ng-mouseover="hover = true" ng-mouseleave="hover = false" title="{{post.title}}">
		                <a ui-sref="post({postID:post.id})">
		                    <div class="info-window-image event-info-window-image" ng-style="{'background-image':'url('+post.thumbnail_sm+')'}">
		                        <!-- <div class="event-networked-label" ng-if="post.post_type_id == 5">NetworkEd Event</div> -->
		                        <div ng-if="!post.archived" class="image-overlay">
		                            <div class="image-overlay-view white" ng-show="hover">
		                                View
		                            </div>
		                        </div>
		                        <div ng-if="post.archived" class="image-overlay archived">
		                            <div class="image-overlay-view white">
		                                Past Event
		                            </div>
		                        </div>
		                    </div>
		                    <div class="event-info-window-text" ng-class="{'archived':post.archived}">
		                        <span class="event-date">{{post.date | dateToISO | date:"MMM d, y @ h:mm a"}}</span><br />
		                        <p class="event-title">{{post.title}}</p>
		                    </div>
		                </a>
		            </div>
		            <div class="clearfix"></div>

			    	<br /><br />
			  	</div>
			</tab>
		</tabset>

	    <div id="footer">
	      <div ng-include="ENV.baseUrl + 'partials/footer.html'"></div>
	    </div>

	  </div>
	</div>


</div>