# Coffee script version

angular.module 'lankar', [], ($compileProvider, $routeProvider, $locationProvider) ->
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
    .when('/add', { templateUrl: 'partials/form.html', controller: this.addCtrl})
    .otherwise({redirectTo: '/links/1'})


this.LinksCtrl = ($scope, $http, $routeParams) ->
  $http.get('lankar.php/dblinks/' + $routeParams.page).success (data) ->
    $scope.links = data.links
    $scope.total = parseInt data.total
    $scope.page = parseInt $routeParams.page
    $scope.isfirst = $scope.page == 1
    $scope.islast = $scope.page == $scope.total

this.addCtrl = ($scope, $http, $routeParams, $location) ->
  master = {
    'url': ''
    'desc': ''
  }

  $scope.cancel = () ->
    $scope.form = angular.copy master

  $scope.save = () ->
    $http({
      'method': 'POST'
      'url': 'lankar.php/link'
      'data': 'url=' + $scope.form.url + '&desc=' + encodeURIComponent $scope.form.desc
      'headers': {
        'Content-Type': 'application/x-www-form-urlencoded'
      }
    }).success (data, status) ->
      $location.path '/links/1'
    .error (data, status) ->
      alert('fail')
    master = $scope.form
    $scope.cancel()

  $scope.isCancelDisabled = () ->
    angular.equals master, $scope.form

  $scope.isSaveDisabled = () ->
    $scope.linkForm.$invalid or angular.equals master, $scope.form

  $scope.cancel()