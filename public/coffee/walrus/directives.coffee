'use strict'

gitWalrusApp.directive 'gwHoverDetails', ->
    link = (scope, element, attr) ->
        element.html attr.gwHoverDetailsSmall
        element.on 'mouseenter', () ->
            element.html attr.gwHoverDetails
        element.on 'mouseleave', (event) ->
            element.html attr.gwHoverDetailsSmall
    return link: link

gitWalrusApp.directive 'clock', ->
    link = (scope, element, attr) ->
        element.addClass 'clock'

    controller = ($scope, $interval) ->
        $scope.date = new Date()
        $scope.updateDate = ->
            $scope.date = new Date()
        $interval $scope.updateDate, 1000

    return restrict: 'E', controller: controller, link: link

gitWalrusApp.directive 'loader', ->
    opts =
        lines: 13
        length: 0
        width: 4
        radius: 10
        corners: 1
        rotate: 0
        direction: 1
        color: '#000'
        speed: 2.2
        trail: 58
        shadow: false
        hwaccel: true
        className: 'spinner'
        zIndex: 2e9
        top: 'auto'
        left: 'auto'
    link = (scope, element, attr) ->
        element.spin(opts);
    return restrict: 'A', link: link

gitWalrusApp.directive 'statusfile', ->
    link = (scope, element, attr) ->
        scope.file.selected = false
        element.selectable
            filter: "h4"
            selected: ->
                scope.file.selected = true
                scope.$digest()
            unselected: ->
                scope.file.selected = false
                scope.$digest()
        element.find('input[type=checkbox]').on 'change', (e) ->
            if $(e.target).is(':checked')
                element.find('h4').addClass 'ui-selected'
            else
                element.find('h4').removeClass 'ui-selected'

    return {
        restrict: 'E',
        link: link
        template: '<h4 class="status_file <%= attr.class %>">
                <input type="checkbox" ng-model="file.selected" />
                <i class="fa fa-1g fa-file"></i> {{ file.name }}
            </h4>'
    }

gitWalrusApp.directive 'stagingarea', ->
    link = (scope, element, attr) ->
    return restrict: 'A', link: link

gitWalrusApp.directive 'prettyprint', ->
    link = (scope, element, attr) ->
        outputContent = scope.tree.binary_data.toString()
        content = "<pre>" + outputContent.escapeHTML() + "</pre>"
        element.html prettyPrintOne(content, null, true)
    return {
        restrict: 'A',
        link: link
    }
