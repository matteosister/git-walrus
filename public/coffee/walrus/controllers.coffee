'use strict'

# HOMEPAGE
gitWalrusApp.controller 'HomepageController', ($scope, $http, $interval) ->
    $http.get('/api/branches').success (data) ->
        $scope.branches = data
        $scope.branch = _.find $scope.branches, (b) ->
            b.name = 'master'

    $http.get("/api/log/master").success (data) ->
        $scope.logs = data.commits

    $scope.changeBranch = ->
        $scope.selected_log = null
        $http.get("/api/log/#{$scope.branch.name}").success (data) ->
            $scope.logs = data.commits

    $scope.date = new Date()
    updateDate = ->
        $scope.date = new Date()
    $interval updateDate, 1000

    $scope.changeLog = (log) ->
        $scope.selected_log = null

        $http.get(log.url).success (data) ->
            $scope.selected_log = data

gitWalrusApp.controller 'TreeController', ($scope, $http, $location) ->
    $http.get("/api#{ $location.path() }").success (data) ->
        $scope.tree = data
        $scope.path = $location.path()

    $scope.go = (path) ->
        $location.path path


