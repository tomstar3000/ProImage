<? $count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2; $r_path = ""; for($n=0;$n<$count;$n++) $r_path .= "../";
define('Allow Scripts',true);
define("PhotoExpress Pro", true);
define("LogIn", true);

require_once($r_path.'../scripts/cart/ssl_paths.php');
require_once('../Connections/cp_connection.php');
require_once($r_path.'scripts/fnct_sql_processor.php');

include 'config.php';
$attempt_timer = 30;
$attempt_number = 5;
if(isset($_POST['Controller']) && $_POST['Controller'] == "true"){
	function checkpassword($User_id, $Pword){
		global $database_cp_connection,$cp_connection,$gateways_cp_connection;
		$getCheckUser = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
		$getCheckUser->mysql("SELECT `cust_id` FROM `cust_customers` WHERE `cust_password` = '$Pword' AND `cust_id` = '$User_id';");
		
		// Return True or False
		return $getCheckUser->TotalRows();
	}

	// Set the number of tries in database against customer id
	function settime($try_num, $User_id){
		global $database_cp_connection,$cp_connection,$gateways_cp_connection, $AevNet_Path;
		global $exist_action;
		
		$getGetUser = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
		$getGetUser->mysql("SELECT `cust_username` FROM `cust_customers` WHERE `cust_id` = '$User_id';");
		$getGetUser = $getGetUser->Rows();
		
		$UserName = $getGetUser[0]['cust_username'];
		$UserName = md5($UserName.time());
		
		$getUpdUser = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
    $getUpdUser->mysql("UPDATE `cust_customers` SET `cust_login_try` = '$try_num', `cust_session` = '$UserName', cust_last_login = NOW() WHERE `cust_id` = '$User_id';");
		
		if($try_num == 0){
			$User_session = array();
			$User_session[0] = $UserName;
			$User_session[1] = "10";
			$User_session[2] = $User_id;
			
			session_id(session_secure());
			session_start();
			session_register('AdminLog');
			$PHPSESSID = session_id();
			
			$_SESSION['AdminLog'] = $User_session;
			setcookie("TestCookie", "Is Cookie");
			if(!isset($_COOKIE['TestCookie'])) $token = "?token=".session_id();
			else $token = "";

			setcookie("ProImageLogIn", urlencode(serialize($User_session)), time()+60*60*24*7*4,'/','.proimagesoftware.com');			
			
      
      $getInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
      $accountDisabledQuery = "SELECT `cust_canceled`, `cust_active`, `cust_del`, `cust_hold` FROM `cust_customers` WHERE `cust_photo` = 'y' AND `cust_id` = $CustId GROUP BY `cust_id` ORDER BY `cust_handle` ASC";

      $getInfo = mysql_query( $accountDisabledQuery, $cp_connection);
      $row_get_info = mysql_fetch_assoc($getInfo);

      $isCancelled = $row_get_info['cust_canceled'] == 'y';
      $isInactive = $row_get_info['cust_active'] == 'n';
      $isDeleted = $row_get_info['cust_del'] == 'y';
      $isOnHold = $row_get_info['cust_hold'] == 'y';

      $isAccountDisabled = $isCancelled || $isInactive || $isDeleted || $isOnHold;

      if( $isAccountDisabled == 1 ){
        $GoTo = "/".$AevNet_Path."/cp.php?path=Pers,Info&cont=view&sort=&rcrd=".$token;
      }
      else{
        $GoTo = "/".$AevNet_Path."/cp.php".$token;
      }

      header(sprintf("Location: %s", $GoTo));

		}
	}
	
	include $r_path.'scripts/fnct_clean_entry.php';
	$UName = clean_variable(trim($_POST['Username']),true);
	$Error = false;
	
	// Check for username is database
	$getCheckUsername = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
	$getCheckUsername->mysql("SELECT `cust_id`, `cust_last_login`, `cust_login_try` FROM `cust_customers` WHERE `cust_username` = '$UName';");
	
	// If Username exists
	if($getCheckUsername->TotalRows() != 0){
		// Get customer Id, password, and the last time database was accessed
		$getCheckUsername = $getCheckUsername->Rows();
		$User_id = $getCheckUsername[0]['cust_id'];
		$Pword = md5(clean_variable(trim($_POST['Password']),true));
		$lasttime = $getCheckUsername[0]['cust_last_login'];
		
		//$lasttime = "20060613155500";
		$lasttime = date("YmdHis", mktime(substr($lasttime,8,2), (substr($lasttime,10,2)+$attempt_timer), substr($lasttime,12,2), substr($lasttime,4,2), substr($lasttime,6,2), substr($lasttime,0,4)));
		$datetime =  date("YmdHis"); $num_try = $row_check_username['cust_login_try'];

		// If number of tries to log in are less than 5
		if($num_try < $attempt_number){
			// Check the password
			$checkpass = checkpassword($User_id,$Pword);
			
			// If password is good go to customer check out
			if($checkpass != 0){
				settime(0,$User_id);
			} else {
				// If password fails then set number of tries plus 1
				settime($num_try+1,$User_id);
				// If number of tries is equal to 5 suspend account
				if($num_try+1 == $attempt_number) $Error = "Your account has been suspended for ".$attempt_timer." minutes: Message 106";
				else $Error = "We were unable to find your username or password: Message 101";
			}
		} else {
			// If number of tries is equal to five check the last time the user attempted to log in.
			if($lasttime<=$datetime){
				// Check the password
				$checkpass = checkpassword($User_id,$Pword);
				// If password is good continue to member section
				if($checkpass != 0){
					settime(0,$User_id);
				} else {
					// Else set number of tries equal to 1;
					settime(1,$User_id);
					$Error = "We were unable to find your username or password: Message 102";
				}
			} else {
				// If number of tries is equal to 5 then dispaly message
				$Error = "Your account has been suspended for ".$attempt_timer." minutes: Message 105";
			}
		}
	} else {
		$Error = "We were unable to find your username or password: Message 103";
	}
} else if(isset($_COOKIE['ProImageLogIn'])){
	$User_session = unserialize(urldecode($_COOKIE['ProImageLogIn']));
	if($User_session != false && is_numeric($User_session[1]) && $User_session[1] == "10"){
		session_start();
		$_SESSION['AdminLog'] = $User_session;
		setcookie("TestCookie", "Is Cookie");
		if(!isset($_COOKIE['TestCookie'])) $token = "?token=".session_id();
		else $token = "";
	
		setcookie("ProImageLogIn", urlencode(serialize($User_session)), time()+60*60*24*7*4,'/','.proimagesoftware.com');
		$GoTo = "/".$AevNet_Path."/cp.php".$token;
		header(sprintf("Location: %s", $GoTo));
	}
}
$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-1; $r_path = ""; for($n=0;$n<$count;$n++) $r_path .= "../"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<? include $r_path.'includes/_metadata.php'; ?>
<link href="/css/ProImageSoftware_09.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div id="Container">
  <? include $r_path.'includes/_navigation.php'; ?>
  <div id="Content2" class="Grey">
    <div id="Text">
      <h1 class="HdrLogIn"><span>Log In</span></h1>
      <br clear="all" />
      <div class="BgText">
        <div class="BgTextBottom">
          <div class="ColmnCntr"><br clear="all" />
            <? if(isset($Error)){ ?><div id="Error"><img src="/images/warning.jpg" width="30" height="29" border="0" align="absmiddle" style="float:left" /><img src="/images/warning.jpg" width="30" height="29" border="0" align="absmiddle"  style="float:right" />
              <p><? echo $Error; ?></p>
            </div><? } ?>
            <form method="post" name="LoginForm" id="LoginForm" enctype="multipart/form-data" action="<? echo $_SERVER['PHP_SELF']; ?>">
              <label for="Username">Username</label>
              <label for="Password">Password</label>
              <br clear="all" />
              <div class="CstmInput">
                <input type="text" name="Username" id="Username" title="Username" value="" />
              </div>
              <div class="CstmInput">
                <input type="password" name="Password" id="Password" title="Password" value="" />
              </div>
              <div class="BtnSubmit">
                <input type="submit" name="Submit" id="Sumbit" value="Submit" />
              </div>
              <input type="hidden" name="Controller" id="Controller" value="true" />
              <br />
              <a href="/lookup.php">Forgot Username/Password </a>
            </form>
          </div>
          <br clear="all" />
        </div>
      </div>
    </div>
    <br clear="all" />
  </div>
  <? include $r_path.'includes/_footer.php'; ?>
</div>
</body>
</html>
