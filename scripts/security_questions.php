<?php 
$query_get_sec_quest = "SELECT * FROM `securty_questions` ORDER BY `question` ASC";
$get_sec_quest = mysql_query($query_get_sec_quest, $cp_connection) or die(mysql_error());
?>
