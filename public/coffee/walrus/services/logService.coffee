gitWalrusApp.factory 'logService', ['$q', '$http', ($q, $http) ->
    getLogs: (branch = 'master') ->
        $http.get("/api/git/log/#{ branch }", cache: true)
]