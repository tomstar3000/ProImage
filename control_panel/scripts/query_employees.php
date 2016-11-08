<? if(!isset($r_path)){$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2; $r_path = "";	for($n=0;$n<$count;$n++)$r_path .= "../"; }
include $r_path.'security.php';
include $r_path.'scripts/fnct_record_5_table.php';
if(isset($_POST['Controller']) && $_POST['Controller'] == "Delete")include $r_path.'scripts/del_employees.php'; ?>

<div id="Form_Header">
<div id="Add">
  <div id="Add"><img src="/<? echo $AevNet_Path; ?>/images/add.jpg" width="72" height="33" alt="add" onclick="javascript:set_form('','Admin,Emp','add','<? echo $sort; ?>','<? echo $rcrd; ?>');" /> </div>
  <img src="/<? echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
  <p>User List</p>
</div>
<div id="Div_Records">
  <?
$query_get_info = "SELECT * FROM `emp_employees` ORDER BY `emp_fname`, `emp_lname` ASC";
$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());

$records = array();
$headers = array();
$sortheaders = false;
$div_data = array();
$drop_downs = false;
$headers[0] = "First Name";
$headers[1] = "Title";
$headers[2] = "Work Type";
while ($row_get_info = mysql_fetch_assoc($get_info)){
	$count = count($records);
	$records[$count][0][0] = false;
	$records[$count][0][1] = false;
	$records[$count][0][2] = false;
	$records[$count][1] = $row_get_info['emp_id'];
	$records[$count][2] = $row_get_info['emp_fname']." ".$row_get_info['emp_lname'];
	$records[$count][3] = $row_get_info['emp_title'];
	$records[$count][4] = $row_get_info['proj_worktype'];
	
	$div_data[$count] = "";
		if($row_get_info['emp_add']){
			$div_data[$count] .= $row_get_info['emp_add']." ".$row_get_info['emp_suite_apt']."<br />";
			if($row_get_info['emp_add_2']){
				$div_data[$count] .= $row_get_info['emp_add_2']."<br />";
			}
			$div_data[$count] .= $row_get_info['emp_city'].", ".$row_get_info['emp_state'].". ".$row_get_info['emp_zip']."<br />";
			if($row_get_info['emp_country']){
				$div_data[$count] .= $row_get_info['emp_country']."<br />";
			}
		}
		if($row_get_info['emp_phone']){
			$div_data[$count] .= "Phone Number: ". phone_number($row_get_info['emp_phone'])."<br />";
		}
		if($row_get_info['emp_cell']){
			$div_data[$count] .= "Mobile Number: ". phone_number($row_get_info['emp_cell'])."<br />";
		}
		if($row_get_info['emp_email']){
			$div_data[$count] .= "E-mail: <a href=\"mailto:".$row_get_info['emp_email']."\">".$row_get_info['emp_email']."</a><br />";
		}
}

mysql_free_result($get_info);
	build_record_5_table('Employees','Employees',$headers,$sortheaders,$records,$div_data,$drop_downs,array(array('Delete Employee(s)','Delete','Delete',false)),false,'100%','0','0','0',false,false,$Rec_Style_1,$Rec_Style_2,$Rec_Style_3,$Rec_Style_4,$Rec_Style_5);
if(is_array($rcrd))$rcrd = implode(",",$rcrd);
?>
</div>
