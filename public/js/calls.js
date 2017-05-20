var app = angular.module("myApp", ['angularUtils.directives.dirPagination']);

app.controller("myCtrl", function($scope, $http) {
	$scope.calls = calls;
	$scope.formatDate = function (argument) {
		return moment(argument).format('MMM DD, YYYY');
	}
	$scope.formatTime = function (argument) {
		return moment(argument).format('HH:mm');
	}
	$scope.callDuration = function (call) {
		if (call.answered_at == null || call.ended_at == null) {
			return 'x';
		} else {
			var total = call.ended_at - call.answered_at;
	    	var sec_num = total;
		    var hours   = Math.floor(sec_num / 3600);
		    var minutes = Math.floor((sec_num / 60));
		    var seconds = sec_num - (minutes * 60);

		    if (hours   < 10) {hours   = "0"+hours;}
		    if (minutes < 10) {minutes = "0"+minutes;}
		    if (seconds < 10) {seconds = "0"+seconds;}
		    var result = minutes + ':' + seconds;
		    if (hours > 0) {
		    	result = hours + ':' + minutes + ':' + seconds;
		    }
		    return result;
		}
	}
});