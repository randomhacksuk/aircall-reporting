@extends('layouts.layout')

@section('content')

<div ng-app="myApp" ng-controller="myCtrl">
	<div class="center" style="width: 760px">
		<h2>Import date</h2>
		<button class="btn btn-default" style="margin-bottom: 20px" ng-click="startImport()">Import</button>
		<div class="panel panel-default" ng-if="start">
			<div class="panel-body">
				<div ng-if="start"><b>Connecting</b></div>
				<div ng-if="start"><b>Starting Import...</b></div>
				<div ng-repeat="value in logs">
					<span style="text-transform: capitalize;">@{{ value.type }}</span> "@{{ value.name }}" imported. Updating... 
					<span ng-if="value.success" class="text-success">Done</span>
					<span ng-if="!value.success" class="text-Danger">Failed</span>
				</div>
				<div ng-if="complete"><b>Import completed</b></div>
			</div>
		</div>
	</div>
</div>

@stop

@section('js')

<script type="text/javascript">

</script>
<script type="text/javascript" src="{{ asset('js/import.js') }}"></script>

@stop