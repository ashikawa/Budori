/*global jQuery*/
(function ($) {
	$.pluginName = function (element, options) {
		
		var defaults = {
				foo: 'bar',
				onFoo: function () {}
			},
			plugin		= this,
			$element	= $(element);
		
		plugin.settings = {};
		
		
		function foo_private_method() {
			// code goes here
		}
		
		plugin.init = function () {
			plugin.settings = $.extend({}, defaults, options);
			// code goes here
		};
		
		plugin.foo_public_method = function () {
			// code goes here
		};
		
		plugin.init();
	};
	
	$.fn.pluginName = function (options) {
		return this.each(function () {
			if (undefined === $(this).data('pluginName')) {
				var plugin = new $.pluginName(this, options);
				$(this).data('pluginName', plugin);
			}
		});
	};
}(jQuery));

/*
$( function() {
	   // attach the plugin to an element
	   $('#hello').pluginName({'foo': 'bar'});
	   // call a public method
	   $('#hello').data('pluginName').foo_public_method();
	   // get the value of a property
	   $('#hello').data('pluginName').settings.foo;
});
*/