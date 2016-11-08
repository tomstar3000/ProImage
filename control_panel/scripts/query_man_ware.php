<?php if(!isset($r_path)){	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;	$r_path = "";	for($n=0;$n<$count;$n++)$r_path .= "../";}
include $r_path.'security.php';
include $r_path.'scripts/fnct_record_5_table.php';
if(isset($_POST['Controller']) && $_POST['Controller'] == "Delete") include $r_path.'scripts/del_man_ware.php';?>

<div id="Form_Header">
  <div id="Add"><img src="/<?php echo $AevNet_Path; ?>/images/add.jpg" width="72" height="33" alt="add" onclick="javascript:set_form('form_','<?php echo implode(",",$path); ?>','add','<?php echo $sort; ?>','<?php echo $rcrd; ?>');" /></div>
</div>
<div id="Div_Records">
  <?php
	$query_get_whare = "SELECT * FROM `sellers_wharehouse` WHERE `sell_id` = '$man_id'";
	$get_whare = mysql_query($query_get_whare, $cp_connection) or die(mysql_error());
	$row_get_whare = mysql_fetch_assoc($get_whare);
	$totalRows_get_whare = mysql_num_rows($get_whare);

	$records = array();
	$headers = array();
	$sortheaders = false;
	$div_data = array();
	$drop_downs = false;
	$headers[0] = "Address";
	
	do{
		$count = count($records);
		$records[$count][0][0] = false;
		$records[$count][0][1] = false;
		$records[$count][0][2] = false;
		$records[$count][1] = $row_get_whare['sell_whare_id'];
		$records[$count][2] = $row_get_whare['sell_whare_add'];
		$temp_div = "";
		if($row_get_whare['sell_whare_suite'] != ""){
			$temp_div .= " Suite/Apt. ".$row_get_whare['sell_whare_suite']."<br />";
		}
		if($row_get_whare['sell_whare_add2'] != ""){
			$temp_div .= $row_get_whare['sell_whare_add2']."<br />";
		}
		if($row_get_whare['sell_whare_city'] != ""){
			$temp_div .= $row_get_whare['sell_whare_city'].", ".$row_get_whare['sell_whare_state'].". ".$row_get_whare['sell_whare_zip']."<br />";
		}
		if($row_get_whare['sell_whare_country'] != ""){
			$temp_div .= $row_get_whare['sell_whare_country'];
		}
		$div_data[$count] = $temp_div;
	} while ($row_get_whare = mysql_fetch_assoc($get_whare));
	
	mysql_free_result($get_whare);

	build_record_5_table('Warehouse','Warehouse',$headers,$sortheaders,$records,$div_data,$drop_downs,array(array('Delete Warehouse','Delete','Delete',false)),false,'100%','0','0','0',false,false,$Rec_Style_1,$Rec_Style_2,$Rec_Style_3,$Rec_Style_4,$Rec_Style_5);
?>
</div>
