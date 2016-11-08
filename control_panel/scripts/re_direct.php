<?php 
include $r_path.'security.php';
$url_string = preg_replace("/is_info=[\\d\\w]*/","",$_SERVER['QUERY_STRING']);
$url_string = preg_replace("/&cont=[\\d\\w]*/","",$url_string);
$url_string = preg_replace("/&info=[\\d\\w%20,]*&/","",$url_string);

if(isset($_GET['Proj_Id'])){
	$url_string = preg_replace("/id=[\\d\\w]*/","id=".$_GET['Proj_Id'],$url_string);
	$url_string = preg_replace("/&Proj_Id=[\\d\\w]*/","",$url_string);
} else if (isset($_GET['Prod_Id'])){
	$url_string = preg_replace("/id=[\\d\\w]*/","id=".$_GET['Prod_Id'],$url_string);
	$url_string = preg_replace("/&Prod_Id=[\\d\\w]*/","",$url_string);
} else if (isset($_GET['Cust_Id'])){
	$url_string = preg_replace("/id=[\\d\\w]*/","id=".$_GET['Cust_Id'],$url_string);
	$url_string = preg_replace("/&Cust_Id=[\\d\\w]*/","",$url_string);
} else if (isset($_GET['cpid'])){
	$url_string = preg_replace("/&cpid=[\\d\\w]*/","",$url_string);
} else if (isset($_GET['fpid'])){
	$url_string = preg_replace("/&fpid=[\\d\\w]*/","",$url_string);
} else if (isset($_GET['spid'])){
	$url_string = preg_replace("/&spid=[\\d\\w]*/","",$url_string);
} else if (isset($_GET['apid'])){
	$url_string = preg_replace("/&apid=[\\d\\w]*/","",$url_string);
} else if (isset($_GET['apid'])){
	$url_string = preg_replace("/&apid=[\\d\\w]*/","",$url_string);
}

$url_string = $_SERVER['PHP_SELF']."?".$url_string;

echo "<script type=\"text/javascript\">document.location.href=\"".$url_string."\";</script>";
//echo "<body onLoad=\"javascript:opener.page_refresh(); window.close();\"></body>";
die(); 
?>