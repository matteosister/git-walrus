"use strict"

gitWalrusApp = angular.module 'gitWalrusApp', [
    'ngRoute',
    'gitWalrusFilters',
    'angular-underscore',
    'hljs'
]

gitWalrusApp.config ['$routeProvider', '$locationProvider', ($routeProvider, $locationProvider) ->
    $routeProvider
        .when '/',
            templateUrl: '/partials/homepage.html'
            controller: 'HomepageController'
        .when '/tree/:ref*',
            templateUrl: '/partials/tree.html'
            controller: 'TreeController'
        .otherwise
            redirectTo: '/'

    $locationProvider.html5Mode(true)
]