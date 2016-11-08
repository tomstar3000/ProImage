<?php include $r_path.'security.php'; 
$temppathing = $pathing[0].",".$pathing[1];
if(isset($_GET['op'])){
	$oldpathing = $pathing[0].",".$_GET['op'].",".$pathing[1];
	$old_id = "&id=".$_GET['oid'];
} else {
	$oldpathing = $temppathing;
	$old_id = "";
}
include $r_path.'scripts/save_project.php'; 
$pattern = "/Path=";
for($n=0;$n<count($pathing);$n++){
	$pattern .= ($n == 0) ? "[\\d\\w]*" : ",[\\d\\w]*" ;
}
$pattern .= "/";
?>

<div id="Tri_Nav">
  <table border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td align="center" class="Tabs" onclick="document.location.href='<?php echo $_SERVER['PHP_SELF']."?".preg_replace($pattern,"Path=".$temppathing.",Over",$_SERVER['QUERY_STRING']);?>'"><p>Overview</p></td>
      <td align="center" class="Tabs" onclick="document.location.href='<?php echo $_SERVER['PHP_SELF']."?".preg_replace($pattern,"Path=".$temppathing.",Time",$_SERVER['QUERY_STRING']);?>'"><p>Timesheet</p></td>
      <td align="center" class="Tabs" onclick="document.location.href='<?php echo $_SERVER['PHP_SELF']."?".preg_replace($pattern,"Path=".$temppathing.",Asset",$_SERVER['QUERY_STRING']); ?>'"><p>Assets</p></td>
      <td align="center" class="Tabs" onclick="document.location.href='<?php echo $_SERVER['PHP_SELF']."?".preg_replace($pattern,"Path=".$temppathing.",Supp",$_SERVER['QUERY_STRING']); ?>'"><p>Supplies</p></td>
      <td align="center" class="Tabs" onclick="document.location.href='<?php echo $_SERVER['PHP_SELF']."?".preg_replace($pattern,"Path=".$temppathing.",Img",$_SERVER['QUERY_STRING']); ?>'"><p>Images</p></td>
      <td align="center" class="Tabs" onclick="document.location.href='<?php echo $_SERVER['PHP_SELF']."?".preg_replace($pattern,"Path=".$temppathing.",Mov",$_SERVER['QUERY_STRING']); ?>'"><p>Movies</p></td>
      <td align="center" class="Tabs" onclick="document.location.href='<?php echo $_SERVER['PHP_SELF']."?".preg_replace($pattern,"Path=".$temppathing.",Sound",$_SERVER['QUERY_STRING']); ?>'"><p>Sounds</p></td>
    </tr>
  </table>
</div>
<div id="Info">
  <?php include $r_path.'forms/project_info.php'; ?>
</div>
<div id="Product_Content">
  <?php if ($pathing[2] == "Time"){
		include $r_path.'scripts/query_proj_timesheet.php';
	} else if ($pathing[2] == "Asset"){
		include $r_path.'scripts/query_proj_asset.php';
	} else if ($pathing[2] == "Supp"){
		include $r_path.'scripts/query_proj_supp.php';
	} else {
	}?>
</div>
