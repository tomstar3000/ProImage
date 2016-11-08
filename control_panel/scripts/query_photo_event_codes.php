<? if(!isset($r_path)){	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;	$r_path = "";	for($n=0;$n<$count;$n++ )$r_path .= "../";}
$EId = $path[2];
include $r_path.'security.php';
include $r_path.'scripts/fnct_record_5_table.php';
if(isset($_POST['Controller']) && $_POST['Controller'] == "Save")include $r_path.'scripts/del_photo_event_codes.php'; ?>

<div id="Sub_Header"> <img src="/control_panel/images/hdr_form_left.jpg" width="17" height="33" border="0" align="left" />
 <h2>Preset Discount Codes List</h2>
 <? if($path[1] == "Preset"){ ?>
 <p id="Back"><a href="#" onclick="javascript:set_form('form_','<?  echo implode(",",array_slice($path,0,2)); ?>','query','<?  echo $sort; ?>','<?  echo $rcrd; ?>');">Back</a></p>
 <? } ?>
</div>
<div id="Div_Records">
 <?
$query_get_info = "SELECT `prod_discount_codes`.* , `photo_event_disc`.`event_disc_id`
	FROM `prod_discount_codes` 
	LEFT JOIN `photo_event_disc`
		ON ((`prod_discount_codes`.`disc_id` = `photo_event_disc`.`disc_id`
			AND `photo_event_disc`.`event_id` = '$EId')
			OR `photo_event_disc`.`disc_id` IS NULL)
	WHERE `prod_discount_codes`.`cust_id` = '0' AND `prod_discount_codes`.`disc_use` = 'y' 
	ORDER BY `prod_discount_codes`.`disc_name` ASC";
$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
$row_get_info = mysql_fetch_assoc($get_info);
$totalRows_get_info = mysql_num_rows($get_info);

$records = array();
$headers = array();
$sortheaders = false;
$div_data = false;
$drop_downs = false;
$headers[0] = "Use&nbsp;&nbsp;";
$headers[1] = "Code Name";
$headers[2] = "Code";
do{
	$count = count($records);
	$records[$count][0][0] = false;
	$records[$count][0][1] = (($row_get_info['event_disc_id'] == NULL)?false:true);
	$records[$count][0][2] = false;
	$records[$count][1] = $row_get_info['disc_id'];
	$records[$count][2] = $row_get_info['disc_name'];
	$records[$count][3] = $row_get_info['disc_code'];
} while ($row_get_info = mysql_fetch_assoc($get_info));
mysql_free_result($get_info);
	build_record_5_table('Discount','Discount Codes',$headers,$sortheaders,$records,$div_data,$drop_downs,array(array('Save Discount Code(s)','Save','Save',false)),false,'100%','0','0','0',false,false,$Rec_Style_1,$Rec_Style_2,$Rec_Style_3,$Rec_Style_4,$Rec_Style_5,true,false,false,false);
if(is_array($rcrd))$rcrd = implode(",",$rcrd);
?>
</div>
