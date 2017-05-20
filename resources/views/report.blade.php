@extends('layouts.layout')

@section('content')
<div id="main" style="display: none" class="center" ng-app="myApp" ng-controller="myCtrl">
	<!-- <a style="color: #000; text-decoration: none;" href="https://github.com/randomhacksuk/aircall-reporting" target="blank"><h1>Aircall Report</h1></a> -->
	<form class="form-inline">
		<div class="form-group">
			<label for="line">Line:</label>
			<select id="line" class="form-control" ng-model="reportNumber" ng-change="filterReports()">
				<option value="all">All</option>
				<option ng-repeat="(key, value) in numbers" value="@{{ key }}">@{{ value }}</option>
			</select>
		</div>
		<div class="form-group">
			<label for="duration">Duration:</label>
			<select id="duration" class="form-control" ng-model="reportDate" ng-change="filterReports()">
				<option ng-repeat="(key, value) in months" value="@{{ key }}">@{{ value }}</option>
			</select>
		</div>
	</form>
	<div style="width: 960px">
		<h2>User data</h2>
		<table class="table table-bordered table-striped">
			<thead>
				<tr>
					<th>Name</th>
					<th>Incoming Calls</th>
					<th>Outgoing Calls</th>
					<th>Total Call Time</th>
				</tr>
			</thead>
			<tbody>
				<tr ng-repeat="user in users">
					<td>@{{ user.name }}</td>
					<td>@{{ inCalls(user.calls) }}</td>
					<td>@{{ outCalls(user.calls) }}</td>
					<td>@{{ totalCalls(user.calls) }}</td>
				</tr>
			</tbody>
		</table>
	</div>
	<h2>Call Data</h2>
	<table class="table table-bordered table-striped" id="callsTable">
		<thead>
			<tr>
				<th></th>
				<th ng-repeat="n in days">@{{ n }}</th>
			</tr>
		</thead>
		<tbody>
			<tr ng-repeat="(key, row) in sortedCalls">
				<td>@{{ key }}</td>
				<td ng-repeat="val in row">@{{ val }}</td>
			</tr>
		</tbody>
	</table>
	<div style="width: 960px">
		<h2>Timing Data</h2>
		<div id="chart"></div>
	</div>
</div>


@stop

@section('js')

<script type="text/javascript">
	window.users = {!! $users !!};
	window.sortedCalls = {!! json_encode($sortedCalls) !!};
	window.months = {!! json_encode($months) !!};
	window.numbers = {!! json_encode($numbers) !!};
	window.graphArray = {!! json_encode($graphArray) !!};
</script>
<script type="text/javascript" src="{{ asset('js/reports.js') }}"></script>

@stop
