<? if(!isset($r_path)) { $count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-1; $r_path = ""; for($n=0;$n<$count;$n++)$r_path .= "../"; }
ob_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><? echo $title; ?></title>
<? $HTML = ob_get_contents(); ob_end_clean();
ob_start(); ?>
<style type="text/css">
<!--
body, html {
	background-color: #292929;
	margin: 0px;
	padding: 0px;
}
#Content {
	background-color: #000000;
	padding: 0px;
	clear: both;
	float: none;
	width: 700px;
	margin-top: 0px;
	margin-right: auto;
	margin-bottom: 0px;
	margin-left: auto;
	border: 1px solid #575757;
}
#Content #Footer {
	text-align: center;
	margin: 0px;
	float: none;
	height: 17px;
	width: 700px;
	padding-top: 3px;
	padding-right: 0px;
	padding-bottom: 0px;
	padding-left: 0px;
	display: block;
}
#Content #Header {
	margin: 0px;
	padding: 0px;
	clear: both;
	float: none;
	height: auto;
	width: 700px;
	border-top-width: 1px;
	border-right-width: 1px;
	border-bottom-width: 1px;
	border-left-width: 1px;
	border-top-style: none;
	border-right-style: none;
	border-bottom-style: solid;
	border-left-style: none;
	border-top-color: #575757;
	border-right-color: #575757;
	border-bottom-color: #575757;
	border-left-color: #575757;
}
#Content #Text {
	padding: 5px;
	width: 690px;
	margin: 0px;
	clear: both;
	float: none;
	border-top-width: 1px;
	border-right-width: 1px;
	border-bottom-width: 1px;
	border-left-width: 1px;
	border-top-style: none;
	border-right-style: none;
	border-bottom-style: solid;
	border-left-style: none;
	border-top-color: #575757;
	border-right-color: #575757;
	border-bottom-color: #575757;
	border-left-color: #575757;
}
#Content a {
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
#Content h1 {
	color:#da7057;
	font-size:22px;
	font:Geneva, Arial, Helvetica, sans-serif;
}
#Content #Text img {
	border: 5px double #575757;
}
#Content p, #Content span {
	color: #FFFFFF;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	font-weight: normal;
	font-variant: normal;
	text-transform: none;
	font-style: normal;
}
-->
</style>
<? $CSS = ob_get_contents(); ob_end_clean();
ob_start(); ?>
</head>
<body>
<div id="Content">
  <div id="Header"><img src="http://www.proimagesoftware.com/<? if(isset($BioImage) && $BioImage !== false){ echo $BioImage; } else { ?>images/hdr_email_logo.jpg<? } ?>" border="0" width="<? if(isset($BioImage) && $BioImage !== false){ echo $BioWidth; } else { echo "700"; } ?>" height="<? if(isset($BioImage) && $BioImage !== false){ echo $BioHeight; } else { echo "186"; } ?>" /> </div>
  <div id="Text"><? echo $text; ?> <br clear="all" />
  </div>
  <div id="Footer">
    <p>&copy; 2007 Photo Express<br clear="all" />
    </p>
  </div>
</div>
<br clear="all" />
<img src="http://www.proimagesoftware.com/images/spacer.gif" border="0" height="1" width="1" style="border:none" /> <br clear="all" />
</body>
</html>
<? $HTML .= ob_get_contents(); ob_end_clean();
require_once($r_path.'scripts/emogrifier.php');
$InlineHTML = new Emogrifier();
$InlineHTML -> setHTML($HTML);
$InlineHTML -> setCSS($CSS);
$HTML = $InlineHTML -> emogrify();

if(function_exists('tidy_parse_string')){
	$config = array('indent' => TRUE,
									'output-xhtml' => TRUE,
									'wrap' => 200);
	$tidy = tidy_parse_string($HTML, $config, 'UTF8');
	$tidy->cleanRepair();
	echo $tidy;
} else {
	echo $HTML;
}
 ?>
