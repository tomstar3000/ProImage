<?

if (!isset($r_path)) {
    $count = count(explode("/", substr($_SERVER['PHP_SELF'], 1))) - 2;
    $r_path = "";
    for ($n = 0; $n < $count; $n++)
        $r_path .= "../";
}
include $r_path . 'security.php';
require_once $r_path . 'scripts/fnct_clean_entry.php';
require_once $r_path . 'scripts/fnct_image_resize.php';
require_once $r_path . 'scripts/fnct_format_file_name.php';
$CId = $path[2];
$CName = (isset($_POST['Name'])) ? clean_variable($_POST['Name'], true) : '';
$Product = (isset($_POST['Product'])) ? clean_variable($_POST['Product'], true) : '';
$CCode = (isset($_POST['Code'])) ? clean_variable($_POST['Code'], true) : '';
$CType = (isset($_POST['Discount_Type'])) ? clean_variable($_POST['Discount_Type'], true) : 's';
$CPercent = (isset($_POST['Percent'])) ? clean_variable($_POST['Percent'], true) : '';
$CPrice = (isset($_POST['Price'])) ? clean_variable($_POST['Price'], true) : '';
$CBuy = (isset($_POST['Buy'])) ? clean_variable($_POST['Buy'], true) : '';
$CGet = (isset($_POST['Get'])) ? clean_variable($_POST['Get'], true) : '';
$MItems = (isset($_POST['Multiple_Items'])) ? clean_variable($_POST['Multiple_Items'], true) : 'n';
$CLimit = (isset($_POST['Limit'])) ? clean_variable($_POST['Limit'], true) : '';
$CDiscon = (isset($_POST['Discontinue'])) ? clean_variable($_POST['Discontinue']) : '';
$CUses = (isset($_POST['Number_of_Uses'])) ? clean_variable($_POST['Number_of_Uses']) : '1';
$Image = "";
$Imagev = (isset($_POST['Image_val'])) ? clean_variable($_POST['Image_val'], true) : '';
$Error = false;
$Note = (isset($_POST['Code_Note'])) ? clean_variable($_POST['Code_Note']) : '';
if (isset($_POST['Controller']) && $_POST['Controller'] == "Save" && ($cont == 'add' || $cont == 'edit')) {
    $SDiscon = date("Y-m-d H:i:s", mktime(0, 0, 0, substr($CDiscon, 0, 2), substr($CDiscon, 3, 2), substr($CDiscon, 6, 4)));
    if (is_uploaded_file($_FILES['Image']['tmp_name'])) {
        $Fname = $_FILES['Image']['name'];
        $Iname = format_file_name($Fname, "i");
        $ISize = $_FILES['Image']['size'];
        $ITemp = $_FILES['Image']['tmp_name'];
        $IType = $_FILES['Image']['type'];
        if ($use_ftp === true) {
            $conn_id = ftp_connect($ftp_server);
            $login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
        }
        $Disc_Folder = realpath($Disc_Folder);
        $Image = loadimage($MaxSize, $Fname, $Iname, $ISize, $ITemp, $IType, $Disc_Folder, $Disc_IWidth, $Disc_IHeight, $Disc_ICrop, $Disc_IResize, $use_ftp, $conn_id);
    } else {
        $Image = array();
        $Image[0] = true;
        $Image[1] = $Imagev;
    }
    if ($Image[0]) {
        $Imagev = $Image[1];
        if ($_POST['Remove_Image'] == "true")
            $Imagev = "";
        if ($cont == "add") {
            $query_get_info = "SELECT COUNT(`disc_id`) AS `count_code` FROM `prod_discount_codes` WHERE `disc_code` = '$CCode'";
            $get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
            $row_get_info = mysql_fetch_assoc($get_info);
            if ($row_get_info['count_code'] == 0) {

                $add = "INSERT INTO `prod_discount_codes` (`disc_name`,`prod_id`,`disc_code`, disc_type, `disc_percent`,`disc_exp`,`disc_price`,`disc_item`,`disc_for`,`disc_mult`,`disc_limit`,`disc_num_uses`,`disc_image`,`disc_note`) 
                                    VALUES ('$CName','$Product','$CCode','$CType','$CPercent','$SDiscon','$CPrice','$CBuy','$CGet','$MItems','$CLimit','$CUses','$Imagev','$Note');";
                $addinfo = mysql_query($add, $cp_connection) or die(mysql_error());
            } else {
                $Error = "That code has already been used";
            }
        } else {
            $upd = "UPDATE `prod_discount_codes` SET `disc_name` = '$CName',`prod_id` = '$Product',`disc_code` = '$CCode',
                                    disc_type = '$CType',
                                    `disc_percent` ='$CPercent',`disc_exp` = '$SDiscon',`disc_price` = '$CPrice',`disc_item`='$CBuy',`disc_for`='$CGet',`disc_mult` = '$MItems',`disc_limit` = '$CLimit',`disc_num_uses` = '$CUses', `disc_image` = '$Imagev', `disc_note` = '$Note' WHERE `disc_id` = '$CId'";
            $updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());
        }
        if (!$Error)
            $cont = "view";
    }
} else {
    if ($cont != "add") {
        $query_get_info = "SELECT * FROM `prod_discount_codes` WHERE `disc_id` = '$CId'";
        $get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
        $row_get_info = mysql_fetch_assoc($get_info);
        $totalRows_get_info = mysql_num_rows($get_info);

        $CName = $row_get_info['disc_name'];
        $Product = $row_get_info['prod_id'];
        $CCode = $row_get_info['disc_code'];
        $CType = $row_get_info['disc_type'];
        $CPercent = $row_get_info['disc_percent'];
        $CPrice = $row_get_info['disc_price'];
        $CBuy = $row_get_info['disc_item'];
        $CGet = $row_get_info['disc_for'];
        $CLimit = $row_get_info['disc_limit'];
        $MItems = $row_get_info['disc_mult'];
        $CDiscon = format_date($row_get_info['disc_exp'], "Dash", false, true, false);
        $CUses = $row_get_info['disc_num_uses'];
        $Image = "";
        $Imagev = $row_get_info['disc_image'];
        $Note = $row_get_info['disc_note'];

        mysql_free_result($get_info);
    }
}
?>