// 'use strict';

/* Controllers */

function SubmissionsCtrl($scope, $http) {
  $http.get('data/submissions_data.php').success(function(data) {
  	console.log(data);
  	$scope.submissions = data;
    // console.log($scope.schedules);
  });

  // $scope.setSchedule = function(when) {
  // 	// alert(when);
  // 	$http(
  // 	  {
  // 	  	url:"data/claim.php",
  // 	  	method:'GET',
  // 	  	params: {"when":when}
  // 	  }
  // 	  	).success(function(data, status, headers, config) {
  // 	  		// alert(data);
		//     $scope.schedules = new Array(24*7);
		//   	for (var i = 0; i <data.length; i++) {
		//   		// var c = 'v'+;
		//   		$scope.schedules[data[i].when] = data[i].by;
		//   		// $scope.schedules['a']='asd';
		//   	};
		// }).error(function(data, status, headers, config) {
		//     // $scope.status = status;
		//     alert(status);
		// });
  // }
  // $scope.orderProp = 'age';
}

//PhoneListCtrl.$inject = ['$scope', '$http'];
