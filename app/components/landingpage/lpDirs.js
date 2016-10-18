angular.module("app")
	.directive("animTrigger", [
		"$window",
		"$document",
		function($window, $document){
		"use strict";

		return function($scope, $element, $attrs){
				
			var w = angular.element($window);
			var d = angular.element($document);
			var bp = 768;
			var animationElements = $element.find(".lpwb-move");


			var imgControl = function(){

				if(w.width()<bp || animationElements.hasClass("in-view")) {
					return;
				}
				var windowHeight = w.height();
			  	var windowTopPosition = w.scrollTop();
			  	var windowBottomPosition = (windowTopPosition + windowHeight);
		    	
		    	var elementHeight = $element.outerHeight();
		    	var elementTopPosition = $element.offset().top;
		    	var elementBottomPosition = (elementTopPosition + elementHeight);

			    //check to see if this current container is within viewport
			    if ((elementBottomPosition >= windowTopPosition) &&
			        (elementTopPosition <= windowBottomPosition)) {
			    	animationElements.addClass('in-view');
			    }
				
			};
			w.on("scroll resize", imgControl);

			//REMOVE HANDLER WHEN SCOPE DIES:
			$scope.$on("$stateChangeStart", function(){
				w.off("scroll", imgControl);
				w.off("resize", imgControl);
			});
			$scope.$on("$destroy", function(){
				w.off("scroll", imgControl);
				w.off("resize", imgControl);
			});
		};

	}])
	.directive("feedback", ["$http","$filter", function($http,$filter){
		"use strict";

		return {
			restrict: "EA", 
			templateUrl: "app/components/landingpage/feedTmpl.html",
			scope: {
				fds:"=feedback"
			},
			link: function(scope, element, attrs){
				scope.feeds = angular.copy(scope.fds);
				scope.feeds = $filter("filter")(scope.feeds, {approved:"yes"});
				if(scope.feeds.length === 0) {
					return;
				}
				scope.feeds[0].active = true;
				scope.prevFeed = function(a,i){
					a[i].active = false;
					if(i === 0) {
						a[a.length-1].active = true;
					} else {
						a[i-1].active = true;
					}
				};
				scope.nextFeed = function(a,i){
					a[i].active = false;
					if(i === a.length-1) {
						a[0].active = true;
					} else {
						a[i+1].active = true;
					}

				};
			}
		};
	}]);