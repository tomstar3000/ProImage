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
$FId = $path[count($path)-1];
$FPId = $path[count($path)-2];
if($cont == "add")$FPId = $path[count($path)-1];
$FName = (isset($_POST['Name'])) ? clean_variable($_POST['Name'],true) : '';
$FDesc = (isset($_POST['Description'])) ? clean_variable($_POST['Description']) : '';
$Image = "";
$Imagev = (isset($_POST['Image_val'])) ? clean_variable($_POST['Image_val'],true) : '';
if(isset($_POST['Controller']) && $_POST['Controller'] == "Save" && ($cont == 'add' || $cont == 'edit')){
	if (is_uploaded_file($_FILES['Image']['tmp_name'])){
		$Fname = $_FILES['Image']['name'];
		$Iname = format_file_name($Fname,"i");
		$ISize = $_FILES['Image']['size'];
		$ITemp = $_FILES['Image']['tmp_name'];
		$IType = $_FILES['Image']['type'];
		
		$Image = loadimage($MaxSize, $Fname, $Iname, $ISize, $ITemp, $IType, $Feat_Folder, $Feat_IWidth, $Feat_IHeight, $Feat_ICrop, $Feat_IResize);
	} else {
		$Image = array();
		if($Feat_IReq && $Imagev != ""){
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
		$text = clean_variable($FDesc,'Store');
		if($cont == "add"){
			$add = "INSERT INTO `prod_features` (`feat_part_id`,`feat_name`,`feat_desc`,`fear_image`) VALUES ('$FPId','$FName','$text','$Image');";
			$addinfo = mysql_query($add, $cp_connection) or die(mysql_error());
		} else {
			$upd = "UPDATE `prod_features` SET `feat_part_id` = '$FPId',`feat_name` = '$FName',`feat_desc` = '$text',`fear_image` = '$Image' WHERE `feat_id` = '$FId'";
			$updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());
		}
		$cont = "view";
	} else {
		$message = $Image[1];
		$Image = $Image[1];
	}
} else {
	if($cont != "add"){			
		$query_get_info = "SELECT * FROM `prod_features` WHERE `feat_id` = '$FId'";
		$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
		$row_get_info = mysql_fetch_assoc($get_info);
		$totalRows_get_info = mysql_num_rows($get_info);
		
		$FPId = $row_get_info['feat_part_id'];
		$FName = $row_get_info['feat_name'];
		$FDesc = $row_get_info['feat_desc'];
		$Image = $row_get_info['fear_image'];
		$Imagev = $Image;
		
		mysql_free_result($get_info);
	}
}

?>