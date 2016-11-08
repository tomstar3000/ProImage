<?
$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-1;
$r_path = "";
for($n=0;$n<$count;$n++){
	$r_path .= "../";
}
define('Allow Scripts',true);
require_once($r_path.'scripts/cart/ssl_paths.php');
require_once($r_path.'scripts/fnct_clean_entry.php');
	require_once($r_path.'Connections/cp_connection.php');
	mysql_select_db($database_cp_connection, $cp_connection);
if(!isset($handle) && isset($_POST['Photographer'])){
	$handle = clean_variable($_POST['Photographer'],true);
	$query_get_info = "SELECT COUNT(`cust_handle`) AS `handle_count` FROM `cust_customers` WHERE `cust_handle` = '$handle'";
	echo $query_get_info;
	$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
	$row_get_info = mysql_fetch_assoc($get_info);
	
	if($row_get_info['handle_count'] > 0){
		$GoTo = "/".$handle;
		header(sprintf("Location: %s", $GoTo));
		die();
	} else {
		$Error = "We were unable to find that photographer.";
	}
}
?>
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
<script type="text/JavaScript">
<!--
var browser=navigator.appName;
var b_version=navigator.appVersion;
var version=parseFloat(b_version.substring(21));
if(browser=="Microsoft Internet Explorer" && version<7) {
	document.write('<link href="/stylesheets/ie_6.css" rel="stylesheet" type="text/css" />');
}
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
    <div id="Main_Navigation">
      <ul>
        <li id="What"><a href="/index.php">What is it? </a></li>
        <li id="Services"><a href="/services.php">Services and Features</a></li>
        <li id="Create" class="Nav_Sel"><a href="/join.php">Create A New Account</a></li>
        <li id="Contact_Us"><a href="/contact_us.php">Contact Us</a></li>
      </ul>
    </div>
  </div>
  <div id="Main_Content">
    <div id="Main_Text_Long">
      <? $query_get_service = "SELECT `prod_id`, `prod_tiny`, `prod_name`, `prod_qty`, `prod_price`, `prod_fee` FROM `prod_products` WHERE `prod_service` = 'y' AND `prod_recur` = 'y' AND `prod_use` = 'y' ORDER BY `prod_name` ASC";
	$get_service = mysql_query($query_get_service, $cp_connection) or die(mysql_error());
	$sharing = array("15","10","7");
	$background = array("hdr_pink.jpg","hdr_blue.jpg","hdr_green.jpg");
	$n = 0; ?>
      <h1>Thank you for choosing Pro Image Software</h1>
      <p>Below you will find our prices and fees to get you started on your personal photo gallery today.</p>
      <form name="ProductForm" id="ProductForm" action="/enroll.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="Service_Level" id="Service_Level" value="" />
      </form>
      <? while($row_get_service = mysql_fetch_assoc($get_service)){ ?>
      <div style="float:left; margin-left:15px; margin-right:15px;">
        <table border="0" cellpadding="5" cellspacing="0" width="294">
          <tr>
            <th colspan="2" style="background:url(/images/<? echo $background[$n]; ?>); padding-top:10px; font-size:16px; height:56px; color:#333333; cursor:pointer" onclick="document.getElementById('Service_Level').value='<? echo $row_get_service['prod_id']; ?>'; document.getElementById('ProductForm').submit();"><strong style="padding:0px; margin:0px;"><? echo $row_get_service['prod_name']; ?></strong></th>
          </tr>
          <tr>
            <td colspan="2"><hr size="1" color="#ffffff" /></td>
          </tr>
          <tr>
            <td class="smallOrangeText"><strong>Storage:</strong></td>
            <td><? echo $row_get_service['prod_qty']; ?> Gb </td>
          </tr>
          <tr>
            <td colspan="2"><hr size="1" color="#ffffff" /></td>
          </tr>
          <tr>
            <td><span class="smallOrangeText"><strong>Monthly Fee: </strong></span></td>
            <td>$<? echo number_format($row_get_service['prod_price'],2,".",","); ?></td>
          </tr>
          <tr>
            <td colspan="2" class="smallOrangeText"><hr size="1" color="#ffffff" /></td>
          </tr>
          <tr>
            <td class="smallOrangeText"><strong>Set Up Fee: </strong></td>
            <td>$<? echo number_format($row_get_service['prod_fee'],2,".",","); ?></td>
          </tr>
          <tr>
            <td colspan="2" class="smallOrangeText"><hr size="1" color="#ffffff" /></td>
          </tr>
          <tr>
            <td class="smallOrangeText"><strong>Revenue Sharing:</strong></td>
            <td><? echo $sharing[$n]; $n++; ?>%&nbsp;</td>
          </tr>
          <tr>
            <td class="smallOrangeText"><strong>Credit Card<br />
              Processing Fee:</strong></td>
            <td><? echo "3"; ?>%</td>
          </tr>
        </table>
      </div>
      <? } ?>
      <br clear="all" />
    </div>
  </div>
</div>
<div id="Footer">
  <div style="float:left">
    <p> Photo Express ProImageSoftware </p>
  </div>
  <div style="float:left; margin-left:30px;">
    <p><a href="mailto:support@proimagesoftware.com" class="Footer_Nav">support@proimagesoftware.com</a></p>
  </div>
</div>
</body>
</html>
