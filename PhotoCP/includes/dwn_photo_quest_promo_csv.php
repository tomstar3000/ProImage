<? define ("PhotoExpress Pro", true);
$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2; $r_path = ""; for($n=0;$n<$count;$n++) $r_path .= "../";

include $r_path.'security.php';
require_once ($r_path.'../Connections/cp_connection.php');
require_once ($r_path.'scripts/fnct_clean_entry.php');

if($loginsession[1] >= 10)require_once($r_path.'includes/get_user_information.php');
$EId = clean_variable($_GET['event_id'],true);

$getList = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
$getList->mysql("SELECT `photo_quest_book`.* FROM `photo_event` INNER JOIN `photo_quest_book` ON `photo_quest_book`.`event_id` = `photo_event`.`event_id` WHERE `photo_event`.`cust_id` = '$CustId' AND `photo_event`.`event_id` = '$EId' AND `photo_quest_book`.`promotion` = 'y' ORDER BY `photo_quest_book`.`email` ASC;");

if (strtoupper(substr(PHP_OS,0,3))=='WIN') $eol="\r\n"; 
else if (strtoupper(substr(PHP_OS,0,3))=='MAC') $eol="\r"; 
else $eol="\n";

$data = '"Title","First Name","Middle Name","Last Name","Suffix","Company","Department","Job Title","Business Street","Business Street 2","Business Street 3","Business City","Business State","Business Postal Code","Business Country","Home Street","Home Street 2","Home Street 3","Home City","Home State","Home Postal Code","Home Country","Other Street","Other Street 2","Other Street 3","Other City","Other State","Other Postal Code","Other Country","Assistant\'s Phone","Business Fax","Business Phone","Business Phone 2","Callback","Car Phone","Company Main Phone","Home Fax","Home Phone","Home Phone 2","ISDN","Mobile Phone","Other Fax","Other Phone","Pager","Primary Phone","Radio Phone","TTY/TDD Phone","Telex","Account","Anniversary","Assistant\'s Name","Billing Information","Birthday","Business Address PO Box","Categories","Children","Directory Server","E-mail Address","E-mail Type","E-mail Display Name","E-mail 2 Address","E-mail 2 Type","E-mail 2 Display Name","E-mail 3 Address","E-mail 3 Type","E-mail 3 Display Name","Gender","Government ID Number","Hobby","Home Address PO Box","Initials","Internet Free Busy","Keywords","Language","Location","Manager\'s Name","Mileage","Notes","Office Location","Organizational ID Number","Other Address PO Box","Priority","Private","Profession","Referred By","Sensitivity","Spouse","User 1","User 2","User 3","User 4","Web Page"'.$eol;

foreach($getList->Rows() as $r){
	$data .=  '"","","","","","","","",,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,"","0/0/00",,,"0/0/00",,,,,"'.$r['email'].'","SMTP","'.$r['email'].'",,,,,,,"Unspecified",,,,"",,"","","",,,,,,,"Normal","False",,,"Normal"'.$eol;
}

header("Content-type: application/x-msdownload");
header("Content-Disposition: attachment; filename=mailing_list.CSV");
//header("Content-Disposition: attachment; filename=KoolTaxOrganizer.xls");
header("Pragma: no-cache");
header("Expires: 0");
print $data;
?>
