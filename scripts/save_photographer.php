<?

if (isset($r_path) === false) {
    $count = count(explode("/", substr($_SERVER['PHP_SELF'], 1))) - 1;
    $r_path = "";
    for ($n = 0; $n < $count; $n++)
        $r_path .= "../";
}
include $r_path . 'scripts/security.php';
include $r_path . 'scripts/fnct_clean_entry.php';
// Set up Authorize only for WPPI, going to keep for Free SignUp for first 30 days.
$is_creditcard = true;
$is_gateway = "Authorize_AIM";
$is_live = true;
$is_error = false;
$is_process = true;
//$is_capture = "AUTH_CAPTURE";
$is_capture = "AUTH_ONLY";
$is_method = "CC";
$approval = false;
$Error = false;
$CIP = $_SERVER['REMOTE_ADDR'];
$SvLvl = isset($_POST['Service_Level']) ? clean_variable($_POST['Service_Level'], true) : (isset($_GET['Service_Level']) ? clean_variable($_GET['Service_Level'], true) : "");

$getChkSvLvl = new sql_processor($database_cp_connection, $cp_connection, $gateways_cp_connection);
$getChkSvLvl->mysql("SELECT `prod_qty`, `prod_price`, `prod_year`, `prod_fee` FROM `prod_products` WHERE `prod_id` = '$SvLvl';");
$getChkSvLvl = $getChkSvLvl->Rows();

$Quota = ($getChkSvLvl[0]['prod_qty'] == 0) ? 50 : $getChkSvLvl[0]['prod_qty'];
$Quota = 1024 * $Quota;
$total = $getChkSvLvl[0]['prod_price'];
$discount = $total; // Discount set up so that the first 30 days are free
$fee = 0; //$getChkSvLvl[0]['prod_fee'];
if (date("Y-m-d") < "2007-05-01")
    $fee = 0;
$grandtotal = $total + $fee - $discount;
if ($grandtotal == 0) {
    $is_capture = "AUTH_ONLY";
    $grandtotal = .01;
}
$recurring = $getChkSvLvl[0]['prod_year'];

$SQ = isset($_POST['Security_Question']) ? clean_variable($_POST['Security_Question'], true) : "";
$Answer = isset($_POST['Answer']) ? clean_variable($_POST['Answer'], true) : "";
$RName = isset($_POST['Referred_By']) ? clean_variable($_POST['Referred_By'], true) : "";
$UName = isset($_POST['Username']) ? clean_variable($_POST['Username'], true) : "";
$FName = isset($_POST['First_Name']) ? clean_variable($_POST['First_Name'], true) : "";
$MName = isset($_POST['Middle_Name']) ? clean_variable($_POST['Middle_Name'], true) : "";
$LName = isset($_POST['Last_Name']) ? clean_variable($_POST['Last_Name'], true) : "";
$Suffix = isset($_POST['Suffix']) ? clean_variable($_POST['Suffix'], true) : "0";
$CString = isset($_POST['Url_Handle']) ? clean_variable($_POST['Url_Handle'], true) : "";
$replace = array(" ", "\\", "/", ":", "*", "?", "\"", "<", ">", "|", "'");
$with = array("_", "", "", "", "", "", "", "", "", "", "");
$CString = str_replace($replace, $with, $CString);

$CName = isset($_POST['Company_Name']) ? clean_variable($_POST['Company_Name'], true) : "";
$Add = isset($_POST['Address']) ? clean_variable($_POST['Address'], true) : "";
$SuiteApt = isset($_POST['Suite_Apt']) ? clean_variable($_POST['Suite_Apt'], true) : "";
$Add2 = isset($_POST['Address_2']) ? clean_variable($_POST['Address_2'], true) : "";
$City = isset($_POST['City']) ? clean_variable($_POST['City'], true) : "";
$State = isset($_POST['State']) ? clean_variable($_POST['State'], true) : "";
$Zip = isset($_POST['Zip']) ? clean_variable($_POST['Zip'], true) : "";
$Count = isset($_POST['Country']) ? clean_variable($_POST['Country'], true) : "USA";
$P1 = isset($_POST['P1']) ? clean_variable($_POST['P1'], true) : "";
$P2 = isset($_POST['P2']) ? clean_variable($_POST['P2'], true) : "";
$P3 = isset($_POST['P3']) ? clean_variable($_POST['P3'], true) : "";
$Phone = isset($_POST['Phone_Number']) ? clean_variable($_POST['Phone_Number'], true) : "";
$W1 = isset($_POST['W1']) ? clean_variable($_POST['W1'], true) : "";
$W2 = isset($_POST['W2']) ? clean_variable($_POST['W2'], true) : "";
$W3 = isset($_POST['W3']) ? clean_variable($_POST['W3'], true) : "";
$Work = isset($_POST['Work_Number']) ? clean_variable($_POST['Work_Number'], true) : "";
$WorkExt = isset($_POST['Work_Ext']) ? clean_variable($_POST['Work_Ext'], true) : "";
$M1 = isset($_POST['M1']) ? clean_variable($_POST['M1'], true) : "";
$M2 = isset($_POST['M2']) ? clean_variable($_POST['M2'], true) : "";
$M3 = isset($_POST['M3']) ? clean_variable($_POST['M3'], true) : "";
$Mobile = isset($_POST['Mobile_Number']) ? clean_variable($_POST['Mobile_Number'], true) : "";
$F1 = isset($_POST['F1']) ? clean_variable($_POST['F1'], true) : "";
$F2 = isset($_POST['F2']) ? clean_variable($_POST['F2'], true) : "";
$F3 = isset($_POST['F3']) ? clean_variable($_POST['F3'], true) : "";
$Fax = isset($_POST['Fax_Number']) ? clean_variable($_POST['Fax_Number'], true) : "";
$Email = isset($_POST['Email']) ? clean_variable($_POST['Email'], true) : "";
$BFName = isset($_POST['Billing_First_Name']) ? clean_variable($_POST['Billing_First_Name'], true) : "";
$BMName = isset($_POST['Billing_Middle_Name']) ? clean_variable($_POST['Billing_Middle_Name'], true) : "";
$BLName = isset($_POST['Billing_Last_Name']) ? clean_variable($_POST['Billing_Last_Name'], true) : "";
$BSuffix = isset($_POST['Billing_Suffix']) ? clean_variable($_POST['Billing_Suffix'], true) : "0";
$BCName = isset($_POST['Billing_Company_Name']) ? clean_variable($_POST['Billing_Company_Name'], true) : "";
$BAdd = isset($_POST['Billing_Address']) ? clean_variable($_POST['Billing_Address'], true) : "";
$BSuiteApt = isset($_POST['Billing_Suite_Apt']) ? clean_variable($_POST['Billing_Suite_Apt'], true) : "";
$BAdd2 = isset($_POST['Billing_Address_2']) ? clean_variable($_POST['Billing_Address_2'], true) : "";
$BCity = isset($_POST['Billing_City']) ? clean_variable($_POST['Billing_City'], true) : "";
$BState = isset($_POST['Billing_State']) ? clean_variable($_POST['Billing_State'], true) : "";
$BZip = isset($_POST['Billing_Zip']) ? clean_variable($_POST['Billing_Zip'], true) : "";
$BCount = isset($_POST['Billing_Country']) ? clean_variable($_POST['Billing_Country'], true) : "USA";
$CCV = isset($_POST['CCV_Code']) ? clean_variable($_POST['CCV_Code'], true) : "";
$CType = isset($_POST['Type_of_Card']) ? clean_variable($_POST['Type_of_Card'], true) : "1";
$CCM = isset($_POST['Expiration_Month']) ? clean_variable($_POST['Expiration_Month'], true) : date("m");
$CCY = isset($_POST['Expiration_Year']) ? clean_variable($_POST['Expiration_Year'], true) : date("Y");

function FindCSS($CSSLink) {
    global $CSS, $Template, $r_path;
    $path_parts = pathinfo($CSSLink);
    $path = $path_parts['basename'];
    $path_parts = pathinfo($Template);
    $path = $path_parts['dirname'] . "/" . $path;
    $handle = fopen($r_path . $path, "r") or die("Failed Opening " . $r_path . $path);
    while (!feof($handle))
        $CSS .= fread($handle, 8192);
    fclose($handle);
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

if (isset($_POST['Controller']) && $_POST['Controller'] == "true") {
    require_once($r_path . 'scripts/cart/encrypt.php');
    require_once($r_path . 'scripts/emogrifier.php');
    require_once $r_path . 'scripts/fnct_phpmailer.php';
    $Pattern = "^[a-z0-9]{4,15}$";
    $UName = trim($UName);
    $PWord = clean_variable($_POST['Password'], true);
    $RPWord = clean_variable($_POST['Confirm_Password'], true);
    // Check Password Pattern
    if (strlen($MName) > 1) {
        $Error = "The information you have entered is invalid, please validate your personal information and try again.";
    }

    if ($Error == false) {
        $getChkUser = new sql_processor($database_cp_connection, $cp_connection, $gateways_cp_connection);
        $getChkUser->mysql("SELECT COUNT(`cust_handle`) AS `count_url_string` FROM `cust_customers` WHERE `cust_handle` = '$CString';");
        $getChkUser = $getChkUser->Rows();
        if ($getChkUser[0]['count_url_string'] > 0) {
            $Error = "That URL Handle has already been used";
        } else if ($Error === false) {
            if (!eregi($Pattern, trim($PWord)) || !eregi($Pattern, $PWord)) {
                $Error = "Please limit your Password to Alphanumeric Characters and Greater Than 3 Characters";
            } else {
                $PWord = md5($PWord);
                $RPWord = md5($RPWord);
                if ($PWord == $RPWord) {
                    $UCWord = clean_variable(encrypt_data(clean_variable($_POST['Password'], true)), "Store");

                    $getChkUser = new sql_processor($database_cp_connection, $cp_connection, $gateways_cp_connection);
                    $getChkUser->mysql("SELECT COUNT(`cust_username`) AS `count_uname` FROM `cust_customers` WHERE `cust_username` = '$UName';");
                    $getChkUser = $getChkUser->Rows();

                    define('IN_PHPBB', true);
                    $phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : $r_path . 'Bulletin_Board_3/';
                    $phpEx = substr(strrchr(__FILE__, '.'), 1);
                    require($phpbb_root_path . 'common.' . $phpEx);
                    require($phpbb_root_path . 'includes/functions_user.' . $phpEx);

                    $UserCheck = validate_username($UName);

                    if ($getChkUser[0]['count_uname'] == 0 && $UserCheck === false) {
                        $getChkUser = new sql_processor($database_cp_connection, $cp_connection, $gateways_cp_connection);
                        $getChkUser->mysql("SELECT COUNT(`cust_handle`) AS `count_url_string` FROM `cust_customers` WHERE `cust_handle` = '$CString';");
                        $getChkUser = $getChkUser->Rows();

                        if ($getChkUser[0]['count_url_string'] == 0) {
                            /** Commented out on 2013-11-18 */
                            if ($use_ftp === true) {
                                $conn_id = ftp_connect($ftp_server);
                                $login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
                            }
                            $absPath = realpath("./" . $r_path) . "/";
                            if (!is_dir($absPath . $CString)) { // Left in to make sure we don't overwrite one of our system folders
                                $CCNum = encrypt_data(clean_variable($_POST['Credit_Card_Number'], true));
                                $CC4Num = substr(clean_variable($_POST['Credit_Card_Number'], true), -4, 4);
                                $OldBCount = $BCount;
                                if (strlen(trim($_POST['Credit_Card_Number'])) == 0) {
                                    $is_process = false; // Line added for WPPI to allow photographers to sign up with out paying.
                                }
                                //if($is_process){
                                //	require_once ($r_path.'scripts/cart/merchant_ini.php');
                                //} else {
                                $approval = true;
                                //}
                                $BCount = $OldBCount;
                                //require_once($r_path.'checkout/process.php');
                                /** Removed 2012-11-18 relying on .htaccess document from here on out. * */
                                if ($approval) {
                                    if ($use_ftp === true) {
                                        ftp_mkdir($conn_id, $absPath . $CString);
                                        ob_start();
                                        echo '<?
                                  $handle = explode("/",$_SERVER[\'PHP_SELF\']);
                                  $handle = $handle[1];
                                  require_once(\'../index.php\');
                                  ?>';
                                        $print = ob_get_contents();
                                        ob_end_clean();
                                        $file_mrk = $absPath . $CString . "/index.php";
                                        $file = fopen("ftp://" . $ftp_user_name . ":" . $ftp_user_pass . "@" . $ftp_server . "/" . $file_mrk, 'w');
                                        fwrite($file, $print);
                                        fclose($file);
                                        ob_start();
                                        echo '<?
                                  $handle = explode("/",$_SERVER[\'PHP_SELF\']);
                                  $handle = $handle[1];
                                  require_once(\'../bio.php\');
                                  ?>';
                                        $print = ob_get_contents();
                                        ob_end_clean();
                                        $file_mrk = $absPath . $CString . "/bio.php";
                                        $file = fopen("ftp://" . $ftp_user_name . ":" . $ftp_user_pass . "@" . $ftp_server . "/" . $file_mrk, 'w');
                                        fwrite($file, $print);
                                        fclose($file);
                                        ob_start();
                                        echo '<?
                                  $handle = explode("/",$_SERVER[\'PHP_SELF\']);
                                  $handle = $handle[1];
                                  require_once(\'../guestbook.php\');
                                  ?>';
                                        $print = ob_get_contents();
                                        ob_end_clean();
                                        $file_mrk = $absPath . $CString . "/guestbook.php";
                                        $file = fopen("ftp://" . $ftp_user_name . ":" . $ftp_user_pass . "@" . $ftp_server . "/" . $file_mrk, 'w');
                                        fwrite($file, $print);
                                        fclose($file);
                                        ob_start();
                                        echo '<?
                                  $handle = explode("/",$_SERVER[\'PHP_SELF\']);
                                  $handle = $handle[1];
                                  require_once(\'../public.php\');
                                  ?>';
                                        $print = ob_get_contents();
                                        ob_end_clean();
                                        $file_mrk = $absPath . $CString . "/public.php";
                                        $file = fopen("ftp://" . $ftp_user_name . ":" . $ftp_user_pass . "@" . $ftp_server . "/" . $file_mrk, 'w');
                                        fwrite($file, $print);
                                        fclose($file);
                                        ob_start();
                                        echo '<?
                                  $handle = explode("/",$_SERVER[\'PHP_SELF\']);
                                  $handle = $handle[1];
                                  require_once(\'../contact.php\');
                                  ?>';
                                        $print = ob_get_contents();
                                        ob_end_clean();
                                        $file_mrk = $absPath . $CString . "/contact.php";
                                        $file = fopen("ftp://" . $ftp_user_name . ":" . $ftp_user_pass . "@" . $ftp_server . "/" . $file_mrk, 'w');
                                        fwrite($file, $print);
                                        fclose($file);
                                    } else {
                                        mkdir($absPath . $CString);
                                        ob_start();
                                        echo '<?
                                  $handle = explode("/",$_SERVER[\'PHP_SELF\']);
                                  $handle = $handle[1];
                                  require_once(\'../index.php\');
                                  ?>';
                                        $print = ob_get_contents();
                                        ob_end_clean();
                                        $file_mrk = $absPath . $CString . "/index.php";
                                        $file = fopen($file_mrk, 'w');
                                        fwrite($file, $print);
                                        fclose($file);
                                        ob_start();
                                        echo '<?
                                  $handle = explode("/",$_SERVER[\'PHP_SELF\']);
                                  $handle = $handle[1];
                                  require_once(\'../bio.php\');
                                  ?>';
                                        $print = ob_get_contents();
                                        ob_end_clean();
                                        $file_mrk = $absPath . $CString . "/bio.php";
                                        $file = fopen($file_mrk, 'w');
                                        fwrite($file, $print);
                                        fclose($file);
                                        ob_start();
                                        echo '<?
                                  $handle = explode("/",$_SERVER[\'PHP_SELF\']);
                                  $handle = $handle[1];
                                  require_once(\'../guestbook.php\');
                                  ?>';
                                        $print = ob_get_contents();
                                        ob_end_clean();
                                        $file_mrk = $absPath . $CString . "/guestbook.php";
                                        $file = fopen($file_mrk, 'w');
                                        fwrite($file, $print);
                                        fclose($file);
                                        ob_start();
                                        echo '<?
                                  $handle = explode("/",$_SERVER[\'PHP_SELF\']);
                                  $handle = $handle[1];
                                  require_once(\'../public.php\');
                                  ?>';
                                        $print = ob_get_contents();
                                        ob_end_clean();
                                        $file_mrk = $absPath . $CString . "/public.php";
                                        $file = fopen($file_mrk, 'w');
                                        fwrite($file, $print);
                                        fclose($file);
                                        ob_start();
                                        echo '<?
                                  $handle = explode("/",$_SERVER[\'PHP_SELF\']);
                                  $handle = $handle[1];
                                  require_once(\'../contact.php\');
                                  ?>';
                                        $print = ob_get_contents();
                                        ob_end_clean();
                                        $file_mrk = $absPath . $CString . "/contact.php";
                                        $file = fopen($file_mrk, 'w');
                                        fwrite($file, $print);
                                        fclose($file);
                                    }
                                    $bill_date = "0000-00-00 00:00:00";
                                    /*
                                      if($recurring == "y"){
                                      $bill_date = date("Y-m-d h:i:s",mktime(0,0,0,date("m"),date("d"),date("Y")+1));
                                      } else {
                                      $bill_date = date("Y-m-d h:i:s",mktime(0,0,0,date("m")+1,date("d"),date("Y")));
                                      } */
                                    $bill_date = date("Y-m-d h:i:s", mktime(0, 0, 0, date("m"), date("d") + 45, date("Y")));

                                    switch ($SvLvl) {
                                        case 9: case 328:
                                            $rev = 15;
                                            break;
                                        case 10: case 329:
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
                                    $Hash = phpbb_hash(trim(decrypt_data($UCWord)));
                                    $username_ary = array();
                                    $username_ary['username'] = $UName;
                                    $username_ary['user_password'] = $Hash;
                                    $username_ary['user_email'] = $Email;
                                    $username_ary['group_id'] = 2; //2 - 5
                                    $username_ary['user_type'] = 3;
                                    $Added_User = user_add($username_ary);

                                    $add = "INSERT INTO `cust_customers` (`cust_handle`,`cust_title`,`cust_department`,`cust_fcname`,`cust_desc`,`cust_desc_2`,`cust_p_ext`,`cust_fwork`,`cust_fext`,`cust_800`,`cust_femail`,`cust_email_2`, `cust_icon` , `cust_type`, `cust_fee`, `cust_referred`,`cust_fname`,`cust_mint`,`cust_lname`,`cust_suffix`,`cust_cname`,`cust_add`,`cust_add_2`,`cust_suite_apt`,`cust_city`,`cust_state`,`cust_zip`,`cust_country`,`cust_phone`,`cust_cell`,`cust_fax`,`cust_work`,`cust_ext`,`cust_email`,`cust_username`,`cust_password`,`cust_uncode`,`cust_session`,`sec_quest_id`,`sec_quest_ans`,`cust_ip`,`cust_login_try`,`cust_last_login`,`cust_photo`,`cust_quota`,`cust_active`,`cust_due_date`,`cust_paid`,`cust_service`,`cust_rev`, `cust_created`, `cust_accessed`, `cust_hold`) VALUES ('$CString','','','','','','0','0','0','0','','','','0', '0','$RName','$FName','$MName','$LName','$Suffix','$CName','$Add','$Add2','$SuiteApt','$City','$State','$Zip','$Count','$Phone','" . intval($Mobile) . "', '" . intval($Fax) . "', '" . intval($Work) . "', '" . intval($WorkExt) . "', '$Email','$UName','$PWord','$UCWord','$User_session[0]','$SQ','$Answer','$CIP','0',NOW(),'y','$Quota','y','$bill_date','y','$SvLvl','$rev', NOW(), NOW(), 'n');";
                                    $addInfo = new sql_processor($database_cp_connection, $cp_connection, $gateways_cp_connection);
                                    $addInfo->mysql($add);

                                    $getLast = new sql_processor($database_cp_connection, $cp_connection, $gateways_cp_connection);
                                    $getLast->mysql("SELECT `cust_id` FROM `cust_customers` WHERE `cust_email` = '$Email' AND `cust_lname` = '$LName' ORDER BY `cust_id` DESC LIMIT 0,1;");
                                    $getLast = $getLast->Rows();

                                    $CId = $getLast[0]['cust_id'];

                                    $add = "INSERT INTO `cust_billing` (`cust_id`,`cust_bill_fname`,`cust_bill_mname`,`cust_bill_lname`,`cust_bill_suffix`,`cust_bill_cname`,`cust_bill_add`, `cust_bill_add_2`, `cust_bill_suite_apt`, `cust_bill_city`, `cust_bill_state`, `cust_bill_zip`, `cust_bill_counry`, `cust_bill_ccnum`, `cust_bill_ccshort`, `cust_bill_ccv`, `cust_bill_year`, `cust_bill_exp_month`, `cust_bill_cc_type_id`, `cust_bill_default`) VALUES ('$CId','$BFName','$BMName','$BLName','$BSuffix','$BCName','$BAdd','$BAdd2','$BSuiteApt','$BCity','$BState','$BZip','$BCount','$CCNum','" . intval($CC4Num) . "', '$CCV','" . intval($CCY) . "','$CCM','$CType', 'y');";
                                    $add = "INSERT INTO `cust_billing` (`cust_id`,`cust_bill_fname`,`cust_bill_mname`,`cust_bill_lname`,`cust_bill_suffix`,`cust_bill_cname`,`cust_bill_add`, `cust_bill_add_2`, `cust_bill_suite_apt`, `cust_bill_city`, `cust_bill_state`, `cust_bill_zip`, `cust_bill_counry`, `cust_bill_ccnum`, `cust_bill_ccshort`, `cust_bill_ccv`, `cust_bill_year`, `cust_bill_exp_month`, `cust_bill_cc_type_id`, `cust_bill_default`) VALUES ('$CId','$FName','$MName','$LName','$Suffix','$CName','$Add','$Add2','$SuiteApt','$City','$State','$Zip','$Count','','', '','','','', 'y');";

                                    $addInfo = new sql_processor($database_cp_connection, $cp_connection, $gateways_cp_connection);
                                    $addInfo->mysql($add);

                                    $getLast = new sql_processor($database_cp_connection, $cp_connection, $gateways_cp_connection);
                                    $getLast->mysql("SELECT `cust_bill_id` FROM `cust_billing` WHERE `cust_id` = '$CId' ORDER BY `cust_bill_id` DESC LIMIT 0,1;");
                                    $getLast = $getLast->Rows();

                                    $BId = $getLast[0]['cust_bill_id'];

                                    $getLast = new sql_processor($database_cp_connection, $cp_connection, $gateways_cp_connection);
                                    $getLast->mysql("SELECT `invoice_id` AS `invoice_num` FROM `orders_invoice` ORDER BY `invoice_id` DESC LIMIT 0,1;");
                                    $getLast = $getLast->Rows();

                                    $inv_num = ($getLast[0]['invoice_num'] + 101) . date("Ymd");
                                    $inv_enc = md5($inv_num);

                                    if ($grandtotal == .01)
                                        $grandtotal = 0;

                                    $add = "INSERT INTO `orders_invoice` (`cust_id`,`cust_bill_id`,`invoice_num`,`invoice_enc`,`invoice_rev`,`invoice_total`,`invoice_disc`,`invoice_grand`,`invoice_date`,`invoice_transaction`,`invoice_paid`,`invoice_paid_date`,`invoice_comp`,`invoice_comp_date`,`invoice_online`,`invoice_desc`,`invoice_comments`, `invoice_tracking`, `referred_comp`, `referred_name`, `invoice_printed_date`, `invoice_pers_quality`, `invoice_pers_print`, `invoice_pers_ship`, `invoice_instruction`) VALUES ('$CId','$BId','$inv_num','$inv_enc','$rev','$total','$discount','$grandtotal',NOW(),'$tran_id','y',NOW(),'y',NOW(),'y','','', '', '', '', '0000-00-00 00:00:00', '', '', '', '');";

                                    $addInfo = new sql_processor($database_cp_connection, $cp_connection, $gateways_cp_connection);
                                    $addInfo->mysql($add);

                                    $getLast = new sql_processor($database_cp_connection, $cp_connection, $gateways_cp_connection);
                                    $getLast->mysql("SELECT `invoice_id` FROM `orders_invoice` WHERE `cust_id` = '$CId' ORDER BY `invoice_id` DESC LIMIT 0,1;");
                                    $getLast = $getLast->Rows();

                                    $inv_id = $getLast[0]['invoice_id'];

                                    $add = "INSERT INTO `orders_invoice_prod` (`invoice_id`,`prod_id`,`invoice_prod_sale`,`invoice_prod_price`,`invoice_prod_fee`,`invoice_prod_qty`) VALUES ('$inv_id','$SvLvl','n','$total','$fee','1');";

                                    $addInfo = new sql_processor($database_cp_connection, $cp_connection, $gateways_cp_connection);
                                    $addInfo->mysql($add);

                                    $encnum = $inv_enc;
                                    
                                    $photog = "INSERT INTO `photo_photographers` (`cust_id`, `photo_fname`, `photo_lname`, `photo_add`, `photo_add2`, `photo_suiteapt`, `photo_city`, `photo_state`, `photo_zip`, `photo_country`, `photo_phone`, `photo_email`, `photo_use`) 
                                                                          VALUES ('$CId', '$FName', '$LName', '$Add','$Add2','$SuiteApt','$City','$State','$Zip', '$Count', '$Phone', '$Email', 'y')";
                                    $photogInfo = new sql_processor( $database_cp_connection, $cp_connection, $gateways_cp_connection );
                                    $photogInfo->mysql($photog);
                                    
                                    ob_start();
                                    include ($r_path . 'checkout/invoice_signup.php');
                                    $msg = ob_get_contents();
                                    ob_end_clean();

                                    $mail = new PHPMailer();
                                    $mail->IsSendMail();
                                    $mail->Host = "smtp.proimagesoftware.com";
                                    $mail->IsHTML(true);
                                    $mail->Sender = "info@proimagesoftware.com";
                                    $mail->Hostname = "proimagesoftware.com";
                                    $mail->From = "info@proimagesoftware.com";
                                    $mail->FromName = "ProImage Software";
                                    $mail->AddAddress($Email);
                                    $mail->Subject = "Pro Image Software: Invoice";
                                    $mail->Body = $msg;
                                    $mail->Send();

                                    $mail = new PHPMailer();
                                    $mail->IsSendMail();
                                    $mail->Host = "smtp.proimagesoftware.com";
                                    $mail->IsHTML(true);
                                    $mail->Sender = "info@proimagesoftware.com";
                                    $mail->Hostname = "proimagesoftware.com";
                                    $mail->From = "info@proimagesoftware.com";
                                    $mail->FromName = "ProImage Software";
                                    $mail->AddAddress("benjamin@alivestudios.com");
                                    $mail->Subject = "Pro Image Software: Invoice";
                                    $mail->Body = $msg;
                                    $mail->Send();

                                    $msg = $CString . ": " . $FName . " " . $LName . " (" . $Email . ") has joined Pro Images Software";
                                    $mail = new PHPMailer();
                                    $mail->IsSendMail();
                                    $mail->Host = "smtp.proimagesoftware.com";
                                    $mail->IsHTML(true);
                                    $mail->Sender = "info@proimagesoftware.com";
                                    $mail->Hostname = "proimagesoftware.com";
                                    $mail->From = "info@proimagesoftware.com";
                                    $mail->FromName = "ProImage Software";
                                    $mail->AddAddress("benjamin@alivestudios.com");
                                    $mail->Subject = "Pro Image Software: Invoice";
                                    $mail->Body = $msg;
                                    $mail->Send();

                                    $email_text = '<p>You can use your Control Panel to administer your events and personal webpage.</p>
	<p>Please Log Onto ' . $_SERVER['HTTP_HOST'] . '/PhotoCP to manage your profile information</p>
	<h1>Your Username Is: ' . $UName . '</h1>
	<h1>Your Password Is: ' . trim(decrypt_data($UCWord)) . '</h1>';
//	<p>Your personal website can be found at:<br />
//	<a href="http://'.$_SERVER['HTTP_HOST']."/".$CString.'" target="_blank">http://'.$_SERVER['HTTP_HOST']."/".$CString.'</a></p>';

                                    ob_start();
                                    include($r_path . 'Templates/Signup/index.php');
                                    $msg = ob_get_contents();
                                    ob_end_clean();

                                    $msg = str_replace('[Title]', "Thank you for joining us at proimagesoftware.com", $msg);
                                    $msg = str_replace('[Text]', $email_text, $msg);

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

                                    //$config = array('indent' => TRUE,'wrap' => 200);
                                    //$tidy = tidy_parse_string($msg, $config, 'UTF8');
                                    //$tidy->cleanRepair();
                                    //$msg = $tidy;
                                    $msg = clean_html_code($msg);

                                    while (strpos("\r", $msg) !== false || strpos("\n", $msg) !== false || strpos("\r\n", $msg) !== false || strpos("\n\r", $msg) !== false) {
                                        $msg = trim(str_replace(array("\r", "\n", "\r\n", "\n\r"), "", $msg));
                                    }

                                    $msg = preg_replace("/ +/", " ", $msg);
                                    $msg = str_replace('href= "', 'href="', $msg);
                                    $msg = str_replace('src="/', 'src="http://' . $_SERVER['HTTP_HOST'] . '/', $msg);
                                    $msg = str_replace('url(/', 'url(http://' . $_SERVER['HTTP_HOST'] . '/', $msg);

                                    $mail = new PHPMailer();
                                    $mail->IsSendMail();
                                    $mail->Host = "smtp.proimagesoftware.com";
                                    $mail->IsHTML(true);
                                    $mail->Sender = "info@proimagesoftware.com";
                                    $mail->Hostname = "proimagesoftware.com";
                                    $mail->From = "info@proimagesoftware.com";
                                    $mail->FromName = "ProImage Software";
                                    $mail->AddAddress($Email);
                                    $mail->Subject = "Thank You for Signing Up";
                                    $mail->Body = $msg;
                                    $mail->Send();

                                    $User_session[0] = $UName;
                                    $User_session[1] = "10";
                                    $User_session[2] = $CId;

                                    session_start();
                                    session_register('AdminLog');
                                    $_SESSION['AdminLog'] = $User_session;
                                    setcookie("TestCookie", "Is Cookie");
                                    if (!isset($_COOKIE['TestCookie'])) {
                                        $token = "?token=" . session_id();
                                        ;
                                    } else {
                                        $token = "";
                                    }
                                    $GoTo = "/PhotoCP/cp.php" . $token;
                                    header(sprintf("Location: %s", $GoTo));
                                } else {
                                    $Error = $message;
                                }
                            } else {
                                $Error = "That Url Handle is already in use.";
                            }
                        } else {
                            $Error = "That Url Handle is already in use.";
                        }
                    } else {
                        $Error = "That Username is already in use.";
                    }
                } else {
                    $Error = "Your Passwords do not match.";
                }
            }
        }
    }
}
?>