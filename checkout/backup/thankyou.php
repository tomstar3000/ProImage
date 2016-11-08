<?
$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-1;
$r_path = "";
for($n=0;$n<$count;$n++){
	$r_path .= "../";
}
define('Allow Scripts',true);
require_once($r_path.'scripts/cart/ssl_paths.php');
require_once($r_path.'scripts/fnct_clean_entry.php');
require_once($r_path.'scripts/fnct_format_phone.php');
require_once($r_path.'Connections/cp_connection.php');
if(isset($_GET['Photographer'])){
	$ = clean_variable($_GET['Photographer'],true);
	$ = str_replace(" ","_",$);
} else if(isset($_POST['Photographer'])){
	$ = clean_variable($_POST['Photographer'],true);
	$ = str_replace(" ","_",$);
} else if($_SERVER['QUERY_STRING'] != "") {
	$ = clean_variable($_SERVER['QUERY_STRING'],true);
	$ = str_replace(" ","_",$);
} else {
	$ = false;
}
mysql_select_db($database_cp_connection, $cp_connection);
$query_get_info = "SELECT `cust_fname`, `cust_mint`, `cust_lname`, `cust_suffix`, `cust_cname`, `cust_desc`, `cust_add`, `cust_add_2`, `cust_suite_apt`, `cust_city`, `cust_state`, `cust_zip`, `cust_country`, `cust_phone`, `cust_cell`, `cust_fax`, `cust_work`, `cust_ext`, `cust_email`, `cust_website`, `cust_thumb`, `cust_image` FROM `cust_customers` WHERE `cust_handle` = '$' LIMIT 0,1";
$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
$row_get_info = mysql_fetch_assoc($get_info);
$code = (isset($_POST['Event_Code'])) ? clean_variable($_POST['Event_Code'],true) : "";
$encnum = (isset($_GET['invoice'])) ? clean_variable($_GET['invoice'],true) : "";
$launch_full = false;
if(isset($_POST['Event_Code'])){
	$query_check_code = "SELECT `event_id`, `event_num` FROM `photo_event` INNER JOIN `cust_customers` ON `cust_customers`.`cust_id` = `photo_event`.`cust_id` WHERE `event_num` LIKE '%$code%' AND `cust_handle` = '$' AND `event_use` = 'y' LIMIT 0,1";
	$check_code = mysql_query($query_check_code, $cp_connection) or die(mysql_error());
	$row_check_code = mysql_fetch_assoc($check_code);
	$total_check_code = mysql_num_rows($check_code);
	if($total_check_code != 0){
		$code = $row_check_code['event_num'];
		session_start();
		if(isset($_SESSION['cart'])){
			if(isset($_SESSION['cart']))unset($_SESSION['cart']);
			if(isset($cart))unset($cart); 
		}
		$code = $code.$;
		if(!isset($_COOKIE['PhotoExpress_Guestbook_'.$code]) && !isset($_POST['Email'])){
			require_once($r_path.'guestbook.php');
			die();
		} else {
			$Event_id = $row_check_code['event_id'];
			$Email = (isset($_POST['Email'])) ? clean_variable($_POST['Email'],true) : "";
			$Promo = (isset($_POST['Promotion'])) ? clean_variable($_POST['Promotion'],true) : 'n';
			
			if(!isset($_COOKIE['PhotoExpress_Guestbook_'.$code])){
				$add = "INSERT INTO `photo_quest_book` (`event_id`,`email`,`promotion`) VALUES ('$Event_id','$Email','$Promo');";
				$add_info = mysql_query($add, $cp_connection) or die(mysql_error());
			}
			setcookie('PhotoExpress_Guestbook_'.$code,$code,(time()+60*60*24*30),'/');
			if(isset($_POST['Fullscreen'])){
				$launch_full = true;
			} else {
				$GoTo = "/photo_viewer.php?Photographer=".$."&code=".$code;
				header(sprintf("Location: %s", $GoTo));
			}
		}
	}
} else {
	$total_check_code = 0;
} ?>
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
<? if($launch_full === true){ ?>
<script type="text/JavaScript">
mywindow = window.open("/photo_viewer.php?Photographer=<? echo $; ?>&code=<? echo $code; ?>&full=true","","toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=0,resizable=0,width="+screen.width+",height="+screen.height
);
</script>
<? } ?>
<script type="text/JavaScript">
<!--
function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_validateForm() { //v4.0
  var i,p,q,nm,test,num,min,max,errors='',args=MM_validateForm.arguments;
  for (i=0; i<(args.length-2); i+=3) { test=args[i+2]; val=MM_findObj(args[i]);
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
}
//-->
</script>
</head>
<body>
<div id="Container">
  <div id="Logo">
    <? if($getInfo[0]['cust_image'] !="" ){
		$data = array(
			'imagetype' => 'logo',
			'image' => $handle.'/'.$getInfo[0]['cust_image'],
			'width' => 998,
			'height' => 387,
			'salt' => time(),
		);
		require_once $r_path.'scripts/cart/encrypt.php';
		$data = base64_encode(encrypt_data(serialize($data)));
		echo '<img src="/images/image.php?data='.$data.'"  />'; } else { ?>
    <img src="/images/spacer.gif" width="998" height="387" />
    <? } ?>
  </div>
  <div id="Content">
    <div id="Navigation">
      <ul>
        <li id="Home"><a href="/index.php<? echo "?".$_SERVER['QUERY_STRING']; ?>">Home</a></li>
        <li id="Bio"><a href="/bio.php<? echo "?".$_SERVER['QUERY_STRING']; ?>">Bio</a></li>
        <!-- 
        <li id="Portfolio"><a href="/portfolio.php<? echo "?".$_SERVER['QUERY_STRING']; ?>">Portfolio</a></li>
				-->
        <li id="Contact"><a href="/contact.php<? echo "?".$_SERVER['QUERY_STRING']; ?>">Contact</a></li>
      </ul>
    </div>
    <div id="Text">
      <h1>Thank you for your purchase </h1>
      <p>Thank you for purchasing from <? echo $row_get_info['cust_cname']; ?>. Your Invoice has been e-mailed to you and your order will be processed soon.</p>
      <p><a href="/checkout/invoice.php?invoice=<? echo $encnum; ?>" target="_blank">Click here to view your invoice.</a> </p>
    </div>
    <div id="Event">
      <h2>Event Login</h2>
      <form method="post" name="Event_Code_Form" id="Event_Code_Form" action="<? echo $_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']; ?>" enctype="multipart/form-data">
        <p>
          <input name="Fullscreen" type="checkbox" class="no_border" id="Fullscreen" value="y" style="border:none" />
          Launch Fullscreen<br />
          <input name="Event Code" id="Event_Code" type="text" value="enter code" onclick="javascript:this.value=''" />
          <img src="/images/btn_enter.jpg" width="50" height="21" align="absmiddle" onclick="MM_validateForm('Event_Code','','R');if(document.MM_returnValue==true){document.getElementById('Event_Code_Form').submit();}" style="cursor:pointer;" /> </p>
      </form>
      <div id="btn_Public">
        <p><a href="public.php?Photographer=<? echo $; ?>">View Public Events</a> </p>
      </div>
      <p>This site requires that you turn off your pop-up blocker and have cookies enabled. </p>
    </div>
    <br clear="all" />
  </div>
</div>
<div id="Footer">
  <div style="float:left">
    <? $Company = ($row_get_info['cust_fcname']!="")?$row_get_info['cust_fcname']:$row_get_info['cust_cname'];
	$Email = ($row_get_info['cust_femail']!="")?$row_get_info['cust_femail']:$row_get_info['cust_email'];
	$Work = ($row_get_info['cust_fwork']!="0")?$row_get_info['cust_fwork']:$row_get_info['cust_work'];
	$Ext = ($row_get_info['cust_fext']!="0")?$row_get_info['cust_fext']:$row_get_info['cust_ext'];
	$Icon = $row_get_info['cust_icon']; ?>
    <p class="no_indent"><? echo $Company;?><br />
      <a href="mailto:<? echo $Email;?>" class="Footer_Nav"><? echo $Email;?></a><br />
      <? if($Work!="0"){echo phone_number($Work); if($Ext!="0")echo " Ext. ".$Ext; }?>
      <br />
      <a href="mailto:support@proimagesoftware.com" class="Footer_Nav">support@proimagesoftware.com</a></p>
  </div>
  <? if($Icon != ""){ ?>
  <div style="float:right;"><img src="/photographers/<? echo $.'/'.$Icon; ?>" height="50"/></div>
  <? } ?>
</div>
</body>
</html>
