<?
$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-1;	$r_path = "";	for($n=0;$n<$count;$n++)$r_path .= "../";
define('Allow Scripts',true);
require_once $r_path.'scripts/cart/encrypt.php';
require_once $r_path.'scripts/fnct_clean_entry.php';

if(!isset($_GET['id'])) die();
if(!isset($_GET['w'])) die();
if(!isset($_GET['h'])) die();

$_GET['data'] = base64_encode ( encrypt_data( serialize( array("id" => clean_variable($_GET['id'],true), "width" => clean_variable($_GET['w'],true), "height" => clean_variable($_GET['h'],true)) ) ) );

include $r_path.'images/image.php';
?>