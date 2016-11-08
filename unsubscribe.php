<? $count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2; $r_path = ""; for($n=0;$n<$count;$n++) $r_path .= "../";
define('Allow Scripts',true);
require_once($r_path.'scripts/cart/ssl_paths.php'); 
require_once($r_path.'Connections/cp_connection.php');
require_once($r_path.'scripts/fnct_sql_processor.php');
require_once $r_path.'scripts/fnct_clean_entry.php';
require_once($r_path.'scripts/fnct_format_phone.php');

$code = (isset($_GET['token'])) ? clean_variable($_GET['token'],true) : ((isset($_POST['token'])) ? clean_variable($_POST['token'],true) : "");
$code = substr($code,5,-5);
$email = (isset($_POST['Email'])) ? clean_variable($_POST['Email'],true) : "";

$getInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
$getInfo->mysql("SELECT `cust_customers`.`cust_fname`, `cust_customers`.`cust_mint`, `cust_customers`.`cust_lname`, `cust_customers`.`cust_suffix`, `cust_customers`.`cust_cname`, `cust_customers`.`cust_handle`, `cust_customers`.`cust_desc`, `cust_customers`.`cust_add`, `cust_customers`.`cust_add_2`, `cust_customers`.`cust_suite_apt`, `cust_customers`.`cust_city`, `cust_customers`.`cust_state`, `cust_customers`.`cust_zip`, `cust_customers`.`cust_country`, `cust_customers`.`cust_phone`, `cust_customers`.`cust_cell`, `cust_customers`.`cust_fax`, `cust_customers`.`cust_work`, `cust_customers`.`cust_ext`, `cust_customers`.`cust_email`, `cust_customers`.`cust_website`, `cust_customers`.`cust_thumb`, `cust_customers`.`cust_image`, `cust_customers`.`cust_fcname`, `cust_customers`.`cust_fwork`, `cust_customers`.`cust_fext`, `cust_customers`.`cust_femail`, `cust_customers`.`cust_icon`, `photo_event`.`event_name` 
	FROM `cust_customers` 
	INNER JOIN `photo_event` ON `photo_event`.`cust_id` = `cust_customers`.`cust_id`
	WHERE `photo_event`.`event_id` = '".$code."' LIMIT 0,1;");
$getInfo = $getInfo->Rows();

$handle = $getInfo[0]['cust_handle'];
if(isset($_POST['Controller']) && $_POST['Controller'] == "true"){
	$updInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
	$updInfo->mysql("UPDATE `photo_quest_book` SET `promotion` = 'n' WHERE `event_id` = '".$code."' AND `email` = '$email';");
} ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<? include $r_path.'includes/_metadata.php'; ?>
<link href="/css/Photographer.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div id="Container">
  <div id="Logo">
    <? if($getInfo[0]['cust_image'] !="" ){ echo '<img src="/photographers/'.$handle.'/'.$getInfo[0]['cust_image'].'"  />'; } else { ?>
    <img src="/images/spacer.gif" width="998" height="387" />
    <? } ?>
  </div>
  <div id="Content">
    <div id="Text_Long">
      <? if(isset($_POST['Controller']) && $_POST['Controller'] == "true"){ ?>
      <h1>You Have Successfully Un-subscribed</h1>
      <p>You have successfully removed your e-mail address from the event <strong><? echo $getInfo[0]['event_name']; ?></strong></p>
      <? } else { ?>
      <h1>Unsubscribe From Our Guestbook </h1>
      <form method="post" name="Unsubscribe_Form" id="Unsubscribe_Form" enctype="multipart/form-data" action="<? echo $_SERVER['PHP_SELF']; ?>">
        <p>To un-subscribe from the e-mail list for event <strong><? echo $getInfo[0]['event_name']; ?></strong> fill in your e-mail address below.</p>
        <p>E-mail
          <input type="text" name="Email" id="Email" />
          <input name="token" id="token" type="hidden" value="<? echo clean_variable($_GET['token'],true); ?>" />
          <input type="hidden" name="Controller" id="Controller" value="true" />
          <img src="/images/btn_enter.jpg" width="50" height="21" border="0" onclick="MM_validateForm('Email','','RisEmail');if(document.MM_returnValue){document.getElementById('Unsubscribe_Form').submit();}" style="cursor:pointer" /> </p>
      </form>
      <? } ?>
    </div>
    <br clear="all" />
  </div>
</div>
<? include $r_path.'includes/_PhotoFooter.php'; ?>
</body>
</html>
