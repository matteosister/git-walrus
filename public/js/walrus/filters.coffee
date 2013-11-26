angular.module('gitWalrusFilters', [])
    .filter 'strip_last_tree_portion', ->
        (input) ->
            return input unless input?
            lastOccurrence = input.lastIndexOf('/')
            return input if lastOccurrence is -1
            return input.substr(0, lastOccurrence)