<?php include $r_path.'security.php'; 
$temppathing = $pathing[0].",".$pathing[1];
if(isset($_GET['op'])){
	$oldpathing = $pathing[0].",".$_GET['op'].",".$pathing[1];
	$old_id = "&id=".$_GET['oid'];
} else {
	$oldpathing = $temppathing;
	$old_id = "";
}
include $r_path.'scripts/save_web_page.php'; 
$pattern = "/Path=";
for($n=0;$n<count($pathing);$n++){
	$pattern .= ($n == 0) ? "[\\d\\w]*" : ",[\\d\\w]*" ;
}
$pattern .= "/";
?>

<div id="Info">
  <?php include $r_path.'forms/web_page_info.php';  ?>
</div>
<div id="Product_Content">
  <?php if ($pathing[2] == "Block"){
		include $r_path.'scripts/query_web_pages.php';
	} ?>
</div>
