<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<link rel="stylesheet" href="{{ asset('css/main.css') }}">
		<link rel="stylesheet" href="{{ asset('css/c3.min.css') }}">
		<title>Air Call Reporting</title>
	</head>
	<body>
	<nav class="navbar navbar-default">
		<div class="container-fluid">
			<div class="navbar-header">
				<a class="navbar-brand" href="/">Aircall Report</a>
			</div>
			<ul class="nav navbar-nav">
				@if(isset($reportsPage))
					<li class="active">
				@else
					<li>
				@endif
					<a href="{{ action('CallsController@getReportingDetails') }}">Reports</a>
				</li>
				@if(isset($callsPage))
					<li class="active">
				@else
					<li>
				@endif
					<a href="{{ action('CallsController@getCallsDetails') }}">Call Data</a>
				</li>
				@if(isset($importPage))
					<li class="active">
				@else
					<li>
				@endif
					<a href="{{ action('AircallController@getImportPage') }}">Import</a>
				</li>
			</ul>
			<ul class="nav navbar-nav navbar-right">
				<li>
					<a href="https://github.com/randomhacksuk/aircall-reporting" target="blank">Github Repo</a>
				</li>
		    </ul>
		</div>
	</nav>
	
		@yield('content')

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
		<script src="{{ asset('js/moment/moment.js') }}" charset="utf-8"></script>
		<script src="{{ asset('js/c3/d3.min.js') }}" charset="utf-8"></script>
		<script src="{{ asset('js/c3/c3.min.js') }}"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.8/angular.min.js"></script>
		<script src="{{ asset('js/c3/c3-angular.min.js') }}"></script>
		<script src="{{ asset('js/dirPagination/dirPagination.js') }}"></script>

		@yield('js')
		
	</body>
</html>
