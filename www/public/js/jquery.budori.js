(function(jQuery) {
	
	function _parseUrl(url){
		var params	= {}
			, splits	= url.split("?", 2)
			, splits, args, tmp, i, l;
		
		if( splits.length != 2){
			return params;
		}
		
		args = splits[1].split("&");
		
		for( i=0, l=args.length; i<l; i++ ){
			tmp = args[i].split("=", 2);
			
			params[tmp[0]] = tmp[1];
		}
		
		return params;
	}
	
	$.fn.extend({
		'enable': function( flag ){
			if( flag ){
				this.removeAttr("disabled");
			}else{
				this.attr("disabled", "disabled");
			}
			return this;
		},
		'setOption': function(obj){
			return this.each(function(){
				var sb = window.document.getElementById(this.id);
				var i = 0;
				$(this).children("option").remove();
				$.each(obj,function(key,value){
					sb.options[i++] = new Option(value,key);
				});
			});
			return this;
		}
	});
		
	$.extend({
		'getUrlVars': function( url ){
			if( !url ){url = document.location.href;}
			return _parseUrl(url);
		},
		'getScriptVars': function(){アプリの名前空間:
			
			var scripts		= document.getElementsByTagName( 'script' )
				, script;
			
			if( scripts.length == 0 ){
				return {};
			}
			script	= scripts[scripts.length-1].src;
			
			return _parseUrl(script);
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
			return $.accumlate(op,[[]],items.reverse());
		},
		'sigma': function( items ){
			var op = function(head, next){
				return head + next;
			}
			return $.accumlate(op,0,items);
		}
	});
})(jQuery);

