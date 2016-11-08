<? if(!isset($r_path)){ $count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2; $r_path = ""; for($n=0;$n<$count;$n++) $r_path .= "../"; }
include $r_path.'security.php';

require_once $r_path.'scripts/fnct_format_date.php';
require_once $r_path.'scripts/fnct_format_phone.php';
require_once($r_path.'scripts/encrypt.php');
require_once $r_path.'../scripts/fnct_ImgeProcessor.php'; ?>
<script type="text/javascript">
function InvoiceImageUpdate(NAME,ID,INV){
	var HTML = '<h1>Upload new image for '+NAME+'</h1>'
					+'<form method="post" action="/PhotoCP/includes/invoice_updater.php" name="InvoiceImageUpdaterForm" id="InvoiceImageUpdaterForm" enctype="multipart/form-data" target="HiddenForm">'
					+'<input type="file" name="Image" id="Image" /><br />'
					//+'<div id="BtnImgSbmt" onclick="javascript:document.getElementById(\'InvoiceImageUpdaterForm\').submit();"><input type="submit" name="Submit" id="Submit" value="Submit" /></div>'
					+'<input type="submit" name="Submit" id="Submit" value="Submit" />'
					+'<input type="hidden" name="Image Id" id="Image_Id" value="'+ID+'" />'
					+'<input type="hidden" name="Invoice Id" id="Invoice_Id" value="'+INV+'" />'
					+'<input type="hidden" name="Customer Id" id="Customer_Id" value="<? echo $CustId; ?>" />'
					+'<input type="hidden" name="Controller" id="Controller" value="true" />'
					+'</form>'
	send_Msg(HTML,true,null,null);
}
</script>

<? if(isset($Error) && strlen(trim($Error)) > 0){ ?>
<div id="RecordTable" class="White">
  <div id="Top"></div>
  <div id="Records">
    <p class="Error"><? echo $Error; ?></p>
  </div>
  <div id="Bottom"></div>
</div>
<br clear="all" />
<? } ?>
<h1 id="HdrType2" class="<? switch($path[0]){
	case 'Clnt': echo 'ClntInvClnt'; break;
	case 'Busn': switch($path[1]){
			case 'Open': echo 'BsnInvsOpen'; break;
			case 'All': echo 'BsnInvsAll'; break;
		} break;
	default: echo 'ClntInvClnt'; break; } ?>">
  <div>Order #<? echo $InvNum.' '.format_date($Date,"Short",false,true,false); ?></div>
</h1>
<? if($Sent == "n"){ ?>
<div id="HdrLinks"> <a href="#" onclick="javascript:set_form('form_','<? echo implode(",",$path); ?>','ToLab','<? echo $sort; ?>','<? echo $rcrd; ?>');" onmouseover="window.status='Send Order to Lab'; return true;" onmouseout="window.status=''; return true;" title="Send Order to Lab" class="BtnSndLab">Send Order to Lab</a> </div>
<? } ?>
<div id="RecordTable" class="White">
  <div id="Top"></div>
  <div id="Records">
    <p><strong>Customer Information:</strong><br />
      <? echo $FName.' '.$LName; ?></a></p>
    <p><strong>Shipping Information:</strong><br />
      <? echo $SFName." ".$SLName; ?> <br />
      <? echo $SAdd.(($SSuite!='')?$SSuite:''); ?><br />
      <? echo $SCity.' '.$SState.'. '.$SZip; ?><br />
      <? echo phone_number($Phone); ?><br />
      <? if($Email!=""){ ?>
      <a href="mailto:<? echo $Email; ?>"><? echo $Email; ?></a>
      <? } ?>
    </p>
    <p><strong>Special Instructions: </strong><br />
      <? echo $IncComm; ?></p>
  </div>
  <div id="Bottom"></div>
</div>
<br clear="all" />
<div id="RecordTable" class="Black">
  <div id="Top"></div>
  <div id="Records">
    <? if($getImgs->TotalRows() > 0) {$n = 0; foreach($getImgs->Rows() as $r){
			$image_id = $r['image_id'];
			$getSizes = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
			$getSizes->mysql("SELECT `orders_invoice_photo`.*, `prod_products`.`prod_name` FROM `orders_invoice_photo` INNER JOIN `prod_products` ON `prod_products`.`prod_id` = `orders_invoice_photo`.`invoice_image_size_id` WHERE `image_id` = '$image_id' AND `invoice_id` = '$IId' ORDER BY `prod_products`.`prod_name` ASC;");
						
			$Folder = explode("/",$r['image_folder']); array_splice($Folder,-2,2,"Large"); $Folder = implode("/",$Folder);
			$Image = ($r['invoice_image_image'] != "") ? $r['invoice_image_image'] : $r['image_tiny'];
			
			$Imager = new ImageProcessor();
			$Imager->SetMaxSize(67108864);
			$Imager->File($photographerFolder.$Folder."/".$Image);
			$Imager->Kill();
			$Imager->CalcResize(100,100);
			$Imager->CalcRotate($r['image_rotate']);
			$width = $Imager->CalcWidth[0];
			$height = $Imager->CalcHeight[0];
			
			$data = array("id" => $r['image_id'], "width" => $width, "height" => $height);
			
			$Imager->CalcResize(1000,1000);
			$Imager->CalcRotate($r['image_rotate']);
			$width2 = $Imager->CalcWidth[0];
			$height2 = $Imager->CalcHeight[0];
			
			$data2 = array("id" => $r['image_id'], "width" => $width2, "height" => $height2); ?>
    <div id="OrderImg">
      <a href="/images/image.php?data=<? echo base64_encode(encrypt_data(serialize($data2))).'&amp;t='.time(); ?>" target="_blank"><img src="/images/image.php?data=<? echo base64_encode(encrypt_data(serialize($data))).'&amp;t='.time(); ?>" width="<? echo $width; ?>" height="<? echo $height; ?>" hspace="5" vspace="5" /></a>
      <table border="0" cellpadding="0" cellspacing="0">
        <tr>
          <th colspan="4"><? echo $Image; ?></th>
        </tr>
        <tr>
          <td colspan="4"><? if($Sent == "n"){ ?>
            <div id="BtnUpdate"><a href="javascript:InvoiceImageUpdate('<? echo $Image; ?>','<? echo $r['image_id']; ?>','<? echo $IId; ?>');" onmouseover="window.status='Update Image'; return true;" onmouseout="window.status=''; return true;" title="Update Image">Update</a></div>
            <? } else { echo "&nbsp;"; } ?></td>
        </tr>
        <tr>
          <th>Size</th>
          <th>As Is</th>
          <th>B&amp;W</th>
          <th>Sepia</th>
        </tr>
        <? foreach($getSizes->Rows() as $r2){ ?>
        <tr>
          <td><? echo $r2['prod_name']; ?></td>
          <td><? echo ($r2['invoice_image_asis'] != 0)?$r2['invoice_image_asis']:"&nbsp;"; ?></td>
          <td><? echo ($r2['invoice_image_bw'] != 0)?$r2['invoice_image_bw']:"&nbsp;"; ?></td>
          <td><? echo ($r2['invoice_image_sepia'] != 0)?$r2['invoice_image_sepia']:"&nbsp;"; ?></td>
        </tr>
        <? } ?>
      </table>
    </div>
    <? $n++; if($n>=2){ $n = 0; echo '<br clear="all" />'; } } if($n < 2) echo '<br clear="all" />'; ?>
    <br clear="all" />
    <? } if($getBdrs->TotalRows() > 0) { $z = 0; $n = 0; foreach($getBdrs->Rows() as $r){ $z++;
				$image_id = $r['invoice_image_id'];			
				
				$getSizes = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
				$getSizes->mysql("SELECT `orders_invoice_border`.*, `prod_products`.`prod_name` 
				FROM `orders_invoice_border` 
				INNER JOIN `prod_products` 
					ON `prod_products`.`prod_id` = `orders_invoice_border`.`invoice_image_size_id` 
				WHERE `invoice_image_id` = '$image_id' 
					AND `invoice_id` = '$IId' 
				ORDER BY `prod_products`.`prod_name` ASC;");
				
				$Image = "ImageId=".$r['image_id']."&Border=".$r['border_id']."&Horizontal=".$r['invoice_horz']."&NWidth=".$r['invoice_imgW']."&NHeight=".$r['invoice_imgH']."&BWidth=".$r['invoice_bordW']."&BHeight=".$r['invoice_bordH']."&SX=".$r['invoice_imgX']."&SY=".$r['invoice_imgY']."&Text=".$r['invoice_text']."&TX=".$r['invoice_textX']."&TY=".$r['invoice_textY']."&Font=".$r['invoice_font']."&Size=".$r['invoice_size']."&Color=".$r['invoice_color']."&Bold=".$r['invoice_bold']."&Italic=".$r['invoice_italic'];
		?>
    <div id="OrderImg">
      <a href="<? echo "/preview.php?".$Image; ?>" target="_blank"><img src="<? echo "/iconpreview.php?".$Image; ?>" alt="<? echo $z."_".$r['prod_name']."_".$r['image_tiny']; ?>" width="100" hspace="5" vspace="5" border="0" align="left" /></a>
      <table border="0" cellpadding="0" cellspacing="0">
        <tr>
          <th colspan="4"><? echo $z."_".$r['prod_name']."_".$r['image_tiny']; ?></th>
        </tr>
        <tr>
          <td colspan="4">&nbsp;</td>
        </tr>
        <tr>
          <th>Size</th>
          <th>As Is</th>
          <th>B&amp;W</th>
          <th>Sepia</th>
        </tr>
        <? foreach($getSizes->Rows() as $r2){ ?>
        <tr>
          <td><? echo $r2['prod_name']; ?></td>
          <td><? echo ($r2['invoice_image_asis'] != 0)?$r2['invoice_image_asis']:"&nbsp;"; ?></td>
          <td><? echo ($r2['invoice_image_bw'] != 0)?$r2['invoice_image_bw']:"&nbsp;"; ?></td>
          <td><? echo ($r2['invoice_image_sepia'] != 0)?$r2['invoice_image_sepia']:"&nbsp;"; ?></td>
        </tr>
        <? } ?>
      </table>
    </div>
    <? $n++; if($n>=2){ $n = 0; echo '<br clear="all" />'; } } if($n < 2) echo '<br clear="all" />'; ?>
    <br clear="all" />
    <? } ?>
  </div>
  <div id="Bottom"></div>
</div>
<br clear="all" />
