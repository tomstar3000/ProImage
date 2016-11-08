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
$AId = $path[count($path)-1];
$APId = $path[count($path)-2];
if($cont == "add")$APId = $path[count($path)-1];
$AName = (isset($_POST['Name'])) ? clean_variable($_POST['Name'],true) : '';
$ADesc = (isset($_POST['Description'])) ? clean_variable($_POST['Description']) : '';
$Image = "";
$Imagev = (isset($_POST['Image_val'])) ? clean_variable($_POST['Image_val'],true) : '';
if(isset($_POST['Controller']) && $_POST['Controller'] == "Save" && ($cont == 'add' || $cont == 'edit')){
	if (is_uploaded_file($_FILES['Image']['tmp_name'])){
		$Fname = $_FILES['Image']['name'];
		$Iname = format_file_name($Fname,"i");
		$ISize = $_FILES['Image']['size'];
		$ITemp = $_FILES['Image']['tmp_name'];
		$IType = $_FILES['Image']['type'];
		if($_POST['Swatch_Size'] == "2"){
			$Attrib_IWidth = $Attrib_IWidth_2;
			$Attrib_IHeight = $Attrib_IHeight_2;
		}
		
		$Image = loadimage($MaxSize, $Fname, $Iname, $ISize, $ITemp, $IType, $Attrib_Folder, $Attrib_IWidth, $Attrib_IHeight, $Attrib_ICrop, $Attrib_IResize);
	} else {
		$Image = array();
		if($Attrib_IReq && $Imagev != ""){
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
		$text = clean_variable($ADesc,'Store');
		if($cont == "add"){
			$add = "INSERT INTO `prod_attributes` (`att_part_id`,`att_name`,`att_desc`,`att_image`) VALUES ('$APId','$AName','$text','$Image');";
			$addinfo = mysql_query($add, $cp_connection) or die(mysql_error());
		} else {
			$upd = "UPDATE `prod_attributes` SET `att_part_id` = '$APId',`att_name` = '$AName',`att_desc` = '$text',`att_image` = '$Image' WHERE `att_id` = '$AId'";
			$updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());
		}
		$cont = "view";
	} else {
		$message = $Image[1];
		$Image = $Image[1];
	}
} else {
	if($cont != "add"){
		$query_get_info = "SELECT * FROM `prod_attributes` WHERE `att_id` = '$AId'";
		$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
		$row_get_info = mysql_fetch_assoc($get_info);
		
		$APId = $row_get_info['att_part_id'];
		$AName = $row_get_info['att_name'];
		$ADesc = $row_get_info['att_desc'];
		$Image = $row_get_info['att_image'];
		$Imagev = $Image;
		
		mysql_free_result($get_info);
	}
}
?>