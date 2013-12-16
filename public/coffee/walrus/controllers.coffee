'use strict'

# HOMEPAGE
gitWalrusApp.controller 'HomepageController', ($scope, $http, $interval) ->
    $http.get('/api/branches').success (data) ->
        $scope.branches = data.items

    $http.get('/api/log/master').success (data) ->
        $scope.logs = data.items

    $scope.date = new Date()
    updateDate = ->
        $scope.date = new Date()
    $interval updateDate, 1000

gitWalrusApp.controller 'TreeController', ($scope, $http, $location) ->
    $http.get("/api#{ $location.path() }").success (data) ->
        $scope.tree = data
        $scope.path = $location.path()

    $scope.go = (path) ->
        $location.path path


