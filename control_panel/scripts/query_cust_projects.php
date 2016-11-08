<?php if(!isset($r_path)){	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;	$r_path = "";	for($n=0;$n<$count;$n++)$r_path .= "../";}
include $r_path.'security.php';
include $r_path.'scripts/fnct_record_5_table.php'; ?>

<div id="Form_Header">
  <div id="Add"><img src="/<?php echo $AevNet_Path; ?>/images/add.jpg" width="72" height="33" alt="add" onclick="javascript:set_form('form_','<?php echo implode(",",$path); ?>','add','<?php echo $sort; ?>','<?php echo $rcrd; ?>');" /></div>
</div>
<div id="Div_Records">
  <?php
$query_get_projects = "SELECT `proj_projects`.*, `proj_categories`.`cat_name` FROM `proj_projects` LEFT OUTER JOIN `proj_categories` ON `proj_categories`.`cat_id` = `proj_projects`.`cat_id` WHERE `cust_id` = '$CId'";
$get_projects = mysql_query($query_get_projects, $cp_connection) or die(mysql_error());
$row_get_projects = mysql_fetch_assoc($get_projects);
	$records = array();
	$headers = array();
	$sortheaders = false;
	$div_data = false;
	$drop_downs = false;
	$headers[0] = "Project Name";
	$headers[1] = "Category";
	$headers[2] = "Time";
	$headers[3] = "Price";
	$headers[4] = "Due";
	$headers[5] = "Completed";
	$headers[6] = "Portfolio";
	$n = 0;
	do{
		$proj_id = $row_get_projects['proj_id'];
		
		$query_get_time = "SELECT * FROM `proj_timesheet` WHERE `proj_id` = '$proj_id'";
		$get_time = mysql_query($query_get_time, $cp_connection) or die(mysql_error());
		$row_get_time = mysql_fetch_assoc($get_time);
		$totalRows_get_time = mysql_num_rows($get_time);
		$hours = 0;
		do{
			$hours += $row_get_time['time_hours'];
		} while ($row_get_time = mysql_fetch_assoc($get_time));
		
		$records[$n][0][0] = false;
		$records[$n][0][1] = false;
		$records[$n][0][2] = false;
		$records[$n][1] = $row_get_projects['proj_id'];
		$records[$n][2] = $row_get_projects['proj_name'];
		$records[$n][3] = $row_get_projects['cat_name'];
		$records[$n][4] = $hours;
		$records[$n][5] = ($hours == 0) ? "$".number_format($row_get_projects['proj_price'],2,".",",") : "$".number_format(($hours*$row_get_projects['proj_bill']),2,".",",");
		$records[$n][6] = ($row_get_projects['proj_end'] != "0000-00-00 00:00:00") ? format_date($row_get_projects['proj_end'], 'Short', '', true, false) : "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" ;;
		$records[$n][7] = ($row_get_projects['proj_comp'] == "y") ? "Yes" : "No";
		$records[$n][8] = ($row_get_projects['proj_port'] == "y") ? "Yes" : "No";
		$n++;
		
		mysql_free_result($get_time);
	} while ($row_get_projects = mysql_fetch_assoc($get_projects));

	mysql_free_result($get_projects);
	
	build_record_5_table('Projects','Projects',$headers,$sortheaders,$records,$div_data,$drop_downs,array(array('Delete Project(s)','Delete','Delete',false)),false,'100%','0','0','0',false,false,$Rec_Style_1,$Rec_Style_2,$Rec_Style_3,$Rec_Style_4,$Rec_Style_5,false,false);
?>
</div>
