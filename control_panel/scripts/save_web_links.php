<? if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++)$r_path .= "../";
}
include $r_path.'security.php';
require_once $r_path.'scripts/fnct_clean_entry.php';
require_once $r_path.'scripts/fnct_image_resize.php';
require_once $r_path.'scripts/fnct_format_file_name.php';
$NId = $path[count($path)-1];
$Name = (isset($_POST['Name'])) ? clean_variable($_POST['Name'],true) : '';
$URL = (isset($_POST['URL'])) ? clean_variable($_POST['URL'],true) : '';
$Target = (isset($_POST['Target'])) ? clean_variable($_POST['Target'],true) : '';
$Head1 = (isset($_POST['Header_1'])) ? clean_variable($_POST['Header_1'],true) : '';
$Head2 = (isset($_POST['Header_2'])) ? clean_variable($_POST['Header_2'],true) : '';
$Image = "";
$Imagev = (isset($_POST['Image_val'])) ? clean_variable($_POST['Image_val'],true) : '';
$Alt = (isset($_POST['Alt'])) ? clean_variable($_POST['Alt']) : '';
$Desc = (isset($_POST['Description'])) ? clean_variable($_POST['Description']) : '';
if(isset($_POST['Controller']) && $_POST['Controller'] == "Save" && ($cont == 'add' || $cont == 'edit')){
	$text = clean_variable($NDesc,"Store");
	if (is_uploaded_file($_FILES['Image']['tmp_name'])){
		$Fname = $_FILES['Image']['name'];
		$Iname = format_file_name($Fname,"i");
		$ISize = $_FILES['Image']['size'];
		$ITemp = $_FILES['Image']['tmp_name'];
		$IType = $_FILES['Image']['type'];
		
		$Image = loadimage($MaxSize, $Fname, $Iname, $ISize, $ITemp, $IType, $Link_Folder, $Link_IWidth, $Link_IHeight, $Link_ICrop, $Link_IResize);
	} else {
		$Image = array();
		if($Link_IReq){
			$Image[0] = false;
			$Image[1] = "Zoom Image is Required";
		} else {
			$Image[0] = true;
			$Image[1] = $Imagev;
		}
	}
	if($Image[0]){
		$Imagev = $Image[1];
		if($_POST['Remove_Image'] == "true") $Imagev = "";
		if($cont == "add"){
			if($_POST['Time'] != $_SESSION['Time']){
				$_SESSION['Time'] = $_POST['Time'];	
				$add = "INSERT INTO `web_links` (`web_link_name`,`web_link_url`,`web_link_target`,`web_link_header_1`,`web_link_header_2`,`web_link_text`,`web_link_image`,`web_link_alt`) VALUES ('$Name','$URL','$Target','$Head1','$Head2','$Desc','$Imagev','$Alt');";
				$addinfo = mysql_query($add, $cp_connection) or die(mysql_error());
	
				$query_get_last = "SELECT `web_link_id` FROM `web_links` ORDER BY `web_link_id` DESC LIMIT 0,1";
				$get_last = mysql_query($query_get_last, $cp_connection) or die(mysql_error());
				$row_get_last = mysql_fetch_assoc($get_last);
				
				$NId = $row_get_last['web_link_id'];
				array_push($path,$NId);
			}
		} else {
			$upd = "UPDATE `web_links` SET `web_link_name` = '$Name',`web_link_url` = '$URL',`web_link_target` = '$Target',`web_link_header_1` = '$Head1',`web_link_header_2` = '$Head2',`web_link_text` = '$Desc',`web_link_image` = '$Imagev',`web_link_alt` = '$Alt' WHERE `web_link_id` = '$NId'";
			$updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());
		}
		$cont = "view";
	}
} else {
	if($cont != "add"){		
		$query_info = "SELECT * FROM `web_links` WHERE `web_link_id` = '$NId'";
		$info = mysql_query($query_info, $cp_connection) or die(mysql_error());
		$row_info = mysql_fetch_assoc($info);
		$totalRows_info = mysql_num_rows($info);
		
		$Name = $row_info['web_link_name'];
		$URL = $row_info['web_link_url'];
		$Target = $row_info['web_link_target'];
		$Head1 = $row_info['web_link_header_1'];
		$Head2 = $row_info['web_link_header_2'];
		$Image = "";
		$Imagev = $row_info['web_link_image'];
		$Alt = $row_info['web_link_alt'];
		$Desc = $row_info['web_link_text'];
	}
}
?>