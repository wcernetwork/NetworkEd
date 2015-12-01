
	@include('includes.auth')

	@include('includes.about')

	<div ui-view ng-class="{'view-post-modal': $state.current.name != 'main' && $state.current.name != ''}"></div>

	</div>

</body>
</html>