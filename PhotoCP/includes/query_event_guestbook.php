<? if(isset($r_path)===false){ $count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2; $r_path = ""; for($n=0;$n<$count;$n++) $r_path .= "../"; }
include $r_path.'security.php';
$EId = $path[2];
if(isset($_POST['Controller']) && $_POST['Controller'] == "Email"){
	$Emails = isset($_POST['Upload_Email']) ? $_POST['Upload_Email'] : '';
	$Emails = explode(",",$Emails);
	foreach($Emails as $k => $v){
		$Emails[$k] = clean_variable($v,true);
		$addEmail = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
		$addEmail->mysql("SELECT COUNT(`questbook_id`) AS `emailcount` FROM `photo_quest_book` WHERE `event_id` = '$EId' AND `email` = '".$Emails[$k]."';");
		$addEmail = $addEmail->Rows();
		if($addEmail[0]['emailcount']==0){
			$addEmail = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
			$addEmail->mysql("INSERT INTO `photo_quest_book` (`email`,`promotion`,`event_id`) VALUES ('".$Emails[$k]."','y','$EId');");
		}
	}
}
if($sort != ""){
	$Sorting = explode(",",$sort,3);
	$Sort = str_replace("_"," ",$Sorting[0]);
	$Order = str_replace("_"," ",$Sorting[1]);
} else {
	$Sort = "Email";
	$Order = "ASC";
}
$sort = $Sort.",".$Order;
$TempSort = array();
$TempSort[0] = $Order;
$TempSort[1] = $Sort;
$Sorting = implode(",",$TempSort);
$Sorting = str_replace(" ","_",$Sorting);
unset($TempSort);

/*if($Sort == "Email"){
	$Sort_val = " ORDER BY `email` ".$Order;	
	$Addselect = "";
	$Innerjoin = "";
} else if($Sort == "Promo"){
	$Sort_val = " ORDER BY `promotion` ".$Order;
	$Addselect = "";
	$Innerjoin = "";
} else {
	$Sort_val = " ORDER BY `email` ".$Order;	
	$Addselect = "";
	$Innerjoin = "";
}*/

switch( $Sort ){
  case "Email":
    $Sort_val = " ORDER BY `email` ".$Order;  
  break;
  case "Promo":
    $Sort_val = " ORDER BY `promotion` ".$Order;
  break;
  case "First Login":
    $Sort_val = " ORDER BY `first_login` ".$Order;
  break;
  case "Last Login":
    $Sort_val = " ORDER BY `first_login` ".$Order;
  break;
  case "Visits":
    $Sort_val = " ORDER BY `visits` ".$Order;
  break;
  default:
    $Sort_val = " ORDER BY `email` ".$Order;  
  break;
}

$Addselect = "";
$Innerjoin = "";

$getInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
$getInfo->mysql("SELECT `photo_quest_book`.`questbook_id`, `photo_quest_book`.`email`, `photo_quest_book`.`promotion`, `photo_quest_book`.`visits`, `photo_quest_book`.`first_login`, `photo_quest_book`.`last_login`, `photo_event`.`event_name` FROM `photo_quest_book`, `photo_event` WHERE `photo_quest_book`.`event_id` = '$EId' AND `photo_quest_book`.`event_id` = `photo_event`.`event_id` GROUP BY `photo_quest_book`.`email` ".$Sort_val.";"); ?>

<h1 id="HdrType2" class="<? switch($path[0]){
	case 'Evnt': echo 'GstBkEvnt'; break;
	case 'Clnt': echo 'GstBkClnt'; break;
	default: echo 'GstBkEvnt'; break; } ?>">
  <?  $rows = $getInfo->Rows();
  ?>
  <div><? echo $rows[0]['event_name']; ?> Guestbook</div>
</h1>
<div id="HdrLinks"><a href="javascript:set_form('form_','<? echo implode(",",array_slice($path,0,4)); ?>','view','<? echo $sort; ?>','<? echo $rcrd; ?>');" onmouseover="window.status='Send E-mail to current guestbook'; return true;" onmouseout="window.status=''; return true;" title="Send E-mail to current guestbook" class="BtnSendEmail">Send E-mail</a></div>
<div id="RecordTable">
  <div id="Top"></div>
  <div id="Records" class="Colmn2"> <span>
    <p>Download Promotional List
    <div class="btnCSV"><a href="/PhotoCP/downloader.php?type=1&event_id=<? echo $EId; if(isset($_GET['token'])) echo '&token='.$_GET['token']; ?>" target="_blank">CSV</a></div>
    <div class="btnExcel"><a href="/PhotoCP/downloader.php?type=2&event_id=<? echo $EId; if(isset($_GET['token'])) echo '&token='.$_GET['token']; ?>" target="_blank">Excel</a></div>
    </p>
    </span> <span>
    <p>Download Complete List
    <div class="btnCSV"><a href="/PhotoCP/downloader.php?type=3&event_id=<? echo $EId; if(isset($_GET['token'])) echo '&token='.$_GET['token']; ?>" target="_blank">CSV</a></div>
    <div class="btnExcel"><a href="/PhotoCP/downloader.php?type=4&event_id=<? echo $EId; if(isset($_GET['token'])) echo '&token='.$_GET['token']; ?>" target="_blank">Excel</a></div>
    </p>
    </span> <br clear="all" />
    <br clear="all" />
  </div>
  <div id="Bottom"></div>
</div>
<br clear="all" />
<div id="RecordTable">
  <div id="Top"></div>
  <div id="Records" class="Colmn2">
    <p>Upload Email Address<br />
      Seperate Emails by a comma to enter multiple Email addresses.<br />
    <div class="CstmFrmElmntTextField" style="margin-left:15px;">
      <textarea name="Upload Email" id="Upload_Email" onfocus="javascript:this.parentNode.className='CstmFrmElmntTextFieldNavSel';" onblur="javascript:this.parentNode.className='CstmFrmElmntTextField';" onmouseover="window.status='Message'; return true;" onmouseout="window.status=''; return true;" title="Message"></textarea>
    </div>
    </p>
    <div id="CstmBtn"> <a href="#" onclick="javascript: document.getElementById('form_path').value='<?
		if($path[1] == "Evnt") $EmailSave = implode(",",array_slice($path,0,4)); else $EmailSave = implode(",",array_slice($path,0,3));
			echo $EmailSave; ?>'; document.getElementById('Controller').value='Email'; document.getElementById('form_action_form').submit();" onmouseover="window.status='Save Emails'; return true;" onmouseout="window.status=''; return true;" title="Save Emails" class="BtnSave">Save</a></div>
    <br clear="all" />
  </div>
  <div id="Bottom"></div>
</div>
<br clear="all" />
<div id="RecordTable" class="<? switch($path[0]){
	case 'Evnt': echo 'Red'; break;
	case 'Clnt': echo 'Yellow'; break;
	case 'Busn': echo 'Green'; break;
	default: echo 'Red'; break; } ?>">
  <div id="Top"></div>
  <div id="Records">
    <? if($getInfo->TotalRows() > 0){
				$class1 = ""; $class2 = "ROver";
				if(intval($k%2)==1){ $class1 = (($k == ($getInfo->TotalRows()-1))?'B':'').'SRow'; }
				else if ($k == ($getInfo->TotalRows()-1)) $class1 = 'B';
				$class2 = (($k == ($getInfo->TotalRows()-1))?'B':'').$class2; ?>
    <table border="0" cellpadding="0" cellspacing="0">
      <tr>
        <th><a href="javascript:document.getElementById('Controller').value = 'false'; set_form('form_','<? echo implode(",",array_slice($path,0,3)); ?>','<? echo $cont; ?>','Email,<? echo ($Sort == "Email" && $Order == "ASC")?'DESC':'ASC'; ?>','');" onmouseover="window.status='Sort By Email'; return true;" onmouseout="window.status=''; return true;" title="Sort By Event Email">E-mail</a></th>
        <th><a href="javascript:document.getElementById('Controller').value = 'false'; set_form('form_','<? echo implode(",",array_slice($path,0,3)); ?>','<? echo $cont; ?>','Promo,<? echo ($Sort == "Promo" && $Order == "ASC")?'DESC':'ASC'; ?>','');" onmouseover="window.status='Sort By Event Code'; return true;" onmouseout="window.status=''; return true;" title="Sort By Event Code">Receive Promotion</a></th>
        <th><a href="javascript:document.getElementById('Controller').value = 'false'; set_form('form_','<? echo implode(",",array_slice($path,0,3)); ?>','<? echo $cont; ?>','First Login,<? echo ($Sort == "First Login" && $Order == "ASC")?'DESC':'ASC'; ?>','');" onmouseover="window.status='Sort By Event First Login'; return true;" onmouseout="window.status=''; return true;" title="Sort By Event First Login">First Login</a></th>
        <th><a href="javascript:document.getElementById('Controller').value = 'false'; set_form('form_','<? echo implode(",",array_slice($path,0,3)); ?>','<? echo $cont; ?>','Last Login,<? echo ($Sort == "Last Login" && $Order == "ASC")?'DESC':'ASC'; ?>','');" onmouseover="window.status='Sort By Event Last Login'; return true;" onmouseout="window.status=''; return true;" title="Sort By Event Last Login">Last Login</a></th>
        <th class="R"><a href="javascript:document.getElementById('Controller').value = 'false'; set_form('form_','<? echo implode(",",array_slice($path,0,3)); ?>','<? echo $cont; ?>','Visits,<? echo ($Sort == "Visits" && $Order == "ASC")?'DESC':'ASC'; ?>','');" onmouseover="window.status='Sort By Event Visits'; return true;" onmouseout="window.status=''; return true;" title="Sort By Event Visits">Visits</a></th>        
      </tr>
      <? foreach($getInfo->Rows() as $k => $r){ ?>
      <tr<? if(strlen(trim($class1))>0) echo ' class="'.$class1.'"'; ?> onMouseOver="this.className='<? echo $class2; ?>';" onMouseOut="this.className='<? echo $class1; ?>';">
        <td><a href="mailto:<? echo $r['email']; ?>" title="<? echo $r['email']; ?>"><? echo $r['email']; ?></a></td>
        <td><? echo ($r['promotion']=="y") ? "Yes" : "No" ?></td>
        <td><? echo format_date( $r['first_login'], "DashShort", 'Standard', true, true ); ?></td>
        <td><? echo format_date( $r['last_login'], "DashShort", 'Standard', true, true ); ?></td>
        
        <td class="R"><? echo $r['visits'] ?></td>
      </tr>
      <? } ?>
    </table>
    <? } else { ?>
    <p>There are no records on file</p>
    <? } ?>
  </div>
  <div id="Bottom"></div>
</div>
<br clear="all" />
