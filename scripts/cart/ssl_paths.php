<?php if(!isset($r_path)){$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-1;	for($n=0;$n<$count;$n++)$r_path .= "../";}

define('ISTESTING', false);

//include $r_path.'scripts/security.php';
$ssl_folders = array();
//$ssl_folders[0] = "checkout";
//$ssl_folders[1] = "member";
$ssl_files = array();
$ssl_files[0] = "enroll.php";
$ssl_files[1] = "join.php";
$ssl_files[2] = "checkout.php";
$files = explode("/",$_SERVER['PHP_SELF']);

$result1 = array_intersect($files, $ssl_folders);
$result2 = array_intersect($files, $ssl_files);
$www = (!eregi('www.',$_SERVER['HTTP_HOST'])) ? "www." : "";
if(ISTESTING === false){
	if(count($result1) > 0 || count($result2) > 0){
		if($_SERVER['HTTPS'] != "on"){
			$GoTo = "https://".$www.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'];
			header(sprintf("Location: %s", $GoTo));
		} else if(!eregi('www.',$_SERVER['HTTP_HOST'])){
			$GoTo = "https://".$www.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'];
			header(sprintf("Location: %s", $GoTo));
		}
	} else {
		if($_SERVER['HTTPS'] == "on"){
			$GoTo = "http://".$www.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'];
			header(sprintf("Location: %s", $GoTo));
		} else if(!eregi('www.',$_SERVER['HTTP_HOST'])){
			$GoTo = "http://".$www.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'];
			header(sprintf("Location: %s", $GoTo));
		}
	}
}
?>