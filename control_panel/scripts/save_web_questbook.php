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
$FName = (isset($_POST['First_Name'])) ? clean_variable($_POST['First_Name'],true) : 'n';
$LName = (isset($_POST['Last_Name'])) ? clean_variable($_POST['Last_Name'],true) : '';
$Date = (isset($_POST['Date'])) ? clean_variable($_POST['Date'],true) : '';
$Email = (isset($_POST['Email'])) ? clean_variable($_POST['Email'],true) : '';
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
		
		$Image = loadimage($MaxSize, $Fname, $Iname, $ISize, $ITemp, $IType, $Guest_Folder, $Guest_IWidth, $Guest_IHeight, $Guest_ICrop, $Guest_IResize);
	} else {
		$Image = array();
		if($Guest_IReq){
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
				$add = "INSERT INTO `web_guestbook` (`guestbook_fname`,`guestbook_lname`,`guestbook_email`,`guestbook_date`,`guestbook_text`,`guestbook_image`,`guestbook_alt`) VALUES ('$FName','$LName','$Email','$Date','$Desc','$Imagev','$Alt');";
				$addinfo = mysql_query($add, $cp_connection) or die(mysql_error());
	
				$query_get_last = "SELECT `guestbook_id` FROM `web_guestbook` ORDER BY `guestbook_id` DESC LIMIT 0,1";
				$get_last = mysql_query($query_get_last, $cp_connection) or die(mysql_error());
				$row_get_last = mysql_fetch_assoc($get_last);
				
				$NId = $row_get_last['guestbook_id'];
				array_push($path,$NId);
			}
		} else {
			$upd = "UPDATE `web_guestbook` SET `guestbook_fname` = '$FName',`guestbook_lname` = '$LName',`guestbook_email` = '$Email',`guestbook_date` = '$Date',`guestbook_text` = '$Desc',`guestbook_image` = '$Imagev',`guestbook_alt` = '$Alt' WHERE `guestbook_id` = '$NId'";
			$updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());
		}
		$cont = "view";
	}
} else {
	if($cont != "add"){		
		$query_info = "SELECT * FROM `web_guestbook` WHERE `guestbook_id` = '$NId'";
		$info = mysql_query($query_info, $cp_connection) or die(mysql_error());
		$row_info = mysql_fetch_assoc($info);
		$totalRows_info = mysql_num_rows($info);
		
		$FName = $row_info['guestbook_fname'];
		$LName = $row_info['guestbook_lname'];
		$Date = $row_info['guestbook_date'];
		$Email = $row_info['guestbook_email'];
		$Image = "";
		$Imagev = $row_info['guestbook_image'];
		$Alt = $row_info['guestbook_alt'];
		$Desc = $row_info['guestbook_text'];
		$IP = $row_info['guestbook_ip'];
	}
}
?>