chatbox.controller("HomeController", function($scope, $location, $http, $rootScope){
	console.log($rootScope.timeout);
	clearTimeout($rootScope.timeout);
	$scope.startChat = function(){
		$http({
			method: 'POST',
			url: 'php_controllers/master.php?action=CheckChatAvail',
			headers: {'Content-Type': 'application/x-www-form-urlencoded'},
			data: {"main": $scope.chatRoomName, "usrname": $scope.chatUserName, "createnew" : true}
		}).
			success(function(data, status, headers, config) {
		}).
		error(function(data, status, headers, config) {
			alert('Service Error');
		}).
		then(function(result){
			$location.path('/chat/'+ $scope.chatRoomName);
			console.log(result.data);
			$rootScope.usernm=$scope.chatUserName;
			localStorage.usernm=$scope.chatUserName;
			$rootScope.grpId=result.data.grpid;
		});
	};
});

chatbox.controller("ChatController", function($scope, $location, $routeParams, $http, $rootScope){
	//alert($routeParams.param);
	$(".overlay").show();
	$scope.fetchChat = function(){
		$http({
			method: 'POST',
			url: 'php_controllers/master.php?action=CheckChatAvail',
			headers: {'Content-Type': 'application/x-www-form-urlencoded'},
			data: {"main": $routeParams.param,  "usrname": "", "createnew" : false}
		}).
			success(function(data, status, headers, config) {
		}).
		error(function(data, status, headers, config) {
			alert('Service Error');
		}).
		then(function(result){
			//console.log(result.data);
			if(result.data.chatgrpavail!=false){
				$scope.chatdata=result.data.chtmsgs;
				$rootScope.grpId=result.data.grpid;
				$rootScope.timeout=setTimeout($scope.fetchChat, 2000);
			}
				$(".overlay").hide();
		});
	};
	$scope.fetchChat();
		
	$scope.enterChat = function(){
		clearTimeout($rootScope.timeout);
		$http({
			method: 'POST',
			url: 'php_controllers/master.php?action=enterChat',
			headers: {'Content-Type': 'application/x-www-form-urlencoded'},
			data: {"usrname": $scope.usrname, "grpid": $rootScope.grpId}
		}).
			success(function(data, status, headers, config) {
		}).
		error(function(data, status, headers, config) {
			alert('Service Error');
		}).
		then(function(result){
			//console.log(result.data);
			$scope.fetchChat();
		});
		$rootScope.usernm=$scope.usrname;
		localStorage.usernm=$scope.usrname;
	}
	
	$scope.sendMsg = function(){
		$http({
			method: 'POST',
			url: 'php_controllers/master.php?action=sendMsg',
			headers: {'Content-Type': 'application/x-www-form-urlencoded'},
			data: {"usernm": $rootScope.usernm, "msgtext": $scope.msgtext, "grpId": $rootScope.grpId}
		}).
			success(function(data, status, headers, config) {
		}).
		error(function(data, status, headers, config) {
			alert('Service Error');
		}).
		then(function(result){
			//console.log(result.data);
			$scope.msgtext='';
			
		});
	}
	
	$scope.path=window.location.href;
});

chatbox.filter('unsafe', function($sce) {
    return function(val) {
        return $sce.trustAsHtml(val);
    };
});