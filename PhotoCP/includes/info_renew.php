<? if(isset($r_path)===false){ $count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2; $r_path = ""; for($n=0;$n<$count;$n++)$r_path .= "../"; }
include $r_path.'security.php';
$HotMenu = "Pers,Renew:view"; $Key = array_search($HotMenu, $StrArray); ?>

<h1 id="HdrType2" class="AccntRenew">
  <div>Enter/Update Credit Card</div>
</h1>
<div id="HdrLinks"> <a href="#" onclick="javascript:document.getElementById('Controller').value = 'Renew'; document.getElementById('form_action_form').submit();" onmouseover="window.status='Update Your Account'; return true;" onmouseout="window.status=''; return true;" title="Update Your Account" class="BtnRenew">Update Your Account</a><a href="javascript:HotMenu('<? echo $HotMenu; ?>',<? echo ($Key!==false)?'2':'1'; ?>);" title="Add to Hot Menu" onmouseover="window.status='Add to Hot Menu'; return true;" onmouseout="window.status=''; return true;" class="BtnHotMenu<? echo ($Key!==false)?'Added':''; ?>" id="BtnHotMenu">Add to Hot Menu</a></div>
<? if(isset($Error) && strlen(trim($Error)) > 0){ ?>
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
<div id="RecordTabs"> <a href="javascript: set_form('','<? echo implode(",",array_slice($path,0,1)).",Info"; ?>','view','','');" id="BtnTabPers">Personal Information</a> <a href="javascript: set_form('','<? echo implode(",",array_slice($path,0,1)).",Bill"; ?>','view','','');" id="BtnTabBill">Billing Information</a> <a href="javascript: set_form('','<? echo implode(",",array_slice($path,0,1)).",Renew"; ?>','view','','');" id="BtnTabRenew"<? if($path[1]=="Renew") echo ' class="NavSel"'; ?>>Renew Account</a> <a href="javascript: set_form('','<? echo implode(",",array_slice($path,0,1)).",Cancel"; ?>','view','','');" id="BtnTabCancel">Cancel Account</a><br clear="all" />
</div>
<div id="RecordTable" class="White">
  <div id="Top"></div>
  <div id="Records">
    <p>Sign Up Date: <? echo format_date($SignUpDate,'Short',false,true,false); ?><br />
      <br />
      Your credit card will be billed on <? echo format_date($DueDate,'Short',false,true,false); ?> for the amount noted in service level.  If you want to change service level or need to update card info enter your information and click Update Account in the upper right of this screen.
      </p>
  </div>
  <div id="Bottom"></div>
</div>
<br clear="all" />
<div id="RecordTable" class="White">
  <div id="Top"></div>
  <div id="Records">
    <p>
      <label for="Service_Level" class="CstmFrmElmntLabel">Service Level</label>
      <!--<script>
        console.log( "hello " + <? //echo $monthsPaid; ?> );
      </script>-->
      <select name="Service Level" id="Service_Level" tabindex="<? echo $tab++; ?>" class="CstmFrmElmnt" title="Service Level">
        <? if($SvLvl <= 11){
					$getServ = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
					$getServ->mysql("SELECT `prod_id`, `prod_name`, `prod_price` FROM `prod_products` WHERE `prod_id` = '9' OR `prod_id` = '10' OR `prod_id` = '11' ORDER BY `prod_name` ASC;"); foreach($getServ->Rows() as $r){ ?>
        <option value="<? echo $r['prod_id']; ?>"<? if($SvLvl == $r['prod_id']) echo' selected="selected"'; ?> title="<? echo $r['prod_name'];?>"><? echo $r['prod_name']." $".number_format($r['prod_price'],2,".",",")."/m"; ?></option>
        <? } } 
        $getServ = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
        if( $monthsPaid > 3 ){
          $getServ->mysql("SELECT `prod_id`, `prod_name`, `prod_price` FROM `prod_products` WHERE `prod_service` = 'y' AND `prod_recur` = 'y' AND `prod_use` = 'y' ORDER BY `prod_name` ASC;");
        }
        else{
          $getServ->mysql("SELECT `prod_products`.`prod_id`, `prod_products`.`prod_name`, `prod_products`.`prod_price` FROM `prod_products`, `prod_categories` WHERE `prod_products`.`prod_service` = 'y' AND `prod_products`.`prod_use` = 'y' AND `prod_products`.`prod_recur` = 'y' AND `prod_products`.`cat_id` = `prod_categories`.`cat_id` AND `prod_categories`.`cat_name` <> 'Account Hold' ORDER BY `prod_name` ASC;");
        }
        foreach($getServ->Rows() as $r){ ?>
          <option value="<? echo $r['prod_id']; ?>"<? if($SvLvl == $r['prod_id']) echo' selected="selected"'; ?> title="<? echo $r['prod_name'];?>"><? echo $r['prod_name']." $".number_format($r['prod_price'],2,".",","); echo $r['prod_id']!="347"?"/m":"/y"; ?></option>
        <? } ?>
      </select>
      <br />
      <label for="Type_of_Card" class="CstmFrmElmntLabel">Card Type</label>
      <? $getCards = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
		 $getCards->mysql("SELECT * FROM `billship_cc_types` WHERE `cc_accept` = 'y' ORDER BY `cc_order` ASC;"); ?>
      <select name="Type of Card" id="Type_of_Card" class="CstmFrmElmnt" onmouseover="window.status='Credit Card Type'; return true;" onmouseout="window.status=''; return true;" title="Credit Card Type">
        <? foreach($getCards->Rows() as $r){ ?>
        <option value="<? echo $r['cc_type_id']; ?>" title="<? echo $r['cc_type_name']; ?>"><? echo $r['cc_type_name']; ?></option>
        <? } ?>
      </select>
      <br />
      <label for="Credit_Card_Number" class="CstmFrmElmntLabel">Credit Card Number</label>
      <input type="text" name="Credit Card Number" id="Credit_Card_Number" maxlength="16" value="" onfocus="javascript:this.className='CstmFrmElmntInputNavSel';" onblur="javascript:this.className='CstmFrmElmntInput';" onmouseover="window.status='Credit Card Number'; return true;" onmouseout="window.status=''; return true;" title="Credit Card Number" class="CstmFrmElmntInput" />
      <br />
      <label for="CCV_Code" class="CstmFrmElmntLabel">CCV Security Code</label>
      <input type="text" name="CCV Code" id="CCV_Code" value="" onfocus="javascript:this.className='CstmFrmElmntInputNavSel';" onblur="javascript:this.className='CstmFrmElmntInput';" onmouseover="window.status='CCV Security Code'; return true;" onmouseout="window.status=''; return true;" title="CCV Security Code" class="CstmFrmElmntInput" />
      <br />
      <label for="Experation_Month" class="CstmFrmElmntLabel">Expiration Date</label>
    <div style="float:left; clear:none; margin-left:5px;">
      <select name="Experation Month" id="Experation_Month" tabindex="<? echo $tab+($tabadd++); ?>" class="CstmFrmElmnt64"  onmouseover="window.status='Experation Month'; return true;" onmouseout="window.status=''; return true;" title="Experation Month">
        <? for($n=1;$n<=12;$n++){ ?>
        <option value="<? echo $n; ?>" title="<? echo $n; ?>"><? echo $n; ?></option>
        <? } ?>
      </select>
    </div>
    <? $date = date("Y"); ?>
    <div style="float:left; clear:none; margin-left:5px;">
      <select name="Experation Year" id="Experation_Year" tabindex="<? echo $tab+($tabadd++); ?>" class="CstmFrmElmnt64"  onmouseover="window.status='Experation Year'; return true;" onmouseout="window.status=''; return true;" title="Experation Year">
        <? for($n=0;$n<10;$n++){ ?>
        <option value="<? echo ($date+$n); ?>" title="<? echo ($date+$n); ?>"><? echo ($date+$n); ?></option>
        <? } ?>
      </select>
    </div>
    <br />
    </p>
  </div>
  <div id="Bottom"></div>
</div>
<?php /*
<br clear="all" />
<div id="RecordTable" class="White">
  <div id="Top"></div>
  <div id="Records" class="Colmn3"> <span>
    <p><strong>Portrait Plan:</strong></p>
    <p><strong>Storage:</strong> 25 Gb<br />
      <strong>Monthly Fee:</strong> $49.00<br />
      <strong>Revenue Sharing:</strong> 15%<br />
      <strong>Credit Card Processing Fee:</strong> 3%</p>
    </span> <span>
    <p><strong>Event Plan:</strong></p>
    <p><strong>Storage:</strong> Unlimited<br />
      <strong>Monthly Fee:</strong> $85.00<br />
      <strong>Revenue Sharing:</strong> 10%<br />
      <strong>Credit Card Processing Fee:</strong> 3%</p>
    </span></div>
  <div id="Bottom"></div>
</div>
<br clear="all" />

*/ ?>
