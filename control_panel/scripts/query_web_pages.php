<? if(!isset($r_path)){	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;	$r_path = "";	for($n=0;$n<$count;$n++)$r_path .= "../";}
include $r_path.'security.php';
include $r_path.'scripts/fnct_record_5_table.php';
if(isset($_POST['Controller']) && $_POST['Controller'] == "Delete") include $r_path.'scripts/del_web_pages.php';
?>
<div id="Form_Header">
  <div id="Add"><img src="/<? echo $AevNet_Path; ?>/images/add.jpg" width="72" height="33" alt="add" onclick="javascript:set_form('form_','<? echo implode(',',$path); ?>','add','<? echo $sort; ?>','<? echo $rcrd; ?>');" /> </div>
  <img src="/<? echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
  <p>Website Page List </p>
</div>
<div id="Div_Records">
<?
$query_get_records = "SELECT * FROM `web_review_navigation` ORDER BY `nav_part_id`, `nav_name` ASC";
$get_records = mysql_query($query_get_records, $cp_connection) or die(mysql_error());

$records = array();
$headers = array();
$sortheaders = false;
$div_data = false;
$drop_downs = false;
$headers[0] = "Page Name";
$headers[1] = "Parent Page";

while ($row_get_records = mysql_fetch_assoc($get_records)){
	if($row_get_records['nav_part_id'] == 0){
		$prnt_name = "Home";
	} else if($row_get_records['nav_part_id'] != -1){
		$prnt_id = $row_get_records['nav_part_id'];
		$query_get_parent = "SELECT * FROM `web_review_navigation` WHERE `nav_id` = '$prnt_id'";
		$get_parent = mysql_query($query_get_parent, $cp_connection) or die(mysql_error());
		$row_get_parent = mysql_fetch_assoc($get_parent);
		$totalRows_get_parent = mysql_num_rows($get_parent);
		
		$prnt_name = $row_get_parent['nav_name'];
		
		mysql_free_result($get_parent);
	} else {
		$prnt_name = "&nbsp;";
	}
	$count = count($records);
	$records[$count][0][0] = false;
	$records[$count][0][1] = false;
	$records[$count][0][2] = false;
	$records[$count][1] = $row_get_records['nav_id'];
	$records[$count][2] = substr($row_get_records['nav_name'],0,25);
	if(strlen($row_get_records['nav_name'])>25) $records[$count][2].="...";
	$records[$count][3] = $prnt_name;
} 

mysql_free_result($get_records);

build_record_5_table('Web_Pages','Website Pages',$headers,$sortheaders,$records,$div_data,$drop_downs,array(array('Delete Page(s)','Delete','Delete',false)),false,'100%','0','0','0',false,false,$Rec_Style_1,$Rec_Style_2,$Rec_Style_3,$Rec_Style_4,$Rec_Style_5);
if(is_array($rcrd)) $rcrd = implode(",",$rcrd);
?>
</div>