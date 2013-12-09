// Generated by CoffeeScript 1.6.3
'use strict';
var gitWalrusApp;

gitWalrusApp = angular.module('gitWalrusApp', ['ngRoute', 'gitWalrusFilters']);

gitWalrusApp.config([
  '$routeProvider', '$locationProvider', function($routeProvider, $locationProvider) {
    $routeProvider.when('/', {
      templateUrl: '/partials/homepage.html',
      controller: 'HomepageController'
    }).when('/tree/:ref*', {
      templateUrl: '/partials/tree.html',
      controller: 'TreeController'
    }).otherwise({
      redirectTo: '/'
    });
    return $locationProvider.html5Mode(true);
  }
]);