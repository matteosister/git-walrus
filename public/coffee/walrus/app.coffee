"use strict"

gitWalrusApp = angular.module 'gitWalrusApp', [
    'ngRoute',
    'gitWalrusFilters',
    'angular-underscore',
    'hljs',
    'ngAnimate'
]

gitWalrusApp.config ['$routeProvider', '$locationProvider', ($routeProvider, $locationProvider) ->
    $routeProvider
        .when '/',
            templateUrl: '/partial/homepage.html'
            controller: 'HomepageController'
        .when '/tree/:ref*',
            templateUrl: '/partial/tree.html'
            controller: 'TreeController'
        .otherwise
            redirectTo: '/'

    $locationProvider.html5Mode(true)
]
