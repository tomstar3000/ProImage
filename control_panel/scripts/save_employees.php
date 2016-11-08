<? if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++)$r_path .= "../";
}
include $r_path.'security.php';
require_once $r_path.'scripts/fnct_clean_entry.php';
require_once $r_path.'scripts/fnct_image_resize.php';
require_once $r_path.'scripts/fnct_date_format_for_db.php';
require_once $r_path.'scripts/fnct_format_file_name.php';
$EId = $path[2];
$FName = (isset($_POST['First_Name'])) ? clean_variable($_POST['First_Name'],true) : '';
$MInt = (isset($_POST['Middle_Initial'])) ? clean_variable($_POST['Middle_Initial'],true) : '';
$LName = (isset($_POST['Last_Name'])) ? clean_variable($_POST['Last_Name'],true) : '';
$Title = (isset($_POST['Title'])) ? clean_variable($_POST['Title'],true) : '';
$WorkType = (isset($_POST['Project_Worktype'])) ? clean_variable($_POST['Project_Worktype'],true) : '';
$EDesc = (isset($_POST['Employee_Description'])) ? clean_variable($_POST['Employee_Description']) : '';
$Add = (isset($_POST['Address'])) ? clean_variable($_POST['Address'],true) : '';
$Add2 = (isset($_POST['Address_2'])) ? clean_variable($_POST['Address_2'],true) : '';
$SApt = (isset($_POST['Suite_Apt'])) ? clean_variable($_POST['Suite_Apt'],true) : '';
$City = (isset($_POST['City'])) ? clean_variable($_POST['City'],true) : '';
$State = (isset($_POST['State'])) ? clean_variable($_POST['State'],true) : '';
$Zip = (isset($_POST['Zip'])) ? clean_variable($_POST['Zip'],true) : '';
$Country = (isset($_POST['Country'])) ? clean_variable($_POST['Country'],true) : '';
$Phone = (isset($_POST['Phone_Number'])) ? clean_variable($_POST['Phone_Number'],true) : '';
$P1 = (isset($_POST['P1'])) ? clean_variable($_POST['P1'],true) : '';
$P2 = (isset($_POST['P2'])) ? clean_variable($_POST['P2'],true) : '';
$P3 = (isset($_POST['P3'])) ? clean_variable($_POST['P3'],true) : '';
$Cell = (isset($_POST['Cell_Number'])) ? clean_variable($_POST['Cell_Number'],true) : '';
$C1 = (isset($_POST['C1'])) ? clean_variable($_POST['C1'],true) : '';
$C2 = (isset($_POST['C2'])) ? clean_variable($_POST['C2'],true) : '';
$C3 = (isset($_POST['C3'])) ? clean_variable($_POST['C3'],true) : '';
$Fax = (isset($_POST['Fax_Number'])) ? clean_variable($_POST['Fax_Number'],true) : '';
$F1 = (isset($_POST['F1'])) ? clean_variable($_POST['F1'],true) : '';
$F2 = (isset($_POST['F2'])) ? clean_variable($_POST['F2'],true) : '';
$F3 = (isset($_POST['F3'])) ? clean_variable($_POST['F3'],true) : '';
$Email = (isset($_POST['Email'])) ? clean_variable($_POST['Email'],true) : '';
$Thumbv = (isset($_POST['Thumbnail_val'])) ? clean_variable($_POST['Thumbnail_val'],true) : '';
$Imagev = (isset($_POST['Image_val'])) ? clean_variable($_POST['Image_val'],true) : '';
if(isset($_POST['Controller']) && $_POST['Controller'] == "Save" && ($cont == 'add' || $cont == 'edit')){	
	if (is_uploaded_file($_FILES['Image']['tmp_name'])){
		$Fname = $_FILES['Image']['name'];
		$Iname = format_file_name($Fname,"i");
		$ISize = $_FILES['Image']['size'];
		$ITemp = $_FILES['Image']['tmp_name'];
		$IType = $_FILES['Image']['type'];
		
		ini_set("memory_limit","100M");
		$Image = loadimage($MaxSize, $Fname, $Iname, $ISize, $ITemp, $IType, $Emp_Folder, $Emp_IWidth, $Emp_IHeight, $Emp_ICrop, $Emp_IResize);		
		ini_restore ("memory_limit");
	} else {
		$Image = array();
		if($Emp_IReq){
			$Image[0] = false;
			$Image[1] = "Image is Required";
		} else {
			$Image[0] = true;
			$Image[1] = $Imagev;
		}
	}
	if (is_uploaded_file($_FILES['Thumbnail']['tmp_name'])){
		$Fname = $_FILES['Thumbnail']['name'];
		$Iname = format_file_name($Fname,"t");
		$ISize = $_FILES['Thumbnail']['size'];
		$ITemp = $_FILES['Thumbnail']['tmp_name'];
		$IType = $_FILES['Thumbnail']['type'];
		
		ini_set("memory_limit","100M");
		$Thumb = loadimage($MaxSize, $Fname, $Iname, $ISize, $ITemp, $IType, $Emp_Folder, $Emp_TWidth, $Emp_THeight, $Emp_TCrop, $Emp_TResize);
		ini_restore ("memory_limit");
	} else {
		$Thumb = array();
		if($Emp_TReq){
			$Thumb[0] = false;
			$Thumb[1] = "Thumbnail Image is Required";
		} else {
			$Thumb[0] = true;
			$Thumb[1] = $Thumbv;
		}
	}
	if($Image[0] && $Thumb[0]){
		$Imagev = $Image[1];
		$Thumbv = $Thumb[1];
		if($_POST['Remove_Image'] == "true")$Imagev = "";
		if($_POST['Remove_Thumbnail'] == "true")$Thumbv = "";
		$text = clean_variable($EDesc,'Store');
		if($cont == "add"){
			if($_POST['Time'] != $_SESSION['Time']){
				$_SESSION['Time'] = $_POST['Time'];			
				$add = "INSERT INTO `emp_employees` (`emp_fname`,`emp_mint`,`emp_lname`,`emp_bio`,`emp_title`,`emp_type_id`,`emp_add`,`emp_add_2`,`emp_suite_apt`,`emp_city`,`emp_state`,`emp_zip`,`emp_country`, `emp_phone`,`emp_cell`,`emp_fax`,`emp_email`,`emp_image`,`emp_thumb`) VALUES ('$FName','$MInt','$LName','$text','$Title','$WorkType','$Add','$Add2','$SApt','$City','$State','$Zip','$Country','$Phone','$Cell','$Fax','$Email','$Imagev','$Thumbv');";
				$addinfo = mysql_query($add, $cp_connection) or die(mysql_error());
																																										
				$query_get_last = "SELECT `emp_id` FROM `emp_employees` ORDER BY `emp_id` DESC LIMIT 0,1";
				$get_last = mysql_query($query_get_last, $cp_connection) or die(mysql_error());
				$row_get_last = mysql_fetch_assoc($get_last);
					
				$EId = $row_get_last['emp_id'];
				array_push($path,$EId);
			}
		} else if($cont == "edit"){
			$upd = "UPDATE `emp_employees` SET `emp_fname` = '$FName',`emp_mint` = '$MInt',`emp_lname` = '$LName',`emp_title` = '$Title',`emp_type_id`='$WorkType',`emp_bio` = '$text',`emp_add` = '$Add',`emp_add_2` = '$Add2',`emp_suite_apt` = '$SApt',`emp_city` = '$City',`emp_state` = '$State',`emp_zip` = '$Zip',`emp_country` = '$Country',`emp_phone` = '$Phone',`emp_cell` = '$Cell',`emp_fax` = '$Fax',`emp_email` = '$Email',`emp_image` = '$Imagev',`emp_thumb` = '$Thumbv' WHERE `emp_id` = '$EId'";
			$updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());
		}
		$cont = "view";
	} else {
		$message = $Image[1]."\n".$Thumb[1];
		$Image = $Image[1];
		$Thumb = $Thumb[1];
	}
} else {
	if($cont != "add"){	
		$query_get_info = "SELECT * FROM `emp_employees` WHERE `emp_id` = '$EId'";
		$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
		$row_get_info = mysql_fetch_assoc($get_info);
		
		$FName = $row_get_info['emp_fname'];
		$MInt = $row_get_info['emp_mint'];
		$LName = $row_get_info['emp_lname'];
		$Title = $row_get_info['emp_title'];
		$WorkType = $row_get_info['emp_type_id'];
		$EDesc = $row_get_info['emp_bio'];
		$Add = $row_get_info['emp_add'];
		$Add2 = $row_get_info['emp_add_2'];
		$SApt = $row_get_info['emp_suite_apt'];
		$City = $row_get_info['emp_city'];
		$State = $row_get_info['emp_state'];
		$Zip = $row_get_info['emp_zip'];
		$Country = $row_get_info['emp_country'];
		$Phone = $row_get_info['emp_phone'];
		$P1 = substr($row_get_info['emp_phone'],0,3);
		$P2 = substr($row_get_info['emp_phone'],3,3);
		$P3 = substr($row_get_info['emp_phone'],6,4);
		$Cell = $row_get_info['emp_cell'];
		$C1 = substr($row_get_info['emp_cell'],0,3);
		$C2 = substr($row_get_info['emp_cell'],3,3);
		$C3 = substr($row_get_info['emp_cell'],6,4);
		$Fax = $row_get_info['emp_fax'];
		$F1 = substr($row_get_info['emp_fax'],0,3);
		$F2 = substr($row_get_info['emp_fax'],3,3);
		$F3 = substr($row_get_info['emp_fax'],6,4);
		$Email = $row_get_info['emp_email'];
		$Thumb = $row_get_info['emp_thumb'];
		$Image = $row_get_info['emp_image'];
		$Thumbv = $Thumb;
		$Imagev = $Image;
		
		mysql_free_result($get_info);
	}
}
?>
