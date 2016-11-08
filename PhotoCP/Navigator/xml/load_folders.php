<? if(!isset($r_path)){	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-1;	$r_path = "";	for($n=0;$n<$count;$n++)$r_path .= "../"; }
$r_path = '../';
require_once $r_path.'includes/cp_connection.php';
require_once $r_path.'scripts/encrypt.php';
require_once $r_path.'scripts/fnct_clean_entry.php';
require_once $r_path.'includes/CleanNames.php';

$data = trim(clean_variable(decrypt_data(base64_decode($_GET['data'])),true));
$master = trim(clean_variable(decrypt_data(base64_decode($_GET['master'])),true));

header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header('Content-type: text/xml');

echo '<?xml version="1.0" encoding="utf-8"?>';

function buildFoldersRoot(){
  global $database_cp_connection,$cp_connection,$gateways_cp_connection,$data,$master;
  $getRcrds = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);

  $getRcrds->mysql("SELECT count(`photo_event_images`.`image_id`) AS TotalCount, `photo_event_images`.`group_id`
                    FROM `photo_event_images`, `photo_event_group`, `photo_event`
                    WHERE `photo_event_images`.`group_id` = `photo_event_group`.`group_id`
                    AND `photo_event`.`event_id` = `photo_event_group`.`event_id`
                    AND `photo_event`.`event_id` = '$data'
                    AND `photo_event`.`cust_id` = '$master'
                    AND `image_active` = 'y'");
  if($getRcrds->totalRows() > 0){
    $totalCount = $getRcrds->Rows();
    echo '<folders count="'.$totalCount[0]['TotalCount'].'">';
    buildFolders(0);
  }
  else{
    echo '<folders count="0">';
  }
}

function buildFolders($PRNT){
  global $database_cp_connection,$cp_connection,$gateways_cp_connection,$data,$master;
  $getRcrds = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
  $getRcrds->mysql("SELECT `photo_event_group`.*, (SELECT count(`photo_event_images`.`image_id`) FROM `photo_event_images` WHERE `photo_event_images`.`group_id` = `photo_event_group`.`group_id` AND `cust_id` = '$master' AND `image_active` = 'y') AS ImageCount
    FROM `photo_event_group`
    INNER JOIN `photo_event`
      ON `photo_event`.`event_id` = `photo_event_group`.`event_id`
    WHERE `photo_event_group`.`event_id` = '$data'
      AND `photo_event_group`.`parnt_group_id` = '$PRNT'
      AND `photo_event`.`cust_id` = '$master'
      AND `photo_event_group`.`group_use` = 'y'
    GROUP BY `photo_event_group`.`group_id`
    ORDER BY `photo_event_group`.`group_name` ASC");
  if($getRcrds->totalRows() > 0){
    foreach($getRcrds->Rows() as $r){
      echo '<folder id="'.$r['group_id'].'" name="'.incodeNames((strlen(trim($r['group_name']))>0)?$r['group_name']:'Unnamed').'" count="'.$r['ImageCount'].'">';
      buildFolders($r['group_id']);
      echo '</folder>';
    }
  }
}

buildFoldersRoot();
echo '</folders>'; ?>