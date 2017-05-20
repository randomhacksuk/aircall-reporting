var app = angular.module("myApp", []);
app.controller("myCtrl", function($scope, $http) {
	$('#main').show();
	$scope.logs = [];
	$scope.complete = false;
	$scope.start = false
	$scope.startImport = function () {
		$scope.start = true;
		$http.get('/get-old-data')
	    .then(function(response) {
	        if(response.data.result == 'success') {
	        	setTimeout(function(){
	        		clearInterval(interval);
	        	}, 1000);
	        	$scope.complete = true;
	        }
	    });
	    var lastId = 0;
		var interval = setInterval(function(){
			$http.get('/get-logs/' + lastId)
		    .then(function(response) {
		    	$scope.logs = $scope.logs.concat(response.data.logs);
		    	if ($scope.logs[$scope.logs.length - 1] != undefined) {
		    		lastId = $scope.logs[$scope.logs.length - 1].id;
		    	}
		    });
		}, 1000);
	}
});