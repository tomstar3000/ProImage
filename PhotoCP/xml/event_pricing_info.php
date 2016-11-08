<? $count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2; $r_path = ""; for($n=0;$n<$count;$n++)$r_path .= "../";
define("PhotoExpress Pro", true);
define('Allow Scripts',true);
require_once($r_path.'../Connections/cp_connection.php');
require_once $r_path.'scripts/fnct_clean_entry.php';

$ID = (isset($_GET['id'])) ? clean_variable($_GET['id'],true) : '';
$CustID = (isset($_GET['user'])) ? clean_variable($_GET['user'],true) : '';
$Action = (isset($_GET['action'])) ? clean_variable($_GET['action'],true) : '';
$Name = (isset($_GET['name'])) ? clean_variable($_GET['name'],true) : '';
$DATAInfo = (isset($_GET['data'])) ? unserialize(rawurldecode($_GET['data'])) : array();
if(isset($_GET['data'])) { foreach($DATAInfo as $k => $v){ $DATAInfo[$k][0] = clean_variable($v[0],true); $DATAInfo[$k][1] = clean_variable($v[1],true); } }

if(intval($Action) == 1){
	$getInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
	$getInfo->mysql("SELECT * FROM `photo_event_price` WHERE `photo_event_price_id` = '$ID';");
	$getInfo = $getInfo->Rows();
	
	$Price = $getInfo[0]['photo_price'];
	$Price = explode(",",$Price);		
	$DATA = '<name>'.$getInfo[0]['price_name'].'</name>';
	$DATA .= '<prices>';
	foreach($Price as $k => $v){ $Value = explode(":",$v); $DATA .= '<price id="'.$Value['0'].'" order="'.$k.'" price="'.number_format($Value['1'],2,".",",").'"></price>'; }
	$DATA .= '</prices>';
} else if(intval($Action) == 2){
	$Ids = array();
	$Id = array();
	$Price = array();
	$Order = array();
	
	$price = array();
	$prices = array();
	$order = array();
	$id = array();
	foreach($DATAInfo as $k => $v){
		array_push($Ids, $k);
		array_push($Id, $k);
		array_push($Price,$v[0]);
		array_push($Order,$v[1]);
	}
	natsort($Order);
	$price = array();
	$prices = array();
	$order = array();
	$id = array();
	
	foreach($Order as $k => $v){
		$order[$k] = $v;
		$id[$k] = $Ids[$k];
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
			array_push($prices,$id[$k].":".preg_replace("/[^0-9.]/","",$price[$k]));
		}
	}
	$Price = array();
	$Price = $prices;
	$price = implode(",",$prices);
	if(intval($ID) == 0){		
		$addInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
		$addInfo->mysql("INSERT INTO `photo_event_price` (`cust_id`,`price_name`,`photo_price`,`photo_color`,`photo_blk_wht`,`photo_sepia`,`photo_price_use`) VALUES ('$CustID','$Name','$price','y','y','y','y');");
		
		$getLast = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
		$getLast->mysql("SELECT `photo_event_price_id` FROM `photo_event_price` WHERE `cust_id` = '$CustID' ORDER BY `photo_event_price_id` DESC LIMIT 0,1;");
		$getLast = $getLast->Rows();
		
		$DATA = "<action>Added</action><data id=\"".$getLast[0]['photo_event_price_id']."\">".$Name."</data>";
	} else {
		$updInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
		$updInfo->mysql("UPDATE `photo_event_price` SET `price_name` = '$Name',`photo_price` = '$price' WHERE `photo_event_price_id` = '$ID';");
		$DATA = "<action>Updated</action>";
	} 
}
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header('Content-type: text/xml');

echo '<?xml version="1.0" encoding="utf-8"?>';
echo '<info>';
echo $DATA;
echo '</info>'; ?>