<?
require_once 'scripts/fnct_format_date.php';
require_once 'scripts/fnct_format_phone.php';
// --------------------------
// ---- Record Table Styles ------
// --------------------------
$AevNet_Path = "control_panel";
$Rec_Style_1 = "E4E4E4";
$Rec_Style_2 = "EEEEEE";
$Rec_Style_3 = "DDDDDD";
$Rec_Style_4 = "EFEFEF";
$Rec_Style_5 = "CDCDCD";
$hdr_img = '<img src="/'.$AevNet_Path.'/images/hdr_form_left.jpg" width="18" height="33" align="left" />';
// --------------------------
$MaxSize = 3200000; //Maximum Files Sizes that can be loaded
// --------------------------
// ---- Borders Images ------
// --------------------------
$Border_HIReq = false;
$Border_HTReq = false;
$Border_VIReq = false;
$Border_VTReq = false;
// Icon Image
$Border_IcResize = true;
$Border_IcCrop = true;
$Border_IcWidth = 129;
$Border_IcHeight = 75;
$Border_IcReq = false;
// --------------------------
// ---- Product Images ------
// --------------------------
$Prod_Folder = "../images/products";
// Large Image
$Prod_IResize = true;
$Prod_ICrop = false;
$Prod_IWidth = 500;
$Prod_IHeight = 500;
$Prod_IReq = false;
// Thumbnail Image
$Prod_TResize = true;
$Prod_TCrop = false;
$Prod_TWidth = 250;
$Prod_THeight = 250;
$Prod_TReq = false;
// Icon Image
$Prod_IcResize = true;
$Prod_IcCrop = false;
$Prod_IcWidth = 130;
$Prod_IcHeight = 100;
$Prod_IcReq = false;
// --------------------------
// ---- Discount Images -----
// --------------------------
$Disc_Folder = "../images/discounts";
// Large Image
$Disc_IResize = true;
$Disc_ICrop = false;
$Disc_IWidth = 275;
$Disc_IHeight = 81;
$Disc_IReq = false;
// --------------------------
// ---- Product Attribute Images ----
// --------------------------
$Prod_Attrib_Folder = "../images/attributes";
// Large Image
$Prod_Attrib_IResize = true;
$Prod_Attrib_ICrop = false;
$Attrib_IWidth = 20;
$Attrib_IHeight = 20;
$Attrib_IWidth_2 = 70;
$Attrib_IHeight_2 = 70;
$Prod_Attrib_IReq = false;
// Thumb Image
$Prod_Attrib_TResize = true;
$Prod_Attrib_TCrop = false;
$Prod_Attrib_TWidth = 500;
$Prod_Attrib_THeight = 150;
$Prod_Attrib_TReq = false;
// --------------------------
// ---- Product Review Images ------
// --------------------------
$Review_Folder = "../images/reviews";
// Large Image
$Review_IResize = true;
$Review_ICrop = false;
$Review_IWidth = 20;
$Review_IHeight = 20;
$Review_IReq = false;
//--------------------------------------------------------------------------------
// --------------------------
// ---- Category Images -----
// --------------------------
$Cat_Folder = "../images/categories";
// Large Image
$Cat_IResize = true;
$Cat_ICrop = false;
$Cat_IWidth = 500;
$Cat_IHeight = 150;
$Cat_IReq = false;
// --------------------------
// ---- Feature Images ------
// --------------------------
$Feat_Folder = "../images/features";
// Large Image
$Feat_IResize = true;
$Feat_ICrop = false;
$Feat_IWidth = 500;
$Feat_IHeight = 150;
$Feat_IReq = false;
// --------------------------
// ---- Spec Images ---------
// --------------------------
$Spec_Folder = "../images/specs";
// Large Image
$Spec_IResize = true;
$Spec_ICrop = false;
$Spec_IWidth = 500;
$Spec_IHeight = 150;
$Spec_IReq = false;
// --------------------------
// ---- Attribute Images ----
// --------------------------
$Attrib_Folder = "../images/attributes";
// Large Image
$Attrib_IResize = true;
$Attrib_ICrop = false;
$Attrib_IWidth = 500;
$Attrib_IHeight = 150;
$Attrib_IReq = false;
// --------------------------
// ---- Attribute Images ----
// --------------------------
$Select_Folder = "../images/selections";
// Large Image
$Select_IResize = true;
$Select_ICrop = false;
$Select_IWidth = 500;
$Select_IHeight = 150;
$Select_IReq = false;
// --------------------------
// ---- Attribute Images ----
// --------------------------
$Group_Folder = "../images/groups";
// Large Image
$Group_IResize = true;
$Group_ICrop = false;
$Group_IWidth = 500;
$Group_IHeight = 150;
$Group_IReq = false;
// --------------------------
// ---- Company Images ------
// --------------------------
$Comp_Folder = "../images/customers";
// Large Image
$Comp_IResize = true;
$Comp_ICrop = false;
$Comp_IWidth = 250;
$Comp_IHeight = 250;
$Comp_IReq = false;
// Thumbnail
$Comp_TResize = true;
$Comp_TCrop = false;
$Comp_TWidth = 82;
$Comp_THeight = 46;
$Comp_TReq = false;
// --------------------------
// ---- Product Images ------
// --------------------------
$Proj_Folder = "../images/projects";
// Large Image
$Proj_IResize = true;
$Proj_ICrop = false;
$Proj_IWidth = 500;
$Proj_IHeight = 150;
$Proj_IReq = false;
// Large Image
$Proj_TResize = true;
$Proj_TCrop = false;
$Proj_TWidth = 500;
$Proj_THeight = 150;
$Proj_TReq = false;
// --------------------------
// ---- Invoice Header ------
// --------------------------
$hdr_1 = "Company:";
$hdr_2 = "Invoice Number:";
$hdr_3 = "Date";
/*
ob_start();
?>
<style>
#inv_Contract{
	font-family:Geneva, Arial, Helvetica, sans-serif;
	font-size: 10px;
	font-style: normal;
	line-height: normal;
	font-weight: normal;
	font-variant: normal;
	text-transform: none;
	color: #000000;
	text-decoration: none;
}
#inv_Header{
	clear: both;
	float: none;
	margin: 0px;
	padding: 0px;
	height: auto;
	width: auto;
}
#inv_Items table{
	border: #000000 1px solid;
}
#inv_Logo{
	width:250px;
	height:auto;
	background:#284464;
	float:left;
	clear: left;
	margin: 0px;
	padding: 0px;
}
#inv_Logo table{
	margin-left:10px;
	margin-right:10px;
}
#inv_Footer{
	text-align: center;
}
#inv_Titles{
	background:#284464;
	padding:5px;
	margin-top:10px;
}
#inv_Titles p{
	color:#FFFFFF;
	font-weight:bold;
	font-size: 12px;
}
h3{
	color:#284464;
	font-family:Geneva, Arial, Helvetica, sans-serif;
	font-size:60px;
	letter-spacing: 15px;
	text-align: center;
}
p{
	color:#000000;
	font-family:Geneva, Arial, Helvetica, sans-serif;
	font-size:10px;
}
.Border_1{
	border-right:1px solid #666666;
	border-bottom:1px solid #666666;
}
.Border_2{
	border-bottom:1px solid #666666;
}
.Border_3{
	border-right:1px solid #666666;
}
.Headers{
	color:#FFFFFF;
	font-size:12px;
}
</style>
<div id="inv_Header">
  <div id="inv_Logo">
    <p><img src="/<? echo $AevNet_Path; ?>/images/Invoice_Logo.jpg" width="250" height="125" /></p>
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
      <tr>
        <td width="50%" valign="top" class="Headers"><? echo $hdr_1; ?></td>
        <td width="50%" valign="top" class="Headers"><? echo $CName; ?></td>
      </tr>
      <tr>
        <td valign="top" class="Headers"><? echo $hdr_2; ?></td>
        <td valign="top" class="Headers"><? echo $INum;
		if($IRev > 1){
			echo " Rev. ".($IRev-1);
		} ?></td>
      </tr>
      <tr>
        <td valign="top" class="Headers"><? echo $hdr_3; ?></td>
        <td valign="top" class="Headers"><? echo date("m/d/Y"); ?></td>
      </tr>
	   <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
    </table>
  </div>
  <h3>INVOICE</h3>
  <p align="center"> <br />
    Aevium<br />
    14186 W. 2nd Ave.<br />
    Golden, CO. 80401<br />
    info@aevium.com, 303-278-4861 </p>
  <br clear="all" />
</div>
<div id="inv_Titles">
  <div id="Description" style="width:66%; float:left">
    <p>Description:</p>
  </div>
  <div id="Hours" style="width:16%; float:left">
    <p align="right">Hours:</p>
  </div>
  <div id="Amount" style="width:16%; float:left">
    <p align="right">Amount:</p>
  </div>
  <br clear="all" />
</div>
<? 
$inv_header = ob_get_contents();
ob_end_clean();

ob_start();
?>
<div id="inv_Footer">
  <p>&copy; <? echo date("Y"); ?> Aevium LLC.</p>
</div>
<? 
$inv_footer = ob_get_contents();
ob_end_clean();
*/
?>
