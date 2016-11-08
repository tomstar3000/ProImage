<? if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++)$r_path .= "../";
}
include $r_path.'security.php';
require_once $r_path.'scripts/fnct_clean_entry.php';
require_once $r_path.'scripts/fnct_image_resize.php';

if(isset($_GET['admin']) && $_GET['admin'] == "true"){
	$adminid = clean_variable($_GET['adminid'],true);
	$query_get_id = "SELECT `cust_id`, `cust_fname`, `cust_lname`, `cust_handle` FROM `cust_customers` WHERE `cust_id` = '$adminid'";
} else {
	$query_get_id = "SELECT `cust_id`, `cust_fname`, `cust_lname`, `cust_handle` FROM `cust_customers` WHERE `cust_session` = '$loginsession[0]'";
}
$get_id = mysql_query($query_get_id, $cp_connection) or die(mysql_error());
$row_get_id = mysql_fetch_assoc($get_id);

$cust_id = $row_get_id['cust_id'];
$Folder = "../photographers";
$Folder = realpath($Folder);
$Folder .= "/".$row_get_id['cust_handle'];

$MaxSize = 3200000; //Maximum Files Sizes that can be loaded
$IWidth = 998;
$IHeight = 387;

mysql_free_result($get_id);
$Desc = (isset($_POST['Page_Text'])) ? clean_variable($_POST['Page_Text']) : '';
$Image = "";
$Imagev = (isset($_POST['Image_val'])) ? clean_variable($_POST['Image_val'],true) : '';
if(isset($_POST['Controller']) && $_POST['Controller'] == "Save" && ($cont == 'add' || $cont == 'edit')){
	if($use_ftp == true){
		$conn_id = ftp_connect($ftp_server);
		$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
	}
	if (is_uploaded_file($_FILES['Image']['tmp_name'])){
		if(!is_dir($Folder)){
			if($use_ftp == true){
				ftp_mkdir($conn_id, $Folder);
			} else {
				mkdir($Folder);	
			}
		}
		$Fname = $_FILES['Image']['name'];
		$Iname = "Logo.jpg";
		$ISize = $_FILES['Image']['size'];
		$ITemp = $_FILES['Image']['tmp_name'];
		$IType = $_FILES['Image']['type'];
		
		ini_set("memory_limit","100M");
		$Image = loadimage($MaxSize, $Fname, $Iname, $ISize, $ITemp, $IType, $Folder, $IWidth, $IHeight, false, true, $use_ftp, $conn_id);
		ini_restore ("memory_limit");
	} else {
		$Image[0] = true;
		$Image[1] = $Imagev;
	}
	if($Image[0]){
		$Image = $Image[1];
		$text = clean_variable($Desc,'Store');		
		$upd = "UPDATE `cust_customers` SET `cust_image` = '$Image', `cust_desc` = '$text' WHERE `cust_id` = '$cust_id'";
		$updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());
	} else {
		$message = "";
		if($Image[0]){
			$message += $Image[1]+"\n";
		}
		$Image = $Image[1];
	}
} else {	
	$query_get_info = "SELECT `cust_image`, `cust_desc` FROM `cust_customers` WHERE `cust_id` = '$cust_id'";
	$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
	$row_get_info = mysql_fetch_assoc($get_info);
	$totalRows_get_info = mysql_num_rows($get_info);
	
	$Desc = $row_get_info['cust_desc'];
	$Image = "";
	$Imagev = $row_get_info['cust_image'];
	
	mysql_free_result($get_info);
}
?>