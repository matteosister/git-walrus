# HOMEPAGE
gitWalrusApp.controller 'HomepageController', ($scope, $http) ->
    $http.get('/api/branches').success (data) ->
        $scope.branches = data.items