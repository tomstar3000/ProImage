<?php if(!isset($r_path)){	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;	$r_path = "";	for($n=0;$n<$count;$n++)$r_path .= "../";}
include $r_path.'security.php';
include $r_path.'scripts/fnct_record_5_table.php';
if(isset($_POST['Controller']) && $_POST['Controller'] == "Delete"){
	include $r_path.'scripts/del_prod_features.php';
}
?>

<div id="Form_Header">
  <div id="Add"><img src="/<?php echo $AevNet_Path; ?>/images/add.jpg" width="72" height="33" alt="add" onclick="javascript:set_form('form_','<?php echo implode(",",$path); ?>','add','<?php echo $sort; ?>','<?php echo $rcrd; ?>');" /></div>
</div>
<div>
  <?php
	$query_get_features = "SELECT `prod_features`.`feat_name`, `prod_link_prod_feat`.* FROM `prod_link_prod_feat` INNER JOIN `prod_features` ON `prod_features`.`feat_id` = `prod_link_prod_feat`.`feat_id` WHERE `prod_id` = '$PId'";
	$get_features = mysql_query($query_get_features, $cp_connection) or die(mysql_error());
	$row_get_features = mysql_fetch_assoc($get_features);
	$totalRows_get_features = mysql_num_rows($get_features);
	
	$records = array();
	$headers = array();
	$sortheaders = false;
	$div_data = false;
	$drop_downs = false;
	$headers[0] = "Feature Name";
	$headers[1] = "Quantity";
	$n = 0;
	$a = 0;
	$parents = array();
	$i_array = array();
	do{
		$count = count($records);
		$records[$count][0][0] = false;
		$records[$count][0][1] = false;
		$records[$count][0][2] = false;
		$records[$count][1] = $row_get_features['link_prod_feat_id'];
		$records[$count][2] = $row_get_features['feat_name'];
		$records[$count][3] = $row_get_features['link_prod_feat_qty'];;
	} while ($row_get_features = mysql_fetch_assoc($get_features)); 
	
	mysql_free_result($get_features);
		
	build_record_5_table('Features','Features',$headers,$sortheaders,$records,$div_data,$drop_downs,array(array('Delete Feature(s)','Delete','Delete',false)),false,'100%','0','0','0',false,false,$Rec_Style_1,$Rec_Style_2,$Rec_Style_3,$Rec_Style_4,$Rec_Style_5);
?>
</div>
