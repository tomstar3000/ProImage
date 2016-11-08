<? if(!isset($r_path)){	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;	$r_path = "";	for($n=0;$n<$count;$n++)$r_path .= "../";}
include $r_path.'security.php';
$items = array();
$items = $_POST['Categories_stored_items'];
function save_vals($value,$key){
	global $cp_connection;
	
	$order = $_POST['Categories_Order'];
	$order = clean_variable($order[$key],true);
	
	$del= "UPDATE `prod_categories`SET `cat_order` = '$order' WHERE `cat_id` = '$value'";
	$delinfo = mysql_query($del, $cp_connection) or die(mysql_error());
}
if(count($items)>0){foreach ($items as $key => $value)save_vals($value,$key);}
?>