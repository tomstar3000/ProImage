<?php if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++)	$r_path .= "../";
}
include $r_path.'security.php';
require_once $r_path.'scripts/fnct_clean_entry.php';
require_once $r_path.'scripts/fnct_send_email.php';

$IId = $path[4];
$Folder = '../../'.$r_path."toPhatFoto";

$query_get_info = "SELECT `cust_customers`.`cust_phone`, `cust_customers`.`cust_fname`, `cust_customers`.`cust_lname`, `cust_customers`.`cust_email`, `cust_shipping`.*, `orders_invoice`.* FROM `orders_invoice` INNER JOIN `cust_customers` ON `cust_customers`.`cust_id` = `orders_invoice`.`cust_id` INNER JOIN `cust_shipping` ON `cust_shipping`.`cust_id` = `orders_invoice`.`cust_id` WHERE `invoice_id` = '$IId'";
$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
$row_get_info = mysql_fetch_assoc($get_info);

$InvNum = $row_get_info['invoice_num'];
$InvEnc = $row_get_info['invoice_enc'];
$Date = $row_get_info['invoice_date'];
$FName = $row_get_info['cust_fname'];
$LName = $row_get_info['cust_lname'];
$Phone = $row_get_info['cust_phone'];
$Email = $row_get_info['cust_email'];
$SFName = $row_get_info['cust_ship_fname'];
$SLName = $row_get_info['cust_ship_lname'];
$SAdd = $row_get_info['cust_ship_add'];
$SSuite = $row_get_info['cust_ship_suite_apt'];
$SCity = $row_get_info['cust_ship_city'];
$SState = $row_get_info['cust_ship_state'];
$SZip = $row_get_info['cust_ship_zip'];
$PSale = $row_get_info['prod_sale'];
$Total = $row_get_info['invoice_total'];
$Sent = $row_get_info['invoice_accepted'];

$query_get_image = "SELECT * FROM `orders_invoice_photo` INNER JOIN `photo_event_images` ON `photo_event_images`.`image_id` = `orders_invoice_photo`.`image_id` WHERE `invoice_id` = '$IId' GROUP BY `orders_invoice_photo`.`image_id`";
$get_image = mysql_query($query_get_image, $cp_connection) or die(mysql_error());
if($cont == "ToLab" || (isset($_POST['Controller']) && $_POST['Controller'] == "Send to Lab")){
	$query_get_images = "SELECT `prod_products`.`prod_name`, `prod_products`.`prod_number`, `orders_invoice_photo`.*, `photo_event_images`.* FROM `orders_invoice_photo` INNER JOIN `photo_event_images` ON `photo_event_images`.`image_id` = `orders_invoice_photo`.`image_id` INNER JOIN `prod_products` ON `prod_products`.`prod_id` = `orders_invoice_photo`.`invoice_image_size_id` WHERE `invoice_id` = '$IId' ";
	$get_images = mysql_query($query_get_images, $cp_connection) or die(mysql_error());
		
	$date = date("Y-m-d");
	$eol = "\r\n";
	if($use_ftp === true){
		$conn_id = ftp_connect($ftp_server);
		$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
	}
	$Folder = realpath($Folder)."/";
	if(!is_dir($Folder.$date)){
		if($use_ftp === true){
			ftp_mkdir($conn_id, $Folder.$date);
		} else {
			mkdir($Folder.$date);
		}
	}
	$absPath = realpath($Folder.$date);
	while(strlen($InvNum)<5){
		$InvNum = "0".$InvNum;
	}
	if(!is_dir($absPath."/".$InvNum)){
		if($use_ftp === true){
			ftp_mkdir($conn_id, $absPath."/".$InvNum);
		} else {
			mkdir($absPath."/".$InvNum);
		}
	}
	$absPath = realpath($absPath."/".$InvNum);
	$mrk_array = array();
	while($row_get_images = mysql_fetch_assoc($get_images)){
		$image = ($row_get_images['invoice_image_image'] != "") ? $row_get_images['invoice_image_image'] : $row_get_images['image_tiny'];
		$new_Folder = "/o".$InvNum.".".$row_get_images['prod_number'];
		$replace = array('\\','/','*','?','"','<','>','|');
		$with = array('-','-','-','-','-','-','-','-');
		$new_Folder = str_replace($with, $replace, $new_Folder);
		if(!is_dir($absPath."/".$new_Folder)){
			if($use_ftp === true){
				ftp_mkdir($conn_id, $absPath."/".$new_Folder);
				ftp_mkdir($conn_id, $absPath."/".$new_Folder."/IMAGE");
				ftp_mkdir($conn_id, $absPath."/".$new_Folder."/MISC");
			} else {
				mkdir($absPath."/".$new_Folder);
				mkdir($absPath."/".$new_Folder."/IMAGE");
				mkdir($absPath."/".$new_Folder."/MISC");
			}
		}
		$count = count($mrk_array[$new_Folder]);
		$mrk_array[$new_Folder][$count]['size'] = $row_get_images['prod_name'];
		$mrk_array[$new_Folder][$count]['image'] = $image;
		$mrk_array[$new_Folder][$count]['asis'] = $row_get_images['invoice_image_asis']+$row_get_images['invoice_image_bw']+$row_get_images['invoice_image_sepia'];
		$mrk_array[$new_Folder][$count]['bw'] = 0;
		$mrk_array[$new_Folder][$count]['sepia'] = 0;
		
		$absFolderPath = realpath($absPath."/".$new_Folder."/IMAGE")."/";
		if($use_ftp === true){
			ftp_put($conn_id, $absFolderPath.$image, '../../'.$r_path.$row_get_images['image_folder'].$row_get_images['image_tiny'], FTP_BINARY);
		} else {
			copy('../../'.$r_path.$row_get_images['image_folder'].$image, $absFolderPath.$image);
		}
	}
	foreach($mrk_array as $key => $value){
		$pid = 0;
		ob_start();
		echo '[HDR]'.$eol.'GEN REV = 01.00'.$eol.'GEN CRT = "NORITSU KOKI" -01.00'.$eol.'GEN DTM = '.date("Y:m:d:H:i:s").$eol.'USR NAM = "'.$InvNum.'"'.$eol.'VUQ RGN = BGN'.$eol.'VUQ VNM = "NORITSU KOKI" -ATR "QSSPrint"'.$eol.'VUQ VER = 01.00'.$eol.'PRT PSL = NML -PSIZE "'.$value[0]['size'].' m"'.$eol.'PRT PCH = '.$key.$eol.'GEN INP = "ZIP"'.$eol.'VUQ RGN = END'.$eol.$eol;
		foreach($value as $k => $v){
			
			if($v['asis'] != 0){
				$pid++;
				$tid = $pid;
				while(strlen($tid)<3){
					$tid = "0".$tid;
				}
				echo '[JOB]'.$eol.'PRT PID = '.$tid.$eol.'PRT TYP = STD'.$eol.'PRT QTY = '.$v['asis'].$eol.'IMG FMT = EXIF2 -J'.$eol.'<IMG SRC = "../IMAGE/'.$v['image'].'">'.$eol.'VUQ RGN = BGN'.$eol.'VUQ VNM = "NORITSU KOKI" -ATR "QSSPrint"'.$eol.'VUQ VER = 01.00'.$eol.'PRT CVP1 = 0'.$eol.'PRT CVP2 = 0'.$eol.'VUQ RGN = END'.$eol.$eol;
			}
			if($v['bw'] != 0){
				$pid++;
				$tid = $pid;
				while(strlen($tid)<3){
					$tid = "0".$tid;
				}
				echo '[JOB]'.$eol.'PRT PID = '.$tid.$eol.'PRT TYP = BW'.$eol.'PRT QTY = '.$v['bw'].$eol.'IMG FMT = EXIF2 -J'.$eol.'<IMG SRC = "../IMAGE/'.$v['image'].'">'.$eol.'VUQ RGN = BGN'.$eol.'VUQ VNM = "NORITSU KOKI" -ATR "QSSPrint"'.$eol.'VUQ VER = 01.00'.$eol.'PRT CVP1 = 0'.$eol.'PRT CVP2 = 0'.$eol.'VUQ RGN = END'.$eol.$eol;
			}
			if($v['sepia'] != 0){
				$pid++;
				$tid = $pid;
				while(strlen($tid)<3){
					$tid = "0".$tid;
				}
				echo '[JOB]'.$eol.'PRT PID = '.$tid.$eol.'PRT TYP = SEPIA'.$eol.'PRT QTY = '.$v['sepia'].$eol.'IMG FMT = EXIF2 -J'.$eol.'<IMG SRC = "../IMAGE/'.$v['image'].'">'.$eol.'VUQ RGN = BGN'.$eol.'VUQ VNM = "NORITSU KOKI" -ATR "QSSPrint"'.$eol.'VUQ VER = 01.00'.$eol.'PRT CVP1 = 0'.$eol.'PRT CVP2 = 0'.$eol.'VUQ RGN = END'.$eol.$eol;
			}
		}
		$print = ob_get_contents();
		ob_end_clean();
		$mrkPath = realpath($absPath."/".$key."/MISC")."/";
		$file_mrk = $mrkPath."AUTOPRINT.MRK";
		if (file_exists($file_mrk)){
			if($use_ftp === true){
				unlink("ftp://".$ftp_user_name.":".$ftp_user_pass."@".$ftp_server."/".$file_mrk);
			} else {
				unlink($file_mrk);
			}
		}
		if($use_ftp === true){
			$file = fopen("ftp://".$ftp_user_name.":".$ftp_user_pass."@".$ftp_server."/".$file_mrk, 'w');
		} else {
			$file = fopen($file_mrk, 'w');
		}
		fwrite($file, $print);
		fclose($file);
	}
}
mysql_free_result($get_info);
?>
