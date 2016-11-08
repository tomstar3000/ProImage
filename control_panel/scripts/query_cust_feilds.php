<? if(!isset($r_path)){	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;	$r_path = "";	for($n=0;$n<$count;$n++) $r_path .= "../";}
include $r_path.'security.php';
include $r_path.'scripts/fnct_record_5_table.php';
if(isset($_POST['Controller']) && $_POST['Controller'] == "Delete")include $r_path.'scripts/del_cust_feilds.php';?>

<div id="Form_Header">
  <div id="Add"><img src="/<? echo $AevNet_Path; ?>/images/add.jpg" width="72" height="33" alt="add" onclick="javascript:set_form('form_','<? echo implode(",",$path); ?>,Feild','add','<? echo $sort; ?>','<? echo $rcrd; ?>');" /> </div>
  <img src="/<? echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
  <p>Custom Field List </p>
</div>
<div id="Div_Records">
  <?	
	$records = array();
	$headers = array();
	$sortheaders = false;
	$div_data = false;
	$drop_downs = false;
	$headers[0] = "Form Name";
	
	$query_get_fields = "SELECT * FROM `form_fields` WHERE `form_id` = '$FId' ORDER BY `feild_id` ASC";
	$get_fields = mysql_query($query_get_fields, $cp_connection) or die(mysql_error());
	$row_get_fields = mysql_fetch_assoc($get_fields);
	$totalRows_get_fields = mysql_num_rows($get_fields);
	do{
		$count = count($records);
		$records[$count][0][0] = false;
		$records[$count][0][1] = false;
		$records[$count][0][2] = false;
		$records[$count][1] = $row_get_fields['feild_id'];
		$records[$count][2] = $row_get_fields['feild_name'];
	} while ($row_get_fields = mysql_fetch_assoc($get_fields)); 
	array_push($path,"Feild");
	mysql_free_result($get_fields);
	build_record_5_table('Custom_Fields','Custom Fields',$headers,$sortheaders,$records,$div_data,$drop_downs,array(array('Delete Field(s)','Delete','Delete',false)),false,'100%','0','0','0',false,false,$Rec_Style_1,$Rec_Style_2,$Rec_Style_3,$Rec_Style_4,$Rec_Style_5);
if(is_array($rcrd))$rcrd = implode(",",$rcrd);
	array_pop($path);
?>
</div>
