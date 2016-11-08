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
require_once $r_path.'scripts/fnct_date_format_for_db.php';
require_once $r_path.'scripts/fnct_format_file_name.php';
if($path[3] == "Proj"){
	$PId = $path[4];
} else {
	$PId = $path[2];
}
$PName = (isset($_POST['Name'])) ? clean_variable($_POST['Name'],true) : '';
$PNumber = (isset($_POST['Project_Number'])) ? clean_variable($_POST['Project_Number'],true) : '';
$PCust = (isset($_POST['Customer'])) ? $_POST['Customer'] : ((isset($_GET['Cust_Id'])) ? $_GET['Cust_Id'] : $path[2]);
if(isset($_POST['Category'])){
	$PCats = $_POST['Category'];
	$Count = count($PCats);
	$PCat = $PCats[$Count-1];
} else {
	$PCat = '0';
}
$PSel_Cal = (isset($_POST['Sel_Cat'])) ? $_POST['Sel_Cat'] : '0';
$PCont = (isset($_POST['Contact'])) ? clean_variable($_POST['Contact'],true) : '';
$PRate = (isset($_POST['Bill_Rate'])) ? clean_variable($_POST['Bill_Rate'],true) : '';
$PPrice = (isset($_POST['Price'])) ? clean_variable($_POST['Price'],true) : '';
$PStart = (isset($_POST['Project_Start'])) ? clean_variable($_POST['Project_Start'],true) : '';
$PEnd = (isset($_POST['Project_End'])) ? clean_variable($_POST['Project_End'],true) : '';
$PDesc = (isset($_POST['Project_Description'])) ? clean_variable($_POST['Project_Description']) : '';
$PWeb = (isset($_POST['Website'])) ? clean_variable($_POST['Website'],true) : '';
$Image = "";
$Imagev = (isset($_POST['Image_val'])) ? clean_variable($_POST['Image_val'],true) : '';
$Thumb = "";
$Thumbv = (isset($_POST['Thumb_val'])) ? clean_variable($_POST['Thumb_val'],true) : '';
$PPort = (isset($_POST['Portfolio'])) ? clean_variable($_POST['Portfolio'],true) : 'n';
$PComp = (isset($_POST['Complete'])) ? clean_variable($_POST['Complete'],true) : 'n';
if(isset($_POST['Controller']) && $_POST['Controller'] == "Save" && ($cont == 'add' || $cont == 'edit')){
	if (is_uploaded_file($_FILES['Image']['tmp_name'])){
		$Fname = $_FILES['Image']['name'];
		$Iname = format_file_name($Fname,"i");
		$ISize = $_FILES['Image']['size'];
		$ITemp = $_FILES['Image']['tmp_name'];
		$IType = $_FILES['Image']['type'];
		
		$Image = loadimage($MaxSize, $Fname, $Iname, $ISize, $ITemp, $IType, $Proj_Folder, $Proj_IWidth, $Proj_IHeight, $Proj_ICrop, $Proj_IResize);
	} else {
		$Image = array();
		if($Proj_IReq && $Imagev != ""){
			$Image[0] = false;
		} else {
			$Image[0] = true;
		}
		$Image[1] = $_FILES['Image']['name'];
	}
	if (is_uploaded_file($_FILES['Thumb']['tmp_name'])){
		$Fname = $_FILES['Thumb']['name'];
		$Iname = format_file_name($Fname,"i");
		$ISize = $_FILES['Thumb']['size'];
		$ITemp = $_FILES['Thumb']['tmp_name'];
		$IType = $_FILES['Thumb']['type'];
		
		$Thumb = loadimage($MaxSize, $Fname, $Iname, $ISize, $ITemp, $IType, $Proj_Folder, $Proj_TWidth, $Proj_THeight, $Proj_TCrop, $Proj_TResize);
	} else {
		$Thumb = array();
		if($Proj_TReq && $Thumbv != ""){
			$Thumb[0] = false;
		} else {
			$Thumb[0] = true;
		}
		$Thumb[1] = $_FILES['Thumb']['tmp_name'];
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
		$text = clean_variable($PDesc,'Store');
		if($cont == "add"){
			$add = "INSERT INTO `proj_projects` (`proj_name`,`proj_number`,`cust_id`,`cat_id`,`cust_cont_id`,`proj_desc`,`proj_website`,`proj_start`,`proj_end`,`proj_image`,`proj_thumb`,`proj_port`,`proj_comp`,`proj_bill`,`proj_price`) VALUES ('$PName','$PNumber','$PCust','$PCat','$PCont','$text','$PWeb','$PStart','$PEnd','$Image','$Thumb','$PPort','$PComp','$PRate','$PPrice');";
			$addinfo = mysql_query($add, $cp_connection) or die(mysql_error());
		} else {
			$upd = "UPDATE `proj_projects` SET `proj_name` = '$PName',`proj_number` = '$PNumber',`cust_id` = '$PCust',`cat_id` = '$PCat',`cust_cont_id` = '$PCont',`proj_desc` = '$text',`proj_website` = '$PWeb',`proj_start` = '$PStart',`proj_end` = '$PEnd',`proj_image` = '$Image',`proj_thumb` = '$Thumb',`proj_port` = '$PPort',`proj_comp` = '$PComp',`proj_bill` = '$PRate', `proj_price` = '$PPrice' WHERE `proj_id` = '$PId'";
			$updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());
		}
		$cont = "view";
	} else {
		$message = "Please Load a Thumbnail and an Image";
		$Image = $Image[1];
		$Thumb = $Thumb[1];
	}
} else {
	if($cont != "add"){		
		$query_get_info = "SELECT * FROM `proj_projects` WHERE `proj_id` = '$PId'";
		$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
		$row_get_info = mysql_fetch_assoc($get_info);
		$totalRows_get_info = mysql_num_rows($get_info);
		
		$PId = $row_get_info['proj_id'];
		$PName = $row_get_info['proj_name'];
		$PNumber = $row_get_info['proj_number'];
		$PCust = $row_get_info['cust_id'];
		$PCat = $row_get_info['cat_id'];
		$PCont = $row_get_info['cust_cont_id'];
		$PRate = $row_get_info['proj_bill'];
		$PPrice = $row_get_info['proj_price'];
		$PStart = $row_get_info['proj_start'];
		$PEnd = $row_get_info['proj_end'];
		$PWeb = $row_get_info['proj_website'];
		$PDesc = $row_get_info['proj_desc'];
		$Image = "";
		$Imagev = $row_get_info['proj_image'];
		$Thumb = "";
		$Thumbv = $row_get_info['proj_thumb'];
		$PPort = $row_get_info['proj_port'];
		$PComp = $row_get_info['proj_comp'];
		
		mysql_free_result($get_info);
	}
}
?>