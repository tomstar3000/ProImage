<?
if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-1;
	for($n=0;$n<$count;$n++){
		$r_path .= "../";
	}
}
include $r_path.'scripts/security.php';
function phone_number($value){
	//1234567890
	//0123456789
	$value = "(".substr($value,0,3).") ".substr($value,3,3)."-".substr($value,6,4);
	return $value;
}
?>