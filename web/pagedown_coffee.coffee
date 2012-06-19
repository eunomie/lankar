# Coffee script version

angular.module 'lankar', [], ($compileProvider, $routeProvider) ->
  $compileProvider.directive 'compile', ($compile) ->
    converter = new Markdown.Converter()
    (scope, element, attrs) ->
      scope.$watch (scope) ->
        converter.makeHtml(scope.$eval(attrs.compile))
      , (value) ->
        element.html value
        $compile(element.contents())(scope)
  $routeProvider.when('/links/:page', { templateUrl: 'partials/links.html', controller: this.LinksCtrl}).otherwise({redirectTo: '/links/1'})


this.LinksCtrl = ($scope, $http, $routeParams) ->
  $http.get('data/links.json').success (data) ->
    $scope.links = data.links
    $scope.total = data.total
    $scope.page = parseInt $routeParams.page

