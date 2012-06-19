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
      para: [
        'Ceci **est** simplement mon blog personnel. Je le met à jour de manière assez irrégulière, même si ça commence à redevenir suffisamment fréquent.' +
          '\n\n' +
          'Pour info le premier billet date de _septembre 2005_' +
          '\n\n' +
          '> Test de citation'
      ]
      url: 'http://www.winsos.net/~yves'
      labels: ['next', 'perso']
      date: '15 juin 2012'
      hash: '-BymHg'
    }, {
      para: [
        'Mon compte twitter. Pas grand chose dessus, je m\'en sert pas mal pour suivre des gens. Disons que je m\'y met petit à petit.'
      ]
      url: 'http://twitter.com/_crev_'
      labels: ['next', 'perso', 'twitter']
      date: '15 juin 2012'
      hash:'1dIdrA'
    }
  ]
