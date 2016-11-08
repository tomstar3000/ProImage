<?php

if (!isset($r_path)) {
  $count = count(explode("/", substr($_SERVER['PHP_SELF'], 1))) - 1;
  $r_path = "";
  for ($n = 0; $n < $count; $n++)
    $r_path .= "../";
}
define('Allow Scripts', true);

require_once($r_path . 'scripts/fnct_xml_parser.php');
require_once($r_path . 'includes/AWS_S3.2.0/sdk.class.php');
require_once $r_path . 'scripts/fnct_phpmailer.php';
require_once $r_path . 'scripts/fnct_ImgeProcessor.php';

echo 'TESTING ' . (( defined('TESTING') == false || TESTING == false) ? 'false' : 'true') . '<br />' . PHP_EOL;

$bucket = 'proimages';
if (defined('TESTING') == false || TESTING == false) {
  $s3 = new AmazonS3();
}

$ImageProcessor = new ImageProcessor();
$contactHeight = 150;
$ImageProcessor->createBlank(600, $contactHeight + 100, '#FFFFFF');

$IId = $inv_id;
$Folder = $phatFoto;
$InvNum = $invnum;
$dq_file = false;

$date = date("Y-m-d");
$accdate = date("Y-m-d H:i:s");
$eol = "\r\n";

$replace = array('\\', '/', '*', '?', '"', '<', '>', '|', '\'');
$with = array('-', '-', '-', '-', '-', '-', '-', '-', '');
$reciever = PI_EMAIL;

ob_start();
include($r_path . 'checkout/invoice.php');
if (isset($row_get_photo['cust_cname']))
  $reciever = $row_get_photo['cust_email'];
$page = ob_get_contents();
ob_end_clean();

$lab_mail = new PHPMailer();
$lab_mail->Host = PI_SMTP;
$lab_mail->IsHTML(true);
$lab_mail->IsSendMail();
$lab_mail->Sender = PI_EMAIL;
$lab_mail->Hostname = PI_HOSTNAME;
$lab_mail->From = $reciever;
$lab_mail->FromName = $reciever;
if (defined('TESTING') == true && TESTING == true) {
  $lab_mail->AddAddress(DEV_EMAIL);
} else {
  foreach (unserialize(LAB_EMAILS) as $count => $to_lab_email) {
    if ($count == 0)
      $lab_mail->AddAddress($to_lab_email);
    else
      $lab_mail->AddBCC($to_lab_email);
  }
}
$lab_mail->Subject = "Invoice #" . $InvNum . " - " . $reciever;
$lab_mail->Body = $page;
unset($page);

$upd = "UPDATE `orders_invoice` SET `invoice_accepted` = 'y', `invoice_accepted_date` = '$accdate' WHERE `invoice_id` = '$IId'";
$upd_info = mysql_query($upd, $cp_connection) or die(mysql_error());

$query_get_images = "SELECT `prod_products`.`prod_name`, `prod_products`.`prod_desc`,
            `prod_products`.`prod_number`, `prod_products`.`prod_number_frontier`, `prod_products`.`prod_serial`, `orders_invoice_photo`.*, `photo_event_images`.* ,
            IF(invoice_image_image != '', invoice_image_image, image_tiny) AS sortby_name        
	FROM `orders_invoice_photo` 
	INNER JOIN `photo_event_images` 
		ON `photo_event_images`.`image_id` = `orders_invoice_photo`.`image_id` 
	INNER JOIN `prod_products` 
		ON `prod_products`.`prod_id` = `orders_invoice_photo`.`invoice_image_size_id` 
	WHERE `invoice_id` = '$IId'
        ORDER BY sortby_name ASC ;";
$get_images = mysql_query($query_get_images, $cp_connection) or die(mysql_error());

$conn_reed_id = NULL;

if (!function_exists('connect_reed_photo')) {

  function connect_reed_photo($reset = false) {
    global $conn_reed_id;
    if ($reset == true) {
      ftp_close($conn_reed_id);
    }
    // Connect to Reed Photography
    $conn_reed_id = ftp_connect(REEDFTP);
    $login_result = ftp_login($conn_reed_id, REEDUSER, REEDPASS);
    ftp_pasv($conn_reed_id, true);
    //ftp_set_option($conn_reed_id, FTP_TIMEOUT_SEC, 180);
  }

}

if (defined('TESTING') == false || TESTING == false) {
  connect_reed_photo();
}

$sc3cmdPath = $date; // New Path favor for s3cmd
$ReedPhotoPath = '/PI_' . $InvNum; //.$date; // New Path for Reed Photography
echo 'Checking Folder ' . $ReedPhotoPath . '<br />' . PHP_EOL;
if (defined('TESTING') == false || TESTING == false) {
  if (!@ftp_chdir($conn_reed_id, $ReedPhotoPath)) {
    ftp_mkdir($conn_reed_id, $ReedPhotoPath);
    ftp_chdir($conn_reed_id, '/');
  }
} else {
  if (!is_dir('/srv/proimage/current/toPhatFoto' . $ReedPhotoPath)) {
    mkdir('/srv/proimage/current/toPhatFoto' . $ReedPhotoPath);
  }
}

$ReedPhotoPath .= '/';
echo 'Checking Folder ' . $ReedPhotoPath . 'frontier<br />' . PHP_EOL;
if (defined('TESTING') == false || TESTING == false) {
  if (!@ftp_chdir($conn_reed_id, $ReedPhotoPath . 'frontier')) {
    ftp_mkdir($conn_reed_id, $ReedPhotoPath . 'frontier');
    ftp_chdir($conn_reed_id, '/');
  }
} else {
  if (!is_dir('/srv/proimage/current/toPhatFoto' . $ReedPhotoPath . 'frontier')) {
    mkdir('/srv/proimage/current/toPhatFoto' . $ReedPhotoPath . 'frontier');
  }
}
echo 'Checking Folder ' . $ReedPhotoPath . 'giclee<br />' . PHP_EOL;
if (defined('TESTING') == false || TESTING == false) {
  if (!@ftp_chdir($conn_reed_id, $ReedPhotoPath . 'giclee')) {
    ftp_mkdir($conn_reed_id, $ReedPhotoPath . 'giclee');
    ftp_chdir($conn_reed_id, '/');
  }
} else {
  if (!is_dir('/srv/proimage/current/toPhatFoto' . $ReedPhotoPath . 'giclee')) {
    mkdir('/srv/proimage/current/toPhatFoto' . $ReedPhotoPath . 'giclee');
  }
}

while (strlen($InvNum) < 5) {
  $InvNum = "0" . $InvNum;
}

$sc3cmdPath = $sc3cmdPath . '/' . $InvNum; // New Path favor for s3cmd

$CheckedFolders = array(
    'giclee' => false,
    'frontier' => false,
);
$ContentsFolders = array();
$LoadedImageList = array();

$mrk_array = array();
$contactSheet = array();

if (!function_exists('getImageRatioByName')) {

  function getImageRatioByName($name, $desc = '') {
    if (preg_match('/package/i', $name) && !empty($desc)) {
      $ratios = array();
      $sizes = explode(' ', $desc);
      foreach ($sizes as $size) {
        if (!empty($size)) {
          if (preg_match('/wallet/i', $size)) {
            $ratio = getImageRatioByName($size);
            $ratios[implode(':', $ratio)] = $ratio;
          } else {
            $size = explode('-', $size);
            if (is_array($size) && isset($size[1])) {
              $ratio = getImageRatioByName($size[1]);
              $ratios[implode(':', $ratio)] = $ratio;
            } else if (is_array($size) && preg_replace('/[^0-9x]/', '', strtolower($size[0])) != '') {
              $ratio = getImageRatioByName($size[0]);
              $ratios[implode(':', $ratio)] = $ratio;
            }
          }
        }
      }
      if (count(ratios) > 0) {
        return array_values($ratios);
      }
    } else if (strpos(strtolower($name), 'x') !== false) {
      $value = preg_replace('/[^0-9x]/', '', strtolower($name));
      $value = explode('x', $value);
      if ($value[0] < $value[1]) {
        // 2x3	1 to 1.5
        $ratio = round($value[0] / $value[1], 2);
        return array($ratio, 1);
      } else {
        //10x8	1.25 to 1
        $ratio = round($value[1] / $value[0], 2);
        return array(0, $ratio);
      }
    } else if (preg_match('/wallet/i', $name)) {
      // 2x3
      return array(.66, 1);
    }
    return NULL;
  }

}

if (!function_exists('processContactSheet')) {

  function processContactSheet($prodName, $prodDesc, $folder, $image, $imageRatio) {
    global $r_path, $contactSheet, $lab_mail, $ImageProcessor, $contactHeight;

    $cropsize = 150;

    echo $prodName . ' - ' . $image . ': ';
    $md5 = md5($prodName . ' - ' . $image);
    if (isset($contactSheet[$md5])) {

      return;
    }

    list($TWidth, $THeight) = getimagesize($folder . $image);
    if ((round(($TWidth / $THeight), 3) == 0.667 || ($TWidth / $THeight) == 1.5) &&
            (($imageRatio[0] == 0.67 && $imageRatio[1] == 1) || ($imageRatio[0] == 1 && $imageRatio[1] == 0.67))) {
      echo 'Image Ratio: 2 x 3 - Skipping ' . $imageRatio[0] . ' x ' . $imageRatio[1] . ' to ' . round($TWidth / $THeight, 3) . PHP_EOL;
      return;
    }

    $n = count($contactSheet);
    $contactSheet[$md5] = true;

    if ($n > 0) {
      $y = $n * ($contactHeight + 20);
      $index = $ImageProcessor->Indx;
      $newHeight = ($contactHeight + 20) * ($n + 1);
      $ImageProcessor->Crop(600, $newHeight, 'TopLeft');
      $ImageProcessor->Overlay(1, $index, 0, $y);
    }
    $y = $n * ($contactHeight + 10);

    $index = $ImageProcessor->Indx;
    $ImageProcessor->File($folder . $image, false, 0);
    $ImageProcessor->Resize($cropsize, $cropsize);
    $ImageProcessor->Overlay(0, $index, 200, $y);

    $index = $ImageProcessor->Indx;
    $ImageProcessor->ChangeIndex(0);

    echo 'Image Size: ' . $ImageProcessor->OrigWidth[0] . ' x ' . $ImageProcessor->OrigHeight[0] . ' - ';

    $TWidth = $ImageProcessor->OrigWidth[0];
    $THeight = $ImageProcessor->OrigHeight[0];

    if ($TWidth > $THeight && $imageRatio[0] < $imageRatio[1]) {
      $cropWidth = $THeight / $imageRatio[0];
      $cropHeight = $THeight;
    } else if ($TWidth > $THeight && $imageRatio[0] > $imageRatio[1]) {
      $cropWidth = $THeight / $imageRatio[1];
      $cropHeight = $THeight;
    } else if ($TWidth < $THeight && $imageRatio[0] < $imageRatio[1]) {
      $cropHeight = $TWidth / $imageRatio[0];
      $cropWidth = $TWidth;
    } else if ($TWidth < $THeight && $imageRatio[0] > $imageRatio[1]) {
      $cropHeight = $TWidth / $imageRatio[1];
      $cropWidth = $TWidth;
    }

    echo 'Image Ratio: ' . $imageRatio[0] . ' x ' . $imageRatio[1] . ' - Crop Ratio: ' . $cropWidth . ' x ' . $cropHeight;
    $ImageProcessor->Crop($cropWidth, $cropHeight, 'Center');
    $ImageProcessor->Overlay(0, $index, 400, $y);

    $ImageProcessor->TextBox('#000000', 10, $r_path . 'fonts/arial.ttf', array($prodName . ' - ' . $image), 10, $y);

    echo PHP_EOL;
  }

}

if (!function_exists('attachImagetoContactSheet')) {

  function attachImagetoContactSheet($prodName, $prodDesc, $folder, $image) {

    $imageRatio = getImageRatioByName($prodName, $prodDesc);
    if (!is_null($imageRatio) && is_file($folder . $image)) {

      if (is_array($imageRatio[0])) {
        foreach ($imageRatio as $imageRatioInd) {
          processContactSheet($prodName, $prodDesc, $folder, $image, $imageRatioInd);
        }
      } else {
        processContactSheet($prodName, $prodDesc, $folder, $image, $imageRatio);
      }
    }
  }

}

while ($row_get_images = mysql_fetch_assoc($get_images)) {

  $digital_download = false;
  if ($dq_file == false && ($row_get_images['prod_serial'] == "099" || $row_get_images['prod_serial'] == "100")) {
    $dq_file = true;
  }

  if ($row_get_images['prod_serial'] == "099" || $row_get_images['prod_serial'] == "100") {
    $digital_download = true;
  }

  $image = ($row_get_images['invoice_image_image'] != "") ? $row_get_images['invoice_image_image'] : $row_get_images['image_tiny'];
  $image = mb_convert_encoding($image, "UTF-8", "HTML-ENTITIES");
  $new_Folder = "o" . $InvNum . "." . $row_get_images['prod_number'];
  $new_Folder = str_replace($replace, $with, $new_Folder);
  if (defined('TESTING') == false || TESTING == false) {
    $response = $s3->if_object_exists($bucket, $sc3cmdPath . "/" . $new_Folder . "/IMAGE/" . $image);
    if ($response) {
      $D = "D_";
    } else {
      $D = "";
    }
  } else
    echo 'Testing skipping AWS' . '<br />' . PHP_EOL;

  // Reed Photography
  if ($row_get_images['prod_number'] == 'Epson') {
    if ($CheckedFolders['giclee'] == false) {
      $ReedPhotoPathFolder = $ReedPhotoPath . 'giclee/PI_' . $InvNum;
      echo 'Checking Folder ' . $ReedPhotoPathFolder . '<br />' . PHP_EOL;
      if (defined('TESTING') == false || TESTING == false) {
        if (!@ftp_chdir($conn_reed_id, $ReedPhotoPathFolder)) {
          ftp_mkdir($conn_reed_id, $ReedPhotoPathFolder);
          ftp_chdir($conn_reed_id, '/');
        }
      } else {
        if (!is_dir('/srv/proimage/current/toPhatFoto' . $ReedPhotoPathFolder)) {
          mkdir('/srv/proimage/current/toPhatFoto' . $ReedPhotoPathFolder);
        }
      }
      $CheckedFolders['giclee'] = true;
    }
  } else {
    if ($CheckedFolders['frontier'] == false) {
      $ReedPhotoPathFolder = $ReedPhotoPath . 'frontier/PI_' . $InvNum;
      echo 'Checking Folder ' . $ReedPhotoPathFolder . '<br />' . PHP_EOL;
      if (defined('TESTING') == false || TESTING == false) {
        if (!@ftp_chdir($conn_reed_id, $ReedPhotoPathFolder)) {
          ftp_mkdir($conn_reed_id, $ReedPhotoPathFolder);
          ftp_chdir($conn_reed_id, '/');
        }
      } else {
        if (!is_dir('/srv/proimage/current/toPhatFoto' . $ReedPhotoPathFolder)) {
          mkdir('/srv/proimage/current/toPhatFoto' . $ReedPhotoPathFolder);
        }
      }
      $CheckedFolders['frontier'] = true;
    }
  }

  $ReedD = "";
  $Reed_contents = array();
  if (isset($ContentsFolders[$ReedPhotoPathFolder]) == false) {
    echo 'Retrieving Folder Contents ' . $ReedPhotoPathFolder . "/." . '<br />' . PHP_EOL;
    if (defined('TESTING') == false || TESTING == false) {
      $Reed_contents = ftp_nlist($conn_reed_id, $ReedPhotoPathFolder . "/.");
      $ContentsFolders[$ReedPhotoPathFolder] = $Reed_contents;
    }
    echo 'Folder Retreval Complete' . '<br />' . PHP_EOL;
  } else {
    $Reed_contents = $ContentsFolders[$ReedPhotoPathFolder];
  }
  if (is_array($Reed_contents)) {
    if (in_array($image, $Reed_contents)) {
      $ReedD = "D_";
    }
  }

  $count = (isset($mrk_array[$new_Folder])) ? count($mrk_array[$new_Folder]) : 0;
  $mrk_array[$new_Folder][$count]['id'] = $row_get_images['prod_number'];
  $mrk_array[$new_Folder][$count]['id_frontier'] = $row_get_images['prod_number_frontier'];
  $mrk_array[$new_Folder][$count]['size'] = $row_get_images['prod_name'];
  $mrk_array[$new_Folder][$count]['image'] = $image;
  $mrk_array[$new_Folder][$count]['asis'] = $row_get_images['invoice_image_asis'];
  $mrk_array[$new_Folder][$count]['bw'] = $row_get_images['invoice_image_bw'];
  $mrk_array[$new_Folder][$count]['sepia'] = $row_get_images['invoice_image_sepia'];

  $abssc3cmdFolderPath = $sc3cmdPath . "/" . $new_Folder . "/IMAGE" . "/";

  $imageStoreFolder = $photographerFolder . mb_convert_encoding($row_get_images['image_folder'], "UTF-8", "HTML-ENTITIES");
  $imageLargeFolder = explode('/', $imageStoreFolder);
  $imageLargeFolder[count($imageLargeFolder) - 2] = 'Large';
  $imageLargeFolder = implode('/', $imageLargeFolder);

  if (is_file($imageStoreFolder . $image)) {
    if (defined('TESTING') == false || TESTING == false) {
      $response = $s3->create_mpu_object($bucket, $abssc3cmdFolderPath . $D . $image, array(
          'fileUpload' => $imageStoreFolder . $image
      ));
    } else
      echo 'Testing skipping AWS' . '<br />' . PHP_EOL;

    // Upload Image to Reed
    if ($digital_download == false) {
      if (in_array($ReedPhotoPathFolder . '/' . $ReedD . $image, $LoadedImageList) == false) {
        echo 'Uploading Image ' . $ReedPhotoPathFolder . '/' . $ReedD . $image . '<br />' . PHP_EOL;
        if (defined('TESTING') == false || TESTING == false) {
          if (!@ftp_put($conn_reed_id, $ReedPhotoPathFolder . '/' . $ReedD . $image, $imageStoreFolder . $image, FTP_BINARY)) {
            echo 'Upload Failed Reconnecting' . '<br />' . PHP_EOL;
            connect_reed_photo(true);
            if (!ftp_put($conn_reed_id, $ReedPhotoPathFolder . '/' . $ReedD . $image, $imageStoreFolder . $image, FTP_BINARY)) {
              echo 'Upload Failed ';
              var_dump(error_get_last());
              echo '<br />' . PHP_EOL;
            }
          }
        } else {
          echo 'Testing Upload: ' . $ReedPhotoPathFolder . '/' . $ReedD . $image . '<br />' . PHP_EOL;
        }
      } else {
        echo 'Image ' . $ReedPhotoPathFolder . '/' . $ReedD . $image . ' already loaded' . '<br />' . PHP_EOL;
      }
    } else {
      echo 'Image ' . $ReedPhotoPathFolder . '/' . $ReedD . $image . ' DIGITAL DOWNLOAD' . '<br />' . PHP_EOL;
    }
  }
  $LoadedImageList[] = $ReedPhotoPathFolder . '/' . $ReedD . $image;

  echo 'Creating Thumbnail for: ' . $imageLargeFolder . $image . '<br />' . PHP_EOL;
  attachImagetoContactSheet($row_get_images['prod_name'], $row_get_images['prod_desc'], $imageLargeFolder, $image);
  sleep(1);
}

ping_SQLServer();
$query_get_images = "SELECT `prod_products`.`prod_name`, `prod_products`.`prod_desc`, `prod_products`.`prod_number`, `prod_products`.`prod_number_frontier`, `prod_products`.`prod_serial`, `orders_invoice_border`.*, `photo_event_images`.* 
	FROM `orders_invoice_border` 
	INNER JOIN `photo_event_images` 
		ON `photo_event_images`.`image_id` = `orders_invoice_border`.`image_id` 
	INNER JOIN `prod_products` 
		ON `prod_products`.`prod_id` = `orders_invoice_border`.`invoice_image_size_id`
	WHERE `invoice_id` = '$IId' ";
$get_images = mysql_query($query_get_images, $cp_connection) or die(mysql_error());
$z = 1;
while ($row_get_images = mysql_fetch_assoc($get_images)) {
  $digital_download = false;
  if ($dq_file == false && ($row_get_images['prod_serial'] == "099" || $row_get_images['prod_serial'] == "100")) {
    $dq_file = true;
  }

  if ($row_get_images['prod_serial'] == "099" || $row_get_images['prod_serial'] == "100") {
    $digital_download = true;
  }

  $Fonts = $r_path . 'xml/fonts.xml';
  $ImageId = $row_get_images['image_id'];
  $BorderId = $row_get_images['border_id'];
  $Horz = $row_get_images['invoice_horz'];
  $PX = $row_get_images['invoice_imgX'];
  $PY = $row_get_images['invoice_imgY'];
  $PBWidth = $row_get_images['invoice_bordW'];
  $PBHeight = $row_get_images['invoice_bordH'];
  $PIWidth = $row_get_images['invoice_imgW'];
  $PIHeight = $row_get_images['invoice_imgH'];
  $Text = $row_get_images['invoice_text'];
  $TX = $row_get_images['invoice_textX'];
  $TY = $row_get_images['invoice_textY'];
  $Font = $row_get_images['invoice_font'];
  $OFSize = $row_get_images['invoice_size'];
  $Color = $row_get_images['invoice_color'];
  $Bold = $row_get_images['invoice_bold'];
  $Italic = $row_get_images['invoice_italic'];

  define("BorderPreview", false);

  $new_Folder = "o" . $InvNum . "." . $row_get_images['prod_number'];
  $new_Folder = str_replace($replace, $with, $new_Folder);

  if ($row_get_images['invoice_image_asis'] > 0) {
    $Filter = false;
    define("PROCESS", true);
    include $r_path . 'scripts/cart/build_border.php';

    $image = $z . "T_" . $row_get_info['prod_name'] . "_" . (($row_get_images['invoice_image_image'] != "") ? $row_get_images['invoice_image_image'] : $row_get_images['image_tiny']);
    $image = mb_convert_encoding($image, "UTF-8", "HTML-ENTITIES");
    $image = str_replace($replace, $with, $image);

    $Border_temp = $Imager->TFile[$Imager->Indx];

    $abssc3cmdFolderPath = $sc3cmdPath . "/" . $new_Folder . "/IMAGE" . "/";
    if (defined('TESTING') == false || TESTING == false) {
      $response = $s3->create_mpu_object($bucket, $abssc3cmdFolderPath . $image, array('fileUpload' => $Border_temp));
    }

    // Reed Photography
    if ($CheckedFolders['frontier'] == false) {
      $ReedPhotoPathFolder = $ReedPhotoPath . 'frontier/PI_' . $InvNum;
      echo 'Checking Folder ' . $ReedPhotoPathFolder . '<br />' . PHP_EOL;
      if (defined('TESTING') == false || TESTING == false) {
        if (!@ftp_chdir($conn_reed_id, $ReedPhotoPathFolder)) {
          ftp_mkdir($conn_reed_id, $ReedPhotoPathFolder);
          ftp_chdir($conn_reed_id, '/');
        }
      }
      $CheckedFolders['frontier'] = true;
    }

    // Upload Image to Reed

    if ($digital_download == false) {
      if (in_array($ReedPhotoPathFolder . '/' . $image, $LoadedImageList) == false) {
        echo 'Uploading Image: ' . $ReedPhotoPathFolder . '/' . $image . '<br />' . PHP_EOL;
        if (defined('TESTING') == false || TESTING == false) {
          if (!@ftp_put($conn_reed_id, $ReedPhotoPathFolder . '/' . $image, $Border_temp, FTP_BINARY)) {
            echo 'Upload Failed Reconnecting' . '<br />' . PHP_EOL;
            connect_reed_photo(true);
            if (!ftp_put($conn_reed_id, $ReedPhotoPathFolder . '/' . $image, $Border_temp, FTP_BINARY)) {
              echo 'Upload Failed ';
              var_dump(error_get_last());
              echo '<br />' . PHP_EOL;
            }
          }
        } else {
          echo 'Testing Upload: ' . $ReedPhotoPathFolder . '/' . $image . '<br />' . PHP_EOL;
        }
        $LoadedImageList[] = $ReedPhotoPathFolder . '/' . $image;
      } else {
        echo 'Image ' . $ReedPhotoPathFolder . '/' . $image . ' already loaded' . '<br />' . PHP_EOL;
      }
    } else {
      echo 'Image ' . $ReedPhotoPathFolder . '/' . $image . ' DIGITAL DOWNLOAD' . '<br />' . PHP_EOL;
    }

    unlink($Border_temp);

    $count = (isset($mrk_array[$new_Folder])) ? count($mrk_array[$new_Folder]) : 0;
    $mrk_array[$new_Folder][$count]['id'] = $row_get_images['prod_number'];
    $mrk_array[$new_Folder][$count]['id_frontier'] = $row_get_images['prod_number_frontier'];
    $mrk_array[$new_Folder][$count]['size'] = $row_get_images['prod_name'];
    $mrk_array[$new_Folder][$count]['image'] = $image;
    $mrk_array[$new_Folder][$count]['asis'] = $row_get_images['invoice_image_asis'];
    $mrk_array[$new_Folder][$count]['bw'] = 0;
    $mrk_array[$new_Folder][$count]['sepia'] = 0;

    sleep(2);
  }
  if ($row_get_images['invoice_image_bw'] > 0) {
    $Filter = "BlackWhite";
    define("PROCESS", true);
    include $r_path . 'scripts/cart/build_border.php';

    $image = $z . "B_" . $row_get_info['prod_name'] . "_" . (($row_get_images['invoice_image_image'] != "") ? $row_get_images['invoice_image_image'] : $row_get_images['image_tiny']);
    $image = mb_convert_encoding($image, "UTF-8", "HTML-ENTITIES");
    $image = str_replace($replace, $with, $image);

    $Border_temp = $Imager->TFile[$Imager->Indx];

    $abssc3cmdFolderPath = $sc3cmdPath . "/" . $new_Folder . "/IMAGE" . "/";
    if (defined('TESTING') == false || TESTING == false) {
      $response = $s3->create_mpu_object($bucket, $abssc3cmdFolderPath . $image, array(
          'fileUpload' => $Border_temp
      ));
    } else
      echo 'Testing skipping AWS' . '<br />' . PHP_EOL;

    // Reed Photography
    if ($CheckedFolders['frontier'] == false) {
      $ReedPhotoPathFolder = $ReedPhotoPath . 'frontier/PI_' . $InvNum;
      echo 'Checking Folder ' . $ReedPhotoPathFolder . '<br />' . PHP_EOL;
      if (defined('TESTING') == false || TESTING == false) {
        if (!@ftp_chdir($conn_reed_id, $ReedPhotoPathFolder)) {
          ftp_mkdir($conn_reed_id, $ReedPhotoPathFolder);
          ftp_chdir($conn_reed_id, '/');
        }
      }
      $CheckedFolders['frontier'] = true;
    }

    // Upload Image to Reed
    if ($digital_download == false) {
      if (in_array($ReedPhotoPathFolder . '/' . $image, $LoadedImageList) == false) {
        echo 'Uploading Image: ' . $ReedPhotoPathFolder . '/' . $image . '<br />' . PHP_EOL;
        if (defined('TESTING') == false || TESTING == false) {
          if (!@ftp_put($conn_reed_id, $ReedPhotoPathFolder . '/' . $image, $Border_temp, FTP_BINARY)) {
            echo 'Upload Failed Reconnecting' . '<br />' . PHP_EOL;
            connect_reed_photo(true);
            if (ftp_put($conn_reed_id, $ReedPhotoPathFolder . '/' . $image, $Border_temp, FTP_BINARY)) {
              echo 'Upload Failed ';
              var_dump(error_get_last());
              echo '<br />' . PHP_EOL;
            }
          }
        } else
          echo 'Testing Upload: ' . $ReedPhotoPathFolder . '/' . $image . '<br />' . PHP_EOL;
        $LoadedImageList[] = $ReedPhotoPathFolder . '/' . $image;
      } else
        echo 'Image ' . $ReedPhotoPathFolder . '/' . $image . ' already loaded' . '<br />' . PHP_EOL;
    } else {
      echo 'Image ' . $ReedPhotoPathFolder . '/' . $image . 'DIGITAL DOWNLOAD' . '<br />' . PHP_EOL;
    }
    unlink($Border_temp);

    $count = (isset($mrk_array[$new_Folder])) ? count($mrk_array[$new_Folder]) : 0;
    $mrk_array[$new_Folder][$count]['id'] = $row_get_images['prod_number'];
    $mrk_array[$new_Folder][$count]['id_frontier'] = $row_get_images['prod_number_frontier'];
    $mrk_array[$new_Folder][$count]['size'] = $row_get_images['prod_name'];
    $mrk_array[$new_Folder][$count]['image'] = $image;
    $mrk_array[$new_Folder][$count]['asis'] = $row_get_images['invoice_image_bw'];
    $mrk_array[$new_Folder][$count]['bw'] = 0;
    $mrk_array[$new_Folder][$count]['sepia'] = 0;

    sleep(2);
  }
  if ($row_get_images['invoice_image_sepia'] > 0) {
    $Filter = "Sepia";
    define("PROCESS", true);
    include $r_path . 'scripts/cart/build_border.php';

    $image = $z . "S_" . $row_get_info['prod_name'] . "_" . (($row_get_images['invoice_image_image'] != "") ? $row_get_images['invoice_image_image'] : $row_get_images['image_tiny']);
    $image = mb_convert_encoding($image, "UTF-8", "HTML-ENTITIES");
    $image = str_replace($replace, $with, $image);

    $Border_temp = $Imager->TFile[$Imager->Indx];

    unset($Image2);
    $abssc3cmdFolderPath = $sc3cmdPath . "/" . $new_Folder . "/IMAGE" . "/";
    if (defined('TESTING') == false || TESTING == false) {
      $response = $s3->create_mpu_object($bucket, $abssc3cmdFolderPath . $image, array(
          'fileUpload' => $Border_temp
      ));
    } else
      echo 'Testing skipping AWS' . '<br />' . PHP_EOL;

    // Reed Photography
    if ($CheckedFolders['frontier'] == false) {
      $ReedPhotoPathFolder = $ReedPhotoPath . 'frontier/PI_' . $InvNum;
      echo 'Checking Folder ' . $ReedPhotoPathFolder . '<br />' . PHP_EOL;
      if (defined('TESTING') == false || TESTING == false) {
        if (!@ftp_chdir($conn_reed_id, $ReedPhotoPathFolder)) {
          ftp_mkdir($conn_reed_id, $ReedPhotoPathFolder);
          ftp_chdir($conn_reed_id, '/');
        }
      }
      $CheckedFolders['frontier'] = true;
    }
    // Upload Image to Reed
    if ($digital_download == false) {
      if (in_array($ReedPhotoPathFolder . '/' . $image, $LoadedImageList) == false) {
        echo 'Uploading Image: ' . $ReedPhotoPathFolder . '/' . $image . '<br />' . PHP_EOL;
        if (defined('TESTING') == false || TESTING == false) {
          if (!@ftp_put($conn_reed_id, $ReedPhotoPathFolder . '/' . $image, $Border_temp, FTP_BINARY)) {
            echo 'Upload Failed Reconnecting' . '<br />' . PHP_EOL;
            connect_reed_photo(true);
            if (!ftp_put($conn_reed_id, $ReedPhotoPathFolder . '/' . $image, $Border_temp, FTP_BINARY)) {
              echo 'Upload Failed ';
              var_dump(error_get_last());
              echo '<br />' . PHP_EOL;
            }
          }
        } else {
          echo 'Testing Upload: ' . $ReedPhotoPathFolder . '/' . $image . '<br />' . PHP_EOL;
        }
        $LoadedImageList[] = $ReedPhotoPathFolder . '/' . $image;
      } else {
        echo 'Image ' . $ReedPhotoPathFolder . '/' . $image . ' already loaded' . '<br />' . PHP_EOL;
      }
    } else {
      echo 'Image ' . $ReedPhotoPathFolder . '/' . $image . ' DIGITAL DOWNLOAD' . '<br />' . PHP_EOL;
    }

    unlink($Border_temp);

    $count = (isset($mrk_array[$new_Folder])) ? count($mrk_array[$new_Folder]) : 0;
    $mrk_array[$new_Folder][$count]['id'] = $row_get_images['prod_number'];
    $mrk_array[$new_Folder][$count]['id_frontier'] = $row_get_images['prod_number_frontier'];
    $mrk_array[$new_Folder][$count]['size'] = $row_get_images['prod_name'];
    $mrk_array[$new_Folder][$count]['image'] = $image;
    $mrk_array[$new_Folder][$count]['asis'] = $row_get_images['invoice_image_sepia'];
    $mrk_array[$new_Folder][$count]['bw'] = 0;
    $mrk_array[$new_Folder][$count]['sepia'] = 0;

    sleep(2);
  }
  ini_restore("memory_limit");
  ini_restore("max_execution_time");
  $z++;
}
unset($CheckedFolders);
unset($LoadedImageList);

if ($dq_file == true) {

  ping_SQLServer();
  $query_get_email = "(SELECT `cust_customers`.`cust_handle`, `cust_customers`.`cust_email`, `inv_customer`.`cust_email` AS `inv_email`, `orders_invoice`.`invoice_enc`, `orders_invoice`.`invoice_id`
		FROM `orders_invoice`
		INNER JOIN `cust_customers` AS `inv_customer` 
			ON `inv_customer`.`cust_id` = `orders_invoice`.`cust_id`
		INNER JOIN `orders_invoice_photo` 
			ON `orders_invoice_photo`.`invoice_id` = `orders_invoice`.`invoice_id` 
		INNER JOIN `photo_event_images` 
			ON `photo_event_images`.`image_id` = `orders_invoice_photo`.`image_id` 
		INNER JOIN `cust_customers` 
			ON `cust_customers`.`cust_id` = `photo_event_images`.`cust_id` 
		WHERE `orders_invoice`.`invoice_id` = '$IId'
		GROUP BY `orders_invoice`.`invoice_id` )
	UNION
		(SELECT `cust_customers`.`cust_handle`, `cust_customers`.`cust_email`, `inv_customer`.`cust_email` AS `inv_email`, `orders_invoice`.`invoice_enc`, `orders_invoice`.`invoice_id`
		FROM `orders_invoice` 
		INNER JOIN `cust_customers` AS `inv_customer` 
			ON `inv_customer`.`cust_id` = `orders_invoice`.`cust_id`
		INNER JOIN `orders_invoice_border` 
			ON `orders_invoice_border`.`invoice_id` = `orders_invoice`.`invoice_id` 
		INNER JOIN `photo_event_images` 
			ON `photo_event_images`.`image_id` = `orders_invoice_border`.`image_id` 
		INNER JOIN `cust_customers` 
			ON `cust_customers`.`cust_id` = `photo_event_images`.`cust_id`
		WHERE `orders_invoice`.`invoice_id` = '$IId'
		GROUP BY `orders_invoice`.`invoice_id` ) ";
  $get_email = mysql_query($query_get_email, $cp_connection) or die(mysql_error());
  $row_get_email = mysql_fetch_assoc($get_email);

  $Handle = $row_get_email['cust_handle'];
  $reciever = $row_get_email['cust_email'];
  $Email = $row_get_email['inv_email'];
  $encnum = $row_get_email['invoice_enc'];
  $BioImage = "Logo.jpg";
  if (is_file($photographerFolder . "photographers/" . $Handle . "/" . $BioImage)) {
    list($BioWidth, $BioHeight) = getimagesize($photographerFolder . "photographers/" . $Handle . "/" . $BioImage);
    $BioImage = $photographerFolder . "photographers/" . $Handle . "/" . $BioImage;
    if ($BioWidth > 700) {
      $Ration = 700 / $BioWidth;
      $BioWidth = 700;
      $BioHeight = $BioHeight * $Ration;
    }
  } else
    $BioImage = false;

  ob_start();
  include($r_path . 'checkout/digital_download_2.php');
  $page2 = ob_get_contents();
  ob_end_clean();

  $mail = new PHPMailer();
  $mail->Host = PI_SMTP;
  $mail->IsHTML(true);
  $mail->IsSendMail();
  $mail->Sender = PI_EMAIL;
  $mail->Hostname = PI_HOSTNAME;
  $mail->From = $reciever;
  $mail->FromName = $reciever;
  $mail->AddAddress($Email);
  $mail->Subject = "Your Digital Download";
  $mail->Body = $page2;
  $mail->Send();
  unset($page);

  unset($mail);
  unset($Handle);
  unset($reciever);
  unset($Email);
  unset($BioImage);
  unset($row_get_email);
  unset($get_email);
  unset($query_get_email);
  unset($page2);
}
unset($dq_file);

$Has_Reed_frontiere = false;
$Has_Reed_giclee = false;
$Reed_Print_frontier_array = array();
$Reed_Print_giclee_array = array();

$cmdMsg = '';
foreach ($mrk_array as $key => $value) {
  $pid = 0;

  ob_start();
  echo '[HDR]' . $eol . 'GEN REV = 01.00' . $eol . 'GEN CRT = "NORITSU KOKI" -01.00' . $eol . 'GEN DTM = ' . date("Y:m:d:H:i:s") . $eol . 'USR NAM = "' . $InvNum . '"' . $eol . 'VUQ RGN = BGN' . $eol . 'VUQ VNM = "NORITSU KOKI" -ATR "QSSPrint"' . $eol . 'VUQ VER = 01.00' . $eol . 'PRT PSL = NML -PSIZE "' . $value[0]['size'] . ' m"' . $eol . 'PRT PCH = ' . $value[0]['id'] . $eol . 'GEN INP = "ZIP"' . $eol . 'VUQ RGN = END' . $eol . $eol;
  foreach ($value as $k => $v) {

    if ($v['asis'] != 0) {
      $pid++;
      $tid = $pid;
      $color_code = 'C';
      while (strlen($tid) < 3) {
        $tid = "0" . $tid;
      }
      $temp_qty = $v['asis'];
      while (strlen($temp_qty) < 3) {
        $temp_qty = "0" . $temp_qty;
      }
      echo '[JOB]' . $eol . 'PRT PID = ' . $tid . $eol . 'PRT TYP = STD' . $eol . 'PRT QTY = ' . $temp_qty . $eol . 'IMG FMT = EXIF2 -J' . $eol . '<IMG SRC = "../IMAGE/' . $v['image'] . '">' . $eol . 'VUQ RGN = BGN' . $eol . 'VUQ VNM = "NORITSU KOKI" -ATR "QSSPrint"' . $eol . 'VUQ VER = 01.00' . $eol . 'PRT CVP1 = 0' . $eol . 'PRT CVP2 = 0' . $eol . 'VUQ RGN = END' . $eol . $eol;
    }
    if ($v['bw'] != 0) {
      $pid++;
      $tid = $pid;
      $color_code = 'B';
      while (strlen($tid) < 3) {
        $tid = "0" . $tid;
      }
      $temp_qty = $v['bw'];
      while (strlen($temp_qty) < 3) {
        $temp_qty = "0" . $temp_qty;
      }
      echo '[JOB]' . $eol . 'PRT PID = ' . $tid . $eol . 'PRT TYP = STD' . $eol . 'PRT QTY = ' . $temp_qty . $eol . 'IMG FMT = EXIF2 -J' . $eol . '<IMG SRC = "../IMAGE/' . $v['image'] . '">' . $eol . 'VUQ RGN = BGN' . $eol . 'VUQ VNM = "NORITSU KOKI" -ATR "QSSPrint"' . $eol . 'VUQ VER = 01.00' . $eol . 'PRT CVP1 = 0' . $eol . 'PRT CVP2 = 0' . $eol . 'VUQ RGN = END' . $eol . $eol;
    }
    if ($v['sepia'] != 0) {
      $pid++;
      $tid = $pid;
      $color_code = 'S';
      while (strlen($tid) < 3) {
        $tid = "0" . $tid;
      }
      $temp_qty = $v['sepia'];
      while (strlen($temp_qty) < 3) {
        $temp_qty = "0" . $temp_qty;
      }
      echo '[JOB]' . $eol . 'PRT PID = ' . $tid . $eol . 'PRT TYP = STD' . $eol . 'PRT QTY = ' . $temp_qty . $eol . 'IMG FMT = EXIF2 -J' . $eol . '<IMG SRC = "../IMAGE/' . $v['image'] . '">' . $eol . 'VUQ RGN = BGN' . $eol . 'VUQ VNM = "NORITSU KOKI" -ATR "QSSPrint"' . $eol . 'VUQ VER = 01.00' . $eol . 'PRT CVP1 = 0' . $eol . 'PRT CVP2 = 0' . $eol . 'VUQ RGN = END' . $eol . $eol;
    }

    if ($v['id'] == 'Epson') {
      if (!empty($v['id_frontier'])) {
        $Has_Reed_giclee = true;
        if (!isset($Reed_Print_giclee_array[md5($v['image'])])) {
          $Reed_Print_giclee_array[md5($v['image'])] = "[Neg]" . $eol;
          $Reed_Print_giclee_array[md5($v['image'])] .= "NegNumber=" . pathinfo($v['image'], PATHINFO_FILENAME) . $eol;
        }
        $tmpElem = &$Reed_Print_giclee_array[md5($v['image'])];
        //$tmpElem .= "Crop=C".$eol;	
        $tmpElem .= "[Unit]" . $eol;
        $tmpElem .= "Code=" . $value[0]['id_frontier'] . $eol;
        $tmpElem .= "Qty=" . intval($temp_qty) . $eol;
        $tmpElem .= "Color=" . $color_code . $eol;
      }
    } else {
      $Has_Reed_frontiere = true;
      if (!empty($v['id_frontier'])) {
        if (!isset($Reed_Print_frontier_array[md5($v['image'])])) {
          $Reed_Print_frontier_array[md5($v['image'])] = "[Neg]" . $eol;
          $Reed_Print_frontier_array[md5($v['image'])] .= "NegNumber=" . pathinfo($v['image'], PATHINFO_FILENAME) . $eol;
        }
        $tmpElem = &$Reed_Print_frontier_array[md5($v['image'])];
        //$tmpElem .= "Crop=C".$eol;	
        $tmpElem .= "[Unit]" . $eol;
        $tmpElem .= "Code=" . $v['id_frontier'] . $eol;
        $tmpElem .= "Qty=" . intval($temp_qty) . $eol;
        $tmpElem .= "Color=" . $color_code . $eol;
      }
    }
  }
  $print = ob_get_contents();
  ob_end_clean();

  echo $cmdMsg;

  $mrks3cmdPath = $sc3cmdPath . "/" . $key . "/MISC" . "/";
  $file_s3cmdmrk = $mrks3cmdPath . "AUTPRINT.MRK";

  $s3cmdtmpfname = tempnam("/tmp", "FOO" . time());
  $handle = fopen($s3cmdtmpfname, "w");
  fwrite($handle, $print);
  fclose($handle);
  if (defined('TESTING') == false || TESTING == false) {
    $response = $s3->create_mpu_object($bucket, $file_s3cmdmrk, array(
        'fileUpload' => $s3cmdtmpfname
    ));
  } else {
    echo 'Testing skipping AWS' . '<br />' . PHP_EOL;
    if (!is_dir('/srv/proimage/current/toPhatFoto' . $ReedPhotoPath . 'MISC')) {
      mkdir('/srv/proimage/current/toPhatFoto' . $ReedPhotoPath . 'MISC');
    }
    if (!is_dir('/srv/proimage/current/toPhatFoto' . $ReedPhotoPath . 'MISC/' . $key)) {
      mkdir('/srv/proimage/current/toPhatFoto' . $ReedPhotoPath . 'MISC/' . $key);
    }
    copy($s3cmdtmpfname, '/srv/proimage/current/toPhatFoto' . $ReedPhotoPath . 'MISC/' . $key . '/AUTPRINT.MRK');
  }
  unlink($s3cmdtmpfname);
}

$Reed_Print_frontier = "[Order]" . $eol;
$Reed_Print_frontier .= "OrderId=PI_" . $InvNum . $eol;
$Reed_Print_frontier .= "CustomersName=" . $row_get_photo['cust_cname'] . $eol;
$Reed_Print_frontier .= implode('', $Reed_Print_frontier_array);
$Reed_Print_giclee = "[Order]" . $eol;
$Reed_Print_giclee = "OrderId=PI_" . $InvNum . $eol;
$Reed_Print_giclee = "CustomersName=" . $row_get_photo['cust_cname'] . $eol;
$Reed_Print_giclee .= implode('', $Reed_Print_giclee_array);
var_dump($Reed_Print_frontier);
if ($Has_Reed_frontiere == true) {
  $frontiere_txt = tempnam("/tmp", "FOOfrontier" . time());
  $handle = fopen($frontiere_txt, "w");
  fwrite($handle, $Reed_Print_frontier);
  fclose($handle);

  // Upload Image to Reed
  if (defined('TESTING') == true && TESTING == true) {
    echo 'Uploading Print File /srv/proimage/current/toPhatFoto' . $ReedPhotoPath . 'frontier/PI_' . $InvNum . '.txt' . '<br />' . PHP_EOL;
    copy($frontiere_txt, '/srv/proimage/current/toPhatFoto' . $ReedPhotoPath . 'frontier/PI_' . $InvNum . '.txt');
  } else {
    echo 'Uploading Print File ' . $ReedPhotoPath . 'frontier/PI_' . $InvNum . '.txt' . '<br />' . PHP_EOL;
    if (!@ftp_put($conn_reed_id, $ReedPhotoPath . 'frontier/PI_' . $InvNum . '.txt', $frontiere_txt, FTP_BINARY)) {
      connect_reed_photo(true);
      if (!ftp_put($conn_reed_id, $ReedPhotoPath . 'frontier/PI_' . $InvNum . '.txt', $frontiere_txt, FTP_BINARY)) {
        echo 'Upload Failed ';
        var_dump(error_get_last());
        echo '<br />' . PHP_EOL;
      }
    }
  }
  unlink($frontiere_txt);
}

if ($Has_Reed_giclee == true) {
  $Gicliee_txt = tempnam("/tmp", "FOOgiclee" . time());
  $handle = fopen($Gicliee_txt, "w");
  fwrite($handle, $Reed_Print_giclee);
  fclose($handle);

  if (defined('TESTING') == true && TESTING == true) {
    echo 'Uploading Print File /srv/proimage/current/toPhatFoto' . $ReedPhotoPath . 'giclee/PI_' . $InvNum . '.txt' . '<br />' . PHP_EOL;
    copy($Gicliee_txt, '/srv/proimage/current/toPhatFoto' . $ReedPhotoPath . 'giclee/PI_' . $InvNum . '.txt');
  } else {
    echo 'Uploading Print File ' . $ReedPhotoPath . 'giclee/PI_' . $InvNum . '.txt' . '<br />' . PHP_EOL;
    if (!@ftp_put($conn_reed_id, $ReedPhotoPath . 'giclee/PI_' . $InvNum . '.txt', $Gicliee_txt, FTP_BINARY)) {
      connect_reed_photo(true);
      if (!ftp_put($conn_reed_id, $ReedPhotoPath . 'giclee/PI_' . $InvNum . '.txt', $Gicliee_txt, FTP_BINARY)) {
        echo 'Upload Failed ';
        var_dump(error_get_last());
        echo '<br />' . PHP_EOL;
      }
    }
  }
  unlink($Gicliee_txt);
}

$Manifest_txt = tempnam("/tmp", "FOOmanifest" . time());
$handle = fopen($Manifest_txt, "w");
fwrite($handle, time());
fclose($handle);

if (defined('TESTING') == true && TESTING == true) {
  echo 'Uploading Print File /srv/proimage/current/toPhatFoto' . $ReedPhotoPath . 'PI_' . $InvNum . '.mnf' . '<br />' . PHP_EOL;
  copy($Manifest_txt, '/srv/proimage/current/toPhatFoto' . $ReedPhotoPath . 'PI_' . $InvNum . '.mnf');
} else {
  echo 'Uploading Print File ' . $ReedPhotoPath . 'PI_' . $InvNum . '.mnf' . '<br />' . PHP_EOL;
  if (!@ftp_put($conn_reed_id, $ReedPhotoPath . 'PI_' . $InvNum . '.mnf', $Manifest_txt, FTP_BINARY)) {
    connect_reed_photo(true);
    if (!ftp_put($conn_reed_id, $ReedPhotoPath . 'PI_' . $InvNum . '.mnf', $Manifest_txt, FTP_BINARY)) {
      echo 'Upload Failed ';
      var_dump(error_get_last());
      echo '<br />' . PHP_EOL;
    }
  }
}
unlink($Manifest_txt);

ob_start();
include($r_path . 'checkout/invoice.php');
$invoice_text = ob_get_contents();
ob_end_clean();

if (defined('TESTING') == true && TESTING == true) {
  echo 'Uploading Print File /srv/proimage/current/toPhatFoto' . $ReedPhotoPath . 'PI_' . $InvNum . '_invoice.html' . '<br />' . PHP_EOL;
  copy($Manifest_txt, '/srv/proimage/current/toPhatFoto' . $ReedPhotoPath . 'PI_' . $InvNum . '_invoice.html');
} else {
  echo 'Uploading Print File ' . $ReedPhotoPath . 'PI_' . $InvNum . '_invoice.html' . '<br />' . PHP_EOL;
  if (!@ftp_put($conn_reed_id, $ReedPhotoPath . 'PI_' . $InvNum . '_invoice.html', $invoice_text, FTP_BINARY)) {
    connect_reed_photo(true);
    if (!ftp_put($conn_reed_id, $ReedPhotoPath . 'PI_' . $InvNum . '_invoice.html', $invoice_text, FTP_BINARY)) {
      echo 'Upload Failed ';
      var_dump(error_get_last());
      echo '<br />' . PHP_EOL;
    }
  }
}
unlink($invoice_text);

if (defined('TESTING') == false || TESTING == false) {
  ftp_close($conn_reed_id);
}
ping_SQLServer();

// Send Email
if (count($contactSheet) > 0) {
  $imgId = md5(microtime());
  $lab_mail->AddEmbeddedImage($ImageProcessor->TFile[$ImageProcessor->Indx], $imgId, $InvNum . '_Contact_Sheet.jpg', 'base64', 'image/jpeg');
  $lab_mail->Body = str_replace('</body>', '<img src="cid:' . $imgId . '" /></body>', $lab_mail->Body);
}

if (defined('TESTING') == true && TESTING == true) {
  //$ImageProcessor->OutputServer('Test.jpg', '/var/www/html/ProImage_Software/Test/');
} else {
  $lab_mail->Send();
}

$ImageProcessor->Kill();
