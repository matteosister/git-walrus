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
        <h4 class="<%= attr.class %>">
            <i class="fa fa-1g fa-file"></i> <%= file.name %>
        </h4>
        """
        element.append tpl(file: scope.file, attr: attr)
        Draggable.create element,
            type:"x,y"
            edgeResistance:0.65
            bounds:".row"
            throwProps:true
    return restrict: 'E', link: link

gitWalrusApp.directive 'loader', ->
    opts =
        lines: 13
        length: 20
        width: 10
        radius: 30
        corners: 1
        rotate: 0
        direction: 1
        color: '#000'
        speed: 1
        trail: 60
        shadow: false
        hwaccel: false
        className: 'spinner'
        zIndex: 2e9
        top: 'auto'
        left: 'auto'
    link = (scope, element, attr) ->
        console.log attr
        element.spin(opts);
    return restrict: 'A', link: link