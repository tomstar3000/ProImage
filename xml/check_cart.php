<?
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-1;
$r_path = "";
for($n=0;$n<$count;$n++) $r_path .= "../";
define ("Allow Scripts", true);
//session_cache_expire(1440);
//session_start();
require_once($r_path.'scripts/fnct_xml_parser.php');
$data = file_get_contents("php://input");
//$data = '<info code="dcw1flyinghorse"></info>';
$data = '<?xml version="1.0" encoding="iso-8859-1"?>'.$data;
$parser = new XMLParser($data, 'raw', 1);
$tree = $parser->getTree();

$code = $tree['INFO']['ATTRIBUTES']['CODE'];
$cookieName = 'PhotoExpress_Cart_'.$code;

$cart == false;
$disc = "null";
session_start();
if(isset($_SESSION['cart'])){
	$cart = explode("[+]",$_SESSION['cart']);
	if($_SESSION['disc'] == "null"){
		$disc = "null";
	} else {
		$disc = '<?xml version="1.0" encoding="iso-8859-1"?>'.$_SESSION['disc'];
		$disc = new XMLParser($disc, 'raw', 1);
		$disc = $parser->getTree();
		$disc = $tree['DISCOUNT']['ATTRIBUTES']['ID'];
	}
} else if(isset($_COOKIE[$cookieName])){
	//$cookieValue = urldecode(base64_decode($_COOKIE[$cookieName]));
	$cookieValue = "";
	$cart = $cookieValue;
	$cart = explode("[-+-]",$cart);
	$disc = $cart[1];
	$cart = explode("[+]",$cart[0]);
}
if($cart != false){
	if(count($cart) == 0){
		$nodes = '<cart name="Empty" discount="'.$disc.'"></cart>';
	} else {
		$nodes = '<cart name="Full" discount="'.$disc.'">';
		foreach($cart as $k=>$v){
			$nodes .= '<item>'.$v.'</item>';
		}
		$nodes .= '</cart>';
	}
	$cart = implode("[+]",$cart);
	$cart .= "[-+-]".$disc;
} else {
	$nodes = '<cart name="Empty"></cart>';
}
echo '<?xml version="1.0" encoding="iso-8859-1"?>';
echo $nodes;
?>