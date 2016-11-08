<?
$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-1;
$r_path = "";
for($n=0;$n<$count;$n++){
	$r_path .= "../";
}
define('Allow Scripts',true);
require_once($r_path.'scripts/cart/ssl_paths.php'); 
require_once($r_path.'scripts/fnct_clean_entry.php');
require_once($r_path.'scripts/fnct_format_phone.php');
require_once($r_path.'Connections/cp_connection.php');
if(isset($_GET['Photographer'])){
	$handle = clean_variable($_GET['Photographer'],true);
	$handle = str_replace(" ","_",$handle);
} else if(isset($_POST['Photographer'])){
	$handle = clean_variable($_POST['Photographer'],true);
	$handle = str_replace(" ","_",$handle);
} else if($_SERVER['QUERY_STRING'] != "") {
	$handle = clean_variable($_SERVER['QUERY_STRING'],true);
	$handle = str_replace(" ","_",$handle);
} else {
	$handle = false;
}
mysql_select_db($database_cp_connection, $cp_connection);
$query_get_info = "SELECT `cust_fname`, `cust_mint`, `cust_lname`, `cust_suffix`, `cust_cname`, `cust_desc`, `cust_add`, `cust_add_2`, `cust_suite_apt`, `cust_city`, `cust_state`, `cust_zip`, `cust_country`, `cust_phone`, `cust_cell`, `cust_fax`, `cust_work`, `cust_ext`, `cust_email`, `cust_website`, `cust_thumb`, `cust_image`, `cust_fcname`, `cust_fwork`, `cust_fext`, `cust_femail`, `cust_icon` FROM `cust_customers` WHERE `cust_handle` = '$handle' LIMIT 0,1";
$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
$row_get_info = mysql_fetch_assoc($get_info);
$code = (isset($_POST['Event_Code'])) ? clean_variable($_POST['Event_Code'],true) : "";
$encnum = (isset($_GET['invoice'])) ? clean_variable($_GET['invoice'],true) : "";
$launch_full = false;

require_once($r_path.'includes/_PhotoInfo.php');
require_once($r_path.'includes/_Guestbook.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<? include $r_path.'includes/_metadata.php'; ?>
<link href="/css/Photographer.css" rel="stylesheet" type="text/css" />
<? if($launch_full === true){ ?>
<script type="text/JavaScript">
AEV_new_window("<? echo $GoTo; ?>","ProImageSoftware",null,null,null,null,null,null,null,null,true);
</script>
<? } ?>
</head>
<body>
<div id="Container">
  <div id="Logo">
    <? if($getInfo[0]['cust_image'] !="" ){
		$data = array(
			'imagetype' => 'logo',
			'image' => $handle.'/'.$getInfo[0]['cust_image'],
			'width' => 998,
			'height' => 387,
			'salt' => time(),
		);
		require_once $r_path.'scripts/cart/encrypt.php';
		$data = base64_encode(encrypt_data(serialize($data)));
		echo '<img src="/images/image.php?data='.$data.'"  />'; } else { ?>
    <img src="/images/spacer.gif" width="998" height="387" />
    <? } ?>
  </div>
  <div id="Content">
    <? include $r_path.'includes/_PhotoNavigation.php'; ?>
    <div id="TextLong">
      <h1>Thank you for your purchase </h1>
      <p>Thank you for purchasing from <? echo $row_get_info['cust_cname']; ?>. Your Invoice has been e-mailed to you and your order will be processed soon.</p>
      <p><a href="/checkout/invoice.php?invoice=<? echo $encnum; ?>" target="_blank">Click here to view your invoice.</a> </p>
    </div>
    <br clear="all" />
  </div>
</div>
<? include $r_path.'includes/_PhotoFooter.php'; ?>
</body>
</html>
