<?
$count = count(explode("/", substr($_SERVER['PHP_SELF'], 1))) - 2;
$r_path = "";
for ($n = 0; $n < $count; $n++) {
    $r_path .= "../";
}
define("PhotoExpress Pro", true);
define('Allow Scripts', true);
require_once($r_path . '../scripts/cart/ssl_paths.php');
require_once('../Connections/cp_connection.php');
include $r_path . 'scripts/a_admin_actions.php';
include 'config.php';
$attempt_timer = 30;
$attempt_number = 5;
$count = count(explode("/", substr($_SERVER['PHP_SELF'], 1))) - 2;
$r_path = "";
for ($n = 0; $n < $count; $n++) {
    $r_path .= "../";
}
if (isset($_POST['Controller']) && $_POST['Controller'] == "true") {

    // Check the password against customer id and password
    function checkpassword($User_id, $Pword) {
        global $cp_connection;

        $query_check_password = "SELECT `user_id` FROM `admin_users` WHERE `user_password` = '$Pword' AND `user_id` = '$User_id'";
        $check_password = mysql_query($query_check_password, $cp_connection) or die(mysql_error());
        $row_check_password = mysql_fetch_assoc($check_password);
        $totalRows_check_password = mysql_num_rows($check_password);

        // Return True or False
        return $totalRows_check_password;

        mysql_free_result($check_password);
    }

    // Set the number of tries in database against customer id
    function settime($try_num, $User_id) {
        global $cp_connection;
        global $exist_action;
        global $navcodes;

        $query_get_userinfo = "SELECT `user_password` FROM `admin_users` WHERE `user_id` = '$User_id'";
        $get_userinfo = mysql_query($query_get_userinfo, $cp_connection) or die(mysql_error());
        $row_get_userinfo = mysql_fetch_assoc($get_userinfo);
        $totalRows_get_userinfo = mysql_num_rows($get_userinfo);

        $UserName = $row_get_userinfo['user_password'];
        //$UserName = md5($UserName.time());
        $update = "UPDATE `admin_users` SET `login_try` = '$try_num' WHERE `user_id` = '$User_id'";
        $updateinfo = mysql_query($update, $cp_connection) or die(mysql_error());

        if ($try_num == 0) {
            $query_get_userinfo = "SELECT * FROM `admin_users` WHERE `user_id` = '$User_id'";
            $get_userinfo = mysql_query($query_get_userinfo, $cp_connection) or die(mysql_error());
            $row_get_userinfo = mysql_fetch_assoc($get_userinfo);

            $User_session = array();
            $User_session[0] = $UserName;
            $User_session[1] = $row_get_userinfo['user_level'];
            if ($User_session[1] == "0") {
                $User_session[2] = $navcodes;
            } else {
                $User_session[2] = unserialize(urldecode($row_get_userinfo['user_log']));
            }
            session_start();
            session_register('AdminLog');
            $_SESSION['AdminLog'] = $User_session;
            setcookie("TestCookie", "Is Cookie");
            if (!isset($_COOKIE['TestCookie'])) {
                $token = "?token=" . session_id();
            } else {
                $token = "";
            }
            $GoTo = "/control_panel/control_panel.php" . $token;
            header(sprintf("Location: %s", $GoTo));
        }
    }

    include $r_path . 'scripts/fnct_clean_entry.php';
    mysql_select_db($database_cp_connection, $cp_connection);
    $UName = clean_variable(trim($_POST['Username']), true);
    $message = false;

    // Check for username is database
    $query_check_username = "SELECT `user_id`, `last_login`, `login_try` FROM `admin_users` WHERE `user_username` = '$UName'";
    $check_username = mysql_query($query_check_username, $cp_connection) or die(mysql_error());
    $row_check_username = mysql_fetch_assoc($check_username);
    $totalRows_check_username = mysql_num_rows($check_username);

    // If Username exists
    if ($totalRows_check_username != 0) {
        // Get customer Id, password, and the last time database was accessed
        $User_id = $row_check_username['user_id'];
        $Pword = md5(clean_variable(trim($_POST['Password']), true));
        $lasttime = $row_check_username['last_login'];

        //$lasttime = "20060613155500";
        $lasttime = date("YmdHis", mktime(substr($lasttime, 8, 2), (substr($lasttime, 10, 2) + $attempt_timer), substr($lasttime, 12, 2), substr($lasttime, 4, 2), substr($lasttime, 6, 2), substr($lasttime, 0, 4)));
        $datetime = date("YmdHis");
        $num_try = $row_check_username['login_try'];

        // If number of tries to log in are less than 5
        if ($num_try < $attempt_number) {

            // Check the password
            $checkpass = checkpassword($User_id, $Pword);
            // If password is good go to customer check out
            if ($checkpass != 0) {
                settime(0, $User_id);
            } else {
                // If password fails then set number of tries plus 1
                settime($num_try + 1, $User_id);
                // If number of tries is equal to 5 suspend account
                if ($num_try + 1 == $attempt_number) {
                    $message = "Your account has been suspended for " . $attempt_timer . " minutes";
                } else {
                    $message = "We were unable to find your username or password.";
                }
            }
        } else {
            // If number of tries is equal to five check the last time the user attempted to log in.
            if ($lasttime <= $datetime) {
                // Check the password
                $checkpass = checkpassword($User_id, $Pword);
                // If password is good continue to member section
                if ($checkpass != 0) {
                    settime(0, $User_id);
                } else {
                    // Else set number of tries equal to 1;
                    settime(1, $User_id);
                    $message = "We were unable to find your username or password.";
                }
            } else {
                // If number of tries is equal to 5 then dispaly message
                $message = "Your account has been suspended for " . $attempt_timer . " minutes";
            }
        }
    } else {
        $message = "We were unable to find your username or password.";
    }
    mysql_free_result($check_username);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <link rel="shortcut icon" href="http://www.proimagesoftware.com/photoexpress.ico" type="image/x-icon">
            <link rel="icon" href="http://www.proimagesoftware.com/photoexpress.ico" type="image/x-icon">
                <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
                <meta name="robots" content="index,follow" />
                <meta name="keywords" content=""/>
                <meta name="language" content="english"/>
                <meta name="author" content="Pro Image Software" />
                <meta name="copyright" content="2007" />
                <meta name="reply-to" content="info@proimagesoftware.com" />
                <meta name="document-rights" content="Copyrighted Work" />
                <meta name="document-type" content="Web Page" />
                <meta name="document-rating" content="General" />
                <meta name="document-distribution" content="Global" />
                <meta http-equiv="expires" content="0" />
                <meta http-equiv="Pragma" content="no-cache" />
                <title>AevNet: Control Panel</title>
                <link href="/<? echo $AevNet_Path; ?>/stylesheet/AevNet.css" rel="stylesheet" type="text/css" />
                </head>
                <body class="bg_login">
                    <div id="Log_in_Container">
                        <div id="Log_in_Spacer"></div>
                        <div id="Log_in_Content">
                            <div id="Log_in_Form">
                                <div style="float:left; width:48%;">
                                    <div style="float:left"> <img src="/<? echo $AevNet_Path; ?>/images/log_in_banner.jpg" width="58" height="300" align="left" /></div>
                                    <div style="float:left">
                                        <p><img src="/<? echo $AevNet_Path; ?>/images/log_in_aevnet.jpg" width="157" height="90" /></p>
                                        <form action="<? echo $_SERVER['PHP_SELF']; ?>" name="LogIn_Form" id="LogIn_Form" method="post" enctype="multipart/form-data">
                                            <?
                                            if (isset($message)) {
                                                echo "<p>" . $message . "</p>";
                                            }
                                            ?>
                                            <p><img src="/<? echo $AevNet_Path; ?>/images/log_in_username.jpg" alt="Username" width="105" height="21" align="left" />
                                                <input name="Username" type="text" id="Username" size="15" style="width:100px;"/>
                                            </p>
                                            <p>&nbsp;</p>
                                            <p><img src="/<? echo $AevNet_Path; ?>/images/log_in_password.jpg" alt="Password" width="105" height="21" align="left" />
                                                <input name="Password" type="password" id="Password" size="15" />
                                            </p>
                                            <p>&nbsp;</p>
                                            <p id="Log_in_Login"><a href="javascript:document.getElementById('LogIn_Form').submit();" title="Login">Login</a></p>
                                            <input type="hidden" name="Controller" id="Controller" value="true" />
                                            <div style="height:1px; overflow:hidden"><br />
                                                <input type="submit" name="btn_submit" id="btn_submit" value="submit" />
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div style=" float:left; height:250px; border-right:#CCCCCC 1px solid; margin-top:25px; margin-bottom:25px; margin-left:10px; margin-right:10px;"></div>
                                <div style="float:right; width:48%; margin-top:50px;">
                                    <h1>Welcome to <br />
                                        PhotoExpress Digital Pro </h1>
                                </div>
                            </div>
                        </div>
                    </div>
                </body>
                </html>
