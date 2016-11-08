<?php
function checkpassword($cust_id, $PWord){
	global $cp_connection;
	
	$query_check_password = "SELECT `user_id` FROM `admin_users` WHERE `user_password` = '$PWord' AND `user_id` = '$cust_id'";
	$check_password = mysql_query($query_check_password, $cp_connection) or die(mysql_error());
	$row_check_password = mysql_fetch_assoc($check_password);
	$totalRows_check_password = mysql_num_rows($check_password);
	
	return $totalRows_check_password;
	
	mysql_free_result($check_password);
}
if(isset($_POST['Controller']) && $_POST['Controller'] == "true"){

	require_once ($r_path.'scripts/fnct_clean_entry.php');
	
	$UName = trim(clean_variable($_POST['Username'], true));
	$Error = false;
	$Pattern = "^[a-z0-9]{4,15}$";
	
	if (!eregi($Pattern, trim(clean_variable($_POST['New_Username'],true))) && trim(clean_variable($_POST['New_Username'],true)) != ""){
		$Error = "Please limit your Username and Password to Alphanumeric Characters";
	} else if (!eregi($Pattern, $UName) && $UName != ""){
		$Error = "Please limit your Username and Password to Alphanumeric Characters";
	} else {
		$query_check_username = "SELECT `user_id` FROM `admin_users` WHERE `user_username` = '$UName' AND `user_id`";
		$check_username = mysql_query($query_check_username, $cp_connection) or die(mysql_error());
		$row_check_username = mysql_fetch_assoc($check_username);
		$totalRows_check_username = mysql_num_rows($check_username);
		
		if($totalRows_check_username != 0){
			$cust_id = $row_check_username['user_id'];
			$PWord = trim(clean_variable($_POST['Password'],true));
			$checkpass = checkpassword($cust_id, md5($PWord));
			
			if($checkpass != 0){
				
				$NUName = trim(clean_variable($_POST['New_Username'],true));
				$NPWord = trim(clean_variable($_POST['New_Password'],true));
				$CPWord = trim(clean_variable($_POST['Confirm_Password'],true));
				if ($NPWord == ""){
					$NPWord = md5($PWord);
				} else {
					$NPWord = md5($NPWord);
				}
				if (strcmp($NPWord, md5($CPWord)) != 0){
					$Error = "Your passwords do not match.";
				} else {
					$SQ = clean_variable($_POST['Security_Question'], true);
					$Answer = clean_variable($_POST['Answer'], true);
					/* if($Answer != ""){
						$upd = "UPDATE `cust_customers` SET `sec_quest_id` = '$SQ',`sec_quest_ans` = '$Answer' WHERE `cust_id` = '$cust_id'";
						$upd_info = mysql_query($upd, $cp_connection) or die(mysql_error());
					} */
					if($NUName){
						$query_uniq_username = "SELECT COUNT(`user_username`) AS `Num_Name` FROM `admin_users` WHERE `user_username` = '$NUName' AND `user_username` != '$UName'";
						$uniq_username = mysql_query($query_uniq_username, $cp_connection) or die(mysql_error());
						$row_uniq_username = mysql_fetch_assoc($uniq_username);
						$totalRows_uniq_username = mysql_num_rows($uniq_username);
						
						if($row_uniq_username['Num_Name'] == 0){
							$upd = "UPDATE `admin_users` SET `user_username` = '$NUName',`user_password` = '$NPWord' WHERE `user_id` = '$cust_id'";
							$upd_info = mysql_query($upd, $cp_connection) or die(mysql_error());
						} else {
							$Error = "That Username is already in use.";
						}
					} else {
						$upd = "UPDATE `admin_users` SET `user_password` = '$NPWord' WHERE `user_id` = '$cust_id'";
						$upd_info = mysql_query($upd, $cp_connection) or die(mysql_error());
					}
					if($upd_info && !$Error){
						$Error = "Your Log-in Information has been changed";
					} else {
						$Error = "We were unable to change your Log-in Information at this time";
					}
					unset($CPWord);
				}
			} else {
				$Error = "We were unable to find your username or password.";
			}
		} else {
			$Error = "Cannot find your Username or Password";
		}
		mysql_free_result($check_username);
	}
}
?>
