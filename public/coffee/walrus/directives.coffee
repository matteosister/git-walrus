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
    controller = ($scope, $interval) ->
        $scope.date = new Date()
        $scope.updateDate = ->
            $scope.date = new Date()
        $interval $scope.updateDate, 1000

    return restrict: 'A', controller: controller

gitWalrusApp.directive 'statusfile', ->
    link = (scope, element, attr) ->
        tpl = _.template """
        <h4 class="status_file <%= attr.class %>">
            <i class="fa fa-1g fa-file"></i> <%= file.name %>
        </h4>
        """
        element.append tpl(file: scope.file, attr: attr)
        element.draggable
            revert: true
    return restrict: 'E', link: link

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

gitWalrusApp.directive 'stagingarea', ->
    link = (scope, element, attr) ->
        element.droppable
            hoverClass: 'state-hover'
            drop: ( event, ui ) ->
                $( this )
                    .addClass( "state-highlight" )
                    .find( "p" )
                    .html( "Dropped!" );
    return restrict: 'A', link: link