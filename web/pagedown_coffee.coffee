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
  $routeProvider
    .when('/links/:page', { templateUrl: 'partials/links.html', controller: this.LinksCtrl})
    .when('/add', { templateUrl: 'partials/form.html'})
    .otherwise({redirectTo: '/links/1'})


this.LinksCtrl = ($scope, $http, $routeParams) ->
  $http.get('data/links_' + $routeParams.page + '.json').success (data) ->
    $scope.links = data.links
    $scope.total = parseInt data.total
    $scope.page = parseInt $routeParams.page
    $scope.isfirst = $scope.page == 1
    $scope.islast = $scope.page == $scope.total

