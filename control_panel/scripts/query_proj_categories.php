<?php if(!isset($r_path)){	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;	$r_path = "";	for($n=0;$n<$count;$n++)		$r_path .= "../";}
include $r_path.'security.php';
include $r_path.'scripts/fnct_record_5_table.php';
if(isset($_POST['Controller']) && $_POST['Controller'] == "Delete")include $r_path.'scripts/del_proj_categories.php';
if(count($path)>2){
	$cat_pid = $path[count($path)-1];
	$is_back = "view";
	$is_path = array_slice($path,0,(count($path)-1));
} else {
	$cat_pid = 0;
	$is_back = "query";
	$is_path = "Prod,Attrib";
} ?>

<div id="Form_Header">
  <div id="Add"><img src="/<?php echo $AevNet_Path; ?>/images/add.jpg" width="72" height="33" alt="add" onclick="javascript:set_form('form_','<?php echo implode(',',$path); ?>','add','<?php echo $sort; ?>','<?php echo $rcrd; ?>');" /> </div>
  <img src="/<?php echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
  <p>Category List </p>
</div>
<div id="Div_Records">
  <?php
$query_get_categories = "SELECT * FROM `proj_categories` WHERE `cat_parent_id` = '$cat_pid'";
$get_categories = mysql_query($query_get_categories, $cp_connection) or die(mysql_error());
$row_get_categories = mysql_fetch_assoc($get_categories);

	$records = array();
	$headers = array();
	$sortheaders = false;
	$div_data = false;
	$drop_downs = false;
	$headers[0] = "Category Name";
	$headers[1] = "Sub-Categories";
	do{
		$cid = $row_get_categories['cat_id'];
		
		$query_get_record_count = "SELECT `cat_id` FROM `proj_categories` WHERE `cat_parent_id` = '$cid'";
		$get_record_count = mysql_query($query_get_record_count, $cp_connection) or die(mysql_error());
		$row_get_record_count = mysql_fetch_assoc($get_record_count);
		
		$records_count = $totalRows_get_record_count;
		
		mysql_free_result($get_record_count);
		$count = count($records);
		$records[$count][0][0] = false;
		$records[$count][0][1] = false;
		$records[$count][0][2] = false;
		$records[$count][1] = $row_get_categories['cat_id'];
		$records[$count][2] = $row_get_categories['cat_name'];
		$records[$count][3] = $records_count;
	} while ($row_get_categories = mysql_fetch_assoc($get_categories)); 
	
	mysql_free_result($get_categories);
	
	build_record_5_table('Categories','Categories',$headers,$sortheaders,$records,$div_data,$drop_downs,array(array('Delete Categorie(s)','Delete','Delete',false)),false,'100%','0','0','0',false,false,$Rec_Style_1,$Rec_Style_2,$Rec_Style_3,$Rec_Style_4,$Rec_Style_5);
if(is_array($rcrd)) $rcrd = implode(",",$rcrd); ?>
</div>
