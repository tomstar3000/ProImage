<?php include $r_path.'security.php';
$temppathing = $pathing[0].",".$pathing[1];
?>

<div id="Form_Header">
  <div id="Add">
    <?php include $r_path.'scripts/save_rept_high.php'; ?>
    <img src="/<?php echo $AevNet_Path; ?>/images/back.jpg" width="72" height="33" alt="back" onClick="location.href = '<?php echo $_SERVER['PHP_SELF']."?Path=".$temppathing."&Sort=".$_GET['Sort']; ?>';" /> </div>
  <img src="/<?php echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
  <p>Hybrid Report</p>
  <br clear="all" />
</div>
<div id="Info">
  <?php 
$is_name = "";
do{
	$name = $row_get_invoice_info['sell_cname'];
	if($name != $id_name){
		$id_name = $name;
		echo "<h1>".$name."\n";
		print("<hr size=\"1\"></h1>");
	
		$inv_id = $row_get_invoice_info['invoice_id'];		
		$man_id = $row_get_invoice_info['sell_id'];
	?>
  <table border="0" cellpadding="0" cellspacing="0" width="95%" align="center">
    <?php 
	  include $r_path.'scripts/get_high_cust.php';
	  do{
		echo "<tr>\n<td align=\"left\"><strong>Ship To:<br />".$row_get_customers['cust_ship_lname'].", ".$row_get_customers['cust_ship_fname'];
		echo ($row_get_customers['cust_ship_cname'] == "") ? "</strong><br />\n" : " - ".$row_get_customers['cust_ship_cname']."</strong><br />\n";
		echo $row_get_customers['cust_ship_add'];
		echo ($row_get_customers['cust_ship_suite_apt'] == "") ? "<br />\n" : " ".$row_get_customers['cust_ship_suite_apt']."<br />\n";
		echo ($row_get_customers['cust_ship_add_2'] == "") ? "" : $row_get_customers['cust_ship_add_2']."<br />\n";
		echo $row_get_customers['cust_ship_city'].", ".$row_get_customers['cust_ship_state'].". ".$row_get_customers['cust_ship_zip']."\n";
		print("</td>\n<td>\n");
		echo $row_get_customers['ship_comp_name']."<br />\n";
		echo $row_get_customers['ship_speed_name']."\n";
		print("</td></tr>\n");
		
		$ship_id = $row_get_customers['cust_ship_id'];
		$speed_id = $row_get_customers['ship_speed_id'];
		
		print("<tr>\n<td colspan=\"2\"><hr size=\"1\" /></td>\n</tr>\n");
		print("<tr>\n<td align=\"left\" width=\"80%\">Product Name</td>\n<td align=\"left\" width=\"20%\">Quantity</td>\n</tr>\n");
		include $r_path.'scripts/get_high_items.php';
		do{
			$item_id = $row_get_items['invoice_prod_id'];
			include $r_path.'scripts/get_inv_items_special.php';
			echo "<tr>\n<td align=\"left\"><strong>".$row_get_items['prod_name']." - ".$row_get_items['prod_number'];
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
			echo "<td align=\"left\">".$row_get_items['invoice_prod_qty']."</td>\n</tr>\n";
		} while($row_get_items = mysql_fetch_assoc($get_items));
		echo "<tr>\n<td colspan=\"2\">&nbsp;</td>\n</tr>\n";
	  } while($row_get_customers = mysql_fetch_assoc($get_customers));
	  mysql_free_result($get_customers);
  ?>
  </table>
  <br clear="all" />
  <br clear="all" />
  <?php
  }
} while($row_get_invoice_info = mysql_fetch_assoc($get_invoice_info)); ?>
</div>
