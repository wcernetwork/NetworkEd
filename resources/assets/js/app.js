var app = angular.module('app', ['ui.router', 'ui.bootstrap', 'uiGmapgoogle-maps', 'textAngular', 'angularFileUpload', 'cfp.loadingBar', 'config', 'ngAnimate', 'infinite-scroll', 'ngBootbox']);

app.factory('userFactory', function($http, $q, ENV) {

	return {
		getUserProfile: function(user_id){
			var deferred = $q.defer();

			$http.get(ENV.apiEndpoint+'user/profile/'+user_id)
				.success(function(data) {
					return deferred.resolve(data);
				})
				.error(function(data) {
					return deferred.resolve(data);
				});

			return deferred.promise;
		},
		getUserPosts: function(user_id, offset, num){
			var deferred = $q.defer();

			$http.get(ENV.apiEndpoint+'user/user-posts/'+user_id+'/'+offset+'/'+num)
				.success(function(data) {
					return deferred.resolve(data);
				})
				.error(function(data) {
					return deferred.resolve(data);
				});

			return deferred.promise;
		},
		getUserFavorites: function(user_id, offset, num){
			var deferred = $q.defer();

			$http.get(ENV.apiEndpoint+'user/user-favorites/'+user_id+'/'+offset+'/'+num)
				.success(function(data) {
					return deferred.resolve(data);
				})
				.error(function(data) {
					return deferred.resolve(data);
				});

			return deferred.promise;
		},
		getApprovalQueue: function(user_id, offset, num){
			var deferred = $q.defer();

			$http.get(ENV.apiEndpoint+'user/approval-queue/'+user_id+'/'+offset+'/'+num)
				.success(function(data) {
					return deferred.resolve(data);
				})
				.error(function(data) {
					return deferred.resolve(data);
				});

			return deferred.promise;
		},
		requestModeratorRole: function(){
			var deferred = $q.defer();

			$http.post(ENV.apiEndpoint+'user/request-moderator-role')
				.success(function(data) {
					return deferred.resolve(data);
				})
				.error(function(data) {
					return deferred.resolve(data);
				});

			return deferred.promise;
		}
	}
});

app.factory('postFactory', function($http, $q, ENV) {

	return {
		getSearch: function(params){
			var deferred = $q.defer();

			$http.post(ENV.apiEndpoint+'search/get-posts', params)
				.success(function(data) {
					return deferred.resolve(data);
				})
				.error(function(data) {
					return deferred.resolve(data);
				});

			return deferred.promise;
		},
		getCollection: function(name){
			var deferred = $q.defer();

			$http.get(ENV.apiEndpoint+'search/posts-collection/'+name)
				.success(function(data) {
					return deferred.resolve(data);
				})
				.error(function(data) {
					return deferred.resolve(data);
				});

			return deferred.promise;
		},
		getNetworkEvents: function(){
			var deferred = $q.defer();

			$http.get(ENV.apiEndpoint+'search/network-events')
				.success(function(data) {
					return deferred.resolve(data);
				})
				.error(function(data) {
					return deferred.resolve(data);
				});

			return deferred.promise;
		},
		getActiveCollections: function(){
			var deferred = $q.defer();

			$http.get(ENV.apiEndpoint+'search/active-collections')
				.success(function(data) {
					return deferred.resolve(data);
				})
				.error(function(data) {
					return deferred.resolve(data);
				});

			return deferred.promise;
		},
		getAllCollections: function(){
			var deferred = $q.defer();

			$http.get(ENV.apiEndpoint+'search/all-collections')
				.success(function(data) {
					return deferred.resolve(data);
				})
				.error(function(data) {
					return deferred.resolve(data);
				});

			return deferred.promise;
		},
		getSearchInRadius: function(latitude, longitude, radius){
			var deferred = $q.defer();

			$http.get(ENV.apiEndpoint+'search/posts-within-radius/'+latitude+'/'+longitude+'/'+radius)
				.success(function(data) {
					return deferred.resolve(data);
				})
				.error(function(data) {
					return deferred.resolve(data);
				});

			return deferred.promise;
		},
		getTags: function(){
			var deferred = $q.defer();

			$http.get(ENV.apiEndpoint+'search/tags')
				.success(function(data) {
					return deferred.resolve(data);
				})
				.error(function(data) {
					return deferred.resolve(data);
				});

			return deferred.promise;
		},
		getPost: function(id){
			var deferred = $q.defer();

			$http.get(ENV.apiEndpoint+'posts/post/'+id)
				.success(function(data) {
					return deferred.resolve(data);
				})
				.error(function(data) {
					return deferred.resolve(data);
				});

			return deferred.promise;
		},
		checkForDuplicates: function(id, title){
			var deferred = $q.defer();

			$http.post(ENV.apiEndpoint+'posts/check-duplicate', { 'id' : id, 'title' : title })
				.success(function(data) {
					return deferred.resolve(data);
				})
				.error(function(data) {
					return deferred.resolve(data);
				});

			return deferred.promise;
		},
		deletePost: function(id){
			var deferred = $q.defer();

			$http.post(ENV.apiEndpoint+'posts/delete', { 'id' : id })
				.success(function(data) {
					return deferred.resolve(data);
				})
				.error(function(data) {
					return deferred.resolve(data);
				});

			return deferred.promise;
		},
		likePost: function(id){
			var deferred = $q.defer();

			$http.post(ENV.apiEndpoint+'posts/like', { 'id' : id })
				.success(function(data) {
					return deferred.resolve(data);
				})
				.error(function(data) {
					return deferred.resolve(data);
				});

			return deferred.promise;
		},
		favoritePost: function(id){
			return $http.post(ENV.apiEndpoint+'posts/favorite', { 'id' : id });
		},
		removeFavorite: function(id){
			return $http.post(ENV.apiEndpoint+'posts/remove-favorite', { 'id' : id });
		},
		checkTagExistence: function(name){
			return $http.post(ENV.apiEndpoint+'posts/check-tag-existence', {name : name});
		},
		geocode: function(address){
			var deferred = $q.defer();

			$http.get('https://maps.googleapis.com/maps/api/geocode/json?address='+address+'&key='+ENV.googleMapsKey)
				.success(function(data) {
					return deferred.resolve(data);
				})
				.error(function(data) {
					return deferred.resolve(data);
				});

			return deferred.promise;
		},
		savePost: function(data){
			return $http.post(ENV.apiEndpoint+'posts/save', data);
		},
		approvePost: function(id){
			return $http.post(ENV.apiEndpoint+'posts/approve', {'id':id});
		},
		rejectPost: function(id){
			return $http.post(ENV.apiEndpoint+'posts/reject', {'id':id});
		},
		validateVideo: function(link){
			return $http.post(ENV.apiEndpoint+'posts/validate-video', { 'video_link' : link });
		},
		batchAddToCollection: function(slug, name, posts) {
			return $http.post(ENV.apiEndpoint+'admin/batch-add-to-collection', { 'collection_slug' : slug, 'collection_name' : name, 'posts' : posts });
		},
		addToCollection: function(slug, name, id) {
			return $http.post(ENV.apiEndpoint+'admin/add-to-collection', { 'collection_slug' : slug, 'collection_name' : name, 'id' : id });
		},
		getAllUsers: function(){
			return $http.get(ENV.apiEndpoint+'admin/all-users');
		},
		updateOwner: function(user_id, post_id){
			return $http.post(ENV.apiEndpoint+'admin/update-post-owner', {'user_id' : user_id, 'post_id' : post_id})
		},
		getImageFromLink: function(url){
			return $http.post(ENV.apiEndpoint+'posts/get-image-from-link', {'url' : url})
		},
		updateViews: function(post_id){
			return $http.post(ENV.apiEndpoint+'posts/update-views', {'id' : post_id});
		}
	}

});

app.config(function($stateProvider, $urlRouterProvider, $locationProvider, uiGmapGoogleMapApiProvider, ENV, cfpLoadingBarProvider, $provide){

	$urlRouterProvider.otherwise("");

	$stateProvider
		.state('main', {
			url: "/",
			template: '',
			data: { state: '' }
		})
		.state('post', {
			url: "/post/:postID",
			templateUrl: ENV.baseUrl+"partials/post.html",
	      	controller: 'PostController',
			data: { state: 'post' }
		});

	uiGmapGoogleMapApiProvider.configure({
		key: ENV.googleMapsKey,
		v: '3.17',
		libraries: 'weather,geometry,visualization'
	});

	cfpLoadingBarProvider.includeSpinner = false;

	$locationProvider.hashPrefix('!');

	$provide.decorator('tabsetDirective', function($delegate) {
		$delegate[0].templateUrl = ENV.baseUrl+'partials/tabset_template.html';
		return $delegate;
	});

});

app.run(function ($state, $rootScope, $window, $location) {
	$rootScope.$state = $state;

	$rootScope.$on('$stateChangeSuccess', function(event) {
		if (!$window.ga)
			return;

		$window.ga('send', 'pageview', {page: $location.path()});

		if ($state.current && $state.current.name == 'post'){
			$window.ga('send', 'event', 'post', 'view', $location.path());
		}
	});
});

app.directive('ajDateTimePicker', function() {

	var format = 'MM/DD/YY hh:mm a';

	return {
		restrict: 'A',
		require: 'ngModel',
		scope: {
			dateVal: '='
		},
		link: function (scope, element, attributes, ctrl) {

			element.datetimepicker({
				format: format,
				defaultDate: new Date(scope.dateVal)
			});

			var picker = element.data('DateTimePicker');

			element.bind('blur', function(blurEvent) {
				scope.$apply(function() {
					ctrl.$setViewValue(element.val());
				});
			});
		}
	}

});

app.controller('AboutController', function($scope, ENV) {

	$scope.ENV = ENV;

	$scope.tabs = [
		{ title:'About', slug:'about', active:true },
		{ title:'Fellows', slug:'fellows', active:false },
		{ title: 'Events', slug:'events', active:false }
	];

	$scope.$watch('actions.aboutPageActive', function(page) {
		angular.forEach($scope.tabs, function(tab) {
			if (page == tab.slug)
			{
				tab.active = true;
			}
			else
			{
				tab.active = false;
			}
		});
	});

});

app.controller('PostController', function($scope, $rootScope, $window, $stateParams, postFactory, $timeout, $location, $upload, ENV, $state, $ngBootbox) {

	$rootScope.$$listeners.tagSelected = [];
	$rootScope.$$listeners.tagDeselected = [];

	$scope.actions.postMode = 'view';
	$scope.actions.postReady = false;
	$scope.actions.showTags = false;
	$scope.actions.duplicateWarning = false;
	$scope.actions.ignoreDuplicates = false;

	$scope.post_map = {
		zoom: 12,
		options: {
			panControl: false,
			zoomControl: false,
			streetViewControl: false,
			mapTypeControl: false,
			scrollwheel: false,
			draggable: false,
			disableDoubleClickZoom: true
		}
	};
	$scope.large_post_map = {
		zoom: 12,
		options: {},
		control: {}
	};

	if ($stateParams.postID == 'add')
	{
		$scope.actions.postMode = 'add';

		$scope.post_map.center = {
			latitude: 43.073052,
			longitude: -89.401230
		};
		$scope.large_post_map.center = {
			latitude: 43.073052,
			longitude: -89.401230
		};

		$scope.post = new Object();
		$scope.post.id = -1;
		$scope.post.user_id = window.networked.user.id;
		$scope.post.user = new Object();
		$scope.post.user.first_name = window.networked.user.first_name;
		$scope.post.user.last_name = window.networked.user.last_name;
		$scope.post.user.id = window.networked.user.id;
		$scope.post.user.city = window.networked.user.city;
		$scope.post.user.state = window.networked.user.state;
		if (window.networked.user.photo == '')
		{
			$scope.post.user.photo = 'assets/images/default_thumbnail.png';
		}
		else
		{
			$scope.post.user.photo = window.networked.user.photo;
		}
		$scope.post.post_type_id = 1;
		$scope.post.post_type = new Object();
		$scope.post.post_type.id = 1;
		$scope.post.post_type.name = "Person";
		$scope.post.post_type.icon_file = "person";
		$scope.post.thumbnail = 'empty_thumbnail.png';
		$scope.post.primary_media = "";
		$scope.post.expiration_date = moment(Date.now()).format('MM/DD/YY hh:mm a');
		$scope.post.summary_chars_remaining = 25;

		$scope.actions.postReady = true;
	}
	else
	{
		var post_id = $stateParams.postID;
		if (!post_id && window.networked.post_id)
		{
			post_id = window.networked.post_id;
		}

		//update view count and last accessed date
		postFactory.updateViews(post_id).then(function(data){});

		postFactory.getPost(post_id).then(function(data) {

			if (!post_id || data == '')
		 	{
		 		$scope.post = {};
		 		$scope.post.nonexistant = true;
		 	}
			else
			{
				$scope.actions.postMode = 'view';

				$scope.post = data;

				$scope.post.coords = {
					latitude: $scope.post.latitude,
					longitude: $scope.post.longitude
				}

				$scope.post.icon = ENV.baseUrl+'assets/images/icons/marker.png';

				$scope.post_map.center = {
					latitude: $scope.post.latitude,
					longitude: $scope.post.longitude
				};
				$scope.large_post_map.center = {
					latitude: $scope.post.latitude,
					longitude: $scope.post.longitude
				};

				if (!$scope.post.expiration_date)
				{
					$scope.post.expiration_date = moment(Date.now()).format('MM/DD/YY hh:mm a');
				}
				else
				{
					$scope.post.expiration_date = moment($scope.post.expiration_date).format('MM/DD/YY hh:mm a');
				}

			}

			if($scope.post.summary){
				$scope.post.summary_chars_remaining = 25 - $scope.post.summary.length;
			}
			else{
				$scope.post.summary_chars_remaining = 25;
			}	

			$scope.actions.postReady = true;
		});
	}

	$scope.viewLargeMap = function()
	{
		$scope.actions.showLargeMap = true;

		$timeout(function(){
			var map_instance = $scope.large_post_map.control.getGMap();
			var latlng = {
				lat: $scope.post_map.center.latitude,
				lng: $scope.post_map.center.longitude
			};

			google.maps.event.trigger(map_instance, 'resize');
			map_instance.setCenter(latlng);
		}, 1);
	}

	$scope.closeLargeMap = function()
	{
		$scope.actions.showLargeMap = false;
	}

	$scope.likePost = function(post)
	{
		if ($scope.actions.postMode == 'view' && !post.liked)
		{
			postFactory.likePost(post.id).then(function(data) {

				if (data == 200)
				{
					post.likes++;

					post.liked = true;
				}
			});
		}
	}

	$scope.addToFavorites = function(post)
	{
		if ($scope.actions.postMode == 'view')
		{
			postFactory.favoritePost(post.id).then(function(data){
				if (data.data == 401)
				{
					post.favorited = false;
				}
				else
				{
					post.favorited = true;
				}

			});
		}
	}

	$scope.removeFromFavorites = function(post)
	{
		if ($scope.actions.postMode == 'view')
		{
			postFactory.removeFavorite(post.id).then(function(data){
				if (data.data == 200)
				{
					post.favorited = false;
				}

			});
		}
	}

	$scope.getImageFromLink = function(post, url, postMedia)
	{
		if (url)
		{
			postFactory.getImageFromLink(url)
				.success(function(data){
					if (postMedia == 'primary_media')
					{
						post.primary_media = data.path;
						post.thumbnail = data.thumbnail;
						post.thumbnail_sm = data.thumbnail_sm;
					}
					else if (postMedia == 'media_2')
					{
						post.media_2 = data.path;
						post.media_2_type = 'image';
						post.media_2_editing = false;
					}
					else if (postMedia == 'media_3')
					{
						post.media_3 = data.path;
						post.media_3_type = 'image';
						post.media_3_editing = false;
					}
				})
				.error(function(){
					if (postMedia == 'primary_media')
					{
						$ngBootbox.alert('Your image could not be uploaded. Please try again with a different image, or use the default image instead.')
						    .then(function() {
						        post.primary_media = 'default_image.jpg';
								post.thumbnail = 'default_image.jpg';
								post.thumbnail_sm = 'default_thumbnail.png';
						    });
					}
					else
					{
						$ngBootbox.alert('Your image could not be uploaded. Please try again with a different image.');
					}
				});
		}
	}

	$scope.onFileSelect = function($files, $event, postMedia)
	{
		// https://github.com/danialfarid/angular-file-upload
		var file = $files[0];
		
		//different routes for primary vs supplementary media (supplementary doesn't need thumbs generated)
		var upload_route;
		if (postMedia == 'primary_media' || postMedia == 'primary_media_doc_thumb'){
			upload_route = 'posts/upload-media';
		}
		else{
			upload_route = 'posts/upload-media-supplementary';
		}

		$scope.upload = $upload.upload({
			url:ENV.apiEndpoint+upload_route,
			file: file,
		}).success(function(data, status, headers, config) {
			if (data == 500)
			{
				if (postMedia == 'primary_media')
				{
					$ngBootbox.alert('Your image was too large to be uploaded. Please try again with a smaller image, or use the default image provided.')
					    .then(function() {
					        $scope.post.primary_media = 'default_image.jpg';
							$scope.post.thumbnail = 'default_image.jpg';
							$scope.post.thumbnail_sm = 'default_thumbnail.png';
					    });
				}
				else
				{
					$ngBootbox.alert('Your image was too large to be uploaded. Please try again with a smaller image.');
				}
			}
			else
			{
				// file is uploaded successfully
				if (postMedia == 'primary_media')
				{
					$scope.post.primary_media = data.path;
					$scope.post.thumbnail = data.thumbnail;
					$scope.post.thumbnail_sm = data.thumbnail_sm;
				}
				else if (postMedia == 'media_2')
				{
					$scope.post.media_2 = data.path;
					$scope.post.media_2_editing = false;
				}
				else if (postMedia == 'media_3')
				{
					$scope.post.media_3 = data.path;
					$scope.post.media_3_editing = false;
				}
				else if (postMedia == 'primary_media_doc_thumb')
				{
					$scope.post.thumbnail = data.thumbnail;
					$scope.post.thumbnail_sm = data.thumbnail_sm;
				}
			}
			
		}).error(function(data, status, headers, config) {
			if (postMedia == 'primary_media')
			{
				$ngBootbox.alert('Your image was too large to be uploaded. Please try again with a smaller image, or use the default image provided.')
				    .then(function() {
				        $scope.post.primary_media = 'default_image.jpg';
						$scope.post.thumbnail = 'default_image.jpg';
						$scope.post.thumbnail_sm = 'default_thumbnail.png';
				    });
			}
			else
			{
				$ngBootbox.alert('Your image was too large to be uploaded. Please try again with a smaller image, or use the default image provided.');
			}
		});
	}

	$scope.addSupplementaryMedia = function(post)
	{
		if (post.media_2 == null)
		{
			post.media_2_editing = true;
		}
		else if (post.media_3 == null)
		{
			post.media_3_editing = true;
		}
	}

	$scope.deleteSupplementaryMedia = function(post, media_num)
	{
		$ngBootbox.confirm('Are you sure you want to remove this ' + post['media_'+media_num+'_type'] + '?')
			.then(function() {
				//confirmed
				post['media_'+media_num] = null;
				post['media_'+media_num+'_type'] = null;
			}, function() {
				//dismissed
			});
	}


	var validateVideoTimeout;
	$scope.validateVideo = function(post, field)
	{
		if (validateVideoTimeout)
		{
			$timeout.cancel(validateVideoTimeout);
		}

		validateVideoTimeout = $timeout(function() {
			if (post[field] != "")
			{
				postFactory.validateVideo(post[field]).then(function(data){
					if (data.data == 400)
					{
						$ngBootbox.alert('This is not a valid video file.');
					}
					else
					{
						if (field == 'primary_media')
						{
							post.thumbnail = data.data.thumbnail;
							post.thumbnail_sm = data.data.thumbnail_sm;
							post.video_id = data.data.video_id;
							post.video_host = data.data.video_host;
						}
					}
				});
			}
		}, 1000);
	}

	$scope.validateDocument = function(post, field)
	{
		if (post[field] != "")
		{

		}
	}

	$scope.deletePost = function(post)
	{
		$ngBootbox.confirm('Are you sure you want to delete this post?')
			.then(function() {
				//confirmed
				postFactory.deletePost(post.id).then(function(res) {

					if (res != '200')
					{
						$ngBootbox.alert('You do not have the privileges to delete this post.');
					}
					else
					{
						$ngBootbox.alert('This post has been removed.').then(function() {
							window.location.href = ENV.baseUrl+"search";
						});
					}

				});
			}, function() {
				//dismissed
			});
	}

	$scope.savePost = function(post)
	{
		if (post.primary_media_type == 'image-link'){
			post.primary_media_type = 'image';
		}
		if (post.media_2_type == 'image-link'){
			post.media_2_type = 'image';
		}
		if (post.media_3_type == 'image-link'){
			post.media_3_type = 'image';
		}

		var error = false;
		var error_message = "";
		if (!post.hasOwnProperty('primary_media_type') || post.primary_media == '')
		{
			error = true;
			error_message += "Primary Media Type, ";
		}
		if (!post.hasOwnProperty('primary_media') || post.primary_media == '' || post.primary_media === undefined)
		{
			error = true;
			error_message += "Primary Media, ";
		}
		if (!post.hasOwnProperty('title') || post.title == '')
		{
			error = true;
			error_message += "Title, ";
		}
		if (!post.hasOwnProperty('description') || post.description == '')
		{
			error = true;
			error_message += "Description, ";
		}
		if (!post.hasOwnProperty('summary') || post.summary == '')
		{
			error = true;
			error_message += "Summary, ";
		}
		if (!post.hasOwnProperty('location') || post.location == '')
		{
			error = true;
			error_message += "Location Name, ";
		}
		if (!post.hasOwnProperty('address') || post.address == '')
		{
			error = true;
			error_message += "Address, ";
		}
		if (!post.hasOwnProperty('city') || post.city == '')
		{
			error = true;
			error_message += "City, ";
		}
		if (!post.hasOwnProperty('state') || post.state == '')
		{
			error = true;
			error_message += "State, ";
		}
		if (!post.hasOwnProperty('zip') || post.zip == '')
		{
			error = true;
			error_message += "Zip, ";
		}
		if ((post.post_type_id == 3 || post.post_type_id == 5) && (!post.hasOwnProperty('expiration_date') || post.expiration_date == ''))
		{
			error = true;
			error_message += "Expiration Date, ";
		}

		//check for contact details
		if (post.post_type_id == 1)
		{
			//person
			if ((!post.hasOwnProperty('contact_email') || post.contact_email == '') && (!post.hasOwnProperty('contact_phone') || post.contact_phone == '') && (!post.hasOwnProperty('contact_website') || post.contact_website == ''))
			{
				error = true;
				error_message += "one or more Contact Details, ";
			}
		}
		else
		{
			//all other post types
			if ((!post.hasOwnProperty('contact_name') || post.contact_name == '') && (!post.hasOwnProperty('contact_email') || post.contact_email == '') && (!post.hasOwnProperty('contact_website') || post.contact_website == ''))
			{
				error = true;
				error_message += "one or more Contact Details, ";
			}
		}

		if (!post.hasOwnProperty('coords'))
		{
			post.coords = {
				latitude: 0,
				longitude: 0
			}
		}

		var tag_count = 0;
		angular.forEach(post.tags, function(category_tags, index){
			if (index != 'Format')
			{
				tag_count += category_tags.length;
			}
		});

		if (tag_count == 0)
		{
			error = true;
			error_message += "Tags, ";
		}

		//make all links in description open in a new window
		post.description = post.description.replace(/(<a\s(?:(?!href=|target=|>).)*href="http:\/\/(?:(?!target=|>).)*)>/ig, '$1 target="_blank">');

		var hasLink = (post.description.indexOf('<a href=') >= 0);

		var invalidPhone = false;
		if (post.contact_phone)
		{
			invalidPhone = post.contact_phone.replace(/[^0-9]/g,'').length !== 10;
		}

		if (error)
		{
			error_message = error_message.substring(0, error_message.length-2);

			$ngBootbox.alert('You are missing the following fields:\n' + error_message + '. \nPlease fill out all fields before saving.');
		}
		else if (invalidPhone)
		{
			$ngBootbox.alert('The contact phone number must be 10 digits long.');
		}
		else if (!hasLink)
		{
			$ngBootbox.confirm('The description field for your entry does not contain any links. We recommend including links out to external sources. \n\nDo you want to continue saving without adding a link?')
				.then(function() {
					//confirmed
					doSave(post);
				}, function() {
					//dismissed
				});
		}
		else
		{
			doSave(post);
		}
	}

	var doSave = function(post)
	{
		postFactory.savePost(post).then(function(data){
			if (data == 'error' || data == 'incorrect permissions')
			{
				$location.path( ENV.baseUrl );
			}
			else
			{
				//need to do this so top tags get updated, tags on post are updated, etc
				$location.path('/post/'+data.data.id );
				$window.location.reload();
			}
		});
	}

	var geocodeTimeout;
	$scope.geocode = function(post)
	{
		if (geocodeTimeout)
		{
			$timeout.cancel(geocodeTimeout);
		}

		geocodeTimeout = $timeout(function() {
			postFactory.geocode(encodeURIComponent(post.address + " " + post.city + " " + post.state + " " + post.zip)).then(function(data){

				if (data.status == "OK")
				{
					var location = data.results[0].geometry.location;
					$scope.post.coords = {
						latitude: location.lat,
						longitude: location.lng
					}
					post.icon = ENV.baseUrl+'assets/images/icons/marker.png';
					
					$scope.post_map.center.latitude = location.lat;
					$scope.post_map.center.longitude = location.lng;
					
					$scope.large_post_map.center.latitude = location.lat;
					$scope.large_post_map.center.longitude = location.lng;

					//update the address entered to match the geocoded address
					var address_components = {}; 
					jQuery.each(data.results[0].address_components, function(k,v1) {jQuery.each(v1.types, function(k2, v2){address_components[v2]=v1.short_name});});
					post.city = address_components.locality;
					post.state = address_components.administrative_area_level_1;
					post.zip = address_components.postal_code;
				}
			
			});
		}, 1000);
	}

	$scope.calcSummaryCharsRemaining = function(){
		if($scope.post.summary){
			$scope.post.summary_chars_remaining = 25 - $scope.post.summary.length;
		}
		else{
			$scope.post.summary_chars_remaining = 25;
		}
	}

	var duplicateTimeout;
	$scope.checkForDuplicates = function(post)
	{
		//only do this for new posts
		if (!$scope.actions.ignoreDuplicates && post.title && post.title != '' && !post.created_at)
		{
			if (duplicateTimeout)
			{
				$timeout.cancel(duplicateTimeout);
			}

			duplicateTimeout = $timeout(function() {
				postFactory.checkForDuplicates(post.id, post.title).then(function(data) {

					if (data.length > 0)
					{
						post.duplicates = data;
						$scope.actions.duplicateWarning = true;
					}

				});
			}, 1500);
		}
	}

	$scope.updatePostType = function(id)
	{
		$scope.post.post_type_id = id;
		$scope.post.post_type.id = id;

		switch (id) {
    		case 1:
    			$scope.post.post_type.name = 'Person';
    			$scope.post.post_type.icon_file = 'person';
    			break;
    		case 2:
    			$scope.post.post_type.name = 'Place';
    			$scope.post.post_type.icon_file = 'place';
    			break;
    		case 3:
    			$scope.post.post_type.name = 'Event';
    			$scope.post.post_type.icon_file = 'event';
    			break;
    		case 4:
    			$scope.post.post_type.name = 'Project';
    			$scope.post.post_type.icon_file = 'project';
    			break;
    		case 5:
    			$scope.post.post_type.name = 'Network Event';
    			$scope.post.post_type.icon_file = 'network_event';
    			break;
		}
	}

	$scope.showTagsSelectionControl = function()
	{
		if (!$scope.post.hasOwnProperty('tags'))
		{
			$scope.post.tags = new Object();
		}

		$scope.$broadcast('showingPostsTagsSelector', true);
	}

	$rootScope.$on('tagSelected', function(event, message) {
		if (!message.post.hasOwnProperty('tags') || message.post.tags.length == 0)
		{
			message.post.tags = new Object();
		}

		if (!message.post.tags.hasOwnProperty(message.category))
		{
			message.post.tags[message.category] = new Array();
		}

		var tag_index = -1;
		angular.forEach(message.post.tags[message.category], function(tag, idx) {
			if (tag.slug == message.slug)
			{
				tag_index = idx;
			}
		});

		if (tag_index == -1)
		{
			message.post.tags[message.category].push({name: message.tag, slug: message.slug});
		}
	});

	$rootScope.$on('tagDeselected', function(event, message) {

		var tag_index = -1;
		angular.forEach(message.post.tags[message.category], function(tag, idx) {
			if (tag.slug == message.slug)
			{
				tag_index = idx;
			}
		});

		message.post.tags[message.category].splice(tag_index, 1);

		if (message.post.tags[message.category].length == 0)
		{
			delete message.post.tags[message.category];
		}
	});

	$scope.deselectTag = function(category, tag)
	{
		var tag_index = -1;
		angular.forEach($scope.post.tags[category], function(search, idx) {
			if (search.slug == tag.slug)
			{
				tag_index = idx;
			}
		});

		$scope.post.tags[category].splice(tag_index, 1);

		if ($scope.post.tags[category].length == 0)
		{
			delete $scope.post.tags[category];
		}

		$scope.$broadcast('setupPostsTags', true);
	}

	$scope.allCollections;
    postFactory.getAllCollections().then(function(data){
		$scope.allCollections = data;
	});

    $scope.selectedAddCollection;
    $scope.addToCollection = function(post, collection)
    {
    	if (collection)
    	{
    		$ngBootbox.confirm('Are you sure you want to add this post to the collection ' + collection.name + '?')
			    .then(function() {
			        //confirmed
			        postFactory.addToCollection(collection.slug, collection.name, post.id).success(function(data) {
			    		if (data == '409')
			    		{
			    			$ngBootbox.alert('This post already exists in the collection.');
			    		}
			    		else
			    		{
			    			$ngBootbox.alert('Post successfully added!');
			    		}
			    	});
			    }, function() {
			        //dismissed
			    });
		}
    }

    $scope.allUsers;
    if (window.networked.user && window.networked.user.role_id == 1)
    {
    	postFactory.getAllUsers().then(function(data){
    		$scope.allUsers = data['data'];
    		angular.forEach($scope.allUsers, function(user){
    			user.name = user.first_name + ' ' + user.last_name + ' (' + user.email + ')';
    		});
    	});
    }

    $scope.selectedNewUser;
    $scope.assignNewOwner = function(post, user)
    {
    	if (user)
    	{
    		$ngBootbox.confirm('Are you sure you want to re-assign this post to ' + user.name + '?')
			    .then(function() {
			        //confirmed
			        postFactory.updateOwner(user.id, post.id).success(function(data) {
			    		if (data)
			    		{
			    			post.user = data.user;
			    			post.user_id = data.user_id;
			    			$ngBootbox.alert('Post owner changed successfully!');
			    		}
			    		else
			    		{
			    			$ngBootbox.alert('You do not have access to do this.');
			    		}
			    	});
			    }, function() {
			        //dismissed
			    });
		}
    }
});

app.controller('AppController', function($scope, uiGmapGoogleMapApi, ENV, $state, $rootScope) {

	$scope.ENV = ENV;

	$scope.actions = new Array();
	$scope.actions.showLogin = false;

	$scope.actions.showAboutPages = false;

	$scope.actions.showTags = false;
	if (window.networked.hasOwnProperty('showTags') && window.networked.showTags == 'true')
	{
		$scope.actions.showTags = true;
	}

	$scope.actions.authMode = 'login';
	$scope.actions.editMyProfile = false;

	if (window.networked.hasOwnProperty('isCollection') && window.networked.isCollection)
	{
		$scope.page_type = 'collection';
	}

	if (window.networked.hasOwnProperty('resetPassword') && window.networked.resetPassword)
	{
		$scope.actions.showLogin = true;
		$scope.actions.authMode = 'reset';
	}

	$scope.actions.postMode = 'view';
	$scope.actions.showTagsSelection = false;

	$scope.filters = new Object();
	if (window.networked.hasOwnProperty('search_term'))
	{
		$scope.filters.searchTerm = window.networked.search_term;
	}
	else
	{
		$scope.filters.searchTerm = '';
	}
	$scope.filters.tagsSelected = new Object();
	$scope.filters.typeSelected = '';
	$scope.filters.sortSelected = ''; //default to nearest, unless a sort value was selected (default is set in the API controller)
	if (window.networked.sort_order)
	{
		$scope.filters.sortSelected = window.networked.sort_order;
	}

	$scope.current_user = window.networked.user;

	$scope.clickMapOverlay = function($event) {
		if ($($event.target).attr('id') == 'map_canvas_overlay_text')
		{
			window.location.href = ENV.baseUrl+"search";
		}
	}

	$scope.searchWithTags = function() {
		$('#search_tags').val(true);
		$('#search_form').submit();
	}

	if(window.networked.all_posts_search_term){
		$scope.allPostsSearchTerm = window.networked.all_posts_search_term;
	}
	$scope.allPostsSearch = function(offset, sort) {
		var url = ENV.baseUrl + 'posts/' + offset + '/';
		if(!sort){
			url += 'recent';
		}
		else{
			url += sort;
		}
		if($scope.allPostsSearchTerm){
			url += '/' + $scope.allPostsSearchTerm;
		}
		window.location.href = url;
	}
});

app.controller('RegistrationController', function($scope, $upload, ENV) {

	$scope.user_photo = 'default_profile.png';
	$scope.user_background = "";

	$scope.uploadUserProfile = function($files, $event, updateObject, updateField)
	{
		// https://github.com/danialfarid/angular-file-upload
		var file = $files[0];
		
		$scope.upload = $upload.upload({
			url: ENV.apiEndpoint+'user/upload-photo',
			file: file,
		}).success(function(data, status, headers, config) {
			// file is uploaded successfully
			$scope.user_photo = data.thumbnail;

			if (updateObject !== undefined)
			{
				updateObject[updateField] = data.thumbnail;
			}
		});
	}

	$scope.selected_roles = [
		{ name: 'Teacher', selected: false },
		{ name: 'Community Member', selected: false },
		{ name: 'Administrator', selected: false },
		{ name: 'Parent', selected: false },
		{ name: 'Researcher', selected: false },
		{ name: 'Other', selected: false }
	];
});

app.controller('SearchTagsController', function($scope, $rootScope, postFactory, $state) {

	$scope.filters.allTags = new Array(); //this is the array for tags used in search
	$scope.filters.allTagsDisplay = new Array();

	postFactory.getTags().then(function(data){

		$scope.filters.allTags = data;
		
		angular.forEach($scope.filters.allTags, function(item, key) {

			$scope.filters.allTagsDisplay.push({
				'category': key,
				'tags': item
			});

		});

	});

	//this is to select a tag to search on
	$scope.clickTag = function(tagCategory, tag) {

		if (!$scope.actions.searchInProgress && (!tag.disabled || tag.selected))
		{
			tag.selected = !tag.selected;

			if (tag.selected)
			{
				if (!$scope.filters.tagsSelected.hasOwnProperty(tagCategory))
				{
					$scope.filters.tagsSelected[tagCategory] = new Array();
				}

				$scope.filters.tagsSelected[tagCategory].push(tag);
			}
			else
			{
				var tag_index = $scope.filters.tagsSelected[tagCategory].indexOf(tag);
				$scope.filters.tagsSelected[tagCategory].splice(tag_index, 1);

				if ($scope.filters.tagsSelected[tagCategory].length == 0)
				{
					delete $scope.filters.tagsSelected[tagCategory];
				}
			}

			$scope.filters.runSearch = true;
		}
	}

	$scope.clickFilterType = function(type)
	{
		if ($scope.filters.typeSelected == type)
		{
			$scope.filters.typeSelected = '';
		}
		else
		{
			$scope.filters.typeSelected = type;
		}

		$scope.filters.runSearch = true;
	}

});

app.controller('PostTagsController', function($scope, $rootScope, postFactory, $timeout, $ngBootbox) {
	
	$scope.selectPostTags = new Array(); //this is the array for tags used when creating/editing a post

	postFactory.getTags().then(function(data){

		angular.forEach(data, function(item, key) {

			//format is assigned automatically
			if (key != 'Format')
			{
				$scope.selectPostTags.push({
					'category': key,
					'tags': item
				});

			}
		});
	});

	$scope.setupPostsTags = function(event, message)
	{
		angular.forEach($scope.selectPostTags, function(item)
		{
			angular.forEach(item.tags, function(tag, idx)
			{
				if ($scope.post.tags.hasOwnProperty(item.category))
				{
					tag.postHasTag = false;
					angular.forEach($scope.post.tags[item.category], function(search) {
						if (search.slug == tag.slug)
						{
							tag.postHasTag = true;
						}
					});
				}
				else
				{
					tag.postHasTag = false;
				}
			});
		});
	}

	$scope.$on('setupPostsTags', function(event, message) {
		if (message)
		{
			$scope.setupPostsTags(event, message);
		}
	});

	//this is used for selecting tags on Posts
	$scope.$on('showingPostsTagsSelector', function(event, message) {
		if (message)
		{
			$scope.setupPostsTags(event, message);			

			$scope.actions.showTagsSelection = true;
		}
	});

	//this is to select a tag to tag a post
	$scope.selectTag = function(tagCategory, tag, post) {

		tag.postHasTag = !tag.postHasTag;

		if (tag.postHasTag)
		{
			$rootScope.$broadcast('tagSelected', {post: post, category: tagCategory, tag: tag.name, slug: tag.slug});
		}
		else
		{
			$rootScope.$broadcast('tagDeselected', {post: post, category: tagCategory, tag: tag.name, slug: tag.slug});
		}
	}

	$scope.suggestTag = function(post)
	{
		var tagCategory = $scope.selectedSuggestCategory.category;
		var tagName = $scope.suggestTagValue;

		//strip special characters
		tagName.replace(/[\'\"]/g, '');

		postFactory.checkTagExistence(tagName).then(function(data){
			if(data.data.response == 'error'){
				$ngBootbox.alert('A tag with that name already exists in the system, under the category <strong>'+data.data.tag.tag_category.name+'</strong>.');
			}
			else{
				var newTag = { name: data.data.tag_name, slug: data.data.tag_slug, postHasTag: true };

				angular.forEach($scope.selectPostTags, function(item, index) {
					if (item.category == tagCategory)
					{
						item.tags.push(newTag);
					}
				});

				$timeout(function() {
					newTag.postHasTag = true;
					$scope.suggestTagValue = '';
				}, 1);

				$rootScope.$broadcast('tagSelected', {post: post, category: tagCategory, tag: newTag.name, slug: newTag.slug});
			}
		});	
	}

});

app.filter('escape', function() {
	return window.encodeURIComponent;
});

app.filter('dateToISO', function() {
  	return function(input) {
  		if (input)
  		{
  			input = new Date(input).toISOString();
  		}
    	return input;
  	};
});

app.filter('addEllipsis', function() {
	return function (input, numChars) {
		if (input && input.length > numChars) {
			return input.substring(0, numChars) + '...';
		}
		return input;
	}
});

app.filter('htmlToPlainText', function() {
	return function(text) {
    	return String(text).replace(/<[^>]+>/gm, '');
    }
});

app.controller('NetworkEventsController', function($scope, postFactory, ENV) {

	postFactory.getNetworkEvents().then(function(data){

		var markers = [];

		_.forEach(data.posts, function(value, key) {
			var ret = {
				id: value.id,
				title: value.title,
				icon: ENV.baseUrl+'assets/images/icons/'+value.post_type.icon_file+'.png',
				thumbnail_sm: ENV.baseUrl+'uploads/'+value.thumbnail_sm,
				date: value.expiration_date,
				description: value.description,
				archived: value.archived != true ? false : true,
				post_type_id: value.post_type_id
			}

			if (value.thumbnail_sm == '')
			{
				ret.thumbnail_sm = ENV.baseUrl+'assets/images/default_thumbnail.png';
			}

			markers.push(ret);
		});

		$scope.networkEvents = markers;
	});

});

app.controller('CollectionsController', function($scope, postFactory, ENV) {

	$scope.collections;
	postFactory.getActiveCollections().then(function(data){
		$scope.collections = data;
	});

});

app.controller('ProfileController', function($scope, postFactory, userFactory, ENV, $ngBootbox) {

	$scope.user_profile = new Object();
	$scope.user_profile.background_image = '';
	$scope.user_profile.photo = '';
	$scope.user_favorites = new Array();

	$scope.map = {
		center: {
			latitude: '',
			longitude: ''
		},
		zoom: 12,
		options: {
			panControl: false,
			zoomControl: false,
			streetViewControl: false,
			mapTypeControl: false
		},
		icon: ENV.baseUrl+'assets/images/icons/marker.png'
	};

	$scope.numUserPostsLoaded = 0;
	$scope.numUserFavoritesLoaded = 0;
	$scope.numApprovalQueueLoaded = 0;

	userFactory.getUserProfile(window.networked.profile_id).then(function(data){

		$scope.user_profile = data;
		$scope.user_profile.selected_roles = [
			{ name: 'Teacher', selected: false },
			{ name: 'Community Member', selected: false },
			{ name: 'Administrator', selected: false },
			{ name: 'Parent', selected: false },
			{ name: 'Researcher', selected: false },
			{ name: 'Other', selected: false }
		];

		//handle backwards compatibility for roles (array vs string)
		$scope.user_profile.user_role_names = '';
		if (typeof $scope.user_profile.role_title === 'string')
		{
			$scope.user_profile.user_role_names = $scope.user_profile.role_title;
			//convert to array
			$scope.user_profile.role_title = [ $scope.user_profile.role_title ];
		}
		else if ($scope.user_profile.role_title && $scope.user_profile.role_title.length > 0)
		{
			$scope.user_profile.user_role_names = $scope.user_profile.role_title.join(', ');
		}

		//check if 'Other' role is selected
		var other_idx;
		if ($scope.user_profile.role_title)
		{
			other_idx = $scope.user_profile.role_title.indexOf('Other');
			if (other_idx >= 0 && $scope.user_profile.role_title_other)
			{
				$scope.user_profile.user_role_names += ' (' + $scope.user_profile.role_title_other + ')';
			}
		}

		//set up roles array for later editing
		angular.forEach($scope.user_profile.selected_roles, function(role, idx) {
			if ($scope.user_profile.role_title && $scope.user_profile.role_title.indexOf(role.name) >= 0)
			{
				role.selected = true;
			}
		});

		//set appropriate values for checkboxes
		if (parseInt($scope.user_profile.share_email)) $scope.user_profile.share_email = '1';
		else $scope.user_profile.share_email = '0';

		//set up user lists
		$scope.numUserPostsLoaded = $scope.user_profile.posts.length;

		angular.forEach(data.favorites, function(item, index) {
			$scope.user_favorites.push(item.post);
		});

		if (data.hasOwnProperty('favorites'))
		{
			$scope.numUserFavoritesLoaded = data.favorites.length;
		}

		if (data.hasOwnProperty('approval_queue'))
		{
			$scope.numApprovalQueueLoaded = data.approval_queue.length;
		}

		if (window.networked.hasOwnProperty('user') && window.networked.user !== null && window.networked.user.hasOwnProperty('id') && window.networked.profile_id == window.networked.user.id)
		{
			$scope.actions.showPosts = 'favorites';
		}
		else
		{
			$scope.actions.showPosts = 'myposts';
		}

	});

	$scope.loadingUserPosts = false;
	$scope.loadedAllUserPosts = false;
	$scope.loadMoreUserPosts = function()
	{
		if ($scope.loadingUserPosts) return;
		$scope.loadingUserPosts = true;

		userFactory.getUserPosts(window.networked.profile_id, $scope.numUserPostsLoaded, 15).then(function(data){

			if (data.length == 0)
			{
				$scope.loadedAllUserPosts = true;
			}
			else
			{
				angular.forEach(data, function(item) {
					$scope.user_profile.posts.push(item);
				});

				$scope.numUserPostsLoaded += data.length;
			}

			$scope.loadingUserPosts = false;

		});
	}

	$scope.loadingUserFavorites = false;
	$scope.loadedAllUserFavorites = false;
	$scope.loadMoreUserFavorites = function()
	{
		if ($scope.loadingUserFavorites) return;
		$scope.loadingUserFavorites = true;

		userFactory.getUserFavorites(window.networked.profile_id, $scope.numUserFavoritesLoaded, 15).then(function(data){

			if (data.length == 0)
			{
				$scope.loadedAllUserFavorites = true;
			}
			else
			{
				angular.forEach(data, function(item) {
					$scope.user_favorites.push(item.post);
				});

				$scope.numUserFavoritesLoaded += data.length;
			}

			$scope.loadingUserFavorites = false;

		});
	}

	$scope.loadingApprovalQueue = false;
	$scope.loadedAllApprovalQueue = false;
	$scope.loadMoreApprovalQueue = function()
	{
		if ($scope.loadingApprovalQueue) return;
		$scope.loadingApprovalQueue = true;

		userFactory.getApprovalQueue(window.networked.profile_id, $scope.numApprovalQueueLoaded, 15).then(function(data){

			if (data.length == 0)
			{
				$scope.loadedAllApprovalQueue = true;
			}
			else
			{
				angular.forEach(data, function(item) {
					$scope.user_profile.approval_queue.push(item);
				});

				$scope.numApprovalQueueLoaded += data.length;
			}

			$scope.loadingApprovalQueue = false;

		});
	}

	$scope.approvePost = function(post)
	{
		postFactory.approvePost(post.id).then(function(data){

			//remove post from the queue
			if (data.data == 200)
			{
				angular.forEach($scope.user_profile.approval_queue, function(item, index) {

					if (item.id == post.id)
					{
						$scope.user_profile.approval_queue.splice(index, 1);
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
		        postFactory.rejectPost(post.id).then(function(data){

					//remove post from the queue
					if (data.data == 200)
					{
						angular.forEach($scope.user_profile.approval_queue, function(item, index) {

							if (item.id == post.id)
							{
								$scope.user_profile.approval_queue.splice(index, 1);
							}
						});
					}
				});
		    }, function() {
		        //dismissed
		    });
	}

	$scope.requestModeratorRole = function()
	{
		if ($scope.user_profile.moderator_requested == 0)
		{
			userFactory.requestModeratorRole().then(function() {
				$scope.user_profile.moderator_requested = 1;
			});
		}
	}

});

app.controller('SearchController', function($scope, postFactory, $filter, uiGmapGoogleMapApi, ENV, cfpLoadingBar, $timeout, $window, $ngBootbox, $state) {

	cfpLoadingBar.start();

	$scope.results_toggle = false;
	$scope.page_type = 'search';
	$scope.collection_name = '';
	$scope.collection_slug = '';
	if (window.networked.isCollection)
	{
		$scope.page_type = 'collection';
		$scope.collection_name = window.networked.collection_name;
		$scope.collection_slug = window.networked.collection_slug;
	}

	$scope.$watch('filters.runSearch', function(doSearch) {
		if (doSearch)
		{
			$scope.doSearch();
			$scope.filters.runSearch = false;
		}
	});

	$scope.pendingPromise;
	$scope.$watch('filters.searchTerm', function() {
		if ($scope.map.ready)
		{
			if ($scope.pendingPromise)
			{
				$timeout.cancel($scope.pendingPromise);
			}

			$scope.pendingPromise = $timeout(function() {
				$scope.doSearch();
			}, 500);
		}
	});

	// HeatLayer = function(heatLayer) {
	// 	var allpoints = new Array();
	//     angular.forEach($scope.allMarkers, function(marker){
	//     	allpoints.push(new google.maps.LatLng(marker.coordinates.latitude, marker.coordinates.longitude));
	//     });

	//     $scope.map.heatMapLayerPoints = new google.maps.MVCArray(allpoints);
	//     heatLayer.setData($scope.map.heatMapLayerPoints);
	// }

	// $scope.resetHeatMapLayerData = function() {
	// 	if ($scope.map.heatMapLayer){

	// 		$scope.map.heatMapLayerPoints.clear();

	// 	    angular.forEach($scope.allMarkers, function(marker){
	// 	    	$scope.map.heatMapLayerPoints.push(new google.maps.LatLng(marker.coordinates.latitude, marker.coordinates.longitude));
	// 	    });
	// 	}
	// }

	$scope.map = {
		ready: false,
		loaded: false,
		resizeMe: false,
		rebuildMarkers: false,
		center: {
			latitude: 44.6520086,
			longitude: -90.2115617
		},
		// showHeat: false,
		// heatMapLayer: null,
		// heatMapLayerPoints: null,
		// heatLayerCallback: function(layer) {
		// 	$scope.map.heatMapLayer = new HeatLayer(layer);
		// },
		// heatLayerOptions: {
		// 	radius: 30,
		// 	useLocalExtrema: true,
		// 	scaleRadius: true,
		// 	opacity: 0.7,
		// 	gradient: [
		// 	    'rgba(0, 255, 255, 0)',
		// 	    'rgba(0, 63, 255, 1)',
		// 	    'rgba(0, 0, 255, 1)',
		// 	    'rgba(0, 0, 223, 1)',
		// 	    'rgba(0, 0, 191, 1)',
		// 	    'rgba(0, 0, 159, 1)',
		// 	    'rgba(0, 0, 127, 1)',
		// 	    'rgba(63, 0, 91, 1)',
		// 	    'rgba(127, 0, 63, 1)',
		// 	    'rgba(191, 0, 31, 1)',
		// 	    'rgba(255, 0, 0, 1)'
		// 	  ]
		// },
		events: {
			center_changed: function(map, eventName, args) {
				//if nearest is the sort being used, reorder the markers
				if ($scope.filters.sortSelected == 'nearest' || $scope.filters.sortSelected == '')
				{
					if ($scope.pendingPromise)
					{
						$timeout.cancel($scope.pendingPromise);
					}

					$scope.pendingPromise = $timeout(function() {
						$scope.sortMarkersByNearest();
					}, 800);
				}
			}
		},
		zoom: 7,
		clusterOptions: {
			title: 'Cluster',
			gridSize: 25,
			minimumClusterSize: 2,
			maxZoom: null,
			styles: [
				{
					textColor: 'white',
					url: ENV.baseUrl+'assets/images/cluster1.png',
					height: 28,
					width: 28
				}
			],
			zoomOnClick: false,
			averageCenter: true
		},
		clusterEvents: {
			click: function (cluster, clusterModels) {
				var gmap = $scope.map.control.getGMap();
				var zoomLevel = gmap.getZoom();

				$scope.$apply(function() {
					//remove any other cluster visible
					$scope.clusters = new Array();

					var clusterLat = cluster.getCenter().lat();
					var clusterLng = cluster.getCenter().lng();

					if (zoomLevel < 16 || (zoomLevel < 18 && clusterModels.length > 20))
					{
						var newZoom = 2 * Math.round((zoomLevel + 2) / 2);

						$scope.map.zoom = newZoom;
						$scope.map.center = {
							latitude: clusterLat,
							longitude: clusterLng
						};
					}
					else
					{
						$scope.clusters.push({
							show: true,
							coords: {
								latitude: clusterLat,
								longitude: clusterLng
							},
							markers: clusterModels
						});
					}
				});
			}
		},
		control: {}
	};
	$scope.totalResultCount = 0;
	$scope.allMarkers = [];
	$scope.selectedMarker = {show: false};
	$scope.clusters = new Array();

	var onSuccess = function(position) {
		$scope.map.center = {
			latitude: position.coords.latitude,
			longitude: position.coords.longitude
		};
		$scope.$apply();
	}

	var onError = function(error) {
		console.log('code: ' + error.code + '\n' + 'message: ' + error.message);
	}

	navigator.geolocation.getCurrentPosition(onSuccess, onError);

	uiGmapGoogleMapApi.then(function(maps) {
		$scope.map.ready = true;

		//if loading post over the map, don't load the map immediately (to save on resources)
		if ($state.current.name != 'post')
		{
			$scope.doSearch();
		}
		else
		{
			cfpLoadingBar.complete();
		}
    });

	//if map hadn't been previously loaded after closing a post, do it now
    $scope.$watch('$state.current.name', function(val) {
    	if ($scope.map.ready && !$scope.map.loaded && $state.current.name != 'post')
    	{
    		$scope.doSearch();
    	}
    });

	$scope.actions.searchInProgress = false;
    $scope.doSearch = function()
    {
    	//if starting to do search, close out to the map
    	if ($state.current.name == 'post'){
			$state.go('main');
		}

    	//don't allow click tags if search is currently in progress
    	$scope.actions.searchInProgress = true;

    	cfpLoadingBar.start();

    	//clear the markers
    	$scope.allMarkers = new Array();

    	//close any open infowindows
    	$scope.closeClick();
    	$scope.closeClusterInfowindowClick();

    	var latitude, longitude;
    	if ($scope.filters.sortSelected == 'nearest' || $scope.filters.sortSelected == '')
    	{
    		latitude = $scope.map.center.latitude;
    		longitude = $scope.map.center.longitude;
    	}

    	//record search in google analytics
    	if ($window.ga){
    		$window.ga('send', 'event', 'search', 'search', $scope.filters.searchTerm);
    	}

    	postFactory.getSearch({
    		'search_term': $scope.filters.searchTerm,
    		'sort': $scope.filters.sortSelected,
    		'type': $scope.filters.typeSelected,
    		'tags': $scope.filters.tagsSelected,
    		'latitude': latitude,
    		'longitude': longitude,
    		'isCollection': window.networked.isCollection,
    		'collection': $scope.collection_slug
    	}).then(function(data){

			var markers = $scope.setupMarkers(data.posts);

			var hasEnabledTagsList = false;
			//check if there are any tags that should be enabled
			angular.forEach(data.tags, function(tagNames, category) {
				hasEnabledTagsList = true;
			});

			if (!hasEnabledTagsList && data.posts.length > 0)
			{
				//no tags are selected, so reenable all
				//don't do this if there are no posts
				angular.forEach($scope.filters.allTagsDisplay, function(tags) {
					angular.forEach(tags.tags, function(tag) {
						tag.disabled = false;
					});
				});
			}
			else
			{
				angular.forEach($scope.filters.allTagsDisplay, function(tags) {
					if (data.tags.hasOwnProperty(tags.category))
					{
						angular.forEach(tags.tags, function(tag) {
							if (data.tags[tags.category].indexOf(tag.slug) >= 0)
							{
								tag.disabled = false;
							}
							else
							{
								tag.disabled = true;
							}
						});
					}
					else
					{
						angular.forEach(tags.tags, function(tag) {
							tag.disabled = true;
						});
					}
				});
			}

			$scope.totalResultCount = data.count;

			$scope.allMarkers = markers;

			var bounds = new google.maps.LatLngBounds();
			for (var i = 0, mark; mark = $scope.allMarkers[i], i < $scope.allMarkers.length; i++)
			{
				bounds.extend(new google.maps.LatLng(mark.coordinates.latitude, mark.coordinates.longitude));
			}

			var gmap = $scope.map.control.getGMap();
			
			if ($scope.filters.sortSelected != 'nearest')
			{
				//expand the bounds out slightly so zoom works better
		       	var extendPoint1 = new google.maps.LatLng(bounds.getNorthEast().lat() + 0.0005, bounds.getNorthEast().lng() + 0.0005);
		       	var extendPoint2 = new google.maps.LatLng(bounds.getNorthEast().lat() - 0.0005, bounds.getNorthEast().lng() - 0.0005);
		       	bounds.extend(extendPoint1);
		       	bounds.extend(extendPoint2);

			    gmap.fitBounds(bounds);
			}

			$scope.actions.searchInProgress = false;

			$scope.map.loaded = true;

			// $scope.resetHeatMapLayerData();

			cfpLoadingBar.complete();

			//force a map resize
			$timeout(function(){
				$scope.map.resizeMe = true;
			}, 1000);
			
		});
    }

    // $scope.$on('toggleHeatMap', function(event, message){
    // 	$scope.map.showHeat = message.show;
    // 	$scope.toggleHeatMap();
    // });

    // $scope.toggleHeatMap = function()
    // {
    // 	// $scope.map.showHeat = ! $scope.map.showHeat;

    // 	$scope.map.rebuildMarkers = true;

    // 	if ($scope.map.showHeat){
    // 		angular.forEach($scope.allMarkers, function(marker){
    // 			marker.markerOptions.visible = false;
    // 		});
    // 	}
    // 	else{
    // 		angular.forEach($scope.allMarkers, function(marker){
    // 			marker.markerOptions.visible = true;
    // 		});
    // 	}

    // 	$timeout(function() {
    // 		$scope.map.rebuildMarkers = false;
    // 	}, 500);
    // }

	$scope.setupMarkers = function(data)
	{
		var markers = [];
		// var markerVisible = $scope.map.showHeat ? false : true;

		_.forEach(data, function(value, key) {
			var ret = {
				foo: value,
				id: value.id,
				coordinates: {
					latitude: value.latitude,
					longitude: value.longitude
				},
				position: new google.maps.LatLng(value.latitude, value.longitude),
				show: false,
				title: value.title,
				icon: ENV.baseUrl+'assets/images/icons/map-'+value.post_type.icon_file+'.png',
				thumbnail: ENV.baseUrl+'uploads/'+value.thumbnail,
				thumbnail_sm: ENV.baseUrl+'uploads/'+value.thumbnail_sm
				// ,
				// markerOptions: {
				// 	visible: markerVisible
				// }
			}

			if (value.thumbnail == '')
			{
				ret.thumbnail = ENV.baseUrl+'assets/images/default_thumbnail.png';
			}
			if (value.thumbnail_sm == '')
			{
				ret.thumbnail_sm = ENV.baseUrl+'assets/images/default_thumbnail.png';
			}

			ret.onClick = function() {
				$scope.$apply(function() {
					$scope.selectedMarker.show = false;
				});
	            $scope.selectedMarker = ret;
	            $scope.selectedMarker.show = true;
			}

			markers.push(ret);
		});

		return markers;
	}

	$scope.sortMarkersByNearest = function()
	{
		$scope.allMarkers.sort(nearestSort);
	}

	var nearestSort = function(a, b)
    {
    	var center = new google.maps.LatLng($scope.map.center.latitude, $scope.map.center.longitude);
    	return google.maps.geometry.spherical.computeDistanceBetween(a.position, center) - google.maps.geometry.spherical.computeDistanceBetween(b.position, center);
    }

    $scope.closeClusterInfowindowClick = function()
    {
    	$scope.clusters = new Array();
    }

    $scope.closeClick = function()
    {
    	$scope.selectedMarker.show = false;
    }

    $scope.clearSearch = function()
    {
    	$scope.filters.searchTerm = "";
		$scope.filters.tagsSelected = new Object();
		$scope.filters.typeSelected = "";

		$scope.filters.runSearch = true;

		angular.forEach($scope.filters.allTags, function(tags, tagCategory) {
			angular.forEach(tags, function(tag) {
				tag.selected = false;
			});
		});
    }

    $scope.clearTag = function(tag, tagCategory)
    {
    	tag.selected = false;
    	
    	var tag_index = $scope.filters.tagsSelected[tagCategory].indexOf(tag);
		$scope.filters.tagsSelected[tagCategory].splice(tag_index, 1);

		if ($scope.filters.tagsSelected[tagCategory].length == 0)
		{
			delete $scope.filters.tagsSelected[tagCategory];
		}

		$scope.filters.runSearch = true;
    }

    $scope.selectSort = function(type)
    {
		$scope.filters.sortSelected = type;

		$scope.filters.runSearch = true;
    }

    $scope.toggleSearchResults = function()
    {
    	$scope.results_toggle = ! $scope.results_toggle;

    	$scope.map.resizeMe = true;
    }

    $scope.allCollections;
    postFactory.getAllCollections().then(function(data){
		$scope.allCollections = data;
	});

    $scope.selectedBatchAddCollection;
    $scope.batchAddToCollection = function(collection)
    {
    	if (collection)
    	{
    		$ngBootbox.confirm('Are you sure you want to add all visible posts from this search to the collection ' + collection.name + '?')
			    .then(function() {
			        //confirmed
			        var posts = new Array();
			    	angular.forEach($scope.allMarkers, function(marker, index) {
			    		posts.push(marker.id);
			    	});

			    	postFactory.batchAddToCollection(collection.slug, collection.name, posts).success(function(data) {
			    		$ngBootbox.alert('Posts successfully added to collection!');
			    	});
			    }, function() {
			        //dismissed
			    });
		}
    }

    var idown;
    $scope.exportToSpreadsheet = function()
    {
    	if ($scope.totalResultCount > 0)
    	{
    		$ngBootbox.alert('Your export will be downloaded shortly.');

	    	var ids = new Array();

	    	angular.forEach($scope.allMarkers, function(marker) {
	    		ids.push(marker.id);
	    	});

	    	var url = ENV.baseUrl+'export/export/';
	    	url += ids.join();

			if (idown) {
				idown.attr('src', url);
			} else {
				idown = $('<iframe>', {id : 'idown', src : url}).hide().appendTo('body');
			}
		}
    }

});

// app.controller('HeatMapBtnCtrl', function ($scope) {
//     $scope.controlText = 'Show heat map';
//     $scope.showHeatMap = false;
//     $scope.controlClick = function () {
//         $scope.showHeatMap = ! $scope.showHeatMap;
//         if($scope.showHeatMap){
//         	$scope.controlText = 'Hide heat map';
//         	$scope.$emit('toggleHeatMap', {show:true});
//         }
//         else{
//         	$scope.controlText = 'Show heat map';
//         	$scope.$emit('toggleHeatMap', {show:false});
//         }
//     };
// });

app.controller('HomeController', function($scope, postFactory, $filter, uiGmapGoogleMapApi, ENV, $timeout) {

	$scope.map = {
		ready: false,
		center: {
			latitude: 43.073052,
			longitude: -89.401230
		},
		zoom: 12,
		clusterOptions: {
			title: 'Cluster',
			gridSize: 25,
			minimumClusterSize: 2,
			maxZoom: null,
			styles: [
				{
					textColor: 'white',
					url: ENV.baseUrl+'assets/images/cluster1.png',
					height: 28,
					width: 28
				}
			],
			zoomOnClick: false
		}
	};
	$scope.allMarkers = new Array();
	$scope.selectedMarker = {show: false};

	var onSuccess = function(position) {
		$scope.map.center = {
			latitude: position.coords.latitude,
			longitude: position.coords.longitude
		};
		$scope.$apply();
	}

	var onError = function(error) {
		console.log('code: ' + error.code + '\n' + 'message: ' + error.message);
	}

	navigator.geolocation.getCurrentPosition(onSuccess, onError);

	uiGmapGoogleMapApi.then(function(maps) {
		postFactory.getSearchInRadius($scope.map.center.latitude, $scope.map.center.longitude, 30).then(function(data){

			var markers = $scope.setupMarkers(data.posts);

			$scope.allMarkers = markers;

			$scope.map.ready = true;
			
		});
    });

	$scope.setupMarkers = function(data)
	{
		var markers = new Array();

		_.forEach(data, function(value, key) {
			var ret = {
				id: value.id,
				coordinates: {
					latitude: value.latitude,
					longitude: value.longitude
				},
				position: new google.maps.LatLng(value.latitude, value.longitude),
				show: false,
				title: value.title,
				icon: ENV.baseUrl+'assets/images/icons/map-'+value.post_type.icon_file+'.png'
			}

			markers.push(ret);
		});

		return markers;
	}
});

app.controller('ClusterInfoController', function($scope) {
	//this is just a placeholder
});

app.controller('InfowindowController', function($scope) {
	//this is just a placeholder
});

app.directive('showNavbar', function($rootScope) {
	return {
		scope: { 'modalOpen': '=' },
		link: function(scope, element) {
			var listener = function(event, toState) {
				if (toState.data.state != "")
				{
					$rootScope.modalOpen = true;
				}
				else
				{
					$rootScope.modalOpen = false;
				}
			}

			$rootScope.$on('$stateChangeSuccess', listener);
		}
	}
});

app.directive('entryThumbnails', function(ENV) {
	return {
		templateUrl: function(tElement, tAttrs){
			if (tAttrs.type == 'posts')
			{
				return ENV.baseUrl+'partials/post_thumbs.html'
			}
			else if (tAttrs.type == 'events')
			{
				return ENV.baseUrl+'partials/event_thumbs.html'
			}
		},
		restrict: 'E',
		scope: {
			entries: '=',
			windowWidth: '@',
			type: '@',
			selectedMarker: '='
		},
		link: function (scope, element, attrs) {
			var content = element.children().children()[0],
                leftEl = element.children().children()[1],
                rightEl = element.children().children()[2],
                interval,
                didScroll = true,
                num = 0;

            scope.$watch('windowWidth', function(width) {
            	var image_width;
            	if (scope.type == 'posts')
            	{
            		image_width = $('.slidebox-item').width();
            	}
            	else if (scope.type == 'events')
            	{
            		image_width = $('.event-item').width();
            	}
            	content.children[0].style.width = (num*image_width)+"px";

                scope.resizeOnWindowChange();
            });

            scope.$watch('entries.length', function(value) {
            	num = value;

				initSlidebox();
			});

			scope.showPopup = function(post)
			{
				scope.selectedMarker.show = false;

				scope.selectedMarker = post;
				scope.selectedMarker.show = true;
			}

			scope.hidePopup = function(post)
			{
				scope.selectedMarker.show = false;
			}

            function initSlidebox()
            {
                if (attrs.contentWidth)
                {
                    content.children[0].style.width = attrs.contentWidth;
                }

                if (attrs.contentClass)
                {
                    content.children[0].className += ' ' + attrs.contentClass;
                }

                var image_width;
            	if (scope.type == 'posts')
            	{
            		image_width = $('.slidebox-item').width();
            	}
            	else if (scope.type == 'events')
            	{
            		image_width = $('.event-item').width();
            	}
            	content.children[0].style.width = (num*image_width)+"px";

                scope.resizeOnWindowChange();

                updateControlVisibility();
            }

            var windowResizing;
            scope.resizeOnWindowChange = function()
            {
                var width_comparison;
                width_comparison = window.innerWidth;

                if (parseFloat(content.children[0].style.width) < width_comparison)
                {
                    leftEl.style.display = 'none';
                    rightEl.style.display = 'none';
                }
                else
                {
                    leftEl.style.display = 'block';
                    rightEl.style.display = 'block';
                }

                clearTimeout(windowResizing);

                updateControlVisibility();
            }

            function scrollNumber(isLeft)
            {
            	var scrollVal = scope.windowWidth * 0.9;

                if (isLeft)
                    $(content).animate( { scrollLeft: '-='+scrollVal }, 500);
                else
                    $(content).animate( { scrollLeft: '+='+scrollVal }, 500);

                didScroll = true;

                updateControlVisibility();
            }

            function updateControlVisibility()
            {
                if (content.scrollLeft === 0)
                {
                    leftEl.style.display = 'none';
                }
                else
                {
                    leftEl.style.display = 'block';
                }

                if (content.scrollLeft === content.scrollWidth - content.offsetWidth)
                {
                    rightEl.style.display = 'none';
                }
                else
                {
                    rightEl.style.display = 'block';
                }
            }

            leftEl.addEventListener('click', function()
            {
                scrollNumber(true);
            }, false);

            rightEl.addEventListener('click', function()
            {
                scrollNumber(false);
            }, false);

            content.addEventListener('scroll', function()
            {
                didScroll = true;

                updateControlVisibility();
            });
		}
	}
});

app.directive('resize', function ($window) {
    return  {
        scope: { resizeWindowWidth:'=resizeWindowWidth',
                 resizeWindowHeight:'=resizeWindowHeight' },
        link: function (scope, element) {
            var w = angular.element($window);

            scope.getWindowDimensions = function () {
                return {
                    'h': w.height(),
                    'w': w.width()
                };
            };

            scope.$watch(scope.getWindowDimensions, function (newValue, oldValue) 
            {
                scope.resizeWindowWidth = newValue.w.toString();
                scope.resizeWindowHeight = newValue.h.toString();
            }, true);

            w.on('resize', function () {
                scope.$apply();
            });
        }
    };
});

app.directive('resizeMap', function() {
	return  {
        scope: { windowHeight : '=',
                 windowWidth : '=',
                 mapPage : '=',
                 mapReady : '=',
                 resizeMe : '=' },
        link: function (scope, element) {
        	var map = $(element).find('.angular-google-map-container');
        	var wrapper = $(element);
        	var ctr = 0;

        	scope.doResize = function() {
            	if (scope.mapPage == 'search')
            	{
            		var newHeight = parseFloat(scope.windowHeight) - $('#top_bar').outerHeight() - $('#search_bottom_container').outerHeight() - $('#footer').outerHeight();

            		//trying this out... the map is just unusable on mobile if it doesn't have a min height
            		if (newHeight < 150) newHeight = 150;

            		$(map).height(newHeight);
            		$(wrapper).height(newHeight);
            	}
            	else if (scope.mapPage == 'post')
            	{
            		$(map).height(parseFloat(scope.windowHeight) - 100);
            	}
            	else
            	{
            		$(map).height(parseFloat(scope.windowHeight));
            	}
            }

        	scope.doResize();

        	scope.$watch('resizeMe', function(val) {
        		
        		if (val)
        		{
        			scope.doResize();
        			scope.resizeMe = false;
        		}

        	});

        	scope.$watch('mapReady', function(val) {
        		if (val)
        		{
        			scope.doResize();
        		}
        	});

            scope.$watch('windowHeight', function() {
            	scope.doResize();
            });

            scope.$watch('windowWidth', function() {
            	scope.doResize();
            });
        }
    };
});

app.directive('scrollDropdownModal', function($timeout) {
	return  {
        scope: { windowHeight : '=',
        		 checkScrollHeight: '=' },
        link: function (scope, element) {

        	function showHideScrollbar() 
        	{
        		var modalHeight = parseInt($(element).height()) + 100;
        		var winHeight = parseInt(scope.windowHeight);

        		if (modalHeight > winHeight)
        		{
        			$(element).parent().addClass('scroll');
        		}
        		else
        		{	
        			$(element).parent().removeClass('scroll');
        		}
        	}

        	scope.$watch('checkScrollHeight', function(val) {
        		$timeout(function()
        		{
        			showHideScrollbar();
        		}, 300);
        	});

            scope.$watch('windowHeight', function(val) {
            	showHideScrollbar();
            });
        }
    };
});

app.directive('socialLink', function() {
	return {
		scope: {
			link: '=',
			type: '='
		},
		restrict: 'E',
		replace: true,
		template: '<a class="gray-link" ng-href="{{linkVal}}" target="{{linkTarget}}"><i class="fa {{linkIcon}}"></i> {{linkDisplay}}</a>',
		link: function(scope, element) {
			if (scope.type == 'email')
			{
				scope.linkVal = 'mailto:'+scope.link;
				scope.linkIcon = 'fa-envelope';
				scope.linkDisplay = scope.link;
				scope.linkTarget = '';
			}
			else
			{
				scope.linkTarget = '_blank';

				if (scope.link.indexOf('facebook.com') >= 0)
				{
					scope.linkVal = scope.link;
					scope.linkDisplay = scope.link;
					scope.linkIcon = 'fa-facebook';
				}
				else if (scope.link.indexOf('twitter.com') >= 0)
				{
					scope.linkVal = scope.link;
					scope.linkDisplay = '@' + scope.link.match(/https?:\/\/(www\.)?twitter\.com\/(#!\/)?@?([^\/]*)/)[3];
					scope.linkIcon = 'fa-twitter';
				}
				else if (scope.link.indexOf('linkedin.com') >= 0)
				{
					scope.linkVal = scope.link;
					scope.linkDisplay = scope.link;
					scope.linkIcon = 'fa-linkedin';
				}
				else
				{
					scope.linkVal = scope.link;
					scope.linkDisplay = scope.link;
					scope.linkIcon = 'fa-external-link';
				}
			}
		}
	}
});

/*https://gist.github.com/justinmc/d72f38339e0c654437a2*/
app.directive('anchorSmoothScroll', function($location) {
    'use strict';
 
    return {
        restrict: 'A',
        replace: false,
        scope: {
            'anchorSmoothScroll': '@'
        },
 
        link: function($scope, $element, $attrs) {
 
            initialize();
    
            /* initialize -
            ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
            function initialize() {
                createEventListeners();
            }
 
            /* createEventListeners -
            ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
            function createEventListeners() {
                // listen for a click
                $element.on('click', function() {
                    // set the hash like a normal anchor scroll
                    // $location.hash($scope.anchorSmoothScroll);
 
                    // smooth scroll to the passed in element
                    scrollTo($scope.anchorSmoothScroll);
                });
            }
 
            /* scrollTo -
            ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
            function scrollTo(eID) {
 
                // This scrolling function 
                // is from http://www.itnewb.com/tutorial/Creating-the-Smooth-Scroll-Effect-with-JavaScript
                
                var i;
                var startY = currentYPosition();
                var stopY = elmYPosition(eID);
                var distance = stopY > startY ? stopY - startY : startY - stopY;
                if (distance < 100) {
                    scrollTo(0, stopY); return;
                }
                var speed = Math.round(distance / 100);
                if (speed >= 20) speed = 20;
                var step = Math.round(distance / 25);
                var leapY = stopY > startY ? startY + step : startY - step;
                var timer = 0;
                if (stopY > startY) {
                    for (i = startY; i < stopY; i += step) {
                        setTimeout('window.scrollTo(0, '+leapY+')', timer * speed);
                        leapY += step; if (leapY > stopY) leapY = stopY; timer++;
                    } return;
                }
                for (i = startY; i > stopY; i -= step) {
                    setTimeout('window.scrollTo(0, '+leapY+')', timer * speed);
                    leapY -= step; if (leapY < stopY) leapY = stopY; timer++;
                }
            }
            
            /* currentYPosition -
            ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
            function currentYPosition() {
                // Firefox, Chrome, Opera, Safari
                if (window.pageYOffset) {
                    return window.pageYOffset;
                }
                // Internet Explorer 6 - standards mode
                if (document.documentElement && document.documentElement.scrollTop) {
                    return document.documentElement.scrollTop;
                }
                // Internet Explorer 6, 7 and 8
                if (document.body.scrollTop) {
                    return document.body.scrollTop;
                }
                return 0;
            }
 
            /* scrollTo -
            ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
            function elmYPosition(eID) {
                var elm = document.getElementById(eID);
                var y = elm.offsetTop;
                var node = elm;
                while (node.offsetParent && node.offsetParent != document.body) {
                    node = node.offsetParent;
                    y += node.offsetTop;
                } return y;
            }
        }
    };
})

;