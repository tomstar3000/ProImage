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
$TId = $path[2];
$Name = (isset($_POST['Name'])) ? clean_variable($_POST['Name'],true) : '';
if(isset($_POST['Controller']) && $_POST['Controller'] == "Save" && ($cont == 'add' || $cont == 'edit')){	
	if($cont == "add"){
		if($_POST['Time'] != $_SESSION['Time']){
			$_SESSION['Time'] = $_POST['Time'];			
			$add = "INSERT INTO `emp_types` (`emp_type_name`) VALUES ('$Name');";
			$addinfo = mysql_query($add, $cp_connection) or die(mysql_error());
																																						
			$query_get_last = "SELECT `emp_type_id` FROM `prod_features` ORDER BY `emp_type_id` DESC LIMIT 0,1";
			$get_last = mysql_query($query_get_last, $cp_connection) or die(mysql_error());
			$row_get_last = mysql_fetch_assoc($get_last);
				
			$TId = $row_get_last['emp_type_id'];
			array_push($path,$TId);
		}
	} else if($cont == "edit"){
		$upd = "UPDATE `emp_types` SET `emp_type_name` = '$Name' WHERE `emp_type_id` = '$TId'";
		$updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());
	}
	$cont = "view";
} else {
	if($cont != "add"){	
		$query_get_info = "SELECT * FROM `emp_types` WHERE `emp_type_id` = '$TId'";
		$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
		$row_get_info = mysql_fetch_assoc($get_info);
		
		$Name = $row_get_info['emp_type_name'];
		
		mysql_free_result($get_info);
	}
}
?>
