<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<link rel="stylesheet" href="{{ asset('css/main.css') }}">
		<link rel="stylesheet" href="{{ asset('css/c3.min.css') }}">
	</head>
	<body>
		<div class="center" ng-app="myApp" ng-controller="myCtrl">
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
					<tr>
						<td>John</td>
						<td>Doe</td>
						<td>john@example.com</td>
						<td>john@example.com</td>
					</tr>
				</tbody>
			</table>
			<h2>Call Data</h2>
			<form class="form-inline">
				<div class="form-group">
					<label for="line">Line:</label>
					<select id="line" class="form-control">
						<option value="uk">UK Sales</option>
						<option value="usa">USA Sales</option>
					</select>
				</div>
				<div class="form-group">
					<label for="duration">Duration:</label>
					<select id="duration" class="form-control">
						<option>Last Month</option>
					</select>
				</div>
			</form>
			<table class="table table-bordered table-striped" id="callsTable">
				<thead>
					<tr>
						<th></th>
						<th ng-repeat="n in days">@{{ n }}</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>Number of incoming calls</td>
						<td ng-repeat="n in days"></td>
					</tr>
					<tr>
						<td>Number of missed calls</td>
						<td ng-repeat="n in days"></td>
					</tr>
					<tr>
						<td>Percentage of missed calls</td>
						<td ng-repeat="n in days"></td>
					</tr>
					<tr>
						<td>Number of voicemails</td>
						<td ng-repeat="n in days"></td>
					</tr>
				</tbody>
			</table>
			<h2>Timing Data</h2>
			<form class="form-inline">
				<div class="form-group">
					<label for="line">Line:</label>
					<select id="line" class="form-control" v-model="timingLine">
						<option value="uk">UK Sales</option>
						<option value="usa">USA Sales</option>
					</select>
				</div>
				<div class="form-group">
					<label for="duration">Duration:</label>
					<select id="duration" class="form-control">
						<option>Last Month</option>
					</select>
				</div>
			</form>
			<c3chart bindto-id="stacked-bar-plot1-chart">
				<chart-column
				  	column-id="data 1"
		            column-name="Data 1"
		            column-color="red"
		            column-values="30,200,100,400,150,250,30,200,100,400,150,250"
		            column-type="bar"
		            ng-show="timingLine == 'uk' || timingLine == ''"
		        />
				<chart-column
				  	column-id="data 2"
	            	column-name="Data 2"
	            	column-color="green"
	                column-values="50,20,10,40,15,25,50,20,10,40,15,25"
	                column-type="bar"
	                ng-show="timingLine == 'usa' || timingLine == ''"
	            />
				<chart-group group-values="data 1,data 2"/>
			</c3chart>
		</div>

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
		<script src="{{ asset('js/c3/d3.min.js') }}" charset="utf-8"></script>
		<script src="{{ asset('js/c3/c3.min.js') }}"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.8/angular.min.js"></script>
		<script src="{{ asset('js/c3/c3-angular.min.js') }}"></script>

		<script type="text/javascript" src="{{ asset('js/main.js') }}"></script>
	</body>
</html>
