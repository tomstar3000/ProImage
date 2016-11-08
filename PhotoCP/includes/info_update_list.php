<? if(isset($r_path)===false){ $count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-1; $r_path = ""; for($n=0;$n<$count;$n++) $r_path .= "../"; }

$getInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
$getInfo->mysql("SELECT `page_text_text` FROM `web_pages` INNER JOIN `web_page_text` ON `web_page_text`.`page_id` = `web_pages`.`page_id` WHERE `web_pages`.`page_id` = '1';");
$getInfo = $getInfo->Rows(); ?>

<h1 id="HdrType2" class="CreateEvnt">
  <div>Update List</div>
</h1>
<div id="RecordTable" class="Green">
  <div id="Top"></div>
  <div id="Records">
    <p><? echo $getInfo[0]['page_text_text']; ?></p>
    <br clear="all" />
  </div>
  <div id="Bottom"></div>
</div>
