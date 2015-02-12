(function() {

    var
            //the HTTP headers to be used by all requests
            httpHeaders,
            //the message to be shown to the user
            message,
            as = angular.module('managerUserApp', ['managerUserApp.filters', 'managerUserApp.services', 'managerUserApp.directives', 'managerUserApp.controllers']);

    as.value('version', '1.0.7');

    as.config(function($routeProvider, $httpProvider) {
        $routeProvider
                .when('/users', {templateUrl: 'partials/users.html', controller: 'ListUserCtrl'})
                .when('/newUser', {templateUrl: 'partials/newUser.html', controller: 'NewUserCtrl'})
                .when('/login', {templateUrl: 'partials/login.html', controller: 'LoginCtrl'})
                .when('/editUser/:id', {templateUrl: 'partials/editUser.html', controller: 'EditUserCtrl'})
                .otherwise({redirectTo: '/users'});
   });

    as.config(function($httpProvider) {

        //configure $http to catch message responses and show them
        $httpProvider.responseInterceptors.push(
                function($q) {
                    console.log('call response interceptor and set message...');
                    var setMessage = function(response) {
                        //if the response has a text and a type property, it is a message to be shown
                        //console.log('@data'+response.data);
                        if (response.data.message) {
                            message = {
                                text: response.data.message.text,
                                type: response.data.message.type,
                                show: true
                            };
                        }
                    };
                    return function(promise) {
                        return promise.then(
                        //this is called after each successful server request
                            function(response) {
                                setMessage(response);
                                return response;
                            },
                            //this is called after each unsuccessful server request
                            function(response) {
                                setMessage(response);
                                return $q.reject(response);
                            }
                        );
                    };
                });                     
            });

        }());
