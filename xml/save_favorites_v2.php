<?
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-1;
$r_path = "";
for($n=0;$n<$count;$n++)$r_path .= "../";
define ("Allow Scripts", true);
require_once($r_path.'scripts/fnct_xml_parser.php');
$data = file_get_contents("php://input");
$data = '<?xml version="1.0" encoding="iso-8859-1"?>'.$data;
$parser = new XMLParser($data, 'raw', 1);
$tree = $parser->getTree();
$code = $tree['IMAGES']['ATTRIBUTES']['CODE'];
$cookieName = 'PhotoExpress_'.$code;

$newdata = "";
foreach (array_slice($tree['IMAGES']['IMAGE'],0,-1) as $v){
	$newdata .= '<image id="'.$v['ATTRIBUTES']['ID'].'" groupid="'.$v['ATTRIBUTES']['GROUPID'].'" tiny="'.$v['ATTRIBUTES']['TINY'].'" small="'.$v['ATTRIBUTES']['SMALL'].'" zoom="'.$v['ATTRIBUTES']['ZOOM'].'" folder="'.$v['ATTRIBUTES']['FOLDER'].'">'.$v['VALUE'].'</image>';
}
$newdata = base64_encode(urlencode($newdata));
setcookie($cookieName,$newdata,(time()+60*60*24*30));

echo urldecode(base64_decode($_COOKIE[$cookieName]));
?>
