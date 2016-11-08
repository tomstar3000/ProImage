<? if(isset($r_path)===false){ $count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2; $r_path = ""; for($n=0;$n<$count;$n++)$r_path .= "../"; }
include $r_path.'security.php';
$HotMenu = "Pers,Renew:view"; $Key = array_search($HotMenu, $StrArray); ?>

<h1 id="HdrType2" class="AccntRenew">
  <div>Cancel Account</div>
</h1>
<div id="HdrLinks"><? if($Canceled != 'y') { ?><a href="#" onclick="javascript:if( confirm('Are your sure you want to cancel your account?') ) {document.getElementById('Controller').value = 'Cancel'; document.getElementById('form_action_form').submit(); }" onmouseover="window.status='Cancel Your Account'; return true;" onmouseout="window.status=''; return true;" title="Cancel Your Account" class="BtnCancelAccount">Cancel Your Account</a><? } ?></div>
<? if(isset($Error) && strlen(trim($Error)) > 0){ ?>
<br clear="all" />
<h1 id="HdrType2" class="EvntInfo2">
  <div>Error</div>
</h1>
<div id="RecordTable" class="White">
  <div id="Top"></div>
  <div id="Records">
    <p class="Error"><? echo $Error; ?></p>
  </div>
  <div id="Bottom"></div>
</div>
<br clear="all" />
<? } else if(isset($Notice) && strlen(trim($Notice)) > 0){ ?>
<br clear="all" />
<h1 id="HdrType2" class="EvntInfo2">
  <div>Notice</div>
</h1>
<div id="RecordTable" class="White">
  <div id="Top"></div>
  <div id="Records">
    <p class="Error"><? echo $Notice; ?></p>
  </div>
  <div id="Bottom"></div>
</div>
<br clear="all" />
<? }?>
<div id="RecordTabs"> <a href="javascript: set_form('','<? echo implode(",",array_slice($path,0,1)).",Info"; ?>','view','','');" id="BtnTabPers">Personal Information</a> <a href="javascript: set_form('','<? echo implode(",",array_slice($path,0,1)).",Bill"; ?>','view','','');" id="BtnTabBill">Billing Information</a> <a href="javascript: set_form('','<? echo implode(",",array_slice($path,0,1)).",Renew"; ?>','view','','');" id="BtnTabRenew">Renew Account</a> <a href="javascript: set_form('','<? echo implode(",",array_slice($path,0,1)).",Cancel"; ?>','view','','');" id="BtnTabCancel"<? if($path[1]=="Cancel") echo ' class="NavSel"'; ?>>Cancel Account</a><br clear="all" />
</div>
<div id="RecordTable" class="White">
  <div id="Top"></div>
  <div id="Records">
    <p>Sign Up Date: <? echo format_date($SignUpDate,'Short',false,true,false); ?><br />
      <br />
      Our Records show that your renewal is due on: <? echo format_date($DueDate,'Short',false,true,false); ?>, if you would
      like to cancel your account please click the cancel my account button above.</p>
  </div>
  <div id="Bottom"></div>
</div>
<br clear="all" />
