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
$IWidth = 725;
$IHeight = 50;

mysql_free_result($get_id);
$Company = (isset($_POST['Company_Name'])) ? clean_variable($_POST['Company_Name'],true) : '';
$Email = (isset($_POST['Email'])) ? clean_variable($_POST['Email'],true) : '';
$Work = (isset($_POST['Work_Number'])) ? clean_variable($_POST['Work_Number'],true) : '';
$W1 = substr($Work,0,3);
$W2 = substr($Work,3,3);
$W3 = substr($Work,6,4);
$Ext = (isset($_POST['Extension'])) ? clean_variable($_POST['Extension'],true) : '';
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
		$Iname = "Footer.jpg";
		$ISize = $_FILES['Image']['size'];
		$ITemp = $_FILES['Image']['tmp_name'];
		$IType = $_FILES['Image']['type'];
		
		ini_set("memory_limit","100M");
		$Image = loadimage($MaxSize, $Fname, $Iname, $ISize, $ITemp, $IType, $Folder, $IWidth, $IHeight, true, true, $use_ftp, $conn_id);
		ini_restore ("memory_limit");
	} else {
		$Image[0] = true;
		$Image[1] = $Imagev;
	}
	if($Image[0]){
		$Image = $Image[1];
		$upd = "UPDATE `cust_customers` SET `cust_fcname` = '$Company', `cust_fwork` = '$Work', `cust_fext` = '$Ext', `cust_femail` = '$Email', `cust_icon` = '$Image' WHERE `cust_id` = '$cust_id'";
		$updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());
	} else {
		$message = "";
		if($Image[0]){
			$message += $Image[1]+"\n";
		}
		$Image = $Image[1];
	}
} else {	
	$query_get_info = "SELECT `cust_cname`, `cust_work`, `cust_ext`, `cust_email`, `cust_fcname`, `cust_fwork`, `cust_fext`, `cust_femail`, `cust_icon` FROM `cust_customers` WHERE `cust_id` = '$cust_id' LIMIT 0,1";
	$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
	$row_get_info = mysql_fetch_assoc($get_info);

	$Company = ($row_get_info['cust_fcname']!="")?$row_get_info['cust_fcname']:$row_get_info['cust_cname'];
	$Email = ($row_get_info['cust_femail']!="")?$row_get_info['cust_femail']:$row_get_info['cust_email'];
	$Work = ($row_get_info['cust_fwork']!="0")?$row_get_info['cust_fwork']:$row_get_info['cust_work'];
	//1234567890
	//0123456789
	$W1 = substr($Work,0,3);
	$W2 = substr($Work,3,3);
	$W3 = substr($Work,6,4);
	$Ext = ($row_get_info['cust_fext']!="0")?$row_get_info['cust_fext']:$row_get_info['cust_ext'];
	$Image = "";
	$Imagev = $row_get_info['cust_icon'];
	
	mysql_free_result($get_info);
}
?>