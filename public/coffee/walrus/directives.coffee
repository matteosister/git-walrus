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
        console.log scope.file
        title = $('<h4>').addClass(scope.file.description)
        element.append title
        icon = $('<i>').addClass 'fa fa-1g fa-file'
        title.append icon
        title.append " #{scope.file.name} "
        title.append """
<a class="btn btn-default btn-xs" role="button" ng-click="stage(file)">
    <i class="fa fa-arrow-circle-right"></i> Stage
</a>
"""

    return restrict: 'E', link: link