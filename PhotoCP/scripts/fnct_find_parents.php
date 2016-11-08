<?php
include $r_path.'security_2.php';
function find_parents($value, $n, $table, $parent, $feild_id){
	global $parents;
	global $cp_connection;
	
	//mysql_select_db($database_cp_connection, $cp_connection);
	$query_get_parent = "SELECT `".$parent."` FROM `".$table."` WHERE `".$feild_id."` = '$value'";
	$get_parent = mysql_query($query_get_parent, $cp_connection) or die(mysql_error());
	$row_get_parent = mysql_fetch_assoc($get_parent);
	$totalRows_get_parent = mysql_num_rows($get_parent);
	
	$count = count($parents);
	
	$parents[$count][0] = $row_get_parent[$parent];
	$parents[$count][1] = $value;
	if($row_get_parent[$parent] != 0){
		find_parents($row_get_parent[$parent],$n, $table, $parent, $feild_id);
	}
	mysql_free_result($get_parent);
}
	
function find_parents_v2($value, $n, $table, $parent, $feild_id){
	global $parents;
	global $cp_connection;
	
	//mysql_select_db($database_cp_connection, $cp_connection);
	$query_get_parent = "SELECT `".$parent."` FROM `".$table."` WHERE `".$feild_id."` = '$value'";
	$get_parent = mysql_query($query_get_parent, $cp_connection) or die(mysql_error());
	$row_get_parent = mysql_fetch_assoc($get_parent);
	$totalRows_get_parent = mysql_num_rows($get_parent);
	
	$count = count($parents);
	
	$parents[$count] = $row_get_parent[$parent];
	if($row_get_parent[$parent] != 0){
		find_parents_v2($row_get_parent[$parent],$n, $table, $parent, $feild_id);
	}
	mysql_free_result($get_parent);
}
function find_parents_v3($value, $n, $table, $parent, $feild_id){
	global $pt_array;
	global $cp_connection;
	
	$count = count($pt_array);
	$pt_array[$count] = $value;
	
	//mysql_select_db($database_cp_connection, $cp_connection);
	$query_get_parent = "SELECT `".$parent."` FROM `".$table."` WHERE `".$feild_id."` = '$value'";
	$get_parent = mysql_query($query_get_parent, $cp_connection) or die(mysql_error());
	$row_get_parent = mysql_fetch_assoc($get_parent);
	$totalRows_get_parent = mysql_num_rows($get_parent);
	
	if($row_get_parent[$parent] != 0){
		find_parents_v3($row_get_parent[$parent],$n, $table, $parent, $feild_id);
	}
	mysql_free_result($get_parent);
}
?>