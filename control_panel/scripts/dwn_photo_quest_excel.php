<?php 
define ("PhotoExpress Pro", true);
$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
$r_path = "";
for($n=0;$n<$count;$n++){
	$r_path .= "../";
}
include $r_path.'security.php';
require_once ($r_path.'../Connections/cp_connection.php');
require_once ($r_path.'scripts/fnct_clean_entry.php');
mysql_select_db($database_cp_connection, $cp_connection);
if($loginsession[1] >= 10)require_once($r_path.'scripts/get_user_information.php');
$EId = clean_variable($_GET['event_id'],true);
$query_name_list = "SELECT `photo_quest_book`.`email`, `photo_quest_book`.`promotion` FROM `photo_event` INNER JOIN `photo_quest_book` ON `photo_quest_book`.`event_id` = `photo_event`.`event_id` WHERE `photo_event`.`cust_id` = '$CustId' AND `photo_event`.`event_id` = '$EId'  ORDER BY `photo_quest_book`.`email` ASC";
	
$Date = date("Y-m-d")."T".date("H:i:s")."Z";
$data = "<?xml version=\"1.0\"?>\n
<?mso-application progid=\"Excel.Sheet\"?>\n
<Workbook xmlns=\"urn:schemas-microsoft-com:office:spreadsheet\"\n
 xmlns:o=\"urn:schemas-microsoft-com:office:office\"\n
 xmlns:x=\"urn:schemas-microsoft-com:office:excel\"\n
 xmlns:ss=\"urn:schemas-microsoft-com:office:spreadsheet\"\n
 xmlns:html=\"http://www.w3.org/TR/REC-html40\">\n
 <DocumentProperties xmlns=\"urn:schemas-microsoft-com:office:office\">\n
  <Author>Event Guestbook</Author>\n
  <LastAuthor>Event Guestbook</LastAuthor>\n
  <Created>".$Date."</Created>\n
  <Version>11.6568</Version>\n
 </DocumentProperties>\n
 <ExcelWorkbook xmlns=\"urn:schemas-microsoft-com:office:excel\">\n
  <WindowHeight>11505</WindowHeight>\n
  <WindowWidth>18075</WindowWidth>\n
  <WindowTopX>240</WindowTopX>\n
  <WindowTopY>120</WindowTopY>\n
  <ProtectStructure>False</ProtectStructure>\n
  <ProtectWindows>False</ProtectWindows>\n
 </ExcelWorkbook>\n
 <Styles>\n
  <Style ss:ID=\"Default\" ss:Name=\"Normal\">\n
   <Alignment ss:Vertical=\"Bottom\"/>\n
   <Borders/>\n
   <Font/>\n
   <Interior/>\n
   <NumberFormat/>\n
   <Protection/>\n
  </Style>\n
  <Style ss:ID=\"s100\">\n
   <Alignment ss:Horizontal=\"Left\" ss:Vertical=\"Bottom\"/>\n
   <Borders>\n
	<Border ss:Position=\"Bottom\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\"/>\n
   </Borders>\n
   <Font ss:FontName=\"Tahoma\" ss:Bold=\"1\"/>\n
  </Style>\n
 </Styles>\n";

$name_list = mysql_query($query_name_list, $cp_connection) or die(mysql_error());
$row_name_list = mysql_fetch_assoc($name_list);
$totalRows_name_list = mysql_num_rows($name_list);
$fields_name_list = mysql_num_fields($name_list);

$data .= "<Worksheet ss:Name=\"ATP Client List\">\n
  <Table>\n
	<Row>\n";
	$fieldtypes = array();
	for ($i = 0; $i < $fields_name_list; $i++) {
		$data .= "<Cell ss:StyleID=\"s100\"><Data ss:Type=\"String\">".mysql_field_name($name_list, $i)."</Data></Cell>\n";
	} 
$data .= "</Row>\n";
do {
	$line = '';
	$n = 0;
	foreach($row_name_list as $value) { 
		$value = str_replace('"', '""', $value);
		$value = "<Cell><Data ss:Type=\"String\">".$value."</Data></Cell>\n"; 
		$line .= $value; 
		$n++;
	}
	$data .= "<Row>\n".trim($line)."\n</Row>\n";
} while ($row_name_list = mysql_fetch_assoc($name_list));
$data .= "</Table>
</Worksheet>\n";
 
mysql_free_result($name_list);

$data .= "</Workbook>";
$data = str_replace("\r","",$data);

header("Content-type: application/x-msdownload");
header("Content-Disposition: attachment; filename=Event_Promo_Guestbook.xml");
header("Pragma: no-cache");
header("Expires: 0");
print $data;

?>
