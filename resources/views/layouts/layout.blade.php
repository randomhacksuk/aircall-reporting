<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<link rel="stylesheet" href="{{ asset('css/main.css') }}">
		<link rel="stylesheet" href="{{ asset('css/c3.min.css') }}">
		<title>Air Call Reporting</title>
	</head>
	<body>
	
		@yield('content')

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
		<script src="{{ asset('js/c3/d3.min.js') }}" charset="utf-8"></script>
		<script src="{{ asset('js/c3/c3.min.js') }}"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.8/angular.min.js"></script>
		<script src="{{ asset('js/c3/c3-angular.min.js') }}"></script>
		<script type="text/javascript" src="{{ asset('js/main.js') }}"></script>
	</body>
</html>
