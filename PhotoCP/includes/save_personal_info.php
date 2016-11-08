<?  if(!isset($r_path)){ $count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2; $r_path = ""; for($n=0;$n<$count;$n++) $r_path .= "../"; }
include $r_path.'security.php';
require_once $r_path.'scripts/fnct_clean_entry.php';
require_once $r_path.'scripts/fnct_image_resize.php';
require_once $r_path.'scripts/fnct_format_file_name.php';

$FName = (isset($_POST['First_Name'])) ? clean_variable($_POST['First_Name'],true) : '';
$MInt = (isset($_POST['Middle_Initial'])) ? clean_variable($_POST['Middle_Initial'],true) : '';
$LName = (isset($_POST['Last_Name'])) ? clean_variable($_POST['Last_Name'],true) : '';
$CName = (isset($_POST['Company_Name'])) ? clean_variable($_POST['Company_Name'],true) : '';
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
$Work = (isset($_POST['Work_Number'])) ? clean_variable($_POST['Work_Number'],true) : '';
$W1 = (isset($_POST['W1'])) ? clean_variable($_POST['W1'],true) : '';
$W2 = (isset($_POST['W2'])) ? clean_variable($_POST['W2'],true) : '';
$W3 = (isset($_POST['W3'])) ? clean_variable($_POST['W3'],true) : '';
$WExt = (isset($_POST['Work_Extension'])) ? clean_variable($_POST['Work_Extension'],true) : '';
$Email = (isset($_POST['Email'])) ? clean_variable($_POST['Email'],true) : '';
$Website = (isset($_POST['Website'])) ? clean_variable($_POST['Website'],true) : '';


$UName = (isset($_POST['Username'])) ? clean_variable($_POST['Username'],true) : '';
$Psword = (isset($_POST['Password'])) ? md5(clean_variable($_POST['Password'],true)) : '';
$CPsword = (isset($_POST['Confirm_Password'])) ? md5(clean_variable($_POST['Confirm_Password'],true)) : '';
$SQId = (isset($_POST['Security_Question'])) ? clean_variable($_POST['Security_Question'],true) : '';
$SQAnw = (isset($_POST['Answer'])) ? clean_variable($_POST['Answer'],true) : '';

if(isset($_POST['Controller']) && $_POST['Controller'] == "Save" && ($cont == 'add' || $cont == 'edit')){
	if($Psword != $CPsword){
		$Error = "Your Passwords Do Not Match";
	} else {
		define('IN_PHPBB', true);
		$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : $r_path.'../Bulletin_Board_3/';
		$phpEx = substr(strrchr(__FILE__, '.'), 1);
		require($phpbb_root_path . 'common.' . $phpEx);
		require($phpbb_root_path . 'includes/functions_user.' . $phpEx);
		
		$checkUser = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
		$checkUser->mysql("SELECT `cust_username` FROM `cust_customers` WHERE `cust_id` = '$CustId';");
		$checkUser = $checkUser->Rows();
		
		$sql = 'SELECT user_id
				FROM ' . USERS_TABLE . "
				WHERE username_clean = '" . $db->sql_escape(utf8_clean_string($checkUser[0]['cust_username'])) . "'";
		$result = $db->sql_query($sql);
		$row = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);
		
		$checkUser = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
		$checkUser->mysql("SELECT COUNT(`cust_username`) AS `user_count`, `cust_username` FROM `cust_customers` WHERE `cust_username` = '$UName' AND `cust_id` != '$CustId';");
		$checkUser = $checkUser->Rows();
		
		if($checkUser[0]['user_count']>0){
			$Error = "That Username and Password are already in use.";
		} else {
			if(isset($_POST['Password']) && $_POST['Password'] != "" && $_POST['Password'] != " "){
				$o_path = $r_path;
				$r_path .= "../";
				require_once($r_path.'scripts/cart/encrypt.php');
				$r_path = $o_path;
				$UCWord = clean_variable(encrypt_data(clean_variable($_POST['Password'], true)),"Store");

				$hash = phpbb_hash(clean_variable($_POST['Password'], true));
				
				$sql = "UPDATE ".USERS_TABLE."
					SET user_password = '" . $db->sql_escape($hash) . "',
						user_pass_convert = 0
					WHERE user_id = '".$row['user_id']."';";
				$db->sql_query($sql);
				
				
				$upd = "UPDATE `cust_customers` SET `cust_username` = '$UName',`cust_password` = '$Psword', `cust_uncode` = '$UCWord' WHERE `cust_id` = '$CustId';";
			} else {
				$upd = "UPDATE `cust_customers` SET `cust_username` = '$UName' WHERE `cust_id` = '$CustId';";
			}
			$updInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
			$updInfo->mysql($upd);
			
			if($checkUser[0]['cust_username'] != $UName){
				$sql = "UPDATE " . USERS_TABLE . "
					SET username = '" .$UName. "',
						username_clean = '" . $db->sql_escape(utf8_clean_string($UName)) . "'
					WHERE user_id = '".$row['user_id']."';";
				$db->sql_query($sql);
				
				user_update_name($row_check_user['cust_username'],$UName);
			}
				
			$upd = "UPDATE `cust_customers` SET `cust_fname` = '$FName',`cust_mint` = '$MInt',`cust_lname` = '$LName',`cust_cname` = '$CName',`cust_add` = '$Add',`cust_add_2` = '$Add2',`cust_suite_apt` = '$SApt',`cust_city` = '$City',`cust_state` = '$State',`cust_zip` = '$Zip',`cust_country` = '$Country',`cust_phone` = '$Phone',`cust_cell` = '$Cell',`cust_fax` = '$Fax',`cust_work` = '$Work',`cust_ext` = '$WExt',`cust_email` = '$Email',`cust_website` = '$Website', `sec_quest_id` = '$SQId', `sec_quest_ans` = '$SQAnw' WHERE `cust_id` = '$CustId'";
			$updInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
			$updInfo->mysql($upd);
			
			$cont = "view";
		}
	} 
} else {
	if($cont != "add"){	
		$getInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
		$getInfo->mysql("SELECT * FROM `cust_customers` WHERE `cust_id` = '$CustId';");
		$getInfo = $getInfo->Rows();
				
		$FName = $getInfo[0]['cust_fname'];
		$MInt = $getInfo[0]['cust_mint'];
		$LName = $getInfo[0]['cust_lname'];
		$CName = $getInfo[0]['cust_cname'];
		$Add = $getInfo[0]['cust_add'];
		$Add2 = $getInfo[0]['cust_add_2'];
		$SApt = $getInfo[0]['cust_suite_apt'];
		$City = $getInfo[0]['cust_city'];
		$State = $getInfo[0]['cust_state'];
		$Zip = $getInfo[0]['cust_zip'];
		$Country = $getInfo[0]['cust_country'];
		$Phone = $getInfo[0]['cust_phone'];
		$P1 = substr($getInfo[0]['cust_phone'],0,3);
		$P2 = substr($getInfo[0]['cust_phone'],3,3);
		$P3 = substr($getInfo[0]['cust_phone'],6,4);
		$Cell = $getInfo[0]['cust_cell'];
		$C1 = substr($getInfo[0]['cust_cell'],0,3);
		$C2 = substr($getInfo[0]['cust_cell'],3,3);
		$C3 = substr($getInfo[0]['cust_cell'],6,4);
		$Fax = $getInfo[0]['cust_fax'];
		$F1 = substr($getInfo[0]['cust_fax'],0,3);
		$F2 = substr($getInfo[0]['cust_fax'],3,3);
		$F3 = substr($getInfo[0]['cust_fax'],6,4);
		$Work = $getInfo[0]['cust_work'];
		$W1 = substr($getInfo[0]['cust_work'],0,3);
		$W2 = substr($getInfo[0]['cust_work'],3,3);
		$W3 = substr($getInfo[0]['cust_work'],6,4);
		$WExt = $getInfo[0]['cust_ext'];
		$Email = $getInfo[0]['cust_email'];
		$Website = $getInfo[0]['cust_website'];
		$UName = $getInfo[0]['cust_username'];
		$SQId = $getInfo[0]['sec_quest_id'];
		$SQAnw = $getInfo[0]['sec_quest_ans'];
	}
}
define('NoSave',true);
if(isset($required_string))
	$onclick = 'MM_validateForm('.$required_string.'); if(document.MM_returnValue == true){document.getElementById(\'Controller\').value = \'Save\'; document.getElementById(\'form_action_form\').submit();}';
else 
	$onclick = 'document.getElementById(\'Controller\').value = \'Save\'; document.getElementById(\'form_action_form\').submit();';
?>
