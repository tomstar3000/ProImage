<?
if(isset($_GET['token'])){
	session_id(strip_tags($_GET['token']));
	if(!isset($_SESSION['AdminLog'])) session_start();
	$token = "&token=".session_id();
} else {
	if(!isset($_SESSION['AdminLog'])) session_start();
	$token = "";
}

$_SESSION['AdminLog'] = false;
unset($_SESSION['AdminLog']);
setcookie("ProImageLogIn", 'Nothing', time()+60*60*24*7*4,'/','.proimagesoftware.com');

$GoTo = "/PhotoCP/index.php";
header(sprintf("Location: %s", $GoTo));
?>