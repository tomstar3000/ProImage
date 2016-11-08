<?php
include $r_path.'security_2.php';
function sort_groups($parents , $i_array, $name, $table, $id){
	global $cp_connection;
	
	$pu_array = array();
	$cu_array = array();
	$nu_array = array();
	
	foreach($parents as $key => $value){
		$pu_array[$key] = $parents[$key][0];
		$cu_array[$key] = $parents[$key][1];
		
		//mysql_select_db($database_cp_connection, $cp_connection);
		$query_get_names = "SELECT `$name` FROM `$table` WHERE `$id` = '$cu_array[$key]'";
		$get_names = mysql_query($query_get_names, $cp_connection) or die(mysql_error());
		$row_get_names = mysql_fetch_assoc($get_names);
		$totalRows_get_names = mysql_num_rows($get_names);
		
		$nu_array[$key] = $row_get_names[$name];
		
		mysql_free_result($get_names);
	}
	$n_array = array();
	$p_array = array();
	$c_array = array();
	$v_array = array();
	$n_array = $nu_array;
	natcasesort($n_array);
	foreach($n_array as $key => $value){
		$nkey = array_keys($nu_array, $value);
		$c_array[$key] = $cu_array[$nkey[0]];
		$p_array[$key] = $pu_array[$nkey[0]];
		$okey = array_keys($i_array[1], $c_array[$key]);
		if($i_array[0][$okey[0]] == ""){
			$v_array[$key][1] = 0;
		} else {
			$v_array[$key][1] = $i_array[0][$okey[0]];
		}
		$v_array[$key][0] = $value;
	}
	
	$records = array();
	$children = array_diff($c_array, $p_array);
	$parents = array_unique(array_diff($c_array, $children));
	$temp_vars = array();
	$n = 0;
	foreach($parents as $key => $value){
		foreach($c_array as $k => $v){
			if($value == $v){
				if($p_array[$k]!=0){
					$temp_vars[$n] = array_shift($parents);
					$n++;
				} 
			}
		}
	}
	$temp = array();
	foreach($children as $key => $value){
		$nkey = array_keys($c_array, $value);
		$temp[$key] = $p_array[$nkey[0]];
	}
	$notused = array_diff($parents, $temp);
	$thead = array();
	$sorted = array();
	$n = 0;
	foreach($parents as $key => $value){
		$add = true;	
		foreach($notused as $k => $v){
			if($value == $v){
				$add = false;
				break;
			}
		}
		if($add){
			$nkey = array_keys($c_array, $value);
			$thead[$n] = $v_array[$nkey[0]][0];
			$sorted[$n] = $value;
			$n++;
		}
		foreach($temp_vars as $k => $v){
			$nkey = array_keys($c_array, $v);
			$okey = array_keys($c_array, $value);
			$test = $p_array[$nkey[0]];
			if($test == $value){
				$thead[$n] = $v_array[$okey[0]][0]." - ".$v_array[$nkey[0]][0];
				$sorted[$n] = $v;
				$n++;
			}
		}
	}
	$n = 0;
	foreach($sorted as $key => $value){
		$nkey = array_keys($p_array, $value);
		foreach($children as $k => $v){
			$okey = array_keys($c_array, $v);
			if($p_array[$okey[0]] == $p_array[$nkey[0]]){
				$records[$n][0] = $thead[$key];
				$records[$n][1] = $v_array[$okey[0]][1];
				$records[$n][2] = $v_array[$okey[0]][0];
				$n++;
			}
		}
	}
	return $records;
}
?>