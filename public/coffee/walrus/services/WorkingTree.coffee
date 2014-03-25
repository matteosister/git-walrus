'use strict'

class WorkingTree
    res: null
    files: null

    constructor: ($resource) ->
        @res = $resource(
            '/api/git/status/working-tree'
            {}
            {'query': { method: 'GET', isArray: false }}
        )
        @files = @res.query()

gitWalrusApp.factory 'WorkingTree', ($resource) ->
    return new WorkingTree($resource)