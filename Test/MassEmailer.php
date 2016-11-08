<?

$count = count(explode("/", substr($_SERVER['PHP_SELF'], 1))) - 1;
$r_path = "";
for ($n = 0; $n < $count; $n++)
    $r_path .= "../";
define("Allow Scripts", true);

set_time_limit(0);
ini_set('max_execution_time', 600);

if (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN')
    $eol = "\r\n";
else if (strtoupper(substr(PHP_OS, 0, 3)) == 'MAC')
    $eol = "\r";
else
    $eol = "\n";

require_once($r_path . 'scripts/cart/encrypt.php');
require_once($r_path . 'scripts/fnct_send_email.php');
require_once $r_path . 'scripts/fnct_phpmailer.php';
require_once($r_path . 'Connections/cp_connection.php');
mysql_select_db($database_cp_connection, $cp_connection);

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
            ON (photo_event_note_events.event_note_id = photo_event_note_events.event_note_id)	
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
                    AND ABS(TO_DAYS(`photo_event_notes`.`event_date`) - TO_DAYS(NOW())) = 0))
        GROUP BY `photo_event_notes`.`event_note_id`, `photo_event`.`event_id`;";
$get_exp = mysql_query($query_get_exp, $cp_connection) or die(mysql_error());
$total_get_exp = mysql_num_rows($get_exp);
if ($total_get_exp > 0) {
    while ($row_get_exp = mysql_fetch_assoc($get_exp)) {
        $EId = $row_get_exp['event_id'];
        $Handle = $row_get_exp['cust_handle'];
        $Event = $row_get_exp['event_num'];
        $Photographer = $row_get_exp['cust_fname'] . " " . $row_get_exp['cust_lname'];
        $PhotoEmail = $row_get_exp['cust_email'];
        $BioImage = "Logo.jpg";
        if (is_file($r_path . "photographers/" . $Handle . "/" . $BioImage)) {
            list($BioWidth, $BioHeight) = getimagesize($r_path . "photographers/" . $Handle . "/" . $BioImage);
            $BioImage = "photographers/" . $Handle . "/" . $BioImage;
            if ($BioWidth > 700) {
                $Ration = 700 / $BioWidth;
                $BioWidth = 700;
                $BioHeight = $BioHeight * $Ration;
            }
        } else {
            $BioImage = false;
        }
        $IName = $row_get_exp['image_tiny'];
        $Image = $r_path . substr($row_get_exp['image_folder'], 0, -11) . "Thumbnails/" . $IName;

        if ($row_get_exp['event_image'] != "") {
            $IName = "Notification";
            $TempName = tempnam("/tmp", $IName);
            $THandle = fopen($TempName, "w");
            fwrite($THandle, base64_decode($row_get_exp['event_image']));
            fclose($THandle);

            $Image = $TempName;
        } else if (!is_file($Image)) {
            $Image = false;
            $IName = false;
        }

        $query_get_book = "SELECT `email` FROM `photo_quest_book` WHERE `event_id` = '$EId' AND `promotion` = 'y' GROUP BY `email`";
        $get_book = mysql_query($query_get_book, $cp_connection) or die(mysql_error());
        $bcc = array();
        while ($row_get_book = mysql_fetch_assoc($get_book))
            array_push($bcc, $row_get_book['email']);
        //array_push($bcc,"development@proimagesoftware.com");

        echo $Handle . " - " . $row_get_exp['event_name'] . " - " . $Image . "<br />";

        $reciever = "info@proimagesoftware.com";
        $subject = "Pro Image Software";
        $text = "";
        if ($Image !== false)
            $text .= '<img class="img" src="cid:' . $IName . '" width="150" alt="' . $IName . '" align="left" vspace="5" hspace="5">';
        if (is_bool(strpos("<p>", strtolower($row_get_exp['event_message'])))) {
            $text .= "<p>" . $row_get_exp['event_message'] . "</p>";
        } else {
            $text .= $row_get_exp['event_message'];
        }
        $text .= '<p>If you would like to view the event please go to <a href="http://www.proimagesoftware.com/' . $Handle . '?code=' . $Event . '">http://www.proimagesoftware.com/' . $Handle . '?code=' . $Event . '</a></p><p>Thank you for being a loyal customer.' . $eol . $Photographer . '</p>';
        $text .= "<br clear=\"all\" /><br clear=\"all\" /><p>Unsubscribe from this newsletter by <a href=\"http://www.proimagesoftware.com/unsubscribe.php?token=" . substr(time(), 0, 5) . $EId . substr(time(), 5) . "\">clicking here</a></p>";

        ob_start();
        include($r_path . 'template_email_2.php');

        $page = ob_get_contents();
        ob_end_clean();

        $mail = new PHPMailer();
        //$mail -> IsSMTP();
        $mail->Host = "smtp.proimagesoftware.com";
        $mail->IsHTML(true);
        $mail->IsSendMail();
        $mail->From = $PhotoEmail;
        $mail->AddAddress($PhotoEmail);
        $mail->FromName = $PhotoEmail;
        $mail->Subject = str_replace("&amp;", "&", $subject);
        foreach ($bcc as $v)
            $mail->AddBCC($v);
        if ($Image !== false)
            $mail->AddEmbeddedImage($Image, $IName);
        $mail->Body = $page;
        print_r($mail);
        //$mail->Send();
        $Emailsent = true;

        $BioImage = false;
        $Image = false;
        if ($row_get_exp['event_image'] != "")
            unlink($TempName);
        unset($Image);
        unset($IName);
        unset($BioImage);
        unset($Handle);
        unset($Event);
        unset($Photographer);

        sleep(2);
    }
}
?>