<?php if(!isset($r_path)){	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;	$r_path = "";	for($n=0;$n<$count;$n++)	$r_path .= "../";}
include $r_path.'security.php';
include $r_path.'scripts/fnct_record_5_table.php';
if($path[1] == "Open"){
	$query_get_open_projects = "SELECT `proj_projects`.*, `cust_customers`.`cust_fname`, `cust_customers`.`cust_lname`, `cust_customers`.`cust_cname`  FROM `proj_projects` LEFT OUTER JOIN `cust_customers` ON `cust_customers`.`cust_id` = `proj_projects`.`cust_id` WHERE `proj_comp` = 'n' ORDER BY `cust_customers`.`cust_fname`, `cust_customers`.`cust_lname`, `cust_customers`.`cust_cname` ASC";
	$get_open_projects = mysql_query($query_get_open_projects, $cp_connection) or die(mysql_error());
	$row_get_open_projects = mysql_fetch_assoc($get_open_projects);
	$totalRows_get_open_projects = mysql_num_rows($get_open_projects);
	
	$records = array();
	$headers = array();
	$sortheaders = false;
	$div_data = false;
	$drop_downs = false;
	$headers[0] = "Project Number";
	$headers[1] = "Client";
	$headers[2] = "Project Name";
	$headers[3] = "Due Date";
	$headers[4] = "Portfolio";
	$n = 0;
	do{
		$records[$n][0] = false;
		$records[$n][1] = $row_get_open_projects['proj_id'];
		$records[$n][2] = $row_get_open_projects['proj_number'];
		$records[$n][3] = ($row_get_open_projects['cust_fname'] != "" && $row_get_open_projects['cust_lname'] != "") ? $row_get_open_projects['cust_lname'].", ".$row_get_open_projects['cust_fname'] : $row_get_open_projects['cust_cname'];
		$records[$n][4] = $row_get_open_projects['proj_name'];
		$records[$n][5] = ($row_get_open_projects['proj_end'] != "0000-00-00 00:00:00") ? format_date($row_get_open_projects['proj_end'], 'Short', '', true, false) : "" ;
		$records[$n][6] = $row_get_open_projects['proj_port'];
		
		$n++;
	} while ($row_get_open_projects = mysql_fetch_assoc($get_open_projects)); 
	
	mysql_free_result($get_open_projects);
	build_record_5_table('Open_Projects','Open Projects',$headers,$sortheaders,$records,$div_data,$drop_downs,'Complete Project(s)','Complete','Complete',false,false,'100%','0','0','0',false,false,$Rec_Style_1,$Rec_Style_2,$Rec_Style_3);
} else {
	$query_get_open_projects = "SELECT `proj_projects`.*, `cust_customers`.`cust_fname`, `cust_customers`.`cust_lname`, `cust_customers`.`cust_cname`  FROM `proj_projects` LEFT OUTER JOIN `cust_customers` ON `cust_customers`.`cust_id` = `proj_projects`.`cust_id` ORDER BY `cust_customers`.`cust_fname`, `cust_customers`.`cust_lname`, `cust_customers`.`cust_cname` ASC";
	$get_open_projects = mysql_query($query_get_open_projects, $cp_connection) or die(mysql_error());
	$row_get_open_projects = mysql_fetch_assoc($get_open_projects);
	$totalRows_get_open_projects = mysql_num_rows($get_open_projects);
	
	$records = array();
	$headers = array();
	$sortheaders = false;
	$div_data = false;
	$drop_downs = false;
	$headers[0] = "Project Number";
	$headers[1] = "Client";
	$headers[2] = "Project Name";
	$headers[3] = "Due Date";
	$headers[4] = "Portfolio";
	$n = 0;
	do{
		$records[$n][0][0] = false;
		$records[$n][0][1] = false;
		$records[$n][0][2] = false;
		$records[$n][1] = $row_get_open_projects['proj_id'];
		$records[$n][2] = $row_get_open_projects['proj_number'];
		$records[$n][3] = ($row_get_open_projects['cust_fname'] != "" && $row_get_open_projects['cust_lname'] != "") ? $row_get_open_projects['cust_lname'].", ".$row_get_open_projects['cust_fname'] : $row_get_open_projects['cust_cname'];
		$records[$n][4] = $row_get_open_projects['proj_name'];
		$records[$n][5] = ($row_get_open_projects['proj_end'] != "0000-00-00 00:00:00") ? format_date($row_get_open_projects['proj_end'], 'Short', '', true, false) : "" ;
		$records[$n][6] = $row_get_open_projects['proj_port'];
		
		$n++;
	} while ($row_get_open_projects = mysql_fetch_assoc($get_open_projects)); 
	
	mysql_free_result($get_open_projects);
	
	build_record_5_table('Projects','Projects',$headers,$sortheaders,$records,$div_data,$drop_downs,array(array('Complete Project(s)','Complete','Complete',false)),false,'100%','0','0','0',false,false,$Rec_Style_1,$Rec_Style_2,$Rec_Style_3,$Rec_Style_4,$Rec_Style_5);
}
?>
