<? if(!isset($r_path)){	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;	$r_path = "";	for($n=0;$n<$count;$n++)$r_path .= "../";}
$EId = $path[2];?>

<div id="Sub_Header"> <img src="/control_panel/images/hdr_form_left.jpg" width="17" height="33" border="0" align="left" />
 <h2>Event Guestbook </h2>
 <p id="MEmail"><a href="#" onclick="javascript:set_form('','<? echo implode(",",$path); ?>','email','<? echo $sort; ?>','<? echo $rcrd; ?>');">mass
   e-mail</a></p>
 <p id="Back"><a href="#" onclick="javascript:set_form('form_','<? echo implode(",",array_slice($path,0,2)); ?>','query','<? echo $sort; ?>','<? echo $rcrd; ?>');">Back</a></p>
</div>
<div>
 <p>download: promotion list: <a href="/control_panel/scripts/dwn_photo_quest_promo_csv.php?token=<? echo session_id(); ?>&event_id=<? echo $EId; ?>" target="_blank">CSV</a> <a href="/control_panel/scripts/dwn_photo_quest_promo_excel.php?token=<? echo session_id(); ?>&event_id=<? echo $EId; ?>" target="_blank">Excel</a> -
  complete list <a href="/control_panel/scripts/dwn_photo_quest_csv.php?token=<? echo session_id(); ?>&event_id=<? echo $EId; ?>" target="_blank">CSV</a> <a href="/control_panel/scripts/dwn_photo_quest_excel.php?token=<? echo session_id(); ?>&event_id=<? echo $EId; ?>" target="_blank">Excel</a></p>
 <p>&nbsp;</p>
 <?
include $r_path.'security.php';
require_once $r_path.'scripts/fnct_record_5_table.php';
$query_get_records = "SELECT `questbook_id`, `email`,`promotion` FROM `photo_quest_book` WHERE `event_id` = '$EId' GROUP BY `email` ORDER BY `questbook_id`, `promotion`, `email` ASC";
$get_records = mysql_query($query_get_records, $cp_connection) or die(mysql_error());
$row_get_records = mysql_fetch_assoc($get_records);
$totalRows_get_records = mysql_num_rows($get_records);

$records = array();
$headers = array();
$sortheaders = false;
$div_data = false;
$drop_downs = false;
$headers[0] = "E-mail";
$headers[1] = "Recieve Promotion";
do{
	$count = count($records);
	$records[$count][0][0] = false;
	$records[$count][0][1] = false;
	$records[$count][0][2] = false;
	$records[$count][1] = $row_get_records['questbook_id'];
	$records[$count][2] = "<a href=\"mailto:".$row_get_records['email']."\">".$row_get_records['email']."</a>";
	$records[$count][3] = ($row_get_records['promotion']=="y") ? "Yes" : "No";
} while ($row_get_records = mysql_fetch_assoc($get_records));

mysql_free_result($get_records);

build_record_5_table('Guestbook','Guestbook',$headers,$sortheaders,$records,$div_data,$drop_downs,false,false,'100%','0','0','0',false,false,$Rec_Style_1,$Rec_Style_2,$Rec_Style_3,$Rec_Style_4,$Rec_Style_5,false,false,false,false,false);
if(is_array($rcrd))$rcrd = implode(",",$rcrd);

?>
</div>
