@include('includes.head')

	@include('includes.navbar')

	<div ng-controller="ProfileController" style="overflow-x: hidden;" @if($errors->profileupdate->has()) ng-init="actions.editMyProfile = true" @endif>
		@if (Auth::user() && Auth::user()->id == $profile_id)
		<div class="slidedown-modal" ng-show="actions.editMyProfile">
			<div class="container" scroll-dropdown-modal window-height="windowHeight" check-scroll-height="actions.editMyProfile">
				<div ng-controller="RegistrationController">
					<a class="close" ng-click="actions.editMyProfile = false"><i class="fa fa-times"></i></a>

					<h1 class="white center">Edit Your Profile</h1>

					@if($errors->profileupdate->has())
						@if($errors->profileupdate->first('email')!='')
						<p class="center"><?= $errors->profileupdate->first('email') ?></p>
						@endif
						@if($errors->profileupdate->first('password')!='')
						<p class="center"><?= $errors->profileupdate->first('password') ?></p>
						@endif
					@endif

					<form action="{{ENV.baseUrl + 'auth/update'}}" method="POST" class="join-form">
						<div class="row-fluid">
							<div class="col-sm-6">
								<div class="login-input-group">
									<div class="col-xs-3 col-no-padding">
										<div class="profile-bg" ng-style="{'background-image':'url('+ENV.baseUrl+'user_photos/'+user_profile.photo+')'}">
											<span class="img-helper"></span>
										</div>
									</div>
									<div class="col-xs-9">
										<div class="login-input-label">
											Profile Image
										</div>
										<div class="login-input-input">
											<input type="file" accept="image/*" class="form-control post-upload-control" ng-file-select ng-file-change="uploadUserProfile($files, $event, user_profile, 'photo')">
											<input type="hidden" ng-value="user_profile.photo" name="photo" />
										</div>
									</div>
									<div class="clearfix"> </div>
								</div>
								<div class="login-input-group row-fluid">
									<div class="col-xs-2 login-input-label">
										Email
									</div>
									<div class="col-xs-10 login-input-input">
										<input type="email" name="email" value="" ng-model="user_profile.email" placeholder="Email" autofocus class="form-control" required>
									</div>
									<div class="clearfix"> </div>

									<div class="col-xs-2 login-input-label">Password</div>
									<div class="col-xs-5 login-input-input">
										<input type="password" name="password" value="" placeholder="Password" class="form-control">
									</div>
									<div class="col-xs-5 login-input-input">
										<input type="password" name="password_confirmation" value="" placeholder="Confirm Password" class="form-control">
									</div>
									<div class="col-xs-12">
										<i><span class="dark-gray">To update your password, enter a new value and confirmation here.</span></i>
									</div>
									<div class="clearfix"> </div>
								</div>
								<div class="login-input-group row-fluid">
									<div class="col-xs-2 login-input-label">
										Name
									</div>
									<div class="col-xs-5 login-input-input">
										<input type="text" name="first_name" ng-value="user_profile.first_name" placeholder="First Name" class="form-control" required>
									</div>
									<div class="col-xs-5 login-input-input">
										<input type="text" name="last_name" ng-value="user_profile.last_name" placeholder="Last Name" class="form-control" required>
									</div>
									<div class="clearfix"> </div>
								</div>

								<div class="login-input-group row-fluid">
									<div class="col-xs-2 login-input-label">
										Location
									</div>
									<div class="col-xs-4 login-input-input">
										<input type="text" name="city" ng-value="user_profile.city" placeholder="City" class="form-control" required>
									</div>
									<div class="col-xs-3 login-input-input">
										<input type="text" name="state" ng-value="user_profile.state" placeholder="State" class="form-control" required>
									</div>
									<div class="col-xs-3 login-input-input">
										<input type="text" name="zip" ng-value="user_profile.zip" placeholder="Zip" class="form-control" required>
									</div>
									<div class="clearfix"> </div>
								</div>

								<div class="login-input-group">
									<div class="col-xs-12 login-input-label">
										Bio
									</div>
									<div class="col-xs-12 login-input-input">
										<textarea class="form-control" ng-model="user_profile.bio" name="bio" placeholder="Enter bio here..." required></textarea>
									</div>
									<div class="clearfix"> </div>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="login-input-group">
									<div class="login-input-label">
										Role
									</div>
									<div class="row-fluid">
										<div class="col-xs-6" ng-repeat="role in user_profile.selected_roles">
											<label class="login-input-radio-label">
												<input name="role[]" value="{{role.name}}" type="checkbox" ng-model="role.selected"> {{role.name}}
												<input ng-if="role.name == 'Other' && role.selected" name="role_title_other" type="text" ng-model="user_profile.role_title_other" class="form-control" />
											</label>
										</div>
										<div class="clearfix"> </div>
									</div>
								</div>
								<div class="login-input-group">
									<div class="col-xs-3 login-input-label">
										Organization
									</div>
									<div class="col-xs-9 login-input-input">
										<input type="text" name="organization" value="" ng-model="user_profile.organization" placeholder="Organization" class="form-control" required>
									</div>
									<div class="clearfix"> </div>
								</div>
								
								<div class="login-input-group">
									<div class="col-xs-12 login-input-label">
										Social Links (Optional. Link to your website, Facebook, LinkedIn, etc)
									</div>
									<div class="col-xs-12 login-input-input">
										<div class="row">
											<div class="col-xs-6">
												<input type="url" name="social_link_1" ng-model="user_profile.social_link_1" placeholder="Social Link 1" class="form-control">
											</div>
											<div class="col-xs-6">
												<input type="url" name="social_link_2" ng-model="user_profile.social_link_2" placeholder="Social Link 2" class="form-control">
											</div>
											<div class="clearfix"></div>
										</div>
									</div>
									<div class="clearfix"></div>
								</div>

								<div class="login-input-group">
									<div class="col-xs-12 login-input-label">
										Display My Email Address on My Profile
									</div>
									<div class="col-xs-12">
										<input name="share_email" type="checkbox" ng-model="user_profile.share_email" value="1" ng-true-value="1" ng-false-value="0"> <span class="black">Select to allow others to contact you via email</span>
									</div>
									<div class="clearfix"> </div>
								</div>

							</div>
							<div class="clearfix"> </div>
						</div>

						<div class="row-fluid">
							<div class="col-sm-6">
								
							</div>
							<div class="col-sm-6">
								<div class="pull-right">
									<button class="btn submit-btn" id="register" type="submit">
										<h3 class="white">Save changes <i class="fa fa-chevron-right"></i></h3>
									</button>
								</div>
							</div>
							<div class="clearfix"> </div>
						</div>

						<input name="_token" type="hidden" value="<?php echo csrf_token(); ?>">	
					</form>

					<br /><br />
				</div>
			</div>
		</div>
		@endif

		<div class="container">

			<div class="col-sm-4 profile-column-wrapper">
				<div class="profile-column-1">
					<div>
						<div class="profile-admin-header" ng-if="user_profile.is_admin">
							<h3 class="white">
								@if (Auth::user() && Auth::user()->is_admin)
								<a ng-href="{{ENV.baseUrl}}admin">Administrator</a>
								@else
								Administrator
								@endif
							</h3>
						</div>
						<div class="profile-admin-header" ng-if="user_profile.is_moderator && !user_profile.is_admin">
							<h3 class="white">Moderator</h3>
						</div>
						<div class="profile-bg" ng-style="{'background-image':'url('+ENV.baseUrl+'user_photos/'+user_profile.photo+')'}">
							@if (Auth::user() && Auth::user()->id == $profile_id)
								<a href="javascript: void(0)" ng-click="actions.editMyProfile = true" class="edit-profile"><i class="fa fa-pencil"></i>&nbsp; Edit Profile</a>
							@endif

							<span class="img-helper"></span>
						</div>
						<div class="profile-dark-bg">
							<h2 class="white">{{user_profile.first_name}} {{user_profile.last_name}}</h2>

							<h3>{{user_profile.num_posts || 0}} Posts</h3>

						</div>

						<div class="profile-about">
							<h3>About</h3>

							<p class="caps">
								<strong>{{user_profile.user_role_names}}</strong><br />
								{{user_profile.organization}}<br />
								{{user_profile.city}}, {{user_profile.state}}
							</p>

							<p>{{user_profile.bio}}</p>

							<h3 ng-show="user_profile.social_link_1 || user_profile.social_link_2 || user_profile.share_email">Links</h3>
							<p>
								<social-link class="profile-social-link" ng-if="user_profile.share_email" link="user_profile.email" type="'email'"></social-link>
								<social-link class="profile-social-link" ng-if="user_profile.social_link_1" link="user_profile.social_link_1" type="'link'"></social-link>
								<social-link class="profile-social-link" ng-if="user_profile.social_link_2" link="user_profile.social_link_2" type="'link'"></social-link>
							</p>
						</div>

						@if (Auth::user() && Auth::user()->id == $profile_id && !Auth::user()->is_moderator && Auth::user()->moderator_requested != -1)
						<div ng-click="requestModeratorRole()" class="request-mod" ng-class="{'allow-hover':user_profile.moderator_requested == 0}">
							<span ng-show="user_profile.moderator_requested == 0"><strong>Want to be a moderator?</strong>&nbsp; <span class="gray">Just ask!</span></span>
							<span ng-show="user_profile.moderator_requested == 1">Your moderator request is pending</span>
						</div>
						@endif
					</div>
				</div>
			</div>

			<div class="col-sm-8 profile-column-wrapper">
				<div class="profile-column-2">
					<div class="profile-view-subheader">
						@if (Auth::user() && Auth::user()->id == $profile_id)
						<span class="profile-view" ng-click="actions.showPosts = 'favorites'"  ng-class="{ 'active' : actions.showPosts == 'favorites' }">
							My Favorites
						</span>
						@endif
						<span class="profile-view" ng-click="actions.showPosts = 'myposts'"  ng-class="{ 'active' : actions.showPosts == 'myposts' }">
							@if (Auth::user() && Auth::user()->id == $profile_id) My @else Their @endif Posts
						</span> 
						@if (Auth::user() && Auth::user()->is_moderator && Auth::user()->id == $profile_id)
						<span class="profile-view" ng-click="actions.showPosts = 'approvalqueue'" ng-class="{ 'active' : actions.showPosts == 'approvalqueue' }">
							Approval Queue ({{user_profile.approval_queue_count || 0}})
						</span>
						@endif
					</div>

					<div class="profile-view-divider"> </div>

					<div ng-if="actions.showPosts == 'favorites'" infinite-scroll="loadMoreUserFavorites()" infinite-scroll-disabled="loadingUserFavorites || loadedAllUserFavorites" infinite-scroll-distance="1">
						<a ui-sref="post({postID:post.id})" ng-repeat-start="post in user_favorites" class="profile-view-row row-fluid" ng-class="{'active': hover}" ng-mouseover="hover = true" ng-mouseleave="hover = false">
							<div class="col-xs-4 col-no-padding">
								<div class="info-window-image" style="background-image:url('{{ENV.baseUrl}}uploads/{{post.thumbnail_sm}}');">
			                        <div class="image-overlay">
			                            <div class="image-overlay-view white" ng-show="hover">
			                                View
			                            </div>
			                        </div>
		                        </div>
		                    </div>

		                    <div class="col-xs-8">
			                    <img class="pull-right" ng-src="{{ENV.baseUrl}}assets/images/icons/{{post.post_type.icon_file}}.png" />
			                    <h2>{{post.title}}</h2>
			                    <p class="caps">By: {{user_profile.first_name}} {{user_profile.last_name}}</p>
		                    </div>

		                    <div class="clearfix"> </div>
						</a>
						<div ng-repeat-end class="profile-view-divider"> </div>
					</div>

					<div ng-if="actions.showPosts == 'myposts'" infinite-scroll="loadMoreUserPosts()" infinite-scroll-disabled="loadingUserPosts || loadedAllUserPosts" infinite-scroll-distance="1">
						<a ui-sref="post({postID:post.id})" ng-repeat-start="post in user_profile.posts" class="profile-view-row row-fluid" ng-class="{'active': hover}" ng-mouseover="hover = true" ng-mouseleave="hover = false">
							<div class="col-xs-4 col-no-padding">
								<div class="info-window-image" style="background-image:url('{{ENV.baseUrl}}uploads/{{post.thumbnail_sm}}');">
			                        <div class="image-overlay">
			                            <div class="image-overlay-view white" ng-show="hover">
			                                View
			                            </div>
			                        </div>
		                        </div>
		                    </div>

		                    <div class="col-xs-8">
			                    <img class="pull-right" ng-src="{{ENV.baseUrl}}assets/images/icons/{{post.post_type.icon_file}}.png" />
			                    <h2>{{post.title}}</h2>
			                    <p class="caps">By: {{user_profile.first_name}} {{user_profile.last_name}}</p>
		                    </div>

		                    <div class="clearfix"> </div>
						</a>
						<div ng-repeat-end class="profile-view-divider"> </div>
					</div>

					<div ng-if="actions.showPosts == 'approvalqueue'" infinite-scroll="loadMoreApprovalQueue()" infinite-scroll-disabled="loadingApprovalQueue || loadedAllApprovalQueue" infinite-scroll-distance="1">
						<a ui-sref="post({postID:post.id})" ng-repeat-start="post in user_profile.approval_queue" class="col-xs-11 profile-view-row row-fluid" ng-class="{'active': hover}" ng-mouseover="hover = true" ng-mouseleave="hover = false">
							<div class="col-xs-4 col-no-padding">
								<div class="info-window-image" style="background-image:url('{{ENV.baseUrl}}uploads/{{post.thumbnail_sm}}');">
			                        <div class="image-overlay">
			                            <div class="image-overlay-view white" ng-show="hover">
			                                View/Edit
			                            </div>
			                        </div>
		                        </div>
		                    </div>

		                    <div class="col-xs-8">
			                    <img class="pull-right" ng-src="{{ENV.baseUrl}}assets/images/icons/{{post.post_type.icon_file}}.png" />
			                    <h2>{{post.title}}</h2>
			                    <p class="caps">By: {{post.user.first_name}} {{post.user.last_name}}</p>
			                    <br />
			                    <p ng-show="post.unapproved_tags.length > 0" class="caps bold">Unapproved Tags:</p>
			                    <ul ng-show="post.unapproved_tags.length > 0">
				                    <li ng-repeat="unapproved_tag in post.unapproved_tags">{{unapproved_tag.tag[0].tag_category.name}}: {{unapproved_tag.name}}</li>
			                    </ul>
		                    </div>

		                    <div class="clearfix"> </div>
						</a>
						<div class="col-xs-1">
							<div class="approve-post" ng-click="approvePost(post)" title="Approve Post"><i class="fa fa-check"></i></div>
		                    <div class="reject-post" ng-click="rejectPost(post)" title="Reject Post"><i class="fa fa-times"></i></div>
						</div>

						<div class="clearfix"> </div>

						<div ng-repeat-end class="profile-view-divider"> </div>
					</div>
				</div>
			</div>

		</div>

	</div>

	<div id="footer">
	    <div ng-include="ENV.baseUrl + 'partials/footer.html'"></div>
	</div>

@include('includes.foot')