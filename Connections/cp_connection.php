<?
date_default_timezone_set('America/Denver');

# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"

$hostname_cp_connection = "localhost:3306";
$database_cp_connection = "cp_photoexpress";
$username_cp_connection = "root";
$password_cp_connection = "aevium07";
$gateways_cp_connection = "MySQL"; // MySQL, MsSQL are support
if($gateways_cp_connection == "MySQL"){
	$cp_connection = mysql_pconnect($hostname_cp_connection, $username_cp_connection, $password_cp_connection);/// or die("We are experiencing some technical difficulties.  Please try again later"); 
        mysql_select_db($database_cp_connection, $cp_connection);
} else {
	$cp_connection = mssql_connect($hostname_cp_connection, $username_cp_connection, $password_cp_connection) or die("We are experiencing some technical difficulties.  Please try again later"); 
}

function ping_SQLServer(){
	global $hostname_cp_connection, $database_cp_connection, $username_cp_connection, $password_cp_connection, $gateways_cp_connection, $cp_connection;
	if($gateways_cp_connection == "MySQL"){
		if (!mysql_ping($cp_connection)) {			
			$cp_connection = mysql_pconnect($hostname_cp_connection, $username_cp_connection, $password_cp_connection) or die("We are experiencing some technical difficulties.  Please try again later"); 
			mysql_select_db($database_cp_connection, $cp_connection);
		}
	}
}

require_once $r_path.'scripts/fnct_sql_processor.php';

$photographerFolder = '/srv/proimage/current/';
$phatFoto = '/srv/proimage/current/toPhatFoto/';

$use_ftp = false;
$ftp_server = "192.168.1.114";
$ftp_user_name = "pwilliam";
$ftp_user_pass = "HL3qMmBw";

$key = "AevNav";

$CCUName = "7vD6j9VF4G";
$CCKey	 = "7h8Jp399GV64DtbR";

define("TESTING", false);
define("PI_EMAIL", "info@proimagesoftware.com");
define("PI_SMTP", "smtp.proimagesoftware.com");
define("PI_HOSTNAME", "proimagesoftware.com");
define("DEV_EMAIL", "development@proimagesoftware.com");
define("NEW_PHOTOGRAPHER", "jim@proimagesoftware.com");
define("LAB_EMAILS", serialize(
	array(
		  "cserv@reedphoto.com",
		  "pat@proimagesoftware.com",
		  "benjamin@alivestudios.com",
		  )));

define("REEDFTP", "ftp.reedphoto.com");
define("REEDUSER", "ProImageSoftware");
define("REEDPASS", "FRast25TAvum");
?>