'use strict'

# HOMEPAGE
gitWalrusApp.controller 'HomepageController', ($scope, $http, $interval) ->
    $http.get('/api/branches').success (data) ->
        $scope.branches = data.items

    $http.get('/api/log/master').success (data) ->
        $scope.log = data.items

    $scope.date = new Date()

    updateDate = ->
        $scope.date = new Date()
    $interval updateDate, 1000
