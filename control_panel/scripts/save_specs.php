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
$SId = $path[count($path)-1];
$SPId = $path[count($path)-2];
if($cont == "add")$SPId = $path[count($path)-1];
$SName = (isset($_POST['Name'])) ? clean_variable($_POST['Name'],true) : '';
$SDesc = (isset($_POST['Description'])) ? clean_variable($_POST['Description']) : '';
$Image = "";
$Imagev = (isset($_POST['Image_val'])) ? clean_variable($_POST['Image_val'],true) : '';
if(isset($_POST['Controller']) && $_POST['Controller'] == "Save" && ($cont == 'add' || $cont == 'edit')){
	if (is_uploaded_file($_FILES['Image']['tmp_name'])){
		$Fname = $_FILES['Image']['name'];
		$Iname = format_file_name($Fname,"i");
		$ISize = $_FILES['Image']['size'];
		$ITemp = $_FILES['Image']['tmp_name'];
		$IType = $_FILES['Image']['type'];
		
		$Image = loadimage($MaxSize, $Fname, $Iname, $ISize, $ITemp, $IType, $Spec_Folder, $Spec_IWidth, $Spec_IHeight, $Spec_ICrop, $Spec_IResize);
	} else {
		$Image = array();
		if($Spec_IReq && $Imagev != ""){
			$Image[0] = false;
		} else {
			$Image[0] = true;
		}
		$Image[1] = $Imagev;
	}
	if($Image[0]){
		$Image = $Image[1];
		if($_POST['Remove_Image'] == "true"){
			$Image = "";
		}
		$text = clean_variable($SDesc,'Store');
		if($cont == "add"){
			$add = "INSERT INTO `prod_specs` (`spec_part_id`,`spec_name`,`spec_desc`,`spec_image`) VALUES ('$SPId','$SName','$text','$Image');";
			$addinfo = mysql_query($add, $cp_connection) or die(mysql_error());
		} else {
			$upd = "UPDATE `prod_specs` SET `spec_part_id` = '$SPId',`spec_name` = '$SName',`spec_desc` = '$text',`spec_image` = '$Image' WHERE `spec_id` = '$SId'";
			$updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());
		}
		$cont = "view";
	} else {
		$message = $Image[1];
		$Image = $Image[1];
	}
} else {
	if($cont != "add"){
		$query_get_info = "SELECT * FROM `prod_specs` WHERE `spec_id` = '$SId'";
		$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
		$row_get_info = mysql_fetch_assoc($get_info);
		$totalRows_get_info = mysql_num_rows($get_info);
		
		$SPId = $row_get_info['spec_part_id'];
		$SName = $row_get_info['spec_name'];
		$SDesc = $row_get_info['spec_desc'];
		$Image = $row_get_info['spec_image'];
		$Imagev = $Image;
		
		mysql_free_result($get_info);
	}
}
?>