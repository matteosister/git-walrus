'use strict'

# HOMEPAGE
gitWalrusApp.controller 'HomepageController', ($scope, $http, $interval, $resource, logService) ->
    workingTree = $resource('/api/status/working-tree');
    workingTree.get {}, (data) ->
        $scope.working_tree = data

    index = $resource('/api/status/index');
    index.get {}, (data) ->
        $scope.index = data

    $http.get('/api/branches').success (data) ->
        $scope.branches = data
        $scope.branch = _.find $scope.branches, (b) ->
            b.name = 'master'

    logService.getLogs().success (data) ->
        $scope.logs = data.commits

    $scope.changeBranch = ->
        $scope.selected_log = null
        logService.getLogs($scope.branch.name).success (data) ->
            $scope.logs = data.commits

    $scope.changeLog = (log) ->
        $scope.selected_log = null
        $http.get(log.url).success (data) ->
            $scope.selected_log = data

    $scope.stage = (file) ->
        $http.post('/api/status/index', file).success ->
            index.get {}, (data) ->
                $scope.index = data
            workingTree.get {}, (data) ->
                $scope.working_tree = data

    $scope.unstage = (file) ->
        $http.post('/api/status/working-tree', file).success ->
            index.get {}, (data) ->
                $scope.index = data
            workingTree.get {}, (data) ->
                $scope.working_tree = data

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


