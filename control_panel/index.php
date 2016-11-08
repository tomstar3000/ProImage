<?
$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
$r_path = "";
for($n=0;$n<$count;$n++){
	$r_path .= "../";
}
define ("PhotoExpress Pro", true);
define('Allow Scripts',true);
require_once($r_path.'../scripts/cart/ssl_paths.php');
require_once('../Connections/cp_connection.php');
include 'config.php';
$attempt_timer = 30;
$attempt_number = 5;
$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
$r_path = "";
for($n=0;$n<$count;$n++){
	$r_path .= "../";
}
if(isset($_POST['Controller']) && $_POST['Controller'] == "true"){
	// Check the password against customer id and password
	function checkpassword($User_id, $Pword){
		global $cp_connection;
		
		$query_check_password = "SELECT `cust_id` FROM `cust_customers` WHERE `cust_password` = '$Pword' AND `cust_id` = '$User_id'";
		$check_password = mysql_query($query_check_password, $cp_connection) or die(mysql_error());
		$row_check_password = mysql_fetch_assoc($check_password);
		$totalRows_check_password = mysql_num_rows($check_password);
			
		// Return True or False
		return $totalRows_check_password;
		
		mysql_free_result($check_password);
	}

	// Set the number of tries in database against customer id
	function settime($try_num, $User_id){
		global $cp_connection;
		global $exist_action;
				
		$query_get_userinfo = "SELECT `cust_username` FROM `cust_customers` WHERE `cust_id` = '$User_id'";
		$get_userinfo = mysql_query($query_get_userinfo, $cp_connection) or die(mysql_error());
		$row_get_userinfo = mysql_fetch_assoc($get_userinfo);
		$totalRows_get_userinfo = mysql_num_rows($get_userinfo);
		
		$UserName = $row_get_userinfo['cust_username'];
		$UserName = md5($UserName.time());
		
		$update = "UPDATE `cust_customers` SET `cust_login_try` = '$try_num', `cust_session` = '$UserName' WHERE `cust_id` = '$User_id'";
		$updateinfo = mysql_query($update, $cp_connection) or die(mysql_error());
		
		if($try_num == 0){
			$User_session = array();
			$User_session[0] = $UserName;
			$User_session[1] = "10";
						
			session_start();
			session_register('AdminLog');
			$_SESSION['AdminLog'] = $User_session;
			setcookie("TestCookie", "Is Cookie");
			if(!isset($_COOKIE['TestCookie'])){
				$token = "?token=".session_id();
			} else {
				$token = "";
			}
			setcookie("AdminLog", base64_encode(urlencode(serialize($User_session))), time()+60*60*24*7*4,'/','.proimagesoftware.com');
			$GoTo = "/control_panel/control_panel.php".$token;
			header(sprintf("Location: %s", $GoTo));
		}
	}
	
	include $r_path.'scripts/fnct_clean_entry.php';
	mysql_select_db($database_cp_connection, $cp_connection);
	$UName = clean_variable(trim($_POST['Username']),true);
	$message = false;
			
	// Check for username is database
	$query_check_username = "SELECT `cust_id`, `cust_last_login`, `cust_login_try` FROM `cust_customers` WHERE `cust_username` = '$UName'";
	$check_username = mysql_query($query_check_username, $cp_connection) or die(mysql_error());
	$row_check_username = mysql_fetch_assoc($check_username);
	$totalRows_check_username = mysql_num_rows($check_username);
	
	// If Username exists
	if($totalRows_check_username != 0){
		// Get customer Id, password, and the last time database was accessed
		$User_id = $row_check_username['cust_id'];
		$Pword = md5(clean_variable(trim($_POST['Password']),true));
		$lasttime = $row_check_username['cust_last_login'];
		
		//$lasttime = "20060613155500";
		$lasttime = date("YmdHis", mktime(substr($lasttime,8,2), (substr($lasttime,10,2)+$attempt_timer), substr($lasttime,12,2), substr($lasttime,4,2), substr($lasttime,6,2), substr($lasttime,0,4)));
		$datetime =  date("YmdHis");
		$num_try = $row_check_username['cust_login_try'];

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
				if($num_try+1 == $attempt_number){
					$message = "Your account has been suspended for ".$attempt_timer." minutes";
				} else {
					$message = "We were unable to find your username or password.";
				}
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
					$message = "We were unable to find your username or password.";
				}
			} else {
				// If number of tries is equal to 5 then dispaly message
				$message = "Your account has been suspended for ".$attempt_timer." minutes";
			}
		}
	} else {
		$message = "We were unable to find your username or password.";
	}
	mysql_free_result($check_username);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="shortcut icon" href="http://www.proimagesoftware.com/photoexpress.ico" type="image/x-icon">
<link rel="icon" href="http://www.proimagesoftware.com/photoexpress.ico" type="image/x-icon">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta name="robots" content="index,follow" />
<meta name="keywords" content=""/>
<meta name="language" content="english"/>
<meta name="author" content="Pro Image Software" />
<meta name="copyright" content="2007" />
<meta name="reply-to" content="info@proimagesoftware.com" />
<meta name="document-rights" content="Copyrighted Work" />
<meta name="document-type" content="Web Page" />
<meta name="document-rating" content="General" />
<meta name="document-distribution" content="Global" />
<meta http-equiv="expires" content="0" />
<meta http-equiv="Pragma" content="no-cache" />
<title>ProImageSoftware</title>
<link href="/stylesheets/photoexpress.css" rel="stylesheet" type="text/css" />
<script type="text/JavaScript">
<!--
function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_validateForm() { //v4.0
  var i,p,q,nm,test,num,min,max,errors='',args=MM_validateForm.arguments;
  for (i=0; i<(args.length-2); i+=3) { test=args[i+2]; val=MM_findObj(args[i]);
    if (val) { nm=val.name; if ((val=val.value)!="") {
      if (test.indexOf('isEmail')!=-1) { p=val.indexOf('@');
        if (p<1 || p==(val.length-1)) errors+='- '+nm+' must contain an e-mail address.\n';
      } else if (test!='R') { num = parseFloat(val);
        if (isNaN(val)) errors+='- '+nm+' must contain a number.\n';
        if (test.indexOf('inRange') != -1) { p=test.indexOf(':');
          min=test.substring(8,p); max=test.substring(p+1);
          if (num<min || max<num) errors+='- '+nm+' must contain a number between '+min+' and '+max+'.\n';
    } } } else if (test.charAt(0) == 'R') errors += '- '+nm+' is required.\n'; }
  } if (errors) alert('The following error(s) occurred:\n'+errors);
  document.MM_returnValue = (errors == '');
}
//-->
</script>
</head>
<body>
<div id="Container">
  <div id="Logo">
    <div id="Main_Navigation">
      <ul>
        <li id="What"><a href="/index.php">What is it? </a></li>
        <li id="Services"><a href="/services.php">Services and Features</a></li>
        <li id="Create"><a href="/join.php">Create A New Account</a></li>
        <li id="Contact_Us"><a href="/contact_us.php">Contact Us</a></li>
      </ul>
    </div>
  </div>
  <div id="Main_Content">
    <div id="Main_Text">
      <h1>Welcome to Photo Express Pro Image Software</h1>
      <p>Pro Image Software is a place where you the photographer and your clients come together. With this cutting edge media
        driven design, your clients will love hanging out viewing and ordering images from your professional photography. Not
        only will your clients love this ordering system, which means more revenue for you, but we are sure you will love this
        software too! PC and Mac compatible! </p>
      <h1>
        <div onclick="javascript:document.location.href='/join.php'" style="float:left; cursor:pointer;"><span class="White_Text">Get
            Started</span> Now.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
        <div onclick="javascript:document.location.href='/services.php'" style="float:left; cursor:pointer;"></div>
      </h1>
      <br clear="all" />
      <p align="center"><br />
        <a href="/join.php" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('startaccount','','/images/btn_getStarted_on.jpg',1)"><img src="/images/btn_getStarted.jpg" name="startaccount" width="223" height="136" border="0" id="startaccount" /></a><a href="/services.php" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('services','','/images/btn_services_features_on.jpg',1)"><img src="/images/btn_services_features.jpg" name="services" width="223" height="136" hspace="5" border="0" id="services" /></a><a href="/services.php"></a><a href="/demo.php" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('demo','','/images/btn_demo_on.jpg',1)"><img src="/images/btn_demo.jpg" name="demo" width="223" height="136" border="0" id="demo" /></a><a href="/demo.php" target="_blank"></a></p>
      <br clear="all" />
    </div>
    
    <div id="Event">
      <h2>Photographer Login </h2>
      <form action="<? echo $_SERVER['control_panel/PHP_SELF']; ?>" name="LogIn_Form" id="LogIn_Form" method="post" enctype="multipart/form-data">
        <p class="no_indent">
          <? if(isset($message)){ ?>
        </p>
        <div style="margin:8px; padding-right:5px; padding-top:5px; padding-bottom:5px; height:auto; background-color:#FFFFFF; clear:both"><img src="/images/warning.jpg" width="30" height="29" border="0" align="absmiddle" style="float:left; padding-right:3px;" />
            <p class="no_indent" style="color:#990000; margin:0px; clear:right"><? echo $message; ?></p>
        </div>
        <? } ?>
        <input name="Username" id="Username" type="text" value="username" onclick="javascript:this.value=''" onchange="javascript:document.getElementById('Password').value=''" />
        <br />
        <input name="Password" id="Password" type="password" value="password" onclick="javascript:this.value=''" />
        <input type="hidden" name="Controller" id="Controller" value="true" />
        <img src="/images/btn_enter.jpg" width="50" height="21" align="absmiddle" onclick="MM_validateForm('Username','','R','Password','','R');if(document.MM_returnValue==true){document.getElementById('LogIn_Form').submit();}" />
        </p>
        <p><a href="/lookup.php">Forgot Your Password</a> </p>
        <div style="height:1px; overflow:hidden"><br />
            <input type="submit" name="btn_submit" id="btn_submit" value="submit" />
        </div>
      </form>
    </div>
    <br clear="all" />
  </div>
</div>
<div id="Footer">
  <div style="float:left">
    <p> Photo Express Digital Studio </p>
  </div>
  <div style="float:left; margin-left:30px;">
    <p><a href="mailto:info@nathenjames.com" class="Footer_Nav">info@photoexpressdigitalstudio.com</a></p>
  </div>
</div>
</body>
</html>
