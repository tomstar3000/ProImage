<?php include $r_path.'security.php';
$temppathing = $pathing[0].",".$pathing[1];
?>

<div id="Form_Header">
<div id="Add">
  <?php include $r_path.'scripts/save_rept_monthly.php'; ?>
  <img src="/<?php echo $AevNet_Path; ?>/images/back.jpg" width="72" height="33" alt="back" onClick="location.href = '<?php echo $_SERVER['PHP_SELF']."?Path=".$temppathing."&Sort=".$_GET['Sort']; ?>';" /> </div>
<img src="/<?php echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
<p>Monthly Report</p>
<br clear="all" />
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
	
		$inv_id = $row_get_invoice_info['invoice_id'];
		$cust_id = $row_get_invoice_info['cust_id'];
	?>
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
  include $r_path.'scripts/get_month_items.php';
  $is_comp = "";
  do{
  	if($row_get_items['sell_cname'] != $is_comp){
		$is_comp = $row_get_items['sell_cname'];
		echo "<tr>\n<th colspan=\"3\">".$is_comp."</th>\n</tr>\n";
		
	}
	echo "<tr>\n<td align=\"left\"><strong>".$row_get_items['prod_name']." - ";
	echo $row_get_items['prod_number']."</strong></td>\n";
	echo "<td align=\"left\">".$row_get_items['invoice_prod_qty']."</td>\n";
	echo "<td align=\"left\">$".number_format($row_get_items['invoice_prod_price'],2,".",",")."</td>\n</tr>\n";
  } while($row_get_items = mysql_fetch_assoc($get_items));
  mysql_free_result($get_items);
  
  ?>
  </table>
  <br clear="all" />
  <br clear="all" />
  <?php
  }
} while($row_get_invoice_info = mysql_fetch_assoc($get_invoice_info)); ?>
</div>
