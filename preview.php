<?
$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-1; $r_path = ""; for($n=0;$n<$count;$n++)$r_path .= "../";

define ("Allow Scripts", true);
include $r_path.'scripts/security.php';
require_once($r_path.'Connections/cp_connection.php');
require_once $r_path.'scripts/fnct_clean_entry.php';
require_once($r_path.'scripts/fnct_xml_parser.php');
$Fonts = $r_path.'xml/fonts.xml';
$ImageId = (isset($_POST['ImageId'])) ? clean_variable($_POST['ImageId'],true) : ((isset($_GET['ImageId'])) ? clean_variable($_GET['ImageId'],true) : die());
$BorderId = (isset($_POST['Border'])) ? clean_variable($_POST['Border'],true) : ((isset($_GET['Border'])) ? clean_variable($_GET['Border'],true) : die());
$Horz = (isset($_POST['Horizontal'])) ? clean_variable($_POST['Horizontal'],true) : ((isset($_GET['Horizontal'])) ? clean_variable($_GET['Horizontal'],true) : die());
$PX = (isset($_POST['SX'])) ? clean_variable($_POST['SX'],true) : ((isset($_GET['SX'])) ? clean_variable($_GET['SX'],true) : die());
$PY = (isset($_POST['SY'])) ? clean_variable($_POST['SY'],true) : ((isset($_GET['SY'])) ? clean_variable($_GET['SY'],true) : die());
$PBWidth = (isset($_POST['BWidth'])) ? clean_variable($_POST['BWidth'],true) : ((isset($_GET['BWidth'])) ? clean_variable($_GET['BWidth'],true) : die());
$PBHeight = (isset($_POST['BHeight'])) ? clean_variable($_POST['BHeight'],true) : ((isset($_GET['BHeight'])) ? clean_variable($_GET['BHeight'],true) : die());
$PIWidth = (isset($_POST['NWidth'])) ? clean_variable($_POST['NWidth'],true) : ((isset($_GET['NWidth'])) ? clean_variable($_GET['NWidth'],true) : die());
$PIHeight = (isset($_POST['NHeight'])) ? clean_variable($_POST['NHeight'],true) : ((isset($_GET['NHeight'])) ? clean_variable($_GET['NHeight'],true) : die());
$Text = (isset($_POST['Text'])) ? clean_variable($_POST['Text'],true) : ((isset($_GET['Text'])) ? clean_variable($_GET['Text'],true) : die());
$TX = (isset($_POST['TX'])) ? clean_variable($_POST['TX'],true) : ((isset($_GET['TX'])) ? clean_variable($_GET['TX'],true) : die());
$TY = (isset($_POST['TY'])) ? clean_variable($_POST['TY'],true) : ((isset($_GET['TY'])) ? clean_variable($_GET['TY'],true) : die());
$Color = (isset($_POST['Color'])) ? clean_variable($_POST['Color'],true) : ((isset($_GET['Color'])) ? clean_variable($_GET['Color'],true) : die());
$Font = (isset($_POST['Font'])) ? clean_variable($_POST['Font'],true) : ((isset($_GET['Font'])) ? clean_variable($_GET['Font'],true) : die());
$OFSize = (isset($_POST['Size'])) ? clean_variable($_POST['Size'],true) : ((isset($_GET['Size'])) ? clean_variable($_GET['Size'],true) : die());
$Bold = (isset($_POST['Bold'])) ? clean_variable($_POST['Bold'],true) : ((isset($_GET['Bold'])) ? clean_variable($_GET['Bold'],true) : die());
$Italic = (isset($_POST['Italic'])) ? clean_variable($_POST['Italic'],true) : ((isset($_GET['Italic'])) ? clean_variable($_GET['Italic'],true) : die());
//$Text = urlencode($Text);

define("BorderPreview", true);
define("BORDERTYPE","Review", true);
define("PERFORMANCE",true);

require_once $r_path.'scripts/cart/build_border.php';  ?>
