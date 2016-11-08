<?
$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-1;
$r_path = "";
for($n=0;$n<$count;$n++){
	$r_path .= "../";
}
define('Allow Scripts',true);
require_once($r_path.'scripts/cart/ssl_paths.php');
require_once($r_path.'scripts/fnct_clean_entry.php');
if(!isset($handle) && isset($_POST['Photographer'])){
	require_once($r_path.'Connections/cp_connection.php');
	mysql_select_db($database_cp_connection, $cp_connection);
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
        <li id="Services" class="Nav_Sel"><a href="/services.php">Services and Features</a></li>
        <li id="Create"><a href="/join.php">Create A New Account</a></li>
        <li id="Contact_Us"><a href="/contact_us.php">Contact Us</a></li>
      </ul>
    </div>
  </div>
  <div id="Main_Content">
    <script type="text/javascript">
		function Show_Div(showthis, hidethis){
			document.getElementById(showthis).style.display = "block";
			document.getElementById(hidethis).style.display = "none";
		}
		</script>
    <div id="Main_Text">
      <h1>Services and Features </h1>
      <p>Click a feature to learn more.</p>
      <div id="Features"><span id="Feat_Upload"><a href="#Fees" onmouseover="javascript:Show_Div('Info_Upload','Event');" onmouseout="javascript:Show_Div('Event','Info_Upload');">Fees.</a></span><span id="Feat_Store"><a href="#Event_Manager" onmouseover="javascript:Show_Div('Info_Store','Event');" onmouseout="javascript:Show_Div('Event','Info_Store');">Event
            Manager.</a></span><span id="Feat_Organize"><a href="#Orders" onmouseover="javascript:Show_Div('Info_Organize','Event');" onmouseout="javascript:Show_Div('Event','Info_Organize');">Orders.</a></span><span id="Feat_Ship"><a href="#Printing" onmouseover="javascript:Show_Div('Info_Ship','Event');" onmouseout="javascript:Show_Div('Event','Info_Ship');">Printing.</a></span><span id="Feat_Cash"><a href="#Clients" onmouseover="javascript:Show_Div('Info_Cash','Event');" onmouseout="javascript:Show_Div('Event','Info_Cash');">Clients.</a></span><br clear="all" />
        <span id="Feat_Track"><a href="#Guestbook" onmouseover="javascript:Show_Div('Info_Track','Event');" onmouseout="javascript:Show_Div('Event','Info_Track');">Guestbook
        Manager.</a></span><span id="Feat_Exposure"><a href="#Products" onmouseover="javascript:Show_Div('Info_Exposure','Event');" onmouseout="javascript:Show_Div('Event','Info_Exposure');">Products &amp; Pricing.</a></span><span id="Feat_Print"><a href="#Reports" onmouseover="javascript:Show_Div('Info_Print','Event');" onmouseout="javascript:Show_Div('Event','Info_Print');">Reports.</a></span><span id="Feat_Customize"><a href="#Website" onmouseover="javascript:Show_Div('Info_Customize','Event');" onmouseout="javascript:Show_Div('Event','Info_Customize');">Website
        Manager.</a></span><span id="Feat_Support"><a href="#Support" onmouseover="javascript:Show_Div('Info_Support','Event');" onmouseout="javascript:Show_Div('Event','Info_Support');">Support.</a></span><br clear="all" />
      </div>
      <p class="no_indent" id="Fees"><strong>Fees<br />
        </strong>Pro Imaging Software is geared to make you more money and keep your profit margin higher at the same time making
        less work for you! With our three simple pricing plans you can choose the right solution for your business. </p>
      <table border="0" cellspacing="0" cellpadding="0" width="100%">
        <tr>
          <td>&nbsp;</td>
          <td><strong>Portrait Plan</strong></td>
          <td><strong>Wedding Plan</strong></td>
          <td><strong>&nbsp;Wedding Plus Plan</strong></td>
        </tr>
        <tr>
          <td><strong>Storage</strong></td>
          <td>Up to 10 GB</td>
          <td>10-49 GB</td>
          <td>Unlimited</td>
        </tr>
        <tr>
          <td><strong>Monthly Fee</strong></td>
          <td>$0.00</td>
          <td>$35.00</td>
          <td>$50.00</td>
        </tr>
        <tr>
          <td><strong>Revenue Share</strong></td>
          <td>15%</td>
          <td>10%</td>
          <td>7%</td>
        </tr>
        <tr>
          <td><strong>Credit Card Fee</strong></td>
          <td>3%</td>
          <td>3%</td>
          <td>3%</td>
        </tr>
        <tr>
          <td><strong>Start Up Fee</strong></td>
          <td>$50.00</td>
          <td>$35.00</td>
          <td>$0.00</td>
        </tr>
      </table>
      <p class="no_indent" id="Event_Manager"><strong>Event Manager</strong><br />
        Create, organize and manage events. Our event creator system is so easy to use. All you have to do is put in an event
          name, attach a price structure, choose an event code, event date, expiration date, description and choose which folders
          to upload to the site. Once the site has finished uploading full resolution images, the site is ready for viewing and
          ordering. </p>
      <p class="no_indent" id="Orders"><strong>Orders</strong><br />
        When your client places an order, confirmation is sent to the client, the photographer and the lab. You the photographer
          now have the ability to fix the image before sending it to the lab if necessary. All you need to do is login to your
          admin, fix the image and re-upload it to the site. No corrections needed? No problem you can automate all orders to
          be sent directly to the lab at any time for any event. Need to find an order? Just enter the invoice number, client
          name, phone or email and our system will find the order for you!</p>
      <p class="no_indent" id="Printing"><strong>Printing</strong><br />
        Once the order is sent to the lab you can rest assured that your photography is now in good hands. Photo Express will
          print the order with the personal touch that you require. With our professional printers and top of the line equipment
          you are guaranteed a perfect print every time! Once the order is printed to perfection it will then be professionally
          packaged in a black envelope wrapped in tissue paper and sent out in a black box. We want your photography to be treated
          like a gift is given to each client. Feel free to personalize your packages with stickers of your choice! </p>
      <p class="no_indent" id="Clients"><strong>Clients</strong><br />
        Your clients are our clients and will be notified at every step of the order to ensure confidence that their purchase
          is in good hands. You can also track the order status as it passes through each phase! Within the admin clients can
          also be tracked and you can see what they are ordering. </p>
      <p class="no_indent" id="Guestbook"><strong>Guestbook Manager</strong><br />
        Marketing is the key to success in business! Photographers that use email marketing, which costs absolutely nothing, increase
          their sales exponentially. With the Guestbook Manager you can keep track of who has viewed each event and send out periodic
          emails reminding them to place their orders, give special promotions for money off and let them know when the event
          is about to expire. </p>
      <p class="no_indent" id="Products"><strong>Products &amp;  Pricing</strong><br />
        Do you need to charge different prices for different events? In products and pricing you can set up as many price structures
          as you like, set up promotions, discount codes and give credits to certain clients. You will also see what you are being
          charged by the lab for the products you sell. </p>
      <p class="no_indent" id="Reports"><strong>Reports</strong><br />
        Knowledge is an invaluable tool. Know what events are your money makers and why clients are buying. Within your reports
          you will be able to access sales data, monthly, daily, yearly, by event, by client and much more! See what your costs
          are and overall profit. </p>
      <p class="no_indent" id="Website"><strong>Website Manager</strong><br />
        Obviously you would like to integrate your look to the site with your personal branding and images. You can do all that
          with our site. You can also use this site as your entire website if you like by adding pages and content. Change this
          out when you like as often as you like at no additional charge to you! </p>
      <p class="no_indent" id="Support"><strong>Support</strong><br />
        The key to any website ordering process is great customer support. You may not have all the answers to questions people
          may have so we will funnel them to us for support related questions. Support questions will be answered either by email
          or by phone (depending on the situation) in a timely and professional manner. We want to help your clients place their
          orders! </p>
    </div>
    <div id="Event">
      <h2>Photographer Login </h2>
			<? if(isset($_COOKIE['AdminLog'])){ ?>
			<a href="/control_panel/control_panel.php<? echo $token; ?>">Log-in to your Control Panel</a>
			<? } else { ?>
      <form action="/control_panel/index.php" name="LogIn_Form" id="LogIn_Form" method="post" enctype="multipart/form-data">
        <p class="no_indent">
          <? if(isset($message)){ ?>
        </p>
        <div style="margin:8px; padding-right:5px; padding-top:5px; padding-bottom:5px; height:auto; background-color:#FFFFFF; clear:both"><img src="/images/warning.jpg" width="30" height="29" border="0" align="absmiddle" style="float:left; padding-right:3px;" />
            <p class="no_indent" style="color:#990000; margin:0px; clear:right"><? echo $message; ?></p>
        </div>
        <? } ?>
        <input name="Username" id="Username" type="text" value="username" onclick="javascript:this.value=''" onchange="javascript:document.getElementById('Password').value=''" />
        <br />
        <input name="Password" id="Password" type="password" value="password" onclick="javascript:this.value=''" />
        <input type="hidden" name="Controller" id="Controller" value="true" />
        <img src="/images/btn_enter.jpg" width="50" height="21" align="absmiddle" onclick="MM_validateForm('Username','','R','Password','','R');if(document.MM_returnValue==true){document.getElementById('LogIn_Form').submit();}" />
        </p>
        <p><a href="/lookup.php">Forgot Your Password</a> </p>
        <div style="height:1px; overflow:hidden"><br />
            <input type="submit" name="btn_submit2" id="btn_submit2" value="submit" />
        </div>
      </form>
			<? } ?>
      <h2>Photographer Code </h2>
			<? if(isset($Error)){ ?>
        <div style="margin:8px; padding-right:5px; padding-top:5px; padding-bottom:5px; height:auto; background-color:#FFFFFF; clear:both"><img src="/images/warning.jpg" width="30" height="29" border="0" align="absmiddle" style="float:left; padding-right:3px;" />
            <p class="no_indent" style="color:#990000; margin:0px; clear:right"><? echo $Error; ?></p>
        </div>
        <? } ?>
      <form method="post" name="Photographer_Code_Form" id="Photographer_Code_Form" action="<? echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
        <input name="Photographer" id="Photographer" type="text" value="photographer code" onclick="javascript:this.value=''" />
        <img src="/images/btn_enter.jpg" width="50" height="21" align="absmiddle" onclick="MM_validateForm('Photographer','','R');if(document.MM_returnValue==true){document.getElementById('Photographer_Code_Form').submit();}" style="cursor:pointer;" />
      </form>
    </div>
    <div id="Info_Upload">
      <p class="no_indent"><strong>Fees<br />
        </strong>Pro Imaging Software is geared to make you more money and keep your profit margin higher at the same time making
        less work for you! With our three simple pricing plans you can choose the right solution for your business. </p>
      <table border="0" cellspacing="0" cellpadding="0" width="100%">
        <tr>
          <td>&nbsp;</td>
          <td><strong>Portrait Plan</strong></td>
          <td><strong>Wedding Plan</strong></td>
          <td><strong>&nbsp;Wedding Plus Plan</strong></td>
        </tr>
        <tr>
          <td><strong>Storage</strong></td>
          <td>Up to 10 GB</td>
          <td>10-49 GB</td>
          <td>Unlimited</td>
        </tr>
        <tr>
          <td><strong>Monthly Fee</strong></td>
          <td>$0.00</td>
          <td>$35.00</td>
          <td>$50.00</td>
        </tr>
        <tr>
          <td><strong>Revenue Share</strong></td>
          <td>15%</td>
          <td>10%</td>
          <td>7%</td>
        </tr>
        <tr>
          <td><strong>Credit Card Fee</strong></td>
          <td>3%</td>
          <td>3%</td>
          <td>3%</td>
        </tr>
        <tr>
          <td><strong>Start Up Fee</strong></td>
          <td>$50.00</td>
          <td>$35.00</td>
          <td>$0.00</td>
        </tr>
      </table>
    </div>
    <div id="Info_Store">
      <p class="no_indent"><strong>Event Manager</strong><br />
        Create, organize and manage events. Our event creator system is so easy to use. All you have to do is put in an event
          name, attach a price structure, choose an event code, event date, expiration date, description and choose which folders
          to upload to the site. Once the site has finished uploading full resolution images, the site is ready for viewing and
          ordering. </p>
    </div>
    <div id="Info_Organize">
      <p class="no_indent"><strong>Orders</strong><br />
        When your client places an order, confirmation is sent to the client, the photographer and the lab. You the photographer
          now have the ability to fix the image before sending it to the lab if necessary. All you need to do is login to your
          admin, fix the image and re-upload it to the site. No corrections needed? No problem you can automate all orders to
          be sent directly to the lab at any time for any event. Need to find an order? Just enter the invoice number, client
          name, phone or email and our system will find the order for you!</p>
    </div>
    <div id="Info_Ship">
      <p class="no_indent"><strong>Printing</strong><br />
        Once the order is sent to the lab you can rest assured that your photography is now in good hands. Photo Express will
          print the order with the personal touch that you require. With our professional printers and top of the line equipment
          you are guaranteed a perfect print every time! Once the order is printed to perfection it will then be professionally
          packaged in a black envelope wrapped in tissue paper and sent out in a black box. We want your photography to be treated
          like a gift is given to each client. Feel free to personalize your packages with stickers of your choice! </p>
    </div>
    <div id="Info_Cash">
      <p class="no_indent"><strong>Clients</strong><br />
        Your clients are our clients and will be notified at every step of the order to ensure confidence that their purchase
          is in good hands. You can also track the order status as it passes through each phase! Within the admin clients can
          also be tracked and you can see what they are ordering. </p>
    </div>
    <div id="Info_Track">
      <p class="no_indent"><strong>Guestbook Manager</strong><br />
        Marketing is the key to success in business! Photographers that use email marketing, which costs absolutely nothing, increase
          their sales exponentially. With the Guestbook Manager you can keep track of who has viewed each event and send out periodic
          emails reminding them to place their orders, give special promotions for money off and let them know when the event
          is about to expire. </p>
    </div>
    <div id="Info_Exposure">
      <p class="no_indent"><strong>Products &amp;  Pricing</strong><br />
        Do you need to charge different prices for different events? In products and pricing you can set up as many price structures
          as you like, set up promotions, discount codes and give credits to certain clients. You will also see what you are being
          charged by the lab for the products you sell. </p>
    </div>
    <div id="Info_Print">
      <p class="no_indent"><strong>Reports</strong><br />
        Knowledge is an invaluable tool. Know what events are your money makers and why clients are buying. Within your reports
          you will be able to access sales data, monthly, daily, yearly, by event, by client and much more! See what your costs
          are and overall profit. </p>
    </div>
    <div id="Info_Customize">
      <p class="no_indent"><strong>Website Manager</strong><br />
        Obviously you would like to integrate your look to the site with your personal branding and images. You can do all that
          with our site. You can also use this site as your entire website if you like by adding pages and content. Change this
          out when you like as often as you like at no additional charge to you! </p>
    </div>
    <div id="Info_Support">
      <p class="no_indent"><strong>Support</strong><br />
        The key to any website ordering process is great customer support. You may not have all the answers to questions people
          may have so we will funnel them to us for support related questions. Support questions will be answered either by email
          or by phone (depending on the situation) in a timely and professional manner. We want to help your clients place their
          orders! </p>
    </div>
    <br clear="all" />
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
