<?php include $r_path.'security.php'; 
require_once($r_path.'scripts/crumbs_pathing.php');
$temppathing = array();
if(count($pathing)>2){
	$temppathing = array_slice($pathing,0,-2);
} else {
	$temppathing = $pathing;
}
?>
<div id="Tri_Nav">
  <table border="0" cellpadding="0" cellspacing="0">
    <tr>
	  <td align="center" class="Tabs" onclick="document.location.href='<?php echo $_SERVER['PHP_SELF']."?".preg_replace("/Path=[\\d\\w,]*/","Path=".implode(',',$temppathing),$query_string);?>'"><p>Overview</p></td>
      <td align="center" class="Tabs" onclick="document.location.href='<?php echo $_SERVER['PHP_SELF']."?".preg_replace("/Path=[\\d\\w,]*/","Path=".implode(',',$temppathing).",".$_GET['id'].",Attr",$query_string);?>'"><p>Attributes</p></td>
      <td align="center" class="Tabs" onclick="document.location.href='<?php echo $_SERVER['PHP_SELF']."?".preg_replace("/Path=[\\d\\w,]*/","Path=".implode(',',$temppathing).",".$_GET['id'].",Feat",$query_string);?>'"><p>Features</p></td>
      <td align="center" class="Tabs" onclick="document.location.href='<?php echo $_SERVER['PHP_SELF']."?".preg_replace("/Path=[\\d\\w,]*/","Path=".implode(',',$temppathing).",".$_GET['id'].",Spec",$query_string);?>'"><p>Specs</p></td>
      <td align="center" class="Tabs" onclick="document.location.href='<?php echo $_SERVER['PHP_SELF']."?".preg_replace("/Path=[\\d\\w,]*/","Path=".implode(',',$temppathing).",".$_GET['id'].",Review",$query_string);?>'"><p>Reviews</p></td>
      <td align="center" class="Tabs" onclick="document.location.href='<?php echo $_SERVER['PHP_SELF']."?".preg_replace("/Path=[\\d\\w,]*/","Path=".implode(',',$temppathing).",".$_GET['id'].",Rating",$query_string);?>'"><p>Ratings</p></td>
    </tr>
    <tr>
      <td align="center" class="Tabs" onclick="location.href='<?php echo $_SERVER['PHP_SELF']."?".preg_replace("/Path=[\\d\\w,]*/","Path=".implode(',',$temppathing).",".$_GET['id'].",Images",$query_string);?>'"><p>Images</p></td>
      <td align="center" class="Tabs" onclick="location.href='<?php echo $_SERVER['PHP_SELF']."?".preg_replace("/Path=[\\d\\w,]*/","Path=".implode(',',$temppathing).",".$_GET['id'].",Groups",$query_string);?>'"><p>Groups</p></td>
      <td align="center" class="Tabs" onclick="location.href='<?php echo $_SERVER['PHP_SELF']."?".preg_replace("/Path=[\\d\\w,]*/","Path=".implode(',',$temppathing).",".$_GET['id'].",Specl",$query_string);?>'"><p>Specl. Delivery</p></td>
      <td align="center" class="Tabs" onclick="location.href='<?php echo $_SERVER['PHP_SELF']."?".preg_replace("/Path=[\\d\\w,]*/","Path=".implode(',',$temppathing).",".$_GET['id'].",Sel",$query_string);?>'"><p>Selections</p></td>
      <td align="center" class="Tabs" onclick="location.href='<?php echo $_SERVER['PHP_SELF']."?".preg_replace("/Path=[\\d\\w,]*/","Path=".implode(',',$temppathing).",".$_GET['id'].",Disc",$query_string);?>'"><p>Discounts</p></td>
	  <td align="center" class="Tabs" onclick="location.href='<?php echo $_SERVER['PHP_SELF']."?".preg_replace("/Path=[\\d\\w,]*/","Path=".implode(',',$temppathing).",".$_GET['id'].",Doc",$query_string);?>'"><p>Documentation</p>
      </td>
    </tr>
  </table>
</div>
<div id="Info">
  <?php include $r_path.'forms/products_info.php';  ?>
</div>
<div id="Product_Content">
  <?php 
    if ($pathing[3] == "Attr"){
		include $r_path.'scripts/query_prod_attribute.php';
	} else if ($pathing[3] == "Feat"){
		include $r_path.'scripts/query_prod_features.php';
	} else if ($pathing[3] == "Spec"){
		include $r_path.'scripts/query_prod_specs.php';
	} else if ($pathing[3]  == "Review") {
		include $r_path.'scripts/query_prod_review.php';
	} else if ($pathing[3]  == "Rating") {
		include $r_path.'scripts/query_prod_rating.php';
	} else if ($pathing[3]  == "Images") {
		include $r_path.'scripts/query_prod_images.php';
	} else if ($pathing[3] == "Groups"){
		include $r_path.'scripts/query_prod_groups.php';
	} else if ($pathing[3] == "Specl"){
		include $r_path.'scripts/query_prod_specl.php';
	} else if ($pathing[3] == "Sel"){
		include $r_path.'scripts/query_prod_sel_group.php';
	} else if ($pathing[3] == "Doc"){
		include $r_path.'scripts/query_prod_documents.php';
	} ?>
</div>
