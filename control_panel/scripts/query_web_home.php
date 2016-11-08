<? if(!isset($r_path)){	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;	$r_path = "";	for($n=0;$n<$count;$n++)$r_path .= "../";}
include $r_path.'security.php';
require_once $r_path.'scripts/fnct_record_5_table.php';
if(isset($_GET['id'])){	$page_pid = clean_variable($_GET['id'],true);
} else {	$page_pid = 0;}
$pathing = implode(",",$pathing);
ob_start();
?>
<div id="Add"><img src="/<? echo $AevNet_Path; ?>/images/add.jpg" width="72" height="33" alt="add" onclick="document.location.href='<? echo $_SERVER['PHP_SELF']; ?>?is_info=true&cont=add&info=products&<? echo $_SERVER['QUERY_STRING']; ?>';" />
</div>
<?
$btn_add = ob_get_contents();
ob_end_clean(); ?>
<div id="Div_Records">
<?	
	$records = array();
	$headers = array();
	$sortheaders = false;
	$div_data = false;
	$drop_downs = false;
	$headers[0] = "Product Name";
	$headers[1] = "Number";
	$headers[2] = "Avail.";
	$headers[3] = "Price";
	$headers[4] = "Qty.";
	$sortheaders[0] = $Sort;
	$sortheaders[1] = $Order;

	$query_get_products = "SELECT `prod_products`.*, `prod_availability`.`avail_name` FROM `prod_products` LEFT OUTER JOIN `prod_availability` ON `prod_availability`.`availability_id` = `prod_products`.`availability_id`".$add_where.$Sort_val;
	$get_products = mysql_query($query_get_products, $cp_connection) or die(mysql_error());
	$row_get_products = mysql_fetch_assoc($get_products);
	$totalRows_get_products = mysql_num_rows($get_products);
	
	$n = 0;
	do{
		$records[$n][0][0] = false;
		$records[$n][0][1] = false;
		$records[$n][0][2] = false;
		$records[$n][1] = $row_get_products['prod_id'];
		$records[$n][2] = $row_get_products['prod_name'];
		$records[$n][3] = $row_get_products['prod_number'];
		$records[$n][4] = $row_get_products['avail_name'];
		$records[$n][5] = $price;
		$records[$n][6] = $row_get_products['prod_qty'];
		
		$n++;
	} while ($row_get_products = mysql_fetch_assoc($get_products)); 
	
	mysql_free_result($get_products);
	
	build_record_5_table('Products', 'Products',$headers,$sortheaders,$records,$div_data,$drop_downs,array(array('Products', 'Products', 'Delete Product(s)', 'Delete',false)),false,'100%','0','0','0',false,false,$Rec_Style_1,$Rec_Style_2,$Rec_Style_3,$Rec_Style_4,$Rec_Style_5);
?>
</div>
