<?
$count = count(explode("/", substr($_SERVER['PHP_SELF'], 1))) - 1;
$r_path = "";
for ($n = 0; $n < $count; $n++)
    $r_path .= "../";
define('Allow Scripts', true);
define('PAGE', "Bio");
require_once($r_path . 'scripts/cart/ssl_paths.php');
require_once($r_path . 'scripts/fnct_clean_entry.php');
require_once($r_path . 'scripts/fnct_format_phone.php');
require_once($r_path . 'Connections/cp_connection.php');
require_once($r_path . 'scripts/fnct_sql_processor.php');

require_once($r_path . 'includes/_PhotoInfo.php');
require_once($r_path . 'includes/_Guestbook.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <? include $r_path . 'includes/_metadata.php'; ?>
        <link href="/css/Photographer.css" rel="stylesheet" type="text/css" />
<? if ($launch_full === true) { ?>
            <script type="text/JavaScript">
                AEV_new_window("<? echo $GoTo; ?>","ProImageSoftware",null,null,null,null,null,null,null,null,true);
            </script>
                <? } ?>
    </head>
    <body>
        <div id="Container">
            <div id="Logo">
                <?
                if ($getInfo[0]['cust_image'] != "") {
                    $data = array(
                        'imagetype' => 'logo',
                        'image' => $handle . '/' . $getInfo[0]['cust_image'],
                        'width' => 998,
                        'height' => 387,
                        'salt' => time(),
                    );
                    require_once $r_path . 'scripts/cart/encrypt.php';
                    $data = base64_encode(encrypt_data(serialize($data)));
                    echo '<img src="/images/image.php?data=' . $data . '"  />';
                } else {
                    ?>
                    <img src="/images/spacer.gif" width="998" height="387" />
                    <? } ?>
            </div>
            <div id="Content">
<? include $r_path . 'includes/_PhotoNavigation.php'; ?>
                <div id="TextLong">
                    <h1><? echo $getInfo[0]['cust_cname']; ?></h1>
        <? if (is_file($r_path . 'photographers/' . $handle . '/Bio_Image.jpg')) { ?>
                        <img src="/photographers/<? echo $handle; ?>/Bio_Image.jpg" border="0" align="left" class="bio_photo" />
<? } ?>
                    <p><? echo $getInfo[0]['cust_desc_2']; ?></p>
                </div>
                <br clear="all" />
            </div>
        </div>
<? include $r_path . 'includes/_PhotoFooter.php'; ?>
    </body>
</html>
