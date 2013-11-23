'use strict'

gitWalrusApp = angular.module 'gitWalrusApp', [
    'ngRoute'
]

gitWalrusApp.config ['$routeProvider', '$locationProvider', ($routeProvider, $locationProvider) ->
    $routeProvider
        .when '/',
            templateUrl: 'partials/homepage.html'
            controller: 'HomepageController'
        .otherwise
            redirectTo: '/'

    $locationProvider.html5Mode(true)
]