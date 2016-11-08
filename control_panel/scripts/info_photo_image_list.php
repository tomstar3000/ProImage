<?
if(isset($r_path)===false){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++)$r_path .= "../";
}
include $r_path.'security.php';

$query_get_info = "SELECT `image_folder`,`image_tiny`, `image_id` FROM `photo_event_images` INNER JOIN `photo_event_group` ON `photo_event_images`.`group_id` = `photo_event_group`.`group_id` WHERE `event_id` = '$path[2]' AND `image_active` = 'y' AND `group_use` = 'y' AND `group_id` > 0 ORDER BY `image_tiny`";
$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
$row_get_info = mysql_fetch_assoc($get_info);
$total_get_info = mysql_num_rows($get_info);
?>

<div>
  <p>Select your event cover from the images below. The event cover can be updated anytime. </p>
  <table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
      <? 
		$a=0;
		$b=0;
		do{
			$b++;
			$index = strrpos($row_get_info['image_folder'], "/");
			$Folder = substr($row_get_info['image_folder'],0,$index);	
			$index = strrpos($Folder, "/");
			$Folder = substr($Folder,0,$index); ?>
      <td valign="top"><a href="/<? echo $Folder."/Thumbnails/".$row_get_info['image_tiny'];?>" target="_blank"><img src="/<? echo $Folder."/Icon/".$row_get_info['image_tiny'];?>" alt="<? echo $row_get_info['image_tiny']; ?>" width="50" hspace="2" vspace="2" border="0" align="left"></a>
        <input type="radio" name="Image_item" id="Image_item" value="<? echo $row_get_info['image_id'];?>" />
        <? echo $row_get_info['image_tiny']; ?></td>
      <? 
	$a++;
		if($a == 4){
			$a = 0;
			echo '</tr><tr><td colspan="4"><hr /></td></tr><tr>';
		}
	} while($row_get_info = mysql_fetch_assoc($get_info)); 
	if($a < 4) for($n=$a;$n<4;$n++){ echo '<td>&nbsp;</td>'; }
	?>
    </tr>
  </table>
  <br clear="all" />
</div>
<br clear="all" />
