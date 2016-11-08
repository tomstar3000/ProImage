<? $count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-1; $r_path = ""; for($n=0;$n<$count;$n++)$r_path .= "../";

$sign_up = "photographer";
define ("Allow Scripts", true);
define("SignUp", true);

require_once($r_path.'scripts/cart/ssl_paths.php'); 
require_once($r_path.'Connections/cp_connection.php');

if($sign_up == "photographer"){
	include $r_path.'scripts/save_photographer.php';
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<? include $r_path.'includes/_metadata.php'; ?>
<link href="/css/ProImageSoftware_09.css" rel="stylesheet" type="text/css" media="screen" />
<script type="text/javascript" src="/javascript/set_tel_numbers.js"></script>
<script type="text/javascript" src="/javascript/custom-form-elements.js"></script>
</head>
<body>
<div id="Container">
  <? include $r_path.'includes/_navigation.php'; ?>
  <div id="Content2" class="Grey">
    <div id="Text">
      <h1 class="HdrSignUp"><span>Sign Up</span></h1>
      <br clear="all" />
      <div class="BgText">
        <div class="BgTextBottom"><br clear="all" />
          <form method="post" action="<? echo $_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']; ?>" name="Sign Up Form" id="Sign_Up_Form" enctype="multipart/form-data">
            <? if(isset($Error) && $Error != false){ ?>
            <div id="Error"><img src="/images/warning.jpg" width="30" height="29" border="0" align="absmiddle" style="float:left" /><img src="/images/warning.jpg" width="30" height="29" border="0" align="absmiddle"  style="float:right" />
              <p><? echo $Error; ?></p>
            </div>
            <br clear="all" />
            <? } ?>
            <div class="Column">
              <label for="Service_Level" style="margin-right:5px;">Service Level </label>
              <div style="float:left">
                <select name="Service Level" id="Service_Level" title="Service Level" class="CstmFrmElmnt"  tabindex="<? $tab=1; $ReqStr = array(); echo $tab++; ?>">
                  <? $getService = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
									// $getService->mysql("SELECT `prod_id`, `prod_name`, `prod_price` FROM `prod_products` WHERE `prod_service` = 'y' AND `prod_use` = 'y' AND `prod_recur` = 'y' ORDER BY `prod_name` ASC;");
                  $getService->mysql("SELECT `prod_products`.`prod_id`, `prod_products`.`prod_name`, `prod_products`.`prod_price` FROM `prod_products`, `prod_categories` WHERE `prod_products`.`prod_service` = 'y' AND `prod_products`.`prod_use` = 'y' AND `prod_products`.`prod_recur` = 'y' AND `prod_products`.`cat_id` = `prod_categories`.`cat_id` AND `prod_categories`.`cat_name` <> 'Account Hold' ORDER BY `prod_name` ASC;");
									foreach($getService->Rows() as $r){ ?>
                  <option value="<? echo $r['prod_id']; ?>"<? if($SvLvl == $r['prod_id']) echo ' selected="selected"'; ?> title="<? echo $r['prod_name'].' - $'.number_format($r['prod_price'],2,".",","); ?>"><? echo $r['prod_name'].' - $'.number_format($r['prod_price'],2,".",",");?></option>
                  <? } ?>
                </select>
              </div>
            </div>
            <br clear="all" />
            <div class="Column">
              <h2>Account Information</h2>
              <label for="Referred_By">Referred By</label>
              <br clear="all" />
              <div class="CstmInput">
                <input type="text" name="Referred By" id="Referred_By" title="Referred By" value="<? echo $RName;?>" tabindex="<? echo $tab++; ?>" />
              </div>
              <br clear="all" />
              <label for="Username">Username *</label>
              <br clear="all" />
              <div class="CstmInput">
                <input type="text" name="Username" id="Username" title="Username" value="<? echo $UName; ?>" tabindex="<? echo $tab++; $ReqStr[] = "'Username','','R'"; ?>" />
              </div>
              <br clear="all" />
              <label for="Url_Handle">URL Name<br />Your clients will see this and it must be all one word. It will display as follows when clients access your unique Pro Image URL: www.proimagesoftware.com/[URLNAME].<br />Choose wisely, this cannot be changed.</label>
              <br clear="all" />
              <div class="CstmInput">
                <input type="text" name="Url Handle" id="Url_Handle" title="Url Handle" value="<? echo $CString;?>" tabindex="<? echo $tab++; $ReqStr[] = "'Url_Handle','','R'"; ?>" />
              </div>
              <br clear="all" />
              <label for="Password">Password *</label>
              <br clear="all" />
              <div class="CstmInput">
                <input type="password" name="Password" id="Password" title="Password" tabindex="<? echo $tab++;$ReqStr[] = "'Password','','R'"; ?>" />
              </div>
              <br clear="all" />
              <label for="Confirm Password">Confirm Password *</label>
              <br clear="all" />
              <div class="CstmInput">
                <input type="password" name="Confirm Password" id="Confirm_Password" title="Confrim Password" tabindex="<? echo $tab++; $ReqStr[] = "'Confirm_Password','','R'"; ?>" />
              </div>
              <br clear="all" />
              <label for="Security_Question">Security Question</label>
              <br clear="all" />
              <select name="Security Question" id="Security_Question" title="Security Question" class="CstmFrmElmnt" tabindex="<? echo $tab++; $ReqStr[] = "'Security_Question','','R'"; ?>">
                <? $getSecQuest = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
									 $getSecQuest->mysql("SELECT * FROM `securty_questions` ORDER BY `question` ASC;");
									 foreach($getSecQuest->Rows() as $r){ ?>
                <option value="<? echo $r['question_id']; ?>"<? if($SQ == $r['question_id'])' selected="selected"'; ?> title="<? echo $r['question']; ?>"><? echo $r['question']; ?></option>
                <? } ?>
              </select>
              <br clear="all" />
              <label for="Answer">Answer *</label>
              <br clear="all" />
              <div class="CstmInput">
                <input type="text" name="Answer" id="Answer" title="Answer" value="<? echo $Answer;?>" tabindex="<? echo $tab++; $ReqStr[] = "'Answer','Security Answer','R'"; ?>" />
              </div>
              <p style="clear:both;padding-top: 25px;">Every plan begins with 45 days of free evaluation.  You may use the full set of features and upload as much as you desire.  After 45 days, you will be required to input a credit card to continue using the system.  Your card will be charged on that day, and your monthly or yearly membership will begin.  Your card will be charged each following month/year until you contact us and request to end your contract</p>
            </div>
            <? /* ?>
            <div class="Column">
              <h2>Billing Information</h2>
              <label for="Billing_First_Name" style="width:123px;">First Name </label>
              <label for="Billing_Middle_Name" style="width:39px;">MI</label>
              <label for="Billing_Last_Name" style="width:123px;">Last Name </label>
              <label for="Billing_Suffix">Suffix</label>
              <br clear="all" />
              <div class="CstmInput117">
                <input type="text" name="Billing First Name" id="Billing_First_Name" title="Billing First Name" value="<? echo $BFName;?>" tabindex="<? echo $tab++; //$ReqStr[] = "'Billing_First_Name','','R'"; ?>" />
              </div>
              <div class="CstmInput34">
                <input type="text" name="Billing Middle Name" id="Billing_Middle_Name" title="Billing Middle Name" value="<? echo $BMName;?>" tabindex="<? echo $tab++; ?>" />
              </div>
              <div class="CstmInput117">
                <input type="text" name="Billing Last Name" id="Billing_Last_Name" title="Billing Last Name" value="<? echo $BLName;?>" tabindex="<? echo $tab++; //$ReqStr[] = "'Billing_Last_Name','','R'"; ?>" />
              </div>
              <div style="float:left">
                <select name="Billing Suffix" id="Billing_Suffix" title="Billing_Suffix" class="CstmFrmElmnt53" tabindex="<? echo $tab++; ?>">
                  <option value="0"<? if($BSuffix == "0") echo ' selected="selected"'; ?>>&nbsp;</option>
                  <option value="1"<? if($BSuffix == "1") echo ' selected="selected"'; ?> title="Sr">Sr.</option>
                  <option value="2"<? if($BSuffix == "2") echo ' selected="selected"'; ?> title="Jr">Jr.</option>
                  <option value="3"<? if($BSuffix == "3") echo ' selected="selected"'; ?> title="II">II</option>
                  <option value="4"<? if($BSuffix == "4") echo ' selected="selected"'; ?> title="III">III</option>
                  <option value="5"<? if($BSuffix == "5") echo ' selected="selected"'; ?> title="IV">IV</option>
                  <option value="6"<? if($BSuffix == "6") echo ' selected="selected"'; ?> title="V">V</option>
                </select>
              </div>
              <br clear="all" />
              <label>Company Name</label>
              <br clear="all" />
              <div class="CstmInput">
                <input type="text" name="Billing Company Name" id="Billing_Company_Name" title="Billing Company Name" value="<? echo $BCName;?>" tabindex="<? echo $tab++; ?>" />
              </div>
              <br clear="all" />
              <label for="Billing_Address" style="width:200px;">Address </label>
              <label for="Billing_Suite_Apt">Suite / Apt</label>
              <br clear="all" />
              <div class="CstmInput">
                <input type="text" name="Billing Address" id="Billing_Address" title="Billing Address" value="<? echo $BAdd;?>" tabindex="<? echo $tab++; //$ReqStr[] = "'Billing_Address','','R'"; ?>" />
              </div>
              <div class="CstmInput34">
                <input type="text" name="Billing Suite Apt" id="Billing_Suite_Apt" title="Billing Suite / Apt" value="<? echo $BSuiteApt;?>" tabindex="<? echo $tab++; ?>" />
              </div>
              <br clear="all" />
              <label for="Billing_Address_2">Address Line 2</label>
              <br clear="all" />
              <div class="CstmInput">
                <input type="text" name="Billing Address 2" id="Billing_Address_2" title="Billing Address Line 2" value="<? echo $BAdd2;?>" tabindex="<? echo $tab++; ?>" />
              </div>
              <br clear="all" />
              <label for="Billing_City" style="width:123px;">City </label>
              <label for="Billing_State" style="width:69px;">State </label>
              <label for="Billing_Zip" style="width:123px;">Zip </label>
              <br clear="all" />
              <div class="CstmInput117">
                <input type="text" name="Billing_City" id="Billing_City" value="<? echo $BCity;?>" tabindex="<? echo $tab++; //$ReqStr[] = "'Billing_City','','R'"; ?>" />
              </div>
              <span style="float:left; clear:none; margin-right:5px;" id="Billing_State_Box">
              <div class="CstmInput34">
                <input type="text" name="Billing_State" id="Billing_State" title="Billing State" tabindex="<? $STab = $tab; echo $tab++; ?>" value="<? echo $BState; //$ReqStr[] = "'Billing_State','','R'"; ?>" />
                <script type="text/javascript">AEV_GetState('<? echo $BCount; ?>','<? echo $BState; ?>','<? echo $STab; ?>','Billing_');</script>
              </div>
              </span>
              <div class="CstmInput117">
                <input type="text" name="Billing Zip" id="Billing_Zip" title="Billing Zip" value="<? echo $BZip;?>" tabindex="<? echo $tab++; //$ReqStr[] = "'Billing_Zip','','R'"; ?>" />
              </div>
              <br clear="all" />
              <label for="Billing_Country">Country *</label>
              <br clear="all" />
              <select name="Billing_Country" id="Billing_Country" title="Billing Country" onchange="javascript:AEV_GetState(document.getElementById('Billing_Country').value,false,'<? echo $STab; ?>','Billing_');" class="CstmFrmElmnt" tabindex="<? echo $tab++;// $ReqStr[] = "'Billing_Country','','R'"; ?>">
                <option value="0" title="Select Country"> -- Select Country -- </option>
                <? $getCnty = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
				 					$getCnty->mysql("SELECT `country_short_3`, `country_name` FROM `a_country` WHERE `country_use` = 'y' ORDER BY `country_name` ASC;");
									foreach($getCnty->Rows() as $r){ ?>
                <option value="<? echo $r['country_short_3']; ?>"<? if($BCount == $r['country_short_3'])echo ' selected="selected"';?> title="<? echo $r['country_name']; ?>"><? echo $r['country_name']; ?></option>
                <? } ?>
              </select>
              <br clear="all" />
              <label for="Type_of_Card">Type of Card *</label>
              <br clear="all" />
              <select name="Type of Card" id="Type_of_Card" class="CstmFrmElmnt" title="Credit Card Type" tabindex="<? echo $tab++; //$ReqStr[] = "'Type_of_Card','Credit Card Type','R'"; ?>">
                <? $getCards = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
				 					$getCards->mysql("SELECT * FROM `billship_cc_types` WHERE `cc_accept` = 'y' ORDER BY `cc_order` ASC;");
									foreach($getCards->Rows() as $r){ ?>
                <option value="<? echo $r['cc_type_id']; ?>"<? if($CType == $r['cc_type_id']) echo ' selected="selected"';?> title="<? echo $r['cc_type_name']; ?>"><? echo $r['cc_type_name']; ?></option>
                <? } ?>
              </select>
              <br clear="all" />
              <label for="Credit_Card_Number">Credit Card Number*</label>
              <br clear="all" />
              <div class="CstmInput">
                <input type="text" name="Credit Card Number" id="Credit_Card_Number" title="Credit Card Number" tabindex="<? echo $tab++; //$ReqStr[] = "'Credit_Card_Number','','R'"; ?>" />
              </div>
              <br clear="all" />
              <label for="CCV_Code">CSV / CCV Code </label>
              <br clear="all" />
              <div class="CstmInput">
                <input type="text" name="CCV Code" id="CCV_Code" title="CSV / CCV Code" value="<? echo $CCV;?>" tabindex="<? echo $tab++; //$ReqStr[] = "'CCV_Code','CSV / CCV Code','R'"; ?>" />
              </div>
              <br clear="all" />
              <label>Expiration Date*</label>
              <br clear="all" />
              <div style="float:left; margin-right:5px;">
                <select name="Expiration Month" id="Expiration_Month" tabindex="<? echo $tab++; //$ReqStr[] = "'Expiration_Month','','R'"; ?>" class="CstmFrmElmnt64">
                  <? for($n=1;$n<=12;$n++){ ?>
                  <option value="<? echo $n; ?>"<? if($CCM == $n) echo ' selected="selected"'; ?> title="<? echo $n." - ".date("F",mktime(0,0,0,$n,1,date("Y"))); ?>"><? echo $n; ?></option>
                  <? } ?>
                </select>
              </div>
              <div style="float:left;">
                <select name="Expiration Year" id="Expiration_Year" tabindex="<? echo $tab++; //$ReqStr[] = "'Expiration_Year','','R'"; ?>" class="CstmFrmElmnt64">
                  <? $date = date("Y"); for($n=0;$n<5;$n++){ ?>
                  <option value="<? echo ($date+$n); ?>"<? if($CCY == ($date+$n)) echo ' selected="selected"'; ?> title="<? echo ($date+$n); ?>"><? echo ($date+$n); ?></option>
                  <? } ?>
                </select>
              </div>
            </div>
            <br clear="all" />
						<? */ ?>
            <div class="Column">
              <h2>Personal Information</h2>
              <label for="First_Name" style="width:123px;">First Name *</label>
              <label for="Middle_Name" style="width:39px;">MI</label>
              <label for="Last_Name" style="width:123px;">Last Name *</label>
              <label for="Suffix">Suffix</label>
              <br clear="all" />
              <div class="CstmInput117">
                <input type="text" name="First Name" id="First_Name" title="First Name" value="<? echo $FName; ?>" tabindex="<? echo $tab++; $ReqStr[] = "'First_Name','','R'"; ?>" />
              </div>
              <div class="CstmInput34">
                <input type="text" name="Middle Name" id="Middle_Name" title="Middle Name" value="<? echo $MName;?>" tabindex="<? echo $tab++; ?>" />
              </div>
              <div class="CstmInput117">
                <input type="text" name="Last Name" id="Last_Name" title="Last Name" value="<? echo $LName;?>" tabindex="<? echo $tab++; $ReqStr[] = "'Last_Name','','R'"; ?>" />
              </div>
              <div style="float:left">
                <select name="Suffix" id="Suffix" tabindex="<? echo $tab++; ?>" class="CstmFrmElmnt53">
                  <option value="0"<? if($Suffix == "0") echo ' selected="selected"'; ?>>&nbsp;</option>
                  <option value="1"<? if($Suffix == "1") echo ' selected="selected"'; ?> title="Sr.">Sr.</option>
                  <option value="2"<? if($Suffix == "2") echo ' selected="selected"'; ?> title="Jr.">Jr.</option>
                  <option value="3"<? if($Suffix == "3") echo ' selected="selected"'; ?> title="II">II</option>
                  <option value="4"<? if($Suffix == "4") echo ' selected="selected"'; ?> title="III">III</option>
                  <option value="5"<? if($Suffix == "5") echo ' selected="selected"'; ?> title="IV">IV</option>
                  <option value="6"<? if($Suffix == "6") echo ' selected="selected"'; ?> title="V">V</option>
                </select>
              </div>
              <br clear="all" />
              <label>Company Name</label>
              <br clear="all" />
              <div class="CstmInput">
                <input type="text" name="Company Name" id="Company_Name" title="Company Name" value="<? echo $CName;?>" tabindex="<? echo $tab++; ?>" />
              </div>
              <br clear="all" />
              <label for="Billing_Address" style="width:200px;">Address *</label>
              <label for="Billing_Suite_Apt">Suite / Apt</label>
              <br clear="all" />
              <div class="CstmInput">
                <input type="text" name="Address" id="Address" title="Address" value="<? echo $Add;?>" tabindex="<? echo $tab++; $ReqStr[] = "'Address','','R'"; ?>" />
              </div>
              <div class="CstmInput34">
                <input type="text" name="Suite Apt" id="Suite_Apt" title="Suite_Apt" value="<? echo $SuiteApt;?>" tabindex="<? echo $tab++; ?>" />
              </div>
              <br clear="all" />
              <label>Address Line 2</label>
              <br clear="all" />
              <div class="CstmInput">
                <input type="text" name="Address 2" id="Address_2" title="Billing Address Line 2" value="<? echo $Add2;?>" tabindex="<? echo $tab++; ?>" />
              </div>
              <br clear="all" />
              <label for="Billing_City" style="width:123px;">City *</label>
              <label for="Billing_State" style="width:69px;">State *</label>
              <label for="Billing_Zip" style="width:123px;">Zip *</label>
              <br clear="all" />
              <div class="CstmInput117">
                <input type="text" name="City" id="City" title="City" value="<? echo $City;?>" tabindex="<? echo $tab++; $ReqStr[] = "'City','','R'"; ?>" />
              </div>
              <span style="float:left; clear:none; margin-right:5px;" id="State_Box">
              <div class="CstmInput34">
                <input type="text" name="State" id="State" title="State" tabindex="<? $STab = $tab; echo $tab++; ?>" value="<? echo $State; $ReqStr[] = "'State','','R'"; ?>" />
                <script type="text/javascript">AEV_GetState('<? echo $Count; ?>','<? echo $State; ?>','<? echo $STab; ?>','');</script>
              </div>
              </span>
              <div class="CstmInput117">
                <input type="text" name="Zip" id="Zip" title="Zip" value="<? echo $Zip;?>" tabindex="<? echo $tab++; $ReqStr[] = "'Zip','','R'"; ?>" />
              </div>
              <br clear="all" />
              <label>Country *</label>
              <br clear="all" />
              <select name="Country" id="Country" title="Country" onchange="javascript:AEV_GetState(document.getElementById('Country').value,false,'<? echo $STab; ?>','');" class="CstmFrmElmnt" tabindex="<? echo $tab++; $ReqStr[] = "'Country','','R'"; ?>">
                <option value="0" title="Select Country"> -- Select Country -- </option>
                <? $getCnty = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
				 					$getCnty->mysql("SELECT `country_short_3`, `country_name` FROM `a_country` WHERE `country_use` = 'y' ORDER BY `country_name` ASC;");
									foreach($getCnty->Rows() as $r){ ?>
                <option value="<? echo $r['country_short_3']; ?>"<? if($Count == $r['country_short_3'])echo ' selected="selected"';?> title="<? echo $r['country_name']; ?>"><? echo $r['country_name']; ?></option>
                <? } ?>
              </select>
              <br clear="all" />
            </div>
            <div class="Column">
              <label for="P1">Phone Number *</label>
              <br clear="all" />
              <div class="CstmInput34">
                <input type="text" name="P1" id="P1" value="<? echo $P1; ?>" maxlength="3" onkeyup="AEV_set_tel_number('Phone_Number','P');" tabindex="<? echo $tab++; ?>" />
              </div>
              <div class="CstmInput34">
                <input type="text" name="P2" id="P2" value="<? echo $P2; ?>" maxlength="3" onkeyup="AEV_set_tel_number('Phone_Number','P');" tabindex="<? echo $tab++; ?>" />
              </div>
              <div class="CstmInput34">
                <input type="text" name="P3" id="P3" value="<? echo $P3; ?>" maxlength="4" onkeyup="AEV_set_tel_number('Phone_Number','P');" tabindex="<? echo $tab++; ?>" />
              </div>
              <input type="hidden" name="Phone Number" id="Phone_Number" value="<? echo $Phone; $ReqStr[] = "'Phone_Number','','R'"; ?>" />
              <br clear="all" />
              <label for="W1" style="width:122px;">Work Number</label>
              <label for="Work_Ext">Ext.</label>
              <br clear="all" />
              <div class="CstmInput34">
                <input type="text" name="W1" id="W1" value="<? echo $W1; ?>" maxlength="3" onkeyup="AEV_set_tel_number('Work_Number','W');" tabindex="<? echo $tab++; ?>" />
              </div>
              <div class="CstmInput34">
                <input type="text" name="W2" id="W2" value="<? echo $W2; ?>" maxlength="3" onkeyup="AEV_set_tel_number('Work_Number','W');" tabindex="<? echo $tab++; ?>" />
              </div>
              <div class="CstmInput34">
                <input type="text" name="W3" id="W3" value="<? echo $W3; ?>" maxlength="4" onkeyup="AEV_set_tel_number('Work_Number','W');" tabindex="<? echo $tab++; ?>" />
              </div>
              <div class="CstmInput34">
                <input type="text" name="Work Ext" id="Work_Ext" value="<? echo $WorkExt;?>" tabindex="<? echo $tab++; ?>" />
              </div>
              <input type="hidden" name="Work Number" id="Work_Number" value="<? echo $Work;?>" />
              <br clear="all" />
              <label for="M1">Mobile Number</label>
              <br clear="all" />
              <div class="CstmInput34">
                <input type="text" name="M1" id="M1" value="<? echo $M1; ?>" maxlength="3" onkeyup="AEV_set_tel_number('Mobile_Number','M');" tabindex="<? echo $tab++; ?>" />
              </div>
              <div class="CstmInput34">
                <input type="text" name="M2" id="M2" value="<? echo $M2; ?>" maxlength="3" onkeyup="AEV_set_tel_number('Mobile_Number','M');" tabindex="<? echo $tab++; ?>" />
              </div>
              <div class="CstmInput34">
                <input type="text" name="M3" id="M3" value="<? echo $M3; ?>" maxlength="4" onkeyup="AEV_set_tel_number('Mobile_Number','M');" tabindex="<? echo $tab++; ?>" />
              </div>
              <input type="hidden" name="Mobile Number" id="Mobile_Number" value="<? echo $Mobile;?>" />
              <br clear="all" />
              <label>Email *</label>
              <br clear="all" />
              <div class="CstmInput">
                <input type="text" name="Email" id="Email" title="Email" value="<? echo $Email;?>" tabindex="<? echo $tab++; $ReqStr[] = "'Email','Email Address','RisEmail'"; ?>" />
              </div>
              <br clear="all" />
              <div class="BtnContinue">
                <input type="submit" name="btnSubmit" id="btnSumbit" value="Submit" title="Submit" onclick="javascript: MM_validateForm(<? echo implode(",",$ReqStr); ?>);return document.MM_returnValue;" />
              </div>
              <br clear="all" />
            </div>
            <br clear="all" />
            <input type="hidden" name="Controller" id="Controller" value="true" />
            <br clear="all" />
          </form>
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
