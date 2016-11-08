<?php include $r_path.'security.php';
$temppathing = $pathing[0].",".$pathing[1];
?>

<div id="Form_Header">
  <div id="Add">
    <?php include $r_path.'scripts/save_rept_daily.php'; ?>
    <img src="/<?php echo $AevNet_Path; ?>/images/back.jpg" width="72" height="33" alt="back" onClick="location.href = '<?php echo $_SERVER['PHP_SELF']."?Path=".$temppathing."&Sort=".$_GET['Sort']; ?>';" /> </div>
  <img src="/<?php echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
  <p>Daily Report</p>
  <br clear="all" />
</div>
<div id="Info">
  <?php 
$is_name = "";
do{
	$name = $row_get_invoice_info['cust_lname'].", ".$row_get_invoice_info['cust_fname'];
	$name .= ($row_get_invoice_info['cust_cname'] == "") ? "" : " - ".$row_get_invoice_info['cust_cname'];
	if($name != $id_name){
		$id_name = $name;
		echo "<h1>".$name."\n";
		print("<hr size=\"1\"></h1>");
	}
	// YYYY-MM-DD HH:MM:SS
	// 0123456789012345678
	// 1234567890123456789
	echo "<h1 align=\"left\">".$row_get_invoice_info['invoice_num']." - ".date("M j, Y g:i a", mktime(substr($row_get_invoice_info['invoice_date'],11,2),substr($row_get_invoice_info['invoice_date'],14,2),substr($row_get_invoice_info['invoice_date'],17,2),substr($row_get_invoice_info['invoice_date'],5,2),substr($row_get_invoice_info['invoice_date'],8,2),substr($row_get_invoice_info['invoice_date'],0,4)))."</h1>"; 
	$ship_id = $row_get_invoice_info['cust_ship_id'];
	$inv_id = $row_get_invoice_info['invoice_id'];
	?>
  <div style="float:left; width:40%; margin-left:10px;">
    <p align="left">
      <?php
		include $r_path.'scripts/get_cust_ship.php';
		
		echo "Ship To: ".$row_get_ship_info['cust_ship_fname']." ".$row_get_ship_info['cust_ship_lname']."<br />\n";
		echo ($row_get_ship_info['cust_ship_cname'] == "") ? "" : $row_get_ship_info['cust_ship_cname']."<br />\n";
		echo $row_get_ship_info['cust_ship_add'];
		echo ($row_get_ship_info['cust_ship_suite_apt'] == "") ? "<br />\n" : $row_get_ship_info['cust_ship_suite_apt']."<br />\n";
		echo ($row_get_ship_info['cust_ship_add_2'] == "") ? "" : $row_get_ship_info['cust_ship_add_2']."<br />\n";
		echo $row_get_ship_info['cust_ship_city'].", ".$row_get_ship_info['cust_ship_state'].". ".$row_get_ship_info['cust_ship_zip']."<br />\n";
		
	?>
    </p>
  </div>
  <?php mysql_free_result($get_ship_info); ?>
  <div style="float:right; width:40%; margin-right:10px;">
    <p align="left"><strong>Total:</strong> $<?php echo number_format($row_get_invoice_info['invoice_total'],2,".",","); ?><br />
      <strong>Tax:</strong> $<?php echo number_format($row_get_invoice_info['invoice_tax'],2,".",","); ?><br />
      <strong>Shipping:</strong> $<?php echo number_format($row_get_invoice_info['invoice_ship'],2,".",","); ?><br />
      <strong>Extra:</strong> $<?php echo number_format($row_get_invoice_info['invoice_extra'],2,".",","); ?><br />
      <strong>Grand Total:</strong> $<?php echo number_format($row_get_invoice_info['invoice_grand'],2,".",","); ?> </p>
  </div>
  <br clear="all" />
  <br clear="all" />
  <table border="0" cellpadding="0" cellspacing="0" width="95%" align="center">
    <tr>
      <td width="80%" align="left">Product Name</td>
      <td width="10%" align="left">Quantity</td>
      <td width="10%" align="left">Price</td>
    </tr>
    <tr>
      <td colspan="3"><hr size="1" /></td>
    </tr>
    <?php 
  include $r_path.'scripts/get_inv_items.php';
  $is_comp = "";
  do{
  	if($row_get_items['sell_cname'] != $is_comp){
		$is_comp = $row_get_items['sell_cname'];
		echo "<tr>\n<th colspan=\"3\">".$is_comp."</th>\n</tr>\n";
		
	}
	$item_id = $row_get_items['invoice_prod_id'];
	$need_spcl = true;
	include $r_path.'scripts/get_inv_items_special.php';
	echo "<tr>\n<td align=\"left\"><strong>".$row_get_items['prod_name']." - ";
	echo $row_get_items['prod_number'];
	if($totalRows_get_attrib > 0){
		echo "; ";
		$i = 0;
		do{
			$i++;
			echo $row_get_attrib['att_name'];
			if($i<$totalRows_get_attrib){
				echo " - ";
			}
		} while($row_get_attrib = mysql_fetch_assoc($get_attrib));
	}
	mysql_free_result($get_attrib);
	echo "</strong></td>\n";
	echo "<td align=\"left\">".$row_get_items['invoice_prod_qty']."</td>\n";
	echo "<td align=\"left\">$".number_format($row_get_items['invoice_prod_price'],2,".",",")."</td>\n</tr>\n";
	if($totalRows_get_special>0){
		echo "<tr>\n<td colspan=\"3\"><blockquote>";
		do{
			echo $row_get_special['spec_del_name']."</br>";
		} while($row_get_special = mysql_fetch_assoc($get_special));
		echo "</blockquote></td>\n</tr>\n";
	}
	
 	mysql_free_result($get_special);
  } while($row_get_items = mysql_fetch_assoc($get_items));
  mysql_free_result($get_items);
  ?>
  </table>
  <br clear="all" />
  <br clear="all" />
  <?php
} while($row_get_invoice_info = mysql_fetch_assoc($get_invoice_info)); ?>
</div>
