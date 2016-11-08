<?php
include $r_path.'security_2.php';
function format_file_name($Fname,$int){
	$Dname = date("mdy");
	$pos = strrpos($Fname, ".");
	$ext = substr($Fname, ($pos+1));
	$Fname = substr($Fname,0,$pos);
	$Fname = md5($Dname.$int.$Fname).".".$ext;
	
	return $Fname;
}
?>