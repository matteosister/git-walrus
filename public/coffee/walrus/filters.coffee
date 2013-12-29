angular.module('gitWalrusFilters', [])
    .filter 'strip_last_tree_portion', ->
        (input) ->
            return input unless input?
            lastOccurrence = input.lastIndexOf('/')
            return input if lastOccurrence is -1
            return input.substr(0, lastOccurrence)

    .filter 'gravatar', (gravatar) ->
        (email, size = 80) ->
            return gravatar.generate email, size

    .filter 'title', ->
        (value) ->
            arr = value.split ' '
            arr = _.map arr, (v) ->
                v.charAt(0).toUpperCase() + v.slice(1).toLowerCase();
            arr.join ' '

    .filter 'hide_zero', ->
        (value) ->
            return '' if value == 0
            value
