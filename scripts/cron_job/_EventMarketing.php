<? $count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-1; $r_path = ""; for($n=0;$n<$count;$n++)$r_path .= "../";
define ("Allow Scripts", true);
define ("CronJob", true);

set_time_limit(0);
ini_set('max_execution_time',600);

$is_creditcard = true;
$is_gateway = "Authorize_AIM";
$is_live = true;
$is_error = false;
$is_process = true;
$is_capture = "AUTH_CAPTURE";
$is_method = "CC";
$approval = false;
$Error = false;
if (strtoupper(substr(PHP_OS,0,3))=='WIN') $eol="\r\n"; else if (strtoupper(substr(PHP_OS,0,3))=='MAC')	$eol="\r"; else $eol="\n";

//$r_path .= "var/www/www.proimagesoftware.com/";
$r_path = "/srv/proimage/current/";
require_once $r_path.'scripts/cart/encrypt.php';
require_once $r_path.'scripts/fnct_send_email.php';
require_once $r_path.'scripts/fnct_clean_entry.php';
require_once $r_path.'scripts/emogrifier.php';
require_once $r_path.'scripts/fnct_phpmailer.php';
require_once $r_path.'scripts/fnct_holidays.php';
require_once $r_path.'scripts/fnct_ImgeProcessor.php';
require_once $r_path.'Connections/cp_connection.php';

mysql_select_db($database_cp_connection, $cp_connection);
require_once($r_path.'control_panel/scripts/query_change_list.php');
$today = date("Y-m-d H:i:s", mktime(0,0,0,date("m"),date("d"),date("Y")));
$tendate = date("Y-m-d H:i:s", mktime(0,0,0,date("m"),date("d")+10,date("Y")));
$thirtydate = date("Y-m-d H:i:s", mktime(0,0,0,date("m")-1,date("d")-1,date("Y")));
$thirtydate1 = date("Y-m-d H:i:s", mktime(0,0,0,date("m")-1,date("d"),date("Y")));
$date = date("Y-m-d H:i:s", mktime(12,0,0,date("m"),date("d"),date("Y")));
$date1 = date("Y-m-d H:i:s", mktime(12,0,0,date("m"),(date("d")-1),date("Y"))); // Date of expired events
$finaldate = date("Y-m-d H:i:s", mktime(12,0,0,date("m"),(date("d")+15),date("Y")));
$diedate = array();

function FindCSS($CSSLink){
	global $CSS, $Template, $r_path;
	$path_parts = pathinfo($CSSLink);
	//$path = $path_parts['basename'];
	//$path_parts = pathinfo($Template);
	$path = $path_parts['dirname']."/".$path_parts['basename'];
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

echo "Event Marketing<br />";

$Holidays = new CalcHoliday;
$Hol = $Holidays->isHoliday(date("Y-m-d",mktime(0,0,0,date("m"),(date("d")+1),date("Y"))));
$BlackF = $Holidays->findHoliday('Black Friday',date("Y"));


$query_get_exp = "SELECT `photo_event`.`event_id`, `photo_event`.`event_use`, `photo_event`.`event_rereleased`, `photo_event`.`event_name`, `photo_event`.`event_num`, `photo_event`.`event_date`, `photo_event`.`event_end`, `photo_event`.`event_mrk_codes`, `cust_customers`.`cust_handle`, `cust_customers`.`cust_cname`, `cust_customers`.`cust_fname`, `cust_customers`.`cust_lname`, `cust_customers`.`cust_email`, `cust_customers`.`cust_city`, `cust_customers`.`cust_state`, `cust_customers`.`cust_zip`, `cust_customers`.`cust_website`, `cust_customers`.`cust_thumb`, `photo_event_images`.`image_tiny` , `photo_event_images`.`image_folder`, `photo_evnt_mrkt`.*, 
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
	WHERE `photo_evnt_mrkt`.`event_mrk_use` = 'y' 
		AND `event_use` = 'y'
		AND `event_del` = 'n'
		AND (
					(
						(`photo_evnt_mrkt`.`event_mrk_days_early_purchase` != '0' 
							AND `photo_evnt_mrkt`.`event_mrk_days_early_purchase` >= ABS(TO_DAYS(`photo_event`.`event_date`) - TO_DAYS(NOW()))
						)
					OR (`photo_evnt_mrkt`.`event_mrk_days_after_start` != '0'
							AND `photo_evnt_mrkt`.`event_mrk_days_after_start` >= ABS(TO_DAYS(`photo_event`.`event_date`) - TO_DAYS(NOW()))
							AND `photo_evnt_mrkt`.`event_mrk_days_cart_expire` = '0'
							AND NOW() >= `photo_event`.`event_date`
						)
					OR (`photo_evnt_mrkt`.`event_mrk_days_after_start` != '0'
							AND `photo_evnt_mrkt`.`event_mrk_days_cart_expire` != '0'
							AND ABS(TO_DAYS(`photo_event`.`event_date`) - TO_DAYS(NOW()) + `photo_evnt_mrkt`.`event_mrk_days_after_start`) <= `photo_evnt_mrkt`.`event_mrk_days_cart_expire`
							AND NOW() >= `photo_event`.`event_date`
						)
					OR (`photo_evnt_mrkt`.`event_mrk_days_before_end` != '0' 
							AND `photo_evnt_mrkt`.`event_mrk_days_before_end` >= ABS(TO_DAYS(`photo_event`.`event_end`) - TO_DAYS(NOW()))
						)
					OR (`photo_evnt_mrkt`.`event_mrk_days_before_end_2` != '0' 
							AND `photo_evnt_mrkt`.`event_mrk_days_before_end_2` >= ABS(TO_DAYS(`photo_event`.`event_end`) - TO_DAYS(NOW()))
						)
					OR (`photo_evnt_mrkt`.`event_mrk_days_cart_expire` != '0' 
							AND `photo_evnt_mrkt`.`event_mrk_days_cart_expire` >= ABS(TO_DAYS(`photo_event`.`event_date`) - TO_DAYS(NOW()))
							AND NOW() <= `photo_event`.`event_date`
						)
					OR (`photo_evnt_mrkt`.`event_mrk_starts` = 'y'
							AND ABS(TO_DAYS(`photo_event`.`event_date`) - TO_DAYS(NOW())) = '0'
							AND (`photo_evnt_mrkt`.`event_mrk_days_after_start` = '0'
								OR ABS(TO_DAYS(`photo_event`.`event_date`) - TO_DAYS(NOW())) <= `photo_evnt_mrkt`.`event_mrk_days_after_start`)
							AND (`photo_evnt_mrkt`.`event_mrk_days_cart_expire` = '0'
								OR ABS(TO_DAYS(`photo_event`.`event_date`) - TO_DAYS(NOW()) + `photo_evnt_mrkt`.`event_mrk_days_after_start`) <= `photo_evnt_mrkt`.`event_mrk_days_cart_expire`)
						)
					)
					".((date("Y-m-d") == $BlackF)?	"OR `photo_evnt_mrkt`.`event_mrk_black_friday` = 'y'":"")."
					".((date("md") == '1210')?			"OR `photo_evnt_mrkt`.`evnt_mrk_dec_10` = 'y'":"")."
					".((date("md") == "1119")?			"OR `photo_evnt_mrkt`.`event_mrk_re_release` = 'y'":"")."
				)
		
	GROUP BY `photo_event`.`event_id`, `photo_evnt_mrkt`.`event_mrk_id`;";
	
$get_exp = mysql_query($query_get_exp, $cp_connection) or die(mysql_error());
$total_get_exp = mysql_num_rows($get_exp);

if($total_get_exp > 0){
	while($row_get_exp = mysql_fetch_assoc($get_exp)){
		
		$EId = $row_get_exp['event_id'];
		echo $EId;
		
		$Handle = $row_get_exp['cust_handle'];
		$Event = $row_get_exp['event_num'];
		$address = (strlen(trim($row_get_exp['cust_city'])) > 0)?$row_get_exp['cust_city']." ".$row_get_exp['cust_state']." ".$row_get_exp['cust_zip']:'';
		$website = (strlen(trim($row_get_exp['cust_website'])) > 0)?$row_get_exp['cust_website']:'';
		$Photographer = ($row_get_exp['cust_cname'] == "") ? $row_get_exp['cust_fname']." ".$row_get_exp['cust_lname'] : $row_get_exp['cust_cname'];
		$EName = $row_get_exp['event_name'];
		$PhotoEmail = $row_get_exp['cust_email'];
		$MrkCodes = str_replace(array("}","{"),'',explode("[+]",$row_get_exp['event_mrk_codes']));
		$Desc = $row_get_exp['event_mrk_text'];
		$EvntDate = date("Y-m-d", mktime(0,0,0,substr($row_get_exp['event_date'],5,2),substr($row_get_exp['event_date'],8,2),substr($row_get_exp['event_date'],0,4)));
		$EvntEnd = date("Y-m-d", mktime(0,0,0,substr($row_get_exp['event_end'],5,2),substr($row_get_exp['event_end'],8,2),substr($row_get_exp['event_end'],0,4)));
		var_dump(" (".date("md")."==1119) && ".$row_get_exp['event_mrk_re_release']." == 'y' && ".$row_get_exp['event_use']." == 'y'");
		if((date("md")=="1119") && $row_get_exp['event_mrk_re_release'] == 'y' && $row_get_exp['event_use'] == 'y'){
			$expDate = date("Y-m-d H:i:s",mktime(0,0,0,1,15,(date("Y")+1)));
			$query_upd_event = "UPDATE `photo_event` SET `event_use` = 'y', `event_rereleased` = 'y', `event_end` = '$expDate' WHERE `event_id` = '$EId';";
			$upd_event = mysql_query($query_upd_event, $cp_connection) or die(mysql_error());
		}
		echo "<br />";
		$BioImage = false;
		$IName = $row_get_exp['image_tiny'];
		$file = $r_path.substr($row_get_exp['image_folder'],0,-11)."Large/".$IName;
		
		$Imager = new ImageProcessor();
		$Imager->SetMaxSize(67108864);
		$Imager->File($file);
		if($Imager->ERROR == false){
			$Imager->Resize(145,145);
			if(intval($rotate) > 0) $Imager->Rotate((intval($rotate)*-1));
			if($color == 'b')				$Imager->Gray();
			else if($color == 's')	$Imager->Sepia();
			$fileType = $Imager->Ext;
	
			$Image = tempnam("/tmp", "FOO");
			
			$Hndl = fopen($Image, "w");
			fwrite($Hndl, $Imager->OutputContents());
			fclose($Hndl);
		} else {
			$IName = false;
			$Image = false;
		}
		
		$query_get_book = "SELECT `email` FROM `photo_quest_book` WHERE `event_id` = '$EId' AND `promotion` = 'y' GROUP BY `email`";
		$get_book = mysql_query($query_get_book, $cp_connection) or die(mysql_error());
		$bcc = array();
		while($row_get_book = mysql_fetch_assoc($get_book))	array_push($bcc,$row_get_book['email']);
		//array_push($bcc,"development@proimagesoftware.com");
		
		echo $Handle." - ".$row_get_exp['event_name']." - ".$Image."<br />";
		
		$title = mb_convert_encoding($EName, "UTF-8", 'HTML-ENTITIES');
		
		ob_start();
		include($r_path.'Templates/Photographer/index.php');
		$msg = ob_get_contents();
		ob_end_clean();
		
		$Time = time();
		
		if($Image !== false) {
			$msg = str_replace('[EmbedImg]','<img class="img" src="cid:'.$Time.'" width="'.$Imager->OrigWidth[0].'" height="'.$Imager->OrigHeight[0].'" alt="'.$Handle.'" vspace="0" hspace="0" alt="'.$title.'" style="border: 3px solid #c89441;"><br clear="all" />',$msg);
		} else { $msg = str_replace('[EmbedImg]','',$msg); }
		
		$msg = str_replace('[Title]',$title,$msg);
		$msg = str_replace('[Unsubscribe]',"http://www.proimagesoftware.com/unsubscribe.php?token=".substr(time(),0,5).$EId.substr(time(),5),$msg);
		$msg = str_replace('[Photographer]',$Photographer,$msg);
		$msg = str_replace('[Address]',((strlen(trim($address))>0)?'<br />'.$address:''),$msg);
		$msg = str_replace('[Website]',((strlen(trim($website))>0)?'<br /><a href="'.$website.'" title="'.$website.'">'.$website.'</a>':''),$msg);
			
		foreach($bcc as $v)	{
			$query_get_mrk = "SELECT `prod_discount_codes`.*, `prod_products`.`prod_name`
		FROM `prod_discount_codes`
		LEFT JOIN `prod_products`
			ON (`prod_products`.`prod_id` = `prod_discount_codes`.`prod_id`
				OR `prod_products`.`prod_id` IS NULL)
		WHERE `evnt_mrk_id` = '".$row_get_exp['event_mrk_id']."' 
			AND `disc_use` = 'y'
		GROUP BY `disc_id`;";

			$get_mrk = mysql_query($query_get_mrk, $cp_connection) or die(mysql_error());
			$total_get_mrk = mysql_num_rows($get_mrk);
			$Coupons = '';
			if($total_get_mrk > 0){ $Coupons = '<tr class="Coupons"><td align="center"><table border="0" cellpadding="0" cellspacing="0"><tr>';
				$a = 0; $b = 0;
				while($row_get_mrk = mysql_fetch_assoc($get_mrk)){
					if(in_array($row_get_mrk['disc_id'],$MrkCodes)){
						if(strlen(trim($row_get_mrk['disc_percent']))>0 && intval($row_get_mrk['disc_percent'])>0){
							$CouponName = "<strong>".$row_get_mrk['disc_percent']."% Off</strong>";
						} else if(strlen(trim($row_get_mrk['disc_price']))>0 && intval($row_get_mrk['disc_price'])>0){
							$CouponName = "$<strong>".number_format($row_get_mrk['disc_price'],2,".",",")." Off</strong>";
						} else if(strlen(trim($row_get_mrk['disc_item']))>0 && intval($row_get_mrk['disc_item']) > 0 && strlen(trim($mrk['disc_for']))>0 && intval($row_get_mrk['disc_for']) > 0){
							$CouponName = "<strong>".$row_get_mrk['disc_item']." for ".$row_get_mrk['disc_for']."</strong>";
						}
						if(strlen(trim($row_get_mrk['prod_name']))>0){
							$CouponName .= "<br />".$row_get_mrk['prod_name'];
						} else {
							$CouponName .= "<br />your order";
						}
						
						$Coupons .= '<td class="Coupon'.(($b==0)?'1':'2').'" valign="top"><p class="Large"><a href="[Link]" title="Redeem"><img src="/images/spacer.gif" alt="Redeem" width="125" height="50" hspace="0" vspace="0" border="0" align="right"></a>'.$CouponName.'</p>
									<p class="Small">Can be found in "discounts" when viewing the event. Type: '.$row_get_mrk['disc_code'].' for your discount code.</p></td>';
						unset($CouponName);
						$a++;
						if($a >= 2){ $b++; $a = 0; $Coupons .= '</tr><tr>'; }
				} }
				if($a < 2){ $Coupons .= '<td>&nbsp;</td>'; }
				$Coupons .= '</tr></table></td></tr>';
			}
			
			$EmailText = array();
			if(intval($row_get_exp['event_mrk_days_early_purchase']) != 0 && 
				$EvntDate == date("Y-m-d") && 
				strlen(trim($row_get_exp['event_mrk_early_text'])) > 0){
				$EmailText[] = $row_get_exp['event_mrk_early_text'];
			}
			if(intval($row_get_exp['event_mrk_days_after_start']) != 0 && 
					intval($row_get_exp['event_mrk_days_after_start']) == intval($row_get_exp['StartToDay']) && 
					strlen(trim($row_get_exp['event_mrk_start_text'])) > 0 && 
					$row_get_exp['event_mrk_starts'] == 'n'){
				$EmailText[] = $row_get_exp['event_mrk_start_text'];
			}
			if(intval($row_get_exp['event_mrk_days_before_end']) != 0 && 
				intval($row_get_exp['event_mrk_days_before_end']) == intval($row_get_exp['EndToDay']) && 
				strlen(trim($row_get_exp['event_mrk_end_text'])) > 0){
				$EmailText[] = $row_get_exp['event_mrk_end_text'];
			}
			if(intval($row_get_exp['event_mrk_days_before_end_2']) != 0 && 
				intval($row_get_exp['event_mrk_days_before_end_2']) == intval($row_get_exp['EndToDay']) && 
				strlen(trim($row_get_exp['event_mrk_end_2_text'])) > 0){
				$EmailText[] = $row_get_exp['event_mrk_end_2_text'];
			}
			if(intval($row_get_exp['event_mrk_days_cart_expire']) != 0 && 
				intval($row_get_exp['event_mrk_days_cart_expire']) == intval($row_get_exp['EndToDay']) && 
				strlen(trim($row_get_exp['event_mrk_expire_text'])) > 0){
				$EmailText[] = $row_get_exp['event_mrk_expire_text'];
			}
			if($row_get_exp['event_mrk_starts'] == 'y' && 
				intval($row_get_exp['StartToDay']) == 1 && 
				strlen(trim($row_get_exp['event_mrk_starts_text'])) > 0){
				$EmailText[] = $row_get_exp['event_mrk_starts_text'];
			}
			if($row_get_exp['event_mrk_black_friday'] == 'y' &&
				strlen(trim($row_get_exp['event_mrk_black_friday_text'])) > 0){
				$EmailText[] = $row_get_exp['event_mrk_black_friday_text'];
			}
			if($row_get_exp['evnt_mrk_dec_10'] == 'y' &&
				strlen(trim($row_get_exp['evnt_mrk_dec_10_text'])) > 0){
				$EmailText[] = $row_get_exp['evnt_mrk_dec_10_text'];
			}
			if($row_get_exp['event_mrk_re_release'] == 'y' &&
				strlen(trim($row_get_exp['event_mrk_re_release_text'])) > 0){
				$EmailText[] = $row_get_exp['event_mrk_re_release_text'];
			}
			
			if($row_get_exp['event_mrk_end_start_promo'] != '0' && $row_get_exp['event_mrk_days_after_start'] != '0' &&
				intval($row_get_exp['EndPromoStart']) == 0 && strlen(trim($row_get_exp['event_mrk_end_start_promo_text'])) > 0){
				
				$EmailText[] = $row_get_exp['event_mrk_end_start_promo_text'];
			}
						
			$msg2 = str_replace('[Coupons]',$Coupons,$msg);
			$msg2 = str_replace('[Name]',$v,$msg2);
			$msg2 = str_replace(array('[Link]','%5BLink%5D'),"http://www.proimagesoftware.com/photo_viewer.php?Photographer=".$Handle."&code=".$Event."&email=".$v."&full=true",$msg2);
			$msg2 = str_replace(array('[Link2]','%5BLink2%5D'),wordwrap("http://www.proimagesoftware.com/photo_viewer.php?Photographer=".$Handle."&code=".$Event."&email=".$v."&full=true", 100, $eol),$msg2);
			$msgStore = $msg2;
			
			foreach($EmailText as $Desc){ $msg3 = $msgStore;
				$msg3 = str_replace('[Text]','<p>'.ereg_replace(array("^<p>","</p>$"),'',sanatizeentry($Desc,true)).'</p>',$msg3);
		
				$Pattern = array();
				$Pattern[] = '/<link\s[^>]*href=(["\']??)([^"\'>]*?)\\1[^>]*rel="stylesheet"(.*)>/eiU';
				$Pattern[] = '@<style[^>]*?>.*?</style>@esiU';
				
				$CSS = "";
				$msg3 = preg_replace($Pattern[0],"FindCSS('$2')",$msg3);
				$msg3 = preg_replace($Pattern[1],"FindCSS2('$0')",$msg3);
				
				$InlineHTML = new Emogrifier();
				$InlineHTML -> setHTML($msg3);
				$InlineHTML -> setCSS($CSS);
			
				$msg3 = removeSpecial($msg3);
			
				$msg3 = $InlineHTML -> emogrify();
				
				while(strpos("\r",$msg3) !== false || strpos("\n",$msg3) !== false || strpos("\r\n",$msg3) !== false || strpos("\n\r",$msg3) !== false ){
					$msg3 = trim(str_replace(array("\r","\n","\r\n","\n\r"),"",$msg3));
				}
				
				$msg3 = preg_replace("/ +/", " ", $msg3);
				$msg3 = str_replace('href= "', 'href="', $msg3);
				$msg3 = str_replace('src="/','src="http://www.proimagesoftware.com/',$msg3);
				$msg3 = str_replace('url(/','url(http://www.proimagesoftware.com/',$msg3);
				
				//$config = array('indent' => TRUE,'wrap' => 200);
				//$tidy = tidy_parse_string($msg, $config, 'UTF8');
				//$tidy->cleanRepair();
				//$msg = $tidy;
				$msg3 = clean_html_code($msg3);
				
				$mail = new PHPMailer();
				$mail -> IsSendMail();
				$mail -> Host = "smtp.proimagesoftware.com";
				$mail -> IsHTML(true);
				$mail -> Sender = "info@proimagesoftware.com";
				$mail -> Hostname = "proimagesoftware.com";
				$mail -> From = $PhotoEmail;
				$mail -> FromName = $Photographer;
				$mail -> ReplyTo = $PhotoEmail;
				$mail -> AddAddress($v);
				$mail -> Subject = str_replace("&amp;","&",$title);
				if($Image !== false) $mail -> AddEmbeddedImage($Image, $Time);
				$mail -> Body = $msg3;
				//$mail -> Send();
			}
			unset($mail);					unset($Pattern);			unset($msgStore);			unset($msg2);			unset($msg3);		
		}
		
		$Emailsent = true;
		$BioImage = false;
		if($Image != false) unlink($Image);
		unset($EId);				unset($PhotoEmail);				unset($msg);				unset($Image);			unset($IName);			unset($address);
		unset($website);		unset($EName);						unset($BioImage);		unset($Handle);			unset($Event);			unset($Photographer);
		unset($MrkCodes);		unset($EvntDate);					unset($EvntEnd);		unset($EmailText);

		sleep(2);
	}
}

?>