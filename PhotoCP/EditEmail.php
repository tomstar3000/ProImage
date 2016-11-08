<? $count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2; $r_path = ""; for($n=0;$n<$count;$n++)$r_path .= "../";
define("PhotoExpress Pro", true);
define('Allow Scripts',true);
include $r_path.'security.php';

require_once($r_path.'../Connections/cp_connection.php');
require_once($r_path.'scripts/fnct_sql_processor.php');
require_once($r_path.'scripts/fnct_clean_entry.php');
require_once($r_path.'scripts/fnct_format_date.php');
require_once $r_path.'scripts/emogrifier.php';
require_once $r_path.'../scripts/fnct_ImgeProcessor.php';

if($loginsession[1] >= 10 || (isset($_GET['admin']) && $_GET['admin'] == "true")) require_once($r_path.'includes/get_user_information.php');
else die('Nothing to see');

$PTRN = array();
$PTRN[] = '/<link\s[^>]*href=(["\']??)([^"\'>]*?)\\1[^>]*rel="stylesheet"(.*)>/eiU';
$PTRN[] = '@<style[^>]*?>.*?</style>@esiU';

function FindCSS($CSSLink){
	global $CSS, $Template, $r_path;
	$path = $CSSLink;
	$HDLE = fopen('../'.$r_path.$path, "r") or die("Failed Opening ".$r_path.$path);
	while (!feof($HDLE)) $CSS .= fread($HDLE, 8192);
	fclose($HDLE);
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

$TYPE = isset($_GET['type']) 	? clean_variable($_GET['type'],true)	: die('Nothing Selected - Error: 100');
$EDIT = isset($_GET['edit']) 	? clean_variable($_GET['edit'],true)	: false; $EDIT = ($EDIT=="true" || $EDIT === true)?true:false;
$ID		= isset($_GET['id'])		? clean_variable($_GET['id'],true)		: die('Nothing Selected - Error: 200');

$HDLE = $IMG = false;
$UNSCB = true;
$EMLTXT = array();
$EMAILTmp = 'index.php';

if($TYPE == '2'){
	$VALS	= isset($_GET['vals'])	? clean_variable($_GET['vals'],true)	: die('Nothing Selected - Error: 300');
	$TITLE = "Special Event Reminder";
	$CPNS = '';
	
	if(isset($_POST['Controller']) && $_POST['Controller'] == "true"){
		$MSG = clean_variable($_POST['Email_Notes'],"Store");
		$mySQL = "UPDATE `cust_customers_special_date` SET `cust_date_msg` = '".$MSG."' WHERE `cust_date_id` = '".$VALS."';";
		$getEmail = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
		$getEmail->mysql($mySQL);
		$EDIT = false;
	}
	
	$mySQL = "SELECT `cust_customers_special_date`.*
		FROM `cust_customers_special_date`
		INNER JOIN `cust_customers` 
			ON `cust_customers`.`cust_id` = `cust_customers_special_date`.`cust_id` 
		WHERE `cust_customers_special_date`.`cust_date_id` = '".$VALS."'
			AND `cust_customers`.`cust_id` = '".$ID."'
			AND `cust_customers`.`photo_id` = '".$CustId."' LIMIT 0,1;";
	$getEmail = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
	$getEmail->mysql($mySQL);
	$Row = $getEmail->Rows();
	if(strlen($Row[0]['cust_date_msg']) > 0){
		$EMLTXT[] = $Row[0]['cust_date_msg'];
	} else {
		$EMLTXT[] = "<p>Dear [Name],</p>
<p>[Photographer] would like acknowledge your anniversary of your [Event Name]. We thank you for your patronage in the past and wish you the very best on your special day.</p>
<p>Sincerely, <br />
[Photographer]</p>";
	}
	$mySQL = "SELECT `cust_fname`, `cust_lname`, `cust_email`, `cust_city`, `cust_state`, `cust_zip`, `cust_website`
		FROM `cust_customers` 
		WHERE `cust_id` = '".$ID."'
			AND `photo_id` = '".$CustId."' LIMIT 0,1;";
	$getEmail = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
	$getEmail->mysql($mySQL);
	$Row = $getEmail->Rows();
	$ADD = '';
	$WEB = 'www.progimagesoftware.com';
	$ENAME = $Row[0]['event_name'];
	$PEMAIL = $Row[0]['cust_email'];
	$PNAME = $Row[0]['cust_fname']." ".$Row[0]['cust_lname'];
	$UNSCB = false;
	$EMAILTmp = 'special_event.php';
} else {
	
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
		$HDLE = $Row[0]['cust_handle'];
		$EVNT = $Row[0]['event_num'];
		$ADD = (strlen(trim($Row[0]['cust_city'])) > 0)?$Row[0]['cust_city']." ".$Row[0]['cust_state']." ".$Row[0]['cust_zip']:'';
		$WEB = (strlen(trim($Row[0]['cust_website'])) > 0)?$Row[0]['cust_website']:'';
		$PHOTO = ($Row[0]['cust_cname'] == "") ? $Row[0]['cust_fname']." ".$Row[0]['cust_lname'] : $Row[0]['cust_cname'];
		$ENAME = $Row[0]['event_name'];
		$PEMAIL = $Row[0]['cust_email'];
	
		$MrkCodes = str_replace(array("}","{"),'',explode("[+]",$Row[0]['event_mrk_codes']));
		$EvntDate = date("Y-m-d", mktime(0,0,0,substr($Row[0]['event_date'],5,2),substr($Row[0]['event_date'],8,2),substr($Row[0]['event_date'],0,4)));
		$EvntEnd = date("Y-m-d", mktime(0,0,0,substr($Row[0]['event_end'],5,2),substr($Row[0]['event_end'],8,2),substr($Row[0]['event_end'],0,4)));
			
		$BioImage = false;
		
		$INME = $Row[0]['image_tiny'];		
		$file = $r_path.substr($row_get_exp['image_folder'],0,-11)."Large/".$INME;
		
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
			$INME = false;
			$IMG = false;
		}
		
		$TITLE = mb_convert_encoding($ENAME, "UTF-8", 'HTML-ENTITIES');
			
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
	
		$CPNS = '';
		if($getMrktCpns->TotalRows() > 0){ $CPNS = '<tr class="Coupons"><td align="center"><table border="0" cellpadding="0" cellspacing="0"><tr>';
			$a = 0; $b = 0;
			foreach($getMrktCpns->Rows() as $record){
				if(in_array($record['disc_id'],$Vals)){ 
					if(strlen(trim($record['disc_percent']))>0 && intval($record['disc_percent'])>0){
						$CpnNAME = "<strong>".$record['disc_percent']."% Off</strong>";
					} else if(strlen(trim($record['disc_price']))>0 && intval($record['disc_price'])>0){
						$CpnNAME = "$<strong>".number_format($record['disc_price'],2,".",",")." Off</strong>";
					} else if(strlen(trim($record['disc_item']))>0 && intval($record['disc_item']) > 0 && strlen(trim($record['disc_for']))>0 && intval($record['disc_for']) > 0){
						$CpnNAME = "<strong>".$record['disc_item']." for ".$record['disc_for']."</strong>";
					}
					if(strlen(trim($record['prod_name']))>0) $CpnNAME .= "<br />".$record['prod_name'];
					else  $CpnNAME .= "<br />your order";
					
					$CPNS .= '<td class="Coupon'.(($b==0)?'1':'2').'" valign="top"><p class="Large"><a href="[Link]" title="Redeem"><img src="/images/spacer.gif" alt="Redeem" width="125" height="50" hspace="0" vspace="0" border="0" align="right"></a>'.$CpnNAME.'</p>
								<p class="Small">Can be found in "discounts" when viewing the event. Type: '.$record['disc_code'].' for your discount code.</p></td>';
					unset($CpnNAME);
					$a++;
					if($a >= 2){ $b++; $a = 0; $CPNS .= '</tr><tr>'; }
			} }
			if($a < 2){ $CPNS .= '<td>&nbsp;</td>'; }
			$CPNS .= '</tr></table></td></tr>';
		}
		
		if(strlen(trim($Row[0]['event_mrk_early_text'])) > 0) 						$EMLTXT[] = $Row[0]['event_mrk_early_text'];
		if(strlen(trim($Row[0]['event_mrk_start_text'])) > 0) 						$EMLTXT[] = $Row[0]['event_mrk_start_text'];
		if(strlen(trim($Row[0]['event_mrk_starts_text'])) > 0) 						$EMLTXT[] = $Row[0]['event_mrk_starts_text'];
		if(strlen(trim($Row[0]['event_mrk_end_start_promo_text'])) > 0) 	$EMLTXT[] = $Row[0]['event_mrk_end_start_promo_text'];
		if(strlen(trim($Row[0]['event_mrk_end_text'])) > 0) 							$EMLTXT[] = $Row[0]['event_mrk_end_text'];
		if(strlen(trim($Row[0]['event_mrk_end_2_text'])) > 0) 						$EMLTXT[] = $Row[0]['event_mrk_end_2_text'];
		if(strlen(trim($Row[0]['event_mrk_expire_text'])) > 0) 						$EMLTXT[] = $Row[0]['event_mrk_expire_text'];
		if(strlen(trim($Row[0]['event_mrk_starts_text'])) > 0) 						$EMLTXT[] = $Row[0]['event_mrk_starts_text'];
		if(strlen(trim($Row[0]['event_mrk_black_friday_text'])) > 0) 			$EMLTXT[] = $Row[0]['event_mrk_black_friday_text'];
		if(strlen(trim($Row[0]['evnt_mrk_dec_10_text'])) > 0)							$EMLTXT[] = $Row[0]['evnt_mrk_dec_10_text'];
		if(strlen(trim($Row[0]['event_mrk_re_release_text'])) > 0) 				$EMLTXT[] = $Row[0]['event_mrk_re_release_text'];
		if(strlen(trim($Row[0]['event_mrk_guestbook_text'])) > 0) 				$EMLTXT[] = $Row[0]['event_mrk_guestbook_text'];
		if($Row[0]['event_mrk_end_start_promo'] != '0') 									$EMLTXT[] = $Row[0]['event_mrk_end_start_promo_text'];
	}
}

ob_start();
include($r_path.'../Templates/Photographer/'.$EMAILTmp);
$MSG = ob_get_contents();
ob_end_clean();

$TIME = time();

if($IMG !== false) {
	list($WDTH, $HGHT) = getimagesize($IMG);
	if($WDTH > $HGHT){ $Perc = 242/$WDTH; $HGHT = round($HGHT*$Perc); $WDTH = 242; }
	else { $Perc = 242/$HGHT; $WDTH = round($WDTH*$Perc); $HGHT = 242; }
	$MSG = str_replace('[EmbedImg]','<img class="img" src="cid:'.$TIME.'" width="'.$WDTH.'" height="'.$HGHT.'" alt="'.$HDLE.'" vspace="0" hspace="0" alt="'.$TITLE.'" style="border: 3px solid #c89441;"><br clear="all" />',$MSG);
} else { $MSG = str_replace('[EmbedImg]','',$MSG); }

$MSG = str_replace('[Title]',$TITLE,$MSG);
$EMLTXT = str_replace('[Unsubscribe]',
				(($UNSCB==false)?'':"http://www.proimagesoftware.com/unsubscribe.php?token=".substr(time(),0,5).$EId.substr(time(),5)),$EMLTXT);
$EMLTXT = str_replace('[Photographer]',$CName,$EMLTXT);
$EMLTXT = str_replace('[Address]',((strlen(trim($ADD))>0)?'<br />'.$ADD:''),$EMLTXT);
$EMLTXT = str_replace('[Website]',((strlen(trim($WEB))>0)?'<br /><a href="'.$WEB.'" title="'.$WEB.'">'.$WEB.'</a>':''),$EMLTXT);

$EMLTXT = str_replace('[Coupons]',$CPNS,$EMLTXT);
$EMLTXT = str_replace('[Name]',$PNAME,$EMLTXT);
$EMLTXT = str_replace(array('[Link]','%5BLink%5D'),
	(($HDLE==false)?'':"http://www.proimagesoftware.com/photo_viewer.php?Photographer=".$HDLE."&code=".$EVNT."&email=".$PEMAIL."&full=true"),$EMLTXT);
$EMLTXT = str_replace(array('[Link2]','%5BLink2%5D'),
	(($HDLE==false)?'':wordwrap("http://www.proimagesoftware.com/photo_viewer.php?Photographer=".$HDLE."&code=".$EVNT."&email=".$PEMAIL."&full=true", 100, $eol))
	,$EMLTXT);

if($EDIT == true){
	ob_start(); ?>

<form method="post" name="EmailForm" id="EmailForm" enctype="multipart/form-data" action="<? echo $_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']; ?>">
  <script type="text/javascript" src="/PhotoCP/TinyMCE/3.5.8/tiny_mce.js"></script>
  <script type="text/javascript">
	tinyMCE.init({
		// General options 	4f3912
		content_css : "/Templates/Photographer/css/special_event.css",
		mode : "exact",
		elements : "Email_Notes",
		theme : "advanced",
		skin : "o2k7",
		skin_variant : "black",
		plugins : "safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",

		// Theme options
		theme_advanced_buttons1 : "bold,italic,underline,|,cut,copy,paste,pastetext,pasteword,|,search,replace",
		theme_advanced_buttons2 : "",
		theme_advanced_buttons3 : "",
		theme_advanced_buttons4 : "",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true
	});
</script>
  <!-- /TinyMCE -->
  <textarea name="Email Notes" id="Email_Notes" style="width:650px; height:535px;" onfocus="javascript:this.parentNode.className='CstmFrmElmntTextFieldNavSel';" onblur="javascript:this.parentNode.className='CstmFrmElmntTextField';" onmouseover="window.status='Email Notes'; return true;" onmouseout="window.status=''; return true;" title="Email Notes"><? echo sanatizeentry(implode("<br />",$EMLTXT),true); ?></textarea>
  <input type="hidden" name="Controller" id="Controller" value="true" />
</form>
<br clear="all" />
<? if(!isset($_GET['Ajax']) || ($_GET['Ajax'] != "true" && $_GET['Ajax'] !== true)) { ?>
<div style="height: 0px; padding: 0px; margin: 0px; float: right; width: 34px;"><a href="#" onclick="document.getElementById('EmailForm').submit(); return false;" onmouseover="window.status='Save Email Information'; return true;" onmouseout="window.status=''; return true;" title="Save Email Information" style="font-size: 2px; background-repeat: no-repeat; text-indent: -100em; display: block; padding: 0px; clear: none; height: 28px; margin-top: 0px; margin-right: 5px; margin-bottom: 0px; margin-left: 0px; overflow: hidden; float: right; background-image: url(/PhotoCP/images/btn_Save.png); height: 22px; width: 34px;">Save</a></div>
<? } ?>
<? 
	$EMLTXT = ob_get_contents();
	ob_end_clean();
	$MSG = str_replace('[Text]',$EMLTXT,$MSG);
} else {
	$MSG = str_replace('[Text]',sanatizeentry(implode("<br />",$EMLTXT),true),$MSG);
}

$CSS = "";
$MSG = preg_replace($PTRN[0],"FindCSS('$2')",$MSG);
$MSG = preg_replace($PTRN[1],"FindCSS2('$0')",$MSG);

$InlineHTML = new Emogrifier();
$InlineHTML -> setHTML($MSG);
$InlineHTML -> setCSS($CSS);

$MSG = removeSpecial($MSG);
$MSG = $InlineHTML -> emogrify();

while(strpos("\r",$MSG) !== false || strpos("\n",$MSG) !== false || strpos("\r\n",$MSG) !== false || strpos("\n\r",$MSG) !== false ){
	$MSG = trim(str_replace(array("\r","\n","\r\n","\n\r"),"",$MSG)); }

$MSG = preg_replace("/ +/", " ", $MSG);
$MSG = str_replace('href= "', 'href="', $MSG);
$MSG = str_replace('src="/','src="http://'.$_SERVER['HTTP_HOST'].'/',$MSG);
$MSG = str_replace('url(/','url(http://'.$_SERVER['HTTP_HOST'].'/',$MSG);

$MSG = clean_html_code($MSG);

echo $MSG;
?>
