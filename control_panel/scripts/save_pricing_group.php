<?
include $r_path . 'security.php';
require_once $r_path . 'scripts/fnct_clean_entry.php';
require_once $r_path.'scripts/fnct_date_format_for_db.php';

// $PGId = $path[2];
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
    array_push($prices,$id[$k].":".preg_replace("/[^0-9.]/","",$price[$k]));
  }
}
$Price = array();
$Price = $prices;
$price = implode(",",$prices);
if(isset($_POST['Controller']) && $_POST['Controller'] == "Save" && ($cont == 'add' || $cont == 'edit')){
  if($cont == "add"){
    
    $getId = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
    $getId->mysql("SELECT `cust_id` FROM `cust_customers` WHERE `cust_session` = '$loginsession[0]';");
    $getId = $getId->Rows();    
    $cust_id = $getId[0]['cust_id'];
    
    $addInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
    $addInfo->mysql("INSERT INTO `photo_event_price` (`cust_id`,`price_name`,`photo_price`,`photo_color`,`photo_blk_wht`,`photo_sepia`,`photo_price_use`) VALUES ('$CustId','$Name','$price','y','y','y','y');");
    
    $getLast = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
    $getLast->mysql("SELECT `photo_event_price_id` FROM `photo_event_price` WHERE `cust_id` = '$CustId' ORDER BY `photo_event_price_id` DESC LIMIT 0,1;");
    $getLast = $getLast->Rows();    
    array_push($path,$getLast[0]['photo_event_price_id']);
  } else {
    $updInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
    $updInfo->mysql("UPDATE `photo_event_price` SET `price_name` = '$Name',`photo_price` = '$price' WHERE `photo_event_price_id` = '$PId';");
  }
  $cont = "view";
} else {
  if($cont != "add"){
    $getInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
    $getInfo->mysql("SELECT * FROM `photo_event_price` WHERE `photo_event_price_id` = '$PId';");
    $getInfo = $getInfo->Rows();
    
    $Name = $getInfo[0]['price_name'];
    $Price = $getInfo[0]['photo_price'];
    $Price = explode(",",$Price);
  }
}
$Id = array();
$prices = array();
foreach($Price as $k => $v){
  $temp = explode(":",$v);
  array_push($Id,$temp[0]);
  array_push($prices,$temp[1]);
}
define('NoSave',true);
if(isset($required_string))
  $onclick = 'MM_validateForm('.$required_string.'); if(document.MM_returnValue == true){document.getElementById(\'Controller\').value = \'Save\'; document.getElementById(\'form_action_form\').submit();}';
else 
  $onclick = 'document.getElementById(\'Controller\').value = \'Save\'; document.getElementById(\'form_action_form\').submit();';
?>