gitWalrusApp = angular.module 'gitWalrusApp', [
    'ngRoute'
]

gitWalrusApp.config ['$routeProvider', ($routeProvider) ->
    $routeProvider
        .when '/',
            templateUrl: 'partials/homepage.html'
            controller: 'HomepageController'
        .otherwise
            redirectTo: '/'
]