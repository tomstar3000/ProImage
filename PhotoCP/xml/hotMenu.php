<?

if (isset($r_path) === false) {
    $count = count(explode("/", substr($_SERVER['PHP_SELF'], 1))) - 2;
    $r_path = "";
    for ($n = 0; $n < $count; $n++)
        $r_path .= "../";
}
$RealPath = $r_path;
$r_path = '../';
define("PhotoExpress Pro", true);
require_once $r_path . 'config.php';
require_once $r_path . '../Connections/cp_connection.php';
require_once $r_path . 'scripts/encrypt.php';
require_once $r_path . 'scripts/fnct_clean_entry.php';
require_once $r_path . 'includes/_hotmenu_names.php';

$ID = isset($_GET['id']) ? clean_variable($_GET['id'], true) : 0;
$BTN = isset($_GET['btn']) ? clean_variable(rawurldecode($_GET['btn']), true) : 0;
$ACT = isset($_GET['act']) ? clean_variable($_GET['act'], true) : 0;

$StrArray = array();
if ($ID == 0) {
    header("Cache-Control: no-cache, must-revalidate");
    header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
    header('Content-type: text/xml');
    echo '<?xml version="1.0" encoding="utf-8"?>';
    echo '<data></data>';
    exit();
}
$getInfo = new sql_processor($database_cp_connection, $cp_connection, $gateways_cp_connection);
$getInfo->mysql("SELECT * FROM `photo_hotmenu` WHERE `cust_id` = '$ID';");
if ($getInfo->TotalRows() == 0 && $ACT == 1) {
    $StrArray[] = $BTN;
    $getInfo->mysql("INSERT INTO `photo_hotmenu` (`cust_id`,`hot_menu`) VALUES ('$ID','" . rawurlencode(serialize($StrArray)) . "');");
} else {
    $StrArray = $getInfo->Rows();
    $StrArray = unserialize(rawurldecode($StrArray[0]['hot_menu']));
    $Key = array_search($BTN, $StrArray);
    if ($ACT == 1) {
        if ($Key === false)
            $StrArray[] = $BTN;
    }
    else if ($ACT == 2) {
        if ($Key !== false)
            array_splice($StrArray, $Key, 1);
    }

    $getInfo->mysql("UPDATE `photo_hotmenu` SET `hot_menu` = '" . rawurlencode(serialize($StrArray)) . "' WHERE `cust_id` = '$ID';");
}

header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header('Content-type: text/xml');

echo '<?xml version="1.0" encoding="utf-8"?>';
echo '<data>';
foreach ($StrArray as $r) {
    $Btn = HotMenuVars($r);
    echo '<btn id="' . rawurlencode($r) . '" name="' . $Btn[0] . '" alt="' . $Btn[1] . '" act="' . $Btn[2] . '" cont="' . $Btn[3] . '"></btn>';
}
echo '</data>';
?>
