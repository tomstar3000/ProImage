<?
// ----------------------------------------------------------------------------
// ----------------          Event Marketing         --------------------------
// ----------------------------------------------------------------------------
/*
$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-1; $r_path = ""; for($n=0;$n<$count;$n++) $r_path .= "../";
define('Allow Scripts',true);
require_once($r_path.'scripts/cart/ssl_paths.php'); 
require_once($r_path.'scripts/fnct_format_phone.php');
require_once($r_path.'Connections/cp_connection.php');
require_once($r_path.'scripts/fnct_sql_processor.php');
$Promo = 'y';
$Event_id = 1;
$Email = "development@proimagesoftware.com";
$Photographer = "Chad Serpan";
$PhotoEmail = "development@proimagesoftware.com";
*/
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
if($Promo == 'y'){
	if (strtoupper(substr(PHP_OS,0,3))=='WIN') $eol="\r\n"; 
	else if (strtoupper(substr(PHP_OS,0,3))=='MAC')	$eol="\r"; 
	else $eol="\n";
	
	require_once $r_path.'scripts/fnct_send_email.php';
	require_once $r_path.'scripts/fnct_clean_entry.php';
	require_once $r_path.'scripts/emogrifier.php';
	require_once $r_path.'scripts/fnct_phpmailer.php';
	require_once $r_path.'scripts/fnct_holidays.php';

	$Holidays = new CalcHoliday;
	$BlackF = $Holidays->findHoliday('Black Friday',date("Y"));
	$discdate = date("Y-m-d");
	
	$getExp = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
	$getExp->mysql("SELECT `photo_evnt_mrkt`.`event_mrk_id`, `photo_evnt_mrkt`.`event_mrk_guestbook_text`, `photo_event`.`event_id`, `photo_event`.`event_name`, `photo_event`.`event_num`, `photo_event`.`event_mrk_codes`, `cust_customers`.`cust_handle`, `cust_customers`.`cust_fname`, `cust_customers`.`cust_lname`, `cust_customers`.`cust_email`, `cust_customers`.`cust_city`, `cust_customers`.`cust_state`, `cust_customers`.`cust_zip`, `cust_customers`.`cust_website`, `cust_customers`.`cust_thumb`, `photo_event_images`.`image_tiny` , `photo_event_images`.`image_folder`,
	ABS(TO_DAYS(`photo_event`.`event_end`) - TO_DAYS(NOW())) AS `EndToDay`, 
	ABS(TO_DAYS(`photo_event`.`event_date`) - TO_DAYS(NOW())) AS `StartToDay`
	FROM `photo_evnt_mrkt` 
	INNER JOIN `photo_event` 
		ON ( `photo_event`.`event_mrk_ids` LIKE CONCAT('}',`photo_evnt_mrkt`.`event_mrk_id`,'{')
				OR `photo_event`.`event_mrk_ids` LIKE CONCAT('}',`photo_evnt_mrkt`.`event_mrk_id`,'[+]%')
				OR `photo_event`.`event_mrk_ids` LIKE CONCAT('%[+]',`photo_evnt_mrkt`.`event_mrk_id`,'[+]%')
				OR `photo_event`.`event_mrk_ids` LIKE CONCAT('%[+]',`photo_evnt_mrkt`.`event_mrk_id`,'{') )
	INNER JOIN `prod_discount_codes`
		ON (`prod_discount_codes`.`evnt_mrk_id`  = `photo_evnt_mrkt`.`event_mrk_id`
				AND 
					 (`photo_event`.`event_mrk_codes` LIKE CONCAT('}',`prod_discount_codes`.`disc_id`,'{')
				OR `photo_event`.`event_mrk_codes` LIKE CONCAT('}',`prod_discount_codes`.`disc_id`,'[+]%')
				OR `photo_event`.`event_mrk_codes` LIKE CONCAT('%[+]',`prod_discount_codes`.`disc_id`,'[+]%')
				OR `photo_event`.`event_mrk_codes` LIKE CONCAT('%[+]',`prod_discount_codes`.`disc_id`,'{') ))
	INNER JOIN `cust_customers` 
		ON `cust_customers`.`cust_id` = `photo_event`.`cust_id`
	LEFT JOIN `photo_event_images` 
		ON (`photo_event_images`.`image_id` = `photo_event`.`event_image`
			OR `photo_event_images`.`image_id` IS NULL)
	WHERE `photo_evnt_mrkt`.`event_mrk_use` = 'y' 
		AND `event_use` = 'y'
		AND `photo_evnt_mrkt`.`event_mrk_guestbook` = 'y'
		AND `photo_event`.`event_id` = '$Event_id'
		GROUP BY `photo_evnt_mrkt`.`event_mrk_id`;");
	
	if($getExp->TotalRows() > 0){
		foreach($getExp->Rows() as $r){
			$EId = $r['event_id'];
			$Handle = $r['cust_handle'];
			$Event = $r['event_num'];
			$address = (strlen(trim($r['cust_city'])) > 0)?$r['cust_city']." ".$r['cust_state']." ".$r['cust_zip']:'';
			$website = (strlen(trim($r['cust_website'])) > 0)?$r['cust_website']:'';
			$Photographer = ($r['cust_cname'] == "") ? $r['cust_fname']." ".$r['cust_lname'] : $r['cust_cname'];
			$EName = $r['event_name'];
			$PhotoEmail = $r['cust_email'];
			$MrkCodes = str_replace(array("}","{"),'',explode("[+]",$r['event_mrk_codes']));
			$Desc = $r['event_mrk_guestbook_text'];
			
			$BioImage = "Logo.jpg";
			if(is_file($r_path."photographers/".$Handle."/".$BioImage)){
				list($BioWidth, $BioHeight) = getimagesize($r_path."photographers/".$Handle."/".$BioImage);
				$BioImage = "photographers/".$Handle."/".$BioImage;
				if($BioWidth > 700){
					$Ration = 700/$BioWidth;
					$BioWidth = 700;
					$BioHeight = $BioHeight*$Ration;
				}
			} else {
				$BioImage = false;
			}
			$IName = $r['image_tiny'];
			$Image = $r_path.substr($r['image_folder'],0,-11)."Thumbnails/".$IName;
			
			if($r['event_image'] != ""){
				$IName = "Notification";
				$TempName = tempnam("/tmp", $IName);
				$THandle = fopen($TempName, "w");
				fwrite($THandle, base64_decode($r['event_image']));
				fclose($THandle);
		
				$Image = $TempName;
			} else if(!is_file($Image)){
				$Image = false;
				$IName = false;
			}							
			$title = mb_convert_encoding($EName, "UTF-8", 'HTML-ENTITIES');
			
			ob_start();
			include($r_path.'Templates/Photographer/index.php');
			$msg = ob_get_contents();
			ob_end_clean();
			
			$Time = time();
			
			if($Image !== false) {
				list($width, $height) = getimagesize($Image);
				if($width > $height){ $Perc = 242/$width; $height = round($height*$Perc); $width = 242; }
				else { $Perc = 242/$height; $width = round($width*$Perc); $height = 242; }
				$msg = str_replace('[EmbedImg]','<img class="img" src="cid:'.$Time.'" width="'.$width.'" height="'.$height.'" alt="'.$handle.'" vspace="0" hspace="0" alt="'.$title.'" style="border: 3px solid #c89441;"><br clear="all" />
				',$msg);
			} else { $msg = str_replace('[EmbedImg]','',$msg); }
			
			$msg = str_replace('[Title]',$title,$msg);
			$msg = str_replace('[Unsubscribe]',"http://www.proimagesoftware.com/unsubscribe.php?token=".substr(time(),0,5).$EId.substr(time(),5),$msg);
			$msg = str_replace('[Text]','
			<p>'.ereg_replace(array("^
			<p>","</p>
			$"),'',sanatizeentry($Desc,true)).'
			</p>
			',$msg);
			$msg = str_replace('[Photographer]',$Photographer,$msg);
			$msg = str_replace('[Address]',((strlen(trim($address))>0)?'<br />
			'.$address:''),$msg);
			$msg = str_replace('[Website]',((strlen(trim($website))>0)?'<br />
			<a href="'.$website.'" title="'.$website.'">'.$website.'</a>':''),$msg);							
			
			$getMrk = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
			$getMrk->mysql("SELECT `prod_discount_codes`.*, `prod_products`.`prod_name`
					FROM `prod_discount_codes`
					LEFT JOIN `prod_products`
						ON (`prod_products`.`prod_id` = `prod_discount_codes`.`prod_id`
							OR `prod_products`.`prod_id` IS NULL) 
					WHERE `evnt_mrk_id` = '".$r['event_mrk_id']."' 
						AND `disc_use` = 'y'
					GROUP BY `disc_id`;");
			$total_get_mrk = $getMrk->TotalRows();
			$Coupons = '';
			if($total_get_mrk > 0){ $Coupons = '<tr class="Coupons"><td align="center"><table border="0" cellpadding="0" cellspacing="0"><tr>';
				$a = 0; $b = 0;
				foreach($getMrk->Rows() as $mrk){
					if(in_array($mrk['disc_id'],$MrkCodes)){
						if(strlen(trim($mrk['disc_percent']))>0 && intval($mrk['disc_percent'])>0){
							$CouponName = "<strong>".$mrk['disc_percent']."% Off</strong>";
						} else if(strlen(trim($mrk['disc_price']))>0 && intval($mrk['disc_price'])>0){
							$CouponName = "$<strong>".number_format($mrk['disc_price'],2,".",",")." Off</strong>";
						} else if(strlen(trim($mrk['disc_item']))>0 && intval($mrk['disc_item']) > 0 && strlen(trim($mrk['disc_for']))>0 && intval($mrk['disc_for']) > 0){
							$CouponName = "<strong>".$mrk['disc_item']." for ".$mrk['disc_for']."</strong>";
						}
						if(strlen(trim($mrk['prod_name']))>0){
							$CouponName .= "<br />".$mrk['prod_name'];
						} else {
							$CouponName .= "<br />your order";
						}
						
						$Coupons .= '<td class="Coupon'.(($b==0)?'1':'2').'" valign="top"><p class="Large"><a href="[Link]" title="Redeem"><img src="/images/spacer.gif" alt="Redeem" width="125" height="50" hspace="0" vspace="0" border="0" align="right"></a>'.$CouponName.'</p>
									<p class="Small">Can be found in "discounts" when viewing the event. Type: '.$mrk['disc_code'].' for your discount code.</p></td>';
						unset($CouponName);
						$a++;
						if($a >= 2){ $b++; $a = 0; $Coupons .= '</tr><tr>'; }
				} }
				if($a < 2){ $Coupons .= '<td>&nbsp;</td>'; }
				$Coupons .= '</tr></table></td></tr>';
			}
			
			$msg = str_replace('[Coupons]',$Coupons,$msg);
			$msg = str_replace('[Name]',$Email,$msg);
			$msg = str_replace(array('[Link]','%5BLink%5D'),"http://www.proimagesoftware.com/photo_viewer.php?Photographer=".$handle."&code=".$code."&email=".$v."&full=true",$msg);
			$msg = str_replace(array('[Link2]','%5BLink2%5D'),wordwrap("http://www.proimagesoftware.com/photo_viewer.php?Photographer=".$handle."&code=".$code."&email=".$v."&full=true", 100, $eol),$msg);
			
			$msg = str_replace('[Text]','<p>'.ereg_replace(array("^<p>","</p>$"),'',sanatizeentry($Desc,true)).'</p>',$msg);
		
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
			
			while(strpos("\r",$msg) !== false || strpos("\n",$msg) !== false || strpos("\r\n",$msg) !== false || strpos("\n\r",$msg) !== false ){
				$msg = trim(str_replace(array("\r","\n","\r\n","\n\r"),"",$msg));
			}
			
			$msg = preg_replace("/ +/", " ", $msg);
			$msg = str_replace('href= "', 'href="', $msg);
			$msg = str_replace('src="/','src="http://'.$_SERVER['HTTP_HOST'].'/',$msg);
			$msg = str_replace('url(/','url(http://'.$_SERVER['HTTP_HOST'].'/',$msg);
			
			//$config = array('indent' => TRUE,'wrap' => 200);
			//$tidy = tidy_parse_string($msg, $config, 'UTF8');
			//$tidy->cleanRepair();
			//$msg = $tidy;
			$msg = clean_html_code($msg);
			
			$mail = new PHPMailer();
			$mail -> IsSendMail();
			$mail -> Host = "smtp.proimagesoftware.com";
			$mail -> IsHTML(true);
			$mail -> Sender = "info@proimagesoftware.com";
			$mail -> Hostname = "proimagesoftware.com";
			$mail -> From = $PhotoEmail;
			$mail -> FromName = $Photographer;
			$mail -> ReplyTo = $PhotoEmail;
			$mail -> AddAddress($Email);
			$mail -> Subject = $title;
			if($Image !== false) $mail -> AddEmbeddedImage($Image, $Time);
			$mail -> Body = $msg;
			$mail -> Send();
				
			unset($mail);
			unset($Pattern);							
		}
		if($r['event_image'] != "") unlink($TempName);
	}
}
// ----------------------------------------------------------------------------
// ----------------          Event Marketing         --------------------------
// ----------------------------------------------------------------------------
?>