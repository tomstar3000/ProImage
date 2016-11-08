<? if(!isset($r_path)){	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-1;	$r_path = "";	for($n=0;$n<$count;$n++)$r_path .= "../";}
define("PhotoExpress Pro", true);
define('Allow Scripts',true);
require_once $r_path.'Connections/cp_connection.php';
require_once $r_path.'scripts/fnct_clean_entry.php';
require_once $r_path.'scripts/fnct_sql_processor.php';

$ID = strtoupper(trim(clean_variable($_GET['ID'],true)));

$getStates = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
$getStates->mysql("SELECT `state_short` 
	FROM `a_states`
	INNER JOIN `a_country`
		ON `a_country`.`country_id` = `a_states`.`country_id`
	WHERE `country_short_3` = '$ID' GROUP BY `state_short` ORDER BY `state_short` ASC");
$getStates->mssql("SELECT state_short
	FROM a_states
	INNER JOIN a_country
		ON a_country.country_id = a_states.country_id
	WHERE country_short_3 = '$ID' GROUP BY state_short ORDER BY state_short ASC");	

header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header('Content-type: text/xml');

echo '<?xml version="1.0" encoding="utf-8"?>';
echo '<statelist>';
foreach($getStates->Rows() as $r) echo '<state>'.$r['state_short'].'</state>';
echo '</statelist>'; ?>