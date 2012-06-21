/**
 * GoogleAnalytics タグの外部化
 */
(function () {
	
	var gaq,
		ga,
		s;
	
	gaq = window._gaq || [];
	gaq.push(['_setAccount', 'UA-XXXXXXXX-1']);
	gaq.push(['_trackPageview']);
	
	window._gaq = gaq;
	
	ga = document.createElement('script');
	ga.type = 'text/javascript';
	ga.async = true;
	ga.src = ('https:' === document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
	
	s = document.getElementsByTagName('script')[0];
	s.parentNode.insertBefore(ga, s);
	
}());
