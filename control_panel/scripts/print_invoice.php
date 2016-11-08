<?php require_once('../../Connections/cp_connection.php'); ?>
<?php
$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
$r_path = "";
for($n=0;$n<$count;$n++){
	$r_path .= "../";
}
mysql_select_db($database_cp_connection, $cp_connection);
define ("<?php echo $AevNet_Path; ?> CONTROL PANEL", true);
include $r_path.'scripts/save_invoice.php';
include $r_path.'config.php';
include $r_path.'security.php';
 
$total = 0;
if(count($Proj_id_items)){
	$s_array = $Proj_id_items;
	sort($s_array);
	$s_id = array_shift($s_array)-1;
	unset($s_array);
} else {
	$s_id = 0;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo $AevNet_Path; ?>: Control Panel</title>
<link href="../stylesheet/<?php echo $AevNet_Path; ?>.css" rel="stylesheet" type="text/css" />
</head>
<body>
<?php echo $inv_header; ?>
<div id="inv_Items">
  <table border="0" cellpadding="5" cellspacing="0" width="100%">
    <?php
  	foreach($Used_items as $k => $v){ 
		$n++;
		//mysql_select_db($database_cp_connection, $cp_connection);
		// Speed
		$query_get_proj_info = "SELECT SUM(`proj_timesheet`.`time_amt` ) AS `timesheet` FROM `proj_projects` LEFT OUTER JOIN `proj_timesheet` ON `proj_timesheet`.`proj_id` = `proj_projects`.`proj_id` LEFT OUTER JOIN `orders_invoice_proj` ON `orders_invoice_proj`.`proj_id` = `proj_projects`.`proj_id`  WHERE `proj_projects`.`proj_id` = '$v' GROUP BY `proj_timesheet`.`proj_id` ORDER BY `proj_projects`.`proj_name`";
		$get_proj_info = mysql_query($query_get_proj_info, $cp_connection) or die(mysql_error());
		$row_get_proj_info = mysql_fetch_assoc($get_proj_info);
		$totalRows_get_proj_info = mysql_num_rows($get_proj_info);
		
		//mysql_select_db($database_cp_connection, $cp_connection);
		$query_get_proj_assts = "SELECT * FROM `proj_assets` WHERE `proj_id` = '$v' ORDER BY `proj_asset_name`";
		$get_proj_assts = mysql_query($query_get_proj_assts, $cp_connection) or die(mysql_error());
		$row_get_proj_assts = mysql_fetch_assoc($get_proj_assts);
		$totalRows_get_proj_assts = mysql_num_rows($get_proj_assts);
		
		//mysql_select_db($database_cp_connection, $cp_connection);
		$query_get_proj_supp = "SELECT * FROM `proj_supplies` WHERE `proj_id` = '$v' ORDER BY `proj_supp_name`";
		$get_proj_supp = mysql_query($query_get_proj_supp, $cp_connection) or die(mysql_error());
		$row_get_proj_supp = mysql_fetch_assoc($get_proj_supp);
		$totalRows_get_proj_supp = mysql_num_rows($get_proj_supp);
		
		$ProjIsInv = $Desc_items[$v]["IsInv"];
		$ProjName = $Desc_items[$v]["Name"];
		$ProjPrice = $Desc_items[$v]["Amount"];
		$ProjDesc = $Desc_items[$v]["Desc"];
		$ProjHours = $Desc_items[$v]["Hours"];
		$ProjTime = $row_get_proj_info['timesheet'];
		
		mysql_free_result($get_proj_info);
  ?>
    <tr>
      <?php $cellcount = 0; ?>
      <td width="66%" class="Border_<?php if($ProjDesc == ""){ echo "1"; } else { echo "3"; } ?>"><?php echo $ProjName.$ProjNum; ?></td>
      <td width="16%" class="Border_<?php if($ProjDesc == ""){ echo "1"; } else { echo "3"; } ?>"><?php
		if($totalRows_get_proj_times != 0){
			do{
				echo $row_get_proj_times['time_hours']." hrs @ $".$row_get_proj_times['time_bill']."/hr<br />";
			} while($row_get_proj_times = mysql_fetch_assoc($get_proj_times));
		} else {
			echo $ProjHours;
		}
	?></td>
      <td width="16%" align="right"<?php if($ProjDesc == ""){ echo " class=\"Border_2\""; } ?>>$ <?php echo number_format($ProjPrice,2,".",","); ?>
        <?php $total += $ProjPrice; ?></td>
    </tr>
    <?php if($ProjDesc != ""){ ?>
    <tr>
      <?php $cellcount++; 
		$tempcount = substr_count($ProjDesc, '<br');
		$cellcount += $tempcount;
		$tempcount = substr_count($ProjDesc, '<p');
		$cellcount += $tempcount*2;
	  ?>
      <td class="Border_1"><blockquote><?php echo $ProjDesc; ?></blockquote></td>
      <td class="Border_1">&nbsp;</td>
      <td class="Border_2">&nbsp;</td>
    </tr>
    <?php  }
 	 if($totalRows_get_proj_assts != 0){ ?>
    <tr>
      <?php $cellcount++; ?>
      <td colspan="3" class="Border_2">Assets</td>
    </tr>
    <?php do{
  $n++; ?>
    <tr>
      <?php $cellcount++; ?>
      <td class="Border_1"><blockquote>
          <p><?php echo $row_get_proj_assts['proj_asset_name']; ?></p>
        </blockquote></td>
      <td align="right" class="Border_1">&nbsp;</td>
      <td align="right" class="Border_2"><?php
	$price = number_format($row_get_proj_assts['proj_asset_price'],2,".",",");
	if($row_get_proj_assts['proj_asset_remove'] == "y") {
		echo "- $(".$price.")";
		$total -= $price;
	} else {
		echo "$".$price;
		$total += $price;
	}?>
        <input type="hidden" name="Project_Price_<?php echo $n; ?>2" id="Project_Price_<?php echo $n; ?>2" value="<?php echo $price; ?>" /></td>
    </tr>
    <?php } while($row_get_proj_assts = mysql_fetch_assoc($get_proj_assts));  
  }
  if($totalRows_get_proj_supp != 0){ ?>
    <tr>
      <?php $cellcount++; ?>
      <td colspan="3">Supplies</td>
    </tr>
    <?php do{
  $n++; ?>
    <tr>
      <?php $cellcount++; ?>
      <td class="Border_1"><blockquote>
          <p><?php echo $row_get_proj_supp['proj_supp_name']; ?></p>
        </blockquote></td>
      <td align="right" class="Border_1">&nbsp;</td>
      <td align="right" class="Border_2"><?php
	$price = number_format($row_get_proj_assts['proj_supp_price'],2,".",",");
	if($row_get_proj_assts['proj_supp_remove'] != "y") {
		echo "- $(".$price.")";
		$total -= $price;
	} else {
		echo "$".$price;
		$total += $price;
	} ?>
        <input type="hidden" name="Project_Price_<?php echo $n; ?>" id="Project_Price_<?php echo $n; ?>" value="<?php echo $price; ?>" /></td>
    </tr>
    <?php } while($row_get_proj_supp = mysql_fetch_assoc($get_proj_supp));
 		 }
			mysql_free_result($get_proj_assts);
			mysql_free_result($get_proj_supp);
  		}
	
	if($IId != ""){
		//mysql_select_db($database_cp_connection, $cp_connection);
		$query_get_payment = "SELECT * FROM `orders_invoice_paid` WHERE `invoice_id` = '$IId' ORDER BY `invoice_paid_date`";
		$get_payment = mysql_query($query_get_payment, $cp_connection) or die(mysql_error());
		$row_get_payment = mysql_fetch_assoc($get_payment);
		$totalRows_get_payment = mysql_num_rows($get_payment);
		
		if($totalRows_get_payment != 0){
			$cellcount += 2;
		}
		for($n=0;$n<(25-$cellcount);$n++){
			print("<tr>\n<td class=\"Border_1\">&nbsp;</td>\n<td class=\"Border_1\">&nbsp;</td>\n<td class=\"Border_2\">&nbsp;</td>\n</tr>");
		}
		if($totalRows_get_payment != 0){?>
    <tr>
      <td colspan="3" class="Border_2">Payments Recieved</td>
    </tr>
    <?php 
	  $n = 0;
	  do{
	  $n++; ?>
    <tr>
      <td class="Border_1"><?php echo format_date($row_get_payment['invoice_paid_date'],"Long","Standard",true,false); ?></td>
      <td align="right" class="Border_1">&nbsp;</td>
      <td align="right" class="Border_2"><?php
		  $price = $row_get_payment['invoice_paid_amt']; 
		  echo "- $".number_format($price,2,".",","); 
		  $total -= $price;
	  ?>
        <input type="hidden" name="Recieved_<?php echo $n; ?>" id="Recieved_<?php echo $n; ?>" value="<?php echo $price; ?>" /></td>
    </tr>
    <?php } while ($row_get_payment = mysql_fetch_assoc($get_payment)); 
  	}
	mysql_free_result($get_payment);
}
?>
    <tr>
      <?php $cellcount++; ?>
      <td colspan="3" align="right"><input type="hidden" name="Total" id="Total" value="<?php echo $total; ?>" />
        <strong>Total: $<span id="html_Total"><?php echo number_format($total,2,".",","); ?></span></strong></td>
    </tr>
  </table>
</div>
<?php echo $inv_footer; ?><br style="page-break-before: always;" />

<div id="inv_Contract">
  <?php
//mysql_select_db($database_cp_connection, $cp_connection);
$query_get_contract = "SELECT * FROM `order_contracts` WHERE `contract_type` = 'Invoice'";
$get_contract = mysql_query($query_get_contract, $cp_connection) or die(mysql_error());
$row_get_contract = mysql_fetch_assoc($get_contract);
$totalRows_get_contract = mysql_num_rows($get_contract);

echo $row_get_contract['contract_text'];

mysql_free_result($get_contract);
?>
</div>
</body>
</html>
