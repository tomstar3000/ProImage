<? if(isset($r_path)===false){ $count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2; $r_path = ""; for($n=0;$n<$count;$n++) $r_path .= "../"; }
include $r_path.'security.php';
require_once($r_path.'scripts/fnct_format_date.php'); ?>
<h1 id="HdrType2" class="ImgsGrps">
  <div>Images &amp; Groups</div>
</h1>
<br clear="all" />
<?
$getInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
$getInfo->mysql("SELECT `event_num`, `event_name` FROM `photo_event` WHERE `event_id` = '".$path[2]."';");
$getInfo = $getInfo->Rows();
$Code = $getInfo[0]['event_num'];
		
require_once($r_path.'Navigator/Navigator.php');
$Nav = new Navigator();
$Nav->setSize(745,700);
$Nav->PassVar('EvntID',$path[2],true);
$Nav->PassVar('EvntName',$getInfo[0]['event_name']);
$Nav->PassVar('PID',$CustId,true);
$Nav->PassVar('HNDL',$Code.$CHandle);
$Nav->PassVar('EML',$Email);
if($cont == "add"){
	$Nav->Uploader = true;
}
$Nav->exportHTML();
?>
<br clear="all" />
