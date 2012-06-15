angular.module('lankar', [], function($compileProvider) {
	$compileProvider.directive('markdown', function($compile) {
		var converter = new Markdown.Converter();
		return {
			restrict: 'E',
			link: function(scope, element, attrs) {
//				$compile(element.contents())(scope);
			}
		};
	});
});

function LinksCtrl($scope) {
	$scope.links = [
		{
			para:[
				'Ceci **est** simplement mon blog personnel. Je le met à jour de manière assez irrégulière, même si ça commence à redevenir suffisamment fréquent.',
				'Pour info le premier billet date de septembre 2005'
			],
			url: 'http://www.winsos.net/~yves',
			labels: ['next', 'perso'],
			date: '15 juin 2012',
			hash: '-BymHg'
		}, {
			para: [
				'Mon compte twitter. Pas grand chose dessus, je m\'en sert pas mal pour suivre des gens. Disons que je m\'y met petit à petit.'
			],
			url: 'http://twitter.com/_crev_',
			labels: ['next', 'perso', 'twitter'],
			date: '15 juin 2012',
			hash:'1dIdrA'
		}
	];
}
