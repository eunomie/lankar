# Coffee script version

angular.module 'lankar', [], ($compileProvider) ->
  $compileProvider.directive 'compile', ($compile) ->
    converter = new Markdown.Converter()
    (scope, element, attrs) ->
      scope.$watch (scope) ->
        converter.makeHtml(scope.$eval(attrs.compile))
      , (value) ->
        element.html value
        $compile(element.contents())(scope)

this.LinksCtrl = ($scope, $http) ->
  $http.get('data/links.json').success (data) ->
    $scope.links = data.links
    $scope.pagenumber = data.pagenumber
    $scope.total = data.total

