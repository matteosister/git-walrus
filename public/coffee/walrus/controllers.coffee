'use strict'

# HOMEPAGE
gitWalrusApp.controller 'HomepageController', ($scope, $http, $resource, $interval) ->
    workingTree = $resource('/api/status/working-tree');
    workingTree.get {}, (data) ->
        $scope.working_tree = data

    index = $resource('/api/status/index');
    index.get {}, (data) ->
        $scope.index = data

    $scope.stage = (file) ->
        $scope.loading = true
        $http.post('/api/status/index', file).success ->
            $scope.loading = false
            index.get {}, (data) ->
                $scope.index = data
            workingTree.get {}, (data) ->
                $scope.working_tree = data

    $scope.unstage = (file) ->
        $scope.loading = true
        $http.post('/api/status/working-tree', file).success ->
            $scope.loading = false
            index.get {}, (data) ->
                $scope.index = data
            workingTree.get {}, (data) ->
                $scope.working_tree = data

gitWalrusApp.controller 'LogController', ($scope, $http, logService) ->
    $http.get('/api/branches').success (data) ->
        $scope.branches = data
        $scope.branch = _.find $scope.branches, (b) ->
            b.name = 'master'

    logService.getLogs().success (data) ->
        $scope.logs = data.commits

    $scope.changeBranch = ->
        $scope.loading = true
        $scope.selected_log = null
        logService.getLogs($scope.branch.name).success (data) ->
            $scope.loading = false
            $scope.logs = data.commits

    $scope.changeLog = (log) ->
        $scope.loading = true
        $http.get(log.url).success (data) ->
            $scope.loading = false
            $scope.selected_log = data
            $scope.loading = false

gitWalrusApp.controller 'TreeController', ($scope, $http, $location) ->
    $scope.loading = true
    $http.get("/api#{ $location.path() }").success (data) ->
        $scope.loading = false
        $scope.tree = data
        $scope.path = $location.path()

    $scope.go = (path) ->
        $location.path path


