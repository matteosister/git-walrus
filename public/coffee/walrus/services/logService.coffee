gitWalrusApp.factory 'logService', ['$q', '$http', ($q, $http) ->
    getLogs: (branch = 'master') ->
        $http.get("/api/log/#{ branch }", cache: true)
]