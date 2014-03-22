'use strict'

class WorkingTree
    contents: null

    constructor: ($resource) ->
        @res = $resource '/api/git/status/working-tree'
        @contents = @res.get()

gitWalrusApp.factory 'WorkingTree', ($resource) ->
    return new WorkingTree($resource)