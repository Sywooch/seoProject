'use strict';
var app = angular.module('app', [
    'ngRoute',      //$routeProvider
    'mgcrea.ngStrap',//bs-navbar, data-match-route directives,
    'ng-imgAreaSelect'
]);
app.controller('ImageController', ['$scope', '$http',
    function ($scope, $http) {
        $scope.areas = [{x1:110, y1:71, x2:210, y2:151}];
        $scope.getCoordinats = function (area) {
            return area.x1 + ', ' + area.y1 + ', ' + area.x2 + ', ' + area.y2;
        }
    }
]);

//app.config(['$routeProvider',
//    function($routeProvider) {
//        $routeProvider.
//            when('/', {
//                templateUrl: '/partials/index.html'
//            }).
//            when('/about', {
//                templateUrl: '/partials/about.html'
//            }).
//            when('/contact', {
//                templateUrl: '/partials/contact.html'
//            }).
//            when('/login', {
//                templateUrl: '/partials/login.html'
//            }).
//            otherwise({
//                templateUrl: '/partials/404.html'
//            });
//    }
//]);