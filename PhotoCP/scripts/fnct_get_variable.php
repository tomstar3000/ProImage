<?
include $r_path.'security_2.php';
function get_variable($variable,$default, $action = false, $clean = true){
	if($clean === true){
		$return = (isset($variable)) ? clean_variable($variable,$action) : $default;
	} else {
		$return = (isset($variable)) ? $variable : $default;
	}
	return $return;
}
?>