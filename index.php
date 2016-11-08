<?
$count = count(explode("/", substr($_SERVER['PHP_SELF'], 1))) - 1;
$r_path = "";
for ($n = 0; $n < $count; $n++)
    $r_path .= "../";

define('Allow Scripts', true);
require_once($r_path . 'scripts/cart/ssl_paths.php');
require_once($r_path . 'scripts/fnct_clean_entry.php');
if (!isset($handle) && isset($_POST['Photographer'])) {
    require_once($r_path . 'Connections/cp_connection.php');
    require_once($r_path . 'scripts/fnct_sql_processor.php');

    $handle = clean_variable($_POST['Photographer'], true);

    $getCheckUser = new sql_processor($database_cp_connection, $cp_connection, $gateways_cp_connection);
    $getCheckUser->mysql("SELECT `cust_handle` FROM `cust_customers` WHERE `cust_handle` = '$handle';");

    if ($getCheckUser->TotalRows() > 0) {
        $getCheckUser = $getCheckUser->Rows();
        $handle = $getCheckUser[0]['cust_handle'];
        $GoTo = "/" . $handle;
        header(sprintf("Location: %s", $GoTo));
        die();
    } else {
        $Error = "We were unable to find that photographer.";
    }
} else if (isset($handle)) {
    require_once($r_path . 'home.php');
    die();
} else if (isset($_COOKIE['ProImageLogIn'])) {
    $User_session = unserialize(urldecode($_COOKIE['ProImageLogIn']));
    if ($User_session != false && is_numeric($User_session[1]) && $User_session[1] == "10") {
        session_start();
        $_SESSION['AdminLog'] = $User_session;
        setcookie("TestCookie", "Is Cookie");
        if (!isset($_COOKIE['TestCookie']))
            $token = "?token=" . session_id();
        else
            $token = "";

        setcookie("ProImageLogIn", urlencode(serialize($User_session)), time() + 60 * 60 * 24 * 7 * 4, '/', '.proimagesoftware.com');
        $GoTo = "/PhotoCP/cp.php" . $token;
        header(sprintf("Location: %s", $GoTo));
    }
} else if (isset($_GET['Error'])) {
    $Error = clean_variable($_GET['Error'], true);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
<? include $r_path . 'includes/_metadata.php'; ?>
        <link href="/css/ProImageSoftware_09.css" rel="stylesheet" type="text/css" media="screen" />
    </head>
    <body>
        <div id="Container">
<? include $r_path . 'includes/_navigation.php'; ?>
            <div id="Content">
                <script type="text/javascript">
                    AC_FL_RunContent('codebase', 'http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0', 'name', 'ProImageSoftwareHome', 'width', '100%', 'height', '496', 'hspace', '0', 'vspace', '0', 'id', 'ProImageSoftwareHome', 'title', 'Pro Image Software', 'src', '/flash/ProImageSoftware_1_2', 'quality', 'high', 'pluginspage', 'http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash', 'bgcolor', '#543b13', 'movie', '/flash/ProImageSoftware_1_2'); //end AC code
                </script>
                <noscript>
                    <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" name="ProImageSoftwareHome" width="100%" height="496" hspace="0" vspace="0" id="ProImageSoftwareHome" title="Pro Image Software">
                        <param name="movie" value="/flash/ProImageSoftware_1_2.swf" />
                        <param name="quality" value="high" />
                        <param name="BGCOLOR" value="#543b13" />
                        <embed src="/flash/ProImageSoftware_1_2.swf" width="100%" height="496" hspace="0" vspace="0" quality="high" pluginspage="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" name="ProImageSoftwareHome" bgcolor="#543b13"></embed>
                    </object>
                </noscript>
            </div>
<? include $r_path . 'includes/_footer.php'; ?>
        </div>
    </body>
</html>
