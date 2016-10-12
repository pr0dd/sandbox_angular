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
		      templateUrl: "app/components/main/main.html",
		      controller: "MainCtrl"
		    })
		    .state('main.frontpage', {
		      url: "", 
		      templateUrl: "app/components/frontpage/frontpage.html",	      
		      controller: "FrontCtrl"
		    })
		    .state('main.home', {
		      url: "home",
		      abstract: true, 
		      templateUrl: "app/components/home/home.html",	      
		      controller: "HomeCtrl"
		    })
		    .state('main.home.landing', {
		      url: "/landing", 
		      templateUrl: "app/components/landingpage/landing.html",	      
		      controller: "LandingCtrl"
		    });

		    // .state('main.home.shop', {
		    //   url: "/shop/:category", 
		    //   templateUrl: "app/components/shop/shop.html",	      
		    //   controller: "ShopCtrl"
		    // })
		    // .state('main.home.product', {
		    //   url: "/product/:id", 
		    //   templateUrl: "app/components/shop/product/product.html",	      
		    //   controller: "ProductCtrl"
		    // })
		    // .state('main.home.promotion', {
		    //   url: "/promotion", 
		    //   templateUrl: "app/components/promotion/promotion.html",	      
		    //   controller: "PromotionCtrl"
		    // })
		    // .state('main.home.videos', {
		    //   url: "/videos", 
		    //   templateUrl: "app/components/videos/videos.html",	      
		    //   controller: "VideosCtrl"
		    // })
		    // .state('main.home.delivery', {
		    //   url: "/delivery", 
		    //   templateUrl: "app/components/delivery/delivery.html",	      
		    //   controller: "DeliveryCtrl"
		    // })
		    // .state('main.home.contacts', {
		    //   url: "/contacts", 
		    //   templateUrl: "app/components/contacts/contacts.html",	      
		    //   controller: "ContactsCtrl"
		    // })
		    // .state('main.login', {
		    //   url: "login4486633", 
		    //   templateUrl: "app/components/login/loginView.html",	      
		    //   controller: "LoginCtrl"
		    // })
		    // //ADMIN:
		    // .state('main.admin', {
		    //   url: "admin4486633",
		    //   abstract: true, 
		    //   templateUrl: "app/components/admin/adminMain.html",
		    //   resolve: {
		    //   	currentUser: [
		    //   		"$http",
		    //   		"apiUrl",
		    //   		"$localStorage",
		    //   		"$q", 
		    //   		function($http, apiUrl, $localStorage, $q){
			   //    		var deferred = $q.defer();
						// // console.log("stored:" , $localStorage.BRCreds);
				  // 		if($localStorage.BRCreds) {
				  //     		var user = {
				  //     			login: $localStorage.BRCreds.login,
				  //     			pass: $localStorage.BRCreds.pass
				  //     		};
				  //     		return $http.post(apiUrl+"login", user)
					 //      			.then(
					 //      				function(response){
					 //      					// console.log("success Account: ",response);
					 //      					return {
					 //      						data: response.data
					 //      					};
					 //      				}, 
					 //      				function(error){
					 //      					console.log("Error happened. Status: ", error);
					 //      					return {
					 //      						err: error 
					 //      					};
					      					
					 //      				});
				  // 		} else {
				  // 			deferred.reject();
				  //     		return deferred.promise
						//       			.catch(function(){
						// 	      					return {
						// 	      						info: "ACCOUNT. User has not been logined yet"
						// 	      					};
						// 	      				});
				  // 		}
		    //   	}],
		    //   	Spi: ["availSpi", function(availSpi){
		    //   		return availSpi;
		    //   	}],
		    //   	Cti: ["availCti", function(availCti){
		    //   		return availCti;
		    //   	}],
		    //   	Pri: ["availPri", function(availPri){
		    //   		return availPri;
		    //   	}],
		    //   	Promi: ["availPromi", function(availPromi){
		    //   		return availPromi;
		    //   	}],
		    //   	users: ["usersList", function(usersList){
		    //   		return usersList;
		    //   	}],
		    //   	todos: ["todosList", function(todosList){
		    //   		return todosList;
		    //   	}],
		    //   	orders: ["ordersList", function(ordersList){
		    //   		return ordersList;
		    //   	}],
		    //   	customers: ["customersList", function(customersList){
		    //   		return customersList;
		    //   	}],
		    //   	trackViews: ["viewsList", function(viewsList){
		    //   		return viewsList;
		    //   	}]
		    //   },	      
		    //   controller: "AdminCtrl"
		    // })
		    // .state('main.admin.dashboard', {
		    //   url: "/dashboard", 
		    //   templateUrl: "app/components/admin/dashboard.html",	      
		    //   controller: "DashboardCtrl"
		    // })
		    // //ORDERS:
		    // .state('main.admin.orders', {
		    //   url: "/orders", 
		    //   templateUrl: "app/components/admin/orders/orders.html",	      
		    //   controller: "OrdersCtrl"
		    // })
		    // .state('main.admin.order', {
		    //   url: "/order/:id", 
		    //   templateUrl: "app/components/admin/orders/order.html",	      
		    //   controller: "OrderCtrl"
		    // })
		    // //CUSTOMERS:
		    // .state('main.admin.customers', {
		    //   url: "/customers", 
		    //   templateUrl: "app/components/admin/customers/customers.html",	      
		    //   controller: "CustomersCtrl"
		    // })
		    // .state('main.admin.customer', {
		    //   url: "/customer/:id", 
		    //   templateUrl: "app/components/admin/customers/customer.html",	      
		    //   controller: "CustomerCtrl"
		    // })
		    // //FEEDS:
		    // .state('main.admin.feeds', {
		    //   url: "/feeds", 
		    //   templateUrl: "app/components/admin/feeds/feeds.html",	      
		    //   controller: "FeedsCtrl"
		    // })
		    // .state('main.admin.feed', {
		    //   url: "/feed/:id", 
		    //   templateUrl: "app/components/admin/feeds/feed.html",	      
		    //   controller: "FeedCtrl"
		    // })
		    // //STARTPAGE:
		    // .state('main.admin.spimages', {
		    //   url: "/spimages", 
		    //   templateUrl: "app/components/admin/startpage/startpageImages.html",	      
		    //   controller: "StartpageImagesCtrl"
		    // })
		    // .state('main.admin.spimage', {
		    //   url: "/spimage/:id", 
		    //   templateUrl: "app/components/admin/startpage/startpageImage.html",	      
		    //   controller: "StartpageImageCtrl"
		    // })
		    // //CATEGORIES:
		    // .state('main.admin.categories', {
		    //   url: "/categories", 
		    //   templateUrl: "app/components/admin/categories/categories.html",	      
		    //   controller: "CategoriesCtrl"
		    // })
		    // .state('main.admin.category', {
		    //   url: "/category/:id", 
		    //   templateUrl: "app/components/admin/categories/category.html",	      
		    //   controller: "CategoryCtrl"
		    // })
		    // .state('main.admin.newcat', {
		    //   url: "/newcat", 
		    //   templateUrl: "app/components/admin/categories/newcat.html",	      
		    //   controller: "NewCatCtrl"
		    // })
		    // //PRODUCTS:
		    // .state('main.admin.products', {
		    //   url: "/products", 
		    //   templateUrl: "app/components/admin/products/products.html",	      
		    //   controller: "ProductsCtrl"
		    // })
		    // .state('main.admin.product', {
		    //   url: "/prod/:id", 
		    //   templateUrl: "app/components/admin/products/prod.html",	      
		    //   controller: "ProdCtrl"
		    // })
		    // .state('main.admin.newproduct', {
		    //   url: "/newproduct", 
		    //   templateUrl: "app/components/admin/products/newproduct.html",	      
		    //   controller: "NewProductCtrl"
		    // })
		    // //USERS:
		    // .state('main.admin.users', {
		    //   url: "/users", 
		    //   templateUrl: "app/components/admin/users/users.html",	      
		    //   controller: "UsersCtrl"
		    // })
		    // .state('main.admin.user', {
		    //   url: "/user/:id", 
		    //   templateUrl: "app/components/admin/users/user.html",	      
		    //   controller: "UserCtrl"
		    // })
		    // .state('main.admin.newuser', {
		    //   url: "/newuser", 
		    //   templateUrl: "app/components/admin/users/newuser.html",	      
		    //   controller: "NewUserCtrl"
		    // })
		    // //CONFIGS:
		    // .state('main.admin.configs', {
		    //   url: "/configs", 
		    //   templateUrl: "app/components/admin/configs/configs.html",	      
		    //   controller: "ConfigsCtrl"
		    // })
		    // //PROMS:
		    // .state('main.admin.proms', {
		    //   url: "/proms", 
		    //   templateUrl: "app/components/admin/proms/proms.html",	      
		    //   controller: "PromsCtrl"
		    // })
		    // .state('main.admin.prom', {
		    //   url: "/prom/:id", 
		    //   templateUrl: "app/components/admin/proms/prom.html",	      
		    //   controller: "PromCtrl"
		    // })
		    // .state('main.admin.newprom', {
		    //   url: "/newprom", 
		    //   templateUrl: "app/components/admin/proms/newprom.html",	      
		    //   controller: "NewPromCtrl"
		    // })
		    // //TODOS:
		    // .state('main.admin.todo', {
		    //   url: "/todo", 
		    //   templateUrl: "app/components/admin/todo/todo.html",	      
		    //   controller: "TodoCtrl"
		    // })
		    // //TRACKVIEWS:
		    // .state('main.admin.trackviews', {
		    //   url: "/trackviews", 
		    //   templateUrl: "app/components/admin/trackviews/trackviews.html",	      
		    //   controller: "TrackviewsCtrl"
		    // });

	}]);