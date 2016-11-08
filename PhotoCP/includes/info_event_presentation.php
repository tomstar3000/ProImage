<script type="text/javascript" src="/javascript/AC_OETags.js"></script>
<script type="text/javascript">
    var Cntrl_StartX = 0;
    var Cntrl_StartL = 0;
    var Cntrl_Drgng = false;
    var Cntrl_Elmnt = null;
    var Cntrl_Timer = 0;

    if (document.layers) { document.captureEvents(Event.MOUSEMOVE); document.onmousemove = captureMousePosition; /* Netscape */ } 
    else if (document.all) document.onmousemove = captureMousePosition; /* Internet Explorer */ 
    else if (document.getElementById) document.onmousemove = captureMousePosition; /* Netcsape 6 */ 
    xMousePos = 0; yMousePos = 0; // Horizontal and Vertical position of the mouse on the screen
    function captureMousePosition(e) { if (document.layers) { // Mouse positions in Netscape
    xMousePos = e.pageX; yMousePos = e.pageY;
    } else if (document.all) { // Mouse positions in IE
    xMousePos = event.clientX+document.body.scrollLeft; yMousePos = window.event.y+document.body.scrollTop;
    } else if (document.getElementById) { // Netscape 6 behaves the same as Netscape 4 in this regard 
    xMousePos = e.pageX; yMousePos = e.pageY; } }
    function Cntrl_Down(ELMNT){ document.onmouseup = Cntrl_Up;
    Cntrl_Drgng = true;	Cntrl_Elmnt = ELMNT; Cntrl_StartX = xMousePos;
    Cntrl_StartL = parseInt(Cntrl_Elmnt.style.left);
    setTimeout("Cntrl_Drag()", 10); upDateWatermark(); }
    function Cntrl_Up(){ if(Cntrl_Elmnt != null) { document.onmouseup = '';
    document.getElementById('Watermark_Opacity').value = Math.round(parseInt(Cntrl_Elmnt.style.left)/117*100);
    Cntrl_Elmnt.parentNode.focus();
    Cntrl_Drgng = false; Cntrl_Elmnt = null; } upDateWatermark(); }
    function Cntrl_Drag(){ if(Cntrl_Elmnt != null){
    var Diff = Cntrl_StartL+xMousePos-Cntrl_StartX;
    if(Diff<0) Diff = 0;
    else if (Diff>117) Diff = 117;
    Cntrl_Elmnt.style.left = Diff+"px";
    document.getElementById('Watermark_Opacity').value = Math.round(parseInt(Cntrl_Elmnt.style.left)/117*100);
    if(Cntrl_Drgng==true) setTimeout("Cntrl_Drag()", 10);
    } upDateWatermark(); }
    function upDateWatermark(){
    var Copy = document.getElementById('Watermark').value;
    var Freq = document.getElementById('Watermark_Frequency').value;
    var Opac = document.getElementById('Watermark_Opacity').value;
    getPlayer().sendToActionScript(Freq+","+Copy+","+Opac);
    }
    function getPlayer(){
    var player = document.getElementById('BeaverDivers');
    if (player == null) player = document.getElementsByTagName('object')[0];
    if (player == null || player.object == null)
    player = document.getElementsByTagName('embed')[0];
    return player;
    }
    function isReady() {
    return true;
    }
</script>
<? if ($path[3] == "Present") { ?>
    <h1 id="HdrType2" class="EvntPresnt">
        <div>Watermark Options</div>
    </h1>
<? } else { ?>
    <h1 id="HdrType2-5" class="EvntPresnt">
        <div>Watermark Options</div>
    </h1>
    <div id="btnCollapse"><a href="#" onClick="javascript: Open_Sec('EventPresnt', this);
                return false;" onMouseOver="window.status = 'Expand Event Order Processing Options';
                return true;" onMouseOut="window.status = '';
                return true;" title="Expand Event Order Processing Options">+</a></div>
    <? } ?>
<div id="HdrLinks"><!-- <a href="#" class="BtnHelp">Help</a> --><? if ($path[3] != "Present") { ?>
        <a href="#" onclick="javascript: Save_Default_Info();
                return false;" onmouseover="window.status = 'Save Default Settings';
                return true;" onmouseout="window.status = '';
                return true;" title="Save Default Settings" class="BtnSaveDefault">Save Defaults</a>
       <? } ?>
    <a href="#" onclick="javascript:<? echo $onclick; ?> return false;" onmouseover="window.status = 'Save Event Information';
            return true;" onmouseout="window.status = '';
            return true;" title="Save Event Information" class="BtnSave<?php if(implode(',',$path) == 'Evnt,Evnt' && $cont=='add') echo 'Upload'; ?>">Save</a></div>
<div id="RecordTable<? echo($path[3] == "Present") ? '' : '-5'; ?>" class="White"> <a id="EventPresnt"></a>
    <div id="Top"></div>
  <div id="Records" class="Colmn2"><!-- <span style="width:163px; padding-left:15px;">
    <label for="Image_Display" class="CstmFrmElmntLabel">Initial Display As:</label>
    <p>
      <input type="radio" name="Image Display" id="Image_Display" value="y" class="CstmFrmElmnt" title="Thumbnails" onMouseOver="window.status='Thumbnails'; return true;" onMouseOut="window.status=''; return true;" />
      <font class="fontSpecial2">Thumbnails</font><br clear="all" />
    </p>
    <sup id="Default"><strong>*&nbsp;</strong></sup>
    <p>
      <input type="radio" name="Image Display" id="Image_Display" value="y" class="CstmFrmElmnt" title="Full Size Images" onMouseOver="window.status='Full Size Images'; return true;" onMouseOut="window.status=''; return true;" />
      <font class="fontSpecial2">Full Size Images</font><br clear="all" />
    </p>
    <p>
      <input type="radio" name="Image Display" id="Image_Display" value="y" class="CstmFrmElmnt" title="Slideshow" onMouseOver="window.status='Slideshow'; return true;" onMouseOut="window.status=''; return true;" />
      <font class="fontSpecial2">Slideshow</font><br clear="all" />
    </p>
    <br />
    <label for="Images_Per_Page" class="CstmFrmElmntLabel">Images Per Page</label>
    <select name="Images Per Page" id="Images_Per_Page" class="CstmFrmElmnt64" onmouseover="window.status='Images Per Page'; return true;" onmouseout="window.status=''; return true;" title="Images Per Page">
        <? for ($n = 1; $n <= 20; $n++) { ?>
              <option value="<? echo ($n * 10); ?>" title="<? echo ($n * 10) . " Images per page"; ?>"><? echo ($n * 10); ?></option>
        <? } ?>
    </select> 
    <br clear="all" />
    </span> --><span style="width:505px; padding-left:15px; margin-left:auto; margin-right:auto; float:none;">
            <label class="CstmFrmElmntLabel">Watermark Options</label>
            <div id="WatermarkDiv"> <span style="width:142px;">
                    <label for="Watermark" class="CstmFrmElmntLabel">Watermark Text</label>
                    <input type="text" name="Watermark" id="Watermark" onfocus="javascript:this.className = 'CstmFrmElmntInputi117NavSel';" onblur="javascript:this.className = 'CstmFrmElmntInputi117';" onmouseover="window.status = 'Watermark Text';
            return true;" onmouseout="window.status = '';
            return true;" title="Watermark Text" onchange="javascript:upDateWatermark();" class="CstmFrmElmntInputi117" value="<? echo $Copy; ?>" />
                    <br />
                    <label for="Number_of_Reps" class="CstmFrmElmntLabel">Number of Reps</label>
                    <select name="Watermark_Frequency" id="Watermark_Frequency" class="CstmFrmElmnt64" onmouseover="window.status = 'Number of Repetitions';
            return true;" onmouseout="window.status = '';
            return true;" onchange="javascript:upDateWatermark();" title="Number of Repetitions">
                                <? for ($n = 0; $n < 10; $n++) { ?>
                            <option value="<? echo ($n + 1); ?>" title="<? echo ($n + 1) . " Times"; ?>"<? if ($WFreq == ($n + 1)) echo ' selected="selected"'; ?>><? echo ($n + 1); ?></option>
                        <? } ?>
                    </select>
                    <br />
                    <!-- 
                    <label for="Position" class="CstmFrmElmntLabel">Position</label>
                    <select name="Position" id="Position" class="CstmFrmElmnt117" onmouseover="window.status='Watermark Position'; return true;" onmouseout="window.status=''; return true;" title="Watermark Position">
                      <option value="Left" title="Left Justified">Left Justified</option>
                      <option value="Center" title="Center Justified">Center Justified</option>
                      <option value="Right" title="Right Justified">Right Justified</option>
                    </select>
                    <br /> -->
                    <label class="CstmFrmElmntLabel">Opacity</label>
                    <div id="BtnController">
                        <div id="Min">0</div>
                        <div id="Max">100</div>
                        <div id="Bar">
                            <div id="Cnt" style="left:<? echo ($Opac / 100) * 117 ?>px" onMouseDown="javascript:Cntrl_Down(this);" onMouseUp="javascript:Cntrl_Up();"></div>
                        </div>
                    </div>
                    <input type="hidden" name="Watermark_Opacity" id="Watermark_Opacity" value="<? echo $Opac; ?>" />
                </span> <span style="width:331px;">
                    <label class="CstmFrmElmntLabel">Sample View</label>
                    <div id="WatermarkDiv2">
                        <?
                        $Image = "/PhotoCP/images/dandi3.jpg";
                        if ($cont == edit) {
                            $getImg = new sql_processor($database_cp_connection, $cp_connection, $gateways_cp_connection);
                            $getImg->mysql("SELECT `image_folder`, `image_tiny` FROM `photo_event_images` WHERE `image_id` = '$Image';");
                            if ($getImg->TotalRows() > 0) {
                                $getImg = $getImg->Rows();
                                $Folder = explode("/", $getImg[0]['image_folder']);
                                array_splice($Folder, -2, 2, "Icon");
                                $Folder = implode("/", $Folder);
                                $Image = "/" . $Folder . "/" . $getImg[0]['image_tiny'];
                            }
                        }
                        ?>
                        <script type="text/javascript">	AC_FL_RunContent( 'codebase','http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0','name','PhotoExpress','width','180','height','180','align','middle','id','PhotoExpress','src','/flash/Watermark?image=<? echo $Image; ?>','quality','high','bgcolor','#4A4A4A','allowscriptaccess','always','pluginspage','http://www.macromedia.com/go/getflashplayer','wmode','transparent','movie','/flash/Watermark?image=<? echo $Image; ?>' ); //end AC code
                        </script>
                        <noscript>
                        <object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0" name="PhotoExpress" width="180" height="180" align="middle" id="PhotoExpress">
                            <param name="allowScriptAccess" value="always" />
                            <param name="movie" value="/flash/Watermark.swf?image=<? echo $Image; ?>" />
                            <param name="quality" value="high" />
                            <param name="bgcolor" value="#4A4A4A" />
                            <param name="wmode" value="transparent" />
                            <embed src="/flash/Watermark.swf?image=<? echo $Image; ?>" width="180" height="180" align="middle" quality="high" bgcolor="#4A4A4A" name="PhotoExpress" allowscriptaccess="always" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" wmode="transparent" />
                        </object>
                        </noscript>
                    </div>
                </span> </div>
        </span> <br clear="all" />
        <br clear="all" />
    </div>
    <div id="Bottom"></div>
</div>
<br clear="all" />
