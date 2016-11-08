<? require_once('../Connections/cp_connection.php'); ?>
<?
define ("AevNet CONTROL PANEL", true);
include 'security.php';
include 'config.php';
$date = date("Ymd");
$q_length = 50000;

if(!is_dir("downloads/database/".$date)){
	if ($handle = opendir('downloads/database/')) {
	   $file_list = array();
	   /* This is the correct way to loop over the directory. */
	   while (false !== ($file = readdir($handle))) {
		   if(is_dir("downloads/database/".$file) && $file != "." && $file != ".."){
				array_push($file_list,$file);
		   }
	   }	
	   if(count($file_list)>4){
		   natcasesort($file_list);
		   $del_folder = array_shift($file_list);
		   if ($handle = opendir('downloads/database/'.$del_folder)) {
			   while (false !== ($file = readdir($handle))) {
				   if(file_exists("downloads/database/".$del_folder."/".$file) && $file != "." && $file != ".."){
						unlink("downloads/database/".$del_folder."/".$file);
				   }
			   }	
		   }
		   rmdir("downloads/database/".$del_folder);
	   }
	   closedir($handle);
	}

	if(@mkdir("downloads/database/".$date, 0775)){
		$index_file = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta http-equiv="refresh" content="1; URL=../../../" />
<title>Un-authorized Access</title>
</head>

<body>
</body>
</html>';
		$index_handle = fopen("downloads/database/".$date."/index.html", 'x');
		fwrite($index_handle, $index_file);
		fclose($index_handle);
	} else {
		echo "<script type=\"text/javascript\">document.location.href=\"control_panel.php\";</script>";
		die();
	}
} else {
	if(!file_exists("downloads/database/".$date."/index.html")){
		$index_file = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta http-equiv="refresh" content="1; URL=../../../" />
<title>Un-authorized Access</title>
</head>

<body>
</body>
</html>';
		$index_handle = fopen("downloads/database/".$date."/index.html", 'x');
		fwrite($index_handle, $index_file);
		fclose($index_handle);
	} else {
		echo "<script type=\"text/javascript\">document.location.href=\"control_panel.php\";</script>";
		die();
	}
}
$sql = "";
mysql_select_db($database_cp_connection, $cp_connection);
$tables = mysql_list_tables($database_cp_connection);
$num_tables = mysql_num_rows($tables);
for ($i = 0; $i < $num_tables; $i++) {   
	$table_name = mysql_tablename($tables, $i);
	$backup_file = 'downloads/database/'.$date.'/'.$table_name.'.sql';
	
	$query_table_cells = "SHOW COLUMNS FROM `$table_name`";
	$table_cells = mysql_query($query_table_cells, $cp_connection) or die(mysql_error());
	$row_table_cells = mysql_fetch_assoc($table_cells);
	$totalRows_table_cells = mysql_num_rows($table_cells);
	
	$cells = array();
	$records = array();
	do{
		array_push($cells,"`".$row_table_cells['Field']."`");
	} while ($row_table_cells = mysql_fetch_assoc($table_cells));

	mysql_free_result($table_cells);
	
	$query_backup = "SELECT * FROM `$table_name`";
	$backup = mysql_query($query_backup, $cp_connection) or die(mysql_error());
	$row_backup = mysql_fetch_assoc($backup);
	$totalRows_backup = mysql_num_rows($backup);
	$line = "\n--\n";
	$line .= "-- Dumping data for table `$table_name`\n";
	$line .= "--\n";
	if($totalRows_backup > 0){
		$insert = "INSERT INTO `$table_name` (".implode(",",$cells).") VALUES";
		$line .= $insert;
		do{
			$record_row = array();
			for($n = 0; $n<count($cells); $n++){
				$field_name = str_replace("`","",$cells[$n]);
				$record = str_replace("'","''",$row_backup[str_replace("`","",$cells[$n])]);
				if(is_numeric($record)){
					array_push($record_row," ".$record);
				} else {
					array_push($record_row," '".$record."'");
				}
			}
			$record_row = implode(",",$record_row);
			$record_row = "\n(".$record_row.")";
			array_push($records,$record_row);
			if(strlen(implode(",",$records)) > $q_length){
				$line .= implode(",",$records).";\n".$insert;
				$records = array();
			}
		} while($row_backup = mysql_fetch_assoc($backup));
		$line .= implode(",",$records).";";
	}
	$sql .= $line;
	mysql_free_result($backup);
}

mysql_free_result($tables);

if(!file_exists("downloads/database/".$date."/backup.sql")){
	$sql_handle = fopen("downloads/database/".$date."/backup.sql", 'x');
	fwrite($sql_handle, $sql);
	fclose($sql_handle);
}
echo "Please Wait... A backup of your database is being created.";
echo "<script type=\"text/javascript\">document.location.href=\"control_panel.php\";</script>";
?>
