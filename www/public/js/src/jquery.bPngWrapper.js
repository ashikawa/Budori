/**
 * 画像の上に透過pngを重ねるためのプラグイン。
 * @todo サンプルを作る
 * usage 
 * 
 * $("img.spijo-container").pngwrapper({
 *   filterImage: './filter.png'
 * });
 *
 * img.spijo-container {
 *  border: 3px solid #F1E8DC;
 * }
 * div.pngwrapper{
 * <!-- 画像サイズ + border, paddingの合計値 -->
 *  width: 65px;
 *  height: 65px;
 *  }
 * div.pngwrapper .img-floater{
 *  border: 1px solid #E6DAC8;
 *  }
 */
/*global jQuery*/
(function ($) {

	$.fn.extend({
		pngwrapper: function (options) {
			
			options = $.extend(options, jQuery.fn.pngwrapper.defaults);
			
			var filterImage = options.filterImage,
				wrapperClass = options.wrapperClass;
			
			return this.each(function () {
				
				$(this).wrap('<div class="' + wrapperClass + '"></div>')
					.after('<div class="png-floater" style="position: absolute;">'
						+ '<img class="wrapper-img" src="'  + filterImage + '" /></div>')
					.wrap('<div class="img-floater" style="position: absolute;"></div>');
			});
		}
	});
	
	jQuery.fn.pngwrapper.defaults = {
		'wrapperClass': "pngwrapper"
	};
}(jQuery));
