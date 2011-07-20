;(function($) {
	$.fn.extend({
		bThumbnail: function(options){
			options = $.extend(
				$.fn.bThumbnail.defaults,
				options);
			
			this.each( function(){
				$(this).wrap('<div class="'+options.appendclass+'"></div>');
				
				var defImage = $(this);
				
				var imgPreloader = new Image();
				
				var width	= options.width;
				var height	= options.height;
				
				var unit	= options.unit;
				
				imgPreloader.onload = function(){
					var oWidth	= imgPreloader.width;
					var oHeight = imgPreloader.height;
					
					var position;
					
					if( $.isFunction(options.position) ){
						var arg = {'width':width,'height':height,
									'oWidth':oWidth,'oHeight':oHeight};
						
						position	= options.position(arg);
					}else{
						position	= options.position;
					}
					
					var x = position.x;
					var y = position.y;
					
					defImage.parent('div.'+options.appendclass).css({
							'position':'relative',
							'height':height+unit,
							'width':width+unit
						});
					defImage.css({
							'position':'absolute',
							'top':(-1*y)+unit,
							'left':(-1*x)+unit,
							'clip':'rect('+y+unit+' '+(x+width)+unit+' '+(y+height)+unit+' '+x+unit+')'
						});
					
					defImage.trigger('postRender');
				};
				imgPreloader.src = $(this).attr('src');
			});
		}
	});
	
	$.fn.bThumbnail.position = {
		random: function(opt)
		{
			function random(original,fil){
				if(original > fil){
					return Math.ceil(Math.random()*(original-fil));
				}else{
					return ( original - fil ) /2;
				}
			}
			
			return {'x': random(opt.oWidth,opt.width),
					'y': random(opt.oHeight,opt.height)};
		},
		center: function(opt)
		{
			function center(original,fil){
				return ( original - fil ) /2;
			}
			
			return {'x': center(opt.oWidth,opt.width),
					'y': center(opt.oHeight,opt.height)};
		}
	};
	
	$.fn.bThumbnail.defaults = {
		'width':100,
		'height':100,
		'unit':'px',
		'appendclass':'b-thumbnails',
		'position': $.fn.bThumbnail.position.center
	};
})(jQuery);

