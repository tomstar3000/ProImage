<? if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++)$r_path .= "../";
}
include $r_path.'security.php';
include $r_path.'FCKeditor/fckeditor.php';
$is_enabled = ($cont == "view") ? false : true;
$is_back = ($cont == "edit") ? "view" : "query"; ?>

<table border="0" cellpadding="0" cellspacing="0" width="100%">
 <? if($is_enabled){ ?>
 <tr>
  <th colspan="4" id="Form_Header"><div id="Add"> <img src="/<? echo $AevNet_Path; ?>/images/back.jpg" width="72" height="33" alt="back" onclick="javascript:set_form('form_','<? echo implode(",",$path); ?>','<? echo $is_back; ?>','<? echo $sort; ?>','<? echo $rcrd; ?>');" /> </div>
   <img src="/<? echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
  <p>Guestbook Information</p></th>
 </tr>
 <? } else { ?>
 <tr>
  <th colspan="4" id="Form_Header"><div id="Add"> <img src="/<? echo $AevNet_Path; ?>/images/edit.jpg" width="72" height="33" alt="edit" onclick="javascript:set_form('form_','<? echo implode(",",$path); ?>','edit','<? echo $sort; ?>','<? echo $rcrd; ?>');" /><img src="/<? echo $AevNet_Path; ?>/images/back.jpg" width="72" height="33" alt="back" onclick="javascript:set_form('form_','<? echo implode(",",array_slice($path,0,count($path)-1)); ?>','<? echo $is_back; ?>','<? echo $sort; ?>','<? echo $rcrd; ?>');" /> </div>
   <img src="/<? echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
  <p>Guestbook Information</p></th>
 </tr>
 <? }
  	if($is_enabled){
			$query_page = "SELECT `nav_id` FROM `web_review_navigation` WHERE `nav_name` = 'news'";
			$page = mysql_query($query_page, $cp_connection) or die(mysql_error());
			$row_page = mysql_fetch_assoc($page);
						
			$news_id = $row_page['nav_id'];
			
			$children = array();
			find_children($news_id, 0, 'web_review_navigation', 'nav_part_id', 'nav_id');
		} else {
			$query_name = "SELECT `nav_name` FROM `web_review_navigation` WHERE `nav_id` = '$Cur_id'";
			$name = mysql_query($query_name, $cp_connection) or die(mysql_error());
			$row_name = mysql_fetch_assoc($name);
		}
  ?>
 <tr>
  <td width="20%"><strong>First Name :</strong></td>
  <td width="80%"><? if(!$is_enabled){ echo $FName; } else { ?>
   <input type="text" name="First Name" id="First_Name" maxlength="150" value="<? echo $FName; ?>" />
   <? } ?>
   &nbsp;</td>
 </tr>
 <tr>
  <td><strong>Last Name:</strong></td>
  <td><? if(!$is_enabled){ echo $LName; } else { ?>
   <input type="text" name="Last Name" id="Last_Name" maxlength="150" value="<? echo $LName; ?>" />
   <? } ?>
   &nbsp;</td>
 </tr>
 <tr>
  <td><strong>Date:</strong></td>
  <td><? if(!$is_enabled){ echo format_date($Date,"DayShort","Standard",true,false); } else { ?>
   <input type="text" name="Date" id="Date" maxlength="150" value="<? echo $Date; ?>" />
   <a href="#" onclick="newwindow=window.open('scripts/calendar.php?future=Both&time=false&field=Date','','scrollbars=no,menubar=no,height=270,width=184,resizable=no,toolbar=no,location=no,status=no'); newwindow.opener=self;"> Select
   Date</a> (yyyy-mm-dd)
   <? } ?>
   &nbsp;</td>
 </tr>
 <tr>
  <td><strong>E-mail :</strong></td>
  <td><? if(!$is_enabled){ echo $Email; } else { ?>
   <input type="text" name="Email" id="Email" maxlength="150" value="<? echo $Email; ?>" />
   <? } ?>
   &nbsp;</td>
 </tr>
 <tr>
  <td><strong>IP Address : </strong></td>
  <td><? echo $IP; ?> &nbsp;</td>
 </tr>
 <tr>
  <td><strong>Image:</strong></td>
  <td><? if($cont == "add" || $cont == "edit"){ ?>
   <input type="file" name="Image" id="Image" />
   <input type="hidden" name="Image_val" id="Image_val" value="<? echo $Imagev;?>" />
   <? }
	  if($Imagev != ""){?>
   &nbsp;<a href="<? echo $Guest_Folder; ?>/<? echo $Imagev;?>" target="_blank">View</a>
   <? } 
	  if($cont == "add" || $cont == "edit"){ ?>
   &nbsp;
   <input type="checkbox" name="Remove Image" id="Remove_Image" value="true" />
   Remove Image
   <? } ?>
   &nbsp;</td>
 </tr>
 <tr>
  <td><strong>Image Alt Tag:</strong></td>
  <td><? if(!$is_enabled){ echo $Alt;	} else { ?>
   <input type="text" name="Alt" id="Alt" maxlength="150" value="<? echo $Alt; ?>" />
   <? } ?></td>
 </tr>
 <tr>
  <td colspan="2" style="padding:10px;<? if(!$is_enabled){ ?> height:300px;<? } ?>" valign="top"><? if(!$is_enabled){	echo $Desc;	} else {
		$oFCKeditor = new FCKeditor('Description');
		$oFCKeditor->BasePath = 'FCKeditor/';
		$oFCKeditor->Value = $Desc;
		$oFCKeditor->Width = '100%';
		$oFCKeditor->Height = '290';
		$oFCKeditor->Create() ;
		$output = $oFCKeditor->CreateHtml();
	}	?></td>
 </tr>
</table>
