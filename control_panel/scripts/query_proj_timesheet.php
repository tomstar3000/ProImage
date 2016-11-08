<?php if(!isset($r_path)){	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;	$r_path = "";	for($n=0;$n<$count;$n++)	$r_path .= "../";}
include $r_path.'security.php';
include $r_path.'scripts/fnct_record_5_table.php';
if(isset($_POST['Controller']) && $_POST['Controller'] == "Delete")include $r_path.'scripts/del_proj_timesheet.php'; ?>

<div id="Form_Header">
  <div id="Add"><img src="/<?php echo $AevNet_Path; ?>/images/add.jpg" width="72" height="33" alt="add" onclick="javascript:set_form('form_','<?php echo implode(",",$path); ?>','add','<?php echo $sort; ?>','<?php echo $rcrd; ?>');" /></div>
</div>
<div id="Div_Records">
  <?php
	$query_get_times = "SELECT `proj_timesheet`.*, `emp_employees`.`emp_fname`, `emp_employees`.`emp_lname`, `proj_worktype`.`proj_worktype` FROM `proj_timesheet` LEFT OUTER JOIN `emp_employees` ON `emp_employees`.`emp_id` = `proj_timesheet`.`emp_id` LEFT OUTER JOIN `proj_worktype` ON `proj_worktype`.`proj_worktype_id` = `proj_timesheet`.`cat_id` WHERE `proj_id` = '$PId' ORDER BY `time_date` ASC";
	$get_times = mysql_query($query_get_times, $cp_connection) or die(mysql_error());
	$row_get_times = mysql_fetch_assoc($get_times);
	$totalRows_get_times = mysql_num_rows($get_times);

	$records = array();
	$headers = array();
	$sortheaders = false;
	$div_data = false;
	$drop_downs = false;
	$headers[0] = "Date";
	$headers[1] = "Employee";
	$headers[2] = "Time";
	$headers[3] = "Rate";
	$headers[4] = "Total";
	$headers[5] = "Work Type";
	$n = 0;
	do{
		
		$records[$n][0][0] = false;
		$records[$n][0][1] = false;
		$records[$n][0][2] = false;
		$records[$n][1] = $row_get_times['time_id'];
		$records[$n][2] = format_date($row_get_times['time_date'], 'Short', 'Standard', true, false);
		$records[$n][3] = $row_get_times['emp_lname'].", ".$row_get_times['emp_fname'];
		$records[$n][4] = $row_get_times['time_hours']." hrs";
		$records[$n][5] = "$".number_format($row_get_times['time_bill'],2,".",",");
		$records[$n][6] = "$".number_format($row_get_times['time_amt'],2,".",",");
		$records[$n][7] = $row_get_times['proj_worktype'];
		$n++;
	} while ($row_get_times = mysql_fetch_assoc($get_times)); 
	
	build_record_5_table('Timesheet','Timesheet',$headers,$sortheaders,$records,$div_data,$drop_downs,array(array('Delete Time(s)','Delete','Delete',false)),false,'100%','0','0','0',false,false,$Rec_Style_1,$Rec_Style_2,$Rec_Style_3,$Rec_Style_4,$Rec_Style_5);
mysql_free_result($get_times);
?>
</div>
