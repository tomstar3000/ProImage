<?php 
if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++){
		$r_path .= "../";
	}
}
include $r_path.'security.php';

$query_list = "SELECT `mail_fname`, `mail_lname`, `email`, `date` FROM `mail_mailing_list` ORDER BY `email` ASC";
	
$Date = date("Y-m-d")."T".date("H:i:s")."Z";
$data = "<?xml version=\"1.0\"?>\n
<?mso-application progid=\"Excel.Sheet\"?>\n
<Workbook xmlns=\"urn:schemas-microsoft-com:office:spreadsheet\"\n
 xmlns:o=\"urn:schemas-microsoft-com:office:office\"\n
 xmlns:x=\"urn:schemas-microsoft-com:office:excel\"\n
 xmlns:ss=\"urn:schemas-microsoft-com:office:spreadsheet\"\n
 xmlns:html=\"http://www.w3.org/TR/REC-html40\">\n
 <DocumentProperties xmlns=\"urn:schemas-microsoft-com:office:office\">\n
  <Author>Mailing List</Author>\n
  <LastAuthor>Mailing List</LastAuthor>\n
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

$list = mysql_query($query_list, $cp_connection) or die(mysql_error());
$row_list = mysql_fetch_assoc($list);
$totalRows_list = mysql_num_rows($list);
$fields_list = mysql_num_fields($list);

$data .= "<Worksheet ss:Name=\"ATP Client List\">\n
  <Table>\n
	<Row>\n";
	$fieldtypes = array();
	for ($i = 0; $i < $fields_list; $i++) {
		$data .= "<Cell ss:StyleID=\"s100\"><Data ss:Type=\"String\">".mysql_field_name($list, $i)."</Data></Cell>\n";
	}
	$data .= "</Row>\n";
	if($totalRows_list > 0){
		do {
			$line = '';
			foreach($row_list as $k => $v) {
				if(mysql_field_type($list,$k) == "timestamp"){
					$v = format_date($v,"Dash","Military",true,true);
				}
				$v = str_replace('"', '""', $v);
				$v = "<Cell><Data ss:Type=\"String\">".$v."</Data></Cell>\n"; 
				$line .= $v;
			}
			$data .= "<Row>\n".trim($line)."\n</Row>\n";
		} while ($row_list = mysql_fetch_assoc($list));
	}
	$data .= "</Table>
</Worksheet>\n";
 
mysql_free_result($list);

$data .= "</Workbook>";
$data = str_replace("\r","",$data);

//header("Content-type: application/x-msdownload");
//header("Content-Disposition: attachment; filename=Mailing_List.xml");
//header("Pragma: no-cache");
//header("Expires: 0");
print $data;

?>
