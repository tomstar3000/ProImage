<?
include $r_path.'security.php';
require_once $r_path.'scripts/fnct_clean_entry.php';
require_once $r_path.'scripts/fnct_image_resize.php';
require_once $r_path.'scripts/fnct_date_format_for_db.php';
$PId = $path[2];
$Name = (isset($_POST['Name'])) ? clean_variable($_POST['Name'],true) : '';
$Id = (isset($_POST['id'])) ? $_POST['id'] : array();
$Ids = (isset($_POST['ids'])) ? $_POST['ids'] : array();
$Price = (isset($_POST['price'])) ? $_POST['price'] : array();
$Order = (isset($_POST['order'])) ? $_POST['order'] : array();
foreach($Order as $k => $v){
	if($v == "" && $Price[$k] != ""){
		$Order[$k] = $k;
	}
}
natsort($Order);
$price = array();
$order = array();
$id = array();
$prices = array();
foreach($Order as $k => $v){
	if($v != ""){
		$order[$k] = $v;
		$id[$k] = $Ids[$k];
	}
}
foreach($Id as $k => $v){
	$key = array_search($v, $id);
	if(is_int($key)){
		$price[$key] = $Price[$key];
	}
}
$Id = array();
foreach($order as $k => $v){
	if(isset($price[$k])){
		array_push($prices,$id[$k].":".$price[$k]);
	}
}
$Price = array();
$Price = $prices;
$price = implode(",",$prices);
if(isset($_POST['Controller']) && $_POST['Controller'] == "Save" && ($cont == 'add' || $cont == 'edit')){
	if($cont == "add"){
		
		$query_get_id = "SELECT `cust_id` FROM `cust_customers` WHERE `cust_session` = '$loginsession[0]'";
		$get_id = mysql_query($query_get_id, $cp_connection) or die(mysql_error());
		$row_get_id = mysql_fetch_assoc($get_id);
		
		$cust_id = $row_get_id['cust_id'];
		
		mysql_free_result($get_id);
		
		$add = "INSERT INTO `photo_event_price` (`cust_id`,`price_name`,`photo_price`,`photo_color`,`photo_blk_wht`,`photo_sepia`,`photo_price_use`) VALUES ('$CustId','$Name','$price','y','y','y','y');";
		$addinfo = mysql_query($add, $cp_connection) or die(mysql_error());
		
		$query_get_last = "SELECT `photo_event_price_id` FROM `photo_event_price` WHERE `cust_id` = '$CustId' ORDER BY `photo_event_price_id` DESC LIMIT 0,1";
		$get_last = mysql_query($query_get_last, $cp_connection) or die(mysql_error());
		$row_get_last = mysql_fetch_assoc($get_last);
		
		array_push($path,$row_get_last['photo_event_price_id']);
	} else {
		$upd = "UPDATE `photo_event_price` SET `price_name` = '$Name',`photo_price` = '$price' WHERE `photo_event_price_id` = '$PId'";
		$updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());
	}
	$cont = "view";
} else {
	if($cont != "add"){
		$query_get_info = "SELECT * FROM `photo_event_price` WHERE `photo_event_price_id` = '$PId'";
		$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
		$row_get_info = mysql_fetch_assoc($get_info);
		
		$Name = $row_get_info['price_name'];
		$Price = $row_get_info['photo_price'];
		$Price = explode(",",$Price);
		mysql_free_result($get_info);
	}
}
$Id = array();
$prices = array();
foreach($Price as $k => $v){
	$temp = explode(":",$v);
	array_push($Id,$temp[0]);
	array_push($prices,$temp[1]);
}
?>
