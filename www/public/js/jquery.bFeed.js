/**
 * RSS を読み込んで li タグで出力する
 * @todo success 辺りのコードちょっと下手なので修正
 */
(function($) {
	$.fn.extend({
		feed: function(options){
			options = $.extend(
				$.fn.feed.defaults,
				options);
			
			this.each(function(){
				
				var self = this;
				
				$.ajax({
					'url': options.url,
					'type': 'get',
					'dataType': 'xml',
					'timeout': 30000,
					'success': function(xml){
						$(xml).find('item').each( function(i, val){
							if( i >= options.limit ) return false;
							$(self).append( options.render(i, val) );
						});
						options.afterRender();
					}
				});
			});
		}
	});
	
	$.fn.bFeed.defaults = {
		limit : 100,
		render: function(i,val){
			return '<li><a href="' +  $(val).find('link').text() + '" target="_blank">'
						+ $(val).find('title').text() + '</a></li>';
		},
		afterRender: function(){}
	};

})(jQuery);

