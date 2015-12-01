@include('includes.head_admin')

	<div id="top_bar" class="fixed fixed-scroll-override">
		<h2 class="logo">
			&nbsp;<a ng-href="{{ENV.baseUrl}}"><span class="black">Network</span><span class="red">Ed</span></a>
		</h2> &nbsp;&nbsp;Admin Panel
	</div>

	<div class="view-collection-modal modal" ng-show="actions.showCollectionPosts">
		<div class="modal-dialog">
	        <div class="modal-content">
	        	<div class="modal-header">
		            <a href="javascript: void(0)" ng-click="actions.showCollectionPosts = false" class="close"><i class="fa fa-times"></i></a>

		            <h4 class="modal-title" id="myModalLabel">
		            	Manage: {{activeCollection.name}} [<strong>Total: {{activeCollection.posts.length || 0}}</strong>]
		            </h4>
		        </div>

		        <div class="modal-body">
		        	<div class="row-fluid" ng-repeat="post in activeCollection.posts">
			            <a href="{{ENV.baseUrl+'#!/post/'+post.id}}" target="_blank" class="black col-xs-10">
							<div class="col-xs-4 info-window-image" style="max-width: 50px; max-height: 50px;" ng-style="{'background-image':'url({{ENV.baseUrl}}uploads/{{post.thumbnail}})'}"></div>
							<div class="col-xs-8" title="{{post.title}}">{{post.title | addEllipsis:35}}<br />{{post.city}}, {{post.state}}</div>
							<div class="col-xs-2"><img class="info-window-icon" ng-src="{{ENV.baseUrl}}assets/images/icons/{{post.post_type.icon_file}}.png" /></div>
							<div class="clearfix"></div><br />
						</a>
						<a ng-click="deletePostFromCollection(activeCollection, post)" href="javascript: void(0)" class="col-xs-2">
							<i class="fa fa-trash-o"></i> Remove
						</a>
					</div>
		        </div>

		        <div class="modal-footer">
			        <button ng-click="actions.showCollectionPosts = false;" class="btn btn-primary">Close</button>
		        </div>
		    </div>
		</div>
	</div>

	<div style="overflow-x: hidden;">

		<div class="container">

			<div class="col-sm-3 profile-column-wrapper">
				<div class="admin-column-1">
					<ul class="admin-manage-list">
						<li class="header">Queues</li>
						<li ng-click="actions.showAdmin = 'approvalqueue'" ng-class="{ 'active' : actions.showAdmin == 'approvalqueue' }">Post Approval ({{admin_data.approval_queue_count || 0}})</li>
						<li ng-click="actions.showAdmin = 'moderator_requests'" ng-class="{ 'active' : actions.showAdmin == 'moderator_requests' }">Moderators ({{admin_data.moderator_queue.length || 0}})</li>
						<li ng-click="actions.showAdmin = 'tag_requests'" ng-class="{ 'active' : actions.showAdmin == 'tag_requests' }">Tags ({{admin_data.tag_queue.length || 0}})</li>
						<li class="header">Users</li>
						<li ng-click="actions.showAdmin = 'moderator_list'" ng-class="{ 'active' : actions.showAdmin == 'moderator_list' }">Moderators List</li>
						<li ng-click="actions.showAdmin = 'user_list'" ng-class="{ 'active' : actions.showAdmin == 'user_list' }">Users List</li>
						<li class="header">Site Management</li>
						<li ng-click="actions.showAdmin = 'collections'" ng-class="{ 'active' : actions.showAdmin == 'collections' }">Collections</li>
						<li ng-click="actions.showAdmin = 'cms'" ng-class="{ 'active' : actions.showAdmin == 'cms' }">Static Content</li>
						<li class="link"><a ng-href="{{ENV.baseUrl}}posts" target="_blank">Posts</a></li>
						<li ng-click="actions.showAdmin = 'tags'" ng-class="{ 'active' : actions.showAdmin == 'tags' }">Tags</li>
						<li class="header">Data</li>
						<li class="inactive">Export to Spreadsheet</li>
					</ul>
					

				</div>
			</div>

			<div class="col-sm-9 profile-column-wrapper">
				<div class="admin-column-2">

					<div ng-if="actions.showAdmin == ''">
						<h2>Admin Panel</h2>

						<p>Select a tab from the menu on the left to edit.</p>
					</div>

					<div ng-if="actions.showAdmin == 'collections'">
						<h2>Collections</h2>
						
						<p>Manage and add new collections here. Once active, they will appear on the front page of the NetworkEd site.</p><br />

						<div class="row">
							<div class="col-xs-4">
								<strong>Name</strong>
							</div>
							<div class="col-xs-5">
								<strong>Image</strong>
							</div>
							<div class="col-xs-2">
								<strong>Active?</strong>
							</div>
							<div class="col-xs-1">
								
							</div>
						</div>
						<div class="clearfix"> </div>
						<div ng-repeat-end class="admin-view-divider"> </div>

						<div ng-repeat-start="collection in admin_data.collections" class="row">
							<div class="col-xs-4">
								<input name="name" value="" ng-model="collection.name" ng-change="saveCollection(collection)" placeholder="Collection name" class="form-control">
							</div>
							<div class="col-xs-3">
								<input type="file" accept="image/*" class="form-control" ng-file-select ng-file-change="uploadCollectionImage($files, $event, collection)">
							</div>
							<div class="col-xs-2">
								<a ng-href="{{ENV.baseUrl}}uploads/collections/{{collection.thumbnail}}" ng-if="collection.thumbnail != ''" target="_blank">
									<img ng-src="{{ENV.baseUrl}}uploads/collections/{{collection.thumbnail}}" style="width: 100%" />
								</a>
								<span ng-show="collection.thumbnail == ''">No image</span>
							</div>
							<div class="col-xs-1">
								<input name="active" type="checkbox" ng-true-value="true" ng-false-value="false" ng-model="collection.active" ng-change="saveCollection(collection)">
							</div>
							<div class="col-xs-2">
								<a ng-click="manageCollection(collection)" href="javascript: void(0)" title="Manage collection">
									<i class="fa fa-pencil"></i>
								</a>
								<a ng-click="deleteCollection(collection)" href="javascript: void(0)" title="Delete this collection">
									<i class="fa fa-trash-o"></i>
								</a>
							</div>
						</div>
						<div class="clearfix"> </div>
						<div ng-repeat-end class="admin-view-divider"> </div>

						<h4 class="pull-right"><a href="javascript: void(0)" ng-click="addCollection()">Add New Collection</a></h4>
						<div class="clearfix"></div>
					</div>

					<div ng-if="actions.showAdmin == 'tags'">
						<h2>Tags</h2>
						<p>This is a list of all tags in the system, organized by category. Post count is for all posts, both approved and pending.</p><br />

						<h4 class="red" ng-repeat-start="(cat_index, array) in admin_data.tags">
							{{array.category.name}} <a href="javascript: void(0)" ng-click="renameCategory(array.category.id)" title="Rename Category"><i class="fa fa-pencil-square-o"></i></a>
							<a class="pull-right" href="javascript: void(0)" ng-click="addNewTag(array.category.id)">+ New Tag</a>
						</h4>
						<table class="table table-striped">
							<tr>
								<td><strong>Tag Name</strong></td>
								<td class="text-right"><strong>Post Count</strong></td>
								<td><strong>Actions</strong></td>
							</tr>
							<tr ng-repeat="tag in array.tags">
								<td ng-if="!tag.edit && !tag.new">
									{{tag.name}}<span ng-if="tag.pending"> <strong>(<a href="javascript: void(0)" ng-click="approveTag(tag)">approve?</a>)</strong></span>
								</td>
								<td ng-if="tag.new || tag.edit">
									<input class="form-control" ng-model="tag.name" />
								</td>
								<td class="text-right">{{tag.count.count}}</td>
								<td>
									<ul class="admin-tag-actions list-inline">
										<li ng-if="!tag.new && !tag.edit">
											<a href="javascript: void(0)" ng-click="deleteTag(tag)">delete</a>
										</li>
										<li ng-if="!tag.new && !tag.edit">
											<a href="javascript: void(0)" ng-click="startRenameTag(tag);">rename</a>
										</li>
										<li ng-if="!tag.new && !tag.edit">
											<a href="javascript: void(0)" ng-click="moveTag(tag, array.category.name);">move</a>
										</li>
										<li ng-if="tag.edit">
											<a href="javascript: void(0)" ng-click="cancelTagUpdate(tag)">cancel</a>
										</li>
										<li ng-if="tag.edit">
											<a href="javascript: void(0)" ng-click="renameTag(tag)">save</a>
										</li>
									</ul>
								</td>
							</tr>
						</table>
						<br /><br ng-repeat-end />
					</div>

					<div ng-if="actions.showAdmin == 'cms'">
						<h2>Static Content</h2>
						<p>Edit the content that appears on the About pages here. To add images, upload an image with the Add Image input, then copy/paste the new image to the correct location in the WYSIWYG.</p><br />

						<h3>About</h3><br />
						<textarea ui-tinymce="tinymceOptions" ui-tinymce ng-model="admin_data.about_page"></textarea>
						<br />
						Add Image: 
						<input type="file" accept="image/*" class="form-control" ng-file-select ng-file-change="onStaticContentFileSelect($files, $event, 'about')">
						<br />
						<button class="pull-right btn btn-red" ng-click="saveStaticContent('about', admin_data.about_page)">Save About</button>
						<div class="clearfix"></div>

						<br /><br />

						<h3>Fellows</h3><br />
						<textarea ui-tinymce="tinymceOptions" ui-tinymce ng-model="admin_data.fellows_page"></textarea>
						<br />
						<button class="pull-right btn btn-red" ng-click="saveStaticContent('fellows', admin_data.fellows_page)">Save Fellows</button>
						<div class="clearfix"></div>
					</div>

					<div ng-if="actions.showAdmin == 'approvalqueue'" infinite-scroll="loadMoreApprovalQueue()" infinite-scroll-disabled="loadingApprovalQueue || loadedAllApprovalQueue" infinite-scroll-distance="1">
						<h2>Approval Queue</h2>
						<p>New or edited posts waiting for moderator approval.</p><br />
						<a ui-sref="post({postID:post.id})" ng-repeat-start="post in admin_data.approval_queue" class="col-xs-11 profile-view-row row-fluid" ng-class="{'active': hover}" ng-mouseover="hover = true" ng-mouseleave="hover = false">
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

					<div ng-if="actions.showAdmin == 'moderator_requests'">
						<h2>Moderator Queue</h2>
						<p>These users have requested to be post moderators for NetworkEd.</p><br />
						<div ng-repeat-start="modRequest in admin_data.moderator_queue" class="col-xs-11 profile-view-row row-fluid">
							<div class="col-xs-4 col-no-padding">
								<div class="info-window-image" style="background-image:url('{{ENV.baseUrl}}user_photos/{{modRequest.photo}}');">
			                        <div class="image-overlay">
			                            <div class="image-overlay-view white" ng-show="hover">
			                                View User Profile
			                            </div>
			                        </div>
		                        </div>
		                    </div>

		                    <div class="col-xs-8">
			                    <a ng-href="{{ENV.baseUrl+'profile/'+modRequest.id}}" target="_blank"><h2>{{modRequest.first_name}} {{modRequest.last_name}}</h2></a>
			                    <table class="table table-striped">
			                    	<tr>
			                    		<td>Email:</td>
			                    		<td>{{modRequest.email}}</td>
			                    	</tr>
			                    	<tr>
			                    		<td>User since:</td>
			                    		<td>{{modRequest.created_at | dateToISO | date:'M/dd/yyyy'}}</td>
			                    	</tr>
			                    	<tr>
			                    		<td>Number of posts:</td>
			                    		<td>{{modRequest.num_posts}}</td>
			                    	</tr>
			                    	<tr>
			                    		<td>Role(s):</td>
			                    		<td>{{modRequest.user_role_names}}</td>
			                    	</tr>
			                    	<tr>
			                    		<td>Organization:</td>
			                    		<td>{{modRequest.organization}}</td>
			                    	</tr>
			                    	<tr>
			                    		<td>Location:</td>
			                    		<td>{{modRequest.city}}, {{modRequest.state}}</td>
			                    	</tr>
			                    	<tr>
			                    		<td>Bio:</td>
			                    		<td>{{modRequest.bio | addEllipsis:500}}</td>
			                    	</tr>
			                    </table>
		                    </div>

		                    <div class="clearfix"> </div>
						</div>
						<div class="col-xs-1">
							<div class="approve-post" ng-click="approveModerator(modRequest)" title="Approve Moderator"><i class="fa fa-check"></i></div>
		                    <div class="reject-post" ng-click="rejectModerator(modRequest)" title="Reject Moderator"><i class="fa fa-times"></i></div>
						</div>

						<div class="clearfix"> </div>

						<div ng-repeat-end class="profile-view-divider"> </div>
					</div>

					<div ng-if="actions.showAdmin == 'tag_requests'">
						<h2>Tag Queue</h2>
						<p>These are tags waiting for approval.</p><br />
						<table class="table table-striped">
							<tr>
								<td><strong>Tag Name</strong></td>
								<td><strong>Category</strong></td>
								<td class="text-right"><strong>Post Count</strong></td>
								<td><strong>Actions</strong></td>
							</tr>
							<tr ng-repeat="tag in admin_data.tag_queue">
								<td ng-if="!tag.edit && !tag.new">
									{{tag.name}}<span ng-if="tag.pending"> <strong>(<a href="javascript: void(0)" ng-click="approveTag(tag)">approve?</a>)</strong></span>
								</td>
								<td>{{tag.tag_category.name}}</td>
								<td ng-if="tag.new || tag.edit">
									<input class="form-control" ng-model="tag.name" />
								</td>
								<td class="text-right">{{tag.count.count}}</td>
								<td>
									<ul class="admin-tag-actions list-inline">
										<li ng-if="!tag.new && !tag.edit">
											<a href="javascript: void(0)" ng-click="deleteTag(tag)">delete</a>
										</li>
										<li ng-if="!tag.new && !tag.edit">
											<a href="javascript: void(0)" ng-click="startRenameTag(tag);">rename</a>
										</li>
										<li ng-if="tag.edit">
											<a href="javascript: void(0)" ng-click="cancelTagUpdate(tag)">cancel</a>
										</li>
										<li ng-if="tag.edit">
											<a href="javascript: void(0)" ng-click="renameTag(tag)">save</a>
										</li>
									</ul>
								</td>
							</tr>
						</table>
					</div>

					<div ng-if="actions.showAdmin == 'user_list'">
						<h2>User List ({{admin_data.total_num_users}} Total)</h2>
						<p>This is the complete current list of all users registered on NetworkEd.</p><br />

						<div class="row">
							<div class="col-xs-4 text-left">
								<a ng-show="numUserListPage > 0" href="javascript: void(0)" ng-click="loadUserListPage(numUserListPage-1)"><< Prev Page</a>
							</div>
							<div class="col-xs-4 text-center">
								Page {{numUserListPage+1}} of {{numUserListTotalPages}}
							</div>
							<div class="col-xs-4 text-right">
								<a ng-show="(admin_data.total_num_users / numUserListToLoad) >= (numUserListPage + 1)" href="javascript: void(0)" ng-click="loadUserListPage(numUserListPage+1)">Next Page >></a>
							</div>
						</div>
						<br />

						<div ng-repeat-start="a_user in admin_data.user_list" class="col-xs-11 profile-view-row row-fluid">
							<div class="col-xs-4 col-no-padding">

								<div class="info-window-image" style="background-image:url('{{ENV.baseUrl}}user_photos/{{a_user.photo}}');">
									<h3 class="white admin_mod_header" ng-if="a_user.is_admin || a_user.is_moderator">
			                    		<span ng-if="a_user.is_admin">Administrator</span>
			                    		<span ng-if="a_user.is_moderator && !a_user.is_admin">Moderator</span>
			                    	</h3>
			                        <div class="image-overlay">
			                        </div>
		                        </div>
		                    </div>

		                    <div class="col-xs-8">
		                    	<i ng-if="a_user.confirmed == 0">User is not yet confirmed. <a href="javascript: void(0)" ng-click="resendUserConfirmationCode(a_user)">Resend their confirmation code</a>.</i>

			                    <a ng-href="{{ENV.baseUrl+'profile/'+a_user.id}}" target="_blank">
			                    	<h2>
			                    		{{a_user.first_name}} {{a_user.last_name}} 
			                    	</h2>
			                    </a>

			                    <table class="table table-striped">
			                    	<tr>
			                    		<td>Email:</td>
			                    		<td>{{a_user.email}}</td>
			                    	</tr>
			                    	<tr>
			                    		<td>User since:</td>
			                    		<td>{{a_user.created_at | dateToISO | date:'M/dd/yyyy'}}</td>
			                    	</tr>
			                    	<tr>
			                    		<td>Number of posts:</td>
			                    		<td>{{a_user.num_posts}}</td>
			                    	</tr>
			                    	<tr>
			                    		<td>Role(s):</td>
			                    		<td>{{a_user.user_role_names}}</td>
			                    	</tr>
			                    	<tr>
			                    		<td>Organization:</td>
			                    		<td>{{a_user.organization}}</td>
			                    	</tr>
			                    	<tr>
			                    		<td>Location:</td>
			                    		<td>{{a_user.city}}, {{a_user.state}}</td>
			                    	</tr>
			                    	<tr>
			                    		<td>Bio:</td>
			                    		<td>{{a_user.bio | addEllipsis:500}}</td>
			                    	</tr>
			                    </table>

			                    <h3 ng-if="!a_user.is_admin" class="black">User Actions:</h3>
			                    <ul ng-if="!a_user.is_admin">
				                    <li ng-if="!a_user.is_moderator"><a href="javascript: void(0)" ng-click="makeModerator(a_user);">Make User a Moderator</a></li>
				                    <li><a href="javascript: void(0)" ng-click="deleteUser(a_user);">Delete User</a></li>
			                    </ul>
		                    </div>

		                    <div class="clearfix"> </div>
						</div>
						<div class="col-xs-1">

						</div>

						<div class="clearfix"> </div>

						<div ng-repeat-end class="profile-view-divider"> </div>

						<br />

						<div class="row">
							<div class="col-xs-4 text-left">
								<a ng-show="numUserListPage > 0" href="javascript: void(0)" ng-click="loadUserListPage(numUserListPage-1)"><< Prev Page</a>
							</div>
							<div class="col-xs-4 text-center">
								Page {{numUserListPage+1}} of {{numUserListTotalPages}}
							</div>
							<div class="col-xs-4 text-right">
								<a ng-show="(admin_data.total_num_users / numUserListToLoad) >= (numUserListPage + 1)" href="javascript: void(0)" ng-click="loadUserListPage(numUserListPage+1)">Next Page >></a>
							</div>
						</div>
					</div>

					<div ng-if="actions.showAdmin == 'moderator_list'">
						<h2>Moderator List ({{admin_data.total_num_moderators}} Total)</h2>
						<p>This is the current list of users who can moderate posts for NetworkEd.</p><br />

						<div ng-repeat-start="a_user in admin_data.moderator_list" class="col-xs-11 profile-view-row row-fluid">
							<div class="col-xs-4 col-no-padding">
								<div class="info-window-image" style="background-image:url('{{ENV.baseUrl}}user_photos/{{a_user.photo}}');">
									<h3 class="white admin_mod_header">
			                    		Moderator
			                    	</h3>
			                        <div class="image-overlay">
			                            <div class="image-overlay-view white" ng-show="hover">
			                                View User Profile
			                            </div>
			                        </div>
		                        </div>
		                    </div>

		                    <div class="col-xs-8">
			                    <i ng-if="a_user.confirmed == 0">User is not yet confirmed. <a href="javascript: void(0)" ng-click="resendUserConfirmationCode(a_user)">Resend their confirmation code</a>.</i>

			                    <a ng-href="{{ENV.baseUrl+'profile/'+a_user.id}}" target="_blank"><h2>{{a_user.first_name}} {{a_user.last_name}}</h2></a>

			                    <table class="table table-striped">
			                    	<tr>
			                    		<td>Email:</td>
			                    		<td>{{a_user.email}}</td>
			                    	</tr>
			                    	<tr>
			                    		<td>User since:</td>
			                    		<td>{{a_user.created_at | dateToISO | date:'M/dd/yyyy'}}</td>
			                    	</tr>
			                    	<tr>
			                    		<td>Number of posts:</td>
			                    		<td>{{a_user.num_posts}}</td>
			                    	</tr>
			                    	<tr>
			                    		<td>Organization:</td>
			                    		<td>{{a_user.organization}}</td>
			                    	</tr>
			                    	<tr>
			                    		<td>Location:</td>
			                    		<td>{{a_user.city}}, {{a_user.state}}</td>
			                    	</tr>
			                    	<tr>
			                    		<td>Bio:</td>
			                    		<td>{{a_user.bio | addEllipsis:500}}</td>
			                    	</tr>
			                    </table>

			                    <p><a href="javascript: void(0)" ng-click="removeModerator(a_user);">Remove as Moderator</a></p>
		                    </div>

		                    <div class="clearfix"> </div>
						</div>
						<div class="col-xs-1">

						</div>

						<div class="clearfix"> </div>

						<div ng-repeat-end class="profile-view-divider"> </div>

						<br />
					</div>
				</div>
			</div>

		</div>

	</div>

@include('includes.foot_admin')