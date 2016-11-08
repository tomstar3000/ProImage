<?php
$count = count(explode("/", substr($_SERVER['PHP_SELF'], 1))) - 1;
$r_path = "";
for ($n = 0; $n < $count; $n++)
  $r_path .= "../";
set_time_limit(0);
ini_set('max_execution_time', (60 * 60));
define('Allow Scripts', true);

require_once($r_path . 'scripts/fnct_clean_entry.php');
require_once($r_path . 'scripts/fnct_format_phone.php');
require_once($r_path . 'scripts/fnct_phpmailer.php');

session_cache_expire(1440);
session_start();
if (isset($_POST['cartjoin'])) {
  $cart = $_POST['cartjoin'];
  $photographer = $_POST['photographer'];
  $code = $_POST['ecode'];
  $qemail = $_POST['qemail'];

  $cart = explode("[-+-]", $cart);
  $disc = $cart[1];
  $cart = $cart[0];
  $_SESSION['cart'] = $cart;
  $_SESSION['disc'] = $disc;
  $_SESSION['photo'] = $photographer;
  $_SESSION['code'] = $code;
  $_SESSION['qemail'] = $qemail;
} else if(isset($_GET['Photographer'])){
  $photographer = clean_variable($_GET['Photographer'], true);
  $code = clean_variable($_GET['code'], true);
}
$Sent = false;
if (isset($_POST['Controller2']) && $_POST['Controller2'] == true) {
  if ($_POST['Time'] != $_SESSION['Time']) {
    $_SESSION['Time'] = $_POST['Time'];
    if (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN')
      $eol = "\r\n";
    else if (strtoupper(substr(PHP_OS, 0, 3)) == 'MAC')
      $eol = "\r";
    else
      $eol = "\n";

    $msg = "Name: " . sanatizeentry($_POST['Support_Name']) . $eol;
    $msg .= "E-mail: " . sanatizeentry($_POST['Support_Email']) . $eol;
    $msg .= "Phone: " . sanatizeentry(phone_number(eregi_replace("[^0-9]", "", $_POST['Support_Phone']))) . $eol;
    $msg .= "Problem: " . sanatizeentry($_POST['Support_Problem']) . $eol;
    $msg .= "Photographer: " . sanatizeentry($_POST['Photo']) . $eol;
    $msg .= "Code: " . sanatizeentry($_POST['Code']) . $eol;
    $msg .= "Comments: " . $eol . sanatizeentry($_POST['Support_Comments']) . $eol;

    $msg = mb_convert_encoding($msg, "UTF-8", "HTML-ENTITIES");

    $server = $_SERVER['HTTP_HOST'];
    if (strpos("http:", strtolower($_SERVER['HTTP_HOST'])) != false)
      $server = substr($_SERVER['HTTP_HOST'], 6);
    if (strpos("https:", strtolower($_SERVER['HTTP_HOST'])) != false)
      $server = substr($_SERVER['HTTP_HOST'], 7);
    if (strpos("www", strtolower($_SERVER['HTTP_HOST'])) != false)
      $server = substr($_SERVER['HTTP_HOST'], 4);

    $mail = new PHPMailer();
    //$mail -> IsSMTP();
    $mail->Host = "smtp." . $server;
    $mail->IsHTML(false);
    $mail->IsSendMail();
    $mail->Sender = "info@proimagesoftware.com";
    $mail->Hostname = "proimagesoftware.com";
    $mail->From = "support@proimagesoftware.com";
    $mail->FromName = "support@proimagesoftware.com";
    $mail->AddAddress("support@proimagesoftware.com");
    $mail->Subject = "Support Ticket";
    $mail->Body = $msg;
    $mail->Send();

    $Sent = true;
  }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <link rel="shortcut icon" href="http://www.proimagesoftware.com/photoexpress.ico" type="image/x-icon" />
    <link rel="icon" href="http://www.proimagesoftware.com/photoexpress.ico" type="image/x-icon" />
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
    <title>ProImageSoftware</title>
    <link href="/stylesheets/photoexpress.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="/javascript/GoogleTracker.js"></script>
    <script type="text/javascript" src="/javascript/GoogleTracker2.js"></script>
    <script type="text/javascript">
      function MM_findObj(n, d) { //v4.01
        var p, i, x;
        if (!d)
          d = document;
        if ((p = n.indexOf("?")) > 0 && parent.frames.length) {
          d = parent.frames[n.substring(p + 1)].document;
          n = n.substring(0, p);
        }
        if (!(x = d[n]) && d.all)
          x = d.all[n];
        for (i = 0; !x && i < d.forms.length; i++)
          x = d.forms[i][n];
        for (i = 0; !x && d.layers && i < d.layers.length; i++)
          x = MM_findObj(n, d.layers[i].document);
        if (!x && d.getElementById)
          x = d.getElementById(n);
        return x;
      }
      function MM_validateForm() { //v4.0
        var i, p, q, nm, test, num, min, max, errors = '', args = MM_validateForm.arguments;
        for (i = 0; i < (args.length - 2); i += 3) {
          test = args[i + 2];
          val = MM_findObj(args[i]);
          if (val) {
            nm = val.name;
            if ((val = val.value) != "") {
              if (test.indexOf('isCheck') != -1) {
                if (document.getElementById(nm.replace(" ", "_")).checked == false)
                  errors += '- You must accept the User Agreement.\n';
              } else if (test.indexOf('isEmail') != -1) {
                p = val.indexOf('@');
                if (p < 1 || p == (val.length - 1))
                  errors += '- ' + nm + ' must contain an e-mail address.\n';
              } else if (test != 'R') {
                num = parseFloat(val);
                if (isNaN(val))
                  errors += '- ' + nm + ' must contain a number.\n';
                if (test.indexOf('inRange') != -1) {
                  p = test.indexOf(':');
                  min = test.substring(8, p);
                  max = test.substring(p + 1);
                  if (num < min || max < num)
                    errors += '- ' + nm + ' must contain a number between ' + min + ' and ' + max + '.\n';
                }
              }
            } else if (test.charAt(0) == 'R')
              errors += '- ' + nm + ' is required.\n';
          }
        }
        if (errors)
          alert('The following error(s) occurred:\n' + errors);
        document.MM_returnValue = (errors == '');
      }
    </script>
    <script language="JavaScript" type="text/javascript">
      var requiredMajorVersion = 9;
      var requiredMinorVersion = 0;
      var requiredRevision = 124;
    </script>
    <style>
      body, html{
        background:none;
        background-color:#FFF;
      }
      p, label {
        color: #000;
      }
    </style>
  </head>
  <body>
    <?php if ($Sent == false) { ?>
      <form method="post" enctype="multipart/form-data" name="SupportForm" id="SupportForm" action="/support.php">
        <p>We are sorry you are having issues. Please describe the problem below and a member of the Pro Image Software Team will be in contact as soon as possible.</p>
        <br clear="all" />
        <label for="Name"><strong>Name: </strong></label>
        <input type="text" name="Support Name" id="Support_Name" />
        <br clear="all" />
        <label for="Email"><strong>E-mail:</strong></label>
        <input type="text" name="Support Email" id="Support_Email" />
        <br clear="all" />
        <label for="Phone"><strong>Phone:</strong></label>
        <input type="text" name="Support Phone" id="Support_Phone" />
        <br clear="all" />
        <label for="Problem" class="Long"><strong>What is the problem you are having?</strong></label>
        <br clear="all" />
        <select name="Support Problem" id="Support_Problem">
          <option value="Viewing">Viewing</option>
          <option value="Favorites">Favorites</option>
          <option value="Cart">Cart</option>
          <option value="Checkout">Checkout</option>
          <option value="Discount Codes">Discount Codes</option>
          <option value="Greeting Cards">Greeting Cards</option>
          <option value="Other">Other</option>
        </select>
        <br clear="all" />
        <label for="Comments" class="Long"><strong>Comments:</strong></label>
        <br clear="all" />
        <textarea name="Support Comments" id="Support_Comments"></textarea>
        <input type="submit" name="Submit" id="Submit" value="Send Support" onclick="MM_validateForm('Support_Name', '', 'R', 'Support_Email', '', 'RisEmail', 'Support_Phone', '', 'R', 'Support_Comments', '', 'R');
            if (document.MM_returnValue) {
              Support(false);
              return true;
            } else {
              return false;
            }" />
        <input type="button" name="Cancel" id="Cancel" value="Cancel" onclick="javascript:window.parent.Support(false);" />
        <input type="hidden" name="Photo" id="Photo" value="<? echo $photographer; ?>" />
        <input type="hidden" name="Code" id="Code" value="<? echo $code; ?>" />
        <input type="hidden" name="Time" id="Time" value="<? echo time(); ?>" />
        <input type="hidden" name="Controller2" id="Controller2" value="true" />
      </form>
    <?php } else { ?>
      <p>Your support e-mail has been sent, a member of the Pro Image team will respond as soon as possible.</p>
      <p><a href="#" title="Close" onclick="javascript:window.parent.Support(false);
          return false;">Close</a></p>
      <?php } ?>
  </body>
</html>
