<? if(!isset($r_path)){
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
$CId = $path[count($path)-1];
$CPId = $path[count($path)-2];
if($cont == "add")$CPId = $path[count($path)-1];
$CName = (isset($_POST['Name'])) ? clean_variable($_POST['Name'],true) : '';
$CDesc = (isset($_POST['Description'])) ? clean_variable($_POST['Description']) : '';
$TagLine = (isset($_POST['Tag_Line'])) ? clean_variable($_POST['Tag_Line'],true) : '';
$Image = "";
$Imagev = (isset($_POST['Image_val'])) ? clean_variable($_POST['Image_val'],true) : '';
$Show = (isset($_POST['Show'])) ? clean_variable($_POST['Show'],true) : 'y';
if(isset($_POST['Controller']) && $_POST['Controller'] == "Save" && ($cont == 'add' || $cont == 'edit')){
	if (is_uploaded_file($_FILES['Image']['tmp_name'])){
		$Fname = $_FILES['Image']['name'];
		$Iname = format_file_name($Fname,"i");
		$ISize = $_FILES['Image']['size'];
		$ITemp = $_FILES['Image']['tmp_name'];
		$IType = $_FILES['Image']['type'];
		
		$Image = loadimage($MaxSize, $Fname, $Iname, $ISize, $ITemp, $IType, $Cat_Folder, $Cat_IWidth, $Cat_IHeight, $Cat_ICrop, $Cat_IResize);
	} else {
		$Image = array();
		if($Cat_IReq && $Imagev != ""){
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
		$text = clean_variable($CDesc,'Store');
		if($cont == "add"){
			if($_POST['Time'] != $_SESSION['Time']){
				$_SESSION['Time'] = $_POST['Time'];	
				$add = "INSERT INTO `prod_categories` (`cat_parent_id`,`cat_name`,`cat_desc`,`cat_image`,`cat_tag`,`cat_show`) VALUES ('$CPId','$CName','$text','$Image','$TagLine','$Show');";
				$addinfo = mysql_query($add, $cp_connection) or die(mysql_error());
				
				$query_get_last = "SELECT `cat_id` FROM `prod_categories` ORDER BY `cat_id` DESC LIMIT 0,1";
				$get_last = mysql_query($query_get_last, $cp_connection) or die(mysql_error());
				$row_get_last = mysql_fetch_assoc($get_last);
				
				$CId = $row_get_last['cat_id'];
				array_push($path,$CId);
			}
		} else {
			$upd = "UPDATE `prod_categories` SET `cat_parent_id` = '$CPId',`cat_name` = '$CName',`cat_desc` = '$text',`cat_image` = '$Image',`cat_tag` = '$TagLine',`cat_show` = '$Show' WHERE `cat_id` = '$CId'";
			$updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());
		}
		$cont = "view";
	} else {
		$message = $Image[1];
		$Image = $Image[1];
	}
} else {
	if($cont != "add"){		
		$query_get_info = "SELECT * FROM `prod_categories` WHERE `cat_id` = '$CId'";
		$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
		$row_get_info = mysql_fetch_assoc($get_info);
		$totalRows_get_info = mysql_num_rows($get_info);
		
		$CPId = $row_get_info['cat_parent_id'];
		$CName = $row_get_info['cat_name'];
		$CDesc = $row_get_info['cat_desc'];
		$TagLine = $row_get_info['cat_tag'];
		$Image = $row_get_info['cat_image'];
		$Imagev = $Image;
		$Show = $row_get_info['cat_show'];
		
		mysql_free_result($get_info);
	}
}

?>