<? 
if(isset($_GET['data'])){
	$data = unserialize(base64_decode(urldecode($_GET['data'])));
	$Width = $data['Width'].'px';
	$Height = $data['Height'].'px';
} else {
	$Width = 'auto';
	$Height = 'auto';
}?>

@charset "utf-8";
/* CSS Document */

body, html {
	background-color: transparent;
	background-image: none;
	margin: 0px;
	padding: 0px;
	clear: both;
	float: none;
 width: <? echo $Width;
?>;
 height: <? echo $Height;
?>;
	border-top-style: none;
	border-right-style: none;
	border-bottom-style: none;
	border-left-style: none;
	overflow: hidden;
}
#BdBottom {
	background-image: url(/PhotoCP/Navigator/images/bd_Record_Top.png);
	background-repeat: no-repeat;
	margin: 0px;
	padding: 0px;
	clear: both;
	float: none;
	height: 3px;
	width: 745px;
}
#BdTop {
	background-image: url(/PhotoCP/Navigator/images/bd_Record_Top.png);
	background-repeat: no-repeat;
	background-position: left top;
	margin: 0px;
	padding: 0px;
	clear: both;
	float: none;
	height: 3px;
	width: 746px;
}
#Container {
	background-image: url(/PhotoCP/Navigator/images/bg_RecordTable_Black.png);
	background-repeat: repeat-y;
	background-position: left top;
	margin: 0px;
	clear: both;
	float: none;
	height: 994px;
	width: 739px;
	padding-top: 0px;
	padding-right: 3px;
	padding-bottom: 0px;
	padding-left: 3px;
}
#Container #Folders {
	margin: 0px;
	padding: 0px;
	clear: none;
	float: left;
	height: 228px;
	width: 262px;
	border-right-width: 1px;
	border-bottom-width: 1px;
	border-top-style: none;
	border-right-style: solid;
	border-bottom-style: solid;
	border-left-style: none;
	border-right-color: #4b4b4b;
	border-bottom-color: #4b4b4b;
}
#Container #Folders h3 {
	margin: 0px;
	padding: 0px;
	clear: both;
	float: none;
	height: 35px;
	width: 262px;
}
#Container  #Folders  #FoldList {
	margin: 0px;
	padding: 0px;
	clear: both;
	float: none;
	height: 193px;
	width: 262px;
	overflow: auto;
}
#Container #Folders ul {
	list-style-type: none;
	margin: 0px;
	padding: 0px;
	clear: both;
	float: none;
	width: 242px;
	display: block;
}
#Container #Folders ul li {
	margin: 0px;
	padding: 0px;
	clear: both;
	float: none;
	width: 242px;
	display: block;
}
#Container #Folders ul li a {
	display: block;
	margin: 0px;
	padding: 0px;
	clear: both;
	float: none;
	height: 22px;
	width: 242px;
	border-top-width: 1px;
	border-bottom-width: 1px;
	border-top-style: solid;
	border-right-style: none;
	border-bottom-style: solid;
	border-left-style: none;
	border-top-color: #000000;
	border-bottom-color: #666666;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	color: #FFFFFF;
	font-weight: bold;
	text-decoration: none;
	background-image: url(/PhotoCP/Navigator/images/bg_FolderNav.gif);
	background-repeat: repeat-x;
	background-position: left top;
}
#Container #Folders ul li a:link {
	background-position: left top;
}
#Container #Folders ul li a:visited {
	background-position: left top;
}
#Container #Folders ul li a:hover {
	background-position: left bottom;
}
#Container #Folders ul li a:active {
	background-position: left top;
}

#Container #Folders ul li a div {
	display: block;
	clear: both;
	float: none;
	background-image: url(/PhotoCP/Navigator/images/fld_style_1.png);
	background-repeat: no-repeat;
	padding-top: 4px;
	padding-right: 0px;
	padding-bottom: 0px;
	padding-left: 50px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	margin-left: 5px;
	background-position: left top;
	height: 18px;
	cursor: pointer;
}
#Container #Folders ul #C a div {
	background-position: left -44px;
}
#Container #Folders ul #O a div {
	background-position: left -88px;
}
#Container #Folders ul #N a div {
	background-position: left top;
}
#Container #Folders ul #C .NavSel div{
	background-position: left -66px;
}
#Container #Folders ul #O .NavSel div{
	background-position: left -110px;
}
#Container #Folders ul #N .NavSel div{
	background-position: left -22px;
}
#Container #Folders ul #C ul, #Container #Folders ul #N ul {
	display:none;
}
#Container #Folders ul li ul li a {
	height: 22px;
	padding-left: 10px;
	width: 232px;
}
#Container #Folders ul li ul li a div {
	background-image: url(/PhotoCP/Navigator/images/fld_style_2.png);
	height: 18px;
}
#Container #Folders ul li ul #C a div {
	background-position: left -44px;
}
#Container #Folders ul li ul #O a div {
	background-position: left -88px;
}
#Container #Folders ul li ul #N a div {
	background-position: left top;
}
#Container #Folders ul li ul #C .NavSel div{
	background-position: left -66px;
}
#Container #Folders ul li ul #O .NavSel div{
	background-position: left -110px;
}
#Container #Folders ul li ul #N .NavSel div{
	background-position: left -22px;
}
#Container #Folders ul li ul #C ul, #Container #Folders ul li ul #N ul {
	display:none;
}
#Container #Folders ul li ul li ul li a {
	height: 22px;
	padding-left: 20px;
	width: 222px;
}
#Container #Folders ul li ul li ul li a div {
	height: 18px;
}
#Container #Folders ul li ul li ul li ul li a {
	height: 22px;
	padding-left: 30px;
	width: 212px;
}
#Container #Folders ul li ul li ul li ul li a div {
	height: 18px;
} 