<?
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-1;
$r_path = "";
for($n=0;$n<$count;$n++)$r_path .= "../";
define ("Allow Scripts", true);
require_once($r_path.'scripts/fnct_xml_parser.php');
$data = file_get_contents("php://input");

//$data = '<images code="dcw1flyinghorse"><image folder="photographers/flyinghorse/dcw1/6 yr olds/" zoom="81T_2888.JPG" small="81T_2888.JPG" tiny="81T_2888.JPG" id="F634964" groupid="0">81T_2888.JPG</image><image folder="photographers/flyinghorse/dcw1/6 yr olds/" zoom="81T_2889.JPG" small="81T_2889.JPG" tiny="81T_2889.JPG" id="F634966" groupid="0">81T_2889.JPG</image><image folder="photographers/flyinghorse/dcw1/6 yr olds/" zoom="81T_2890.JPG" small="81T_2890.JPG" tiny="81T_2890.JPG" id="F634967" groupid="0">81T_2890.JPG</image><image folder="photographers/flyinghorse/dcw1/6 yr olds/" zoom="81T_2891.JPG" small="81T_2891.JPG" tiny="81T_2891.JPG" id="F634968" groupid="0">81T_2891.JPG</image><image folder="photographers/flyinghorse/dcw1/6 yr olds/" zoom="81T_2895.JPG" small="81T_2895.JPG" tiny="81T_2895.JPG" id="F634972" groupid="0">81T_2895.JPG</image><image folder="photographers/flyinghorse/dcw1/6 yr olds/" zoom="81T_2894.JPG" small="81T_2894.JPG" tiny="81T_2894.JPG" id="F634971" groupid="0">81T_2894.JPG</image><image>Done</image></images>';
$data2 = '<?xml version="1.0" encoding="iso-8859-1"?>'.$data;
$parser = new XMLParser($data2, 'raw', 1);
$tree = $parser->getTree();
$code = $tree['IMAGES']['ATTRIBUTES']['CODE'];
$index = strpos($data,">");
$data = substr($data,($index+1),-10);
/*
$cookieName = 'PhotoExpress_'.$code;
$data = base64_encode(urlencode($data));
setcookie($cookieName,$data,(time()+60*60*24*30));
echo "Cookie: ".$cookieName." ".urldecode(base64_decode($data));
*/
?>
