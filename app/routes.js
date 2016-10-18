angular.module("app")
	.config([
		"$stateProvider", 
		"$urlRouterProvider", 
		function($stateProvider, $urlRouterProvider){
		"use strict";
		
		$urlRouterProvider.otherwise("/");
		$stateProvider
		    .state('main', {
		      	url: "/",
		      	abstract: true,
		      	templateUrl: "app/components/main/main_view.html",
		      	controller: "MainCtrl"
		    })
		    .state('main.home', {
		      	url: "",
		      	abstract: true,
		      	templateUrl: "app/components/home/home_view.html",	      
		      	controller: "HomeCtrl"
		    })
		    .state('main.home.frontpage', {
		      	url: "",
		      	templateUrl: "app/components/home/home_frontpage/home_frontpage_view.html"
		    })
		    .state('main.home.shop', {
		      	url: "shop",
		      	templateUrl: "app/components/home/home_shop/home_shop_view.html"
		    })
	}]);