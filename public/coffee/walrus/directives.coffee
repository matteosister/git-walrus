'use strict'

gitWalrusApp.directive 'gwHoverDetails', ->
    link = (scope, element, attr) ->
        element.on 'mouseenter', (event) ->
            console.log event
    link: link