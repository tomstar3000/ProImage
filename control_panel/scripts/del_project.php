<?php

include $r_path.'security.php';
$items = array();
$items = $_POST['Projets_items'];
function delete_vals($value){
	global $cp_connection;
	
	
}
if(count($items)>0){
	foreach ($items as $key => $value){
		delete_vals($value);
	}
}
?>