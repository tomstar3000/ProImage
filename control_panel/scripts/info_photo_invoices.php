<? if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++){
		$r_path .= "../";
	}
}
include $r_path.'security.php';
require_once $r_path.'scripts/fnct_format_date.php';
require_once $r_path.'scripts/fnct_format_phone.php';
$is_enabled = ((count($path)<=3 && $cont == "view") || (count($path)>3)) ? false : true;
$is_back = ($cont == "edit" || count($path)>3) ? "view" : "query"; ?>

<div id="PleaseWait" style="display:none;">
 <div id="Hider"></div>
 <div id="Message">
  <h2 align="center"><strong>Please Wait!</strong></h2>
  <br clear="all" />
  <p><strong>This order is being sent to the lab.</strong></p>
  <br clear="all" />
 </div>
</div>
<script type="text/javascript">
function form_submit(id){
	mywindow = window.open("/control_panel/image_updater.php?<? echo ($token!="") ? $token."&": ""; ?>image="+id+"&invoice=<? echo $InvEnc; if(isset($_GET['adminid'])) echo '&adminid='.$_GET['adminid']; ?>","","toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=0,resizable=0,width=300,height=250");
}
</script>
<div id="Sub_Header"> <img src="/control_panel/images/hdr_form_left.jpg" width="17" height="33" border="0" align="left" />
 <h2>Order #<? echo $InvNum."&nbsp;&nbsp;&nbsp;&nbsp;".format_date($Date,"Short",false,true,false); ?></h2>
 <p id="Back"><a href="#" onclick="javascript:set_form('form_','<?php echo implode(",",array_slice($path,0,count($path)-1)); ?>','<? echo $is_back; ?>','<?php echo $sort; ?>','<?php echo $rcrd; ?>');">Back</a></p>
 <? if($Sent == "n"){ ?>
 <p id="Lab"><a href="#" onclick="javascript:set_form('form_','<?php echo implode(",",$path); ?>','ToLab','<?php echo $sort; ?>','<?php echo $rcrd; ?>');">To
   Lab</a>
  <? } ?>
 </p>
</div>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
 <tr>
  <td width="20%"><strong>Customer Information: </strong></td>
  <td colspan="3"><? echo $FName." ".$LName; ?>&nbsp;</td>
 </tr>
 <tr>
  <td colspan="4"><strong>Shipping Information: </strong></td>
 </tr>
 <tr>
  <td colspan="4"><p><? echo $SFName." ".$SLName; ?> <br />
    <? echo $SAdd; if($SSuite!="")echo $SSuite; ?><br />
    <? echo $SCity." ".$SState.". ".$SZip; ?><br />
    <? echo phone_number($Phone); ?><br />
    <? if($Email!=""){ ?>
    <a href="mailto:<? echo $Email; ?>"><? echo $Email; ?></a>
    <? } ?>
    <br />
    &nbsp;</p></td>
 </tr>
 <tr>
  <td colspan="4"><strong>Special Instructions: <br />
   <? echo $IncComm; ?> </strong></td>
 </tr>
 <tr>
  <td colspan="4"><strong>Order Contents:</strong></td>
 </tr>
 <tr>
  <? $n = 0;
		$a = 0;
		while($row_get_image = mysql_fetch_assoc($get_image)){ 
			$image_id = $row_get_image['image_id'];
			$query_get_sizes = "SELECT `orders_invoice_photo`.*, `prod_products`.`prod_name` FROM `orders_invoice_photo` INNER JOIN `prod_products` ON `prod_products`.`prod_id` = `orders_invoice_photo`.`invoice_image_size_id` WHERE `image_id` = '$image_id' AND `invoice_id` = '$IId' ORDER BY `prod_products`.`prod_name` ASC";
			$get_sizes = mysql_query($query_get_sizes, $cp_connection) or die(mysql_error());
			$n++;
			$a++;
			$folder = $row_get_image['image_folder'];
			$index = strrpos($folder,"/");
			$folder = substr($folder,0,$index);
			$index = strrpos($folder,"/");
			$folder = substr($folder,0,$index);
			$image = ($row_get_image['invoice_image_image'] != "") ? $row_get_image['invoice_image_image'] : $row_get_image['image_tiny'];
		?>
  <td width="32%" valign="top"><a href="<? echo "/".$folder."/Thumbnails/".$image; ?>" target="_blank"><img src="<? echo "/".$folder."/Icon/".$image; ?>" alt="<? echo $image; ?>" width="100" hspace="5" vspace="5" border="0" align="left" /></a>
   <table border="0" cellpadding="0" cellspacing="0" width="155">
    <tr>
     <td colspan="4"><strong><? echo $image; ?></strong></td>
    </tr>
    <tr>
     <td colspan="4"><? if($Sent == "n"){ ?>
      <img src="/control_panel/images/btn_update.jpg" alt="Update Image" width="131" height="25" border="0" style="cursor:pointer" onclick="javascript:form_submit('<? echo $row_get_image['image_id']; ?>');" />
      <? } else { echo "&nbsp;"; } ?></td>
    </tr>
    <tr>
     <td><strong>Size</strong></td>
     <td><strong>As Is</strong></td>
     <td><strong>B&amp;W</strong></td>
     <td><strong>Sepia</strong></td>
    </tr>
    <? while($row_get_sizes = mysql_fetch_assoc($get_sizes)){ ?>
    <tr>
     <td><? echo $row_get_sizes['prod_name']; ?></td>
     <td><? echo ($row_get_sizes['invoice_image_asis'] != 0)?$row_get_sizes['invoice_image_asis']:"&nbsp;"; ?></td>
     <td><? echo ($row_get_sizes['invoice_image_bw'] != 0)?$row_get_sizes['invoice_image_bw']:"&nbsp;"; ?></td>
     <td><? echo ($row_get_sizes['invoice_image_sepia'] != 0)?$row_get_sizes['invoice_image_sepia']:"&nbsp;"; ?></td>
    </tr>
    <? } ?>
   </table></td>
  <? if($n == 3){
				$n = 0;
				echo "</tr><tr><td colspan=\"3\"><hr /></td><tr>";
			} } 
			$z = 1;
			while($row_get_border = mysql_fetch_assoc($get_border)){ 
				$image_id = $row_get_border['invoice_image_id'];
				$query_get_sizes = "SELECT `orders_invoice_border`.*, `prod_products`.`prod_name` 
				FROM `orders_invoice_border` 
				INNER JOIN `prod_products` 
					ON `prod_products`.`prod_id` = `orders_invoice_border`.`invoice_image_size_id` 
				WHERE `invoice_image_id` = '$image_id' 
					AND `invoice_id` = '$IId' 
				ORDER BY `prod_products`.`prod_name` ASC";
				$get_sizes = mysql_query($query_get_sizes, $cp_connection) or die(mysql_error());
				$n++;
				$a++;
				$image = "ImageId=".$row_get_border['image_id']."&Border=".$row_get_border['border_id']."&Horizontal=".$row_get_border['invoice_horz']."&NWidth=".$row_get_border['invoice_imgW']."&NHeight=".$row_get_border['invoice_imgH']."&BWidth=".$row_get_border['invoice_bordW']."&BHeight=".$row_get_border['invoice_bordH']."&SX=".$row_get_border['invoice_imgX']."&SY=".$row_get_border['invoice_imgY']."&Text=".$row_get_border['invoice_text']."&TX=".$row_get_border['invoice_textX']."&TY=".$row_get_border['invoice_textY']."&Font=".$row_get_border['invoice_font']."&Size=".$row_get_border['invoice_size']."&Color=".$row_get_border['invoice_color']."&Bold=".$row_get_border['invoice_bold']."&Italic=".$row_get_border['invoice_italic'];
		?>
  <td width="32%" valign="top"><a href="<? echo "/preview.php?".$image; ?>" target="_blank"><img src="<? echo "/iconpreview.php?".$image; ?>" alt="<? echo $z."_".$row_get_border['prod_name']."_".$row_get_border['image_tiny']; ?>" width="100" hspace="5" vspace="5" border="0" align="left" /></a>
   <table border="0" cellpadding="0" cellspacing="0" width="155">
    <tr>
     <td colspan="4"><strong><? echo $z."_".$row_get_border['prod_name']."_".$row_get_border['image_tiny']; ?></strong></td>
    </tr>
    <tr>
     <td colspan="4">&nbsp;</td>
    </tr>
    <tr>
     <td><strong>Size</strong></td>
     <td><strong>As Is</strong></td>
     <td><strong>B&amp;W</strong></td>
     <td><strong>Sepia</strong></td>
    </tr>
    <? while($row_get_sizes = mysql_fetch_assoc($get_sizes)){ ?>
    <tr>
     <td><? echo $row_get_sizes['prod_name']; ?></td>
     <td><? echo ($row_get_sizes['invoice_image_asis'] != 0)?$row_get_sizes['invoice_image_asis']:"&nbsp;"; ?></td>
     <td><? echo ($row_get_sizes['invoice_image_bw'] != 0)?$row_get_sizes['invoice_image_bw']:"&nbsp;"; ?></td>
     <td><? echo ($row_get_sizes['invoice_image_sepia'] != 0)?$row_get_sizes['invoice_image_sepia']:"&nbsp;"; ?></td>
    </tr>
    <? } ?>
   </table></td>
  <? $z++; if($n == 3){
				$n = 0;
				echo "</tr><tr><td colspan=\"3\"><hr /></td><tr>";
			} } 
			if($n<3){
				for($z = $n;$z<=$n+1;$z++){
					echo "<td>&nbsp;</td>";
			} } ?>
 </tr>
</table>
