<? $count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2; $r_path = ""; for($n=0;$n<$count;$n++)$r_path .= "../";
define("PhotoExpress Pro", true);
define('Allow Scripts',true);
include $r_path.'security.php';
require_once($r_path.'../Connections/cp_connection.php');
require_once($r_path.'scripts/fnct_sql_processor.php');
require_once($r_path.'scripts/fnct_clean_entry.php');
require_once($r_path.'scripts/fnct_format_date.php');
require_once $r_path.'scripts/emogrifier.php';
function FindCSS($CSSLink){
	global $CSS, $Template, $r_path;
	$path_parts = pathinfo($CSSLink);
	$path = $path_parts['basename'];
	$path_parts = pathinfo($Template);
	$path = $path_parts['dirname']."/".$path;
	$Handle = fopen($r_path.$path, "r") or die("Failed Opening ".$r_path.$path);
	while (!feof($Handle)) $CSS .= fread($Handle, 8192);
	fclose($Handle);
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
if (strtoupper(substr(PHP_OS,0,3))=='WIN') $eol="\r\n"; else if (strtoupper(substr(PHP_OS,0,3))=='MAC')	$eol="\r"; else $eol="\n";

//?id=1&mrkt=1&vals=
$CustId = clean_variable($_GET['id'],true);
$MrktId = clean_variable($_GET['mrkt'],true);
$Vals		= explode(".",clean_variable($_GET['vals'],true));

$mySQL = "SELECT `photo_event`.`event_id`, `photo_event`.`event_use`, `photo_event`.`event_rereleased`, `photo_event`.`event_name`, `photo_event`.`event_num`, `photo_event`.`event_date`, `photo_event`.`event_end`, `photo_event`.`event_mrk_codes`, `cust_customers`.`cust_handle`, `cust_customers`.`cust_fname`, `cust_customers`.`cust_lname`, `cust_customers`.`cust_email`, `cust_customers`.`cust_city`, `cust_customers`.`cust_state`, `cust_customers`.`cust_zip`, `cust_customers`.`cust_website`, `cust_customers`.`cust_thumb`, `photo_event_images`.`image_tiny` , `photo_event_images`.`image_folder`, `photo_evnt_mrkt`.*, 
ABS(TO_DAYS(`photo_event`.`event_end`) - TO_DAYS(NOW())) AS `EndToDay`, 
ABS(TO_DAYS(`photo_event`.`event_date`) - TO_DAYS(NOW())) AS `StartToDay`,
( ABS(TO_DAYS(`photo_event`.`event_date`) - TO_DAYS(NOW())) - (`photo_evnt_mrkt`.`event_mrk_days_after_start` - `photo_evnt_mrkt`.`event_mrk_end_start_promo`))
 AS `EndPromoStart`
	FROM `photo_evnt_mrkt` 
	INNER JOIN `photo_event` 
		ON ( `photo_event`.`event_mrk_ids` LIKE CONCAT('}',`photo_evnt_mrkt`.`event_mrk_id`,'{')
				OR `photo_event`.`event_mrk_ids` LIKE CONCAT('}',`photo_evnt_mrkt`.`event_mrk_id`,'[+]%')
				OR `photo_event`.`event_mrk_ids` LIKE CONCAT('%[+]',`photo_evnt_mrkt`.`event_mrk_id`,'[+]%')
				OR `photo_event`.`event_mrk_ids` LIKE CONCAT('%[+]',`photo_evnt_mrkt`.`event_mrk_id`,'{') )
	INNER JOIN `cust_customers` 
		ON `cust_customers`.`cust_id` = `photo_event`.`cust_id` 
	LEFT JOIN `photo_event_images` 
		ON (`photo_event_images`.`image_id` = `photo_event`.`event_image`
			OR `photo_event_images`.`image_id` IS NULL)
	INNER JOIN `photo_quest_book` 
		ON `photo_quest_book`.`event_id` = `photo_event`.`event_id` 
	WHERE `event_use` = 'y'
		AND `event_del` = 'n'
		AND `photo_evnt_mrkt`.`event_mrk_id` = '".$MrktId."'
		AND `cust_customers`.`cust_id` = '".$CustId."'
	GROUP BY `photo_event`.`event_id`, `photo_evnt_mrkt`.`event_mrk_id`
	LIMIT 0,1;";
	
$getMrktEvent = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
$getMrktEvent->mysql($mySQL);
if($getMrktEvent->TotalRows() > 0){ $Row=$getMrktEvent->Rows();
	$EId = $Row[0]['event_id'];
	$Handle = $Row[0]['cust_handle'];
	$Event = $Row[0]['event_num'];
	$address = (strlen(trim($Row[0]['cust_city'])) > 0)?$Row[0]['cust_city']." ".$Row[0]['cust_state']." ".$Row[0]['cust_zip']:'';
	$website = (strlen(trim($Row[0]['cust_website'])) > 0)?$Row[0]['cust_website']:'';
	$Photographer = ($Row[0]['cust_cname'] == "") ? $Row[0]['cust_fname']." ".$Row[0]['cust_lname'] : $Row[0]['cust_cname'];
	$EName = $Row[0]['event_name'];
	$PhotoEmail = $Row[0]['cust_email'];
	$MrkCodes = str_replace(array("}","{"),'',explode("[+]",$Row[0]['event_mrk_codes']));
	$Desc = $Row[0]['event_mrk_text'];
	$EvntDate = date("Y-m-d", mktime(0,0,0,substr($Row[0]['event_date'],5,2),substr($Row[0]['event_date'],8,2),substr($Row[0]['event_date'],0,4)));
	$EvntEnd = date("Y-m-d", mktime(0,0,0,substr($Row[0]['event_end'],5,2),substr($Row[0]['event_end'],8,2),substr($Row[0]['event_end'],0,4)));
		
	$BioImage = "Logo.jpg";
	if(is_file($r_path."../photographers/".$Handle."/".$BioImage)){
		list($BioWidth, $BioHeight) = getimagesize($r_path."../photographers/".$Handle."/".$BioImage);
		$BioImage = "../photographers/".$Handle."/".$BioImage;
		if($BioWidth > 700){
			$Ration = 700/$BioWidth;
			$BioWidth = 700;
			$BioHeight = $BioHeight*$Ration;
		}
	} else {
		$BioImage = false;
	}
	$IName = $Row[0]['image_tiny'];
	$Image = $r_path.substr($Row[0]['image_folder'],0,-11)."Thumbnails/".$IName;
	
	if($Row[0]['event_image'] != ""){
		$IName = "Notification";
		$TempName = tempnam("/tmp", $IName);
		$THandle = fopen($TempName, "w");
		fwrite($THandle, base64_decode($Row[0]['event_image']));
		fclose($THandle);

		$Image = $TempName;
	} else if(!is_file($Image)){
		$Image = false;
		$IName = false;
	}	
	$title = mb_convert_encoding($EName, "UTF-8", 'HTML-ENTITIES');
	
	ob_start();
	include($r_path.'../Templates/Photographer/index.php');
	$msg = ob_get_contents();
	ob_end_clean();
	
	$Time = time();
	
	if($Image !== false) {
		list($width, $height) = getimagesize($Image);
		if($width > $height){ $Perc = 242/$width; $height = round($height*$Perc); $width = 242; }
		else { $Perc = 242/$height; $width = round($width*$Perc); $height = 242; }
		$msg = str_replace('[EmbedImg]','<img class="img" src="cid:'.$Time.'" width="'.$width.'" height="'.$height.'" alt="'.$Handle.'" vspace="0" hspace="0" alt="'.$title.'" style="border: 3px solid #c89441;"><br clear="all" />',$msg);
	} else { $msg = str_replace('[EmbedImg]','',$msg); }
	
	$msg = str_replace('[Title]',$title,$msg);
	$msg = str_replace('[Unsubscribe]',"http://www.proimagesoftware.com/unsubscribe.php?token=".substr(time(),0,5).$EId.substr(time(),5),$msg);
	$msg = str_replace('[Photographer]',$Photographer,$msg);
	$msg = str_replace('[Address]',((strlen(trim($address))>0)?'<br />'.$address:''),$msg);
	$msg = str_replace('[Website]',((strlen(trim($website))>0)?'<br /><a href="'.$website.'" title="'.$website.'">'.$website.'</a>':''),$msg);
		
	$mrkMySQL = "SELECT `prod_discount_codes`.*, `prod_products`.`prod_name`
	FROM `prod_discount_codes`
	LEFT JOIN `prod_products`
		ON (`prod_products`.`prod_id` = `prod_discount_codes`.`prod_id`
			OR `prod_products`.`prod_id` IS NULL)
	WHERE `evnt_mrk_id` = '".$MrktId."' 
		AND `disc_use` = 'y'
	GROUP BY `disc_id`;";
	$getMrktCpns = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
	$getMrktCpns->mysql($mrkMySQL);

	$Coupons = '';
	if($getMrktCpns->TotalRows() > 0){ $Coupons = '<tr class="Coupons"><td align="center"><table border="0" cellpadding="0" cellspacing="0"><tr>';
		$a = 0; $b = 0;
		foreach($getMrktCpns->Rows() as $record){
			if(in_array($record['disc_id'],$Vals)){ 
				if(strlen(trim($record['disc_percent']))>0 && intval($record['disc_percent'])>0){
					$CouponName = "<strong>".$record['disc_percent']."% Off</strong>";
				} else if(strlen(trim($record['disc_price']))>0 && intval($record['disc_price'])>0){
					$CouponName = "$<strong>".number_format($record['disc_price'],2,".",",")." Off</strong>";
				} else if(strlen(trim($record['disc_item']))>0 && intval($record['disc_item']) > 0 && strlen(trim($record['disc_for']))>0 && intval($record['disc_for']) > 0){
					$CouponName = "<strong>".$record['disc_item']." for ".$record['disc_for']."</strong>";
				}
				if(strlen(trim($record['prod_name']))>0){
					$CouponName .= "<br />".$record['prod_name'];
				} else {
					$CouponName .= "<br />your order";
				}
				
				$Coupons .= '<td class="Coupon'.(($b==0)?'1':'2').'" valign="top"><p class="Large"><a href="[Link]" title="Redeem"><img src="/images/spacer.gif" alt="Redeem" width="125" height="50" hspace="0" vspace="0" border="0" align="right"></a>'.$CouponName.'</p>
							<p class="Small">Can be found in "discounts" when viewing the event. Type: '.$record['disc_code'].' for your discount code.</p></td>';
				unset($CouponName);
				$a++;
				if($a >= 2){ $b++; $a = 0; $Coupons .= '</tr><tr>'; }
		} }
		if($a < 2){ $Coupons .= '<td>&nbsp;</td>'; }
		$Coupons .= '</tr></table></td></tr>';
	}
	
	$EmailText = array();
	if(strlen(trim($Row[0]['event_mrk_early_text'])) > 0){
		$EmailText[] = $Row[0]['event_mrk_early_text'];
	}
	if(strlen(trim($Row[0]['event_mrk_start_text'])) > 0){
		$EmailText[] = $Row[0]['event_mrk_start_text'];
	}
	if(strlen(trim($Row[0]['event_mrk_starts_text'])) > 0){
		$EmailText[] = $Row[0]['event_mrk_starts_text'];
	}
	if(strlen(trim($Row[0]['event_mrk_end_start_promo_text'])) > 0){
		$EmailText[] = $Row[0]['event_mrk_end_start_promo_text'];
	}
	if(strlen(trim($Row[0]['event_mrk_end_text'])) > 0){
		$EmailText[] = $Row[0]['event_mrk_end_text'];
	}
	if(strlen(trim($Row[0]['event_mrk_end_2_text'])) > 0){
		$EmailText[] = $Row[0]['event_mrk_end_2_text'];
	}
	if(strlen(trim($Row[0]['event_mrk_expire_text'])) > 0){
		$EmailText[] = $Row[0]['event_mrk_expire_text'];
	}
	if(strlen(trim($Row[0]['event_mrk_starts_text'])) > 0){
		$EmailText[] = $Row[0]['event_mrk_starts_text'];
	}
	if(strlen(trim($Row[0]['event_mrk_black_friday_text'])) > 0){
		$EmailText[] = $Row[0]['event_mrk_black_friday_text'];
	}
	if(strlen(trim($Row[0]['evnt_mrk_dec_10_text'])) > 0){
		$EmailText[] = $Row[0]['evnt_mrk_dec_10_text'];
	}
	if(strlen(trim($Row[0]['event_mrk_re_release_text'])) > 0){
		$EmailText[] = $Row[0]['event_mrk_re_release_text'];
	}
	if(strlen(trim($Row[0]['event_mrk_guestbook_text'])) > 0){
		$EmailText[] = $Row[0]['event_mrk_guestbook_text'];
	}
	if($Row[0]['event_mrk_end_start_promo'] != '0'){
		$EmailText[] = $Row[0]['event_mrk_end_start_promo_text'];
	}
	$msg = str_replace('[Coupons]',$Coupons,$msg);
	$msg = str_replace('[Name]',$PhotoEmail,$msg);
	$msg = str_replace(array('[Link]','%5BLink%5D'),"http://www.proimagesoftware.com/photo_viewer.php?Photographer=".$Handle."&code=".$Event."&email=".$PhotoEmail."&full=true",$msg);
	$msg = str_replace(array('[Link2]','%5BLink2%5D'),wordwrap("http://www.proimagesoftware.com/photo_viewer.php?Photographer=".$Handle."&code=".$Event."&email=".$PhotoEmail."&full=true", 100, $eol),$msg);
	
	$Desc = implode("<br />",$EmailText);
	
	$msg = str_replace('[Text]','<p>'.ereg_replace(array("^<p>","</p>$"),'',sanatizeentry($Desc,true)).'</p>',$msg);

	$Pattern = array();
	$Pattern[] = '/<link\s[^>]*href=(["\']??)([^"\'>]*?)\\1[^>]*rel="stylesheet"(.*)>/eiU';
	$Pattern[] = '@<style[^>]*?>.*?</style>@esiU';
	/*
	$CSS = "";
	$msg = preg_replace($Pattern[0],"FindCSS('$2')",$msg);
	$msg = preg_replace($Pattern[1],"FindCSS2('$0')",$msg);
	
	$InlineHTML = new Emogrifier();
	$InlineHTML -> setHTML($msg);
	$InlineHTML -> setCSS($CSS);

	$msg = removeSpecial($msg);
	
	$msg = $InlineHTML -> emogrify();
	*/
	while(strpos("\r",$msg) !== false || strpos("\n",$msg) !== false || strpos("\r\n",$msg) !== false || strpos("\n\r",$msg) !== false ){
		$msg = trim(str_replace(array("\r","\n","\r\n","\n\r"),"",$msg));
	}
	
	$msg = preg_replace("/ +/", " ", $msg);
	$msg = str_replace('href= "', 'href="', $msg);
	$msg = str_replace('src="/','src="http://'.$_SERVER['HTTP_HOST'].'/',$msg);
	$msg = str_replace('url(/','url(http://'.$_SERVER['HTTP_HOST'].'/',$msg);

	$msg = clean_html_code($msg);

	echo $msg;
} ?>