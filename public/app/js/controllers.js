(function() {
    var as = angular.module('managerUserApp.controllers', ['ngResource']);   

    as.controller('managerUserAppCtrl', function($scope, $rootScope, $http, i18n, $location) {
        $scope.language = function() {
            return i18n.language;
        };
        $scope.setLanguage = function(lang) {
            i18n.setLanguage(lang);
        };
        $scope.activeWhen = function(value) {
            return value ? 'active' : '';
        };

        $scope.path = function() {
            return $location.url();
        };
        $rootScope.appUrl = "http://localhost/ZF2+Angular/ZF2_Angular_User/public";

    });

    as.controller('ListUserCtrl', function($scope, $rootScope, $http, $routeParams, $location) {
        var load = function() {
            console.log('call load()...');
            console.log($rootScope.appUrl);
            $http.get($rootScope.appUrl+'/manager-user')
                 .success(function(data, status, headers, config) {
                    $scope.users = data.data;
                    angular.copy($scope.users, $scope.copy);
                });
        }
        load();
        $scope.deleteUser=function(index){
            var user=$scope.users[index];
            console.log('Bạn muốn xóa user có id = '+user.id);
            $http.delete($rootScope.appUrl+'/manager-user/'+user.id)
                 .success(function(data, status, headers, config){
                    console.log('Xóa thành công!');
                    load();
                 })
                 .error(function(data, status, headers, config){
                    console.log('Xóa thất bại!');
                    load();
                 })

        }
    });

    as.controller('NewUserCtrl',function($scope, $rootScope, $http, $location){
        $scope.user={};
        $scope.newUser=function(){
            $http.post($rootScope.appUrl+'/manager-user',$scope.user)
                 .success(function(data, status, headers, config){
                    console.log('success');
                    $location.path('/users');
                 })
                 .error(function(data, status, headers, config){
                    console.error('error');
                    $location.path('/users');
                 })
            console.log('new user');
        }
    });

    as.controller('EditUserCtrl',function($scope, $rootScope, $http, $routeParams, $location){
        $scope.user={};
        var load=function(){
            console.log($routeParams['id']);
            $http.get($rootScope.appUrl+'/manager-user/'+$routeParams['id'])
                 .success(function(data, status, headers, config){
                    $scope.user=data.data;
                    angular.copy($scope.user, $scope.copy);
                 })                
        }
        load();
        $scope.editUser=function(){
            $http.put($rootScope.appUrl+'/manager-user/'+$scope.user.id,$scope.user)
                 .success(function(data, status, headers, config){
                    console.log('Cập nhật thành công!');
                    $location.path('/users');
                 })
                 .error(function(data, status, headers, config){
                    console.log('Lỗi không edit được user');
                    $location.path('/users');

                 })
        }


    });

    // as.factory('resource', function($resource){
    //     return $resource($rootScope.appUrl+'/login');
    // });

    as.controller('LoginCtrl',function($scope, $rootScope, $http, $routeParams, $location, $resource){
        $scope.user={};
        $scope.login=function(){
            $http.post($rootScope.appUrl+'/login',$scope.user)
                 .success(function(data, status, headers, config){
                    if(data.data=='success'){
                        console.log('Đăng nhập thành công!');
                        $location.path('/users');
                    }
                    else
                    {
                        console.log('Đăng nhập thất bại');
                        $location.path('/users');
                    }
                 })
                 .error(function(data, status, headers, config){
                    console.log('Đăng nhập thất bại');
                    $location.path('/users');
                 })

            // Định nghĩa resource
            // console.log('Define CreditCard class');
            // var userResource = $resource($rootScope.appUrl+'/login',
            //  {}, {
            //   charge: {method:'GET', params:{charge:true}}
            //  });

            // sử dụng phương thức get 
            // var users = userResource.get({id:1},function() {
            //     console.log(users);
            // });
            //sử dụng phương thức query
            // var users = userResource.query(function() {
            //     //console.log(users[0]);
            //     console.log('success');
            // });


        }
    });

    
 
}());