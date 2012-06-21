/**
 * jquery.accordion　と(たぶん)完全互換。
 * head部と、accordion本体にid必須。
 *
 * 2009/04/28 a.shigeru
 */
;(function($) {
	$.fn.extend({
		bAccordion: function( options, data ){
			
			var active = options.active ? options.active + ',' : '';
			
			$.each( this, function( key, val ){
				var json = JSON.parse( $.cookie( "bAccordion" ));
				if( json && json[val.id] ){
					$.each( json[val.id], function( k, v){
						active = active + "#" + v + ",";
					});
				}
			});
			
			options = $.extend(options,{ "active" : active } );
			
			this.accordion(options, data)
				.bind("change.ui-accordion", setCookie );
		}
	});
	
	
	function setCookie( event, ui ) {
		
		var active = JSON.parse( $.cookie( "bAccordion" )) || {};
		var id = this.id;
		
		active[id] = {};
		$.each( $(ui.options.active), function( key, val ){
			active[id][key] = val.id;
		});
		
		$.cookie( "bAccordion", JSON.stringify(active), { expires: 7, path:'/' } );
	};
})(jQuery);
