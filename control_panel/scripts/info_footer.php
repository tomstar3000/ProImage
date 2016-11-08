<? if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++) $r_path .= "../";
}
include $r_path.'security.php';
require_once $r_path.'scripts/fnct_format_phone.php';
$is_enabled = ((count($path)<=3 && $cont == "view") || (count($path)>3)) ? false : true;
$is_back = ($cont == "edit") ? "view" : "query"; ?>

<div id="Sub_Header"> <img src="/control_panel/images/hdr_form_left.jpg" width="17" height="33" border="0" align="left" />
 <h2>Home Page Information</h2>
</div>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
 <tr>
  <td><strong>Footer Image: </strong></td>
  <td><input type="file" name="Image" id="Image">
   <input type="hidden" name="Image_val" id="Image_val" value="<? echo $Imagev;?>">
   <? if($Imagev != ""){?>
   &nbsp;<a href="<? echo "/photographers/".$row_get_id['cust_handle']; ?>/<? echo $Imagev;?>" target="_blank">View</a>
   <? } ?>
   50 x 725 Pixel Dimensions</td>
 </tr>
 <tr>
  <td><strong>Company Name: </strong></td>
  <td><? if(!$is_enabled){ echo $Company; } else { ?>
   <input type="text" name="Company Name" id="Company_Name" value="<? echo $Company; ?>" />
   <? } ?>
   &nbsp;</td>
 </tr>
 <tr>
  <td><strong>E-mail: </strong></td>
  <td><? if(!$is_enabled){ echo $Email; } else { ?>
   <input type="text" name="Email" id="Email" value="<? echo $Email; ?>" />
   <? } ?>
   &nbsp;</td>
 </tr>
 <tr>
  <td><strong>Phone Number: </strong></td>
  <td><? if(!$is_enabled){ if($Work != "0")echo "(".$W1.") ".$W2."-".$W3; if($Ext != "0") echo " Ext. ".$Ext;} else { ?>
   (
   <input name="W1" type="text" id="W1" size="6" value="<? echo $W1;?>" maxlength="3" onkeyup="set_tel_number('Work_Number','P');" />
   )
   <input name="W2" type="text" id="W2" size="6" value="<? echo $W2;?>" maxlength="3" onkeyup="set_tel_number('Work_Number','P');" />
   -
   <input name="W3" type="text" id="W3" size="8" value="<? echo $W3;?>" maxlength="4" onkeyup="set_tel_number('Work_Number','P');" />
   <input type="hidden" name="Work Number" id="Work_Number" value="<? echo $Work;?>" />
   Ext.
   <input type="text" name="Extension" id="Extension" value="<? echo $Ext; ?>" size="6" maxlength="10" />
   <? } ?>
   &nbsp;</td>
 </tr>
</table>
