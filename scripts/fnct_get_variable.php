<?
if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-1;
	for($n=0;$n<$count;$n++){
		$r_path .= "../";
	}
}
include $r_path.'scripts/security.php';
function get_variable($variable,$default, $action = false, $clean = true){
	if($clean === true){
		$return = (isset($variable)) ? clean_variable($variable,$action) : $default;
	} else {
		$return = (isset($variable)) ? $variable : $default;
	}
	return $return;
}
?>