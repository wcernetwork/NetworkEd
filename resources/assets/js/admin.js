var adminApp = angular.module('adminApp', ['textAngular', 'angularFileUpload', 'config', 'infinite-scroll', 'app', 'ui.tinymce', 'ngBootbox']);

adminApp.factory('adminFactory', function($http, $q, ENV) {

	return {
		getAdminData: function(){
			var deferred = $q.defer();

			$http.get(ENV.apiEndpoint+'admin/data')
				.success(function(data) {
					return deferred.resolve(data);
				})
				.error(function(data) {
					return deferred.resolve(data);
				});

			return deferred.promise;
		},
		getApprovalQueue: function(offset, num){
			var deferred = $q.defer();

			$http.get(ENV.apiEndpoint+'admin/approval-queue/'+offset+'/'+num)
				.success(function(data) {
					return deferred.resolve(data);
				})
				.error(function(data) {
					return deferred.resolve(data);
				});

			return deferred.promise;
		},
		approvePost: function(id){
			return $http.post(ENV.apiEndpoint+'posts/approve', {'id':id});
		},
		rejectPost: function(id){
			return $http.post(ENV.apiEndpoint+'posts/reject', {'id':id});
		},
		approveModerator: function(id){
			return $http.post(ENV.apiEndpoint+'admin/approve-moderator', {'id':id});
		},
		rejectModerator: function(id){
			return $http.post(ENV.apiEndpoint+'admin/reject-moderator', {'id':id});
		},
		saveCollection: function(collection){
			return $http.post(ENV.apiEndpoint+'admin/save-collection', {'collection':collection});
		},
		deleteCollection: function(id){
			return $http.post(ENV.apiEndpoint+'admin/delete-collection', {'id':id});
		},
		deletePostFromCollection: function(post_id, collection_id){
			return $http.post(ENV.apiEndpoint+'admin/remove-post-from-collection', {'post_id':post_id,'collection_id':collection_id});
		},
		getUserList: function(offset, num) {
			return $http.get(ENV.apiEndpoint+'admin/user-list/'+offset+'/'+num);
		},
		saveStaticContent: function(page, content) {
			return $http.post(ENV.apiEndpoint+'admin/save-static-content', {'page':page, 'content':content});
		},
		deleteUser: function(user_id) {
			return $http.post(ENV.apiEndpoint+'admin/delete-user', {'id':user_id});
		},
		makeModerator: function(user_id) {
			return $http.post(ENV.apiEndpoint+'admin/make-moderator', {'id':user_id});
		},
		removeModerator: function(user_id) {
			return $http.post(ENV.apiEndpoint+'admin/remove-moderator', {'id':user_id});
		},
		resendUserCode: function(user_id) {
			return $http.post(ENV.apiEndpoint+'admin/resend-user-code', {'id':user_id});
		},
		deleteTag: function(id) {
			return $http.post(ENV.apiEndpoint+'admin/delete-tag', {'id': id});
		},
		moveTag: function(tag_id, cat_id) {
			return $http.post(ENV.apiEndpoint+'admin/move-tag', {'tag_id' : tag_id, 'category_id' : cat_id});
		},
		renameCategory: function(id, newName) {
			return $http.post(ENV.apiEndpoint+'admin/rename-category', {'id': id, 'new': newName});
		},
		renameTag: function(id, category_id, newName) {
			return $http.post(ENV.apiEndpoint+'admin/rename-tag', {'id' : id, 'category_id' : category_id, 'newName': newName});
		},
		approveTag: function(id) {
			return $http.post(ENV.apiEndpoint+'admin/approve-tag', {'id' : id});
		},
		addNewTag: function(id, tag_name) {
			return $http.post(ENV.apiEndpoint+'admin/add-new-tag', {'id': id, 'name': tag_name});
		}
	}
});

adminApp.controller('AdminController', function($scope, postFactory, adminFactory, ENV, $upload, $timeout, $ngBootbox, $compile) {

	$scope.ENV = ENV;

	$scope.actions = new Array();
	$scope.actions.showCollectionPosts = false;

	$scope.current_user = window.networked.user;

	$scope.tinymceOptions = {
		height: 500,
		content_css: ENV.baseUrl+'assets/css/all.min.css',
		plugins: 'code paste table link colorpicker hr image',
		menubar: false,
		toolbar: [
			'undo redo | cut copy paste pastetext | bold italic underline | alignleft aligncenter alignright alignjustify | styleselect | bullist numlist',
			'table | link | hr | code'
		]
	};

	adminFactory.getAdminData().then(function(data){

		$scope.admin_data = data;

		$scope.admin_data.about_page = data.about_content;
		$scope.admin_data.fellows_page = data.fellows_content;

		if (data.hasOwnProperty('approval_queue'))
		{
			$scope.numApprovalQueueLoaded = data.approval_queue.length;
		}

		if (data.hasOwnProperty('collections'))
		{
			angular.forEach($scope.admin_data.collections, function(collection) {
				if (parseInt(collection.active)) collection.active = 'true';
				else collection.active = 'false';
				collection.old_name = collection.name;
			});
		}

		$scope.numUserListToLoad = 15;
		$scope.numUserListPage = 0;
		$scope.numUserListTotalPages;
		if (data.hasOwnProperty('user_list'))
		{
			$scope.numUserListTotalPages = Math.ceil($scope.admin_data.total_num_users / $scope.numUserListToLoad);

			angular.forEach($scope.admin_data.user_list, function(a_user) {
				a_user.user_role_names = '';
				if (typeof a_user.role_title === 'string')
				{
					a_user.user_role_names = a_user.role_title;
					//convert to array
					a_user.role_title = [ a_user.role_title ];
				}
				else if (a_user.role_title && a_user.role_title.length > 0)
				{
					a_user.user_role_names = a_user.role_title.join(', ');
				}

				//check if 'Other' role is selected
				var other_idx;
				if (a_user.role_title)
				{
					other_idx = a_user.role_title.indexOf('Other');
					if (other_idx >= 0 && a_user.role_title_other)
					{
						a_user.user_role_names += ' (' + a_user.role_title_other + ')';
					}
				}
			});
		}

		if (data.hasOwnProperty('moderator_queue'))
		{
			angular.forEach($scope.admin_data.moderator_queue, function(moderator) {
				moderator.user_role_names = '';
				if (typeof moderator.role_title === 'string')
				{
					moderator.user_role_names = moderator.role_title;
					//convert to array
					moderator.role_title = [ moderator.role_title ];
				}
				else if (moderator.role_title && moderator.role_title.length > 0)
				{
					moderator.user_role_names = moderator.role_title.join(', ');
				}

				//check if 'Other' role is selected
				var other_idx;
				if (moderator.role_title)
				{
					other_idx = moderator.role_title.indexOf('Other');
					if (other_idx >= 0 && moderator.role_title_other)
					{
						moderator.user_role_names += ' (' + moderator.role_title_other + ')';
					}
				}
			});
		}

		if (window.networked.tab)
		{
			$scope.actions.showAdmin = window.networked.tab;
		}
		else
		{
			$scope.actions.showAdmin = '';
		}

	});

	$scope.loadingApprovalQueue = false;
	$scope.loadedAllApprovalQueue = false;
	$scope.loadMoreApprovalQueue = function()
	{
		if ($scope.loadingApprovalQueue) return;
		$scope.loadingApprovalQueue = true;

		adminFactory.getApprovalQueue($scope.numApprovalQueueLoaded, 15).then(function(data){

			if (data.length == 0)
			{
				$scope.loadedAllApprovalQueue = true;
			}
			else
			{
				angular.forEach(data, function(item) {
					$scope.admin_data.approval_queue.push(item);
				});

				$scope.numApprovalQueueLoaded += data.length;
			}

			$scope.loadingApprovalQueue = false;

		});
	}

	$scope.approvePost = function(post)
	{
		adminFactory.approvePost(post.id).then(function(data){

			//remove post from the queue
			if (data.data == 200)
			{
				angular.forEach($scope.admin_data.approval_queue, function(item, index) {

					if (item.id == post.id)
					{
						$scope.admin_data.approval_queue.splice(index, 1);
						$scope.admin_data.approval_queue_count--;
					}
				});
			}
		});
	}

	$scope.rejectPost = function(post)
	{
		$ngBootbox.confirm('Are you sure you want to reject this post?')
		    .then(function() {
		        //confirmed
		        adminFactory.rejectPost(post.id).then(function(data){

					//remove post from the queue
					if (data.data == 200)
					{
						angular.forEach($scope.admin_data.approval_queue, function(item, index) {

							if (item.id == post.id)
							{
								$scope.admin_data.approval_queue.splice(index, 1);
								$scope.admin_data.approval_queue_count--;
							}
						});
					}
				});
		    }, function() {
		        //dismissed
		    });
	}

	$scope.approveModerator = function(user)
	{
		adminFactory.approveModerator(user.id).then(function() {
			window.location.replace(ENV.baseUrl+'admin/moderator_requests');
		});
	}

	$scope.rejectModerator = function(user)
	{
		adminFactory.rejectModerator(user.id).then(function() {
			window.location.replace(ENV.baseUrl+'admin/moderator_requests');
		});
	}

	$scope.addCollection = function()
	{
		$ngBootbox.prompt('New collection name:')
			.then(function(result) {
				//prompt returned
				adminFactory.saveCollection({
					thumbnail: '',
					name: result,
					active: 'false', 
					activeVal: 0
				}).then(function(resp) {
					if (resp && resp.data)
					{
						if (resp.data == '409')
						{
							$ngBootbox.alert('A collection already exists with this name');
						}
						else if (resp.data.id)
						{
							$scope.admin_data.collections.push({
								id: resp.data.id,
								slug: resp.data.slug,
								thumbnail: '',
								name: result,
								active: 'false',
								old_name: result
							});
						}
					}
				});
			}, function() {
				//dismissed
			});
	}

	$scope.deleteCollection = function(collection)
	{
		$ngBootbox.confirm('Are you sure you want to delete the Collection ' + collection.name + '?')
		    .then(function() {
		        //confirmed
		        if (collection.id)
				{
					adminFactory.deleteCollection(collection.id).then(function() {
						
					});
				}

				var index = $scope.admin_data.collections.indexOf(collection);
				$scope.admin_data.collections.splice(index, 1);
		    }, function() {
		        //dismissed
		    });
	}

	$scope.pendingSave;
	$scope.saveCollection = function(collection)
	{
		if (collection.name)
		{
			if ($scope.pendingSave)
			{
				$timeout.cancel($scope.pendingSave);
			}

			$scope.pendingSave = $timeout(function() {
				//post thumbnail, name, and active
				if (collection.active == 'true') collection.activeVal = 1;
				else collection.activeVal = 0;
				adminFactory.saveCollection(collection).then(function(resp) {
					if (resp && resp.data)
					{
						if (resp.data == '409')
						{
							$ngBootbox.alert('A collection already exists with this name');
							collection.name = collection.old_name;
						}
						else if (resp.data.id)
						{
							collection.slug = resp.data.slug;
							collection.old_name = resp.data.name;
						}
					}
				});
			}, 1000);
		}
	}

	$scope.uploadCollectionImage = function($files, $event, updateObject)
	{
		// https://github.com/danialfarid/angular-file-upload
		var file = $files[0];
		
		$scope.upload = $upload.upload({
			url: ENV.apiEndpoint+'admin/upload-collection-image',
			file: file,
		}).success(function(data, status, headers, config) {
			// file is uploaded successfully
			updateObject.thumbnail = data.thumbnail;

			$scope.saveCollection(updateObject);
		});
	}

	$scope.activeCollection;
	$scope.manageCollection = function(collection)
	{
		$scope.activeCollection = collection;

		postFactory.getCollection(collection.slug).then(function(data) {
			collection.posts = data.posts;

			$scope.actions.showCollectionPosts = true;
		});
	}

	$scope.deletePostFromCollection = function(collection, post)
	{
		adminFactory.deletePostFromCollection(post.id, collection.id).then(function(data) {
			var index = collection.posts.indexOf(post);
			collection.posts.splice(index, 1);
		});
	}

	$scope.loadUserListPage = function(page)
	{
		adminFactory.getUserList(page*$scope.numUserListToLoad, $scope.numUserListToLoad).then(function(data) {
			$scope.admin_data.user_list = data['data'];
			$scope.numUserListPage = page;
		});
	}

	$scope.saveStaticContent = function(page, content)
	{
		adminFactory.saveStaticContent(page, content).then(function(data) {
			$ngBootbox.alert('Saved!');
		});
	}

	$scope.onStaticContentFileSelect = function($files, $event, contentPage)
	{
		// https://github.com/danialfarid/angular-file-upload
		var file = $files[0];
		
		$scope.upload = $upload.upload({
			url:ENV.apiEndpoint+'admin/upload-static-content-media',
			file: file,
		}).success(function(data, status, headers, config) {
			if (data != 500)
			{
				var img = '<img src="/uploads/'+data+'" />';

				if (contentPage == 'about')
				{
					$scope.admin_data.about_page = img + $scope.admin_data.about_page;
				}
				else if (contentPage == 'fellows')
				{
					$scope.admin_data.fellows_page += img + $scope.admin_data.fellows_page;
				}
			}
		});
	}

	$scope.deleteUser = function(user)
	{
		if (!user.is_admin){
			$ngBootbox.confirm('Are you sure you want to permanently delete the user ' + user.first_name + ' ' + user.last_name + ' from the system? This will reassign all of their posts to you.')
			    .then(function() {
			        //confirmed
			        adminFactory.deleteUser(user.id).then(function(data) {
						if (data['data'] != 500)
						{
							$ngBootbox.alert('User deleted and posts reassigned to your user.').then(function( ){
								window.location.replace(ENV.baseUrl+'admin/user_list');
							});
						}
						else
						{
							$ngBootbox.alert('Could not delete user.');
						}
					});
			    }, function() {
			        //dismissed
			    });
		}
	}

	$scope.makeModerator = function(user)
	{
		if (!user.is_moderator){
			$ngBootbox.confirm('Are you sure you want to make ' + user.first_name + ' ' + user.last_name + ' a moderator?')
			    .then(function() {
			        //confirmed
			        adminFactory.makeModerator(user.id).then(function(data) {
						if (data['data'] != 500)
						{
							user.is_moderator = true;
							user.role_id = 2;

							$ngBootbox.alert(user.first_name + ' ' + user.last_name + ' is now a moderator.').then(function() {
								window.location.replace(ENV.baseUrl+'admin/user_list');
							});
							
						}
						else
						{
							$ngBootbox.alert('Could not make user a moderator.');
						}
					});
			    }, function() {
			        //dismissed
			    });
		}
	}

	$scope.removeModerator = function(user)
	{
		$ngBootbox.confirm('Are you sure you want to revoke moderator privileges from ' + user.first_name + ' ' + user.last_name + '?')
		    .then(function() {
		        //confirmed
		        adminFactory.removeModerator(user.id).then(function(data) {
					$ngBootbox.alert('User is no longer a moderator.').then(function() {
						window.location.replace(ENV.baseUrl+'admin/moderator_list');
					});
				});
		    }, function() {
		        //dismissed
		    });
	}

	$scope.resendUserConfirmationCode = function(user)
	{
		adminFactory.resendUserCode(user.id).then(function(data) {
			$ngBootbox.alert('User confirmation code resent. The email should be delivered to the user shortly.');
		});
	}

	$scope.renameCategory = function(id)
	{
		$ngBootbox.prompt('Enter new name for category:').then(function(result) {
			adminFactory.renameCategory(id, result).then(function(data) {
				if (data['data'] == 500)
				{
					$ngBootbox.alert('A category already exists with this name.');
				}
				else
				{
					window.location.replace(ENV.baseUrl+'admin/'+$scope.actions.showAdmin);
				}
			});
		});
	}

	$scope.deleteTag = function(tag)
	{
		$ngBootbox.confirm('Are you sure you want to delete the tag ' + tag.name + '?')
		    .then(function() {
		        //confirmed
		        adminFactory.deleteTag(tag.id).then(function(data) {
					window.location.replace(ENV.baseUrl+'admin/'+$scope.actions.showAdmin);
				});
		    }, function() {
		        //dismissed
		    });
	}

	//move tag to a different category
	$scope.moveTagCategoryId;
	$scope.moveTag = function(tag, currentCatName)
	{
		var tplCrop = '<select ng-model="moveTagCategoryId" class="form-control"><option value="{{obj.category.id}}" ng-repeat="obj in admin_data.tags">{{obj.category.name}}</option></select>';
		var template = angular.element(tplCrop);
		var linkFn = $compile(template);
		var html = linkFn($scope);

		var options = {
			title: 'Move <strong>'+tag.name+'</strong> to a new category.<br />Current category is <strong>'+currentCatName+'</strong>.',
			message: html,
			buttons: {
				warning: {
					label: 'Cancel',
					className: 'btn-gray',
					callback: function() {

					}
				},
				success: {
					label: 'Move',
					className: 'btn-red',
					callback: function() {
						adminFactory.moveTag(tag.id, $scope.moveTagCategoryId).then(function() {
							window.location.replace(ENV.baseUrl+'admin/'+$scope.actions.showAdmin);
						});
					}
				}
			}
		}

		$ngBootbox.customDialog(options);
	}

	$scope.startRenameTag = function(tag)
	{
		tag.old_name = tag.name;
		tag.edit = true;
	}

	$scope.renameTag = function(tag)
	{
		adminFactory.renameTag(tag.id, tag.category_id, tag.name).then(function(data) {
			if (data.data.response == 500)
			{
				$ngBootbox.alert('A tag already exists with this name in the category <strong>'+data.data.tag.tag_category.name+'</strong>.');
				tag.name = tag.old_name;
				tag.old_name = null;
			}
			else
			{
				window.location.replace(ENV.baseUrl+'admin/'+$scope.actions.showAdmin);
			}
		});
	}

	$scope.cancelTagUpdate = function(tag)
	{
		if (tag.old_name){
			tag.name = tag.old_name;
			tag.old_name = null;
		}
		tag.edit = false;
	}

	$scope.approveTag = function(tag)
	{
		adminFactory.approveTag(tag.id).then(function(data) {
			window.location.replace(ENV.baseUrl+'admin/'+$scope.actions.showAdmin);
		});
	}

	$scope.addNewTag = function(id)
	{
		$ngBootbox.prompt('Enter name for new tag:').then(function(result) {
			adminFactory.addNewTag(id, result).then(function(data) {
				if (data.data.response == 500)
				{
					$ngBootbox.alert('A tag already exists with this name in the category <strong>'+data.data.tag.tag_category.name+'</strong>.');
				}
				else
				{
					window.location.replace(ENV.baseUrl+'admin/'+$scope.actions.showAdmin);
				}
			});
		});
	}

});