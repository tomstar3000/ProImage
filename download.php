<?
$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-1; for($n=0;$n<$count;$n++)$r_path .= "../";
if($_SERVER['HTTPS'] == "on"){
	$GoTo = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'];
	header(sprintf("Location: %s", $GoTo));
}
define ("Allow Scripts", true);

require_once($r_path.'Connections/cp_connection.php');
require_once($r_path.'scripts/fnct_clean_entry.php');
$Fatel = false;
//https://testing.proimagesoftware.com/checkout/download.php?invoice=dc1322dd294effcdac9942803027b362dd28f283fac5be486ed3f7c7ca1462fc
//http://www.proimagesoftware.com/download.php?invoice=8dace006f34038186ff908851cb7f09c07ba68cf368bd69c04a32100b0481881
if(isset($_GET['invoice'])) {
	require_once($r_path.'scripts/fnct_zip.php');
	if(isset($_GET['invoice']))$encnum = clean_variable($_GET['invoice'],true);
	$key = substr($encnum,-32);
	$encnum = substr($encnum,0,32);
	mysql_select_db($database_cp_connection, $cp_connection);
	$size = 0;
	$files = array();
	$folders = array();
	$query_get_photo = "SELECT `photo_event_images`.*, `prod_products`.`prod_id`,`prod_products`.`prod_serial`,`photo_event`.`event_num`,`cust_customers`.`cust_handle`,`orders_invoice`.`invoice_paid_date`,
	`photo_event_group`.`parnt_group_id`,`photo_event_group`.`group_name`,
	ABS(TO_DAYS(`orders_invoice`.`invoice_date`) - TO_DAYS(NOW())) AS `TestInvoice`
			FROM `photo_event_images`
			INNER JOIN `photo_event_group`
				ON `photo_event_group`.`group_id` = `photo_event_images`.`group_id`
			INNER JOIN `photo_event`
				ON `photo_event`.`event_id` = `photo_event_group`.`event_id`
			INNER JOIN `cust_customers`
				ON `cust_customers`.`cust_id` = `photo_event`.`cust_id`
			INNER JOIN `orders_invoice_photo` 
				ON `orders_invoice_photo`.`image_id` = `photo_event_images`.`image_id` 
			INNER JOIN `orders_invoice` 
				ON `orders_invoice`.`invoice_id` = `orders_invoice_photo`.`invoice_id`
			INNER JOIN `prod_products`
				ON `prod_products`.`prod_id` = `orders_invoice_photo`.`invoice_image_size_id`
			WHERE `orders_invoice`.`invoice_enc` = '$encnum'
				AND (`prod_products`.`prod_serial` = '099'
					OR `prod_products`.`prod_serial` = '100')
			GROUP BY `photo_event_images`.`image_id`,`prod_products`.`prod_serial`
			ORDER BY `image_tiny` ASC";
	$get_photo = mysql_query($query_get_photo, $cp_connection) or die(mysql_error());
	if(isset($_POST['Controller']) && $_POST['Controller'] == "true"){
		if(md5(strtolower($_POST['Code'])) == $key){
			while($row_get_photo = mysql_fetch_assoc($get_photo)){
				$filename = $row_get_photo['cust_handle']."_".$row_get_photo['event_num'];
				if($row_get_photo['prod_serial']=="099") {
					$filesize = "Large";
				} else if($row_get_photo['prod_serial']=="100"){
					$filesize = "EncryptFolder";
				} else {
					$filesize = "Large";
				}
			
				$index = strrpos($row_get_photo['image_folder'], "/");
				$Folder = substr($row_get_photo['image_folder'],0,$index);
				$Folder = mb_convert_encoding($Folder,"UTF-8","HTML-ENTITIES");
				$index = strrpos($Folder, "/");
				$Folder = substr($Folder,0,$index);
				$PrntGroupId = $row_get_photo['parnt_group_id'];
				$FilePath = preg_replace("/[^a-zA-Z0-9_]/","",str_replace(" ","_",$row_get_photo['group_name']))."/";
				$FilePath = mb_convert_encoding($FilePath,"UTF-8","HTML-ENTITIES");
				while($PrntGroupId != 0){
					$query_get_group = "SELECT `photo_event_group`.`parnt_group_id`,`photo_event_group`.`group_name`
						FROM `photo_event_group`
						WHERE `group_id` = '$PrntGroupId'";
					$get_group = mysql_query($query_get_group, $cp_connection) or die(mysql_error());
					$row_get_group = mysql_fetch_assoc($get_group);
					$PrntGroupId = $row_get_group['parnt_group_id'];
					$FilePath = preg_replace("/[^a-zA-Z0-9_]/","",str_replace(" ","_",$row_get_group['group_name'])).$FilePath."/";
				}
				if($filesize == "EncryptFolder"){
					$TFile = $photographerFolder.mb_convert_encoding($row_get_photo['image_folder'].$row_get_photo['image_tiny'],"UTF-8","HTML-ENTITIES");
					array_push($files, $TFile);
					$size += (filesize($TFile));
					array_push($folders,("Hi-Res/".$FilePath));
					unset($TFile);
				} else {
					$TFile = $photographerFolder.mb_convert_encoding($Folder."/".$filesize."/".$row_get_photo['image_tiny'],"UTF-8","HTML-ENTITIES");
					array_push($files, $TFile);
					$size += (filesize($TFile));
					array_push($folders,("Lo-Res/".$FilePath));
					unset($TFile);
				}
			}
			//$size += (50*count($files));
			$size = round($size/pow(1024, 2));
			$dwnspd = 56/1024; // 56kb in megabytes
			
			$filename .= "_".$size."MB.zip";
			$ziper = new zipfile();
			//ini_set("memory_limit",$size."M");
			ini_set("memory_limit","500M");
			ini_set("max_execution_time",round(($size/$dwnspd)));
			set_time_limit(0);
			$ziper->addFiles($files,$folders);  //array of files, destination Directory
			
			sleep (1);
			header("Content-Type: application/zip");
			header("Content-Disposition: attachment; filename=".$filename);
			header("Pragma: no-cache");
			header("Expires: 0");
			
			echo $ziper->file();
			
			ini_restore ("memory_limit");
			ini_restore ("max_execution_time");
			exec(0);
		} else {
			$Error = "The download key that you have entered does not match the key from your invoice. Please douple check your key and try again.";
		}
	} else {
		$row_get_photo = mysql_fetch_assoc($get_photo);
		if($row_get_photo['TestInvoice'] > 15){
			$Error = "The invoice that you have selected to download is past the 15 days experation.";
			$Fatel = true;
		}
	}
} else {
	$Error = "We were unable to find the invoice you are looking for. Please make sure the link you have entered matches the link on the invoice.";
	$Fatel = true;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="shortcut icon" href="http://www.proimagesoftware.com/photoexpress.ico" type="image/x-icon">
<link rel="icon" href="http://www.proimagesoftware.com/photoexpress.ico" type="image/x-icon">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta name="robots" content="index,follow" />
<meta name="keywords" content=""/>
<meta name="language" content="english"/>
<meta name="author" content="Pro Image Software" />
<meta name="copyright" content="2007" />
<meta name="reply-to" content="info@proimagesoftware.com" />
<meta name="document-rights" content="Copyrighted Work" />
<meta name="document-type" content="Web Page" />
<meta name="document-rating" content="General" />
<meta name="document-distribution" content="Global" />
<meta http-equiv="expires" content="0" />
<meta http-equiv="Pragma" content="no-cache" />
<title>ProImageSoftware</title>
<link href="/stylesheets/photoexpress.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/javascript/GoogleTracker.js"></script>
<script type="text/javascript">
<!--
function MM_validateForm() { //v4.0
  if (document.getElementById){
    var i,p,q,nm,test,num,min,max,errors='',args=MM_validateForm.arguments;
    for (i=0; i<(args.length-2); i+=3) { test=args[i+2]; val=document.getElementById(args[i]);
      if (val) { nm=val.name; if ((val=val.value)!="") {
        if (test.indexOf('isEmail')!=-1) { p=val.indexOf('@');
          if (p<1 || p==(val.length-1)) errors+='- '+nm+' must contain an e-mail address.\n';
        } else if (test!='R') { num = parseFloat(val);
          if (isNaN(val)) errors+='- '+nm+' must contain a number.\n';
          if (test.indexOf('inRange') != -1) { p=test.indexOf(':');
            min=test.substring(8,p); max=test.substring(p+1);
            if (num<min || max<num) errors+='- '+nm+' must contain a number between '+min+' and '+max+'.\n';
      } } } else if (test.charAt(0) == 'R') errors += '- '+nm+' is required.\n'; }
    } if (errors) alert('The following error(s) occurred:\n'+errors);
    document.MM_returnValue = (errors == '');
} }
//-->
</script>
</head>
<body onload="MM_preloadImages('/images/btn_getStarted_on.jpg','/images/btn_services_features_on.jpg','/images/btn_demo_on.jpg')">
<div id="Container">
 <div id="Logo"></div>
 <div id="Main_Content">
  <div id="#Main_Text_Long">
   <h1 style="padding-left:10px;">Digital Download Portal</h1>
   <? if (isset($Error)) { ?>
    <div style="margin:8px; padding-right:5px; padding-top:5px; padding-bottom:5px; height:auto; background-color:#FFFFFF; clear:both"><img src="/images/warning.jpg" width="30" height="29" border="0" />
     <p style="color:#990000;"><? echo $Error; ?></p>
     <p style="color:#990000;">If you feel the information that you have entered is correct or you are experiencing issues please
      contact us at <a href="mailto:info@photoexpress.com">info@photoexpress.com</a></p>
    </div>
    <? } if($Fatel === false){ ?>
    <div id="MsgForm">
     <p>Please verify the your download by entering in the security code that accompanied your invoice.</p>
     <form method="post" name="CheckForm" id="CheckForm" action="<? echo $_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']; ?>" enctype="multipart/form-data">
      <label for="Code">Enter Code: </label>
      <input type="text" name="Code" id="Code" />
      <input type="hidden" name="Controller" id="Controller" value="true" />
      <img src="/images/btn_enter.jpg" width="50" height="21" align="absmiddle" onclick="MM_validateForm('Code','','R'); if(document.MM_returnValue==true){document.getElementById('MsgForm').style.display='none';document.getElementById('MsgShow').style.display='block';document.getElementById('CheckForm').submit();}" />
     </form>
    </div>
    <div id="MsgShow" style="display:none;">
     <p>Please wait for the file to initiate it's download.</p>
    </div>
    <? } ?>
  </div>
  <br clear="all" />
 </div>
</div>
<div id="Footer">
 <div style="float:left">
  <p> Photo Express ProImageSoftware </p>
 </div>
 <div style="float:left; margin-left:30px;">
  <p><a href="mailto:support@proimagesoftware.com" class="Footer_Nav">support@proimagesoftware.com</a></p>
 </div>
</div>
</body>
</html>
