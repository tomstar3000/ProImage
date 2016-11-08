<? 
$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
$r_path = "";
for($n=0;$n<$count;$n++)$r_path .= "../";

define('Allow Scripts',true);
require_once($r_path.'scripts/cart/ssl_paths.php'); 
if(isset($_POST['Controller']) && $_POST['Controller'] == "true"){
	require_once ($r_path.'checkout/process.php');
	die();
} else if(isset($_POST['Controller']) && $_POST['Controller'] == "review"){
	$is_enabled = false;
} else {
	$is_enabled = true;
}
session_start();
$cart = $_SESSION['cart'];
$photographer = $_SESSION['photo'];
$code = $_SESSION['code'];
if(isset($_POST['cartjoin'])){
	$cart = $_POST['cartjoin'];
	$photographer = $_POST['photographer'];
	$code = $_POST['code'];
	$_SESSION['cart'] = $cart;
	$_SESSION['photo'] = $photographer;
	$_SESSION['code'] = $code;
}
$code = "curry";  // Delete this line when Live.
$photographer = "walkaboutstudio";
$cart = explode("[+]",$cart);
$tempcart = array();
$total = 0;
$discount = 0;
$shipping = 0;
$extra = 0;
$tax = 0;
$grandtotal = 0;
require_once($r_path.'scripts/fnct_clean_entry.php');
require_once($r_path.'scripts/fnct_format_phone.php');
require_once($r_path.'Connections/cp_connection.php');
mysql_select_db($database_cp_connection, $cp_connection);
require_once($r_path.'scripts/cart/save_cust_info.php');
require_once($r_path.'scripts/cart/get_ship_comp.php');
require_once($r_path.'scripts/cart/get_ship_speeds.php');
require_once($r_path.'scripts/cart/get_ship_cost.php');

$query_get_info = "SELECT `cust_id`, `cust_fname`, `cust_mint`, `cust_lname`, `cust_suffix`, `cust_cname`, `cust_add`, `cust_add_2`, `cust_suite_apt`, `cust_city`, `cust_state`, `cust_zip`, `cust_country`, `cust_phone`, `cust_cell`, `cust_fax`, `cust_work`, `cust_ext`, `cust_email`, `cust_website`, `cust_thumb` FROM `cust_customers` WHERE `cust_handle` = '$photographer' LIMIT 0,1";
$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
$row_get_info = mysql_fetch_assoc($get_info);

$query_get_price = "SELECT `photo_event_price`.`photo_price`, `photo_event_price`.`photo_color`, `photo_event_price`.`photo_blk_wht`, `photo_event_price`.`photo_sepia`, `photo_event`.`photo_to_lab`, `photo_event`.`photo_at_lab`, `photo_event`.`photo_at_photo` FROM `cust_customers` INNER JOIN `photo_event` ON `photo_event`.`cust_id` = `cust_customers`.`cust_id` INNER JOIN `photo_event_price` ON `photo_event_price`.`photo_event_price_id` = `photo_event`.`event_price_id` WHERE `cust_handle` = '$photographer' AND `event_num` = '$code'";
$get_price = mysql_query($query_get_price, $cp_connection) or die(mysql_error());
$row_get_price = mysql_fetch_assoc($get_price);
$price = explode(",",$row_get_price['photo_price']);
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
<script type="text/javascript" src="/javascript/changebillingshipping.js"></script>
<script type="text/javascript" src="/javascript/set_tel_numbers.js"></script>
<script type="text/javascript">
function change_state(prefix,value){
	if(value == "USA"){
		document.getElementById(prefix+'State_Text').style.display='none';
		document.getElementById(prefix+'State_Select').style.display='block';
		document.getElementById(prefix+'State_Text').getElementsByTagName('input')[0].disabled=true;
		document.getElementById(prefix+'State_Select').getElementsByTagName('select')[0].disabled=false;
	} else {
		document.getElementById(prefix+'State_Text').style.display='block';
		document.getElementById(prefix+'State_Select').style.display='none';
		document.getElementById(prefix+'State_Text').getElementsByTagName('input')[0].disabled=false;
		document.getElementById(prefix+'State_Select').getElementsByTagName('select')[0].disabled=true;
	}
}
function checktax(){
	if(document.getElementById('Billing_Zip').value.length>=5){
		document.getElementById('Check_Out_Form').submit();
	}
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
      if(test.indexOf('isCheck')!=-1){
	  	if (document.getElementById(nm.replace(" ", "_")).checked == false) errors+='- You must accept the User Agreement.\n';
	  } else if (test.indexOf('isEmail')!=-1) { p=val.indexOf('@');
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
  <div id="Cart">
    <div id="Cart_Header">
      <div style="float:left; clear:left;">Order Review</div>
      <div style="float:right; margin-top:8px; margin-right:8px; clear:right"><a href="/photo_viewer.php?Photographer=<? echo $photographer;?>&code=<? echo $code;?>" style="border:none;"><img src="/images/btn_edit_cart.jpg" alt="Edit Shopping Cart" width="87" height="22" border="0"  style="border:none;" /></a></div>
    </div>
    <div id="Cart_Info_All">
      <? $n = 0;	$total = 0;
		foreach($cart as $k => $v){ 
			$cart_id = explode("-",$v);
			$cart_items = explode(",",$cart_id[1]);
			if($cart_id[2] == "I"){
				$query_get_item_info = "SELECT `image_name`, `image_large`, `image_tiny`, `image_folder` FROM `photo_event_images` WHERE `image_id` = '$cart_id[0]'";
				$get_item_info = mysql_query($query_get_item_info, $cp_connection) or die(mysql_error());
				$row_get_item_info = mysql_fetch_assoc($get_item_info);
				$folder = $row_get_item_info['image_folder'];
				$index = strrpos($folder,"/");
				$folder = substr($folder,0,$index);
				$index = strrpos($folder,"/");
				$folder = substr($folder,0,$index);
				ob_start();	?>
      <div id="Cart_Info" style="background:#<? echo($n==0)?"2c2c2c":"1c1c1c"; $n=($n==0)?1:0;  ?>">
        <p class="no_indent" style="padding:5px;"><? echo $row_get_item_info['image_large']; ?></p>
        <img src="<? echo "/".$folder."/Icon/".$row_get_item_info['image_tiny']; ?>" width="100" hspace="5" vspace="5" border="0" />
        <table border="0" cellpadding="0" cellspacing="0" width="275" align="center">
          <tr>
            <th>Sizes</th>
            <th>Prices</th>
            <th>As Is</th>
            <th>B&amp;W</th>
            <th style="border-right:none">Sepia</th>
          </tr>
          <? foreach($price as $key => $value){
							$cart_ids = explode(":",$cart_items[$key]);
							$cart_qty = explode(".",$cart_ids[1]);
							$temp_price = explode(":",$value);
							$query_get_price_info = "SELECT `prod_name` FROM `prod_products` WHERE `prod_id` = '$temp_price[0]'";
							$get_price_info = mysql_query($query_get_price_info, $cp_connection) or die(mysql_error());
							$row_get_price_info = mysql_fetch_assoc($get_price_info);
							$total_get_price_info = mysql_num_rows($get_price_info);
							if($total_get_price_info > 0 && ($cart_qty[0] || $cart_qty[1] || $cart_qty[2])){ ?>
          <tr>
            <td><? echo $row_get_price_info['prod_name']; ?></td>
            <td>$<? echo number_format($temp_price[1],2,".",","); 
						$total += $temp_price[1]*$cart_qty[0]+$temp_price[1]*$cart_qty[1]+$temp_price[1]*$cart_qty[2]; ?></td>
            <td align="center"><? echo ($cart_qty[0] == 0) ? "&nbsp;" : $cart_qty[0]; ?></td>
            <td align="center"><? echo ($cart_qty[1] == 0) ? "&nbsp;" : $cart_qty[1]; ?></td>
            <td style="border-right:none" align="center"><? echo ($cart_qty[2] == 0) ? "&nbsp;" : $cart_qty[2]; ?></td>
          </tr>
          <? } } ?>
        </table>
      </div>
      <? $item = ob_get_contents();
				ob_end_clean();
				echo $item;
			} else {
				ob_start();
				$Specs = explode(",",$cart_id[4]); ?>
      <div id="Cart_Info" style="background:#<? echo($n==0)?"2c2c2c":"1c1c1c"; $n=($n==0)?1:0;  ?>">
        <p class="no_indent" style="padding:5px;"><? echo $row_get_item_info['image_large']; ?></p>
        <img src="<? echo "/iconpreview.php?ImageId=".$cart_id[0]."&Border=".$Specs[0]."&Horizontal=".$Specs[1]."&NWidth=".$Specs[2]."&NHeight=".$Specs[3]."&BWidth=".$Specs[4]."&BHeight=".$Specs[5]."&SX=".$Specs[6]."&SY=".$Specs[7]."&Text=".$Specs[8]."&TX=".$Specs[9]."&TY=".$Specs[10]."&Font=".$Specs[11]."&Size=".$Specs[12]."&Color=".$Specs[13]."&Bold=".$Specs[14]."&Italic=".$Specs[15]; ?>" width="100" hspace="5" vspace="5" border="0" />
        <table border="0" cellpadding="0" cellspacing="0" width="275" align="center">
          <tr>
            <th>Sizes</th>
            <th>Prices</th>
            <th>As Is</th>
            <th>B&amp;W</th>
            <th style="border-right:none">Sepia</th>
          </tr>
          <? foreach($cart_items as $key => $value){
					$cart_ids = explode(":",$value);
					$cart_qty = explode(".",$cart_ids[1]);
					if($total_get_price_info > 0 && ($cart_qty[0] || $cart_qty[1] || $cart_qty[2])){ ?>
          <tr>
            <td><? echo urldecode($cart_ids[2]); ?></td>
            <td>$<? echo number_format(urldecode($cart_ids[3]),2,".",","); 
						$total += urldecode($cart_ids[3])*$cart_qty[0]+urldecode($cart_ids[3])*$cart_qty[1]+urldecode($cart_ids[3])*$cart_qty[2];
						?></td>
            <td align="center"><? echo ($cart_qty[0] == 0) ? "&nbsp;" : $cart_qty[0]; ?></td>
            <td align="center"><? echo ($cart_qty[1] == 0) ? "&nbsp;" : $cart_qty[1]; ?></td>
            <td style="border-right:none" align="center"><? echo ($cart_qty[2] == 0) ? "&nbsp;" : $cart_qty[2]; ?></td>
          </tr>
          <? } } ?>
        </table>
      </div>
      <? $item = ob_get_contents();
				ob_end_clean();
				echo $item;
			} }	?>
    </div>
    <div id="Cart_Total">
      <div style="float:left; margin-top:5px;"> $<? echo number_format($total,2,".",","); ?></div>
      <div style="float:right; clear:right; margin-top:2px;"><a href="/photo_viewer.php?Photographer=<? echo $photographer;?>&code=<? echo $code;?>" style="border:none;"><img src="/images/btn_edit_cart_2.jpg" alt="Edit Shopping Cart" width="86" height="22" border="0"  style="border:none;" /></a></div>
    </div>
  </div>
  <div style="float:left;">
    <div id="Cart_Content">
      <? if($message != "") { ?>
      <div style="margin:8px; height:29px; background-color:#FFFFFF; clear:both"><img src="/images/warning.jpg" width="30" height="29" border="0" align="absmiddle" style="float:left" /><img src="/images/warning.jpg" width="30" height="29" border="0" align="absmiddle"  style="float:right" />
        <p style="padding-bottom:0px; padding-left:3px; padding-right:0px; padding-top:5px; color:#990000; margin:0px;"><? echo $message; ?></p>
      </div>
      <? } ?>
      <form action="<? echo $_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']; ?>" name="Check_Out_Form" id="Check_Out_Form" method="post" enctype="multipart/form-data">
        <h1 style="margin:0px; padding:0px; float:right">
          <? if(!$is_enabled){ echo 'Review'; } else { ?>
          Checkout
          <? } $tab=1;?>
        </h1>
        <h1 style="margin:0px; padding:0px;">Personal Information</h1>
        <table border="0" cellpadding="0" cellspacing="3" width="500">
          <tr>
            <th><strong>
              <? if($is_enabled)echo "* "; ?>
              E-mail:</strong></th>
            <th><strong>
              <? if($is_enabled)echo "* "; ?>
              Phone Number: </strong></th>
          </tr>
          <tr>
            <td><? if(!$is_enabled){ echo $CheckOut[2]['Email']; } else { ?>
              <input type="text" name="Email" id="Email" value="<? echo $CheckOut[2]['Email'];?>" style="width:190px;" tabindex="<? echo $tab++; ?>" />
              <? } ?></td>
            <td><? if(!$is_enabled){ echo phone_number($CheckOut[2]['Phone']); } else { ?>
              (
              <input name="P1" type="text" id="P1" value="<? echo substr($CheckOut[2]['Phone'],0,3);?>" maxlength="3" onkeyup="set_tel_number('Phone_Number','P'); move_tel_number('P','1');" style="width:30px;" tabindex="<? echo $tab++; ?>" />
              )
              <input name="P2" type="text" id="P2" value="<? echo substr($CheckOut[2]['Phone'],3,3);?>" maxlength="3" onkeyup="set_tel_number('Phone_Number','P'); move_tel_number('P','2');" style="width:30px;" tabindex="<? echo $tab++; ?>" />
              -
              <input name="P3" type="text" id="P3" value="<? echo substr($CheckOut[2]['Phone'],6,4);?>" maxlength="4" onkeyup="set_tel_number('Phone_Number','P');" style="width:40px;" tabindex="<? echo $tab++; ?>" />
              <input type="hidden" name="Phone Number" id="Phone_Number" value="<? echo $CheckOut[2]['Phone'];?>" />
              <? } ?></td>
          </tr>
        </table>
        <h1 style="margin:0px; padding:0px;">Billing Information</h1>
        <table border="0" cellpadding="0" cellspacing="3" width="500">
          <tr>
            <th colspan="3"><strong>
              <? if($is_enabled)echo "* "; ?>
              Name on Card:</strong></th>
          </tr>
          <tr>
            <td colspan="3"><? if(!$is_enabled){ echo $CheckOut[0]['BFName']." ".$CheckOut[0]['BLName']; } else { ?>
              <input type="text" name="Billing First Name" id="Billing_First_Name" value="<? echo $CheckOut[0]['BFName'];?>" style="width:190px;" onblur="javascript:same_bill();" tabindex="<? echo $tab++; ?>" />
              <input type="text" name="Billing Last Name" id="Billing_Last_Name" value="<? echo $CheckOut[0]['BLName'];?>" style="width:190px;" onblur="javascript:same_bill();" tabindex="<? echo $tab++; ?>" />
              <? } ?></td>
          </tr>
          <tr>
            <th colspan="2"><strong>
              <? if($is_enabled)echo "* "; ?>
              Address:</strong></th>
            <th><strong>Suite / Apt: </strong></th>
          </tr>
          <tr>
            <td colspan="2"><? if(!$is_enabled){ echo $CheckOut[0]['BAdd']; } else { ?>
              <input type="text" name="Billing Address" id="Billing_Address" value="<? echo $CheckOut[0]['BAdd'];?>" style="width:280px;" onblur="javascript:same_bill();" tabindex="<? echo $tab++; ?>" />
              <? } ?></td>
            <td><? if(!$is_enabled){ echo $CheckOut[0]['BSuite']; } else { ?>
              <input type="text" name="Billing SuiteApt" id="Billing_SuiteApt" value="<? echo $CheckOut[0]['BSuite'];?>" style="width:75px;" onblur="javascript:same_bill();" tabindex="<? echo $tab++; ?>" />
              <? } ?></td>
          </tr>
          <tr>
            <td><strong>
              <? if($is_enabled)echo "* "; ?>
              City:</strong></td>
            <td><strong>
              <? if($is_enabled)echo "* "; ?>
              State:</strong></td>
            <td><strong>
              <? if($is_enabled)echo "* "; ?>
              Zip:</strong></td>
          </tr>
          <tr>
            <td><? if(!$is_enabled){echo $CheckOut[0]['BCity'];} else { ?>
              <input type="text" name="Billing City" id="Billing_City" value="<? echo $CheckOut[0]['BCity'];?>" style="width:170px;" onblur="javascript:same_bill();" tabindex="<? echo $tab++; ?>" />
              <? } ?></td>
            <td><? if(!$is_enabled){ echo $CheckOut[0]['BState']; } else {  ?>
              <div style="float:left; <? if($CheckOut[0]['BCount']!="USA")print(" display:none;"); ?>" id="Billing_State_Select">
                <? $query_get_states = "SELECT `state_short` FROM `a_states` WHERE `country_id` = '225' ORDER BY `state_short` ASC";
		$get_states = mysql_query($query_get_states, $cp_connection) or die(mysql_error());
		$SelVal = $CheckOut[0]['BState'];?>
                <select name="Billing State" id="Billing_State"<? if($CheckOut[0]['BCount']!="USA")print(" disabled=\"disabled\""); ?> tabindex="<? echo $tab++; ?>">
                  <? while($row_get_states = mysql_fetch_assoc($get_states)){ ?>
                  <option value="<? echo $row_get_states['state_short'] ?>"<? if($SelVal == $row_get_states['state_short']){print(' selected="selected"');}?>><? echo $row_get_states['state_short'] ?></option>
                  <? } ?>
                </select>
                <? mysql_free_result($get_states); ?>
              </div>
              <div style="float:left;<? if($CheckOut[0]['BCount']=="USA")print(" display:none;"); ?>" id="Billing_State_Text">
                <input name="Billing State" type="text" id="Billing_State" value="<? echo $SelVal;?>" size="3" maxlength="3"<? if($CheckOut[0]['BCount']=="USA"){print(" disabled=\"disabled\"");} ?> tabindex="<? echo $tab++; ?>" />
              </div>
              <? } ?></td>
            <td><? if(!$is_enabled){echo $CheckOut[0]['BZip'];} else { ?>
              <input name="Billing Zip" type="text" id="Billing_Zip" style="width:75px;" value="<? echo $CheckOut[0]['BZip'];?>" maxlength="5" onblur="javascript:same_bill();" tabindex="<? echo $tab++; ?>" />
              <? } ?></td>
          </tr>
          <tr>
            <th colspan="2"><strong>Country:</strong></th>
            <th><strong>Card Type: </strong></th>
          </tr>
          <tr>
            <td colspan="2"><? if(!$is_enabled){ echo $CheckOut[0]['BCount']; } else { 
						$query_get_country = "SELECT `country_short_3`, `country_name` FROM `a_country` WHERE `country_use` = 'y' ORDER BY `country_name` ASC";
						$get_country = mysql_query($query_get_country, $cp_connection) or die(mysql_error());
						$SelVal = $CheckOut[0]['BCount']; ?>
              <select name="Billing Country" id="Billing_Country" tabindex="<? echo $tab++; ?>" onchange="javascript:change_state('Billing_',this.value);" onblur="javascript:same_bill();">
                <option value="0"> -- Select Country -- </option>
                <? while($row_get_country = mysql_fetch_assoc($get_country)){ ?>
                <option value="<? echo $row_get_country['country_short_3']; ?>"<? if($SelVal == $row_get_country['country_short_3']){print(' selected="selected"');}?>><? echo $row_get_country['country_name']; ?></option>
                <? } ?>
              </select>
              <? } ?></td>
            <td><? $query_get_cards = "SELECT `cc_type_id`, `cc_type_name` FROM `billship_cc_types` WHERE `cc_accept` = 'y' ORDER BY `cc_order` ASC";
				 $get_cards = mysql_query($query_get_cards, $cp_connection) or die(mysql_error()); 
				 if(!$is_enabled){ while($row_get_cards = mysql_fetch_assoc($get_cards)){
							if($CheckOut[0]['CCT']==$row_get_cards['cc_type_id'])echo $row_get_cards['cc_type_name'];
						} } else { ?>
              <select name="Credit Card Type" id="Credit_Card_Type" style="width:120px;" tabindex="<? echo $tab++; ?>">
                <? while($row_get_cards = mysql_fetch_assoc($get_cards)){ ?>
                <option value="<? echo $row_get_cards['cc_type_id']; ?>"<? if($CheckOut[0]['CCT']==$row_get_cards['cc_type_id'])echo ' selected="selected"'; ?>><? echo $row_get_cards['cc_type_name']; ?></option>
                <? } ?>
              </select>
              <? } ?></td>
          </tr>
          <tr>
            <th><strong>
              <? if($is_enabled)echo "* "; ?>
              Credit Card Number - (xxxxxxxxxxxxxxxx) </strong></th>
            <th><strong>
              <? if($is_enabled)echo "* "; ?>
              Security Code:</strong></th>
            <th><strong>Experation Date:</strong></th>
          </tr>
          <tr>
            <td><? if(!$is_enabled){ echo "xxxx-xxxx-xxxx-".$CheckOut[0]['CC4Num']; } else { ?>
              <input name="Credit Card Number" type="text" id="Credit_Card_Number" style="width:190px;" maxlength="16" value="<? echo clean_variable($_POST['Credit_Card_Number'],true);?>" tabindex="<? echo $tab++; ?>" />
              <? } ?></td>
            <td><? if(!$is_enabled){ echo $CheckOut[0]['CCV']; } else { ?>
              <input name="CCV Code" type="text" id="CCV_Code" style="width:45px;" value="<? echo $CheckOut[0]['CCV'];?>" maxlength="4" tabindex="<? echo $tab++; ?>" />
              <? } ?></td>
            <td><? if(!$is_enabled){ echo $CheckOut[0]['CCM']." / ".$CheckOut[0]['CCY']; } else { ?>
              <select name="Expiration Month" id="Expiration_Month" tabindex="<? echo $tab++; ?>">
                <? for($n=1;$n<=12;$n++){ ?>
                <option value="<? echo $n; ?>"<? if($CheckOut[0]['CCM']==$n)echo ' selected="selected"';?>><? echo $n; ?></option>
                <? } ?>
              </select>
              <select name="Expiration Year" id="Expiration_Year" tabindex="<? echo $tab++; ?>">
                <? $date = date("Y");
				for($n=$date;$n<=($date+5);$n++){ ?>
                <option value="<? echo $n; ?>"<? if($CheckOut[0]['CCY']==$n)echo ' selected="selected"';?>><? echo $n; ?></option>
                <? } ?>
              </select>
              <? } ?></td>
          </tr>
        </table>
        <div style="width:450px; clear:both;">
          <div style="float:left;">
            <h1 style="margin:0px; padding:0px;">Shipping Information</h1>
          </div>
          <? if($is_enabled){ ?>
          <div style="float:right;">
            <p style="margin:0px;">
              <input type="checkbox" name="Same_as_Billing" id="Same_as_Billing" class="no_border" value="y" onclick="javascript:same_bill();" tabindex="<? echo $tab++; ?>"<? if($ship_same)echo ' checked="checked"';?>  />
              Same as Billing</p>
          </div>
          <? } ?>
          <br clear="all" />
        </div>
        <table border="0" cellpadding="0" cellspacing="3" width="500">
          <tr>
            <th colspan="3"><strong>
              <? if($is_enabled)echo "* "; ?>
              Name:</strong></th>
          </tr>
          <tr>
            <td colspan="3"><? if(!$is_enabled){ echo $CheckOut[1]['SFName']." ".$CheckOut[1]['SLName'];; } else { ?>
              <input type="text" name="Shipping First Name" id="Shipping_First_Name" value="<? echo $CheckOut[1]['SFName'];?>" style="width:190px;" tabindex="<? echo $tab++; ?>"<? if($ship_same)echo ' disabled="disabled""';?> />
              <input type="text" name="Shipping Last Name" id="Shipping_Last_Name" value="<? echo $CheckOut[1]['SLName'];?>" style="width:190px;" tabindex="<? echo $tab++; ?>"<? if($ship_same)echo ' disabled="disabled""';?> />
              <? } ?></td>
          </tr>
          <tr>
            <th colspan="2"><strong>
              <? if($is_enabled)echo "* "; ?>
              Address:</strong></th>
            <th><strong>Suite / Apt:</strong></th>
          </tr>
          <tr>
            <td colspan="2"><? if(!$is_enabled){ echo $CheckOut[1]['SAdd']; } else { ?>
              <input type="text" name="Shipping Address" id="Shipping_Address" value="<? echo $CheckOut[1]['SAdd'];?>" style="width:280px;" tabindex="<? echo $tab++; ?>"<? if($ship_same)echo ' disabled="disabled""';?> />
              <? } ?></td>
            <td><? if(!$is_enabled){ echo $CheckOut[1]['SSuite']; } else { ?>
              <input type="text" name="Shipping SuiteApt" id="Shipping_SuiteApt" value="<? echo $CheckOut[1]['SSuite'];?>" style="width:75px;" tabindex="<? echo $tab++; ?>"<? if($ship_same)echo ' disabled="disabled""';?> />
              <? } ?></td>
          </tr>
          <tr>
            <td><strong>
              <? if($is_enabled)echo "* "; ?>
              City:</strong></td>
            <td><strong>
              <? if($is_enabled)echo "* "; ?>
              State:</strong></td>
            <td><strong>
              <? if($is_enabled)echo "* "; ?>
              Zip:</strong></td>
          </tr>
          <tr>
            <td><? if(!$is_enabled){ echo $CheckOut[1]['SCity']; } else { ?>
              <input type="text" name="Shipping City" id="Shipping_City" value="<? echo $CheckOut[1]['SCity'];?>" style="width:170px;" tabindex="<? echo $tab++; ?>"<? if($ship_same)echo ' disabled="disabled""';?> />
              <? } ?></td>
            <td><? if(!$is_enabled){ echo $CheckOut[1]['SState']; } else {  ?>
              <div style="float:left; <? if($CheckOut[1]['SCount']!="USA")print(" display:none;"); ?>" id="Shipping_State_Select">
                <? $query_get_states = "SELECT `state_short` FROM `a_states` WHERE `country_id` = '225' ORDER BY `state_short` ASC";
		$get_states = mysql_query($query_get_states, $cp_connection) or die(mysql_error());
		$SelVal = $CheckOut[1]['SState'];?>
                <select name="Shipping State" id="Shipping_State"<? if($CheckOut[1]['SCount']!="USA")print(" disabled=\"disabled\""); ?> tabindex="<? echo $tab++; ?>"<? if($ship_same)echo ' disabled="disabled""';?>>
                  <? while($row_get_states = mysql_fetch_assoc($get_states)){ ?>
                  <option value="<? echo $row_get_states['state_short'] ?>"<? if($SelVal == $row_get_states['state_short']){print(' selected="selected"');}?>><? echo $row_get_states['state_short'] ?></option>
                  <? } ?>
                </select>
                <? mysql_free_result($get_states); ?>
              </div>
              <div style="float:left;<? if($CheckOut[1]['SCount']=="USA")print(" display:none;"); ?>" id="Shipping_State_Text">
                <input name="Shipping State" type="text" id="Shipping_State" value="<? echo $SelVal;?>" size="3" maxlength="3"<? if($CheckOut[0]['BCount']=="USA"){print(" disabled=\"disabled\"");} ?> tabindex="<? echo $tab++; ?>"<? if($ship_same)echo ' disabled="disabled""';?> />
              </div>
              <? } ?>
            </td>
            <td><? if(!$is_enabled){ echo $CheckOut[1]['SZip']; } else { ?>
              <input name="Shipping Zip" type="text" id="Shipping_Zip" style="width:75px;" value="<? echo $CheckOut[1]['SZip'];?>" maxlength="5" tabindex="<? echo $tab++; ?>"<? if($ship_same)echo ' disabled="disabled""';?> />
              <? } ?></td>
          </tr>
          <tr>
            <td colspan="3"><? if(!$is_enabled){ echo $CheckOut[1]['SCount']; } else { 
						$query_get_country = "SELECT `country_short_3`, `country_name` FROM `a_country` WHERE `country_use` = 'y' ORDER BY `country_name` ASC";
						$get_country = mysql_query($query_get_country, $cp_connection) or die(mysql_error());
						$SelVal = $CheckOut[1]['SCount']; ?>
              <select name="Shipping_Country" id="Shipping_Country" tabindex="<? echo $tab+($tabadd++); ?>" onchange="javascript:change_state('Shipping_',this.value);" onblur="javascript:same_bill();"<? if($ship_same)echo ' disabled="disabled""';?>>
                <option value="0"> -- Select Country -- </option>
                <? while($row_get_country = mysql_fetch_assoc($get_country)){ ?>
                <option value="<? echo $row_get_country['country_short_3']; ?>"<? if($SelVal == $row_get_country['country_short_3']){print(' selected="selected"');}?>><? echo $row_get_country['country_name']; ?></option>
                <? } ?>
              </select>
              <? } ?></td>
          </tr>
          <tr>
            <th><strong>Shipping Speed Information:</strong></th>
            <th colspan="2"><strong>Discount Code:</strong></th>
          </tr>
          <tr>
            <td><? if(!$is_enabled){ 
						if($speed < 0){
								echo ($speed == -1) ? "Pick up at Lab" : "Pick up at Studio";
						} else {
						while($row_get_ship_comp = mysql_fetch_assoc($get_ship_comp)){
							if($ship_comp==$row_get_ship_comp['ship_comp_id'])echo $row_get_ship_comp['ship_comp_name']." ";
						}
						while($row_ship_speeds = mysql_fetch_assoc($ship_speeds)){
							if($speed==$row_ship_speeds['ship_speed_id'])echo $row_ship_speeds['ship_speed_name'];
						}}} else { ?>
              <select name="Shipping Company" id="Shipping_Company" style="width:120px;" tabindex="<? echo $tab++; ?>" onchange="document.getElementById('Check_Out_Form').submit();">
                <? while($row_get_ship_comp = mysql_fetch_assoc($get_ship_comp)){ ?>
                <option value="<? echo $row_get_ship_comp['ship_comp_id']; ?>"<? if($ship_comp==$row_get_ship_comp['ship_comp_id'])echo ' selected="selected"';?>><? echo $row_get_ship_comp['ship_comp_name']; ?></option>
                <? } if($row_get_price['photo_at_lab'] == 'y'){ ?>
                <option value="7"<? if($ship_comp==7)echo ' selected="selected"';?>>Pick up at Lab</option>
                <? } if($row_get_price['photo_at_photo'] == 'y'){ ?>
                <option value="8"<? if($ship_comp==8)echo ' selected="selected"';?>>Pick up at Studio</option>
                <? } ?>
              </select>
              <input type="hidden" name="Shipping_Company_id" id="Shipping_Company_id" value="<? echo $ship_comp; ?>" onchange="document.getElementById('Check_Out_Form').submit();" />
              <? if($ship_comp<7) { ?>
              <select name="Shipping Speed" id="Shipping_Speed" style="width:120px;" tabindex="<? echo $tab++; ?>" onchange="document.getElementById('Check_Out_Form').submit();">
                <? while($row_ship_speeds = mysql_fetch_assoc($ship_speeds)){ ?>
                <option value="<? echo $row_ship_speeds['ship_speed_id']; ?>"<? if($speed==$row_ship_speeds['ship_speed_id'])echo ' selected="selected"';?>><? echo $row_ship_speeds['ship_speed_name']; ?></option>
                <? } ?>
              </select>
              <? } } ?></td>
            <td colspan="2"><? if(!$is_enabled){if($CheckOut[2]['discode']!="")echo $CheckOut[2]['discode']." - ".$CheckOut[2]['disname']; } else { ?>
              <input name="Discount Code" type="text" id="Discount_Code" style="width:75px;" value="<? echo $CheckOut[2]['discode'];?>" tabindex="<? echo $tab++; ?>" />
              <? if($CheckOut[2]['discode']!="" && $message == "")echo " - ".$CheckOut[2]['disname']; ?>
              <? } ?></td>
          </tr>
        </table>
        <div style="margin-top:15px; clear:both">
          <? if($is_enabled){ ?>
          <div style="float:left; width:500px;">
            <p class="no_indent">
              <input name="User Agreement" type="checkbox" class="no_border" id="User_Agreement" value="y" />
              I accept and understand that this order is final and will not be refunded without first deducting a credit card
              fee of 6% of total order. </p>
          </div>
          <? } ?>
          <div style="float:right; width:150px;">
            <div style="float:left; margin-right:10px;">
              <p class="no_indent">Total:<br />
                Discount:<br />
                Shipping:<br />
                Tax:<br />
                <strong>Grand Total:</strong> </p>
            </div>
            <div style="float:left">
              <p class="no_indent">$ <? echo number_format($total,2,".",","); ?>
                <input type="hidden" name="Total" id="Total" value="<? echo $total; ?>" />
                <br />
                $
                <? if($CheckOut[2]['discPrice'] != 0){
							$discount = ($CheckOut[2]['discPrice']>$total) ? $total : $CheckOut[2]['discPrice'];
						} else {
							$discount = $total*($CheckOut[2]['discPerc']/100);
						}
						echo number_format($discount,2,".",","); ?>
                <input type="hidden" name="Discount" id="Discount" value="<? echo $discount; ?>" />
                <br />
                $ <? echo number_format($shipping,2,".",","); ?>
                <input type="hidden" name="Shipping" id="Shipping" value="<? echo $shipping; ?>" />
                <br />
                $
                <?  require_once($r_path.'scripts/cart/calc_tax.php'); 
						echo number_format($tax,2,".",","); ?>
                <input type="hidden" name="Tax" id="Tax" value="<? echo $tax; ?>" />
                <br />
                <? $grandtotal = $total-$discount+$shipping+$tax; ?>
                <strong>$ <? echo number_format($grandtotal,2,".",","); ?></strong></p>
            </div>
          </div>
        </div>
        <br clear="all" />
        <div style="clear:both; float:left;">
          <p class="no_indent">Insutructions to the photographer:<br />
					<? if(!$is_enabled){ echo $CheckOut[2]['Comm']; } else { ?>
            <textarea name="Comments" id="Comments" style="width:250px; height:25px;"><? echo $CheckOut[2]['Comm'];?></textarea>
						<? } ?>
          </p>
        </div>
        <br clear="all" />
        <input type="hidden" name="Controller" id="Controller" value="false" />
      </form>
    </div>
    <div id="Cart_Submit">
      <? if(!$is_enabled){ ?>
      <img src="/images/btn_edit_info.jpg" width="86" height="22" hspace="5" style="cursor:pointer" onclick="document.getElementById('Controller').value='edit'; document.getElementById('Check_Out_Form').submit();" />
      <? } ?>
      <img src="/images/btn_submit_order.jpg" width="86" height="22" style="cursor:pointer" onclick="<? if($is_enabled){ ?>MM_validateForm('Email2','','R','P1','','RisNum','P2','','RisNum','P3','','RisNum','Billing_First_Name','','R','Billing_Last_Name','','R','Billing_Address','','R','Billing_City','','R','Billing_State','','R','Billing_Zip','','RisNum','Credit_Card_Number','','RisNum','CCV_Code','','R','Shipping_First_Name','','R','Shipping_Last_Name','','R','Shipping_Address','','R','Shipping_City','','R','Shipping_Zip','','RisNum','User_Agreement','','RisCheck'); if(document.MM_returnValue){<? } ?>document.getElementById('Controller').value='<? if(!$is_enabled){ echo 'true'; } else { ?>review<? } ?>'; document.getElementById('Check_Out_Form').submit(); <? if($is_enabled){ ?>}<? } ?>" /> </div>
  </div>
</div>
<div id="Footer">
  <div style="float:left">
    <p><? echo $row_get_info['cust_cname'];?></p>
    <p><? echo $row_get_info['cust_add']; if($row_get_info['cust_suite_apt']!="")echo " Suite/Apt ".$row_get_info['cust_suite_apt'];?></p>
    <? if($row_get_info['cust_add_2']!="")echo "<p>".$row_get_info['cust_add_2']."</p>";?>
    <p><? echo $row_get_info['cust_city']." ".$row_get_info['cust_state']." ".$row_get_info['cust_zip'];?></p>
  </div>
  <div style="float:left; margin-left:30px;">
    <p><a href="mailto:<? echo $row_get_info['cust_email'];?>" class="Footer_Nav"><? echo $row_get_info['cust_email'];?></a> </p>
    <p><a href="http://<? echo $row_get_info['cust_website'];?>" target="_blank" class="Footer_Nav"><? echo $row_get_info['cust_website'];?></a></p>
    <p><? echo phone_number($row_get_info['cust_work']); if($row_get_info['cust_ext']!="0")echo " Ext. ".$row_get_info['cust_ext'];?></p>
  </div>
</div>
</body>
</html>
<? //$cart = implode("[+]",$tempcart);
$_SESSION['cart'] = implode("[+]",$cart); ?>
