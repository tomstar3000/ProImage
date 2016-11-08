<?

$count = count(explode("/", substr($_SERVER['PHP_SELF'], 1))) - 1;
$r_path = "";
for ($n = 0; $n < $count; $n++)
    $r_path .= "../";
define("Allow Scripts", true);
define("CronJob", true);

set_time_limit(0);
ini_set('max_execution_time', 600);

$is_creditcard = true;
$is_gateway = "Authorize_ARB";
$is_live = true;
$is_error = false;
$is_process = true;
$is_capture = "STATUS";
$approval = false;
$Error = false;
if (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN')
    $eol = "\r\n"; else if (strtoupper(substr(PHP_OS, 0, 3)) == 'MAC')
    $eol = "\r";
else
    $eol = "\n";

//$r_path .= "var/www/www.proimagesoftware.com/";
$r_path = "/srv/proimage/current/";
require_once $r_path . 'scripts/cart/encrypt.php';
require_once $r_path . 'scripts/fnct_send_email.php';
require_once $r_path . 'scripts/fnct_clean_entry.php';
require_once $r_path . 'scripts/emogrifier.php';
require_once $r_path . 'scripts/fnct_phpmailer.php';
require_once $r_path . 'scripts/fnct_holidays.php';
require_once $r_path . 'scripts/fnct_ImgeProcessor.php';
require_once $r_path . 'Connections/cp_connection.php';

mysql_select_db($database_cp_connection, $cp_connection);
require_once($r_path . 'control_panel/scripts/query_change_list.php');
$today = date("Y-m-d H:i:s", mktime(0, 0, 0, date("m"), date("d"), date("Y")));
$tendate = date("Y-m-d H:i:s", mktime(0, 0, 0, date("m"), date("d") + 10, date("Y")));
$thirtydate = date("Y-m-d H:i:s", mktime(0, 0, 0, date("m") - 1, date("d") - 1, date("Y")));
$thirtydate1 = date("Y-m-d H:i:s", mktime(0, 0, 0, date("m") - 1, date("d"), date("Y")));
$date = date("Y-m-d H:i:s", mktime(12, 0, 0, date("m"), date("d"), date("Y")));
$date1 = date("Y-m-d H:i:s", mktime(12, 0, 0, date("m"), (date("d") - 1), date("Y"))); // Date of expired events
$date2 = date("Y-m-d H:i:s", mktime(12, 0, 0, date("m"), (date("d") + 2), date("Y"))); // Date of expired events
$date3 = date("Y-m-d H:i:s", mktime(12, 0, 0, date("m"), (date("d") + 3), date("Y"))); // Date of expired events
$date4 = date("Y-m-d H:i:s", mktime(12, 0, 0, date("m"), (date("d") + 4), date("Y"))); // Date of expired events
$date5 = date("Y-m-d H:i:s", mktime(12, 0, 0, date("m"), (date("d") + 5), date("Y"))); // Date of expired events
$finaldate = date("Y-m-d H:i:s", mktime(12, 0, 0, date("m"), (date("d") + 15), date("Y")));
$diedate = array();
for ($n = 2; $n <= 29; $n++) {
    array_push($diedate, date("Y-m-d H:i:s", mktime(12, 0, 0, date("m"), date("d") - $n, date("Y"))));
}

function FindCSS($CSSLink) {
    global $CSS, $Template, $r_path;
    $path_parts = pathinfo($CSSLink);
    //$path = $path_parts['basename'];
    //$path_parts = pathinfo($Template);
    $path = $path_parts['dirname'] . "/" . $path_parts['basename'];
    $Handle = fopen($r_path . $path, "r") or die("Failed Opening " . $r_path . $path);
    while (!feof($Handle))
        $CSS .= fread($Handle, 8192);
    fclose($Handle);
    return "";
}

function FindCSS2($StyleSheet) {
    global $CSS;
    $CSS .= $StyleSheet;
    return "";
}

function cleanUpHTML($text) {
    $text = ereg_replace(" style=[^>]*", "", $text);
    return ($text);
}

ob_start();

$accountNotices = array(
    '5days' => array(),
    '3days' => array(),
    'canceled' => array(),
    'renewed' => array(),
    'failed' => array(),
    'deliquint' => array(),
);

echo '5 Days Out<br />' . PHP_EOL;
$query_get_exp = "SELECT `cust_id`, `cust_service`, `cust_fname`, `cust_lname`, `cust_handle`, `cust_email`, `cust_fee`, cust_canceled, cust_subscription_number, cust_due_date FROM `cust_customers` WHERE `cust_active` = 'y' AND `cust_photo` = 'y' AND `cust_paid` = 'y' AND (`cust_due_date` != '0000-00-00 00:00:00' && cust_subscription_number IS NULL AND `cust_due_date` <= '$date5' AND `cust_due_date` > '$date4' AND cust_service IN ('344', '345', '347', '348') )";
$get_exp = mysql_query($query_get_exp, $cp_connection) or die(mysql_error());
while ($row_get_exp = mysql_fetch_assoc($get_exp)) {
    echo $row_get_exp['cust_handle'] . ": " . $row_get_exp['cust_fname'] . " " . $row_get_exp['cust_lname'];
    $approval = false;
    $reciever = "info@proimagesoftware.com";
    $Email = $row_get_exp['cust_email'];
    $subject = "Pro Image Software: Trial Notice";
    $CId = $row_get_exp['cust_id'];
    $subscription_id = $row_get_exp['cust_subscription_number'];
    $SvLvl = $row_get_exp['cust_service'];

    $accountNotices['5days'][] = $row_get_exp['cust_handle'] . ": " . $row_get_exp['cust_fname'] . " " . $row_get_exp['cust_lname'];

    $text = "<p>Your trial period is almost over, please log-in to your account and renew your account under the business section, to continue your experience with Pro Image Software." . $eol . $eol . " Sincerely Pro Image Software.</p>";

    ob_start();
    include($r_path . 'template_email_2.php');
    $page = ob_get_contents();
    ob_end_clean();

    $mail = new PHPMailer();
    $mail->IsSendMail();
    $mail->Host = "smtp.proimagesoftware.com";
    $mail->IsHTML(true);
    $mail->Sender = "info@proimagesoftware.com";
    $mail->Hostname = "proimagesoftware.com";
    $mail->From = $reciever;
    $mail->ReplyTo = $reciever;
    $mail->AddAddress($Email);
    $mail->Subject = $subject;
    $mail->Body = $page;
    $mail->Send();
}
echo '3 Days Out<br />' . PHP_EOL;
$query_get_exp = "SELECT `cust_id`, `cust_service`, `cust_fname`, `cust_lname`, `cust_handle`, `cust_email`, `cust_fee`, cust_canceled, cust_subscription_number, cust_due_date FROM `cust_customers` WHERE `cust_active` = 'y' AND `cust_photo` = 'y' AND `cust_paid` = 'y' AND (`cust_due_date` != '0000-00-00 00:00:00' && cust_subscription_number IS NULL AND `cust_due_date` <= '$date3' AND `cust_due_date` > '$date2' AND cust_service IN ('344', '345', '347', '348') )";
$get_exp = mysql_query($query_get_exp, $cp_connection) or die(mysql_error());
while ($row_get_exp = mysql_fetch_assoc($get_exp)) {
    echo $row_get_exp['cust_handle'] . ": " . $row_get_exp['cust_fname'] . " " . $row_get_exp['cust_lname'];
    $approval = false;
    $reciever = "info@proimagesoftware.com";
    $Email = $row_get_exp['cust_email'];
    $subject = "Pro Image Software: Trial Notice";
    $CId = $row_get_exp['cust_id'];
    $subscription_id = $row_get_exp['cust_subscription_number'];
    $SvLvl = $row_get_exp['cust_service'];

    $accountNotices['3days'][] = $row_get_exp['cust_handle'] . ": " . $row_get_exp['cust_fname'] . " " . $row_get_exp['cust_lname'];

    $text = "<p>Your trial period is almost over, please log-in to your account and renew your account under the business section, to continue your experience with Pro Image Software." . $eol . $eol . " Sincerely Pro Image Software.</p>";

    ob_start();
    include($r_path . 'template_email_2.php');
    $page = ob_get_contents();
    ob_end_clean();

    $mail = new PHPMailer();
    $mail->IsSendMail();
    $mail->Host = "smtp.proimagesoftware.com";
    $mail->IsHTML(true);
    $mail->Sender = "info@proimagesoftware.com";
    $mail->Hostname = "proimagesoftware.com";
    $mail->From = $reciever;
    $mail->ReplyTo = $reciever;
    $mail->AddAddress($Email);
    $mail->Subject = $subject;
    $mail->Body = $page;
    $mail->Send();
}

echo "Needs Processing<br />" . PHP_EOL;
$query_get_exp = "SELECT `cust_id`, `cust_service`, `cust_fname`, `cust_lname`, `cust_handle`, `cust_email`, `cust_fee`, cust_canceled, cust_subscription_number, cust_due_date, DATEDIFF(cust_due_date, cust_created) AS created_to_due  FROM `cust_customers` WHERE `cust_active` = 'y' AND `cust_photo` = 'y' AND `cust_paid` = 'y' AND `cust_hold` = 'n' AND (`cust_due_date` != '0000-00-00 00:00:00' && `cust_due_date` <= '$date1')";
$get_exp = mysql_query($query_get_exp, $cp_connection) or die(mysql_error());

while ($row_get_exp = mysql_fetch_assoc($get_exp)) {
    echo $row_get_exp['cust_handle'] . ": " . $row_get_exp['cust_fname'] . " " . $row_get_exp['cust_lname'];
    $approval = false;
    $reciever = "info@proimagesoftware.com";
    $Email = $row_get_exp['cust_email'];
    $subject = "Pro Image Software: Membership Renewal";
    $CId = $row_get_exp['cust_id'];
    $subscription_id = $row_get_exp['cust_subscription_number'];
    $SvLvl = $row_get_exp['cust_service'];

    if ($row_get_exp['cust_canceled'] == 'y') {
        $upd = "UPDATE `cust_customers` SET `cust_paid` = 'n', cust_active = 'n' WHERE `cust_id` = '$CId';";
        echo " - User Canceled:" . "<br />" . PHP_EOL;
        $updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());

        $accountNotices['canceled'][] = $row_get_exp['cust_handle'] . ": " . $row_get_exp['cust_fname'] . " " . $row_get_exp['cust_lname'];

        $text = "<p>Thank you for using ProImageSoftware.com. Sorry to see you go, your account has been de-activated, if at any time you would like to come back please log-in with your current username and password and renew your account." . $eol . $eol . " Sincerely Pro Image Software.</p>";

        ob_start();
        include($r_path . 'template_email_2.php');
        $page = ob_get_contents();
        ob_end_clean();

        $mail = new PHPMailer();
        $mail->IsSendMail();
        $mail->Host = "smtp.proimagesoftware.com";
        $mail->IsHTML(true);
        $mail->Sender = "info@proimagesoftware.com";
        $mail->Hostname = "proimagesoftware.com";
        $mail->From = $reciever;
        $mail->ReplyTo = $reciever;
        $mail->AddAddress($Email);
        $mail->Subject = str_replace("&amp;", "&", 'Pro Image Software: Membership Canceled');
        $mail->Body = $page;
        $mail->Send();

        continue;
    }

    $query_get_cost = "SELECT * FROM `prod_products` WHERE `prod_id` = '$SvLvl' LIMIT 0,1";
    $get_cost = mysql_query($query_get_cost, $cp_connection) or die(mysql_error());
    $row_get_cost = mysql_fetch_assoc($get_cost);
    $recurring = $row_get_cost['prod_year'];
    $current_due_date = strtotime($row_get_exp['cust_due_date']);
    $d = intval(date('j', $current_due_date), 10);
    $m = intval(date('n', $current_due_date), 10);
    $y = intval(date('Y', $current_due_date), 10);

    if ($recurring == "y") {
        $bill_date = date("Y-m-d h:i:s", mktime(0, 0, 0, $m, $d, $y + 1));
    } else {
        $bill_date = date("Y-m-d h:i:s", mktime(0, 0, 0, $m + 1, $d, $y));
    }
    $total = $row_get_cost['prod_price'];
    if ($row_get_exp['cust_fee'] != 0)
        $total = $row_get_exp['cust_fee'];
    $fee = $row_get_cost['prod_fee'];
    $fee = 0;
    //$grandtotal = $total+$fee;
    $grandtotal = $total;
    echo " - " . $row_get_cost['prod_name'] . " Charge: $" . number_format($total, 2, ".", ",");

    if ($grandtotal > 0) {
        if ($is_process) {
            if (empty($subscription_id) == true) {
                $query_get_billing = "SELECT * FROM `cust_billing` WHERE `cust_id` = '$CId' LIMIT 0,1";
                $get_billing = mysql_query($query_get_billing, $cp_connection) or die(mysql_error());
                $row_get_billing = mysql_fetch_assoc($get_billing);
                $total_get_billing = mysql_num_rows($get_billing);

                if ($total_get_billing > 0 && !empty($CCNum)) {
                    $BId = $row_get_billing['cust_id'];
                    $BFName = $row_get_billing['cust_bill_fname'];
                    $BMName = $row_get_billing['cust_bill_mname'];
                    $BLName = $row_get_billing['cust_bill_lname'];
                    $BSuffix = $row_get_billing['cust_bill_suffix'];
                    $BCName = $row_get_billing['cust_bill_cname'];
                    $BAdd = $row_get_billing['cust_bill_add'];
                    $BSuiteApt = $row_get_billing['cust_bill_suite_apt'];
                    $BAdd2 = $row_get_billing['cust_bill_add_2'];
                    $BCity = $row_get_billing['cust_bill_city'];
                    $BState = $row_get_billing['cust_bill_state'];
                    $BZip = $row_get_billing['cust_bill_zip'];
                    $BCount = $row_get_billing['cust_bill_counry'];
                    $CCNum = $row_get_billing['cust_bill_ccnum'];
                    $CC4Num = $row_get_billing['cust_bill_ccshort'];
                    $CType = $row_get_billing['cust_bill_cc_type_id'];
                    $CCV = $row_get_billing['cust_bill_ccv'];
                    $CCM = $row_get_billing['cust_bill_exp_month'];
                    $CCY = $row_get_billing['cust_bill_year'];

                    $is_gateway = "Authorize_AIM";
                    $is_capture = "AUTH_CAPTURE";
                    $is_method = "CC";
                    include ($r_path . 'scripts/cart/merchant_ini.php');

                    $upd = "UPDATE `cust_billing` SET `cust_bill_ccnum` = 0, `cust_bill_ccv` = 0, cust_bill_exp_month = 0, cust_bill_year = 0 WHERE `cust_id` = '$CId';";
                    $updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());

                    if ($approval == true) {
                        if ($recurring == "y") {
                            $ReNewInterval = 12;
                        } else {
                            $ReNewInterval = 1;
                        }
                        $ReNewUnit = 'months';
                        $ReNewTotalOccurance = 9999;
                        $ReNewStart = date("Y-m-d", strtotime($bill_date));

                        $is_gateway = "Authorize_ARB";
                        $is_capture = "SUBSCRIBE";
                        include ($r_path . 'scripts/cart/merchant_ini.php');

                        var_dump($subscription_id);

                        $upd = "UPDATE cust_customers SET cust_subscription_number = '$subscription_id' WHERE `cust_id` = '$CId';";
                        $updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());
                    }

                    unset($BId);
                    unset($BFName);
                    unset($BMName);
                    unset($BLName);
                    unset($BSuffix);
                    unset($BCName);
                    unset($BAdd);
                    unset($BSuiteApt);
                    unset($BAdd2);
                    unset($BCity);
                    unset($BState);
                    unset($BZip);
                    unset($BCount);
                    unset($CCNum);
                    unset($CC4Num);
                    unset($CCV);
                    unset($CType);
                    unset($CCM);
                    unset($CCY);
                } else {
                    $approval = false;
                    $message = "We were unable to find your records.";
                }
            } else {
                $is_gateway = "Authorize_ARB";
                $is_capture = "STATUS";
                include ($r_path . 'scripts/cart/merchant_ini.php');
                switch (strtolower($status)) {
                    case 'active':
                        $approval = true;
                        break;
                    default:
                        $approval = false;
                        break;
                }
            }
        } else {
            $approval = true;
        }
    } else {
        $approval = true;
    }

    if ($approval == true) {
        switch ($SvLvl) {
            case 9:
            case 328:
                $rev = 15;
                break;
            case 10:
            case 329:
                $rev = 10;
                break;
            case 11:
                $rev = 7;
                break;
            case 344:
                $rev = 17;
                break;
            case 345:
                $rev = 12;
                break;
            case 347:
                $rev = 9;
                break;
            case 348:
                $rev = 9;
                break;
            default:
                $rev = 9;
                break;
        }

        $query_get_last = "SELECT `invoice_id` AS `invoice_num` FROM `orders_invoice` ORDER BY `invoice_id` DESC LIMIT 0,1";
        $get_last = mysql_query($query_get_last, $cp_connection) or die(mysql_error());
        $row_get_last = mysql_fetch_assoc($get_last);
        $inv_num = ($row_get_last['invoice_num'] + 101) . date("Ymd");

        $accountNotices['renewed'][] = $row_get_exp['cust_handle'] . ": " . $row_get_exp['cust_fname'] . " " . $row_get_exp['cust_lname'];

        echo " - Passed: " . $inv_num . "<br />" . PHP_EOL;

        $inv_enc = md5($inv_num);
        mysql_free_result($get_last);

        $add = "INSERT INTO `orders_invoice` (`cust_id`,`cust_bill_id`,`invoice_num`,`invoice_enc`,`invoice_rev`,`invoice_total`,`invoice_date`,`invoice_transaction`,`invoice_paid`,`invoice_paid_date`,`invoice_comp`,`invoice_comp_date`,`invoice_online`) VALUES ('$CId', 0,'$inv_num','$inv_enc','$rev','$total',NOW(),'$tran_id','y',NOW(),'y',NOW(),'y');";
        $addinfo = mysql_query($add, $cp_connection) or die(mysql_error());

        $query_get_last = "SELECT `invoice_id` FROM `orders_invoice` WHERE `cust_id` = '$CId' ORDER BY `invoice_id` DESC LIMIT 0,1";
        $get_last = mysql_query($query_get_last, $cp_connection) or die(mysql_error());
        $row_get_last = mysql_fetch_assoc($get_last);
        $inv_id = $row_get_last['invoice_id'];
        mysql_free_result($get_last);

        $add = "INSERT INTO `orders_invoice_prod` (`invoice_id`,`prod_id`,`invoice_prod_sale`,`invoice_prod_price`,`invoice_prod_fee`,`invoice_prod_qty`) VALUES ('$inv_id','$SvLvl','n','$total','$fee','1');";
        $addinfo = mysql_query($add, $cp_connection) or die(mysql_error());

        $upd = "UPDATE `cust_customers` SET `cust_due_date` = '$bill_date', `cust_paid` = 'y' WHERE `cust_id` = '$CId';";
        $updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());

        $text = "<p>Thank you for contining your service with ProImageSoftware.com. We have charged $" . number_format($total, 2, ".", ",") . " to your credit card ending in " . $CC4Num . $eol . $eol . " Sincerely Pro Image Software.</p>";
        if ($grandtotal > 0) {
            ob_start();
            include($r_path . 'template_email_2.php');
            $page = ob_get_contents();
            ob_end_clean();

            $mail = new PHPMailer();
            $mail->IsSendMail();
            $mail->Host = "smtp.proimagesoftware.com";
            $mail->IsHTML(true);
            $mail->Sender = "info@proimagesoftware.com";
            $mail->Hostname = "proimagesoftware.com";
            $mail->From = $reciever;
            $mail->ReplyTo = $reciever;
            $mail->AddAddress($Email);
            $mail->Subject = str_replace("&amp;", "&", $subject);
            $mail->Body = $page;
            $mail->Send();

            $mail = new PHPMailer();
            $mail->IsSendMail();
            $mail->Host = "smtp.proimagesoftware.com";
            $mail->IsHTML(true);
            $mail->Sender = "info@proimagesoftware.com";
            $mail->Hostname = "proimagesoftware.com";
            $mail->From = $reciever;
            $mail->ReplyTo = $reciever;
            $mail->AddAddress("development@proimagesoftware.com");
            $mail->AddAddress("info@proimagesoftware.com");
            $mail->Subject = str_replace("&amp;", "&", $subject);
            $mail->Body = $page;
            $mail->Send();
        }
    } else {
        $upd = "UPDATE `cust_customers` SET `cust_paid` = 'n' WHERE `cust_id` = '$CId';";
        echo " - Failed: " . $responce[0] . " - " . $error . " - " . $message . "<br />";
        $updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());

        $accountNotices['failed'][] = $row_get_exp['cust_handle'] . ": " . $row_get_exp['cust_fname'] . " " . $row_get_exp['cust_lname'];

        if (in_array($SvLvl, array('344', '345', '347', '348')) && $row_get_exp['created_to_due'] <= 46) {
            $text = "<p>Your Trial period has ended.  I you would like to continue using ProImage Software update your billing information by logging into the control panel. Then click the renew button to renew your plan." . $eol . $eol . "Sincerely Pro Image Software.</p>";
        } else {
            $text = "<p>There was a problem renewing your account." . $eol . $eol . $message . $eol . $eol . "Please update your billing information by logging into the control panel. Then click the renew button to renew your plan." . $eol . $eol . "Sincerely Pro Image Software.</p>";
        }

        ob_start();
        include($r_path . 'template_email_2.php');
        $page = ob_get_contents();
        ob_end_clean();

        $mail = new PHPMailer();
        $mail->IsSendMail();
        $mail->Host = "smtp.proimagesoftware.com";
        $mail->IsHTML(true);
        $mail->Sender = "info@proimagesoftware.com";
        $mail->Hostname = "proimagesoftware.com";
        $mail->From = $reciever;
        $mail->ReplyTo = $reciever;
        $mail->AddAddress($Email);
        $mail->Subject = str_replace("&amp;", "&", $subject);
        $mail->Body = $page;
        $mail->Send();

        $mail = new PHPMailer();
        $mail->IsSendMail();
        $mail->Host = "smtp.proimagesoftware.com";
        $mail->IsHTML(true);
        $mail->Sender = "info@proimagesoftware.com";
        $mail->Hostname = "proimagesoftware.com";
        $mail->From = $reciever;
        $mail->ReplyTo = $reciever;
        $mail->AddAddress("info@proimagesoftware.com");
        $mail->AddAddress("development@proimagesoftware.com");
        $mail->Subject = str_replace("&amp;", "&", $subject);
        $mail->Body = $page;
        $mail->Send();
    }
    if (isset($subscription_id))
        unset($subscription_id);
    if (isset($status))
        unset($status);
    unset($reciever);
    unset($Email);
    unset($subject);
    unset($text);
    unset($page);
    sleep(2);
}
/* Notify accounts that are deliquint that they are and they need to pay */

echo "Deliquint Accounts<br />" . PHP_EOL;
$query_get_exp = "SELECT `cust_id`, `cust_service`, `cust_fname`, `cust_lname`, `cust_handle`, `cust_email` FROM `cust_customers` WHERE `cust_active` = 'y' AND `cust_photo` = 'y' AND `cust_paid` = 'n' AND `cust_hold` = 'n' AND `cust_due_date` <= '$finaldate'";
$get_exp = mysql_query($query_get_exp, $cp_connection) or die(mysql_error());

while ($row_get_exp = mysql_fetch_assoc($get_exp)) {
    $reciever = "info@proimagesoftware.com";
    $Email = $row_get_exp['cust_email'];
    $subject = "Pro Image Software: Membership Renewal";
    $CId = $row_get_exp['cust_id'];
    $upd = "UPDATE `cust_customers` SET `cust_active` = 'n' WHERE `cust_id` = '$CId';";
    $updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());
    echo $row_get_exp['cust_handle'] . ": " . $row_get_exp['cust_fname'] . " " . $row_get_exp['cust_lname'] . "<br />";

    $accountNotices['deliquint'][] = $row_get_exp['cust_handle'] . ": " . $row_get_exp['cust_fname'] . " " . $row_get_exp['cust_lname'];

    $text = "<p>Your account is 15 days over due and has been suspended.  If you would like to continue using our services please update your billing information and click the renew button." . $eol . $eol . "Sincerely Pro Image Software.</p>";

    ob_start();
    include($r_path . 'template_email_2.php');
    $page = ob_get_contents();
    ob_end_clean();

    // $page = str_replace('=','=3D',$page);

    $mail = new PHPMailer();
    $mail->IsSendMail();
    $mail->Host = "smtp.proimagesoftware.com";
    $mail->IsHTML(true);
    $mail->Sender = "info@proimagesoftware.com";
    $mail->Hostname = "proimagesoftware.com";
    $mail->From = $reciever;
    $mail->ReplyTo = $reciever;
    $mail->AddAddress($Email);
    $mail->Subject = str_replace("&amp;", "&", $subject);
    $mail->Body = $page;
    $mail->Send();

    unset($reciever);
    unset($Email);
    unset($subject);
    unset($CId);
    unset($upd);
    unset($text);
    unset($page);
}

$report = 'Trial Membership: 5 days left' . $eol;
foreach ($accountNotices['5days'] as $account) {
    $report .= $account . $eol;
}
$report .= $eol . 'Trial Membership: 3 days left' . $eol;
foreach ($accountNotices['3days'] as $account) {
    $report .= $account . $eol;
}
$report .= $eol . 'Canceled Membership:' . $eol;
foreach ($accountNotices['canceled'] as $account) {
    $report .= $account . $eol;
}
$report .= $eol . 'Renewed Membership:' . $eol;
foreach ($accountNotices['renewed'] as $account) {
    $report .= $account . $eol;
}
$report .= $eol . 'Failed on Renew Membership:' . $eol;
foreach ($accountNotices['failed'] as $account) {
    $report .= $account . $eol;
}
$report .= $eol . 'Deliquint Membership:' . $eol;
foreach ($accountNotices['deliquint'] as $account) {
    $report .= $account . $eol;
}
$mail = new PHPMailer();
$mail->IsSendMail();
$mail->Host = "smtp.proimagesoftware.com";
$mail->IsHTML(true);
$mail->Sender = "info@proimagesoftware.com";
$mail->Hostname = "proimagesoftware.com";
$mail->From = "info@proimagesoftware.com";
$mail->ReplyTo = "info@proimagesoftware.com";
$mail->AddAddress('benjamin@alivestudios.com');
$mail->Subject = 'Renewel Report';
$mail->Body = $report;
$mail->Send();

/*

  Event nofication for guestbook from photographer settings

 */

echo "Event Notifications<br />";
$query_get_exp = "SELECT `photo_event`.`event_id`, `photo_event`.`event_name`, `photo_event`.`event_num`, 
            `photo_event`.`owner_email`, `cust_customers`.`cust_handle`, `cust_customers`.`cust_cname`, 
            `cust_customers`.`cust_fname`, `cust_customers`.`cust_lname`, `cust_customers`.`cust_email`, 
            `cust_customers`.`cust_city`, `cust_customers`.`cust_state`, `cust_customers`.`cust_zip`, `cust_customers`.`cust_website`,
            `cust_customers`.`cust_thumb`, `photo_event_images`.`image_tiny` , `photo_event_images`.`image_folder`, `photo_event_notes`.`event_message`,
            `photo_event_notes`.`event_image`, 
            ABS(TO_DAYS(`photo_event`.`event_end`) - TO_DAYS(NOW())) AS `EndToDay`, 
            ABS(TO_DAYS(`photo_event`.`event_date`) - TO_DAYS(NOW())) AS `StartToDay`, 
            ABS(TO_DAYS(`photo_event_notes`.`event_date`) - TO_DAYS(NOW())) AS `TestToday`
	FROM `photo_event_notes` 
        INNER JOIN photo_event_note_events
            ON (photo_event_note_events.event_note_id = photo_event_notes.event_note_id)	
	INNER JOIN `photo_event` 
            ON `photo_event`.`event_id` = `photo_event_note_events`.`event_id` 
	INNER JOIN `cust_customers` 
            ON `cust_customers`.`cust_id` = `photo_event`.`cust_id` 
	LEFT JOIN `photo_event_images` 
            ON (`photo_event_images`.`image_id` = `photo_event`.`event_image`
                OR `photo_event_images`.`image_id` IS NULL)
	INNER JOIN `photo_quest_book` 
            ON `photo_quest_book`.`event_id` = `photo_event`.`event_id` 
	WHERE photo_event_notes.cust_id != 0
            AND `promotion` = 'y' 
            AND `event_use` = 'y'
            AND ((`photo_event_notes`.`event_before` = 'b' 
                    AND `photo_event_notes`.`event_days` = ABS(TO_DAYS(`photo_event`.`event_date`) - TO_DAYS(NOW())) 
                    AND NOW() <= `photo_event`.`event_date`)
                OR (`photo_event_notes`.`event_before` = 's' 
                    AND `photo_event_notes`.`event_days` = ABS(TO_DAYS(`photo_event`.`event_date`) - TO_DAYS(NOW())) 
                    AND NOW() >= `photo_event`.`event_date`)
                OR (`photo_event_notes`.`event_before` = 'e' 
                    AND `photo_event_notes`.`event_days` = ABS(TO_DAYS(`photo_event`.`event_end`) - TO_DAYS(NOW())))
                OR (`photo_event_notes`.`event_date` != '0000-00-00 00:00:00' 
                    AND `photo_event_notes`.`event_before` NOT IN ('b','s','e')
                    AND ABS(TO_DAYS(`photo_event_notes`.`event_date`) - TO_DAYS(NOW())) = 0))
        GROUP BY `photo_event_notes`.`event_note_id`, `photo_event`.`event_id`;";
$get_exp = mysql_query($query_get_exp, $cp_connection) or die(mysql_error());
$total_get_exp = mysql_num_rows($get_exp);

if ($total_get_exp > 0) {
    while ($row_get_exp = mysql_fetch_assoc($get_exp)) {
        $EId = $row_get_exp['event_id'];
        $Handle = $row_get_exp['cust_handle'];
        $Event = $row_get_exp['event_num'];
        $address = (strlen(trim($row_get_exp['cust_city'])) > 0) ? $row_get_exp['cust_city'] . " " . $row_get_exp['cust_state'] . " " . $row_get_exp['cust_zip'] : '';
        $website = (strlen(trim($row_get_exp['cust_website'])) > 0) ? $row_get_exp['cust_website'] : '';
        $Photographer = ($row_get_exp['cust_cname'] == "") ? $row_get_exp['cust_fname'] . " " . $row_get_exp['cust_lname'] : $row_get_exp['cust_cname'];
        $EName = $row_get_exp['event_name'];
        $PhotoEmail = $row_get_exp['cust_email'];
        $BioImage = "Logo.jpg";
        $Desc = $row_get_exp['event_message'];

        $BioImage = false;
        $IName = $row_get_exp['image_tiny'];
        $file = $r_path . substr($row_get_exp['image_folder'], 0, -11) . "Large/" . $IName;

        $Imager = new ImageProcessor();
        $Imager->SetMaxSize(67108864);
        $Imager->File($file);
        if ($Imager->ERROR == false) {
            $Imager->Resize(145, 145);
            if (intval($rotate) > 0)
                $Imager->Rotate((intval($rotate) * -1));
            if ($color == 'b')
                $Imager->Gray();
            else if ($color == 's')
                $Imager->Sepia();
            $fileType = $Imager->Ext;

            $Image = tempnam("/tmp", "FOO");

            $Hndl = fopen($Image, "w");
            fwrite($Hndl, $Imager->OutputContents());
            fclose($Hndl);
        } else {
            $IName = false;
            $Image = false;
        }

        $query_get_book = "SELECT `email` FROM `photo_quest_book` WHERE `event_id` = '$EId' AND `promotion` = 'y' GROUP BY `email`";
        $get_book = mysql_query($query_get_book, $cp_connection) or die(mysql_error());
        $bcc = array();
        while ($row_get_book = mysql_fetch_assoc($get_book))
            array_push($bcc, $row_get_book['email']);
        //array_push($bcc,"development@proimagesoftware.com");

        echo $Handle . " - " . $row_get_exp['event_name'] . " - " . $Image . "<br />";

        $title = mb_convert_encoding($EName, "UTF-8", 'HTML-ENTITIES');

        ob_start();
        include($r_path . 'Templates/Photographer/index.php');
        $msg = ob_get_contents();
        ob_end_clean();

        $Time = time();

        if ($Image !== false) {
            $msg = str_replace('[EmbedImg]', '<img class="img" src="cid:' . $Time . '" width="' . $Imager->OrigWidth[0] . '" height="' . $Imager->OrigHeight[0] . '" alt="' . $Handle . '" vspace="0" hspace="0" alt="' . $title . '" style="border: 3px solid #c89441;"><br clear="all" />', $msg);
        } else {
            $msg = str_replace('[EmbedImg]', '', $msg);
        }

        $msg = str_replace('[Text]', '<p>' . ereg_replace(array("^<p>", "</p>$"), '', sanatizeentry($Desc, true)) . '</p>', $msg);
        $msg = str_replace('[Title]', $title, $msg);
        $msg = str_replace('[Unsubscribe]', "http://www.proimagesoftware.com/unsubscribe.php?token=" . substr(time(), 0, 5) . $EId . substr(time(), 5), $msg);
        $msg = str_replace('[Photographer]', $Photographer, $msg);
        $msg = str_replace('[Address]', ((strlen(trim($address)) > 0) ? '<br />' . $address : ''), $msg);
        $msg = str_replace('[Website]', ((strlen(trim($website)) > 0) ? '<br /><a href="' . $website . '" title="' . $website . '">' . $website . '</a>' : ''), $msg);
        $msg = str_replace('[Coupons]', '', $msg);

        $Pattern = array();
        $Pattern[] = '/<link\s[^>]*href=(["\']??)([^"\'>]*?)\\1[^>]*rel="stylesheet"(.*)>/eiU';
        $Pattern[] = '@<style[^>]*?>.*?</style>@esiU';

        $CSS = "";
        $msg = preg_replace($Pattern[0], "FindCSS('$2')", $msg);
        $msg = preg_replace($Pattern[1], "FindCSS2('$0')", $msg);

        $InlineHTML = new Emogrifier();
        $InlineHTML->setHTML($msg);
        $InlineHTML->setCSS($CSS);

        $msg = removeSpecial($msg);
        $msg = $InlineHTML->emogrify();
        $msg = clean_html_code($msg);

        while (strpos("\r", $msg) !== false || strpos("\n", $msg) !== false || strpos("\r\n", $msg) !== false || strpos("\n\r", $msg) !== false) {
            $msg = trim(str_replace(array("\r", "\n", "\r\n", "\n\r"), "", $msg));
        }

        $msg = preg_replace("/ +/", " ", $msg);
        $msg = str_replace('href= "', 'href="', $msg);
        $msg = str_replace('src="/', 'src="http://www.proimagesoftware.com/', $msg);
        $msg = str_replace('url(/', 'url(http://www.proimagesoftware.com/', $msg);

        foreach ($bcc as $v) {
            $msg2 = str_replace('[Name]', $v, $msg);
            $msg2 = str_replace(array('[Link]', '%5BLink%5D'), "http://www.proimagesoftware.com/photo_viewer.php?Photographer=" . $Handle . "&code=" . $Event . "&email=" . $v . "&full=true", $msg2);
            $msg2 = str_replace(array('[Link2]', '%5BLink2%5D'), wordwrap("http://www.proimagesoftware.com/photo_viewer.php?Photographer=" . $Handle . "&code=" . $Event . "&email=" . $v . "&full=true", 100, $eol), $msg2);

            echo "Testing image embedding<br />" . $msg2 . "<br />";

            $mail = new PHPMailer();
            $mail->IsSendMail();
            $mail->Host = "smtp.proimagesoftware.com";
            $mail->IsHTML(true);
            $mail->Sender = "info@proimagesoftware.com";
            $mail->Hostname = "proimagesoftware.com";
            $mail->From = $PhotoEmail;
            $mail->FromName = $Photographer;
            $mail->ReplyTo = $PhotoEmail;
            $mail->AddAddress($v);
            //$mail -> AddAddress($row_get_exp['owner_email']);
            //if(count($bcc) > 0) foreach($bcc as $v) $mail -> AddBCC($v);
            $mail->Subject = str_replace("&amp;", "&", $title);
            if ($Image !== false) {
                $mail->AddEmbeddedImage($Image, $Time);
            }
            $mail->Body = $msg2;
            $mail->Send();
        }

        $Emailsent = true;
        $BioImage = false;
        if ($Image != false)
            unlink($Image);

        unset($Handle);
        unset($title);
        unset($Image);
        unset($IName);
        unset($address);
        unset($website);
        unset($EName);
        unset($BioImage);
        unset($Handle);
        unset($Event);
        unset($Desc);
        unset($Photographer);

        sleep(2);
    }
}

/*

  Event nofication for guestbook on when event was launched.

 */

echo "Event Release E-mail<br />";
$query_get_exp = "SELECT `photo_event`.`event_id`, `photo_event`.`event_name`, `photo_event`.`event_num`, `photo_event`.`owner_email`, `cust_customers`.`cust_handle`, `cust_customers`.`cust_cname`, `cust_customers`.`cust_fname`, `cust_customers`.`cust_lname`, `cust_customers`.`cust_email`, `cust_customers`.`cust_city`, `cust_customers`.`cust_state`, `cust_customers`.`cust_zip`, `cust_customers`.`cust_website`, `cust_customers`.`cust_thumb`, `photo_event_images`.`image_tiny` , `photo_event_images`.`image_folder`,  `photo_email_messages`.`email_message`,
ABS(TO_DAYS(`photo_event`.`event_end`) - TO_DAYS(NOW())) AS `EndToDay`, 
ABS(TO_DAYS(`photo_event`.`event_date`) - TO_DAYS(NOW())) AS `StartToDay`
	FROM `photo_event` 
	INNER JOIN `cust_customers` 
		ON `cust_customers`.`cust_id` = `photo_event`.`cust_id` 
	LEFT JOIN `photo_event_images` 
		ON (`photo_event_images`.`image_id` = `photo_event`.`event_image`
			OR `photo_event_images`.`image_id` IS NULL)
	INNER JOIN `photo_quest_book` 
		ON `photo_quest_book`.`event_id` = `photo_event`.`event_id`
	INNER JOIN `photo_email_messages`
		ON `photo_email_messages`.`email_msg_id` = '1'
	WHERE `promotion` = 'y' 
		AND `event_use` = 'y'
		AND ABS(TO_DAYS(`photo_event`.`event_date`) - TO_DAYS(NOW())) = '1'
	GROUP BY `photo_event`.`event_id`;";
$get_exp = mysql_query($query_get_exp, $cp_connection) or die(mysql_error());
$total_get_exp = mysql_num_rows($get_exp);

if ($total_get_exp > 0) {
    while ($row_get_exp = mysql_fetch_assoc($get_exp)) {
        $EId = $row_get_exp['event_id'];
        $Handle = $row_get_exp['cust_handle'];
        $Event = $row_get_exp['event_num'];
        $address = (strlen(trim($row_get_exp['cust_city'])) > 0) ? $row_get_exp['cust_city'] . " " . $row_get_exp['cust_state'] . " " . $row_get_exp['cust_zip'] : '';
        $website = (strlen(trim($row_get_exp['cust_website'])) > 0) ? $row_get_exp['cust_website'] : '';
        $Photographer = ($row_get_exp['cust_cname'] == "") ? $row_get_exp['cust_fname'] . " " . $row_get_exp['cust_lname'] : $row_get_exp['cust_cname'];
        $EName = $row_get_exp['event_name'];
        $PhotoEmail = $row_get_exp['cust_email'];
        $BioImage = "Logo.jpg";
        $Desc = $row_get_exp['email_message'];
        $Owner = $row_get_exp['owner_email'];

        $BioImage = false;
        $IName = $row_get_exp['image_tiny'];
        $file = $r_path . substr($row_get_exp['image_folder'], 0, -11) . "Large/" . $IName;

        $Imager = new ImageProcessor();
        $Imager->SetMaxSize(67108864);
        $Imager->File($file);
        if ($Imager->ERROR == false) {
            $Imager->Resize(145, 145);
            if (intval($rotate) > 0)
                $Imager->Rotate((intval($rotate) * -1));
            if ($color == 'b')
                $Imager->Gray();
            else if ($color == 's')
                $Imager->Sepia();
            $fileType = $Imager->Ext;

            $Image = tempnam("/tmp", "FOO");

            $Hndl = fopen($Image, "w");
            fwrite($Hndl, $Imager->OutputContents());
            fclose($Hndl);
        } else {
            $IName = false;
            $Image = false;
        }

        $query_get_book = "SELECT `email` FROM `photo_quest_book` WHERE `event_id` = '$EId' AND `promotion` = 'y' GROUP BY `email`";
        $get_book = mysql_query($query_get_book, $cp_connection) or die(mysql_error());
        $bcc = array();
        while ($row_get_book = mysql_fetch_assoc($get_book))
            array_push($bcc, $row_get_book['email']);
        array_push($bcc, $Owner);

        echo $Handle . " - " . $row_get_exp['event_name'] . " - " . $Image . "<br />";

        $title = mb_convert_encoding($EName, "UTF-8", 'HTML-ENTITIES');

        ob_start();
        include($r_path . 'Templates/Photographer/index.php');
        $msg = ob_get_contents();
        ob_end_clean();

        $Time = time();

        if ($Image !== false) {
            $msg = str_replace('[EmbedImg]', '<img class="img" src="cid:' . $Time . '" width="' . $Imager->OrigWidth[0] . '" height="' . $Imager->OrigHeight[0] . '" alt="' . $Handle . '" vspace="0" hspace="0" alt="' . $title . '" style="border: 3px solid #c89441;"><br clear="all" />', $msg);
        } else {
            $msg = str_replace('[EmbedImg]', '', $msg);
        }

        $msg = str_replace('[Text]', '<p>' . ereg_replace(array("^<p>", "</p>$"), '', sanatizeentry($Desc, true)) . '</p>', $msg);
        $msg = str_replace('[Title]', $title, $msg);
        $msg = str_replace('[Unsubscribe]', "http://www.proimagesoftware.com/unsubscribe.php?token=" . substr(time(), 0, 5) . $EId . substr(time(), 5), $msg);
        $msg = str_replace('[Photographer]', $Photographer, $msg);
        $msg = str_replace('[Address]', ((strlen(trim($address)) > 0) ? '<br />' . $address : ''), $msg);
        $msg = str_replace('[Website]', ((strlen(trim($website)) > 0) ? '<br /><a href="' . $website . '" title="' . $website . '">' . $website . '</a>' : ''), $msg);
        $msg = str_replace('[Coupons]', '', $msg);

        $Pattern = array();
        $Pattern[] = '/<link\s[^>]*href=(["\']??)([^"\'>]*?)\\1[^>]*rel="stylesheet"(.*)>/eiU';
        $Pattern[] = '@<style[^>]*?>.*?</style>@esiU';

        $CSS = "";
        $msg = preg_replace($Pattern[0], "FindCSS('$2')", $msg);
        $msg = preg_replace($Pattern[1], "FindCSS2('$0')", $msg);

        $InlineHTML = new Emogrifier();
        $InlineHTML->setHTML($msg);
        $InlineHTML->setCSS($CSS);

        $msg = removeSpecial($msg);
        $msg = $InlineHTML->emogrify();
        $msg = clean_html_code($msg);

        while (strpos("\r", $msg) !== false || strpos("\n", $msg) !== false || strpos("\r\n", $msg) !== false || strpos("\n\r", $msg) !== false) {
            $msg = trim(str_replace(array("\r", "\n", "\r\n", "\n\r"), "", $msg));
        }

        $msg = preg_replace("/ +/", " ", $msg);
        $msg = str_replace('href= "', 'href="', $msg);
        $msg = str_replace('src="/', 'src="http://www.proimagesoftware.com/', $msg);
        $msg = str_replace('url(/', 'url(http://www.proimagesoftware.com/', $msg);

        foreach ($bcc as $v) {
            $msg2 = str_replace('[Name]', $v, $msg);
            $msg2 = str_replace(array('[Link]', '%5BLink%5D'), "http://www.proimagesoftware.com/photo_viewer.php?Photographer=" . $Handle . "&code=" . $Event . "&email=" . $v . "&full=true", $msg2);
            $msg2 = str_replace(array('[Link2]', '%5BLink2%5D'), wordwrap("http://www.proimagesoftware.com/photo_viewer.php?Photographer=" . $Handle . "&code=" . $Event . "&email=" . $v . "&full=true", 100, $eol), $msg2);

            echo "Testing image embedding<br />" . $msg2 . "<br />";

            $mail = new PHPMailer();
            $mail->IsSendMail();
            $mail->Host = "smtp.proimagesoftware.com";
            $mail->IsHTML(true);
            $mail->Sender = "info@proimagesoftware.com";
            $mail->Hostname = "proimagesoftware.com";
            $mail->From = $PhotoEmail;
            $mail->FromName = $Photographer;
            $mail->ReplyTo = $PhotoEmail;
            //$mail -> AddAddress($row_get_exp['owner_email']);
            $mail->AddAddress($v);
            //if(count($bcc) > 0) foreach($bcc as $v) $mail -> AddBCC($v);
            $mail->Subject = str_replace("&amp;", "&", $title);
            if ($Image !== false)
                $mail->AddEmbeddedImage($Image, $Time);
            $mail->Body = $msg2;
            $mail->Send();
        }

        $Emailsent = true;
        $BioImage = false;
        if ($Image != false)
            unlink($Image);

        unset($Handle);
        unset($title);
        unset($Image);
        unset($IName);
        unset($address);
        unset($website);
        unset($EName);
        unset($Owner);
        unset($BioImage);
        unset($Handle);
        unset($Event);
        unset($Desc);
        unset($Photographer);

        sleep(2);
    }
}

/* Event Marketing

  SQL statement calculates the event marketing requirements based off event preferances and sends out an e-mail to everyone in the guestbook

 */
echo "Event Marketing<br />";

$Holidays = new CalcHoliday;
$Hol = $Holidays->isHoliday(date("Y-m-d", mktime(0, 0, 0, date("m"), (date("d") + 1), date("Y"))));
$BlackF = $Holidays->findHoliday('Black Friday', date("Y"));

$query_get_exp = "SELECT `photo_event`.`event_id`, `photo_event`.`event_use`, `photo_event`.`event_rereleased`, `photo_event`.`event_name`, `photo_event`.`event_num`, `photo_event`.`event_date`, `photo_event`.`event_end`, `photo_event`.`event_mrk_codes`, `cust_customers`.`cust_handle`, `cust_customers`.`cust_cname`, `cust_customers`.`cust_fname`, `cust_customers`.`cust_lname`, `cust_customers`.`cust_email`, `cust_customers`.`cust_city`, `cust_customers`.`cust_state`, `cust_customers`.`cust_zip`, `cust_customers`.`cust_website`, `cust_customers`.`cust_thumb`, `photo_event_images`.`image_tiny` , `photo_event_images`.`image_folder`, `photo_evnt_mrkt`.*, 
ABS(TO_DAYS(`photo_event`.`event_end`) - TO_DAYS(NOW())) AS `EndToDay`, 
ABS(TO_DAYS(`photo_event`.`event_date`) - TO_DAYS(NOW())) AS `StartToDay`,
( ABS(TO_DAYS(`photo_event`.`event_date`) - TO_DAYS(NOW())) - (`photo_evnt_mrkt`.`event_mrk_days_after_start` - `photo_evnt_mrkt`.`event_mrk_end_start_promo`))
 AS `EndPromoStart`
	FROM `photo_evnt_mrkt` 
	INNER JOIN `photo_event` 
		ON ( `photo_event`.`event_mrk_ids` LIKE CONCAT('}',`photo_evnt_mrkt`.`event_mrk_id`,'{')
				OR `photo_event`.`event_mrk_ids` LIKE CONCAT('}',`photo_evnt_mrkt`.`event_mrk_id`,'[+]%')
				OR `photo_event`.`event_mrk_ids` LIKE CONCAT('%[+]',`photo_evnt_mrkt`.`event_mrk_id`,'[+]%')
				OR `photo_event`.`event_mrk_ids` LIKE CONCAT('%[+]',`photo_evnt_mrkt`.`event_mrk_id`,'{') )
	INNER JOIN `cust_customers` 
		ON `cust_customers`.`cust_id` = `photo_event`.`cust_id` 
	LEFT JOIN `photo_event_images` 
		ON (`photo_event_images`.`image_id` = `photo_event`.`event_image`
			OR `photo_event_images`.`image_id` IS NULL)
	INNER JOIN `photo_quest_book` 
		ON `photo_quest_book`.`event_id` = `photo_event`.`event_id` 
	WHERE `photo_evnt_mrkt`.`event_mrk_use` = 'y' 
		AND `event_use` = 'y'
		AND `event_del` = 'n'
		AND (
					(
						(`photo_evnt_mrkt`.`event_mrk_days_early_purchase` != '0' 
							AND `photo_evnt_mrkt`.`event_mrk_days_early_purchase` >= ABS(TO_DAYS(`photo_event`.`event_date`) - TO_DAYS(NOW()))
						)
					OR (`photo_evnt_mrkt`.`event_mrk_days_after_start` != '0'
							AND `photo_evnt_mrkt`.`event_mrk_days_after_start` >= ABS(TO_DAYS(`photo_event`.`event_date`) - TO_DAYS(NOW()))
							AND `photo_evnt_mrkt`.`event_mrk_days_cart_expire` = '0'
							AND NOW() >= `photo_event`.`event_date`
						)
					OR (`photo_evnt_mrkt`.`event_mrk_days_after_start` != '0'
							AND `photo_evnt_mrkt`.`event_mrk_days_cart_expire` != '0'
							AND ABS(TO_DAYS(`photo_event`.`event_date`) - TO_DAYS(NOW()) + `photo_evnt_mrkt`.`event_mrk_days_after_start`) <= `photo_evnt_mrkt`.`event_mrk_days_cart_expire`
							AND NOW() >= `photo_event`.`event_date`
						)
					OR (`photo_evnt_mrkt`.`event_mrk_days_before_end` != '0' 
							AND `photo_evnt_mrkt`.`event_mrk_days_before_end` >= ABS(TO_DAYS(`photo_event`.`event_end`) - TO_DAYS(NOW()))
						)
					OR (`photo_evnt_mrkt`.`event_mrk_days_before_end_2` != '0' 
							AND `photo_evnt_mrkt`.`event_mrk_days_before_end_2` >= ABS(TO_DAYS(`photo_event`.`event_end`) - TO_DAYS(NOW()))
						)
					OR (`photo_evnt_mrkt`.`event_mrk_days_cart_expire` != '0' 
							AND `photo_evnt_mrkt`.`event_mrk_days_cart_expire` >= ABS(TO_DAYS(`photo_event`.`event_date`) - TO_DAYS(NOW()))
							AND NOW() <= `photo_event`.`event_date`
						)
					OR (`photo_evnt_mrkt`.`event_mrk_starts` = 'y'
							AND ABS(TO_DAYS(`photo_event`.`event_date`) - TO_DAYS(NOW())) = '0'
							AND (`photo_evnt_mrkt`.`event_mrk_days_after_start` = '0'
								OR ABS(TO_DAYS(`photo_event`.`event_date`) - TO_DAYS(NOW())) <= `photo_evnt_mrkt`.`event_mrk_days_after_start`)
							AND (`photo_evnt_mrkt`.`event_mrk_days_cart_expire` = '0'
								OR ABS(TO_DAYS(`photo_event`.`event_date`) - TO_DAYS(NOW()) + `photo_evnt_mrkt`.`event_mrk_days_after_start`) <= `photo_evnt_mrkt`.`event_mrk_days_cart_expire`)
						)
					)
					" . ((date("Y-m-d") == $BlackF) ? "OR `photo_evnt_mrkt`.`event_mrk_black_friday` = 'y'" : "") . "
					" . ((date("md") == '1210') ? "OR `photo_evnt_mrkt`.`evnt_mrk_dec_10` = 'y'" : "") . "
					" . ((date("md") == "1101") ? "OR `photo_evnt_mrkt`.`event_mrk_re_release` = 'y'" : "") . "
				)
		
	GROUP BY `photo_event`.`event_id`, `photo_evnt_mrkt`.`event_mrk_id`;";
$get_exp = mysql_query($query_get_exp, $cp_connection) or die(mysql_error());
$total_get_exp = mysql_num_rows($get_exp);

if ($total_get_exp > 0) {
    while ($row_get_exp = mysql_fetch_assoc($get_exp)) {

        $EId = $row_get_exp['event_id'];
        $Handle = $row_get_exp['cust_handle'];
        $Event = $row_get_exp['event_num'];
        $address = (strlen(trim($row_get_exp['cust_city'])) > 0) ? $row_get_exp['cust_city'] . " " . $row_get_exp['cust_state'] . " " . $row_get_exp['cust_zip'] : '';
        $website = (strlen(trim($row_get_exp['cust_website'])) > 0) ? $row_get_exp['cust_website'] : '';
        $Photographer = ($row_get_exp['cust_cname'] == "") ? $row_get_exp['cust_fname'] . " " . $row_get_exp['cust_lname'] : $row_get_exp['cust_cname'];
        $EName = $row_get_exp['event_name'];
        $PhotoEmail = $row_get_exp['cust_email'];
        $MrkCodes = str_replace(array("}", "{"), '', explode("[+]", $row_get_exp['event_mrk_codes']));
        $Desc = $row_get_exp['event_mrk_text'];
        $EvntDate = date("Y-m-d", mktime(0, 0, 0, substr($row_get_exp['event_date'], 5, 2), substr($row_get_exp['event_date'], 8, 2), substr($row_get_exp['event_date'], 0, 4)));
        $EvntEnd = date("Y-m-d", mktime(0, 0, 0, substr($row_get_exp['event_end'], 5, 2), substr($row_get_exp['event_end'], 8, 2), substr($row_get_exp['event_end'], 0, 4)));

        if ((date("md") == "1101") && $row_get_exp['event_use'] == 'y' && $row_get_exp['event_rereleased'] == 'n') {
            $expDate = date("Y-m-d H:i:s", mktime(0, 0, 0, 1, 15, (date("Y") + 1)));
            $query_upd_event = "UPDATE `photo_event` SET `event_use` = 'y', `event_rereleased` = 'y', `event_end` = '$expDate' WHERE `event_id` = '$EId';";
            $upd_event = mysql_query($query_upd_event, $cp_connection) or die(mysql_error());
        }

        $BioImage = false;
        $IName = $row_get_exp['image_tiny'];
        $file = $r_path . substr($row_get_exp['image_folder'], 0, -11) . "Large/" . $IName;

        $Imager = new ImageProcessor();
        $Imager->SetMaxSize(67108864);
        $Imager->File($file);
        if ($Imager->ERROR == false) {
            $Imager->Resize(145, 145);
            if (isset($rotate) && intval($rotate) > 0)
                $Imager->Rotate((intval($rotate) * -1));
            if (isset($color) && $color == 'b')
                $Imager->Gray();
            else if (isset($color) && $color == 's')
                $Imager->Sepia();
            $fileType = $Imager->Ext;

            $Image = tempnam("/tmp", "FOO");

            $Hndl = fopen($Image, "w");
            fwrite($Hndl, $Imager->OutputContents());
            fclose($Hndl);
        } else {
            $IName = false;
            $Image = false;
        }

        $query_get_book = "SELECT `email` FROM `photo_quest_book` WHERE `event_id` = '$EId' AND `promotion` = 'y' GROUP BY `email`";
        $get_book = mysql_query($query_get_book, $cp_connection) or die(mysql_error());
        $bcc = array();
        while ($row_get_book = mysql_fetch_assoc($get_book))
            array_push($bcc, $row_get_book['email']);
        //array_push($bcc,"development@proimagesoftware.com");

        echo $Handle . " - " . $row_get_exp['event_name'] . " - " . $Image . "<br />";

        $title = mb_convert_encoding($EName, "UTF-8", 'HTML-ENTITIES');

        ob_start();
        include($r_path . 'Templates/Photographer/index.php');
        $msg = ob_get_contents();
        ob_end_clean();

        $Time = time();

        if ($Image !== false) {
            $msg = str_replace('[EmbedImg]', '<img class="img" src="cid:' . $Time . '" width="' . $Imager->OrigWidth[0] . '" height="' . $Imager->OrigHeight[0] . '" alt="' . $Handle . '" vspace="0" hspace="0" alt="' . $title . '" style="border: 3px solid #c89441;"><br clear="all" />', $msg);
        } else {
            $msg = str_replace('[EmbedImg]', '', $msg);
        }

        $msg = str_replace('[Title]', $title, $msg);
        $msg = str_replace('[Unsubscribe]', "http://www.proimagesoftware.com/unsubscribe.php?token=" . substr(time(), 0, 5) . $EId . substr(time(), 5), $msg);
        $msg = str_replace('[Photographer]', $Photographer, $msg);
        $msg = str_replace('[Address]', ((strlen(trim($address)) > 0) ? '<br />' . $address : ''), $msg);
        $msg = str_replace('[Website]', ((strlen(trim($website)) > 0) ? '<br /><a href="' . $website . '" title="' . $website . '">' . $website . '</a>' : ''), $msg);

        foreach ($bcc as $v) {
            $query_get_mrk = "SELECT `prod_discount_codes`.*, `prod_products`.`prod_name`
		FROM `prod_discount_codes`
		LEFT JOIN `prod_products`
			ON (`prod_products`.`prod_id` = `prod_discount_codes`.`prod_id`
				OR `prod_products`.`prod_id` IS NULL)
		WHERE `evnt_mrk_id` = '" . $row_get_exp['event_mrk_id'] . "' 
			AND `disc_use` = 'y'
		GROUP BY `disc_id`;";

            $get_mrk = mysql_query($query_get_mrk, $cp_connection) or die(mysql_error());
            $total_get_mrk = mysql_num_rows($get_mrk);
            $Coupons = '';
            if ($total_get_mrk > 0) {
                $Coupons = '<tr class="Coupons"><td align="center"><table border="0" cellpadding="0" cellspacing="0"><tr>';
                $a = 0;
                $b = 0;
                while ($row_get_mrk = mysql_fetch_assoc($get_mrk)) {
                    if (in_array($row_get_mrk['disc_id'], $MrkCodes)) {
                        if (strlen(trim($row_get_mrk['disc_percent'])) > 0 && intval($row_get_mrk['disc_percent']) > 0) {
                            $CouponName = "<strong>" . $row_get_mrk['disc_percent'] . "% Off</strong>";
                        } else if (strlen(trim($row_get_mrk['disc_price'])) > 0 && intval($row_get_mrk['disc_price']) > 0) {
                            $CouponName = "$<strong>" . number_format($row_get_mrk['disc_price'], 2, ".", ",") . " Off</strong>";
                        } else if (strlen(trim($row_get_mrk['disc_item'])) > 0 && intval($row_get_mrk['disc_item']) > 0 && strlen(trim($mrk['disc_for'])) > 0 && intval($row_get_mrk['disc_for']) > 0) {
                            $CouponName = "<strong>" . $row_get_mrk['disc_item'] . " for " . $row_get_mrk['disc_for'] . "</strong>";
                        }
                        if (strlen(trim($row_get_mrk['prod_name'])) > 0) {
                            $CouponName .= "<br />" . $row_get_mrk['prod_name'];
                        } else {
                            $CouponName .= "<br />your order";
                        }

                        $Coupons .= '<td class="Coupon' . (($b == 0) ? '1' : '2') . '" valign="top"><p class="Large"><a href="[Link]" title="Redeem"><img src="/images/spacer.gif" alt="Redeem" width="125" height="50" hspace="0" vspace="0" border="0" align="right"></a>' . $CouponName . '</p>
									<p class="Small">Can be found in "discounts" when viewing the event. Type: ' . $row_get_mrk['disc_code'] . ' for your discount code.</p></td>';
                        unset($CouponName);
                        $a++;
                        if ($a >= 2) {
                            $b++;
                            $a = 0;
                            $Coupons .= '</tr><tr>';
                        }
                    }
                }
                if ($a < 2) {
                    $Coupons .= '<td>&nbsp;</td>';
                }
                $Coupons .= '</tr></table></td></tr>';
            }

            $EmailText = array();
            if (intval($row_get_exp['event_mrk_days_early_purchase']) != 0 &&
                    $EvntDate == date("Y-m-d") &&
                    strlen(trim($row_get_exp['event_mrk_early_text'])) > 0) {
                $EmailText[] = $row_get_exp['event_mrk_early_text'];
            }
            if (intval($row_get_exp['event_mrk_days_after_start']) != 0 &&
                    intval($row_get_exp['event_mrk_days_after_start']) == intval($row_get_exp['StartToDay']) &&
                    strlen(trim($row_get_exp['event_mrk_start_text'])) > 0 &&
                    $row_get_exp['event_mrk_starts'] == 'n') {
                $EmailText[] = $row_get_exp['event_mrk_start_text'];
            }
            if (intval($row_get_exp['event_mrk_days_before_end']) != 0 &&
                    intval($row_get_exp['event_mrk_days_before_end']) == intval($row_get_exp['EndToDay']) &&
                    strlen(trim($row_get_exp['event_mrk_end_text'])) > 0) {
                $EmailText[] = $row_get_exp['event_mrk_end_text'];
            }
            if (intval($row_get_exp['event_mrk_days_before_end_2']) != 0 &&
                    intval($row_get_exp['event_mrk_days_before_end_2']) == intval($row_get_exp['EndToDay']) &&
                    strlen(trim($row_get_exp['event_mrk_end_2_text'])) > 0) {
                $EmailText[] = $row_get_exp['event_mrk_end_2_text'];
            }
            if (intval($row_get_exp['event_mrk_days_cart_expire']) != 0 &&
                    intval($row_get_exp['event_mrk_days_cart_expire']) == intval($row_get_exp['EndToDay']) &&
                    strlen(trim($row_get_exp['event_mrk_expire_text'])) > 0) {
                $EmailText[] = $row_get_exp['event_mrk_expire_text'];
            }
            if ($row_get_exp['event_mrk_starts'] == 'y' &&
                    intval($row_get_exp['StartToDay']) == 1 &&
                    strlen(trim($row_get_exp['event_mrk_starts_text'])) > 0) {
                $EmailText[] = $row_get_exp['event_mrk_starts_text'];
            }
            if ($row_get_exp['event_mrk_black_friday'] == 'y' &&
                    strlen(trim($row_get_exp['event_mrk_black_friday_text'])) > 0) {
                $EmailText[] = $row_get_exp['event_mrk_black_friday_text'];
            }
            if ($row_get_exp['evnt_mrk_dec_10'] == 'y' &&
                    strlen(trim($row_get_exp['evnt_mrk_dec_10_text'])) > 0) {
                $EmailText[] = $row_get_exp['evnt_mrk_dec_10_text'];
            }
            if ($row_get_exp['event_mrk_re_release'] == 'y' &&
                    strlen(trim($row_get_exp['event_mrk_re_release_text'])) > 0) {
                $EmailText[] = $row_get_exp['event_mrk_re_release_text'];
            }

            if ($row_get_exp['event_mrk_end_start_promo'] != '0' && $row_get_exp['event_mrk_days_after_start'] != '0' &&
                    intval($row_get_exp['EndPromoStart']) == 0 && strlen(trim($row_get_exp['event_mrk_end_start_promo_text'])) > 0) {

                $EmailText[] = $row_get_exp['event_mrk_end_start_promo_text'];
            }

            $msg2 = str_replace('[Coupons]', $Coupons, $msg);
            $msg2 = str_replace('[Name]', $v, $msg2);
            $msg2 = str_replace(array('[Link]', '%5BLink%5D'), "http://www.proimagesoftware.com/photo_viewer.php?Photographer=" . $Handle . "&code=" . $Event . "&email=" . $v . "&full=true", $msg2);
            $msg2 = str_replace(array('[Link2]', '%5BLink2%5D'), wordwrap("http://www.proimagesoftware.com/photo_viewer.php?Photographer=" . $Handle . "&code=" . $Event . "&email=" . $v . "&full=true", 100, $eol), $msg2);
            $msgStore = $msg2;

            foreach ($EmailText as $Desc) {
                $msg3 = $msgStore;
                $msg3 = str_replace('[Text]', '<p>' . ereg_replace(array("^<p>", "</p>$"), '', sanatizeentry($Desc, true)) . '</p>', $msg3);

                $Pattern = array();
                $Pattern[] = '/<link\s[^>]*href=(["\']??)([^"\'>]*?)\\1[^>]*rel="stylesheet"(.*)>/eiU';
                $Pattern[] = '@<style[^>]*?>.*?</style>@esiU';

                $CSS = "";
                $msg3 = preg_replace($Pattern[0], "FindCSS('$2')", $msg3);
                $msg3 = preg_replace($Pattern[1], "FindCSS2('$0')", $msg3);

                $InlineHTML = new Emogrifier();
                $InlineHTML->setHTML($msg3);
                $InlineHTML->setCSS($CSS);

                $msg3 = removeSpecial($msg3);

                $msg3 = $InlineHTML->emogrify();

                while (strpos("\r", $msg3) !== false || strpos("\n", $msg3) !== false || strpos("\r\n", $msg3) !== false || strpos("\n\r", $msg3) !== false) {
                    $msg3 = trim(str_replace(array("\r", "\n", "\r\n", "\n\r"), "", $msg3));
                }

                $msg3 = preg_replace("/ +/", " ", $msg3);
                $msg3 = str_replace('href= "', 'href="', $msg3);
                $msg3 = str_replace('src="/', 'src="http://www.proimagesoftware.com/', $msg3);
                $msg3 = str_replace('url(/', 'url(http://www.proimagesoftware.com/', $msg3);

                //$config = array('indent' => TRUE,'wrap' => 200);
                //$tidy = tidy_parse_string($msg, $config, 'UTF8');
                //$tidy->cleanRepair();
                //$msg = $tidy;
                $msg3 = clean_html_code($msg3);

                $mail = new PHPMailer();
                $mail->IsSendMail();
                $mail->Host = "smtp.proimagesoftware.com";
                $mail->IsHTML(true);
                $mail->Sender = "info@proimagesoftware.com";
                $mail->Hostname = "proimagesoftware.com";
                $mail->From = $PhotoEmail;
                $mail->FromName = $Photographer;
                $mail->ReplyTo = $PhotoEmail;
                $mail->AddAddress($v);
                $mail->Subject = str_replace("&amp;", "&", $title);
                if ($Image !== false)
                    $mail->AddEmbeddedImage($Image, $Time);
                $mail->Body = $msg3;
                $mail->Send();
            }
            unset($mail);
            unset($Pattern);
            unset($msgStore);
            unset($msg2);
            unset($msg3);
        }

        $Emailsent = true;
        $BioImage = false;
        if ($Image != false)
            unlink($Image);
        unset($EId);
        unset($PhotoEmail);
        unset($msg);
        unset($Image);
        unset($IName);
        unset($address);
        unset($website);
        unset($EName);
        unset($BioImage);
        unset($Handle);
        unset($Event);
        unset($Photographer);
        unset($MrkCodes);
        unset($EvntDate);
        unset($EvntEnd);
        unset($EmailText);

        sleep(2);
    }
}

/*

  Function checks for events that have expired and sets the event usage to no

 */

echo "Expired Events<br />";
$query_get_exp = "SELECT `photo_event`.`event_id`, `photo_event`.`event_name`, `photo_event`.`event_num`, `cust_customers`.`cust_handle`
	FROM `photo_event` 
	INNER JOIN `cust_customers` 
		ON `cust_customers`.`cust_id` = `photo_event`.`cust_id`
	WHERE `event_end` <= '$date1'
		AND `event_use` = 'y'
	GROUP BY `event_id`";
$get_exp = mysql_query($query_get_exp, $cp_connection) or die(mysql_error());
$total_get_exp = mysql_num_rows($get_exp);
while ($row_get_exp = mysql_fetch_assoc($get_exp)) {
    $EId = $row_get_exp['event_id'];
    echo $row_get_exp['cust_handle'] . ": " . $row_get_exp['event_name'] . " - " . $row_get_exp['event_num'] . "(" . $row_get_exp['event_id'] . ")" . "<br />";
    $upd = "UPDATE `photo_event` SET `event_use` = 'n' WHERE `event_id` = '$EId';";
    $updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());
}

$page = ob_get_contents();
ob_end_clean();

$mail = new PHPMailer();
$mail->IsSendMail();
$mail->Host = "smtp.proimagesoftware.com";
$mail->IsHTML(true);
$mail->Sender = "info@proimagesoftware.com";
$mail->Hostname = "proimagesoftware.com";
$mail->From = "development@proimagesoftware.com";
$mail->ReplyTo = "development@proimagesoftware.com";
$mail->AddAddress("info@proimagesoftware.com");
$mail->AddAddress("development@proimagesoftware.com");
$mail->Subject = "Photoexpress CronJob - Processing";
$mail->Body = $page;
$mail->Send();

require_once $r_path . 'scripts/cron_job/_specialEvents.php';
require_once $r_path . 'scripts/cron_job/_dbBackup.php';
?>
