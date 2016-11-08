<? if(!isset($r_path)){	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;	$r_path = "";	for($n=0;$n<$count;$n++)$r_path .= "../";}
include $r_path.'security.php';
require_once $r_path.'scripts/fnct_record_5_table.php';
require_once $r_path.'scripts/fnct_format_date.php';
if(isset($_POST['Controller']) && $_POST['Controller'] == "Delete")include $r_path.'scripts/del_web_career.php';
?>

<div id="Form_Header">
  <div id="Add"><img src="/<? echo $AevNet_Path; ?>/images/add.jpg" width="72" height="33" alt="add" onclick="javascript:set_form('','Web,Career','add','<? echo $sort; ?>','<? echo $rcrd; ?>');" /> </div>
  <img src="/<? echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
  <p>Career List </p>
</div>
<div id="Div_Records">
  <?
$query_get_info = "SELECT * FROM `web_careers` ORDER BY `career_name`, `career_number` ASC";
$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());

$records = array();
$headers = array();
$sortheaders = false;
$div_data = false;
$drop_downs = false;
$headers[0] = "Career Title";
$headers[1] = "Date";
$headers[2] = "Active";

while ($row_get_info = mysql_fetch_assoc($get_info)){
	$count = count($records);
	$records[$count][0][0] = false;
	$records[$count][0][1] = false;
	$records[$count][0][2] = false;
	$records[$count][1] = $row_get_info['career_id'];
	$records[$count][2] = $row_get_info['career_name'];;
	$records[$count][3] = format_date($row_get_info['career_date'],"Short", false, true, false);
	$records[$count][4] = ($row_get_info['career_active'] == "y") ? "Yes" : "No";
}

mysql_free_result($get_info);
build_record_5_table('Careers','Careers',$headers,$sortheaders,$records,$div_data,$drop_downs,array(array('Delete Career(s)','Delete','Delete',false)),false,'100%','0','0','0',false,false,$Rec_Style_1,$Rec_Style_2,$Rec_Style_3,$Rec_Style_4,$Rec_Style_5);
if(is_array($rcrd))$rcrd = implode(",",$rcrd);
?>
</div>
