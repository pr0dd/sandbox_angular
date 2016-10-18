angular.module('app').run(['$templateCache', function($templateCache) {
  'use strict';

  $templateCache.put('app/components/home/home_frontpage/home_frontpage_view.html',
    "<h1>Frontpage view (home.frontpage)</h1>"
  );


  $templateCache.put('app/components/home/home_shop/home_shop_view.html',
    "<h1>Shope view (home.shop)</h1>"
  );


  $templateCache.put('app/components/home/home_view.html',
    "<div class=\"container-fluid\"> <div class=\"row\"> <div class=\"container\"> <div class=\"row\"> <div class=\"col-sm-4\"> <ul> <li><a ui-sref=\"main.home.frontpage\">Frontpage</a></li> <li><a ui-sref=\"main.home.shop\">Shop</a></li> </ul> </div> <div class=\"col-sm-8\" ui-view></div> </div> </div> </div> </div>"
  );


  $templateCache.put('app/components/main/main_view.html',
    "<div class=\"main-component\" ui-view></div>"
  );

}]);
