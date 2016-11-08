<?php if(!isset($r_path)){	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;	$r_path = "";	for($n=0;$n<$count;$n++)	$r_path .= "../";}
include $r_path.'security.php';
include $r_path.'scripts/fnct_record_5_table.php';
if(isset($_POST['Controller']) && $_POST['Controller'] == "Delete")	include $r_path.'scripts/del_features.php';
if(count($path)>2){
	$feat_pid = $path[count($path)-1];
	$is_back = "view";
	$is_path = array_slice($path,0,(count($path)-1));
} else {
	$feat_pid = 0;
	$is_back = "query";
	$is_path = "Prod,Feat";
} ?>
<div id="Form_Header">
  <div id="Add"><img src="/<?php echo $AevNet_Path; ?>/images/add.jpg" width="72" height="33" alt="add" onclick="javascript:set_form('form_','<?php echo implode(',',$path); ?>','add','<?php echo $sort; ?>','<?php echo $rcrd; ?>');" /> </div>
  <img src="/<?php echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
  <p>Features List </p>
</div>
<div id="Div_Records">
<?php
$query_get_features = "SELECT * FROM `prod_features` WHERE `feat_part_id` = '$feat_pid'";
$get_features = mysql_query($query_get_features, $cp_connection) or die(mysql_error());
$row_get_features = mysql_fetch_assoc($get_features);
$totalRows_get_features = mysql_num_rows($get_features);

	$records = array();
	$headers = array();
	$sortheaders = array();
	$div_data = false;
	$drop_downs = false;
	$headers[0] = "Feature Name";
	$headers[1] = "Sub-Featires";
	$n = 0;
	do{
		$fid = $row_get_features['feat_id'];
		
		$query_get_record_count = "SELECT `feat_id` FROM `prod_features` WHERE `feat_part_id` = '$fid'";
		$get_record_count = mysql_query($query_get_record_count, $cp_connection) or die(mysql_error());
		$row_get_record_count = mysql_fetch_assoc($get_record_count);
		$totalRows_get_record_count = mysql_num_rows($get_record_count);
		
		$records_count = $totalRows_get_record_count;
		
		mysql_free_result($get_record_count);
		
		$records[$n][0][0] = false;
		$records[$n][0][1] = false;
		$records[$n][0][2] = false;
		$records[$n][1] = $row_get_features['feat_id'];
		$records[$n][2] = $row_get_features['feat_name'];
		$records[$n][3] = $records_count;
		
		$n++;
	} while ($row_get_features = mysql_fetch_assoc($get_features)); 
	
	mysql_free_result($get_features);
	
	build_record_5_table('Features','Features',$headers,$sortheaders,$records,$div_data,$drop_downs,array(array('Delete Feature(s)','Delete','Delete',false)),false,'100%','0','0','0',false,false,$Rec_Style_1,$Rec_Style_2,$Rec_Style_3,$Rec_Style_4,$Rec_Style_5);
if(is_array($rcrd)) $rcrd = implode(",",$rcrd);
?>
</div>