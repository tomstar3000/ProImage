<? if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++) $r_path .= "../";
}
include $r_path.'security.php';
require_once $r_path.'scripts/fnct_clean_entry.php';
if($loginsession[1] <= 1) {
	$UId = $path[2];
} else {
	$UId = $loginsession[0];
}
$UName = (isset($_POST['Username'])) ? clean_variable($_POST['Username'],true) : '';
$Psword = (isset($_POST['Password'])) ? md5(clean_variable($_POST['Password'],true)) : '';
$CPsword = (isset($_POST['Confirm_Password'])) ? md5(clean_variable($_POST['Confirm_Password'],true)) : '';
$UserLvl = (isset($_POST['User_Type'])) ? clean_variable($_POST['User_Type'],true) : 1;

if(isset($_POST['Controller']) && $_POST['Controller'] == "Save" && ($cont == 'add' || $cont == 'edit')){
	$Usercodes = $navcodes;
	foreach($Usercodes as $k => $v){
		$Usercodes[$k]['Active'] = 0;
		foreach($v['Sub'] as $k2 => $v2){
			$Usercodes[$k]['Sub'][$k2]['Active'] = 0;
		}
	}
	foreach($_POST['Selection'] as $v){
		$Usercodes[$v]['Active'] = 1;
		if(count($_POST['Selection_'.$v]) > 0){
			foreach($_POST['Selection_'.$v] as $v2){
				$Usercodes[$v]['Sub'][$v2]['Active'] = 1;
			}
		}
	}
	$StoreCodes = urlencode(serialize($Usercodes));
	if($cont == "add"){
		if($Psword != $CPsword){
			$Error = "Your Passwords Do Not Match";
		} else {
			$query_check_user = "SELECT COUNT(`user_username`) AS `user_count` FROM `admin_users` WHERE `user_username` = '$UName'";
			$check_user = mysql_query($query_check_user, $cp_connection) or die(mysql_error());
			$row_check_user = mysql_fetch_assoc($check_user);				
			if($row_check_user['user_count']>0){
				$Error = "That Username and Password are already in use.";
			} else {	
				if($_POST['Time'] != $_SESSION['Time']){
					$_SESSION['Time'] = $_POST['Time'];			
					$add = "INSERT INTO `admin_users` (`user_username`,`user_password`,`user_level`,`user_log`) VALUES ('$UName','$Psword','$UserLvl','$StoreCodes');";
					$addinfo = mysql_query($add, $cp_connection) or die(mysql_error());
					
					$query_get_last = "SELECT `user_id` FROM `admin_users` ORDER BY `user_id` DESC LIMIT 0,1";
					$get_last = mysql_query($query_get_last, $cp_connection) or die(mysql_error());
					$row_get_last = mysql_fetch_assoc($get_last);
					
					$UId = $row_get_last['user_id'];
					array_push($path,$UId);
				}
			}
		}
	} else {
		if($Psword != $CPsword){
			$Error = "Your Passwords Do Not Match";
		} else {
			$query_check_user = "SELECT COUNT(`user_username`) AS `user_count` FROM `admin_users` WHERE `user_username` = '$UName' AND `user_id` != '$UId'";
			$check_user = mysql_query($query_check_user, $cp_connection) or die(mysql_error());
			$row_check_user = mysql_fetch_assoc($check_user);				
			if($row_check_user['user_count']>0){
				$Error = "That Username and Password are already in use.";
			} else {
				if(isset($_POST['Password']) && $_POST['Password'] != "" && $_POST['Password'] != " "){
					$upd = "UPDATE `admin_users` SET `user_username` = '$UName',`user_password` = '$Psword',`user_level` = '$UserLvl', `user_log` = '$StoreCodes' WHERE `user_id` = '$UId'";
				} else {
					$upd = "UPDATE `admin_users` SET `user_username` = '$UName',`user_level` = '$UserLvl', `user_log` = '$StoreCodes' WHERE `user_id` = '$UId'";
				}
				$updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());		
			}
		} 
	}
	$cont = "view";
} else {
	if($cont != "add"){		
		$query_get_info = "SELECT * FROM `admin_users` WHERE `user_id` = '$UId'";
		$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
		$row_get_info = mysql_fetch_assoc($get_info);
		
		$UName = $row_get_info['user_username'];
		$UserLvl = $row_get_info['user_level'];
		$Usercodes = unserialize(urldecode($row_get_info['user_log']));
		mysql_free_result($get_info);
	}
}

?>