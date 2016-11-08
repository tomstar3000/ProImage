<?php if(!isset($r_path)){	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;	$r_path = "";	for($n=0;$n<$count;$n++)	$r_path .= "../";}
include $r_path.'security.php';
include $r_path.'scripts/fnct_record_5_table.php';
if(isset($_POST['Controller']) && $_POST['Controller'] == "Delete")	include $r_path.'scripts/del_selections.php'; ?>

<div id="Form_Header">
 <div id="Add"><img src="/<?php echo $AevNet_Path; ?>/images/add.jpg" width="72" height="33" alt="add" onclick="javascript:set_form('','Prod,Sel','add','<?php echo $sort; ?>','<?php echo $rcrd; ?>');" /> </div>
 <img src="/<?php echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
 <p>Selection List </p>
</div>
<div id="Div_Records">
 <?php 
$query_get_selections = "SELECT * FROM `prod_selections` ORDER BY `sel_name` ASC ";
$get_selections = mysql_query($query_get_selections, $cp_connection) or die(mysql_error());
$row_get_selections = mysql_fetch_assoc($get_selections);
$totalRows_get_selections = mysql_num_rows($get_selections);

	$records = array();
	$headers = array();
	$sortheaders = false;
	$div_data = false;
	$drop_downs = false;
	$headers[0] = "Selection Group Name";
	$n = 0;
	do{
		$records[$n][0][0] = false;
		$records[$n][0][1] = false;
		$records[$n][0][2] = false;
		$records[$n][1] = $row_get_selections['sel_id'];
		$records[$n][2] = $row_get_selections['sel_name'];
				
		$n++;
	} while ($row_get_selections = mysql_fetch_assoc($get_selections)); 
	
	mysql_free_result($get_selections);
	
	build_record_5_table('Selection_Groups','Selection Groups',$headers,$sortheaders,$records,$div_data,$drop_downs,array(array('Delete Selection Group(s)','Delete','Delete',false)),false,'100%','0','0','0',false,false,$Rec_Style_1,$Rec_Style_2,$Rec_Style_3,$Rec_Style_4,$Rec_Style_5);
?>
</div>
