<? $count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-1; $r_path = ""; for($n=0;$n<$count;$n++)$r_path .= "../";
ob_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta name="robots" content="index,follow" />
<meta name="keywords" content=""/>
<meta name="language" content="english"/>
<meta name="author" content="Pro Image Software" />
<meta name="copyright" content="2007" />
<meta name="reply-to" content="support@proimagesoftware.com" />
<meta name="document-rights" content="Copyrighted Work" />
<meta name="document-type" content="Web Page" />
<meta name="document-rating" content="General" />
<meta name="document-distribution" content="Global" />
<title>ProImageSoftware</title>
<? $HTML = ob_get_contents(); ob_end_clean();
ob_start(); ?>
<style>
body, html {
	margin: 0px;
	padding: 0px;
	height: auto;
	width: auto;
	background-color: #000000;
}
a {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	font-weight: normal;
	font-variant: normal;
	text-transform: none;
	font-style: normal;
	line-height: normal;
	text-indent: 10px;
	text-align: justify;
	color: #da7057;
}
h1 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 22px;
	font-weight: normal;
	color: #b7624e;
	font-style: normal;
	line-height: normal;
	font-variant: normal;
	text-transform: none;
	text-decoration: none;
}
h2 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	font-weight: normal;
	color: #b7624e;
	font-style: normal;
	line-height: normal;
	font-variant: normal;
	text-transform: none;
	text-decoration: none;
}
h3 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	font-weight: normal;
	color: #b7624e;
	font-style: normal;
	line-height: normal;
	font-variant: normal;
	text-transform: none;
	text-decoration: none;
	margin: 0px;
	padding: 0px;
}
h4 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 14px;
	font-weight: bold;
	color: #FFFFFF;
	font-style: normal;
	line-height: normal;
	font-variant: normal;
	text-transform: none;
	text-decoration: none;
	margin: 0px;
	padding: 0px;
}
input, select, textarea {
	border-top-width: 1px;
	border-right-width: 1px;
	border-bottom-width: 1px;
	border-left-width: 1px;
	border-top-style: solid;
	border-right-style: solid;
	border-bottom-style: solid;
	border-left-style: solid;
	font-size: 11px;
	color: #686868;
	font-family: Arial, Helvetica, sans-serif;
}
th {
	color: #ce6b54;
	font-weight: normal;
}
p {
	color: #FFFFFF;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	font-weight: normal;
	font-variant: normal;
	text-transform: none;
	font-style: normal;
	line-height: normal;
	text-decoration: none;
	text-indent: 10px;
	text-align: justify;
}
a.Footer_Nav:link {
	color: #FFFFFF;
}
a.Footer_Nav:visited {
	color: #FFFFFF;
}
a.Footer_Nav:hover {
	color:#FFFFFF;
}
a.Footer_Nav:active {
	color: #FFFFFF;
}
.bio_photo {
	border: 5px solid #cccccc;
	padding: 0px;
	margin-top: 0px;
	margin-right: 25px;
	margin-bottom: 25px;
	margin-left: 0px;
}
.smallOrangeText {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	font-style: normal;
	font-weight: normal;
	color: #ce6b54;
}
hr.Orange {
	color:#ce6b54;
	background-color:#1ab7ea;
}
.no_border {
	border-top-style: none;
	border-right-style: none;
	border-bottom-style: none;
	border-left-style: none;
}
#Container {
	padding: 0px;
	margin-top: 0px;
	margin-right: auto;
	margin-bottom: 0px;
	margin-left: auto;
	width: 1000px;
	clear: both;
}
#Content {
	background-color: #292929;
	width: 997px;
	clear: right;
	height: auto;
	min-height:331px;
	margin: 0px;
	padding: 0px;
	border-top-width: 1px;
	border-right-width: 1px;
	border-bottom-width: 1px;
	border-left-width: 1px;
	border-top-style: none;
	border-right-style: solid;
	border-bottom-style: solid;
	border-left-style: solid;
	border-top-color: #999999;
	border-right-color: #999999;
	border-bottom-color: #999999;
	border-left-color: #999999;
}
#Footer {
	height: 60px;
	width: 980px;
	margin-top: 0px;
	margin-right: auto;
	margin-bottom: 0px;
	margin-left: auto;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 11px;
	color: #FFFFFF;
	font-weight: bold;
	padding: 10px;
 background-image: url(http://<? echo $_SERVER['HTTP_HOST']; ?>/images/bg_footer.jpg);
	background-repeat: repeat-x;
	background-position: left top;
	clear: both;
}
#Footer p {
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 10px;
	margin-left: 0px;
	font-size: 11px;
	clear:both;
}
#Logo {
	border: 1px solid #606060;
	margin: 0px;
	padding: 0px;
	clear: both;
	width: 998px;
	height: 105px;
 background-image: url(http://<? echo $_SERVER['HTTP_HOST'];
?>/images/Logo_email.jpg);
	background-repeat: no-repeat;
	background-position: left top;
}
#Main_Content {
	background-color: #292929;
	width: 998px;
	clear: both;
	margin: 0px;
	padding: 0px;
	border-right-width: 1px;
	border-bottom-width: 1px;
	border-left-width: 1px;
	border-right-style: solid;
	border-bottom-style: solid;
	border-left-style: solid;
	border-right-color: #606060;
	border-bottom-color: #606060;
	border-left-color: #606060;
}
#Main_Content table {
	clear: both;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	color: #FFFFFF;
	text-decoration: none;
}
#Main_Text_Long {
	margin: 0px;
	padding: 10px;
	clear: both;
	height:auto;
	width: 978px;
 background-image: url(http://<? echo $_SERVER['HTTP_HOST'];
?>/images/bg_large.jpg);
	background-repeat: repeat-y;
	background-position: right top;
	background-color: #494949;
}
</style>
<? $CSS = ob_get_contents(); ob_end_clean();
ob_start(); ?>
</head>
<body>
<div id="Container">
  <div id="Logo"></div>
  <div id="Main_Content"><img src="http://<? echo $_SERVER['HTTP_HOST']; ?>/images/spacer.gif" width="1" height="1" /><? echo $email_text; ?></div>
</div>
<div id="Footer">
  <div style="float:left">
    <p> Photo Express ProImageSoftware </p>
  </div>
  <div style="float:left; margin-left:30px;">
    <p><a href="mailto:support@proimagesoftware.com" class="Footer_Nav">support@proimagesoftware.com</a></p>
  </div>
</div>
</body>
</html>
<? $HTML .= ob_get_contents(); ob_end_clean();
require_once($r_path.'scripts/emogrifier.php');
$InlineHTML = new Emogrifier();
$InlineHTML -> setHTML($HTML);
$InlineHTML -> setCSS($CSS);
echo $InlineHTML -> emogrify(); ?>
