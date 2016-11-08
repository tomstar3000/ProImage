<?

$handle = explode("/", $_SERVER['REQUEST_URI']);
$handle = $handle[1];
define('HANDLE', $handle);

if (preg_match('/\/bio.php/i', $_SERVER['REQUEST_URI'])) {
    require_once('../bio.php');
} else if (preg_match('/\/public.php/i', $_SERVER['REQUEST_URI'])) {
    require_once('../public.php');
} else if (preg_match('/\/contact.php/i', $_SERVER['REQUEST_URI'])) {
    require_once('../contact.php');
} else {
    require_once('../index.php');
}
?>