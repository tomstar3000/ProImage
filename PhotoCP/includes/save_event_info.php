<?
if (isset($r_path) === false) {
    $count = count(explode("/", substr($_SERVER['PHP_SELF'], 1))) - 2;
    $r_path = "";
    for ($n = 0; $n < $count; $n++)
        $r_path .= "../";
}
include $r_path . 'security.php';
require_once $r_path . 'scripts/fnct_clean_entry.php';

$getDefault = new sql_processor($database_cp_connection, $cp_connection, $gateways_cp_connection);
$getDefault->mysql("SELECT `default_event` FROM `photo_cust_defaults` WHERE `cust_id` = '$CustId';");
$getDefault = $getDefault->Rows();

$Owner = '';
$Phone = '';
$Owner_Email = '';
$Location = '';
$City = '';
$State = '';
$Country = 'USA';

$Public_Event = 'n';
$SendLab = 'n';
$At_Lab = 'n';
$At_Studio = 'n';
$ShipClient = 'n';
$Correct = 'n';
$Event_Duration = 90;
$Event_Marketing = array();
$Event_Marketing_Codes = array();

$Watermark = ('(C) ' . date("Y"));
$Watermark_Opacity = '20';
$Watermark_Frequency = '1';

$Defaults = unserialize(urldecode($getDefault[0]['default_event']));
if ($Defaults != false && count($Defaults) > 0) {
    foreach ($Defaults as $k => $v) {
        $variable = $k;
        if (is_array($v)) {
            $$variable = array();
            foreach ($v as $v2) {
                ${$variable}[] = $v;
            }
        } else {
            $$variable = $v;
        }
    }
}
if (isset($Defaults['Event_Marketing'])) {
    $Event_Marketing = $Defaults['Event_Marketing'];
}
if (isset($Defaults['Event_Marketing_Codes'])) {
    $Event_Marketing_Codes = $Defaults['Event_Marketing_Codes'];
}

$Durtn = $Event_Duration;
unset($Event_Duration);
$EId = $path[2];
$required_string = array();
$Name = (isset($_POST['Event_Name'])) ? clean_variable($_POST['Event_Name'], true) : '';
$required_string[] = "'Event_Name','','R'";
$Group = (isset($_POST['Pricing_Group'])) ? clean_variable($_POST['Pricing_Group'], true) : ((isset($Pricing_Group)) ? $Pricing_Group : '0');
// $required_string[] = "'Pricing_Group','','RisSelect'";
$Public = (isset($_POST['Public_Event'])) ? clean_variable($_POST['Public_Event'], true) : $Public_Event;
$Code = (isset($_POST['Event_Code'])) ? clean_variable(((strlen(trim($_POST['Event_Code'])) == 0) ? $Name : $_POST['Event_Code']), true) : '';
$Code = preg_replace("/[^a-zA-Z0-9\._\-]/", "", $Code);
$required_string[] = "'Event_Code','','R'";
$Photo = (isset($_POST['Photographer'])) ? clean_variable($_POST['Photographer']) : ((isset($Photographer)) ? $Photographer : '0');
// $required_string[] = "'Photographer','','RisSelect'";

$Loc = (isset($_POST['Location'])) ? clean_variable($_POST['Location'], true) : $Location;
// $required_string[] = "'Location','','R'";
$City = (isset($_POST['City'])) ? clean_variable($_POST['City'], true) : $City;
$State = (isset($_POST['State'])) ? clean_variable($_POST['State'], true) : $State;
$Country = (isset($_POST['Country'])) ? clean_variable($_POST['Country'], true) : $Country;

if (strlen($Phone) == 10) {
    $P1 = substr($Phone, 0, 3);
    $P2 = substr($Phone, 3, 3);
    $P3 = substr($Phone, 6, 4);
}

$OwnName = (isset($_POST['Owner'])) ? clean_variable($_POST['Owner'], true) : $Owner;
// $required_string[] = "'Owner','','R'";
$OwnEmail = (isset($_POST['Owner_Email'])) ? clean_variable($_POST['Owner_Email'], true) : $Owner_Email;
// $required_string[] = "'Owner_Email','','R'";
$Phone = (isset($_POST['Phone_Number'])) ? clean_variable($_POST['Phone_Number'], true) : $Phone;

$P1 = (isset($_POST['P1'])) ? clean_variable($_POST['P1'], true) : $P1;
$P2 = (isset($_POST['P2'])) ? clean_variable($_POST['P2'], true) : $P2;
$P3 = (isset($_POST['P3'])) ? clean_variable($_POST['P3'], true) : $P3;

$Copy = (isset($_POST['Watermark'])) ? clean_variable($_POST['Watermark']) : $Watermark;
$Opac = (isset($_POST['Watermark_Opacity'])) ? clean_variable(round($_POST['Watermark_Opacity'])) : $Watermark_Opacity;
$WFreq = (isset($_POST['Watermark_Frequency'])) ? clean_variable($_POST['Watermark_Frequency']) : $Watermark_Frequency;
$ENote = (isset($_POST['Notify'])) ? clean_variable($_POST['Notify']) : '10';
$Image = (isset($_POST['Image_Val'])) ? clean_variable($_POST['Image_Val'], true) : '';
$ToLab = (isset($_POST['SendLab'])) ? clean_variable($_POST['SendLab'], true) : $SendLab;
$AtLab = (isset($_POST['At_Lab'])) ? clean_variable($_POST['At_Lab'], true) : $At_Lab;
$AtPhoto = (isset($_POST['At_Studio'])) ? clean_variable($_POST['At_Studio'], true) : $At_Studio;
$ShipStud = (isset($_POST['ShipClient'])) ? clean_variable($_POST['ShipClient'], true) : $ShipClient;
$ColorCrt = (isset($_POST['Correct'])) ? clean_variable($_POST['Correct'], true) : $Correct;

$MktIds = (isset($_POST['Event_Marketing'])) ? $_POST['Event_Marketing'] : $Event_Marketing;
foreach ($MktIds as $k => $r) {
    $MktIds[$k] == clean_variable($r, true);
}
$MktIds = "}" . implode("[+]", $MktIds) . "{";
$MktCodes = (isset($_POST['Event_Marketing_Codes'])) ? $_POST['Event_Marketing_Codes'] : $Event_Marketing_Codes;
foreach ($MktCodes as $k => $r) {
    $MktCodes[$k] == clean_variable($r, true);
}
$MktCodes = "}" . implode("[+]", $MktCodes) . "{";

if (isset($_POST['Event_Hour'])) {
    if ($_POST['Event_AMPM'] == "pm")
        $_POST['Event_Hour'] = intval($_POST['Event_Hour']) + 12;
    else if (intval($_POST['Event_Hour']) == 12)
        $_POST['Event_Hour'] = 0;
    while (intval($_POST['Event_Hour']) > 24)
        $_POST['Event_Hour'] = intval($_POST['Event_Hour']) - 12;
    while (strlen($_POST['Event_Hour']) < 2)
        $_POST['Event_Hour'] = "0" . $_POST['Event_Hour'];
    while (strlen($_POST['Event_Minute']) < 2)
        $_POST['Event_Minute'] = "0" . $_POST['Event_Minute'];
}
$Date = (isset($_POST['Event_Month'])) ? clean_variable($_POST['Event_Year'] . "-" . $_POST['Event_Month'] . "-" . $_POST['Event_Day'] . " 00:00:00", true) : date("Y-m-d H:i:s");
$SDate = (isset($_POST['Start_Event_Month'])) ? clean_variable($_POST['Start_Event_Year'] . "-" . $_POST['Start_Event_Month'] . "-" . $_POST['Start_Event_Day'] . " " . $_POST['Event_Hour'] . ":" . $_POST['Event_Minute'] . ":00", true) : date("Y-m-d H:i:s");

if (isset($Expiration_Year) && isset($Expiration_Month) && isset($Expiration_Day)) {
    $EDate = $Expiration_Year . "-" . $Expiration_Month . "-" . $Expiration_Day . " 00:00:00";
} else {
    $EDate = date("Y-m-d", mktime(1, 1, 1, date("m"), (date("d") + $Durtn), date("Y")));
}
$EDate = (isset($_POST['Expiration_Month'])) ? clean_variable($_POST['Expiration_Year'] . "-" . $_POST['Expiration_Month'] . "-" . $_POST['Expiration_Day'], true) : $EDate;
$Desc = (isset($_POST['Event_Notes'])) ? clean_variable($_POST['Event_Notes']) : '';

if (isset($_POST['Controller']) && $_POST['Controller'] == "Save" && ($cont == "add" || $cont == "edit")) {
    $Code = trim($Code);
    $folder_replace = array("\\", "/", ":", "*", "?", "<", ">", "|", "'");
    $folder_with = array("", "", "", "", "", "", "", "", "");
    $Code = str_replace($folder_replace, $folder_with, $Code);
    $text2 = clean_variable($Desc, 'Store');

    $getId = new sql_processor($database_cp_connection, $cp_connection, $gateways_cp_connection);
    $getId->mysql("SELECT `cust_id` FROM `cust_customers` WHERE `cust_session` = '$loginsession[0]';");
    $getId = $getId->Rows();

    $cust_id = $getId[0]['cust_id'];

    if ($cont == "add") {
        $CheckNum = new sql_processor($database_cp_connection, $cp_connection, $gateways_cp_connection);
        $CheckNum->mysql("SELECT COUNT(`event_num`) AS `event_count` FROM `photo_event` WHERE `cust_id` = '$CustId' AND LOWER(`event_num`) = LOWER('$Code') AND `event_use` = 'y';");
        $CheckNum = $CheckNum->Rows();

        if ($CheckNum[0]['event_count'] == 0) {
            if ($_POST['Time'] != $_SESSION['Time']) {
                $_SESSION['Time'] = $_POST['Time'];
                $addInfo = new sql_processor($database_cp_connection, $cp_connection, $gateways_cp_connection);
                $addInfo->mysql("INSERT INTO `photo_event` (`cust_id`,`photo_id`,`event_num`,`event_price_id`,`event_public`,`event_name`,`event_desc`,`event_date`,`event_is_on`,`event_end`,`photo_to_lab`,`photo_at_lab`,`photo_at_photo`,`photo_ship_stud`,`photo_color_crt`,`event_use`,`event_copyright`,`event_opacity`,`event_frequency`,`event_not`,`owner`,`owner_email`,`owner_phone`,`owner_location`,`owner_city`,`owner_state`,`owner_count`,`event_mrk_ids`,`event_mrk_codes`) VALUES ('$CustId','$Photo','$Code','$Group','$Public','$Name','$text2','$Date','$SDate','$EDate','$ToLab','$AtLab','$AtPhoto','$ShipStud','$ColorCrt','y','$Copy','$Opac','$WFreq','$ENote','$OwnName','$OwnEmail','$Phone','$Loc','$City','$State','$Country','$MktIds','$MktCodes');");

                $addInfo->mysql("SELECT `event_id` FROM `photo_event` WHERE `cust_id` = '$CustId' ORDER BY `event_id` DESC LIMIT 0,1;");
                $addInfo = $addInfo->Rows();

                if (isset($_POST['GiftCerts'])) {
                    $items = array();
                    $items = $_POST['GiftCerts'];
                    $getAction = new sql_processor($database_cp_connection, $cp_connection, $gateways_cp_connection);
                    foreach ($items as $key => $value) {
                        $getAction->mysql("INSERT INTO `photo_event_disc` (`event_id`,`disc_id`,`disc_type`) VALUES ('" . $addInfo[0]['event_id'] . "','$value','g');");
                    }
                }
                if (isset($_POST['Discount_items'])) {
                    $items = array();
                    $items = $_POST['Discount_items'];
                    $getAction = new sql_processor($database_cp_connection, $cp_connection, $gateways_cp_connection);
                    foreach ($items as $key => $value) {
                        $getAction->mysql("INSERT INTO `photo_event_disc` (`event_id`,`disc_id`,`disc_type`) VALUES ('" . $addInfo[0]['event_id'] . "','$value','s');");
                    }
                }
                if (isset($_POST['Coupons'])) {
                    $items = $_POST['Coupons'];
                    $getAction = new sql_processor($database_cp_connection, $cp_connection, $gateways_cp_connection);
                    $getAction->mysql("INSERT INTO `photo_event_disc` (`event_id`,`disc_id`,`disc_type`) VALUES ('" . $addInfo[0]['event_id'] . "','$items','c');");
                }
                if (isset($_POST['Event_Notifications_items'])) {
                    $items = $_POST['Event_Notifications_items'];
                    $getAction = new sql_processor($database_cp_connection, $cp_connection, $gateways_cp_connection);
                    foreach ($items as $key => $value) {
                        $getAction->mysql("INSERT INTO `photo_event_note_events`  VALUES ('" . $addInfo[0]['event_id'] . "','$value');");
                    }
                }
                array_push($path, $addInfo[0]['event_id']);
                array_push($path, "ImgsGrps");
            }
        } else {
            $Error = "That event code is already in use.";
        }
    } else {
        $CheckNum = new sql_processor($database_cp_connection, $cp_connection, $gateways_cp_connection);
        $CheckNum->mysql("SELECT COUNT(`event_num`) AS `event_count` FROM `photo_event` WHERE `cust_id` = '$CustId' AND LOWER(`event_num`) = LOWER('$Code') AND `event_use` = 'y' AND `event_id` != '$EId';");
        $CheckNum = $CheckNum->Rows();

        if ($CheckNum[0]['event_count'] == 0) {
            $updInfo = new sql_processor($database_cp_connection, $cp_connection, $gateways_cp_connection);
            if (isset($_POST['Watermark'])) {
                $AddUpdate = ", `event_copyright` = '$Copy',`event_opacity` = '$Opac', `event_frequency` = '$WFreq'";
            }
            if (isset($_POST['Notify'])) {
                $AddUpdate = ", `event_not` = '$ENote'";
            }
            if (isset($_POST['Event_Marketing'])) {
                $AddUpdate = ", `event_mrk_ids` = '$MktIds', `event_mrk_codes` = '$MktCodes'";
            }
            $updInfo->mysql("UPDATE `photo_event` SET `photo_id` = '$Photo', `event_num` ='$Code',`event_price_id` = '$Group',`event_public` = '$Public',`event_name` = '$Name',`event_desc` = '$text2',`event_date` = '$Date',`event_is_on` = '$SDate',`event_end` = '$EDate',`photo_to_lab` = '$ToLab',`photo_at_lab` = '$AtLab' ,`photo_at_photo` = '$AtPhoto',`photo_ship_stud` = '$ShipStud',`photo_color_crt` = '$ColorCrt',`event_use` = 'y',`owner` = '$OwnName', `owner_email` = '$OwnEmail' ,`owner_phone` = '$Phone', `owner_location` = '$Loc', `owner_city` = '$City', `owner_state` = '$State', `owner_count` = '$Country' " . $AddUpdate . "  WHERE `event_id` = '$EId';");

            if (isset($_POST['GiftCerts'])) {
                $items = array();
                $items = $_POST['GiftCerts'];
                $getAction = new sql_processor($database_cp_connection, $cp_connection, $gateways_cp_connection);
                $getAction->mysql("DELETE FROM `photo_event_disc` WHERE `event_id` = '$EId' AND `disc_type` = 'g';");
                $getAction->mysql("OPTIMIZE TABLE `photo_event_disc`;");
                foreach ($items as $key => $value) {
                    $getAction->mysql("INSERT INTO `photo_event_disc` (`event_id`,`disc_id`,`disc_type`) VALUES ('$EId','$value','g');");
                }
            }
            if (isset($_POST['Discount_items'])) {
                $items = array();
                $items = $_POST['Discount_items'];
                $getAction = new sql_processor($database_cp_connection, $cp_connection, $gateways_cp_connection);
                $getAction->mysql("DELETE FROM `photo_event_disc` WHERE `event_id` = '$EId' AND `disc_type` = 's';");
                $getAction->mysql("OPTIMIZE TABLE `photo_event_disc`;");
                foreach ($items as $key => $value) {
                    $getAction->mysql("INSERT INTO `photo_event_disc` (`event_id`,`disc_id`,`disc_type`) VALUES ('$EId','$value','s');");
                }
            }
            if (isset($_POST['Coupns'])) {
                $items = array();
                $items = $_POST['Coupns'];
                $getAction = new sql_processor($database_cp_connection, $cp_connection, $gateways_cp_connection);
                $getAction->mysql("DELETE FROM `photo_event_disc` WHERE `event_id` = '$EId' AND `disc_type` = 'c';");
                $getAction->mysql("OPTIMIZE TABLE `photo_event_disc`;");
                foreach ($items as $key => $value) {
                    $getAction->mysql("INSERT INTO `photo_event_disc` (`event_id`,`disc_id`,`disc_type`) VALUES ('$EId','$value','c');");
                }
            }
            if (isset($_POST['Event_Notifications_items'])) {
                $items = array();
                $items = $_POST['Event_Notifications_items'];
                $getAction = new sql_processor($database_cp_connection, $cp_connection, $gateways_cp_connection);
                $getAction->mysql("DELETE FROM `photo_event_note_events` WHERE `event_id` = '$EId'");
                $getAction->mysql("OPTIMIZE TABLE `photo_event_note_events`;");
                foreach ($items as $key => $value) {
                    $getAction->mysql("INSERT INTO `photo_event_note_events` VALUES ('$EId','$value');");
                }
            }

            $cont = "view";
        } else {
            $Error = "That event code is already in use.";
        }
    }
} else if (isset($_POST['Controller']) && ($_POST['Controller'] == "Expire" || $_POST['Controller'] == "Remove") && $cont == "view") {
    if ($_POST['Controller'] == "Expire") {
        $updInfo = new sql_processor($database_cp_connection, $cp_connection, $gateways_cp_connection);
        $updInfo->mysql("UPDATE `photo_event` SET `event_use` = 'n' WHERE `event_id` = '$EId';");
    } else {
        $ExpDate = date("Y-m-d H:i:s", mktime(0, 0, 0, date("m"), (date("d") - 15), date("Y")));
        $updInfo = new sql_processor($database_cp_connection, $cp_connection, $gateways_cp_connection);
        $updInfo->mysql("UPDATE `photo_event` SET `event_use` = 'n', `event_updated` = '$ExpDate' WHERE `event_id` = '$EId';");
        ?>
        <script type="text/javascript">set_form('', 'Evnt,Evnt', 'query', '<? echo $sort; ?>', '<? echo $rcrd; ?>');</script>
        <?
    }
} else if (isset($_POST['Controller']) && $_POST['Controller'] == "Release" && $cont == "view") {
    $updInfo = new sql_processor($database_cp_connection, $cp_connection, $gateways_cp_connection);
    $updInfo->mysql("UPDATE `photo_event` SET `event_use` = 'y' WHERE `event_id` = '$EId';");
} else {
    if ($cont != "add") {
        $getInfo = new sql_processor($database_cp_connection, $cp_connection, $gateways_cp_connection);
        $getInfo->mysql("SELECT * FROM `photo_event` WHERE `event_id` = '$EId';");
        $getInfo = $getInfo->Rows();

        $Name = $getInfo[0]['event_name'];
        $Group = $getInfo[0]['event_price_id'];
        $Public = $getInfo[0]['event_public'];
        $Code = $getInfo[0]['event_num'];
        $Photo = $getInfo[0]['photo_id'];

        $Loc = $getInfo[0]['owner_location'];
        $City = $getInfo[0]['owner_city'];
        $State = $getInfo[0]['owner_state'];
        $Country = $getInfo[0]['owner_count'];

        $OwnName = $getInfo[0]['owner'];
        $OwnEmail = $getInfo[0]['owner_email'];
        $Phone = $getInfo[0]['owner_phone'];
        $P1 = substr($getInfo[0]['owner_phone'], 0, 3);
        $P2 = substr($getInfo[0]['owner_phone'], 3, 3);
        $P3 = substr($getInfo[0]['owner_phone'], 6, 4);

        $Copy = $getInfo[0]['event_copyright'];
        $Opac = $getInfo[0]['event_opacity'];
        $WFreq = $getInfo[0]['event_frequency'];
        $Image = $getInfo[0]['event_image'];
        $Date = $getInfo[0]['event_date'];
        $SDate = $getInfo[0]['event_is_on'];
        $EDate = $getInfo[0]['event_end'];
        $ENote = $getInfo[0]['event_not'];
        $SDesc = $getInfo[0]['event_short'];
        $Desc = $getInfo[0]['event_desc'];
        $ToLab = $getInfo[0]['photo_to_lab'];
        $AtLab = $getInfo[0]['photo_at_lab'];
        $AtPhoto = $getInfo[0]['photo_at_photo'];
        $ShipStud = $getInfo[0]['photo_ship_stud'];
        $ColorCrt = $getInfo[0]['photo_color_crt'];
        $MktIds = $getInfo[0]['event_mrk_ids'];
        $MktCodes = $getInfo[0]['event_mrk_codes'];
        //$Views = $getInfo[0]['event_views'];
        //yyyy-mm-dd hh:ii:ss
        //0123456789012345678
        $date1 = date("U", mktime(0, 0, 1, substr($Date, 5, 2), substr($Date, 8, 2), substr($Date, 0, 4)));
        $date2 = date("U", mktime(0, 0, 1, substr($EDate, 5, 2), substr($EDate, 8, 2), substr($EDate, 0, 4)));
        $Durtn = $date2 - $date1;
        $Durtn = round($Durtn / (60 * 60 * 24));
        switch ($Durtn) {
            case 30: case 60: case 90: case 120: $Durtn = $Durtn;
                break;
            default: $Durtn = 0;
                break;
        }
        $USE = $getInfo[0]['event_use'];
    }
}
if ($cont != "add" && !isset($USE)) {
    $getInfo = new sql_processor($database_cp_connection, $cp_connection, $gateways_cp_connection);
    $getInfo->mysql("SELECT * FROM `photo_event` WHERE `event_id` = '$EId';");
    $getInfo = $getInfo->Rows();
    //$Views = $getInfo[0]['event_views'];
    $USE = $getInfo[0]['event_use'];
}
define('NoSave', true);
if (isset($required_string)) {
    $required_string = implode(",", $required_string);
    $onclick = 'MM_validateForm(' . $required_string . '); if(document.MM_returnValue == true){document.getElementById(\'Controller\').value = \'Save\'; document.getElementById(\'form_action_form\').submit();}';
}
else
    $onclick = 'document.getElementById(\'Controller\').value = \'Save\'; document.getElementById(\'form_action_form\').submit();';
?>