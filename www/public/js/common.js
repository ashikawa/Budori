if ( !window.console ){
	var console = {};
	
	if( typeof opera != null){
		console.log = function(obj){
			opera.postError(obj);
		};
	}else{
		//IEは無視で。
	}
}

if( !Array.indexOf ){
	Array.prototype.indexOf = function(target){
		for(var i = 0; i < this.length; i++){
			if(this[i] === target){ 
				return i;
			}
		}
		return -1;
	};
}
$(function() {

	$('textarea.resizeable').TextAreaResizer();
	$('input[@type="checkbox"].field').createCheckboxRange();
});
