angular.module("app")
	.controller("MainCtrl", [
		"$scope", 
		"$state",
		"Utilities",
		function(
			$scope,
			$state,
			Utilities
		){

		"use strict";
		
		console.log("MainCtrl");
		// Define utility functions:
		$scope.checkRoute = Utilities.checkRoute;
		$scope.getData = Utilities.getData;

	}]);