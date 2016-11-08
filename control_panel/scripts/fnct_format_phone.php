<?php
include $r_path.'security_2.php';
function phone_number($value){
	//1234567890
	//0123456789
	$value = "(".substr($value,0,3).") ".substr($value,3,3)."-".substr($value,6,4);
	return $value;
}
?>