angular.module('app').run(['$templateCache', function($templateCache) {
  'use strict';

  $templateCache.put('app/components/frontpage/frontpage.html',
    " <div class=\"front-page\"> <div class=\"container-fluid fp-content\"> <div class=\"row\"> <div class=\"container\"> <div class=\"row\"> <div class=\"col-xs-12 fp-content-wrapper\"> <div class=\"row fp-top-line\"> <div class=\"col-sm-4\"> <div class=\"fp-tl-wrapper\"> <h2>FRONT PAGE</h2> <a ui-sref=\"main.home.landing\">Home</a> </div> </div> </div> </div> </div> </div> </div> </div> </div>"
  );


  $templateCache.put('app/components/home/footer/footer.html',
    "<footer class=\"footer\"> <div class=\"container-fluid main-btm-menu\"> <div class=\"row\"> <div class=\"container\"> <div class=\"row\"> <div class=\"col-xs-12 btm-menu-wrapper\"> <h3>HOME: FOOTER</h3> </div> </div> </div> </div> </div> </footer>"
  );


  $templateCache.put('app/components/home/header/header.html',
    "<header class=\"header\"> <div class=\"container-fluid header-top-part\"> <div class=\"row\"> <div class=\"container\"> <div class=\"row htp-row\" ng-switch on=\"data.lang\"> <div class=\"col-sm-4\"> <div class=\"htp-wrapper logo\"> <a ui-sref=\"main.home.landing\"> <img src=\"assets/img/logo.png\"> </a> </div> </div> <div class=\"col-sm-4\"> <div class=\"htp-wrapper socials\"> <h3>HOME:HEADER</h3> </div> </div> <div class=\"col-sm-4\"> <div class=\"htp-wrapper phone\"> </div> </div> </div> </div> </div> </div> </header> "
  );


  $templateCache.put('app/components/home/home.html',
    "<div class=\"home\"> <ng-include src=\"'app/components/home/header/header.html'\"></ng-include> <ui-view></ui-view> <div class=\"push\"></div> </div> <ng-include src=\"'app/components/home/footer/footer.html'\"></ng-include>"
  );


  $templateCache.put('app/components/landingpage/landing.html',
    " <div class=\"container-fluid landing-page\"> <div class=\"row lp-bg\"> <div class=\"container\"> <div class=\"row lp-row\"> <div class=\"col-xs-12 lp-wrapper\"> <h3>HOME: LANDING PAGE</h3> <a ui-sref=\"main.frontpage\">FrontPage</a> </div> </div> </div> </div> </div> <div class=\"container-fluid lp-why\"></div> "
  );


  $templateCache.put('app/components/main/main.html',
    " <ui-view></ui-view> "
  );

}]);
