<?
if(isset($_GET['data'])){
	$data = unserialize(base64_decode(urldecode($_GET['data'])));
	foreach($data as $k => $v) $$k = $v;
}
$SendCSS = array();
if(isset($Width)) $SendCSS['Width'] = $Width;
if(isset($Height)) $SendCSS['Height'] = $Height;
$ToCntrl['Value'] = true;
foreach($data as $k => $v) $ToCntrl[$k] = $v;

session_start(); if(!session_is_registered('JSNavigator')) session_register('JSNavigator'); $_SESSION['JSNavigator'] = true; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Navigator</title>
<link href="<? echo $NavFolder; ?>/css/stylesheet.css" rel="stylesheet" type="text/css" media="screen" />
<script type="text/javascript" src="<? echo $NavFolder; ?>/javascript/custom-form-elements.js"></script>
<script type="text/javascript" src="<? echo $NavFolder; ?>/javascript/cntrlrs.php?data=<? echo urlencode(base64_encode(serialize($ToCntrl))); ?>"></script>
<script type="text/javascript" src="<? echo $NavFolder; ?>/javascript/standard_functions.js"></script>
</head>
<body>
<!-- <textarea id="Testing" name="Testing" style="width:745px; height:150px;"></textarea> -->
<div id="FltMenuList" onmouseover="javascript: Org_PopMenuMove(false,null,null); Org_PopMenu_Over();" onmouseout="t = 1; Org_PopMenu_Out(); Org_PopMenuHide();">
  <ul>
    <li><a href="javascript:Org_PopMenu_Open();" onmouseover="javascript:window.status='Open group'; return true;" onmouseout="javascript:window.status=''; return true;" title="Open group">Open Group</a></li>
    <li><a href="javascript:Org_PopMenu_Add();" onmouseover="javascript:window.status='Add a group'; return true;" onmouseout="javascript:window.status=''; return true;" title="Add a group">Add Group</a></li>
    <li><a href="javascript:Org_PopMenu_Rnme();" onmouseover="javascript:window.status='Rename group'; return true;" onmouseout="javascript:window.status=''; return true;" title="Rename group">Rename Group</a></li>
    <li><a href="javascript:Org_PopMenu_Rmv();" onmouseover="javascript:window.status='Remove group'; return true;" onmouseout="javascript:window.status=''; return true;" onclick="return confirm('Are you sure you want to remove this group? Removing this group will place it\'s images in the Trash');" title="Remove group">Remove Group</a></li>
    <li><a href="javascript:Org_PopMenu_Upld();" onmouseover="javascript:window.status='Upload images to folder'; return true;" onmouseout="javascript:window.status=''; return true;" title="Upload images to folder" >Upload to Group</a></li>
    <li><a href="javascript:Org_PopMenu_Trsh();" onmouseover="javascript:window.status='Move all images to the trash'; return true;" onmouseout="javascript:window.status=''; return true;" title="Move all images to the trash" onclick="return confirm('Are you sure you want to move all the images to the trash folder?');">Move All Images to Trash</a></li>
    <li><a href="javascript:Org_PopMenu_Epty_Trsh();" onmouseover="javascript:window.status='Remove all the images in the trash'; return true;" onmouseout="javascript:window.status=''; return true;" title="Remove all the images in the trash" onclick="return confirm('Are you sure you want to empty your trash?');">Empty Trash</a></li>
    <li><a href="javascript:Org_PopMenu_Upld();" onmouseover="javascript:window.status='Upload image groups to Event'; return true;" onmouseout="javascript:window.status=''; return true;" title="Upload image groups to Event" >Upload image groups</a></li>
  </ul>
</div>
<div id="BdTop"></div>
<div id="Container">
  <input type="hidden" name="CoverID" id="CoverID" value="0" />
  <div id="Folders">
    <h3>Image Groups</h3>
    <div id="HdrLinks150"><a href="javascript:Org_Rmv_Fldr();" class="BtnRmvFolder" onmouseover="javascript:window.status='Remove Group'; return true;" onmouseout="javascript:window.status=''; return true;" title="Remove Group" onclick="return confirm('Are you sure you want to remove the selected group? Removing this group will place it\'s images in the Trash');">Remove Group</a> <a href="javascript:Org_Fldr_Upld();" onmouseover="javascript:window.status='Upload to Group'; return true;" onmouseout="javascript:window.status=''; return true;" title="Upload to Group" class="BtnUpldFolder">Upload to Group</a><a href="javascript:Org_Create_Folder();" class="BtnAddFolder" onmouseover="javascript:window.status='Add Group'; return true;" onmouseout="javascript:window.status=''; return true;" title="Add Group">Add Group</a></div>
    <div id="FoldList"></div>
  </div>
  <div id="Navigation">
    <h3>Global Image Tools</h3>
    <!-- <div id="HdrLinks150"><a href="#" class="BtnApplyEffect">Apply Effects</a></div> -->
    <div id="Btns">
      <div id="Btn">
        <div><a href="javascript:Org_Sel_Images(true);" class="BtnSelAll" onmouseover="javascript:window.status='Select All Images'; return true;" onmouseout="javascript:window.status=''; return true;" title="Select All Images">Select All</a></div>
      </div>
      <div id="Btn">
        <div><a href="javascript:Org_Sel_Images(false);" class="BtnDeSelAll" onmouseover="javascript:window.status='Deselect All Images'; return true;" onmouseout="javascript:window.status=''; return true;" title="Deselect All Images">Deselect All</a></div>
      </div>
      <div id="Btn">
        <div><a href="javascript:Org_Fav_Imgs();" class="BtnFavImg" onmouseover="javascript:window.status='Make selected images favorite'; return true;" onmouseout="javascript:window.status=''; return true;" title="Make selected images favorite">Make Favorite Image</a></div>
      </div>
      <div id="Btn">
        <div><a href="javascript:Org_Eff_Imgs('Rotate','90');" class="BtnRotateClck" onmouseover="javascript:window.status='Rotate Clockwise'; return true;" onmouseout="javascript:window.status=''; return true;" title="Rotate Clockwise">Rotate Clockwise</a></div>
      </div>
      <div id="Btn">
        <div><a href="javascript:Org_Eff_Imgs('Rotate','-90');" class="BtnRotateCntClck" onmouseover="javascript:window.status='Rotate Counter Clockwise'; return true;" onmouseout="javascript:window.status=''; return true;" title="Rotate Counter Clockwise">Rotate Counter Clockwise</a></div>
      </div>
      <!-- <div id="Btn">
        <div><a href="javascript:Org_Eff_Imgs('ImgColor','d');" class="BtnImgColor" onmouseover="javascript:window.status='Default Image Color'; return true;" onmouseout="javascript:window.status=''; return true;" title="Default Image Color">Default Image Color</a></div>
      </div>
      <div id="Btn">
        <div><a href="javascript:Org_Eff_Imgs('ImgColor','b');" class="BtnImgBlackWhite" onmouseover="javascript:window.status='Turn Image Color To Black &amp; White'; return true;" onmouseout="javascript:window.status=''; return true;" title="Turn Image Color To Black &amp; White">Turn Image Color To Black &amp; White</a></div>
      </div>
      <div id="Btn">
        <div><a href="javascript:Org_Eff_Imgs('ImgColor','s');" class="BtnImgSepia" onmouseover="javascript:window.status='Turn Image Color To Sepia'; return true;" onmouseout="javascript:window.status=''; return true;" title="Turn Image Color To Sepia">Turn Image Color To Sepia</a></div>
      </div> -->
      <div id="Btn">
        <div><a href="javascript:Org_Del_Imgs();" class="BtnRmvImg" onmouseover="javascript:window.status='Delete selected images'; return true;" onmouseout="javascript:window.status=''; return true;" title="Delete selected images" onclick="return confirm('Are you sure you want to remove these images?');">Delete Image</a></div>
      </div>
      <br clear="all" />
      <div id="Btn">
        <div>
          <div id="Mover">
            <label for="Move_to_Folder">Move Selected Images To:</label>
            <div id="SelectFolder">
              <select name="Move to Folder" id="Move_to_Folder" class="CstmFrmElmnt">
                <option value="0" title="Select Group" selected="selected">-- Select Group --</option>
              </select>
            </div>
            <div id="BtnMove"> <a href="javascript:Org_Move_Imgs();" onmouseover="javascript:window.status='Move images to selected group'; return true;" onmouseout="javascript:window.status=''; return true;" title="Move images to selected group">Move Images</a></div>
            <br clear="all" />
          </div>
        </div>
      </div>
      <div id="Btn">
        <div>
          <div id="Mover">
            <label for="Move_to_Folder">Move Selected Group To:</label>
            <div id="SelectFolder">
              <select name="Move Folder to Folder" id="Move_Folder_to_Folder" class="CstmFrmElmnt">
                <option value="0" title="Main Group" selected="selected">Main Group</option>
              </select>
            </div>
            <div id="BtnMove"> <a href="javascript:Org_Move_Fold();" onmouseover="javascript:window.status='Move group to selected group'; return true;" onmouseout="javascript:window.status=''; return true;" title="Move group to selected group">Move Group</a></div>
            <br clear="all" />
          </div>
        </div>
      </div>
      <!-- 
      <div id="Btn">
        <div><a href="#" class="BtnMailFrnd">Mail to a Friend</a></div>
      </div> -->
      <!-- 
      <div id="Btn">
        <div>
          <div id="Controller" class="Contrast">
            <div id="Min">-100</div>
            <div id="Max">100</div>
            <div id="Bar">
              <div id="Cnt"></div>
            </div>
            <p>Contrast</p>
          </div>
        </div>
      </div>
      <div id="Btn">
        <div>
          <div id="Controller" class="Brightness">
            <div id="Min">-100</div>
            <div id="Max">100</div>
            <div id="Bar">
              <div id="Cnt"></div>
            </div>
            <p>Brightness</p>
          </div>
        </div>
      </div>
      <div id="Btn">
        <div>
          <div id="Controller" class="Sharpen">
            <div id="Min">-100</div>
            <div id="Max">100</div>
            <div id="Bar">
              <div id="Cnt"></div>
            </div>
            <p>Sharpen</p>
          </div>
        </div>
      </div>
      <div id="Btn">
        <div><a href="#" class="BtnPblcImg">Make Public Images</a></div>
      </div>
      <div id="Btn">
        <div><a href="#" class="BtnPrvtImg">Make Private Images</a></div>
      </div> -->
    </div>
  </div>
  <div id="Images"></div>
</div>
<div id="BdBottom"></div>
<script type="text/javascript">Org_Load_Fold(0);</script>
</body>
</html>
