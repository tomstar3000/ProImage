<? $count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2; $r_path = ""; for($n=0;$n<$count;$n++)$r_path .= "../";
define("PhotoExpress Pro", true);
define('Allow Scripts',true);
include $r_path.'security.php';
require_once($r_path.'../Connections/cp_connection.php');
require_once($r_path.'includes/get_user_information.php'); ?>

<h1>Add / Edit Photographer Information</h1>
<form method="post" name="PhotographerForm" id="PhotographerForm" enctype="multipart/form-data">
  <label for="First_Name" class="CstmFrmElmntLabel">Name</label>
  <input type="text" name="Photographer_First Name" id="Photographer_First_Name" value="" maxlength="75" onfocus="javascript:this.className='CstmFrmElmntInputNavSel';" onblur="javascript:this.className='CstmFrmElmntInput';" onmouseover="window.status='First Name'; return true;" onmouseout="window.status=''; return true;" title="First Name" class="CstmFrmElmntInput" />
  <input type="text" name="Photographer_Last Name" id="Photographer_Last_Name" value="" maxlength="75" onfocus="javascript:this.className='CstmFrmElmntInputNavSel';" onblur="javascript:this.className='CstmFrmElmntInput';" onmouseover="window.status='Last Name'; return true;" onmouseout="window.status=''; return true;" title="Last Name" class="CstmFrmElmntInput" />
  <br clear="all" />
  <label for="Photographer_Address" class="CstmFrmElmntLabel">Address</label>
  <input name="Photographer_Address" type="text" id="Photographer_Address" value="" maxlength="125" onfocus="javascript:this.className='CstmFrmElmntInputNavSel';" onblur="javascript:this.className='CstmFrmElmntInput';" onmouseover="window.status='Address'; return true;" onmouseout="window.status=''; return true;" title="Address" class="CstmFrmElmntInput" />
  <strong class="CstmFrmElmntStrong">Suite/Apt</strong>
  <input type="text" name="Photographer_Suite Apt" id="Photographer_Suite_Apt" value="" maxlength="25" size="10" onfocus="javascript:this.className='CstmFrmElmntInputi34NavSel';" onblur="javascript:this.className='CstmFrmElmntInputi34';" onmouseover="window.status='Suite/Apt'; return true;" onmouseout="window.status=''; return true;" title="Suite/Apt" class="CstmFrmElmntInputi34" />
  <br clear="all" />
  <label for="Photographer_Address_2" class="CstmFrmElmntLabel">Address Second Line</label>
  <input type="text" name="Photographer_Address 2" id="Photographer_Address_2" value="" maxlength="125" onfocus="javascript:this.className='CstmFrmElmntInputNavSel';" onblur="javascript:this.className='CstmFrmElmntInput';" onmouseover="window.status='Address Second Line'; return true;" onmouseout="window.status=''; return true;" title="Address Second Line" class="CstmFrmElmntInput" />
  <br clear="all" />
  <label for="Photographer_City" class="CstmFrmElmntLabel">City, State, Zip</label>
  <input name="Photographer_City" type="text" id="Photographer_City" value="" onfocus="javascript:this.className='CstmFrmElmntInputNavSel';" onblur="javascript:this.className='CstmFrmElmntInput';" onmouseover="window.status='City'; return true;" onmouseout="window.status=''; return true;" title="City" class="CstmFrmElmntInput" />
  <span style="float:left; clear:none; margin-right:5px;" id="Photographer_State_Box">
  <input name="Photographer_State" type="text" id="Photographer_State" value="" onfocus="javascript:this.className='CstmFrmElmntInputNavSel';" onblur="javascript:this.className='CstmFrmElmntInput';" onmouseover="window.status='State'; return true;" onmouseout="window.status=''; return true;" title="State" class="CstmFrmElmntInput" />
  </span>
  <input name="Photographer_Zip" type="text" id="Photographer_Zip" value="" onfocus="javascript:this.className='CstmFrmElmntInputi117NavSel';" onblur="javascript:this.className='CstmFrmElmntInputi117';" onmouseover="window.status='Zip'; return true;" onmouseout="window.status=''; return true;" title="Zip" class="CstmFrmElmntInputi117" />
  <br clear="all" />
  <label for="Photographer_Country" class="CstmFrmElmntLabel">Country</label>
  <select name="Photographer_Country" id="Photographer_Country" onchange="javascript:AEV_GetState(document.getElementById('Photographer_Country').value,false,false,'Photographer_');" class="CstmFrmElmnt" onmouseover="window.status='Country'; return true;" onmouseout="window.status=''; return true;" title="Country">
    <option value="0" title="Select Country"> -- Select Country -- </option>
    <? $getCnty = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
			 $getCnty->mysql("SELECT `country_short_3`, `country_name` FROM `a_country` WHERE `country_use` = 'y' ORDER BY `country_name` ASC;");
			 foreach($getCnty->Rows() as $r){ ?>
    <option value="<? echo $r['country_short_3']; ?>" title="<? echo $r['country_name']; ?>"><? echo $r['country_name']; ?></option>
    <? } ?>
  </select>
  <br clear="all" />
  <label for="P1" class="CstmFrmElmntLabel">Phone Number</label>
  <input name="Photographer P1" type="text" id="Photographer_P1" size="6" class="CstmFrmElmntInputi29" onfocus="javascript:this.className='CstmFrmElmntInputi29NavSel';" onblur="javascript:this.className='CstmFrmElmntInputi29';" onmouseover="window.status='Phone Number: Area Code'; return true;" onmouseout="window.status=''; return true;" title="Phone Number: Area Code" value="" maxlength="3" onkeyup="AEV_set_tel_number('Photographer_Phone_Number','Photographer_P');" />
  <input name="Photographer P2" type="text" id="Photographer_P2" size="6" class="CstmFrmElmntInputi29" onfocus="javascript:this.className='CstmFrmElmntInputi29NavSel';" onblur="javascript:this.className='CstmFrmElmntInputi29';" onmouseover="window.status='Phone Number: Prefix'; return true;" onmouseout="window.status=''; return true;" title="Phone Number: Prefix" value="" maxlength="3" onkeyup="AEV_set_tel_number('Photographer_Phone_Number','Photographer_P');" />
  <input name="Photographer P3" type="text" id="Photographer_P3" size="8" class="CstmFrmElmntInputi34" onfocus="javascript:this.className='CstmFrmElmntInputi34NavSel';" onblur="javascript:this.className='CstmFrmElmntInputi34';" onmouseover="window.status='Phone Number: Suffix'; return true;" onmouseout="window.status=''; return true;" title="Phone Number: Suffix" value="" maxlength="4" onkeyup="AEV_set_tel_number('Photographer_Phone_Number','Photographer_P');" />
  <input type="hidden" name="Photographer Phone Number" id="Photographer_Phone_Number" value="" />
  <br clear="all" />
  <label for="Photographer_Email" class="CstmFrmElmntLabel">E-mail</label>
  <input type="text" name="Photographer_Email" id="Photographer_Email" value="" maxlength="125" onfocus="javascript:this.className='CstmFrmElmntInputNavSel';" onblur="javascript:this.className='CstmFrmElmntInput';" onmouseover="window.status='E-mail'; return true;" onmouseout="window.status=''; return true;" title="E-mail" class="CstmFrmElmntInput" />
  <br clear="all" />
  <? /* <div id="BtnImgSbmt" onclick="javascript:document.getElementById('InvoiceImageUpdaterForm').submit();"><input type="submit" name="Submit" id="Submit" value="Submit" /></div> */ ?>
  <input type="submit" name="BtnSubmit" id="BtnSubmit" value="Submit" onclick="javascript: Save_Photo_Info(); return false;" />
  <input type="hidden" name="Controller" id="Controller" value="true" />
</form>
