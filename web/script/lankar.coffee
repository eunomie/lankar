# **Länkar** is a really nice links manager.
#
# It helps to collect, comment (in [Markdown](http://daringfireball.net/projects/markdown/syntax)), categorize
# and share links.
# 
# Länkar is written using [AngularJS](http://www.angularjs.org) and this is the main
# source file, written in [CoffeeScript](http://coffeescript.org/).

#### Angular module

# Declare the lankar module
angular.module 'lankar', [], ($compileProvider, $routeProvider, $locationProvider) ->
  # Add a new directive to compile content written in markdown
  # to an html content.
  #
  #     <div class="previewmarkdown uneditable-input" compile="form.description"></div>
  #
  # will fill the div content by the html produced by compiling `form.description` data
  # using [PageDown](http://code.google.com/p/pagedown/wiki/PageDown)
  #
  # This is used to render the description of links and also to have
  # live preview of markdown input.
  $compileProvider.directive 'compile', ($compile) ->
    # Use the `SanitizingConverter` to clean entry, remove html, etc.
    converter = new Markdown.getSanitizingConverter()
    (scope, element, attrs) ->
      scope.$watch (scope) ->
        converter.makeHtml(scope.$eval(attrs.compile))
      , (value) ->
        element.html value
        $compile(element.contents())(scope)
  # Defines routes :
  $routeProvider
    # * to links list, with pagination
    .when('/links/:page', { templateUrl: 'partials/links.html', controller: this.LinksCtrl})
    # * to a form that allow to add a link
    .when('/add', { templateUrl: 'partials/form.html', controller: this.addCtrl})
    # * if no routes, redirect to the first page of links
    .otherwise({redirectTo: '/links/1'})


#### Links controller

# This controller is used to render the links list with
# pagination.
this.LinksCtrl = ($scope, $http, $routeParams) ->
  $http.get('lankar.php/links/' + $routeParams.page).success (data) ->
    $scope.links = data.links
    $scope.total = parseInt data.total
    $scope.page = parseInt $routeParams.page
    # disable buttons when at begin or end of the links
    $scope.isfirst = $scope.page == 1
    $scope.islast = $scope.page == $scope.total

#### Link adding controller

# This controller is used to add a link.
this.addCtrl = ($scope, $http, $routeParams, $location) ->
  # Get parameters in the url (if called from bookmarklet)
  #
  # Get the url of the link to add
  url = decodeURIComponent($location.search().url || '')
  # Get the title of the link to add
  title = decodeURIComponent($location.search().title || '')
  desc = ''
  # and construct a default description with title and link in markdown
  desc = '###' + title + '\n\n[' + title + '](' + url + ')' if url != '' and title != ''
  # Store the default configuration. This allow to track changes and enable/disable buttons.
  master = {
    'url': url
    'title': title
    'description': desc
    'tags': ''
  }
  # Each link can has some tags. Removes duplicated entries and store
  # single labels to be previewed.
  $scope.$watch 'form.tags', () ->
    labels = $scope.form.tags.split /[ ,]+/
    tags = []
    angular.forEach labels, (value) ->
      tags.push value if !(value in tags) && value != ''
    $scope.form.labels = tags

  # Cancel form by setting all fields to default value.
  $scope.cancel = () ->
    $scope.form = angular.copy master

  # Save the link, send datas to the server and redirect to the links list
  # if all is ok.
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