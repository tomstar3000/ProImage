<?
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_cp_connection = "localhost:3306";
$database_cp_connection = "cp_photoexpress";
$username_cp_connection = "root";
$password_cp_connection = "aevium07";
$gateways_cp_connection = "MySQL"; // MySQL, MsSQL are support
if($gateways_cp_connection == "MySQL"){
	$cp_connection = mysql_pconnect($hostname_cp_connection, $username_cp_connection, $password_cp_connection) or die("We are experiencing some technical difficulties.  Please try again later"); 
} else {
	$cp_connection = mssql_connect($hostname_cp_connection, $username_cp_connection, $password_cp_connection) or die("We are experiencing some technical difficulties.  Please try again later"); 
}
require_once $r_path.'scripts/fnct_sql_processor.php';

$photographerFolder = '/srv/proimage/current/';
$phatFoto = '/srv/proimage/current/toPhatFoto/';

$use_ftp = false;
$ftp_server = "192.168.1.114";
$ftp_user_name = "pwilliam";
$ftp_user_pass = "HL3qMmBw";

//$ftp_server = "192.168.1.114";
//$ftp_user_name = "dev1";
//$ftp_user_pass = "aevium07";

$key = "AevNav";
?>