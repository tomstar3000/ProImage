<?php if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++){
		$r_path .= "../";
	}
}
include $r_path.'security.php';
require_once $r_path.'scripts/fnct_clean_entry.php';
require_once $r_path.'scripts/fnct_image_resize.php';
require_once $r_path.'scripts/fnct_format_file_name.php';
$RId = $path[4];
$ProdId = $path[2];
$Image_1 = "";
$Imagev_1 = (isset($_POST['Image_val'])) ? clean_variable($_POST['Image_val'],true) : '';
if(isset($_POST['Controller']) && $_POST['Controller'] == "Save" && ($cont == 'add' || $cont == 'edit')){
	$message = "";
	for($n = 1; $n<=5; $n++){
		$IName = (isset($_POST['Name_'.$n])) ? clean_variable($_POST['Name_'.$n],true) : '0';
		$IAlt = (isset($_POST['Alt_'.$n])) ? clean_variable($_POST['Alt_'.$n],true) : '0';
		if (is_uploaded_file($_FILES['Image_'.$n]['tmp_name'])){
			$Fname = $_FILES['Image_'.$n]['name'];
			$Iname = format_file_name($Fname,"i");
			$ISize = $_FILES['Image_'.$n]['size'];
			$ITemp = $_FILES['Image_'.$n]['tmp_name'];
			$IType = $_FILES['Image_'.$n]['type'];
			
			$Image = loadimage($MaxSize, $Fname, $Iname, $ISize, $ITemp, $IType, $Prod_Folder, $Prod_IWidth, $Prod_IHeight, $Prod_ICrop, $Prod_IResize);
		} else {
			$Image = array();
			$Image[0] = false;
			$Image[1] = "";
		}
		if (is_uploaded_file($_FILES['Icon_'.$n]['tmp_name'])){
			$Fname = $_FILES['Icon_'.$n]['name'];
			$Iname = format_file_name($Fname,"t");
			$ISize = $_FILES['Icon_'.$n]['size'];
			$ITemp = $_FILES['Icon_'.$n]['tmp_name'];
			$IType = $_FILES['Icon_'.$n]['type'];
			
			$Icon = loadimage($MaxSize, $Fname, $Iname, $ISize, $ITemp, $IType, $Prod_Folder, $Prod_TWidth, $Prod_THeight, $Prod_TCrop, $Prod_TResize);
		} else {
			$Icon = array();
			$Icon[0] = false;
			$Icon[1] = "";
		}
		if($Image[0] || $Icon[0]){
			$Image = $Image[1];
			$Icon = $Icon[1];
			if($_POST['Remove_Image_'.$n] == "true"){
				$Image = "";
			}
			if($_POST['Remove_Icon_'.$n] == "true"){
				$Icon = "";
			}
			if($cont == "add"){
				$add = "INSERT INTO `prod_product_images` (`prod_id`,`prod_image_alt`,`prod_image_name`,`prod_image_thumb`,`prod_image_image`) VALUES ('$ProdId','$IAlt','$IName','$Icon','$Image');";
				$addinfo = mysql_query($add, $cp_connection) or die(mysql_error());
			} else {
				$upd = "UPDATE `prod_product_images` SET `prod_id` = '$ProdId',`prod_image_alt` = '$IAlt',`prod_image_name` = '$IName',`prod_image_thumb` = '$Icon',`prod_image_image` = '$Image' WHERE `prod_image_id` = '$IId'";
				$updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());
			}
		} else {
			if($Image[0]){
				$message += $Image[1]+"\n";
			}
			if($Icon[0]){
				$message += $Icon[1];
			}
			$Image = $Image[1];
			$Icon = $Icon[1];
		}
	}
	$cont = "query";
} else {
	if($cont != "add"){
		$query_get_info = "SELECT * FROM `prod_product_images` WHERE `prod_image_id` = '$RId'";
		$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
		$row_get_info = mysql_fetch_assoc($get_info);
		$totalRows_get_info = mysql_num_rows($get_info);
						
		mysql_free_result($get_info);
	}
}

?>