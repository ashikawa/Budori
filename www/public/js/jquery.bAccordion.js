/**
 * jquery.accordion　と(たぶん)完全互換。
 * head部と、accordion本体にid必須。
 *
 * 2009/04/28 a.shigeru
 */
;(function(jQuery) {
	jQuery.fn.extend({
		bAccordion: function( options, data ){
			
			var active = options.active ? options.active + ',' : '';
			
			jQuery.each( this, function( key, val ){
				var json = jQuery.parseJSON( jQuery.cookie( "bAccordion" ));
				if( json && json[val.id] ){
					jQuery.each( json[val.id], function( k, v){
						active = active + "#" + v + ",";
					});
				}
			});
			
			options = jQuery.extend(options,{ "active" : active } );
			
			this.accordion(options, data)
				.bind("change.ui-accordion", setCookie );
		}
	});
	
	
	function setCookie( event, ui ) {
		
		var active = jQuery.parseJSON( jQuery.cookie( "bAccordion" )) || {};
		var id = this.id;
		
		active[id] = {};
		jQuery.each( jQuery(ui.options.active), function( key, val ){
			active[id][key] = val.id;
		});
		
		jQuery.cookie( "bAccordion", jQuery.toJSON(active), { expires: 7, path:'/' } );
	};
})(jQuery);
