<?php
include $r_path.'security.php';
$cust_id = $_GET['id'];
ob_start();
?>

<div id="Form_Header">
  <div id="Add"><img src="/<?php echo $AevNet_Path; ?>/images/add.jpg" width="72" height="33" alt="add" onclick="document.location.href='<?php echo $_SERVER['PHP_SELF']; ?>?is_info=true&cont=add&info=cust_invoice,Invoice&Cust_Id=<?php echo $cust_id; ?>&<?php echo $_SERVER['QUERY_STRING']; ?>';" /></div>
</div>
<?php
$btn_add = ob_get_contents();
ob_end_clean(); ?>
<div id="Div_Records">
  <?php
$query_get_projects = "SELECT `proj_projects`.*, `proj_categories`.`cat_name` FROM `proj_projects` LEFT OUTER JOIN `proj_categories` ON `proj_categories`.`cat_id` = `proj_projects`.`cat_id` WHERE `cust_id` = '$cust_id'";
$get_projects = mysql_query($query_get_projects, $cp_connection) or die(mysql_error());
$row_get_projects = mysql_fetch_assoc($get_projects);
$totalRows_get_projects = mysql_num_rows($get_projects);

$records = array();
	$headers = array();
	$sortheaders = false;
	$div_data = false;
	$drop_downs = false;
	$headers[0] = "Project Name";
	$headers[1] = "Category";
	$headers[2] = "Time";
	$headers[3] = "Due";
	$headers[4] = "Completed";
	$headers[5] = "Portfolio";
	$n = 0;
	do{
		$proj_id = $row_get_projects['proj_id'];
		
		$query_get_time = "SELECT * FROM `proj_timesheet` WHERE `proj_id` = '$proj_id'";
		$get_time = mysql_query($query_get_time, $cp_connection) or die(mysql_error());
		$row_get_time = mysql_fetch_assoc($get_time);
		$totalRows_get_time = mysql_num_rows($get_time);
		$hours = 0;
		do{
			$hours .= $row_get_time['time_hours'];
		} while ($row_get_time = mysql_fetch_assoc($get_time));
		$records[$n][0] = "";
		$records[$n][1] = $row_get_projects['proj_id'];
		$records[$n][2] = $row_get_projects['proj_name'];
		$records[$n][3] = $row_get_projects['cat_name'];
		$records[$n][4] = $hours;
		$records[$n][5] = $row_get_projects['proj_end'];
		$records[$n][6] = ($row_get_projects['proj_comp'] = "y") ? "Yes" : "No";
		$records[$n][7] = ($row_get_projects['proj_port'] = "y") ? "Yes" : "No";
		
		$n++;
		
		mysql_free_result($get_time);
	} while ($row_get_projects = mysql_fetch_assoc($get_projects));

	mysql_free_result($get_projects);
	
	build_record_table('Projects', 'Projects', '', '', false, '', '', '', $headers, $sortheaders, $records, $div_data, $drop_downs, "view_product.php?", 'view', '', '_blank', 0, 0, 0, '', '100%', '', '', $Rec_Style_1, $Rec_Style_2, $Rec_Style_3, $btn_add, $hdr_img, "Record_Drop_Down");
?>
</div>
