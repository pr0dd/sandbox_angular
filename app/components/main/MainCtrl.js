angular.module("app")
	.controller("MainCtrl", [
		"$scope", 
		"$state",
		"$sce", 
		function(
			$scope,
			$state,
			$sce
		){

		"use strict";
		
		console.log("Main");

		$scope.treatSrc = function(src){
			src = "https://www.youtube.com/embed/"+src;
			return $sce.trustAsResourceUrl(src);
		};

		$scope.goTo = function(partial,params){
			$state.go(partial,params);
		};

		$scope.data = {};

	}]);