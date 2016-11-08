<?
function randomnumber($length){
	$pattern = "1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
	for($i=0;$i<$length;$i++){
		if(isset($key)){
			$key .= $pattern{rand(0,61)};
		} else {
			$key = $pattern{rand(0,61)};
		}
	}
	return $key;
}
?>