<?
if (!defined('PhotoExpress Pro')) {
	echo "Hacking Attempt";
	die();
}

session_start();
require_once($r_path.'capture_user_access.php');
$QueryString = $_SERVER['QUERY_STRING'];
$QueryString = preg_replace("/&token=[\\d\\w]*/","",$QueryString);
$QueryString = preg_replace("/token=[\\d\\w]*/","",$QueryString);
$QueryString = preg_replace("/&admin=[\\d\\w]*/","",$QueryString);
$QueryString = preg_replace("/&adminid=[\\d\\w]*/","",$QueryString);

if(isset($_GET['token'])){
	session_id(strip_tags($_GET['token']));
	if(!isset($_SESSION['AdminLog'])) session_start();
	$token = "&token=".session_id();
} else {
	if(!isset($_SESSION['AdminLog'])) session_start();
	$token = "";
}
if(isset($_GET['admin']) && $_GET['admin'] == "true"){
	$token.="&admin=".$_GET['admin']."&adminid=".$_GET['adminid'];
}

if(isset($_SESSION['AdminLog'])){
	$loginsession = $_SESSION['AdminLog'];
	if($loginsession[0] === 0 || $loginsession[0] === false){
		setcookie("ProImageLogIn", 'Nothing', time()+60*60*24*7*4,'/','.proimagesoftware.com');
		$GoTo = "/PhotoCP/index.php";
		header(sprintf("Location: %s", $GoTo));
	}
} else {
	setcookie("ProImageLogIn", 'Nothing', time()+60*60*24*7*4,'/','.proimagesoftware.com');
	$GoTo = "/PhotoCP/index.php";
	header(sprintf("Location: %s", $GoTo));
}
?>
