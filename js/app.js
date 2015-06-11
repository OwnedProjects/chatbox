var chatbox = angular.module('chatbox', [
'ngRoute',
'ngResource'
]);

chatbox.run(function($rootScope, $http, $templateCache) {
	$rootScope.usernm="";
	if(localStorage.usernm){
		$rootScope.usernm = localStorage.usernm;
	}	
});

chatbox.config(['$routeProvider','$resourceProvider',
  function($routeProvider,$resourceProvider) {
    $routeProvider.
      when('/home', {
        templateUrl: 'views/home.html'
      }).
	  when('/chat/:param', {
		templateUrl: 'views/chat.html'		
	  }).   
      otherwise({
        redirectTo: '/home'
      });
	  
}]);