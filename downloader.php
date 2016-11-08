<?
$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-1; for($n=0;$n<$count;$n++)$r_path .= "../";
if($_SERVER['HTTPS'] == "on"){
	$GoTo = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'];
	header(sprintf("Location: %s", $GoTo));
}
define ("Allow Scripts", true);

require_once($r_path.'Connections/cp_connection.php');
require_once($r_path.'scripts/fnct_clean_entry.php');
require_once($r_path.'scripts/fnct_format_phone.php');
require_once($r_path.'scripts/fnct_sql_processor.php');
require_once($r_path.'scripts/cart/encrypt.php');
$Fatel = false;

//http://testing.proimagesoftware.com/downloader.php?invoice=JUQ0JTE2JUVGMyU4RSVERiVERSVFNSVDNCVFM1olMDYlQkQlOUQlOTJuJThDJTAzLiU4MSU3RSU5OCU5NCU4NCVBQiVFMyVCNCUyNiVGRCUyQSVBN3clQUElQTYlQ0MlRTglQ0JGJUREJTg1JTA0JUVDUHolRjhqJUI0bSVDQSVGQ1QlQUElMEFwJUVCWSUyOCVDOUIlOTUlQzYlOTQlQTlad1olMTMlMTclMTclMjVMJTA2JTA4SSUyRmUlOEUlMTQlQkIlMEZtJUU1QSVCOCU1RCUxQjYlQzAlRDYlOEYlQkIlRDAlMUMlNUUlRTYlQUI=

if(isset($_GET['invoice'])) {
	require_once($r_path.'scripts/fnct_zip.php');
		
	$KEY = clean_variable(decrypt_data(base64_decode($_GET['invoice'])),true);
	$KEY = explode(".",$KEY);
	$key = trim($KEY[2]);
	$date = $KEY[1];
	$encnum = $KEY[0];
		
	mysql_select_db($database_cp_connection, $cp_connection);
	$size = 0;
	$files = array();
	$folders = array();
	$getRows = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
	$getRows->mysql("SELECT `photo_event_images`.*, `prod_products`.`prod_id`,`prod_products`.`prod_serial`,`photo_event`.`event_id`,`photo_event`.`event_num`,
									
`cust_customers`.`cust_handle`, `cust_customers`.`cust_id`, `cust_customers`.`cust_fname`, `cust_customers`.`cust_mint`, `cust_customers`.`cust_lname`, `cust_customers`.`cust_suffix`, `cust_customers`.`cust_cname`, `cust_customers`.`cust_desc`, `cust_customers`.`cust_desc_2`, `cust_customers`.`cust_add`, `cust_customers`.`cust_add_2`, `cust_customers`.`cust_suite_apt`, `cust_customers`.`cust_city`, `cust_customers`.`cust_state`, `cust_customers`.`cust_zip`, `cust_customers`.`cust_country`, `cust_customers`.`cust_phone`, `cust_customers`.`cust_cell`, `cust_customers`.`cust_fax`, `cust_customers`.`cust_work`, `cust_customers`.`cust_ext`, `cust_customers`.`cust_email`, `cust_customers`.`cust_website`, `cust_customers`.`cust_thumb`, `cust_customers`.`cust_image`, `cust_customers`.`cust_fcname`, `cust_customers`.`cust_fwork`, `cust_customers`.`cust_fext`, `cust_customers`.`cust_femail`, `cust_customers`.`cust_icon`, `cust_customers`.`cust_active`, `cust_customers`.`cust_paid`,
									
					  `orders_invoice`.`invoice_paid_date`, `photo_event_group`.`parnt_group_id`,`photo_event_group`.`group_name`
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
			ORDER BY `image_tiny` ASC;");
	$getInfo = $getRows->Rows();
	
	$getCode = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
	$getCode->mysql("SELECT `event_id`, `event_num`, `event_name`, `event_date`, `event_name`, `event_desc`, `image_tiny`, `image_id`, `image_folder`, `image_rotate`, `event_short`, `cust_cname`, `cust_fname`, `cust_lname`, `cust_email`
									FROM `photo_event`
									LEFT OUTER JOIN `photo_event_images`
										ON `photo_event_images`.`image_id` = `photo_event`.`event_image`
									INNER JOIN `cust_customers` 
										ON `cust_customers`.`cust_id` = `photo_event`.`cust_id`
									WHERE `event_id` = '".$getInfo[0]['event_id']."' 
										AND `event_use` = 'y' LIMIT 0,1;");
	$getCode = $getCode->Rows();
	 
	$custid = $getInfo[0]['cust_id'];
	if(isset($_POST['Controller']) && $_POST['Controller'] == "true"){
		if(md5(strtolower(trim($_POST['Code']))) == $key){
			foreach($getRows->Rows() as $r){
				$filename = $r['cust_handle']."_".$r['event_num'];
				if($r['prod_serial']=="099") {
					$filesize = "Large";
				} else if($r['prod_serial']=="100"){
					$filesize = "EncryptFolder";
				} else {
					$filesize = "Large";
				}
			
				$index = strrpos($r['image_folder'], "/");
				$Folder = substr($r['image_folder'],0,$index);
				$Folder = mb_convert_encoding($Folder,"UTF-8","HTML-ENTITIES");
				$index = strrpos($Folder, "/");
				$Folder = substr($Folder,0,$index);
				$PrntGroupId = $r['parnt_group_id'];
				$FilePath = preg_replace("/[^a-zA-Z0-9_]/","",str_replace(" ","_",$r['group_name']))."/";
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
					$TFile = $photographerFolder.mb_convert_encoding($r['image_folder'].$r['image_tiny'],"UTF-8","HTML-ENTITIES");
					array_push($files, $TFile);
					$size += (filesize($TFile));
					array_push($folders,("Hi-Res/".$FilePath));
					unset($TFile);
				} else {
					$TFile = $photographerFolder.mb_convert_encoding($Folder."/".$filesize."/".$r['image_tiny'],"UTF-8","HTML-ENTITIES");
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
		if(date("U") > $date){
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
<? include $r_path.'includes/_metadata.php'; ?>
<link href="/css/Photographer.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/javascript/GoogleTracker.js"></script>
<script type="text/javascript" src="/javascript/standard_functions.js"></script>
<script type="text/javascript" src="/javascript/AC_OETags.js"></script>
</head>
<body>
<div id="Container">
  <? $CvrClass='2'; include $r_paht.'includes/_PhotoSlideShow.php'; ?>
  <div id="Content"> <br clear="all" />
    <div id="TextLong2a">
      <h1>Digital Download Portal</h1>
      <? if (isset($Error)) { ?>
        <div style="margin:8px; padding-right:5px; padding-top:5px; padding-bottom:5px; height:auto; background-color:#FFFFFF; clear:both"><img src="/images/warning.jpg" width="30" height="29" border="0" />
          <p style="color:#990000;"><? echo $Error; ?></p>
          <p style="color:#990000;">If you feel the information that you have entered is correct or you are experiencing issues please
            contact us at <a href="mailto:support@photoexpress.com">support@photoexpress.com</a></p>
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
      <br clear="all" />
    </div>
  </div>
</div>
<? include $r_path.'includes/_PhotoFooter.php'; ?>
</body>
</html>
