<?
$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
$r_path = "";
for($n=0;$n<$count;$n++){
	$r_path .= "../";
}
define('Allow Scripts',true);
require_once($r_path.'scripts/cart/ssl_paths.php');
require_once($r_path.'scripts/fnct_clean_entry.php');
require_once($r_path.'scripts/fnct_format_phone.php');
require_once($r_path.'Connections/cp_connection.php');
mysql_select_db($database_cp_connection, $cp_connection);
$query_get_info = "SELECT `cust_fname`, `cust_mint`, `cust_lname`, `cust_suffix`, `cust_cname`, `cust_desc_2`, `cust_add`, `cust_add_2`, `cust_suite_apt`, `cust_city`, `cust_state`, `cust_zip`, `cust_country`, `cust_phone`, `cust_cell`, `cust_fax`, `cust_work`, `cust_ext`, `cust_email`, `cust_website`, `cust_thumb`, `cust_image`, `cust_active`,`cust_paid`, `cust_fcname`, `cust_fwork`, `cust_fext`, `cust_femail`, `cust_icon` FROM `cust_customers` WHERE `cust_handle` = '$handle' LIMIT 0,1";
$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
$row_get_info = mysql_fetch_assoc($get_info);
if($row_get_info['cust_active'] == 'n'){
//if($row_get_info['cust_active'] == 'n' || $row_get_info['cust_paid'] == 'n'){
	$GoTo = "/index.php?Error=We were unable to find that photographer.";
	header(sprintf("Location: %s", $GoTo));
}
$code = (isset($_POST['Event_Code'])) ? clean_variable($_POST['Event_Code'],true) : ((isset($_GET['code'])) ? clean_variable($_GET['code'],true) : "");
$custid = $row_get_info['cust_id'];
$launch_full = false;

require_once($r_path.'includes/_Guestbook.php'); ?>
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
<script type="text/javascript" src="/javascript/GoogleTracker2.js"></script>
<? if($launch_full === true){ ?>
<script type="text/JavaScript">
function fullscreen(){
	var isWin=(navigator.appVersion.indexOf("Win")!=-1)? true : false;
	var isIE=(navigator.appVersion.indexOf("MSIE")!=-1)? true : false;
	if(isWin&&isIE) {
		var mywindow = window.open("/photo_viewer.php?Photographer=<? echo $handle; ?>&code=<? echo $code; ?>&full=true",'ProImageSoftware',"fullscreen=yes,scrollbars=1");
		mywindow.focus();
	} else {
	// 	AK: for non-ie browsers, specify width and height instead of using fullscreen 
		var mywindow = window.open("/photo_viewer.php?Photographer=<? echo $handle; ?>&code=<? echo $code; ?>&full=true","ProImageSoftware","width="+screen.availWidth+",height="+screen.availHeight+",scrollbars=1");
		mywindow.moveTo(0,0);
		mywindow.focus();
	}
}
fullscreen();
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
        <li id="Portfolio" class="Nav_Sel"><a href="/portfolio.php<? echo "?".$_SERVER['QUERY_STRING']; ?>">Portfolio</a></li>
				-->
        <li id="Contact"><a href="/contact.php<? echo "?".$_SERVER['QUERY_STRING']; ?>">Contact</a></li>
      </ul>
    </div>
    <div id="Text_Long">
      <h1>Public Events </h1>
      <? 
			$custid = $row_get_info['cust_id'];
			$query_get_prot = "SELECT `event_date`, `event_num`, `event_short` FROM `photo_event` INNER JOIN `photo_event_group` ON `photo_event_group`.`event_id` = `photo_event`.`event_id` INNER JOIN `photo_event_images` ON `photo_event_images`.`group_id` = `photo_event_group`.`group_id` WHERE `cust_id` = '$custid' ORDER BY `event_date` DESC";
			$get_prot = mysql_query($query_get_prot, $cp_connection) or die(mysql_error());
			$n=0;
			while($row_get_prot = mysql_fetch_assoc($get_prot)){ ?>
      <div id="Event_List">
        <div style="float:left; display:block;"> <img src="/photographers/Chad_Serpan/Bio_Image.jpg" width="80" border="0" align="left" class="bio_photo" style="margin-bottom:0px; "/> </div>
        <div style="float:left; width:650px;">
          <div>
            <div style="float:left;">
              <h3><? echo substr($row_get_prot['event_date'],5,2)."/".substr($row_get_prot['event_date'],8,2)."/".substr($row_get_prot['event_date'],0,4);?></h3>
            </div>
            <div style="float:right;">
              <form action="<? echo $_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']; ?>" name="Launch_Form_<? echo $n; ?>" id="Launch_Form_<? echo $n; ?>" method="post" enctype="multipart/form-data">
                <input type="hidden" name="Event_Code" id="Event_Code" value="<? echo $row_get_prot['event_num']; ?>" style="display:none" />
                <input type="hidden" class="no_border" id="Fullscreen" value="n" style="border:none" />
              </form>
              <a href="#" onclick="javascript:document.getElementById('Launch_Form_<? echo $n; ?>').submit();">Launch Event</a><br />
              <!-- <a href="#" onclick="javascript:document.getElementById('Fullscreen').value = 'y'; document.getElementById('Launch_Form_<? echo $n; ?>').submit();">Launch
       Event - Full Screen</a> -->
            </div>
          </div>
          <br clear="all"/>
          <p style="margin:0px;"><? echo $row_get_prot['event_short']; ?></p>
        </div>
        <br clear="all"/>
      </div>
      <? $n++; } ?>
    </div>
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
  <? if($Icon != "" && is_file($r_path."photographers/".$handle.'/'.$Icon)){ ?>
  <div style="float:right;"><img src="/photographers/<? echo $handle.'/'.$Icon; ?>" height="50"/></div>
  <? } ?>
</div>
</body>
</html>
