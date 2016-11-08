<? if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++)$r_path .= "../";
}
include $r_path.'security.php';
include $r_path.'scripts/fnct_record_5_table.php';
if(isset($_POST['Controller']) && $_POST['Controller'] == "true")include $r_path.'scripts/del_web_req_types.php';
?>
<div id="Form_Header">
  <div id="Add"><img src="/<? echo $AevNet_Path; ?>/images/add.jpg" width="72" height="33" alt="add" onclick="javascript:set_form('','Web,ReqType','add','<? echo $sort; ?>','<? echo $rcrd; ?>');" /> </div>
  <img src="/<? echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
  <p>Career Requirement List </p>
</div>
<div id="Div_Records">
<?
$query_get_info = "SELECT * FROM `web_career_req_type` ORDER BY `career_req_type_name` ASC";
$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());

$records = array();
$headers = array();
$sortheaders = false;
$div_data = false;
$drop_downs = false;
$headers[0] = "Name";

while ($row_get_info = mysql_fetch_assoc($get_info)){
	$count = count($records);
	$records[$count][0][0] = false;
	$records[$count][0][1] = false;
	$records[$count][0][2] = false;
	$records[$count][1] = $row_get_info['career_req_type_id'];
	$records[$count][2] = $row_get_info['career_req_type_name'];
} 

mysql_free_result($get_info);

build_record_5_table('Req_Types','Career Requirement Types',$headers,$sortheaders,$records,$div_data,$drop_downs,array(array('Delete Requirement_Type(s)','Delete','Delete',false)),false,'100%','0','0','0',false,false,$Rec_Style_1,$Rec_Style_2,$Rec_Style_3,$Rec_Style_4,$Rec_Style_5);
if(is_array($rcrd))$rcrd = implode(",",$rcrd);
?>
</div>