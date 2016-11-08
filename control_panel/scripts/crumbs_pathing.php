<?php
$query_string = preg_replace("/is_info=[\\d\\w,]*/","",$_SERVER['QUERY_STRING']);
$query_string = preg_replace("/&cont=[\\d\\w,]*/","",$query_string);
$query_string = preg_replace("/&info=[\\d\\w,]*/","",$query_string);
$query_string = preg_replace("/&view=[\\d\\w,]*/","",$query_string);

if(!isset($_GET['oid'])){
	$backpathing = array();
	$backpathing = $pathing;
	if(!isset($_GET['cont']) || $_GET['cont'] == "edit" || $_GET['cont'] == "view"){
		if(isset($_GET['id']) && $backpathing[count($backpathing)-2] == $_GET['id']){
			$back_string = implode(',',array_slice($backpathing,0,-2));
			$back_string = preg_replace("/Path=[\\d\\w,]*/","Path=".$back_string,$query_string);
			$back_string = preg_replace("/id=[\\d\\w,]*/","id=".$pathing[count($pathing)-2],$back_string);
		} else if (isset($_GET['id']) && is_numeric($backpathing[count($backpathing)-2])){
			$back_string = implode(',',array_slice($backpathing,0,-2));
			$back_string = preg_replace("/id=[\\d\\w,]*/","id=".$pathing[count($pathing)-2],$query_string);
		} else if(count($backpathing)>2) {
			$back_string = implode(',',array_slice($backpathing,-1));
		} else {
			$back_string = preg_replace("/&id=[\\d\\w,]*/","",$query_string);;
		}
	} else {
		$back_string = $query_string;
	}
	if(strpos($back_string,"&") === 0){
		$back_string = substr($back_string,1);
	}
	if(isset($_GET['view']) && $_GET['view'] == "true"){
		$back_string = "is_info=".$_GET['is_info']."&cont=view&info=".$_GET['info']."&".$back_string."&id=".$_GET['id'];
	}
} else {
	$backpathing = explode(",",$_GET['oid']);
	$backpathing_2= $backpathing[count($backpathing)-1];
	$backpathing = array_slice($backpathing,0,-1);
	if($backpathing_2 == 0){
		$back_string = preg_replace("/&oid=[\\d\\w,-]*/","",$query_string);
		$back_string = preg_replace("/&id=[\\d\\w,]*/","",$back_string);
	} else {
		$back_string = preg_replace("/id=[\\d\\w,]*/","id=".$backpathing_2,$query_string);
		$back_string = preg_replace("/oid=[\\d\\w,-]*/","oid=".implode(",",$backpathing),$back_string);
	}
	unset($backpathing_2);
}
if(strpos($query_string,"&") === 0){
	$query_string = substr($query_string,1);
}

?>