<?php if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++){
		$r_path .= "../";
	}
}
include $r_path.'security.php';
require_once $r_path.'scripts/fnct_image_resize.php';
require_once $r_path.'scripts/fnct_format_file_name.php';
$SId = $path[2];
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
		
		$Image = loadimage($MaxSize, $Fname, $Iname, $ISize, $ITemp, $IType, $Select_Folder, $Select_IWidth, $Select_IHeight, $Select_ICrop, $Select_IResize);
	} else {
		$Image = array();
		if($Select_IReq && $Imagev != ""){
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
			$add = "INSERT INTO `prod_selections` (`sel_name`,`sel_desc`,`sel_image`) VALUES ('$SName','$text','$Image');";
			$addinfo = mysql_query($add, $cp_connection) or die(mysql_error());
		} else {
			$upd = "UPDATE `prod_selections` SET `sel_name` = '$SName',`sel_desc` = '$text',`sel_image` = '$Image' WHERE `sel_id` = '$SId'";
			$updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());
		}
		$cont = "view";
	} else {
		$message = $Image[1];
		$Image = $Image[1];
	}
} else {
	if($cont != "add"){		
		$query_get_info = "SELECT * FROM `prod_selections` WHERE `sel_id` = '$SId'";
		$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
		$row_get_info = mysql_fetch_assoc($get_info);
		
		$SName = $row_get_info['sel_name'];
		$SDesc = $row_get_info['sel_desc'];
		$Image = $row_get_info['sel_image'];
		$Imagev = $Image;
		
		mysql_free_result($get_info);
	}
}
?>