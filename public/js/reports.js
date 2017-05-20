var app = angular.module("myApp", ['gridshore.c3js.chart']);
app.controller("myCtrl", function($scope, $http) {
	$('#main').show();
    $scope.days = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31];
    $scope.users = users;
    $scope.sortedCalls = sortedCalls;
    $scope.months = months;
    $scope.numbers = numbers;

    $scope.reportNumber = 'all';
    var keys = Object.keys($scope.months);
	$scope.reportDate = keys[keys.length-1];

    $scope.totalCalls = function (calls) {
    	var total = 0
    	angular.forEach(calls, function (value, key) {
    		if (value.ended_at != null) {
    			total += value.ended_at - value.started_at;
    		}
    	});
    	var sec_num = total;
	    var hours   = Math.floor(sec_num / 3600);
	    var minutes = Math.floor((sec_num / 60));
	    var seconds = sec_num - (minutes * 60);

	    if (hours   < 10) {hours   = "0"+hours;}
	    if (minutes < 10) {minutes = "0"+minutes;}
	    if (seconds < 10) {seconds = "0"+seconds;}
	    return hours + ':' + minutes + ':' + seconds;
    };
    $scope.inCalls = function (calls) {
    	var count = 0;
    	angular.forEach(calls, function (value, key) {
    		if (value.direction == 'inbound') {
    			count++;
    		}
    	});
    	return count;
    };
    $scope.outCalls = function (calls) {
    	var count = 0;
    	angular.forEach(calls, function (value, key) {
    		if (value.direction == 'outbound') {
    			count++;
    		}
    	});
    	return count;
    };

    $scope.filterReports = function () {
    	$http.get('/filter-reports/' + $scope.reportDate + '/' + $scope.reportNumber)
	    .then(function(response) {
	        $scope.sortedCalls = response.data.sortedCalls;
	        $scope.users = response.data.users;
	        chart.load({
		        columns: [
		            response.data.graphArray[0],
		            response.data.graphArray[1],
		            response.data.graphArray[2],
		            response.data.graphArray[3]
		        ]
		    });
	    });
    };

    var chart = c3.generate({
		bindto: '#chart',
		data: {
			columns: [
				graphArray[0],
				graphArray[1],
				graphArray[2],
				graphArray[3]
			],
			type: 'bar',
			groups: [
	            ['Inbound Answered', 'Inbound Abondoned', 'Inbound Voicemail', 'Outbound']
	        ],
		},
		bar: {
			width: {
				ratio: 0.5 
			}
		},
		axis: {
			x: {
				tick: {
					values: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24],
					format: function (x) { return x + ':00'; }
				},
				min: 1
			}
		}
	});

});