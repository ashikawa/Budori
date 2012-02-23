;(function($) {
	$.fn.extend({
	
		'bTwitter': function( options ) {
			options = $.extend(
				$.fn.bTwitter.defaults,
				options );
			
			var buildString = "";
			
			for(var i=0;i<options.users.length;i++)
			{
				if(i!=0) buildString+='+OR+';
				buildString+='from:'+options.users[i];
			}
			
			var container = $(this);
			
			container.data('init',options);
			
			$.ajax({
				'url':'http://search.twitter.com/search.json',
				'dataType':'jsonp',
				'data':{
						'q':buildString,
						'rpp':50
					},
				'success': function(ob){
						container.data('response', ob);
						
						container.html('');
						$.each( ob.results, function(k,v){
							container.append(options.createTweet(k,v));
						});
						container.slideDown('slow');
					}
			});
		}
	});
	
	$.fn.bTwitter.defaults = {
		users:[],
		createTweet: function(key,result){
			var str = '<div class="tweet">'
					+ '<div class="txt">'+this.formatString(result.text)+'</div>'
					+ '<div class="time">'+this.relativeTime(result.created_at)+'</div>'
					+ '</div>';
			return str;
		},
		formatString: function(str){
			str=' '+str;
			str = $.addlink(str);
			str = str.replace(/([^\w])\@([\w\-]+)/gm,'$1<a href="http://twitter.com/$2" target="_blank">@$2</a>');
			str = str.replace(/([^\w])\#([\w\-]+)/gm,'$1<a href="http://twitter.com/search?q=%23$2" target="_blank">#$2</a>');
			return str;
		},
		relativeTime: function(pastTime){
			var origStamp = Date.parse(pastTime);
			var curDate = new Date();
			var currentStamp = curDate.getTime();
			
			var difference = parseInt((currentStamp - origStamp)/1000);
			
			if(difference < 0) return false;
			
			if(difference <= 5)				return "Just now";
			if(difference <= 20)			return "Seconds ago";
			if(difference <= 60)			return "A minute ago";
			if(difference < 3600)			return parseInt(difference/60)+" minutes ago";
			if(difference <= 1.5*3600) 		return "One hour ago";
			if(difference < 23.5*3600)		return Math.round(difference/3600)+" hours ago";
			if(difference < 1.5*24*3600)	return "One day ago";
			
			var dateArr = pastTime.split(' ');
			return dateArr[4].replace(/\:\d+$/,'')+' '+dateArr[2]+' '+dateArr[1]+(dateArr[3]!=curDate.getFullYear()?' '+dateArr[3]:'');
		}
	};
})(jQuery);
