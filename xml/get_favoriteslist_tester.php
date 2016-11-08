<?
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-1;
$r_path = "";
for($n=0;$n<$count;$n++)$r_path .= "../";
define ("Allow Scripts", true);
include $r_path.'scripts/security.php';
require_once($r_path.'Connections/cp_connection.php');
require_once($r_path.'scripts/fnct_clean_entry.php');
require_once($r_path.'scripts/fnct_xml_parser.php');
$data = file_get_contents("php://input");

//$data = '<event code="dcw1flyinghorse"></event>';
$data = '<?xml version="1.0" encoding="iso-8859-1"?>'.$data;
$parser = new XMLParser($data, 'raw', 1);
$tree = $parser->getTree();
$code = $tree['EVENT']['ATTRIBUTES']['CODE'];
$email = $tree['EVENT']['ATTRIBUTES']['EMAIL'];

mysql_select_db($database_cp_connection, $cp_connection);
$query_info = "SELECT *
	FROM `photo_cust_favories`
	WHERE `fav_code` = '$code'
		AND `fav_email` = '$email'
	ORDER BY `fav_date` DESC";
$info = mysql_query($query_info, $cp_connection) or die(mysql_error());
$row_info = mysql_fetch_assoc($info);
echo '<?xml version="1.0" encoding="iso-8859-1"?>';
echo '<images name="Favorites">';
echo $row_info['fav_xml'];
echo '</images>';
?>
