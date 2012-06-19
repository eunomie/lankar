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

this.LinksCtrl = ($scope) ->
  $scope.links = [
    {
      desc: 
        'Ceci **est** simplement mon blog personnel. Je le met à jour de manière assez irrégulière, même si ça commence à redevenir suffisamment fréquent.' +
          '\n\n' +
          'Pour info le premier billet date de _septembre 2005_' +
          '\n\n' +
          '> Test de citation'
      url: 'http://www.winsos.net/~yves'
      labels: ['next', 'perso']
      date: '15 juin 2012'
      hash: '-BymHg'
    }, {
      desc: 'Mon compte twitter. Pas grand chose dessus, je m\'en sert pas mal pour suivre des gens. Disons que je m\'y met petit à petit.'
      url: 'http://twitter.com/_crev_'
      labels: ['next', 'perso', 'twitter']
      date: '15 juin 2012'
      hash:'1dIdrA'
    }, {
      desc: 'Générateur de documentation pour php, basé sur Silex, twig, ...'
      url: 'http://fabien.potencier.org/article/63/sami-yet-another-php-api-documentation-generator'
      labels: ['next', 'devel', 'php', 'documentation'],
      date: '19 juin 2012',
      hash: 'g7jbew'
    }
  ]
  $scope.pagenumber = 3
  $scope.linksnumber = 12
