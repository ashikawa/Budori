{xml version="1.0" encoding=#CHARSET#}
{doctype}
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja">
<head>
{format indent="\t" stripline=true}
	<meta http-equiv="Content-Type" content="application/xhtml+xml; charset={#CHARSET#}" />
	<meta http-equiv="Content-Language" content="ja" />
	<meta http-equiv="Content-Style-Type" content="text/css" />
	<meta http-equiv="Content-Script-Type" content="text/javascript" />
	<meta http-equiv="imagetoolbar" content="no" />
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
	<meta name="author" content="{#AUTHER#}" />
	<meta name="robots" content="index,follow" />
	<meta name="viewport" content="width=480, user-scalable=no, maximum-scale=0.6667" />
	{head_meta}
	<link href="http://{$smarty.server.SERVER_NAME}" rel="start" />
	<link href="mailto:{#MAILTO#}" rev="made" />
	<link href="/style/reset.css" media="screen" rel="stylesheet" type="text/css" />
	<link href="/style/layout.css" media="screen" rel="stylesheet" type="text/css" />
	<link href="/style/common.css" media="screen" rel="stylesheet" type="text/css" />
	<link href="/style/portal.css" media="screen" rel="stylesheet" type="text/css" />
	<link href="/style/thickbox.css" media="screen" rel="stylesheet" type="text/css" />
	<link href="/favicon.ico" rel="shortcut icon" />
	{head_link}
	<script type="text/javascript" src="/js/jquery.js"></script>
	<script type="text/javascript" src="/js/jquery.budori.js"></script>
	<title>{head_title}</title>
</format>
</head>
<body class="{$controller|strtr:"_":"-"}">
<div id="wrapper">
	
	<div id="header">
		
		<h1><a href="/">{#TITLE#}</a></h1>
		
		{#SUB_TITLE#}<br />
		
	</div>
	
	<div id="pankuzu">
		{pankuzu}
	</div>
	
<div id="main">
{zend_layout}
</div>
	<div id="footer">
		<ul id="fNavi">
			<li class="line"><a href="/docs/index.html">docs</a></li>
			<li><a href="/info/phpinfo">phpinfo</a></li>
		</ul>
		<p class="copyright">{#COPYRIGHT#}</p>
	<!--//end div#footer--></div>
</div>

</body>
</html>
