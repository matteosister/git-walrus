'use strict'

# HOMEPAGE
gitWalrusApp.controller 'HomepageController', ($scope, $http) ->
    $http.get('/api/branches').success (data) ->
        $scope.branches = data.items
    $http.get('/api/log/master').success (data) ->
        $scope.log = data.items