<? if(!isset($r_path)) { $r_path = ""; $count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-1; for($n=0;$n<$count;$n++)$r_path .= "../"; }
require_once($r_path.'Connections/cp_connection.php');
require_once($r_path.'scripts/fnct_sql_processor.php');
require_once($r_path.'scripts/cart/encrypt.php');
require_once($r_path.'scripts/fnct_clean_entry.php');
require_once($r_path.'scripts/emogrifier.php');
if(!function_exists("FindCSS")){
	function FindCSS($CSSLink){
		global $CSS, $Template, $r_path;
		$path_parts = pathinfo($CSSLink);
		$path = $path_parts['basename'];
		$path_parts = pathinfo($Template);
		$path = $path_parts['dirname']."/".$path;
		$handle = fopen($r_path.$path, "r") or die("Failed Opening ".$r_path.$path);
		while (!feof($handle)) $CSS .= fread($handle, 8192);
		fclose($handle);
		return "";
	}
	function FindCSS2($StyleSheet){
		global $CSS;
		$CSS .= $StyleSheet;
		return "";
	}
	function cleanUpHTML($text) {
		$text = ereg_replace(" style=[^>]*","", $text);
		return ($text);
	}
}

$b = 0; $AlNum = "0123456789abcdefghijklmnopqrstuvwxyz"; $Key = ""; while($b<5){ $RNum = rand(0, 36); $Key .= $AlNum[$RNum]; $b++; }
$date = date("U",mktime(date("H"),date("i"),date("s"),date("m"),(date("d")+15),date("Y")));

$KEY = base64_encode(encrypt_data($encnum.".".$date.".".md5(strtolower(trim($Key)))));

$getInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
$getInfo->mysql("SELECT `cust_fname`, `cust_lname`, `cust_cname`, `cust_city`, `cust_state`, `cust_zip`, `cust_website`
	FROM (
		SELECT * FROM 
			(SELECT `cust_customers`.`cust_fname`, `cust_customers`.`cust_lname`, `cust_customers`.`cust_cname`, `cust_customers`.`cust_city`, `cust_customers`.`cust_state`, `cust_customers`.`cust_zip`, `cust_customers`.`cust_website`, `orders_invoice`.`invoice_id`
			FROM `photo_event_images` 
			INNER JOIN `orders_invoice_photo` 
				ON `orders_invoice_photo`.`image_id` = `photo_event_images`.`image_id` 
			INNER JOIN `photo_event_group` 
				ON `photo_event_group`.`group_id` = `photo_event_images`.`group_id` 
			INNER JOIN `photo_event` 
				ON `photo_event`.`event_id` = `photo_event_group`.`event_id` 
			INNER JOIN `orders_invoice` 
				ON `orders_invoice`.`invoice_id` = `orders_invoice_photo`.`invoice_id` 
			INNER JOIN `cust_customers` 
				ON `cust_customers`.`cust_id` = `photo_event`.`cust_id` 
			WHERE `orders_invoice`.`invoice_enc` = '$encnum'
			GROUP BY `orders_invoice`.`invoice_id`) AS `DerivedTable1`
	UNION 
		SELECT * FROM 
			(SELECT `cust_customers`.`cust_fname`, `cust_customers`.`cust_lname`, `cust_customers`.`cust_cname`, `cust_customers`.`cust_city`, `cust_customers`.`cust_state`, `cust_customers`.`cust_zip`, `cust_customers`.`cust_website`, `orders_invoice`.`invoice_id`
			FROM `photo_event_images` 
			INNER JOIN `orders_invoice_border` 
				ON `orders_invoice_border`.`image_id` = `photo_event_images`.`image_id` 
			INNER JOIN `photo_event_group` 
				ON `photo_event_group`.`group_id` = `photo_event_images`.`group_id` 
			INNER JOIN `photo_event` 
				ON `photo_event`.`event_id` = `photo_event_group`.`event_id` 
			INNER JOIN `orders_invoice` 
				ON `orders_invoice`.`invoice_id` = `orders_invoice_border`.`invoice_id` 
			INNER JOIN `cust_customers` 
				ON `cust_customers`.`cust_id` = `photo_event`.`cust_id` 
			WHERE `orders_invoice`.`invoice_enc` = '$encnum'
			GROUP BY `orders_invoice`.`invoice_id`) AS `DerivedTable2`
) AS `MainTable`
GROUP BY `invoice_id`;");
$getInfo = $getInfo->Rows();
$name = ($getInfo[0]['cust_cname'] == "") ? $getInfo[0]['cust_fname']." ".$getInfo[0]['cust_lname'] : $getInfo[0]['cust_cname'];
$code = $getInfo[0]['event_num'];
$address = (strlen(trim($getInfo[0]['cust_city'])) > 0)?$getInfo[0]['cust_city']." ".$getInfo[0]['cust_state']." ".$getInfo[0]['cust_zip']:'';
$website = (strlen(trim($getInfo[0]['cust_website'])) > 0)?$getInfo[0]['cust_website']:'';

$DownloadLink = "http://www.proimagesoftware.com/downloader.php?invoice=".$KEY;
ob_start(); ?>

<p>Thank you for purchasing digital files from photographer. </p>
<p>At your leisure you can <a href="<? echo $DownloadLink; ?>">download
  your digital files here.</a></p>
<p>Please enter your key code is <strong><? echo $Key; ?></strong></p>
<p>Please note you have 15 days to download the files, should you encounter problems you may revisit this link to re-download
 your files. </p>
<p>If you need further assistance please contact us at <a href="mailto:support@proimagesoftware.com">support@proimagesoftware.com</a></p>
<p>Sincerely Photographer.</p>
<br clear="all" />


<? 
$text = ob_get_contents();
ob_end_clean();

ob_start();
include($r_path.'Templates/Photographer/digitaldownload.php');
$msg = ob_get_contents();
ob_end_clean();

$Time = time();

$msg = str_replace('[Name]',$row_get_bill['cust_bill_fname']." ".$row_get_bill['cust_bill_lname'],$msg);
$msg = str_replace('[Title]','Your Digital Download',$msg);
$msg = str_replace('[Text]',$text,$msg);
$msg = str_replace('[Photographer]',$name,$msg);
$msg = str_replace('[Address]',((strlen(trim($address))>0)?'<br />'.$address:''),$msg);
$msg = str_replace('[Website]',((strlen(trim($website))>0)?'<br /><a href="'.$website.'" title="'.$website.'">'.$website.'</a>':''),$msg);
$msg = str_replace('[Link]',$DownloadLink,$msg);
$msg = str_replace('[Link2]',$DownloadLink,$msg);

$Pattern = array();
$Pattern[] = '/<link\s[^>]*href=(["\']??)([^"\'>]*?)\\1[^>]*rel="stylesheet"(.*)>/eiU';
$Pattern[] = '@<style[^>]*?>.*?</style>@esiU';

$CSS = "";
$msg = preg_replace($Pattern[0],"FindCSS('$2')",$msg);
$msg = preg_replace($Pattern[1],"FindCSS2('$0')",$msg);

$InlineHTML = new Emogrifier();
$InlineHTML -> setHTML($msg);
$InlineHTML -> setCSS($CSS);

$msg = removeSpecial($msg);

$msg = $InlineHTML -> emogrify();

//$config = array('indent' => TRUE,'wrap' => 200);
//$tidy = tidy_parse_string($msg, $config, 'UTF8');
//$tidy->cleanRepair();
//$msg = $tidy;
$msg = clean_html_code($msg);

while(strpos("\r",$msg) !== false || strpos("\n",$msg) !== false || strpos("\r\n",$msg) !== false || strpos("\n\r",$msg) !== false ){
	$msg = trim(str_replace(array("\r","\n","\r\n","\n\r"),"",$msg));
}

$msg = preg_replace("/ +/", " ", $msg);
$msg = str_replace('href= "', 'href="', $msg);
$msg = str_replace('src="/','src="http://'.$_SERVER['HTTP_HOST'].'/',$msg);
$msg = str_replace('url(/','url(http://'.$_SERVER['HTTP_HOST'].'/',$msg);

echo $msg;
?>
