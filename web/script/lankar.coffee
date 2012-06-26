# Coffee script version

angular.module 'lankar', [], ($compileProvider, $routeProvider, $locationProvider) ->
  $compileProvider.directive 'compile', ($compile) ->
    converter = new Markdown.getSanitizingConverter()
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
  $http.get('lankar.php/links/' + $routeParams.page).success (data) ->
    $scope.links = data.links
    $scope.total = parseInt data.total
    $scope.page = parseInt $routeParams.page
    $scope.isfirst = $scope.page == 1
    $scope.islast = $scope.page == $scope.total

this.addCtrl = ($scope, $http, $routeParams, $location) ->
  url = decodeURIComponent($location.search().url || '')
  title = decodeURIComponent($location.search().title || '')
  desc = ''
  desc = '###' + title + '\n\n[' + title + '](' + url + ')' if url != '' and title != ''
  master = {
    'url': url
    'title': title
    'description': desc
    'tags': ''
  }
  $scope.$watch 'form.tags', () ->
    labels = $scope.form.tags.split /[ ,]+/
    tags = []
    angular.forEach labels, (value) ->
      tags.push value if !(value in tags) && value != ''
    $scope.form.labels = tags

  $scope.cancel = () ->
    $scope.form = angular.copy master

  $scope.save = () ->
    $http({
      'method': 'POST'
      'url': 'lankar.php/link'
      'data': 'url=' + encodeURIComponent($scope.form.url) +
        '&title=' + encodeURIComponent($scope.form.title) +
        '&description=' + encodeURIComponent($scope.form.description) +
        '&tags=' + encodeURIComponent($scope.form.labels)
      'headers': {
        'Content-Type': 'application/x-www-form-urlencoded'
      }
    }).success (data, status) ->
      $location.search(null) and $location.path '/links/1'
    .error (data, status) ->
      alert('fail')
    master = $scope.form
    $scope.cancel()

  $scope.isCancelDisabled = () ->
    angular.equals master, $scope.form

  $scope.isSaveDisabled = () ->
    $scope.linkForm.$invalid or angular.equals master, $scope.form

  $scope.cancel()