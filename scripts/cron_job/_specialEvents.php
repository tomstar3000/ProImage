<?
if(!defined("CronJob")) die("Hacking Attempt");
echo "Special Events<br />";

$query_get_evnts = "SELECT `client`.`cust_fname` AS `client_fname`, `client`.`cust_lname` AS `client_lname`, `client`.`cust_email` AS `client_email`, `cust_customers`.`cust_handle`, `cust_customers`.`cust_cname`, `cust_customers`.`cust_fname`, `cust_customers`.`cust_lname`, `cust_customers`.`cust_email`, `cust_customers`.`cust_city`, `cust_customers`.`cust_state`, `cust_customers`.`cust_zip`, `cust_customers`.`cust_website`, `cust_customers`.`cust_thumb`, `DV1`.* FROM(
	SELECT *, 
	DATE_FORMAT(NOW(),'%Y-%m-%d') AS `now`,
	
	DATE_SUB( DATE_FORMAT(`cust_date_date`,'%Y-%m-%d'), INTERVAL `cust_date_rmd_time` DAY) AS `pres_date`,
	
	DATE_SUB( CONCAT( DATE_FORMAT(NOW(),'%Y'),'-', DATE_FORMAT(NOW(),'%m'), '-', DATE_FORMAT(`cust_date_date`,'%d') ), INTERVAL `cust_date_rmd_time` DAY) AS `to_date`,
		
	DAYOFYEAR(`cust_date_date`) - DAYOFYEAR(NOW()) - `cust_date_rmd_time` AS `days_to_year`,
	
	CASE DAYOFYEAR(DATE_FORMAT( CONCAT( DATE_FORMAT(NOW(),'%Y'),'-12-31'),'%Y-%m-%d')) WHEN '366' THEN '1' ELSE '0' END AS `leap_year`,
		
	DATE_FORMAT(`cust_date_date`, '%w')+1 AS `day_of_week`,
	DATE_FORMAT(`cust_date_date`, '%c') AS `month_of_year`,
	WEEK(`cust_date_date`) - WEEK(DATE_SUB(`cust_date_date`, INTERVAL DAYOFMONTH(`cust_date_date`)-1 DAY))+1 AS `week_of_month`,
	DATE_FORMAT(`cust_date_date`, '%u') AS `week_of_year`
	
	FROM `cust_customers_special_date`
) AS `DV1`
INNER JOIN `cust_customers` AS `client`
	ON `client`.`cust_id` = `DV1`.`cust_id`
INNER JOIN `cust_customers`
	ON `cust_customers`.`cust_id` = `client`.`photo_id`
WHERE (
			 (`cust_date_freg` = '2'
			 AND `to_date` = `now`
		 ) OR (
		 	`cust_date_freg` = '3'
			AND (
					 (`days_to_year` = '1'
					 		AND `leap_year` = '0'
						)
					 OR 
					 (`days_to_year` = '0'
					 		AND `leap_year` = '1'
						)
					)
		 ) OR (
		 	`cust_date_freg` = '1'
			AND `pres_date` = `now`
		 )
		) ";

$get_evnts = mysql_query($query_get_evnts, $cp_connection) or die(mysql_error());

ob_start();
include($r_path.'Templates/Photographer/special_event.php');
$basemsg = ob_get_contents();
ob_end_clean();
	
while($row_get_evnts = mysql_fetch_assoc($get_evnts)){
	$Handle = $row_get_evnts['cust_handle'];
	$Event = $row_get_evnts['event_num'];
	$address = (strlen(trim($row_get_evnts['cust_city'])) > 0)?$row_get_evnts['cust_city']." ".$row_get_evnts['cust_state']." ".$row_get_evnts['cust_zip']:'';
	$website = (strlen(trim($row_get_evnts['cust_website'])) > 0)?$row_get_evnts['cust_website']:'';
	$Photographer = ($row_get_evnts['cust_cname'] == "") ? $row_get_evnts['cust_fname']." ".$row_get_evnts['cust_lname'] : $row_get_evnts['cust_cname'];
	$EName = $row_get_evnts['event_name'];
	$PhotoEmail = $row_get_evnts['cust_email'];
	$BioImage = false;
	$title = $row_get_evnts['cust_date_name'];
	$ClntName = $row_get_evnts['client_fname']." ".$row_get_evnts['cust_lname'];
	$EName = $row_get_evnts['cust_date_name'];
	$Desc = $row_get_evnts['cust_date_msg'];
	$Desc = str_replace('[Name]',$ClntName,$Desc);
	
	echo $Handle.": ".$ClntName."<br />";
	
	$msg = str_replace('[Title]',$title,$basemsg);
	$msg = str_replace('[Photographer]',$Photographer,$msg);
	$msg = str_replace('[Event Name]',$EName,$msg);
	$msg = str_replace('[Address]',((strlen(trim($address))>0)?'<br />'.$address:''),$msg);
	$msg = str_replace('[Website]',((strlen(trim($website))>0)?'<br /><a href="'.$website.'" title="'.$website.'">'.$website.'</a>':''),$msg);
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
	$msg = str_replace('src="/','src="http://www.proimagesoftware.com/',$msg);
	$msg = str_replace('url(/','url(http://www.proimagesoftware.com/',$msg);
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
	$mail -> AddAddress($row_get_evnts['client_email'], $ClntName);
	$mail -> Subject = "This is a reminder: ".str_replace("&amp;","&",$title);
	$mail -> Body = $msg;
	$mail -> Send();
	
	unset($Handle); unset($Event); unset($address); unset($website); unset($Photographer); unset($EName); unset($PhotoEmail); unset($BioImage); unset($title); unset($ClntName);
	unset($Desc); unset($mail); unset($msg);
}
?>