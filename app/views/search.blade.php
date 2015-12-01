@include('includes.head')

	@include('includes.navbar')

	<div id="tags_slidedown" ng-controller="SearchTagsController" class="slidedown-modal" ng-show="actions.showTags">

		<div class="container" scroll-dropdown-modal window-height="windowHeight" check-scroll-height="actions.showTags">

			<a href="javascript: void(0)" ng-click="actions.showTags = false" class="close"><i class="fa fa-times"></i></a>
			<div class="clearfix"> </div>

			<div class="row">

				<div class="col-sm-3 col-xs-6 tags-type tag-people" ng-click="clickFilterType(1)" ng-class="{ 'active' : filters.typeSelected == 1, 'inactive' : filters.typeSelected != 1 && filters.typeSelected != '' }">
					<div class="rounded-corners">
						People
					</div>
				</div>
				<div class="col-sm-3 col-xs-6 tags-type tag-place" ng-click="clickFilterType(2)" ng-class="{ 'active' : filters.typeSelected == 2, 'inactive' : filters.typeSelected != 2 && filters.typeSelected != '' }">
					<div class="rounded-corners">
						Place
					</div>
				</div>
				<div class="col-sm-3 col-xs-6 tags-type tag-event" ng-click="clickFilterType(3)" ng-class="{ 'active' : filters.typeSelected == 3, 'inactive' : filters.typeSelected != 3 && filters.typeSelected != '' }">
					<div class="rounded-corners">
						Event
					</div>
				</div>
				<div class="col-sm-3 col-xs-6 tags-type tag-project" ng-click="clickFilterType(4)" ng-class="{ 'active' : filters.typeSelected == 4, 'inactive' : filters.typeSelected != 4 && filters.typeSelected != '' }">
					<div class="rounded-corners">
						Project
					</div>
				</div>

			</div>
			
			<br />

			<div class="row">
				<div ng-repeat="item in filters.allTagsDisplay" class="tag-category-name" ng-class="{ 'active' : actions.tagCategoryDisplayed == item.category }" ng-click="actions.tagCategoryDisplayed = item.category">
					{{item.category}} &nbsp;<i class="fa fa-caret-down"></i>
				</div>
			</div>

			<div ng-show="actions.tagCategoryDisplayed == item.category" class="row tag-row" ng-repeat="item in filters.allTagsDisplay">
				<div ng-repeat="tag in item.tags" class="tags-tag" ng-init="tag.selected = false" ng-click="clickTag(item.category, tag)" ng-class="{ 'selected' : tag.selected, 'disabled' : tag.disabled }">
					{{tag.name}}
				</div>
			</div>
		</div>

	</div>

	<div id="search_map" ng-controller="SearchController">

		<div resize-map map-ready="map.ready" map-page="'search'" window-height="windowHeight" window-width="windowWidth" resize-me="map.resizeMe">

			<!--<script type="text/ng-template" id="heatmap.tpl.html">
		        <button class="btn btn-sm btn-red" ng-click="controlClick()">{{controlText}}</button>
		    </script>-->

			<ui-gmap-google-map center='map.center' zoom='map.zoom' events='map.events' control="map.control">

				<!-- <ui-gmap-map-control template="heatmap.tpl.html" position="top-right" controller="HeatMapBtnCtrl" index="-1"></ui-gmap-map-control> -->

				<!-- <ui-gmap-layer namespace="visualization" type="HeatmapLayer" show="map.showHeat" onCreated="map.heatLayerCallback" options="map.heatLayerOptions"></ui-gmap-layer>  -->

				<ui-gmap-window ng-repeat="cluster in clusters" coords="cluster.coords" show="cluster.show" closeClick="closeClusterInfowindowClick" templateUrl="ENV.baseUrl+'partials/clusterinfowindow.html'" templateParameter="cluster.markers"> </ui-gmap-window> 

				<ui-gmap-window coords="selectedMarker.coordinates" show="selectedMarker.show" closeClick="closeClick" templateUrl="ENV.baseUrl+'partials/infowindow.html'" templateParameter="selectedMarker"> </ui-gmap-window>

				<ui-gmap-markers models="allMarkers" coords="'coordinates'" icon="'icon'" click="'onClick'" doCluster="true" clusterOptions="map.clusterOptions" clusterEvents="map.clusterEvents" options="'markerOptions'" doRebuildAll="map.rebuildMarkers"> </ui-gmap-markers>
			</ui-gmap-google-map>
		</div>

		<div id="search_bottom_container" ng-class="{'small':results_toggle}">
			<div class="search-results-header">
				<div class="search-results-search-text-container">
					<span ng-click="toggleSearchResults()" class="hidden-md hidden-lg search-results-collapse red" title="Collapse Search Results">
						<i ng-if="!results_toggle" class="fa fa-chevron-down"></i>
						<i ng-if="results_toggle" class="fa fa-chevron-up"></i>
					</span>

					<span class="red" ng-if="!actions.searchInProgress">{{totalResultCount}}</span><span class="hidden-xs">&nbsp;Results</span>
					<span ng-if="page_type == 'search'">
						for
					</span>
					<input ng-if="page_type == 'search'" class="search-results-search-text" type="text" placeholder="search term..." ng-model="filters.searchTerm" />
					<span ng-if="page_type == 'search'">
						&nbsp;&nbsp;<span title="Clear search and filters" ng-click="clearSearch()" class="search-results-clear-search"><i class="fa fa-times"></i></span>
					</span>

					<span ng-if="page_type == 'collection'">
						in<span class="hidden-xs"> Collection</span> 
					</span>
					<span ng-if="page_type == 'collection'" class="red search-results-search-text">{{collection_name}}</span>
					<span ng-if="page_type == 'collection'">
						&nbsp;&nbsp;<a title="Start new search" href="{{ENV.baseUrl + 'search'}}" class="search-results-clear-search"><i class="fa fa-times"></i></a>
					</span>

					<div class="search-results-tags-container" ng-show="!results_toggle">
						<div ng-repeat="(tagCategory, tags) in filters.tagsSelected">
							<div ng-repeat="tag in tags" class="search-tag">
								{{tag.name}} <span ng-click="clearTag(tag, tagCategory)" class="search-results-tag-remove"><i class="fa fa-times"></i></span>
							</div>
						</div>
					</div>
				</div>

				<span ng-click="toggleSearchResults()" class="hidden-xs hidden-sm search-results-collapse red" title="Collapse Search Results">
					<i ng-if="!results_toggle" class="fa fa-chevron-down"></i>
					<i ng-if="results_toggle" class="fa fa-chevron-up"></i>
				</span>

				<div class="search-results-subheader">
					<span class="hidden-xs hidden-sm" ng-show="totalResultCount > 0">
						<a title="Export search results to spreadsheet" href="javascript: void(0)" ng-click="exportToSpreadsheet()"><i class="fa fa-download"></i> Download</a>
						&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
					</span>
					Sort: 
					<span class="search-results-sort" ng-click="selectSort('nearest')"  ng-class="{ 'active' : filters.sortSelected == 'nearest' || filters.sortSelected == '' }">Nearest</span> 
					<span class="search-results-sort" ng-click="selectSort('helpful')"  ng-class="{ 'active' : filters.sortSelected == 'helpful' }">Most Helpful</span> 
					<span class="search-results-sort" ng-click="selectSort('recent')"  ng-class="{ 'active' : filters.sortSelected == 'recent' }">Recent</span>
					&nbsp;
				</div>
			</div>

			@if (Auth::check() && Auth::user()->is_admin)
			<div ng-if="page_type != 'collection'" class="hidden-xs" style="float: right; padding-right: 20px;">
				Add all visible posts to Collection: 
				<select ng-model="selectedBatchAddCollection" ng-options="collection.name for collection in allCollections"></select> 
				<a href="javascript: void(0)" ng-click="batchAddToCollection(selectedBatchAddCollection)">Add<span class="hidden-sm"> to Collection</span></a>
			</div>
			<div class="clearfix"> </div>
			@endif

			<entry-thumbnails entries="allMarkers" selected-marker="selectedMarker" window-width="{{windowWidth}}" type="posts" ng-hide="results_toggle"> </entry-thumbnails>

		</div>
	</div>

	<div id="footer" class="search_footer">
		<div ng-include="ENV.baseUrl + 'partials/footer.html'"></div>
	</div>

@include('includes.foot')