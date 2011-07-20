;(function(jQuery) {
	jQuery.fn.extend({
		enable: function( flag ){
			if( flag ){
				this.removeAttr("disabled");
			}else{
				this.attr("disabled", "disabled");
			}
			return this;
		},
		setOption: function(obj){
			return this.each(function(){
				var sb = window.document.getElementById(this.id);
				var i = 0;
				jQuery(this).children("option").remove();
				jQuery.each(obj,function(key,value){
					sb.options[i++] = new Option(value,key);
				});
			});
			return this;
		}
	});
	jQuery.extend({
		'bAjax': function( options ) {
			jQuery.ajax(jQuery.extend({
				type: 'get',
				dataType: 'json',
//				beforeSend: function(xhr){
//					xhr.overrideMimeType("text/javascript;charset=UTF-8");
//				},
				timeout: 30000
			}, options ));
		},
		'getUrlVars': function( url ){
			if( !url ){url = document.location.href;}
			var query = url.replace(/^[^�?]+�??/,'');
			
			var Params = {};
			if ( ! query ) {return Params;}// return empty object
			var Pairs = query.split(/[;&?]/);
			for ( var i = 0; i < Pairs.length; i++ ) {
				var KeyVal = Pairs[i].split('=');
				if ( ! KeyVal || KeyVal.length > 2 ) {continue;}
				
				if(KeyVal.length == 1){
					var key = unescape( KeyVal[0] );
					Params[key] = null;
				}else{
					var key = unescape( KeyVal[0] );
					var val = unescape( KeyVal[1] );
					val = val.replace(/�+/g, ' ');
					Params[key] = val;
				}
			}
			return Params;
		},
		'addlink':function(str){
			return str.replace(
							/((ftp|https?):\/\/([-\w\.]+)+(:\d+)?(\/([\w/_\.]*(\?\S+)?)?)?)/gm,
							'<a href="$1" target="_blank">$1</a>'
						);
		},
		'accumlate':function (op, initial, sequence){
			if(sequence.length == 0){
				return initial;
			}
			return op(sequence.shift(), arguments.callee(op, initial, sequence) );
		},
		'makeProduct':function( items ){
			var op = function(head, arr){
				
				var ret = [];
				if( head.length != 0 ){
					for(var i=0; i<arr.length; i++){
						for(var j=0; j<head.length; j++){
							ret.push( arr[i].concat(head[j]));
						}
					}
					return ret;
				}
				return arr;
			}
			return jQuery.accumlate(op,[[]],items.reverse());
		},
		'sigma': function( items ){
			var op = function(head, next){
				return head + next;
			}
			return $.accumlate(op,0,items);
		}
		
	});
})(jQuery);

