<?php
include $r_path.'security_2.php';
function sort_groups($parents, $i_array, $name, $table, $id, $parent, $offset){
	global $cp_connection;
	global $pt_array;
	
	$pu_array = array();
	$p_array = array();
	$cu_array = array();
	$nu_array = array();
	$groups = array();
	$records = array();
	
	foreach($parents as $k => $v){
		$pu_array[$k] = $parents[$k][0];
		$cu_array[$k] = $parents[$k][1];
	}
	foreach($i_array as $k => $v){
		$nkey = array_keys($cu_array, $v[1]);
		$key = $pu_array[$nkey[0]];
		$groups[$key][count($groups[$key])] = $v[1];
	}
	foreach($groups as $k => $v){
		foreach($v as $key => $val){
			$pt_array = array();
			find_parents_v3($val,0,$table,$parent,$id);	
			$count = count($nu_array);
			natcasesort($pt_array);
			$p_array[$count] = $val;
			foreach($pt_array as $k => $v){
				//mysql_select_db($database_cp_connection, $cp_connection);
				$query_get_names = "SELECT `$name` FROM `$table` WHERE `$id` = '$v'";
				$get_names = mysql_query($query_get_names, $cp_connection) or die(mysql_error());
				$row_get_names = mysql_fetch_assoc($get_names);
				$totalRows_get_names = mysql_num_rows($get_names);
				
				$nu_array[$count] = ($nu_array[$count] == "") ? $row_get_names[$name] : $nu_array[$count]." - ".$row_get_names[$name];
				mysql_free_result($get_names);
			}
		}
	}
	$n_array = array();
	$n_array = $nu_array;
	natcasesort($n_array);
	$n_array = array_unique($n_array);
	foreach ($n_array as $k => $v){
		$nkey = array_keys($nu_array, $v);
		$find = $p_array[$nkey[0]];
		foreach ($i_array as $key => $val){
			if($val[1]==$find){
				$count = count($records);
				$temp = $v;
				if($offset == true){
					$pos = strpos($temp, " - ");
					$temp = substr($temp, 0, $pos);
				}
				$records[$count][0] = $temp;
				$records[$count][1] = $val[0];
				$records[$count][2] = $val[2];
			}
		}
	}
	return $records;
}
?>