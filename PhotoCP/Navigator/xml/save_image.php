<?php

if (isset($r_path) === false) {
  $count = count(explode("/", substr($_SERVER['PHP_SELF'], 1))) - 1;
  $r_path = "";
  for ($n = 0; $n < $count; $n++)
    $r_path .= "../";
}
$RealPath = $r_path;
$r_path = '../';
define("PhotoExpress Pro", true);
require_once $r_path . 'includes/cp_connection.php';
require_once $r_path . 'scripts/encrypt.php';
require_once $r_path . 'scripts/fnct_clean_entry.php';
require_once $r_path . 'includes/CleanNames.php';

define("AURIGMAv", 'v_8.0.96');

require_once $RealPath . 'Aurigma/' . AURIGMAv . '/UploadScripts/PostFields.class.php';
//require_once $RealPath . 'Aurigma/' . AURIGMAv . '/UploadScripts/FlashRequestFix.php';

//preProcessRequest();

$galleryPath = $RealPath . "photographers/";
if ($use_ftp === false)
  $galleryPath = $photographerFolder . "photographers/";
$LargePath = "Large/";
$absGalleryPath = $galleryPath . "/";
//$absGalleryPath = realpath($galleryPath)."/";

$FID = isset($_GET['fid']) ? trim(clean_variable($_GET['fid'], true)) : 0;
$EID = isset($_GET['event']) ? trim(clean_variable(decrypt_data(base64_decode($_GET['event'])), true)) : "Test";

$getRcrds = new sql_processor($database_cp_connection, $cp_connection, $gateways_cp_connection);
$getRcrds->mysql("SELECT `cust_customers`.`cust_id`, `cust_customers`.`cust_handle`, `photo_event`.`event_num` FROM `cust_customers` INNER JOIN `photo_event` ON `cust_customers`.`cust_id` = `photo_event`.`cust_id` WHERE `event_id` = '$EID' AND `event_use` = 'y';");

if ($getRcrds->TotalRows() == 0) {
  echo "Hacking";
  die();
}
$getRcrds = $getRcrds->Rows();
$Cust_id = $getRcrds[0]['cust_id'];
$folderName = $getRcrds[0]['cust_handle'];
$Event_name = $getRcrds[0]['event_num'];

function saveUploadedFiles() {
  global $Event_name, $folderName, $EID, $FID, $filePtr, $Parnt_Group_id, $Cust_id, $galleryPath, $absGalleryPath, $LargePath, $ThumbnailsPath, $IconPath, $ftp_server, $ftp_user_name, $ftp_user_pass, $use_ftp, $database_cp_connection, $cp_connection, $gateways_cp_connection;

  try {
    $eventFolderName = $Event_name;
    $originalFileName = rawurlencode($_POST[sprintf(PostFields::sourceName, 0)]);

    if (intval($FID) != 0) {
      $getRcrds = new sql_processor($database_cp_connection, $cp_connection, $gateways_cp_connection);
      $getRcrds->mysql("SELECT `group_name` FROM `photo_event_group` WHERE `group_id` = '$FID';");
      $getRcrds = $getRcrds->Rows();

      $groupFolderName = $getRcrds[0]['group_name'];

      //$fileName = str_replace("\\","/",clean_variable($_POST['FileName_1']));
      $fileName = str_replace("\\", "/", clean_variable(urldecode($originalFileName)));
      $fileName = explode("/", $fileName);
      $fileName = $fileName[count($fileName) - 1];
    } else {
      //$groupFolderName = str_replace("\\","/",clean_variable($_POST['FileName_1']));
      //$groupFolderName = str_replace("\\","/",clean_variable($originalFileName));

      $groupFolderName = str_replace("\\", "/", urldecode($originalFileName));
      $groupFolderName = explode("/", $groupFolderName);
      $fileName = $groupFolderName[count($groupFolderName) - 1];
      $groupFolderName = $groupFolderName[count($groupFolderName) - 2];
      if ($groupFolderName == "" || strlen($groupFolderName) == 0)
        $groupFolderName = "Unamed Group";

      $getRcrds = new sql_processor($database_cp_connection, $cp_connection, $gateways_cp_connection);
      $getRcrds->mysql("SELECT `group_id` FROM `photo_event_group` WHERE `event_id` = '$EID' AND `group_name` = '" . cleanNames($groupFolderName) . "' AND `parnt_group_id` = '0' ORDER BY `group_id` DESC LIMIT 0,1;");

      if ($getRcrds->TotalRows() == 0) {
        $getRcrds->mysql("INSERT INTO `photo_event_group` (`event_id`,`parnt_group_id`,`group_name`,`group_use`) VALUES ('$EID','0','" . cleanNames($groupFolderName) . "','y');");

        $getRcrds->mysql("SELECT `group_id` FROM `photo_event_group` WHERE `event_id` = '$EID' AND `group_name` = '" . cleanNames($groupFolderName) . "' AND `parnt_group_id` = '0' ORDER BY `group_id` DESC LIMIT 0,1;");
        $getRcrds = $getRcrds->Rows();
        $FID = $getRcrds[0]['group_id'];
      } else {
        $getRcrds = $getRcrds->Rows();
        $FID = $getRcrds[0]['group_id'];
        $getRcrds = new sql_processor($database_cp_connection, $cp_connection, $gateways_cp_connection);
        $getRcrds->mysql("UPDATE `photo_event_group` SET `group_use` = 'y' WHERE `group_id` = '$FID';");
      }
    }
    $fileName = cleanNames($fileName, 3);
    $groupFolderName = cleanNames($groupFolderName, 2);

    $ImagePath = substr(md5($folderName), 5, 10) . "/";
    if ($use_ftp === true) {
      $conn_id = ftp_connect($ftp_server);
      $login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
    }

    if (!is_dir($absGalleryPath . $folderName)) {
      if ($use_ftp === true) {
        ftp_mkdir($conn_id, $absGalleryPath . $folderName);
      } else {
        mkdir($absGalleryPath . $folderName);
      }
      if (!is_dir($absGalleryPath . $folderName)) {
        throw new Exception('Failed to create ' . $absGalleryPath . $folderName);
      }
    }
    if (!is_dir($absGalleryPath . $folderName . "/" . $eventFolderName)) {
      if ($use_ftp === true) {
        ftp_mkdir($conn_id, $absGalleryPath . $folderName . "/" . $eventFolderName);
      } else {
        mkdir($absGalleryPath . $folderName . "/" . $eventFolderName);
      }
      if (!is_dir($absGalleryPath . $folderName . "/" . $eventFolderName)) {
        throw new Exception('Failed to create ' . $absGalleryPath . $folderName . "/" . $eventFolderName);
      }
    }

    if (!is_dir($absGalleryPath . $folderName . "/" . $eventFolderName . "/" . $groupFolderName)) {
      if ($use_ftp === true) {
        ftp_mkdir($conn_id, $absGalleryPath . $folderName . "/" . $eventFolderName . "/" . $groupFolderName);
        ftp_mkdir($conn_id, $absGalleryPath . $folderName . "/" . $eventFolderName . "/" . $groupFolderName . "/" . $ImagePath);
        ftp_mkdir($conn_id, $absGalleryPath . $folderName . "/" . $eventFolderName . "/" . $groupFolderName . "/" . $LargePath);
      } else {
        mkdir($absGalleryPath . $folderName . "/" . $eventFolderName . "/" . $groupFolderName);
        mkdir($absGalleryPath . $folderName . "/" . $eventFolderName . "/" . $groupFolderName . "/" . $ImagePath);
        mkdir($absGalleryPath . $folderName . "/" . $eventFolderName . "/" . $groupFolderName . "/" . $LargePath);
      }
      if (!is_dir($absGalleryPath . $folderName . "/" . $eventFolderName . "/" . $groupFolderName)) {
        throw new Exception('Failed to create ' . $absGalleryPath . $folderName . "/" . $eventFolderName . "/" . $groupFolderName);
      }
    }

    /* Demo is not pathed the same so need to uncomment this part and comment out lower section to upload to the correct folder. */
    $absImagePath = $absGalleryPath . $folderName . "/" . $eventFolderName . "/" . $groupFolderName . "/" . $ImagePath . "/";
    $absLargePath = $absGalleryPath . $folderName . "/" . $eventFolderName . "/" . $groupFolderName . "/" . $LargePath . "/";

    if (!$_FILES[sprintf(PostFields::file, 0, 0)]['size'])
      return;
    if (!$_FILES[sprintf(PostFields::file, 1, 0)]['size'])
      return;

    $SourceFile = $_FILES[sprintf(PostFields::file, 0, 0)];
    $ThumbnailFile = $_FILES[sprintf(PostFields::file, 1, 0)];

    $Angle = clean_variable($_POST[sprintf(PostFields::angle, 0)], true);

    if ($use_ftp === true) {
      $file_mrk = $absGalleryPath . $folderName . "/" . $eventFolderName . "/ExtractDump.txt";
      $filePtr = @fopen("ftp://" . $ftp_user_name . ":" . $ftp_user_pass . "@" . $ftp_server . $file_mrk, "a");
    } else {
      $file_mrk = $absGalleryPath . $folderName . "/" . $eventFolderName . "/ExtractDump.txt";
      $filePtr = fopen($file_mrk, "a");
    }

    if ($filePtr && true == false) {

      $data = "--------------------------------------------\n";
      $data .= "Date = " . date("Y-m-d H:i:s") . "\n";
      fwrite($filePtr, $data);

      $data .= "************************\n";
      $data .= "****** Successful ******\n";
      $data .= "************************\n";
      $data .= "Groupname = " . $groupFolderName . "\n";
      $data .= "FileName = " . $fileName . "\n";
      $data .= "Rotation = " . $Angle . "\n";
      $data .= "** Source **\n";
      $data .= "Temp = " . $SourceFile['tmp_name'] . "\n";
      $data .= "Size = " . $SourceFile['size'] . "\n";
      $data .= "Type = " . $SourceFile['type'] . "\n";
      list($Width, $Height) = getimagesize($SourceFile['tmp_name']);
      //$data .= "Temp = ".$_FILES["SourceFile_1"]['tmp_name']."\n";
      //$data .= "Size = ".$_FILES["SourceFile_1"]['size']."\n";
      //$data .= "Type = ".$_FILES["SourceFile_1"]['type']."\n";
      //list($Width, $Height) = getimagesize($_FILES["SourceFile_1"]['tmp_name']);
      $data .= "Deminsions = " . $Width . " x " . $Height . "\n";
      $data .= "** File 1 **\n";
      $data .= "Temp = " . $ThumbnailFile['tmp_name'] . "\n";
      $data .= "Size = " . $ThumbnailFile['size'] . "\n";
      $data .= "Type = " . $ThumbnailFile['type'] . "\n";
      list($Width, $Height) = getimagesize($ThumbnailFile['tmp_name']);
      //$data .= "Temp = ".$_FILES["Thumbnail3_1"]['tmp_name']."\n";
      //$data .= "Size = ".$_FILES["Thumbnail3_1"]['size']."\n";
      //$data .= "Type = ".$_FILES["Thumbnail3_1"]['type']."\n";
      //list($Width, $Height) = getimagesize($_FILES["Thumbnail3_1"]['tmp_name']);
      $data .= "Deminsions = " . $Width . " x " . $Height . "\n";
      @fwrite($filePtr, $data);
      @fclose($filePtr);
    }

    if ($use_ftp === true) {
      //ftp_put($conn_id, $absImagePath.$fileName, $_FILES["SourceFile_1"]['tmp_name'], FTP_BINARY);
      //ftp_put($conn_id, $absLargePath.$fileName, $_FILES["Thumbnail3_1"]['tmp_name'], FTP_BINARY);
      ftp_put($conn_id, $absImagePath . $fileName, $SourceFile['tmp_name'], FTP_BINARY);
      ftp_put($conn_id, $absLargePath . $fileName, $ThumbnailFile['tmp_name'], FTP_BINARY);
    } else {
      //copy($_FILES["SourceFile_1"]['tmp_name'], $absImagePath.$fileName);
      //copy($_FILES["Thumbnail3_1"]['tmp_name'], $absLargePath.$fileName);
      copy($SourceFile['tmp_name'], $absImagePath . $fileName);
      copy($ThumbnailFile['tmp_name'], $absLargePath . $fileName);
    }
    //$size = $_FILES['SourceFile_1']['size']/1048576;
    $size = $SourceFile['size'] / 1048576;
    $time = time();
    $folder = 'photographers/' . $folderName . "/" . $eventFolderName . "/" . $groupFolderName . "/" . $ImagePath;
    $folder = str_replace("../", "", $folder);

    $getRcrds = new sql_processor($database_cp_connection, $cp_connection, $gateways_cp_connection);
    $getRcrds->mysql("INSERT INTO `photo_event_images` (`cust_id`,`group_id`,`image_tiny`,`image_small`,`image_large`,`image_folder`,`image_size`,`imgage_org_rotate`,`image_time`,`image_active`) VALUES ('$Cust_id','$FID','$fileName','$fileName','$fileName','" . str_replace("'", "''", $folder) . "','$size','$Angle','$time','y');");

    $getRcrds->mysql("UPDATE `photo_event_group` SET `group_updated` = NOW() WHERE `group_id` = '$FID';");
    $getRcrds->mysql("UPDATE `photo_event` SET `event_updated` = NOW() WHERE `event_id` = '$EID';");
  } catch (Exception $e) {
    require_once $RealPath . 'scripts/fnct_phpmailer.php';

    $mail = new PHPMailer();
    $mail->IsSendMail();
    $mail->Host = "smtp.proimagesoftware.com";
    $mail->IsHTML(false);
    $mail->Sender = "info@proimagesoftware.com";
    $mail->Hostname = "proimagesoftware.com";
    $mail->From = 'development@proimagesoftware.com';
    $mail->AddAddress('chad.serpan@gmail.com');
    $mail->Subject = 'Photoexpress: Upload Error';
    $mail->Body = $e->getMessage();
    $mail->Send();
  }
}

saveUploadedFiles();

//postProcessRequest();