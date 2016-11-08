<?php
include $r_path.'security_2.php';
function find_children($value, $n, $table, $parent, $feild_id){
	global $children;
	global $cp_connection;
	
	//mysql_select_db($database_cp_connection, $cp_connection);
	$query_get_children = "SELECT `".$feild_id."`, `".$parent."` FROM `".$table."` WHERE `".$parent."` = '$value'";
	$get_children = mysql_query($query_get_children, $cp_connection) or die(mysql_error());
	$row_get_children = mysql_fetch_assoc($get_children);
	$totalRows_get_children = mysql_num_rows($get_children);
	
	if($totalRows_get_children != 0){
		do{
			$count = count($children);
			$children[$count] = $row_get_children[$feild_id];
			if($row_get_children[$parent] != 0){
				find_children($row_get_children[$feild_id], $n, $table, $parent, $feild_id);
			}			
		} while ($row_get_children = mysql_fetch_assoc($get_children));
	}
	mysql_free_result($get_children);
}
?>