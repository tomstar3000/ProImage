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
$AId = $path[4];
$ProdId = $path[2];
$AName = (isset($_POST['Name'])) ? clean_variable($_POST['Name'],true) : '';
$AWhole = (isset($_POST['Whole_Price'])) ? clean_variable($_POST['Whole_Price'],true) : '';
$APrice = (isset($_POST['Price'])) ? clean_variable($_POST['Price'],true) : '';
$ASale = (isset($_POST['Sale_Price'])) ? clean_variable($_POST['Sale_Price'],true) : '';
$ASaleExp = (isset($_POST['Sale_Experation'])) ? clean_variable($_POST['Sale_Experation'],true) : '	';
if(isset($_POST['Attribute'])){
	$PAttrs = $_POST['Attribute'];
	$Count = count($PAttrs);
	$PAttr = $PAttrs[$Count-1];
} else {
	$PAttr = '0';
}
$PSel_Attr = (isset($_POST['Sel_Attr'])) ? $_POST['Sel_Attr'] : '0';
$Image = "";
$Imagev = (isset($_POST['Image_val'])) ? clean_variable($_POST['Image_val'],true) : '';
$Thumb = "";
$Thumbv = (isset($_POST['Thumb_val'])) ? clean_variable($_POST['Thumb_val'],true) : '';
$PQty = (isset($_POST['Quantity'])) ? clean_variable($_POST['Quantity'],true) : '';
if(isset($_POST['Controller']) && $_POST['Controller'] == "Save" && ($cont == 'add' || $cont == 'edit')){
	if (is_uploaded_file($_FILES['Image']['tmp_name'])){
		$Fname = $_FILES['Image']['name'];
		$Iname = format_file_name($Fname,"t");
		$ISize = $_FILES['Image']['size'];
		$ITemp = $_FILES['Image']['tmp_name'];
		$IType = $_FILES['Image']['type'];
		
		$Image = loadimage($MaxSize, $Fname, $Iname, $ISize, $ITemp, $IType, $Prod_Attrib_Folder, $Prod_Attrib_IWidth, $Prod_Attrib_IHeight, $Prod_Attrib_ICrop, $Prod_Attrib_IResize);
	} else {
		$Image = array();
		if($Attrib_IReq && $Imagev != ""){
			$Image[0] = false;
		} else {
			$Image[0] = true;
		}
		$Image[1] = $Imagev;
	}
	if (is_uploaded_file($_FILES['Thumb']['tmp_name'])){
		$Fname = $_FILES['Thumb']['name'];
		$Iname = format_file_name($Fname,"i");
		$Isize = $_FILES['Thumb']['size'];
		$Itemp = $_FILES['Thumb']['tmp_name'];
		$Itype = $_FILES['Thumb']['type'];
		
		$Thumb = loadimage($MaxSize, $Fname, $Iname, $ISize, $ITemp, $IType, $Prod_Attrib_Folder, $Prod_Attrib_TWidth, $Prod_Attrib_THeight, $Prod_Attrib_TCrop, $Prod_Attrib_TResize);
	} else {
		$Thumb = array();
		if($Attrib_IReq){
			$Thumb[0] = false;
		} else {
			$Thumb[0] = true;
		}
		$Thumb[1] = $Thumbv;
	}
	if($Image[0] && $Thumb[0]){
		$Image = $Image[1];
		$Thumb = $Thumb[1];
		if($_POST['Remove_Image'] == "true"){
			$Image = "";
		}
		if($_POST['Remove_Thumb'] == "true"){
			$Thumb = "";
		}
		if($cont == "add"){
			$add = "INSERT INTO `prod_link_prod_att` (`prod_id`,`att_id`,`link_prod_att_name`,`att_price`,`att_whole`,`att_sale`,`att_sale_exp`,`link_prod_att_image`,`link_prod_att_thumb`,`link_prod_att_qty`) VALUES ('$ProdId','$PAttr','$AName','$APrice','$AWhole','$ASale','$ASaleExp','$Image','$Thumb','$PQty');";
			$addinfo = mysql_query($add, $cp_connection) or die(mysql_error());
		} else {
			$upd = "UPDATE `prod_link_prod_att` SET `prod_id` = '$ProdId',`att_id` = '$PAttr',`link_prod_att_name` = '$AName',`att_price` = '$APrice',`att_whole` = '$AWhole',`att_sale` = '$ASale',`att_sale_exp` = '$ASaleExp',`link_prod_att_image` = '$Image',`link_prod_att_thumb` = '$Thumb',`link_prod_att_qty` = '$PQty' WHERE `link_prod_att_id` = '$AId'";
			$updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());
		}
		$cont = "view";
	} else {
		$message = $Image[1];
		$Image = $Image[1];
	}
} else {
	if($cont != "add"){
		$query_get_info = "SELECT * FROM `prod_link_prod_att` WHERE `link_prod_att_id` = '$AId'";
		$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
		$row_get_info = mysql_fetch_assoc($get_info);
		$totalRows_get_info = mysql_num_rows($get_info);
		
		$AName = $row_get_info['link_prod_att_name'];
		$APrice = $row_get_info['att_price'];
		$AWhole = $row_get_info['att_whole'];
		$ASale = $row_get_info['att_sale'];
		$ASaleExp = $row_get_info['att_sale_exp'];
		$PAttr = $row_get_info['att_id'];
		$Image = $row_get_info['link_prod_att_image'];
		$Imagev = $Image;
		$Thumb = $row_get_info['link_prod_att_thumb'];
		$Thumbv = $Thumb;
		$PQty = $row_get_info['link_prod_att_qty'];
		
		mysql_free_result($get_info);
	}
}
?>