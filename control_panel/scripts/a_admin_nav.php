<? $TempCodes = $loginsession[2]; ?>
<h1>Control Panel</h1>
<ul id="Main_Nav">
 <? foreach($navcodes as $k => $v){
 		if(isset($TempCodes[$k]['Active']) && $TempCodes[$k]['Active'] == 1){ 
			foreach($TempCodes[$k]['Sub'] as $k2 => $v2){
				if($v2['Active'] == 1){
					$tempChild = $k2;
					$tempCode = isset($v['Sub'][$k2]['Code']) ? $v['Sub'][$k2]['Code'] : 'query';
					break;
				}	} ?>
 <li id="Btn_Main"><a href="#" onclick="javascript:set_form('','<? echo $k.",".$tempChild; ?>','<? echo $tempCode; ?>','','');" title="<? echo $v['Name']; ?>"<? if($path[0] == $k) echo ' class="Main_Nav_Select"';?>>&nbsp;<? echo $v['Name']; ?></a></li>
 <? if(count($v['Sub']) > 0 && $path[0] == $k){ ?>
</ul>
<ul id="Sub_Nav" style="margin:0px;">
  <? foreach($v['Sub'] as $k2 => $v2){ if(isset($TempCodes[$k]['Sub'][$k2]['Active']) && $TempCodes[$k]['Sub'][$k2]['Active'] == 1){ ?>
 <li id="Btn_Sub"><a href="#" onclick="javascript:set_form('','<? echo $k.",".$k2; ?>','<? echo isset($v2['Code']) ? $v2['Code'] : 'query'; ?>','','');" title="<? echo $v2['Name']; ?>"<? if($path[1] == $k2) echo ' class="Sub_Nav_Select"';?>>&nbsp;<? echo $v2['Name']; ?></a></li>
  <? } } ?>
</ul><ul id="Main_Nav">
 <? } } } ?>
</ul>