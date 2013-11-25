angular.module('gitWalrusFilters', [])
    .filter 'strip_last_tree_portion', ->
        (input) ->
            lastOccurrence = input.lastIndexOf('/')
            return input.substr(0, lastOccurrence)