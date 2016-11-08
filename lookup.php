<? $count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-1; $r_path = ""; for($n=0;$n<$count;$n++) $r_path .= "../";
define ("PhotoExpress Pro", true);
define('Allow Scripts',true);
require_once($r_path.'Connections/cp_connection.php');
require_once($r_path.'scripts/fnct_sql_processor.php');

require_once($r_path.'scripts/cart/ssl_paths.php');
$attempt_timer = 30;
$attempt_number = 5;
if(isset($_POST['Controller']) && $_POST['Controller'] == "lookup"){
	require_once $r_path.'scripts/fnct_clean_entry.php';
	
	$UName = clean_variable(trim($_POST['Look_Up_Username']),true);
	$Email = clean_variable($_POST['Email'], true);
	$SQ = clean_variable($_POST['Security_Question'], true);
	$Answer = clean_variable($_POST['Answer'], true);
	
	$getCheckUser = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
	if(!isset($_GET['condition'])){
		$getCheckUser->mysql("SELECT `cust_uncode`, `cust_email` FROM `cust_customers` WHERE `cust_username` = '$UName' AND `sec_quest_id` = '$SQ' AND `sec_quest_ans` = '$Answer';");
	} else if(isset($_GET['condition']) && $_GET['condition'] == "user"){
		$getCheckUser->mysql("SELECT `cust_username`, `cust_email` FROM `cust_customers` WHERE `cust_email` = '$Email' AND `sec_quest_id` = '$SQ' AND `sec_quest_ans` = '$Answer';");
	}
		
	if($getCheckUser->TotalRows() == 0){
		$Error = "User information could not be found";
	} else {
		require_once $r_path.'scripts/fnct_phpmailer.php';
		
		$getCheckUser = $getCheckUser->Rows();
		
		$Email = trim($getCheckUser[0]['cust_email']);
		//$Email = "development@proimagesoftware.com";
		$Sender = "info@proimagesoftware.com";
		$Host = "proimagesoftware.com";
		$Subject = "Pro Image Software: User Information Request";

		if (strtoupper(substr(PHP_OS,0,3))=='WIN')	$eol="\r\n"; 
		else if (strtoupper(substr(PHP_OS,0,3))=='MAC') $eol="\r"; 
		else $eol="\n"; 
			
		if(!isset($_GET['condition'])){
			require_once $r_path.'scripts/cart/encrypt.php';
			
			$PWord = decrypt_data($getCheckUser[0]['cust_uncode']);
			$msg = "Pro Image Software:".$eol."Your Password has been requested; if this has not been requested by you please change your current password and notify us at ".$Sender.$eol.$eol."Password: ".$PWord;
		} else if(isset($_GET['condition']) && $_GET['condition'] == "user"){	
			$UName = $getCheckUser[0]['cust_username'];
			$msg = "Pro Image Software:".$eol."Your Password has been requested; if this has not been requested by you please change your current password and notify us at ".$Sender.$eol.$eol."Username: ".$UName;
		}		
		$mail = new PHPMailer();
		$mail -> IsSendMail();
		$mail -> Host = "smtp.proimagesoftware.com";
		$mail -> IsHTML(false);
		$mail -> Sender = $Sender;
		$mail -> Hostname = $Host;
		$mail -> From = $Sender;
		$mail -> FromName = "ProImage Software";
		$mail -> AddAddress($Email);
		$mail -> Subject = $Subject;
		$mail -> Body = $msg;
		$mail->Send();
		
		$Error = "Your User Information has been sent to you.";
	}
	unset($Email); unset($PWord); unset($msg);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<? include $r_path.'includes/_metadata.php'; ?>
<script type="text/javascript" src="/javascript/custom-form-elements.js"></script>
<link href="/css/ProImageSoftware_09.css" rel="stylesheet" type="text/css" media="screen" />
</head>
<body>
<div id="Container">
  <? include $r_path.'includes/_navigation.php'; ?>
  <div id="Content2" class="Grey">
    <div id="Text">
      <h1 class="HdrForgotUsrPass"><span>Forgot Username/Password</span></h1>
      <br clear="all" />
      <div class="BgText">
        <div class="BgTextBottom">
          <div class="ColmnCntr"><br clear="all" />
            <form action="<? echo $_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']; ?>" method="post" name="Password_form" id="Password_form" enctype="multipart/form-data">
              <? if($Error){ ?>
              <div id="Error"><img src="/images/warning.jpg" width="30" height="29" border="0" align="absmiddle" style="float:left" /><img src="/images/warning.jpg" width="30" height="29" border="0" align="absmiddle"  style="float:right" />
                <p><? echo $Error; ?></p>
              </div>
              <? } if(!isset($_GET['condition'])){?>
              <h2>Forgot Your Password </h2>
              <label for="Username">Username *</label>
              <br clear="all" />
              <div class="CstmInput">
                <input type="text" name="Look Up Username" id="Look_Up_Username" title="Username">
              </div>
              <br clear="all" />
              <label for="Security_Question">Security Question</label>
              <br clear="all" />
              <select name="Security Question" id="Security_Question" title="Security Question" class="CstmFrmElmnt">
                <? $getSecQuest = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
									 $getSecQuest->mysql("SELECT * FROM `securty_questions` ORDER BY `question` ASC;");
									 foreach($getSecQuest->Rows() as $r){ ?>
                <option value="<? echo $r['question_id']; ?>" title="<? echo $r['question']; ?>"><? echo $r['question']; ?></option>
                <? } ?>
              </select>
              <br clear="all" />
              <label for="Answer">Answer *</label>
              <br clear="all" />
              <div class="CstmInput">
                <input type="text" name="Answer" id="Answer" title="Security Answer">
              </div>
              <br clear="all" />
              <a href="/lookup.php?condition=user" title="Forgot Username?">Forgot Username? </a><br clear="all" />
              <div class="BtnContinue">
                <input type="submit" name="btnSubmit" id="btnSumbit" value="Submit" title="Submit" onclick="MM_validateForm('Look_Up_Username','','R','Answer','','R');return document.MM_returnValue;" />
              </div>
              <input type="hidden" name="Controller" id="Controller" value="lookup">
              <? } else if (isset($_GET['condition'])){ ?>
              <h2>Forgot Username?</h2>
              <label for="Email">E-mail *</label>
              <br clear="all" />
              <div class="CstmInput">
                <input type="text" name="Email" id="Email" title="Email Address">
              </div>
              <br clear="all" />
              <label for="Security_Question">Security Question</label>
              <br clear="all" />
              <select name="Security Question" id="Security_Question" class="CstmFrmElmnt" title="Security Question">
                <? $getSecQuest = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
											$getSecQuest->mysql("SELECT * FROM `securty_questions` ORDER BY `question` ASC;");
											foreach($getSecQuest->Rows() as $r){ ?>
                <option value="<? echo $r['question_id']; ?>" title="<? echo $r['question']; ?>"><? echo $r['question']; ?></option>
                <? } ?>
              </select>
              <br clear="all" />
              <label for="Answer">Answer *</label>
              <br clear="all" />
              <div class="CstmInput">
                <input type="text" name="Answer" id="Answer" title="Answer">
              </div>
              <br clear="all" />
              <a href="/lookup.php" title="Forgot Password?">Forgot Password? </a><br clear="all" />
              <div class="BtnContinue">
                <input type="submit" name="btnSubmit" id="btnSumbit" value="Submit" title="Submit" onclick="MM_validateForm('Email','','RisEmail','Answer','','R');return document.MM_returnValue;" />
              </div>
              <input type="hidden" name="Controller" id="Controller" value="lookup">
              <? } ?>
              <br clear="all" />
            </form>
          </div>
          <br clear="all" />
        </div>
      </div>
      <br clear="all" />
    </div>
  </div>
  <? include $r_path.'includes/_footer.php'; ?>
</div>
</body>
</html>
