<?php if(!isset($r_path)){	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;	$r_path = "";	for($n=0;$n<$count;$n++)	$r_path .= "../";}
include $r_path.'security.php';
include $r_path.'scripts/fnct_record_5_table.php';
if(isset($_POST['Controller']) && $_POST['Controller'] == "Delete"){
	include $r_path.'scripts/del_prod_images.php';
}
?>

<div id="Form_Header">
  <div id="Add"><img src="/<?php echo $AevNet_Path; ?>/images/add.jpg" width="72" height="33" alt="add" onclick="javascript:set_form('form_','<?php echo implode(",",$path); ?>','add','<?php echo $sort; ?>','<?php echo $rcrd; ?>');" /></div>
</div>
<div>
  <?php
	$query_get_images = "SELECT * FROM `prod_product_images` WHERE  `prod_id` = '$PId'";
	$get_images = mysql_query($query_get_images, $cp_connection) or die(mysql_error());
	$records = array();
	$headers = array();
	$sortheaders = false;
	$div_data = false;
	$drop_downs = false;
	$headers[0] = "Image Name";
	$headers[1] = "&nbsp;";
	while ($row_get_images = mysql_fetch_assoc($get_images)){
		$count = count($records);
		$records[$count][0][0] = false;
		$records[$count][0][1] = false;
		$records[$count][0][2] = false;
		$records[$count][1] = $row_get_images['prod_image_id'];
		$records[$count][2] = $row_get_images['prod_image_name'];
		$records[$count][3] = '<a href="/images/products/'.$row_get_images['prod_image_image'].'" target="_blank">View</a>';
	}
	mysql_free_result($get_images);
	build_record_5_table('Images','Images',$headers,$sortheaders,$records,$div_data,$drop_downs,array(array('Delete Image(s)','Delete','Delete',false)),false,'100%','0','0','0',false,false,$Rec_Style_1,$Rec_Style_2,$Rec_Style_3,$Rec_Style_4,$Rec_Style_5, true, false, false, false);
?>
</div>
