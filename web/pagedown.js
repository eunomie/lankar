// based on http://blog.angularjs.org/2012/05/custom-components-part-1.html
angular.module('lankar', []).directive('markdown', function() {
	var converter = new Markdown.Converter();
	return {
		restrict: 'E',
		link: function(scope, element, attrs) {
			var htmlText = converter.makeHtml(element.text());
			element.html(htmlText);
		}
	};
});