<?php if(!isset($r_path)){	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;	$r_path = "";	for($n=0;$n<$count;$n++)	$r_path .= "../";}
include $r_path.'security.php';
include $r_path.'scripts/fnct_record_5_table.php';
if(isset($_POST['Controller']) && $_POST['Controller'] == "Delete") include $r_path.'scripts/del_specl.php'; ?>
<div id="Form_Header">
  <div id="Add"><img src="/<?php echo $AevNet_Path; ?>/images/add.jpg" width="72" height="33" alt="add" onclick="javascript:set_form('','Prod,Specl','add','<?php echo $sort; ?>','<?php echo $rcrd; ?>');" /> </div>
  <img src="/<?php echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
  <p>Special Delivery List </p>
</div>
<div id="Div_Records">
<?php 
$query_get_special = "SELECT * FROM `prod_special_delivery` ORDER BY `spec_del_name` ASC";
$get_special = mysql_query($query_get_special, $cp_connection) or die(mysql_error());
$row_get_special = mysql_fetch_assoc($get_special);
$totalRows_get_special = mysql_num_rows($get_special);

$records = array();
$headers = array();
$sortheaders = false;
$div_data = false;
$drop_downs = false;
$headers[0] = "Special Delivery Name";
$headers[1] = "Price";
do{		
	$count = count($records);
	$records[$count][0][0] = false;
	$records[$count][0][1] = false;
	$records[$count][0][2] = false;
	$records[$count][1] = $row_get_special['spec_del_id'];
	$records[$count][2] = $row_get_special['spec_del_name'];
	$records[$count][3] = "$".number_format($row_get_special['spec_del_price'],2,".",",");
} while ($row_get_special = mysql_fetch_assoc($get_special)); 

mysql_free_result($get_special);
build_record_5_table('Special_Delivery','Special Delivery',$headers,$sortheaders,$records,$div_data,$drop_downs,array(array('Delete Delivery Option(s)','Delete','Delete',false)),false,'100%','0','0','0',false,false,$Rec_Style_1,$Rec_Style_2,$Rec_Style_3,$Rec_Style_4,$Rec_Style_5);
?>
</div>