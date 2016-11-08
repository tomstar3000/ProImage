<?php if(!isset($r_path)){$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;	$r_path = "";	for($n=0;$n<$count;$n++)$r_path .= "../";}
include $r_path.'security.php';
include $r_path.'scripts/fnct_record_5_table.php';
if(isset($_POST['Controller']) && $_POST['Controller'] == "Remove/Un-Remove")include $r_path.'scripts/del_proj_asset.php'; ?>

<div id="Form_Header">
  <div id="Add"><img src="/<?php echo $AevNet_Path; ?>/images/add.jpg" width="72" height="33" alt="add" onclick="javascript:set_form('form_','<?php echo implode(",",$path); ?>','add','<?php echo $sort; ?>','<?php echo $rcrd; ?>');" /></div>
</div>
<div id="Div_Records">
  <?php 
	$query_get_assets = "SELECT * FROM `proj_assets` WHERE `proj_id` = '$proj_id' ORDER BY `proj_asset_name` ASC";
	$get_assets = mysql_query($query_get_assets, $cp_connection) or die(mysql_error());
	$row_get_assets = mysql_fetch_assoc($get_assets);
	$records = array();
	$headers = array();
	$sortheaders = false;
	$div_data = false;
	$drop_downs = false;
	$headers[0] = "Name";
	$headers[1] = "";
	$headers[2] = "Date";
	$headers[3] = "Price";
	$n = 0;
	do{
		$records[$n][0][0] = false;
		$records[$n][0][1] = false;
		$records[$n][0][2] = false;
		$records[$n][1] = $row_get_assets['proj_asset_id'];
		$records[$n][2] = $row_get_assets['proj_asset_name'];
		$records[$n][3] = ($row_get_assets['proj_asset_remove']=="y") ? "Removed" : "";
		$records[$n][4] = format_date($row_get_assets['proj_asset_date'], 'Short', '', true, false);
		$records[$n][5] = "$".number_format($row_get_assets['proj_asset_price'],2,".",",");
		$n++;
	} while ($row_get_assets = mysql_fetch_assoc($get_assets)); 
	
	build_record_5_table('Assets','Assets',$headers,$sortheaders,$records,$div_data,$drop_downs,array(array('Remove/Un-Remove Asset(s)','Remove/Un-Remove','Remove/Un-Remove',false)),false,'100%','0','0','0',false,false,$Rec_Style_1,$Rec_Style_2,$Rec_Style_3,$Rec_Style_4,$Rec_Style_5);
mysql_free_result($get_assets);
?>
</div>
