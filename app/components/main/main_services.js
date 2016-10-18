angular.module("app")
	.factory("Utilities", [
		"$http",
		"$state",
		function(
			$http,
			$state
		){
		"use strict";

		var _checkRoute = function(r){
			if(!angular.isString(r)) {
				console.error("Utilities service -> 'checkRoute' function: argument must be a string");
			}

			if($state.current.name === r){
				return true;
			} else {
				return false;
			}
		};

		var _getData = function(id,a){
			if(!angular.isString(id) || !angular.isArray(a)) {
				console.error("Utilities service -> 'getData' function: got invalid arguments");
			}
			
			var data;
			
			for(var i = 0, l = a.length; i<l; i++){
				if(a[i].id === id){
					data = a[i];
					break;
				}
			}

			return data;
		};

		var _goTo = function(partial, params){
			$state.go(partial,params);
		};

		return {
			checkRoute: _checkRoute,
			getData: _getData,
			goTo: _goTo
		};
	}]);
	