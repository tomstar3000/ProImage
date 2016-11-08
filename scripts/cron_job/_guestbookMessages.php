<?

$count = count(explode("/", substr($_SERVER['PHP_SELF'], 1))) - 1;
$r_path = "";
for ($n = 0; $n < $count; $n++)
    $r_path .= "../";
define("Allow Scripts", true);
define("CronJob", true);

set_time_limit(0);
ini_set('max_execution_time', 600);

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

echo "Event Notifications<br />";
$query_get_exp = "SELECT `photo_event`.`event_id`, `photo_event`.`event_name`, `photo_event`.`event_num`,
            `photo_event`.`owner_email`, `cust_customers`.`cust_handle`, `cust_customers`.`cust_cname`, `cust_customers`.`cust_fname`, 
            `cust_customers`.`cust_lname`, `cust_customers`.`cust_email`, `cust_customers`.`cust_city`, `cust_customers`.`cust_state`, 
            `cust_customers`.`cust_zip`, `cust_customers`.`cust_website`, `cust_customers`.`cust_thumb`, `photo_event_images`.`image_tiny` , 
            `photo_event_images`.`image_folder`, `photo_event_notes`.`event_message`, `photo_event_notes`.`event_image`, 
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
	WHERE `promotion` = 'y' 
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
                    AND ABS(TO_DAYS(`photo_event_notes`.`event_date`) - TO_DAYS(NOW())) = '0'))
	GROUP BY `photo_event_notes`.`event_note_id`;";
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
        $msg = str_replace('[Photographer]', $name, $msg);
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
            $mail->IsSendMail();
            $mail->Sender = "info@proimagesoftware.com";
            $mail->Hostname = "proimagesoftware.com";
            $mail->From = $PhotoEmail;
            $mail->FromName = $Photographer;
            $mail->ReplyTo = $PhotoEmail;
            $mail->AddAddress($v);
            //$mail -> AddAddress($row_get_exp['owner_email']);
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
        unset($BioImage);
        unset($Handle);
        unset($Event);
        unset($Desc);
        unset($Photographer);

        sleep(2);
    }
}
?>