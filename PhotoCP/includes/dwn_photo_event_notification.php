<?

define("PhotoExpress Pro", true);
$count = count(explode("/", substr($_SERVER['PHP_SELF'], 1))) - 2;
$r_path = "";
for ($n = 0; $n < $count; $n++)
    $r_path .= "../";

include $r_path . 'security.php';
require_once ($r_path . '../Connections/cp_connection.php');
require_once ($r_path . 'scripts/fnct_clean_entry.php');
mysql_select_db($database_cp_connection, $cp_connection);
if ($loginsession[1] >= 10)
    require_once($r_path . 'includes/get_user_information.php');
$ENId = preg_replace('/[^0-9]/', '', base64_decode(urldecode($_GET['id'])));

$getItem = new sql_processor($database_cp_connection, $cp_connection, $gateways_cp_connection);
$getItem->mysql("SELECT event_image
    FROM `photo_event_notes` 
    WHERE event_note_id = '$ENId';");


$content = base64_decode($getItem->results[0]['event_image']);

header('Content-Type: image/jpeg');
header("Pragma: no-cache");
header("Expires: 0");
header("Content-Length: " . strlen($content));
echo $content;