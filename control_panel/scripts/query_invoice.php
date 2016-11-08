<?php
include $r_path.'security.php';
if(isset($_POST['Invoices_controller']) && $_POST['Invoices_controller'] == "true"){
	include $r_path.'scripts/del_invoice.php';
}
if(isset($_POST['On_Line_Invoices_controller']) && $_POST['On_Line_Invoices_controller'] == "true"){
	include $r_path.'scripts/del_online_invoice.php';
}
$pathing = implode(",",$pathing);
ob_start();
?>

<div id="Add"><img src="/<?php echo $AevNet_Path; ?>/images/add.jpg" width="72" height="33" alt="add" onclick="document.location.href='<?php echo $_SERVER['PHP_SELF']; ?>?is_info=true&cont=add&info=invoice&<?php echo $_SERVER['QUERY_STRING']; ?>';" /> </div>
<?php
$btn_add = ob_get_contents();
ob_end_clean(); ?>

<div id="Div_Records">
  <?php
	$records = array();
	$headers = array();
	$sortheaders = false;
	$div_data = false;
	$drop_downs = false;
	$headers[0] = "Invoice";
	$headers[1] = "Customer";
	$headers[2] = "Total";
	$headers[3] = "Invoice Date";
	$headers[4] = "Due Date";
	$headers[5] = "&nbsp;";
	
	$query_get_invoice = "SELECT `orders_invoice`.`invoice_id`, `orders_invoice`.`invoice_num_2`, `orders_invoice`.`invoice_rev`, `orders_invoice`.`invoice_total`, `orders_invoice`.`invoice_date`, `orders_invoice`.`invoice_due`, `cust_customers`.`cust_cname`, `cust_customers`.`cust_fname`, `cust_customers`.`cust_lname` 
		FROM `orders_invoice` 
		LEFT OUTER JOIN `cust_customers` 
			ON `cust_customers`.`cust_id` = `orders_invoice`.`cust_id` 
		WHERE `invoice_online` = 'n' 
			AND `invoice_comp` = 'n' 
		ORDER BY `orders_invoice`.`invoice_num_2`";
	$get_invoice = mysql_query($query_get_invoice, $cp_connection) or die(mysql_error());
	$row_get_invoice = mysql_fetch_assoc($get_invoice);
	$totalRows_get_invoice = mysql_num_rows($get_invoice);
	
	do{
		$count = count($records);
		$records[$count][0] = false;
		$records[$count][1] = $row_get_invoice['invoice_id'];
		$records[$count][2] = $row_get_invoice['invoice_num_2'];
		if($row_get_invoice['invoice_rev'] > 1){
			$records[$count][2] .= " Rev. ".$row_get_invoice['invoice_rev'];
		}
		$records[$count][3] = ($row_get_invoice['cust_cname'] == "") ? $row_get_invoice['cust_lname'].", ".$row_get_invoice['cust_fname'] : $row_get_invoice['cust_cname'];
		$records[$count][4] = "$".number_format($row_get_invoice['invoice_total'],2,".",",");
		$records[$count][5] = ($row_get_invoice['invoice_date'] != "0000-00-00 00:00:00") ? format_date($row_get_invoice['invoice_date'], 'Short', '', true, false) : "" ;
		$records[$count][6] = ($row_get_invoice['invoice_due'] != "0000-00-00 00:00:00") ? format_date($row_get_invoice['invoice_due'], 'Short', '', true, false) : "" ;
		$records[$count][7] = "<a href=\"scripts/print_invoice.php?cont=view&id=".$row_get_invoice['invoice_id']."\" target=\"_blank\">Print</a>";
			
	} while ($row_get_invoice = mysql_fetch_assoc($get_invoice)); 
	
	mysql_free_result($get_invoice);
	
	build_record_table('Invoices', 'Internal Invoices', 'Complete Invoice(s)', 'Complete', true, '', '', '', $headers, $sortheaders, $records, $div_data, $drop_downs,$_SERVER['PHP_SELF']."?is_info=true&cont=edit&info=invoice&Path=".$pathing."&Sort=".$Sorting, 'Edit', '', '', 0, 0, 0, '', '100%', '', '', $Rec_Style_1, $Rec_Style_2, $Rec_Style_3, $btn_add, $hdr_img, "Record_Drop_Down");
?>
</div>
<br clear="all" />
<div id="Div_Records">
  <?php
	
	$records = array();
	$headers = array();
	$sortheaders = false;
	$div_data = false;
	$drop_downs = false;
	$headers[0] = "Invoice";
	$headers[1] = "Customer";
	$headers[2] = "Total";
	$headers[3] = "Invoice Date";
	$headers[4] = "Due Date";
	$headers[5] = "&nbsp;";
	
	$query_get_invoice = "SELECT `orders_invoice`.`invoice_enc`, `orders_invoice`.`invoice_id`, `orders_invoice`.`invoice_num`, `orders_invoice`.`invoice_rev`, `orders_invoice`.`invoice_total`, `orders_invoice`.`invoice_date`, `orders_invoice`.`invoice_due`, `cust_customers`.`cust_cname`, `cust_customers`.`cust_fname`, `cust_customers`.`cust_lname` FROM `orders_invoice` LEFT OUTER JOIN `cust_customers` ON `cust_customers`.`cust_id` = `orders_invoice`.`cust_id` WHERE `invoice_online` = 'y' AND `invoice_comp` = 'n' ORDER BY `orders_invoice`.`invoice_num_2`";
	$get_invoice = mysql_query($query_get_invoice, $cp_connection) or die(mysql_error());
	$row_get_invoice = mysql_fetch_assoc($get_invoice);
	$totalRows_get_invoice = mysql_num_rows($get_invoice);
	
	do{
		$count = count($records);
		$records[$count][0] = false;
		$records[$count][1] = $row_get_invoice['invoice_enc'];
		$records[$count][2] = $row_get_invoice['invoice_num'];
		$records[$count][3] = $row_get_invoice['cust_lname'].", ".$row_get_invoice['cust_fname'];
		$records[$count][4] = "$".number_format($row_get_invoice['invoice_total'],2,".",",");
		$records[$count][5] = ($row_get_invoice['invoice_date'] != "0000-00-00 00:00:00") ? format_date($row_get_invoice['invoice_date'], 'Short', '', true, false) : "" ;
			
	} while ($row_get_invoice = mysql_fetch_assoc($get_invoice)); 
	
	mysql_free_result($get_invoice);
	
	build_record_table('On_Line_Invoices', 'On-Line Invoices', 'Complete Invoice(s)', 'Complete', true, '', '', '', $headers, $sortheaders, $records, $div_data, $drop_downs,"/member/invoice.php?aevnet=true", 'View', '', '_blank', 0, 0, 0, '', '100%', '', '', $Rec_Style_1, $Rec_Style_2, $Rec_Style_3, '', $hdr_img, "Record_Drop_Down");
?>
</div>
