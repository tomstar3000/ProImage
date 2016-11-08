<?
if(isset($r_path)===false){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++)$r_path .= "../";
}
include $r_path.'security.php'; ?>

<div id="Sub_Header"> <img src="/control_panel/images/hdr_form_left.jpg" width="17" height="33" border="0" align="left" />
  <h2>Renewal Information</h2>
</div>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
  <? if(isset($message)) echo '<tr><td colspan="2" style="background-color:#FFFFFF; color:#CC0000;">'.$message.'</td></tr>'; ?>
  <tr>
    <td>Our Records show that you renewal is due on: <? echo format_date($DueDate,'Short',false,true,false); ?>, if you would
      like to renew make sure that the information below is correct and click the &quot;Renew Button&quot;. You can also use this
      opportunity to change your service plan. </td>
  </tr>
</table>
<br clear="all" />
<? 
$query_get_service = "SELECT `prod_id`, `prod_name`, `prod_price` FROM `prod_products` WHERE `prod_service` = 'y' AND `prod_recur` = 'y' ORDER BY `prod_name` ASC";
	$get_service = mysql_query($query_get_service, $cp_connection) or die(mysql_error());
	$row_get_service = mysql_fetch_assoc($get_service);
	$totalRows_get_service = mysql_num_rows($get_service);
	  
	  $SelVal = $SvLvl; ?>
<div>
  <p>Service Level:
    <select name="Service Level" id="Service_Level" tabindex="<? echo $tab++; ?>">
      <? do{ ?>
      <option value="<? echo $row_get_service['prod_id']; ?>"<? if($SelVal == $row_get_service['prod_id']){print(' selected="selected"');}?>><? echo $row_get_service['prod_name'];?></option>
      <? } while($row_get_service = mysql_fetch_assoc($get_service)); ?>
    </select>
    &nbsp;</p>
</div>
<div style="width:200px; float:left;">
  <p><strong>Portrait Plan:</strong></p>
  <p><strong>Storage:</strong> 10 Gb<br />
    <strong>Monthly Fee:</strong> $0.00<br />
  <strong>Set Up Fee:</strong> $50.00<br />
  <strong>Revenue Sharing:</strong> 15%<br />
  <strong>Credit Card Processing Fee:</strong> 3%</p>
</div>
<div style="width:200px; float:left;">
  <p><strong>Wedding Plan:</strong></p>
  <p><strong>Storage:</strong> 50 Gb<br />
    <strong>Monthly Fee:</strong> $35.00<br />
  <strong>Set Up Fee:</strong> $35.00<br />
  <strong>Revenue Sharing:</strong> 10%<br />
  <strong>Credit Card Processing Fee:</strong> 3%</p>
</div>
<div style="width:200px; float:left;">
  <p><strong>Wedding Plus  Plan:</strong></p>
  <p><strong>Storage:</strong> 100 Gb<br />
    <strong>Monthly Fee:</strong> $50.00<br />
  <strong>Set Up Fee:</strong> $0.00<br />
  <strong>Revenue Sharing:</strong> 7%<br />
  <strong>Credit Card Processing Fee:</strong> 3%</p>
</div>
<br clear="all" />
<br clear="all" />
