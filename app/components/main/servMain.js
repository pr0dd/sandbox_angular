angular.module("app")
	.factory("spimages", ["$http","apiUrl", function($http, apiUrl){
		"use strict";
		
		return $http.get(apiUrl+"getSPI")
				.then(
					function(response){
						// console.log(response);
						return response.data;
					},
					function(error){
						console.log("Error happened. Status: ", error.status);
					});

	}])
	.factory("availSpi", ["$http","apiUrl", function($http, apiUrl){
		"use strict";
		
		return $http.get(apiUrl+"getAvailSPI")
				.then(
					function(response){
						// console.log(response);
						return response.data;
					},
					function(error){
						console.log("Error happened. Status: ", error.status);
					});

	}])
	.factory("availCti", ["$http","apiUrl", function($http, apiUrl){
		"use strict";
		
		return $http.get(apiUrl+"getAvailCTI")
				.then(
					function(response){
						// console.log(response);
						return response.data;
					},
					function(error){
						console.log("Error happened. Status: ", error.status);
					});

	}])
	.factory("availPri", ["$http","apiUrl", function($http, apiUrl){
		"use strict";
		
		return $http.get(apiUrl+"getAvailPRI")
				.then(
					function(response){
						// console.log(response);
						return response.data;
					},
					function(error){
						console.log("Error happened. Status: ", error.status);
					});

	}])
	.factory("availPromi", ["$http","apiUrl", function($http, apiUrl){
		"use strict";
		
		return $http.get(apiUrl+"getAvailPROMI")
				.then(
					function(response){
						// console.log(response);
						return response.data;
					},
					function(error){
						console.log("Error happened. Status: ", error.status);
					});

	}])
	.factory("prList", ["$http","apiUrl", function($http, apiUrl){
		"use strict";
		
		return $http.get(apiUrl+"getProducts")
				.then(
					function(response){
						// console.log(response);
						return response.data;
					},
					function(error){
						console.log("Error happened. Status: ", error.status);
					});

	}])
	.factory("usersList", ["$http","apiUrl", function($http, apiUrl){
		"use strict";
		
		return $http.get(apiUrl+"getUsers")
				.then(
					function(response){
						// console.log(response);
						return response.data;
					},
					function(error){
						console.log("Error happened. Status: ", error.status);
					});

	}])
	.factory("todosList", ["$http","apiUrl", function($http, apiUrl){
		"use strict";
		
		return $http.get(apiUrl+"getTodos")
				.then(
					function(response){
						// console.log(response);
						return response.data;
					},
					function(error){
						console.log("Error happened. Status: ", error.status);
					});

	}])
	.factory("configList", ["$http","apiUrl", function($http, apiUrl){
		"use strict";
		
		return $http.get(apiUrl+"getConfigs")
				.then(
					function(response){
						// console.log(response);
						return response.data;
					},
					function(error){
						console.log("Error happened. Status: ", error.status);
					});

	}])
	.factory("customersList", ["$http","apiUrl", function($http, apiUrl){
		"use strict";
		
		return $http.get(apiUrl+"getCustomers")
				.then(
					function(response){
						// console.log(response);
						return response.data;
					},
					function(error){
						console.log("Error happened. Status: ", error.status);
					});

	}])
	.factory("ordersList", ["$http","apiUrl", function($http, apiUrl){
		"use strict";
		
		return $http.get(apiUrl+"getOrders")
				.then(
					function(response){
						// console.log(response);
						return response.data;
					},
					function(error){
						console.log("Error happened. Status: ", error.status);
					});

	}])
	.factory("feedsList", ["$http","apiUrl", function($http, apiUrl){
		"use strict";
		
		return $http.get(apiUrl+"getFeeds")
				.then(
					function(response){
						// console.log(response);
						return response.data;
					},
					function(error){
						console.log("Error happened. Status: ", error.status);
					});

	}])
	.factory("catsList", ["$http","apiUrl", function($http, apiUrl){
		"use strict";
		
		return $http.get(apiUrl+"getCats")
				.then(
					function(response){
						// console.log(response);
						return response.data;
					},
					function(error){
						console.log("Error happened. Status: ", error.status);
					});

	}])
	.factory("promotionsList", ["$http","apiUrl", function($http, apiUrl){
		"use strict";
		
		return $http.get(apiUrl+"getProms")
				.then(
					function(response){
						// console.log(response);
						return response.data;
					},
					function(error){
						console.log("Error happened. Status: ", error.status);
					});

	}])
	.factory("viewsList", ["$http","apiUrl", function($http, apiUrl){
		"use strict";
		
		return $http.get(apiUrl+"getViews")
				.then(
					function(response){
						// console.log(response);
						return response.data;
					},
					function(error){
						console.log("Error happened. Status: ", error.status);
					});

	}]);