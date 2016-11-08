<?
if (isset($r_path) === false) {
    $count = count(explode("/", substr($_SERVER['PHP_SELF'], 1))) - 2;
    $r_path = "";
    for ($n = 0; $n < $count; $n++)
        $r_path .= "../";
}
include $r_path . 'security.php';
require_once($r_path . 'scripts/fnct_format_date.php');
require_once($r_path . 'scripts/encrypt.php');
require_once $r_path . '../scripts/fnct_ImgeProcessor.php';
?>
<script type="text/javascript" src="/PhotoCP/javascript/event_info.php"></script>
<script type="text/javascript">
  var XMLFold = 'Navigator/xml'; // XML Folder
  var XMLFavImg = XMLFold+'/favorite_images.php'; // Were to go to make an image a favorite

  function GetXmlHttpObject(){ // Set up our gateway
  try { Gateway=new ActiveXObject("Microsoft.XMLHTTP"); Gateway.async="false"; } // Internet Explorer
  catch (e) { try{ Gateway = new XMLHttpRequest(); } // Firefox, Opera 8.0+, Safari
              catch(e){ Gateway=new ActiveXObject('MSXML2.XMLHTTP.3.0');} } // Internet Explorer 5.5 and 6
  return Gateway; }

  function Org_Initiate(){ // Initiate the xmlHTTP gateway
    xmlHttp=GetXmlHttpObject(); // Create a new Object
    if (xmlHttp==null) { alert ("Your browser does not support AJAX!"); return; } // Alert people that their browswer doesn't support Ajax
  }

  function Org_Imgs_Chngd_No_Load(){ // Called once we recieve a successful return from the Ajax gateway
    if (xmlHttp.readyState==4){ SendMsg("Images Saved",true); }
  }

  function SendMsg(VAL,AMND){ return false; if(AMND == true) document.getElementById('Testing').value = VAL+'\r\n'+document.getElementById('Testing').value; else document.getElementById('Testing').value = VAL; }// Send Message to our testing textarea

  function Org_Get_Btn(VAL,NME){ var BTN = null;
    for(var n=0; n<document.getElementById(VAL).getElementsByTagName('ul')[0].getElementsByTagName('li').length; n++){
      // console.log( n );
      if(document.getElementById(VAL).getElementsByTagName('ul')[0].getElementsByTagName('li')[n].className == NME){
        var BTN = document.getElementById(VAL).getElementsByTagName('ul')[0].getElementsByTagName('li')[n]; break;
        } } return BTN; }

  function Org_Fav_Img(VAL){ var ImgArry = new Array(); ImgArry.push(VAL); // Set a single image as a favorite
    var BTN = Org_Get_Btn(VAL,"BtnImgFav"); var ACT = "false";
    if(BTN.getElementsByTagName('a')[0].className=="NavSel"){ BTN.getElementsByTagName('a')[0].className=""; }
    else { BTN.getElementsByTagName('a')[0].className="NavSel"; ACT = "true"; }
    // console.log( ACT );
    SendMsg("Favorites Image "+ImgArry,true); ImgArry = serialize(ImgArry); Org_Initiate(); // Serialize the Array for PHP
    if(xmlHttp != null) {var url = XMLFavImg+"?data="+escape(ImgArry)+"&master=<? echo base64_encode(encrypt_data($CustId)); ?>&handle=<? echo base64_encode(urlencode($Code.$CHandle)); ?>&email=<? echo base64_encode(urlencode($Email)); ?>&action="+ACT;
      xmlHttp.open('get',url); xmlHttp.onreadystatechange = Org_Imgs_Chngd_No_Load; xmlHttp.send('');
    } }

  function TrckImg(VAL){ ImgTrack = VAL; } // Set Image Tracker
</script>
<? if ($cont == "view") { ?>

    <h1 id="HdrType2" class="UpcmngEvnt">
        <div>Overview</div>
    </h1>
    <div id="HdrLinks">
        <? if ($USE == 'y') { ?>
            <a href="javascript:document.getElementById('Controller').value = 'Remove'; document.getElementById('form_action_form').submit();" onclick="javascript:if (confirm('Are You Sure You Want To Remove This Events? If you select to remove event this event will no longer be available for re-release and will delete all images from the server. Reports will remain for event.'))
                                return true;
                            else
                                return false;" onmouseover="window.status = 'Remove Event <? echo str_replace("'", "\'", $Name); ?>';
                            return true;" onmouseout="window.status = '';
                            return true;" title="Remove Event <? echo str_replace("'", "\'", $Name); ?>" class="BtnRmvEvnt">Remove Event</a>
            <a href="javascript:document.getElementById('Controller').value = 'Expire'; document.getElementById('form_action_form').submit();" onclick="javascript:if (confirm('Are You Sure You Want To Expire This Events?'))
                                return true;
                            else
                                return false;" onmouseover="window.status = 'Expire Event <? echo str_replace("'", "\'", $Name); ?>';
                            return true;" onmouseout="window.status = '';
                            return true;" title="Expire Event <? echo str_replace("'", "\'", $Name); ?>" class="BtnExpEvnt">Expire Event</a>
            <a href="/photo_viewer.php?Photographer=<? echo $CHandle; ?>&code=<? echo $Code; ?>&email=<? echo $Email; ?>&full=true" target="_blank" onmouseover="window.status = 'View Event <? echo str_replace("'", "\'", $Name); ?>';
                            return true;" onmouseout="window.status = '';
                            return true;" title="View Event <? echo str_replace("'", "\'", $Name); ?>" class="BtnviewEvtn">View Event</a>
           <? } else { ?>
            <a href="#" onclick="javascript:document.getElementById('Controller').value = 'Release';
                            document.getElementById('form_action_form').submit();
                            return false;" onmouseover="window.status = 'Re-Release Event <? echo str_replace("'", "\'", $Name); ?>';
                            return true;" onmouseout="window.status = '';
                            return true;" title="Re-Release Event <? echo str_replace("'", "\'", $Name); ?>" class="BtnReRelEvnt">Re-Release Event</a>
           <? } ?>
    </div>
    <br clear="all" />
    <h1 id="HdrType3" class="EvntInfo">
        <div>Event Name / Contact</div>
    </h1>
    <div id="HdrLinks3"><a href="javascript:set_form('','<? echo implode(",", $path); ?>','edit','<? echo $sort; ?>','<? echo $rcrd; ?>');" title="Edit Event <? echo $Name; ?>" onmouseover="window.status = 'Edit Event <? echo $Name; ?>';
                        return true;" onmouseout="window.status = '';
                        return true;" class="BtnEdit">Edit</a></div>
    <div id="RecordTable" class="White">
        <div id="Top"></div>
        <div id="Records" class="Colmn4">
            <div class="FltLeft">
              <h4><? echo $Name; ?></h4>
              <p> <strong>Code:</strong> <? echo $Code; ?></p>
          </div>
          <div id="FltRight" class="BdLGrey">
              <h4>Expiration Date</h4>
              <p><? echo date("n/j/y", mktime(1, 0, 0, substr($EDate, 5, 2), substr($EDate, 8, 2), substr($EDate, 0, 4))); ?></p>
          </div>
          <div id="FltRight" class="BdLGrey">
              <h4>Release Date</h4>
              <p><? echo date("n/j/y", mktime(1, 0, 0, substr($Date, 5, 2), substr($Date, 8, 2), substr($Date, 0, 4))); ?></p>
          </div>
        </div>
        <div id="Bottom"></div>
    </div>
    <br clear="all" />
    <div id="Box210" style="margin: 0 10px 0 0;">
        <h1 id="HdrType210" class="EvntProcc" style="width: 210px;">
            <div>Order Processing</div>
        </h1>
        <div id="RecordTable210" class="White" style="margin: 0 5px;">
            <div id="Top"></div>
            <div id="Records">
                <p>
                    <input type="radio" name="SendLab" id="SendLab" value="y" class="CstmFrmElmnt"<? if ($ToLab == 'y') echo ' checked="checked"'; ?> title="Send all orders for this event directly to the lab" onmouseover="window.status = 'Send all orders for this event directly to the lab';
                        return true;" onmouseout="window.status = '';
                        return true;" onclick="javascript:Save_Evnt_Shp_Opt('<? echo base64_encode(encrypt_data($EId)); ?>');" />
                    Send directly to lab </p>
                <div class="DividerBlack"></div>
                <p>
                    <input type="radio" name="SendLab" id="SendLab" value="n" class="CstmFrmElmnt"<? if ($ToLab == 'n') echo ' checked="checked"'; ?> title="Approve all orders for this event before they are sent to the lab" onmouseover="window.status = 'Approve all orders for this event before they are sent to the lab';
                        return true;" onmouseout="window.status = '';
                        return true;" onclick="javascript:Save_Evnt_Shp_Opt('<? echo base64_encode(encrypt_data($EId)); ?>');" />
                    I want to approve my orders<br clear="all" />
                </p>
            </div>
            <div id="Bottom"></div>
        </div>
    </div>
    <div id="Box330">
        <h1 id="HdrType330" class="EvntStats" style="width: 330px; ">
            <div>Event Statistics</div>
        </h1>
        <div id="RecordTable330" class="White" style="margin: 0 5px;">
            <div id="Top"></div>
            <div id="Records">
                <div id="GroupBoxBlue">
                    <p>Views</p>
                    <div id="Tab">
                        <?
                        $getViews = new sql_processor($database_cp_connection, $cp_connection, $gateways_cp_connection);
                        $getViews->mysql("SELECT DISTINCT COUNT(`fav_email`) AS `fav_count` 
					FROM `photo_cust_favories`
					WHERE `fav_code` = '" . $Code . $CHandle . "';");
                        $getViews = $getViews->Rows();
                        echo $getViews[0]['fav_count'];
                        ?>
                    </div>
                </div>
                <div id="GroupBoxGreen2">
                    <p>Total Orders</p>
                    <div id="Tab">
                        <?
                        $getOrders = new sql_processor($database_cp_connection, $cp_connection, $gateways_cp_connection);
                        $getOrders->mysql("SELECT COUNT(`invoice_id`) AS `order_count` FROM (
                        (
                                SELECT `orders_invoice`.`invoice_id`
                                FROM `orders_invoice`
                                INNER JOIN `orders_invoice_photo` 
                                        ON `orders_invoice_photo`.`invoice_id` = `orders_invoice`.`invoice_id` 
                                INNER JOIN `photo_event_images` 
                                        ON `photo_event_images`.`image_id` = `orders_invoice_photo`.`image_id`
                                INNER JOIN `photo_event_group` 
                                        ON `photo_event_group`.`group_id` = `photo_event_images`.`group_id`
                                INNER JOIN `photo_event` 
                                        ON `photo_event`.`event_id` = `photo_event_group`.`event_id`
                                WHERE `photo_event`.`event_id` = '$EId'
                                GROUP BY `orders_invoice`.`invoice_id`
                        ) UNION (
                                SELECT `orders_invoice`.`invoice_id`
                                FROM `orders_invoice` 
                                INNER JOIN `orders_invoice_border` 
                                        ON `orders_invoice_border`.`invoice_id` = `orders_invoice`.`invoice_id` 
                                INNER JOIN `photo_event_images` 
                                        ON `photo_event_images`.`image_id` = `orders_invoice_border`.`image_id`
                                INNER JOIN `photo_event_group` 
                                        ON `photo_event_group`.`group_id` = `photo_event_images`.`group_id`
                                INNER JOIN `photo_event` 
                                        ON `photo_event`.`event_id` = `photo_event_group`.`event_id`
                                WHERE `photo_event`.`event_id` = '$EId'
                                GROUP BY `orders_invoice`.`invoice_id`
                        ) 
                ) AS `DV1`;");
                        $getOrders = $getOrders->Rows();
                        echo $getOrders[0]['order_count'];
                        ?>
                    </div>
                    <!-- <div id="BtnView"><a href="#">View</a></div> -->
                </div>
                <div id="GroupBoxGreen2">
                    <p>Total Sales</p>
                    <div id="Tab">
                        <?
                        $getOrders = new sql_processor($database_cp_connection, $cp_connection, $gateways_cp_connection);
                        $getOrders->mysql("SELECT (SUM(`invoice_grand`) + SUM(`invoice_disc`)) AS `order_total` FROM (
                                (
                                        SELECT `orders_invoice`.*, `photo_event_group`.`event_id`
                                        FROM `orders_invoice`
                                        INNER JOIN `orders_invoice_photo` 
                                                ON `orders_invoice_photo`.`invoice_id` = `orders_invoice`.`invoice_id` 
                                        INNER JOIN `photo_event_images` 
                                                ON `photo_event_images`.`image_id` = `orders_invoice_photo`.`image_id`
                                        INNER JOIN `photo_event_group` 
                                                ON `photo_event_group`.`group_id` = `photo_event_images`.`group_id`
                                        INNER JOIN `photo_event` 
                                                ON `photo_event`.`event_id` = `photo_event_group`.`event_id`
                                        WHERE `photo_event`.`event_id` = '$EId'
                                        GROUP BY `orders_invoice`.`invoice_id`
                                ) UNION (
                                        SELECT `orders_invoice`.*, `photo_event_group`.`event_id`
                                        FROM `orders_invoice` 
                                        INNER JOIN `orders_invoice_border` 
                                                ON `orders_invoice_border`.`invoice_id` = `orders_invoice`.`invoice_id` 
                                        INNER JOIN `photo_event_images` 
                                                ON `photo_event_images`.`image_id` = `orders_invoice_border`.`image_id`
                                        INNER JOIN `photo_event_group` 
                                                ON `photo_event_group`.`group_id` = `photo_event_images`.`group_id`
                                        INNER JOIN `photo_event` 
                                                ON `photo_event`.`event_id` = `photo_event_group`.`event_id`
                                        WHERE `photo_event`.`event_id` = '$EId'
                                        GROUP BY `orders_invoice`.`invoice_id`
                                ) 
                        ) AS `DV1`;");
                        $getOrders = $getOrders->Rows();
                        echo '$' . number_format($getOrders[0]['order_total'], 2, ".", ",");
                        ?>
                    </div>
                    <!-- <div id="BtnView"><a href="#">View</a></div> -->
                </div>
                <br clear="all" />
            </div>
            <div id="Bottom"></div>
        </div>
    </div>
    <br clear="all" />
    <br clear="all" />
    <h1 id="HdrType3" class="EvntFavs">
        <div>Photographer Favorites</div>
    </h1>
    <div id="RecordTable" class="Black">
        <div id="Top"></div>
        <div id="Records">
            <?
            $FavsMsg = '<div style="width:550px; margin-left:auto; margin-right:auto; padding-top:15px;"><h1 style="font-size:25px; margin-bottom:10px;"><img src="/PhotoCP/images/graph_Favs.jpg" width="64" height="64" align="left" hspace="10" style="margin-bottom:60px;" />Don\'t forget to select your favorite photos!</h1><p>Selecting your favorite photos from any event is an easy way to bring the best images straight to the top for your clients. It also helps you keep organized and provides you with an easy way to market your most attractive images.</p>
    <p>Go to your <a href="javascript:set_form(\'\',\'' . implode(",", array_slice($path, 0, 3)) . ',ImgsGrps\',\'view\',\'' . $sort . '\',\'' . $rcrd . '\');" title="Edit Event Favorites" onmouseover="window.status=\'Edit Event Favorites\'; return true;" onmouseout="window.status=\'\'; return true;">images and groups</a> page to get started!</p>
    <p>Once selected, your favorites will show up here. You can modify them at any time.</p></div>';
            $getFavs = new sql_processor($database_cp_connection, $cp_connection, $gateways_cp_connection);
            $getFavs->mysql("SELECT `fav_xml`
					FROM `photo_cust_favories`
					WHERE `fav_code` = '" . $Code . $CHandle . "'
						AND `fav_email` = '" . $Email . "'
						AND `fav_occurance` = '2'
					ORDER BY `fav_date` DESC;");
            if ($getFavs->TotalRows() > 0) {
                $getFavs = $getFavs->Rows();
                $getFavs = explode(".", $getFavs[0]['fav_xml']);
                if (count($getFavs) > 0) {
                    foreach ($getFavs as $r) {
                        if (strlen($r) > 0) {
                            $getFav = new sql_processor($database_cp_connection, $cp_connection, $gateways_cp_connection);
                            $getFav->mysql("SELECT `image_folder`,`image_tiny`,`image_name`, `image_id`, `image_rotate` FROM `photo_event_images` WHERE `image_id` = '" . $r . "';");
                            ?>
                            <div id="<? echo $r; ?>">
                            <div id="FavImg">
                                    <?
                                    $getFav = $getFav->Rows();
                                    $Name = $getFav[0]['image_tiny'];
                                    if (strlen($Name) > 20) {
                                        $index = strpos($Name, ".");
                                        $type = substr($Name, ($index + 1));
                                        $Name = substr($Name, 0, $index);
                                        $Subtract = ceil(strlen($Name) / 2) - ceil((strlen($Name) - $StrLen + 3 + (strlen($type) + 1)) / 2);
                                        $Name = substr($Name, 0, $Subtract) . "..." . substr($Name, (-1 * $Subtract)) . "." . $type;
                                    }

                                    $Folder = explode("/", $getFav[0]['image_folder']);
                                    array_splice($Folder, -2, 2, "Large");
                                    $Folder = implode("/", $Folder);

                                    $Imager = new ImageProcessor();
                                    $Imager->SetMaxSize(67108864);
                                    $Imager->File("../" . $r_path . $Folder . "/" . $getFav[0]['image_tiny']);
                                    $Imager->Kill();
                                    $Imager->CalcResize(134, 134);
                                    $Imager->CalcRotate($getFav[0]['image_rotate']);
                                    $width = $Imager->CalcWidth[0];
                                    $height = $Imager->CalcHeight[0];

                                    $data = array("id" => $getFav[0]['image_id'], "width" => 134, "height" => 134);
                                    $MRGN = round((134 - $height) / 2) + 10;
                                    ?>
                                    <p><? echo $Name; ?></p>
                                    <ul id="Btns">
                                      <li class="BtnImgFav">
                                          <a href="javascript: Org_Fav_Img('<? echo $r; ?>');" onmousedown="javascript:TrckImg(false);" onmouseup="javascript:TrckImg(true);" onmouseover="javascript:window.status='Make image: <? echo $Name; ?>, a favorite'; return true;" onmouseout="javascript:window.status=''; return true;" title="Make image: <? echo $Name; ?>, a favorite" class="NavSel"} ?> >Make Favorite</a>
                                      </li>
                                    </ul>
                                    <img src="/images/image.php?data=<? echo base64_encode(encrypt_data(serialize($data))) . '&amp;t=' . time(); ?>" width="<? echo round($width); ?>" height="<? echo round($height); ?>" vspace="<? echo $MRGN; ?>" style="margin-top:0; margin-bottom:0;" />
                            </div>
                            </div>
                            <?
                        }
                    }
                } else {
                    echo $FavsMsg;
                }
            } else {
                echo $FavsMsg;
            }
            ?>
            <br clear="all" />
        </div>
        <div id="Bottom"></div>
    </div>
    <br clear="all" />
    <?
} else {
    if ($cont == "edit") {
        require_once($r_path . 'includes/info_calendar.php');
        ?>
        <script type="text/javascript">
            function Open_Sec(ID, ELMNT) {
            if (ELMNT.className == "NavSel") {
            ELMNT.className = '';
            document.getElementById(ID).parentNode.style.display = 'block';
            } else {
            ELMNT.className = 'NavSel';
            document.getElementById(ID).parentNode.style.display = 'none';
            }
            }
        </script>
        <?
    }
    if (isset($Error) && strlen(trim($Error)) > 0) {
        ?>
        <h1 id="HdrType2-5" class="EvntInfo2">
            <div>Error</div>
        </h1>
        <div id="RecordTable-5" class="White">
            <div id="Top"></div>
            <div id="Records">
                <p class="Error"><? echo $Error; ?></p>
            </div>
            <div id="Bottom"></div>
        </div>
        <br clear="all" />
    <? } ?>
    <h1 id="HdrType2-5" class="EvntInfo2">
        <div>Event Information</div>
    </h1>
    <div id="btnCollapse"><a href="#" onclick="javascript: Open_Sec('EventInfo', this);
                        return false;" onmouseover="window.status = 'Expand Event Information';
                        return true;" onmouseout="window.status = '';
                        return true;" title="Expand Event Information">+</a></div>
    <div id="HdrLinks"><?php if ($cont == 'add') { ?><a href="#" onclick="javascript: Save_Default_Info();
                            return false;" onmouseover="window.status = 'Save Default Settings';
                            return true;" onmouseout="window.status = '';
                            return true;" title="Save Default Settings" class="BtnSaveDefault">Save Defaults</a>
                                                        <?php } ?>
        <a href="#" onclick="javascript:<? echo $onclick; ?> return false;" onmouseover="window.status = 'Save Event Information';
                        return true;" onmouseout="window.status = '';
                        return true;" title="Save Event Information" class="BtnSave<?php if(implode(',',$path) == 'Evnt,Evnt' && $cont=='add') echo 'Upload'; ?>">Save</a></div>
    <div id="RecordTable-5" class="White"> <a id="EventInfo"></a>
        <div id="Top"></div>
        <div id="Records" class="Colmn3" style="padding-bottom: 15px;"> <span style="width:185px; padding-left:15px;">
                <label for="Event_Name" class="CstmFrmElmntLabel"> Event Name *</label>
                <input type="text" name="Event Name" id="Event_Name" value="<? echo $Name; ?>" onfocus="javascript:this.className = 'CstmFrmElmntInputNavSel';" onblur="javascript:this.className = 'CstmFrmElmntInput';" onmouseover="window.status = 'Event Name';
                        return true;" onmouseout="window.status = '';
                        return true;" title="Event Name" class="CstmFrmElmntInput" />
                <br />
                <label for="Owner" class="CstmFrmElmntLabel">Owner</label>
                <input type="text" name="Owner" id="Owner" onfocus="javascript:this.className = 'CstmFrmElmntInputNavSel';" onblur="javascript:this.className = 'CstmFrmElmntInput';" onmouseover="window.status = 'Owner';
                        return true;" onmouseout="window.status = '';
                        return true;" title="Owner" class="CstmFrmElmntInput" value="<? echo $OwnName; ?>" />
                <br />
                <label for="P1" class="CstmFrmElmntLabel">Owner Phone</label>
                <input name="P1" type="text" id="P1" size="6" class="CstmFrmElmntInputi29" onfocus="javascript:this.className = 'CstmFrmElmntInputi29NavSel';" onblur="javascript:this.className = 'CstmFrmElmntInputi29';" onmouseover="window.status = 'Owner\'s Phone Number: Area Code';
                        return true;" onmouseout="window.status = '';
                        return true;" title="Owner's Phone Number: Area Code" value="<? echo $P1; ?>" maxlength="3" onkeyup="AEV_set_tel_number('Phone_Number', 'P');" />
                <input name="P2" type="text" id="P2" size="6" class="CstmFrmElmntInputi29" onfocus="javascript:this.className = 'CstmFrmElmntInputi29NavSel';" onblur="javascript:this.className = 'CstmFrmElmntInputi29';" onmouseover="window.status = 'Owner\'s Phone Number: Prefix';
                        return true;" onmouseout="window.status = '';
                        return true;" title="Owner's Phone Number: Prefix" value="<? echo $P2; ?>" maxlength="3" onkeyup="AEV_set_tel_number('Phone_Number', 'P');" />
                <input name="P3" type="text" id="P3" size="8" class="CstmFrmElmntInputi34" onfocus="javascript:this.className = 'CstmFrmElmntInputi34NavSel';" onblur="javascript:this.className = 'CstmFrmElmntInputi34';" onmouseover="window.status = 'Owner\'s Phone Number: Suffix';
                        return true;" onmouseout="window.status = '';
                        return true;" title="Owner's Phone Number: Suffix" value="<? echo $P3; ?>" maxlength="4" onkeyup="AEV_set_tel_number('Phone_Number', 'P');" />
                <input type="hidden" name="Phone Number" id="Phone_Number" value="<? echo $Phone; ?>" />
                <br />
                <label for="Owner_Email" class="CstmFrmElmntLabel">Owner Email</label>
                <input type="text" name="Owner Email" id="Owner_Email" onfocus="javascript:this.className = 'CstmFrmElmntInputNavSel';" onblur="javascript:this.className = 'CstmFrmElmntInput';" onmouseover="window.status = 'Owner E-mail';
                        return true;" onmouseout="window.status = '';
                        return true;" title="Owner E-mail" class="CstmFrmElmntInput" value="<? echo $OwnEmail; ?>" />
            </span> <span style="width:245px; padding-left:15px;">
                <label for="Release_Month" class="CstmFrmElmntLabel"> Date of Event</label>
                <div style="float:left; clear:none;">
                    <select name="Start Event Month" id="Start_Event_Month" class="CstmFrmElmnt88" onmouseover="window.status = 'Month of Event';
                        return true;" onmouseout="window.status = '';
                        return true;" title="Month of Event">
                                <?
                                $TDate = date("m", mktime(0, 0, 1, substr($SDate, 5, 2), 1, date("Y")));
                                for ($n = 0; $n < 12; $n++) {
                                    $TDate2 = date("m", mktime(0, 0, 1, ($n + 1), 1, date("Y")));
                                    ?>
                            <option value="<? echo $TDate2; ?>" title="<? echo date("F", mktime(0, 0, 1, ($n + 1), 1, date("Y"))); ?>"<? if ($TDate == $TDate2) echo ' selected="selected"'; ?>><? echo date("F", mktime(0, 0, 1, ($n + 1), 1, date("Y"))); ?></option>
                        <? } ?>
                    </select>
                </div>
                <div style="float:left; clear:none; margin-left:3px; margin-right:3px;">
                    <select name="Start Event Day" id="Start_Event_Day" class="CstmFrmElmnt53" onmouseover="window.status = 'Day of Event';
                        return true;" onmouseout="window.status = '';
                        return true;" title="Day of Event">
                                <?
                                $TDate = date("d", mktime(0, 0, 1, 1, substr($SDate, 8, 2), date("Y")));
                                for ($n = 0; $n < 31; $n++) {
                                    $TDate2 = date("d", mktime(0, 0, 1, 1, ($n + 1), date("Y")));
                                    ?>
                            <option value="<? echo $TDate2; ?>" title="<? echo $TDate2; ?>"<? if ($TDate == $TDate2) echo ' selected="selected"'; ?>><? echo $TDate2; ?></option>
                        <? } ?>
                    </select>
                </div>
                <div style="float:left; clear:none;">
                    <select name="Start Event Year" id="Start_Event_Year" class="CstmFrmElmnt64" onmouseover="window.status = 'Year of Event';
                        return true;" onmouseout="window.status = '';
                        return true;" title="Year of Event">
                                <?
                                $TDate = date("Y", mktime(0, 0, 1, 1, 1, substr($SDate, 0, 4)));
                                for ($n = -2; $n < 5; $n++) {
                                    $TDate2 = date("Y", mktime(0, 0, 1, 1, 1, (date("Y") + $n)));
                                    ?>
                            <option value="<? echo $TDate2; ?>" title="<? echo $TDate2; ?>"<? if ($TDate == $TDate2) echo ' selected="selected"'; ?>><? echo $TDate2; ?></option>
                        <? } ?>
                    </select>
                </div>
                <div id="BtnCalendar"><a href="javascript:StartCalDate('ReleaseCalendar','Start_Event_Year','Start_Event_Month','Start_Event_Day',null);" onmouseover="window.status = 'Start Calendar';
                        return true;" onmouseout="window.status = '';
                        return true;" title="Start Calendar" id="ReleaseCalendar">Calendar</a></div>
                <br />
                <!--<label for="Event_Hour" class="CstmFrmElmntLabel">Time of Event</label>
                <input type="text" name="Event Hour" id="Event_Hour" class="CstmFrmElmntInputi29" onfocus="javascript:this.className = 'CstmFrmElmntInputi29NavSel';" onblur="javascript:this.className = 'CstmFrmElmntInputi29';" onmouseover="window.status = 'Event Hour';
                        return true;" onmouseout="window.status = '';
                        return true;" title="Event Hour" value="<? echo date("g", mktime(substr($SDate, 11, 2), 0, 1, 1, 1, date("Y"))); ?>" />
                <input type="text" name="Event Minute" id="Event_Minute" class="CstmFrmElmntInputi29" onfocus="javascript:this.className = 'CstmFrmElmntInputi29NavSel';" onblur="javascript:this.className = 'CstmFrmElmntInputi29';" onmouseover="window.status = 'Event Minute';
                        return true;" onmouseout="window.status = '';
                        return true;" title="Event Minute" value="<? echo date("i", mktime(1, substr($SDate, 14, 2), 1, 1, 1, date("Y"))); ?>" />
                <div style="float:left; clear:none;">
                    <input type="radio" name="Event_AMPM" id="Event_AMPM" value="am" class="CstmFrmElmntAM" title="Event Time AM" onmouseover="window.status = 'AM';
                        return true;" onmouseout="window.status = '';
                        return true;"<? if (date("A", mktime(substr($SDate, 11, 2), 0, 1, 1, 1, date("Y"))) == "AM") echo ' checked="checked"'; ?> />
                </div>
                <div style="float:left; clear:none;">
                    <input type="radio" name="Event_AMPM" id="Event_AMPM" value="pm" class="CstmFrmElmntPM" title="Event Time PM" onmouseover="window.status = 'PM';
                        return true;" onmouseout="window.status = '';
                        return true;"<? if (date("A", mktime(substr($SDate, 11, 2), 0, 1, 1, 1, date("Y"))) == "PM") echo ' checked="checked"'; ?> />
                </div>
                <br />-->
                <label for="Event_Month" class="CstmFrmElmntLabel">Event Release Date</label>
                <div style="float:left; clear:none;">
                    <select name="Event Month" id="Event_Month" class="CstmFrmElmnt88" onmouseover="window.status = 'Release Event Month';
                        return true;" onmouseout="window.status = '';
                        return true;" onchange="javascript:SetDates();" title="Release Event Month">
                                <?
                                $TDate = date("m", mktime(0, 0, 1, substr($Date, 5, 2), 1, date("Y")));
                                for ($n = 0; $n < 12; $n++) {
                                    $TDate2 = date("m", mktime(0, 0, 1, ($n + 1), 1, date("Y")));
                                    ?>
                            <option value="<? echo $TDate2; ?>" title="<? echo date("F", mktime(0, 0, 1, ($n + 1), 1, date("Y"))); ?>"<? if ($TDate == $TDate2) echo ' selected="selected"'; ?>><? echo date("F", mktime(0, 0, 1, ($n + 1), 1, date("Y"))); ?></option>
                        <? } ?>
                    </select>
                </div>
                <div style="float:left; clear:none; margin-left:3px; margin-right:3px;">
                    <select name="Event Day" id="Event_Day" class="CstmFrmElmnt53" onmouseover="window.status = 'Release Event Day';
                        return true;" onmouseout="window.status = '';
                        return true;" onchange="javascript:SetDates();" title="Release Event Day">
                                <?
                                $TDate = date("d", mktime(0, 0, 1, 1, substr($Date, 8, 2), date("Y")));
                                for ($n = 0; $n < 31; $n++) {
                                    $TDate2 = date("d", mktime(0, 0, 1, 1, ($n + 1), date("Y")));
                                    ?>
                            <option value="<? echo $TDate2; ?>" title="<? echo $TDate2; ?>"<? if ($TDate == $TDate2) echo ' selected="selected"'; ?>><? echo $TDate2; ?></option>
                        <? } ?>
                    </select>
                </div>
                <div style="float:left; clear:none;">
                    <select name="Event Year" id="Event_Year" class="CstmFrmElmnt64" onmouseover="window.status = 'Release Event Year';
                        return true;" onmouseout="window.status = '';
                        return true;" onchange="javascript:SetDates();" title="Release Event Year">
                                <?
                                $TDate = date("Y", mktime(0, 0, 1, 1, 1, substr($Date, 0, 4)));
                                for ($n = -2; $n < 5; $n++) {
                                    $TDate2 = date("Y", mktime(0, 0, 1, 1, 1, (date("Y") + $n)));
                                    ?>
                            <option value="<? echo $TDate2; ?>" title="<? echo $TDate2; ?>"<? if ($TDate == $TDate2) echo ' selected="selected"'; ?>><? echo $TDate2; ?></option>
                        <? } ?>
                    </select>
                </div>
                <div id="BtnCalendar"><a href="javascript:StartCalDate('EventCalendar','Event_Year','Event_Month','Event_Day',null);" onmouseover="window.status = 'Start Calendar';
                        return true;" onmouseout="window.status = '';
                        return true;" title="Start Calendar" id="EventCalendar">Calendar</a></div>
            </span> <span style="width:205px; padding-left:15px;">
                <label for="Photographer" class="CstmFrmElmntLabel">Photographer</label>
                <div style="float:left; clear:none;">
                    <select name="Photographer" id="Photographer" class="CstmFrmElmnt" title="Photographer">
                        <option value="0" title="Select">-- Select --</option>
                        <?
                        $getPhotos = new sql_processor($database_cp_connection, $cp_connection, $gateways_cp_connection);
                        $getPhotos->mysql("SELECT `photo_photographers`.`photo_id`, `photo_photographers`.`photo_lname`, `photo_photographers`.`photo_fname`, `cust_customers`.`cust_fname`, `cust_customers`.`cust_lname` FROM `photo_photographers`, `cust_customers` WHERE `photo_photographers`.`photo_use` = 'y' AND `cust_customers`.`cust_id` = '$CustId' AND `cust_customers`.`cust_id` = `photo_photographers`.`cust_id` ORDER BY `photo_lname` ASC, `photo_fname` ASC;");
                        foreach ($getPhotos->Rows() as $r) {
                            ?>
                            <option value="<? echo $r['photo_id']; ?>" title="<? echo $r['photo_lname'] . ", " . $r['photo_fname']; ?>"
                            <?
                            if( $Photo == '0' ){
                              if( $r['photo_lname'] == $r['cust_lname'] && $r['photo_fname'] == $r['cust_fname'] ){
                                echo' selected="selected"';
                              }
                            }
                            else{
                              if ($r['photo_id'] == $Photo){
                                echo' selected="selected"';
                              }
                            }
                            ?>><? echo $r['photo_lname'] . ", " . $r['photo_fname']; ?></option>
                        <? } ?>
                    </select>
                </div>
                <div id="BtnEdit2"><a href="javascript:PhotoInfo();" onmouseover="window.status = 'Add/Edit Photographer';
                        return true;" onmouseout="window.status = '';
                        return true;" title="Add/Edit Photographer">Edit Photographer</a></div>
                <br clear="all" />
                <label for="Event_Code" class="CstmFrmElmntLabel">Event Code *</label>
                <input type="text" name="Event Code" id="Event_Code" onfocus="javascript:this.className = 'CstmFrmElmntInputNavSel';" onblur="javascript:this.className = 'CstmFrmElmntInput';" onmouseover="window.status = 'Event Code';
                        return true;" onmouseout="window.status = '';
                        return true;" title="Event Code" class="CstmFrmElmntInput" value="<? echo $Code; ?>" />
                <br />
                <label for="Pricing_Group" class="CstmFrmElmntLabel">Price List</label>
                <div style="float:left; clear:none;">
                    <select name="Pricing Group" id="Pricing_Group" class="CstmFrmElmnt" title="Price List">
                        <option value="0" title="Select">-- Select --</option>
                        <?
                        $getUsrInfo = new sql_processor($database_cp_connection, $cp_connection, $gateways_cp_connection);
                        $getUsrInfo->mysql("SELECT `photo_event_price`.* 
                                            FROM `photo_event_price` 
                                            LEFT OUTER JOIN `photo_event_price_cust_del` 
                                              ON (`photo_event_price_cust_del`.`photo_event_price_id` = `photo_event_price`.`photo_event_price_id`
                                                 AND `photo_event_price_cust_del`.`cust_id` = '$CustId') 
                                            WHERE (`photo_event_price`.`cust_id` = '$CustId' 
                                              OR `photo_event_price`.`cust_id` = '0') 
                                              AND `photo_event_price`.`photo_price_use` = 'y' 
                                              AND `photo_event_price_cust_del`.`photo_event_price_id` IS NULL 
                                              ORDER BY `price_name` ASC;");

                        foreach ($getUsrInfo->Rows() as $v) {
                            ?>
                            <option value="<? echo $v['photo_event_price_id']; ?>" title="<? echo $v['price_name']; ?>"
                            <? 
                            if($Group == "0"){
                                if( $v['cust_id'] == "0" ){
                                    echo ' selected="selected"';
                                }
                            }
                            else{
                                if ($v['photo_event_price_id'] == $Group){
                                    echo ' selected="selected"';
                                }
                            }
                            ?>
                            ><? echo $v['price_name']; ?></option>
                        <? } ?>
                    </select>
                </div>
                <div id="BtnEdit2"><a href="javascript:PricInfo();" onmouseover="window.status = 'Add/Edit Price List';
                        return true;" onmouseout="window.status = '';
                        return true;" title="Add/Edit Price List">Edit Price List</a></div>
                <br clear="all" />
                <label for="Private_Public" class="CstmFrmElmntLabel">Accessibility</label>
                <? if ($Public == "n") { ?>
                    <sup id="Default"><strong>*&nbsp;</strong></sup>
                <? } ?>
                <div style="float:left; clear:none;">
                    <input type="radio" name="Public Event" id="Public_Event" value="n" class="CstmFrmElmntPvt" title="Private" onmouseover="window.status = 'Private';
                        return true;" onmouseout="window.status = '';
                        return true;"<? if ($Public == "n") echo ' checked="checked"'; ?> />
                </div>
                <? if ($Public == "y") { ?>
                    <sup id="Default2"><strong>*&nbsp;</strong></sup>
                <? } ?>
                <div style="float:left; clear:none;">
                    <input type="radio" name="Public Event" id="Public_Event" value="y" class="CstmFrmElmntPub" title="Public" onmouseover="window.status = 'Public';
                        return true;" onmouseout="window.status = '';
                        return true;"<? if ($Public == "y") echo ' checked="checked"'; ?> />
                </div>
            </span> <br clear="all" />
            <hr />
            <span style="width:185px; padding-left:15px;">
                <label for="Location" class="CstmFrmElmntLabel">Location of Event</label>
                <input type="text" name="Location" id="Location" onfocus="javascript:this.className = 'CstmFrmElmntInputNavSel';" onblur="javascript:this.className = 'CstmFrmElmntInput';" onmouseover="window.status = 'Location of Event';
                        return true;" onmouseout="window.status = '';
                        return true;" title="Location of Event" class="CstmFrmElmntInput" value="<? echo $Loc; ?>" />
            </span> <span style="width:245px; padding-left:15px;">
                <label for="City" class="CstmFrmElmntLabel" style="float:left; clear:none; width:130px;">City</label>
                <label for="State" class="CstmFrmElmntLabel" style="float:left; clear:none;">State</label><br clear="all" />

                <input name="City" type="text" id="City" value="<? echo $City; ?>" onfocus="javascript:this.className = 'CstmFrmElmntInputi117NavSel';" onblur="javascript:this.className = 'CstmFrmElmntInputi117';" onmouseover="window.status = 'City';
                        return true;" onmouseout="window.status = '';
                        return true;" title="City" class="CstmFrmElmntInputi117" />
                <span style="float:left; width:auto; clear:none; margin-right:5px;" id="State_Box">
                    <script type="text/javascript">AEV_GetState('<? echo $Country; ?>', '<? echo $State; ?>', '', '');</script>
                </span> </span> <span style="width:205px; padding-left:15px;">
                <label for="Country" class="CstmFrmElmntLabel">Country</label>
                <?
                $getCnty = new sql_processor($database_cp_connection, $cp_connection, $gateways_cp_connection);
                $getCnty->mysql("SELECT `country_short_3`, `country_name` FROM `a_country` WHERE `country_use` = 'y' ORDER BY `country_name` ASC;");
                ?>
                <select name="Country" id="Country" onchange="javascript:AEV_GetState(document.getElementById('Country').value, false, false, '');" class="CstmFrmElmnt" onmouseover="window.status = 'Country';
                        return true;" onmouseout="window.status = '';
                        return true;" title="Country">
                    <option value="0" title="Select Country"> -- Select Country -- </option>
                    <? foreach ($getCnty->Rows() as $r) { ?>
                        <option value="<? echo $r['country_short_3']; ?>"<? if ($Country == $r['country_short_3']) echo ' selected="selected"'; ?> title="<? echo $r['country_name']; ?>"><? echo $r['country_name']; ?></option>
                    <? } ?>
                </select>
            </span> <br clear="all" />
        </div>
        <div id="Bottom"></div>
    </div>
    <br clear="all" />
    <h1 id="HdrType2-5" class="EvntDurt">
        <div>Event Duration</div>
    </h1>
    <div id="btnCollapse"><a href="#" onclick="javascript: Open_Sec('EventDurt', this);
                        return false;" onmouseover="window.status = 'Expand Event Duration';
                        return true;" onmouseout="window.status = '';
                        return true;" title="Expand Event Duration">+</a></div>
    <div id="HdrLinks"><?php if ($cont == 'add') { ?><a href="#" onclick="javascript: Save_Default_Info();
                            return false;" onmouseover="window.status = 'Save Default Settings';
                            return true;" onmouseout="window.status = '';
                            return true;" title="Save Default Settings" class="BtnSaveDefault">Save Defaults</a>
                                                        <?php } ?>
        <a href="#" onclick="javascript:<? echo $onclick; ?> return false;" onmouseover="window.status = 'Save Event Information';
                        return true;" onmouseout="window.status = '';
                        return true;" title="Save Event Information" class="BtnSave<?php if(implode(',',$path) == 'Evnt,Evnt' && $cont=='add') echo 'Upload'; ?>">Save</a></div>
    <div id="RecordTable-5" class="White"> <a id="EventDurt"></a>
        <div id="Top"></div>
        <div id="Records" class="Colmn3"> <span style="width:185px; padding-left:15px;">
                <label for="Event_Duration" class="CstmFrmElmntLabel">Event Duration</label>
                <div style="float:left; clear:none;">
                    <? if (intval($Durtn) == 30) { ?>
                        <sup id="Default"><strong>*&nbsp;</strong></sup>
                    <? } ?>
                    <p>
                        <input type="radio" name="Event Duration" id="Event_Duration" value="30" class="CstmFrmElmnt" title="30 Days" onmouseover="window.status = '30 Days';
                        return true;" onmouseout="window.status = '';
                        return true;" onchange="javascript:SetDates();"<? if (intval($Durtn) == 30) echo ' checked="checked"'; ?> />
                        <font class="fontSpecial2">30 Days</font><br clear="all" />
                    </p>
                    <? if (intval($Durtn) == 60) { ?>
                        <sup id="Default"><strong>*&nbsp;</strong></sup>
                    <? } ?>
                    <p>
                        <input type="radio" name="Event Duration" id="Event_Duration" value="60" class="CstmFrmElmnt" title="60 Days" onmouseover="window.status = '60 Days';
                        return true;" onmouseout="window.status = '';
                        return true;" onchange="javascript:SetDates();"<? if (intval($Durtn) == 60) echo ' checked="checked"'; ?> />
                        <font class="fontSpecial2">60 Days</font><br clear="all" />
                    </p>
                    <? if (intval($Durtn) == 90) { ?>
                        <sup id="Default"><strong>*&nbsp;</strong></sup>
                    <? } ?>
                    <p>
                        <input type="radio" name="Event Duration" id="Event_Duration" value="90" class="CstmFrmElmnt" title="90 Days" onmouseover="window.status = '90 Days';
                        return true;" onmouseout="window.status = '';
                        return true;" onchange="javascript:SetDates();"<? if (intval($Durtn) == 90) echo ' checked="checked"'; ?> />
                        <font class="fontSpecial2">90 Days</font><br clear="all" />
                    </p>
                </div>
            </span><span style="width:185px; padding-left:15px;">
                <label for="Event_Duration" class="CstmFrmElmntLabel">Event Duration</label>
                <div style="float:left; clear:none;">
                    <? if (intval($Durtn) == 180) { ?>
                        <sup id="Default"><strong>*&nbsp;</strong></sup>
                    <? } ?>
                    <p>
                        <input type="radio" name="Event Duration" id="Event_Duration" value="180" class="CstmFrmElmnt" title="120 Days" onmouseover="window.status = '120 Days';
                        return true;" onmouseout="window.status = '';
                        return true;" onchange="javascript:SetDates();"<? if (intval($Durtn) == 180) echo ' checked="checked"'; ?> />
                        <font class="fontSpecial2">6 Months</font><br clear="all" />
                    </p>
                    <? if (intval($Durtn) == 270) { ?>
                        <sup id="Default"><strong>*&nbsp;</strong></sup>
                    <? } ?>
                    <p>
                        <input type="radio" name="Event Duration" id="Event_Duration" value="270" class="CstmFrmElmnt" title="30 Days" onmouseover="window.status = '30 Days';
                        return true;" onmouseout="window.status = '';
                        return true;" onchange="javascript:SetDates();"<? if (intval($Durtn) == 270) echo ' checked="checked"'; ?> />
                        <font class="fontSpecial2">9 Months</font><br clear="all" />
                    </p>
                    <? if (intval($Durtn) == 365) { ?>
                        <sup id="Default"><strong>*&nbsp;</strong></sup>
                    <? } ?>
                    <p>
                        <input type="radio" name="Event Duration" id="Event_Duration" value="365" class="CstmFrmElmnt" title="60 Days" onmouseover="window.status = '60 Days';
                        return true;" onmouseout="window.status = '';
                        return true;" onchange="javascript:SetDates();"<? if (intval($Durtn) == 365) echo ' checked="checked"'; ?> />
                        <font class="fontSpecial2">1 Year</font><br clear="all" />
                    </p>
                    <? if (intval($Durtn) == 0) { ?>
                        <sup id="Default"><strong>*&nbsp;</strong></sup>
                    <? } ?>
                    <p>
                        <input type="radio" name="Event Duration" id="Event_Duration" value="0" class="CstmFrmElmnt" title="Other" onmouseover="window.status = '0 Days';
                        return true;" onmouseout="window.status = '';
                        return true;" onchange="javascript:SetDates();"<? if (intval($Durtn) == 0) echo ' checked="checked"'; ?> />
                        <font class="fontSpecial2">Other</font><br clear="all" />
                    </p>
                </div>
            </span> <span style="width:245px; padding-left:15px;">
                <label for="Expiration_Month" class="CstmFrmElmntLabel">Event Expiration Date</label>
                <div style="float:left; clear:none;">
                    <select name="Expiration Month" id="Expiration_Month" class="CstmFrmElmnt88" onblur="javascript:setDateRel();" onmouseover="window.status = 'Event Expiration Month';
                        return true;" onmouseout="window.status = '';
                        return true;" title="Event Expiration Month">
                                <?
                                $TDate = date("m", mktime(0, 0, 1, substr($EDate, 5, 2), 1, date("Y")));
                                for ($n = 0; $n < 12; $n++) {
                                    $TDate2 = date("m", mktime(0, 0, 1, ($n + 1), 1, date("Y")));
                                    ?>
                            <option value="<? echo $TDate2; ?>" title="<? echo date("F", mktime(0, 0, 1, ($n + 1), 1, date("Y"))); ?>"<? if ($TDate == $TDate2) echo ' selected="selected"'; ?>><? echo date("F", mktime(0, 0, 1, ($n + 1), 1, date("Y"))); ?></option>
                        <? } ?>
                    </select>
                    <br />
                </div>
                <div style="float:left; clear:none; margin-left:3px; margin-right:3px;">
                    <select name="Expiration Day" id="Expiration_Day" class="CstmFrmElmnt53" onblur="javascript:setDateRel();"  onmouseover="window.status = 'Event Expiration Day';
                        return true;" onmouseout="window.status = '';
                        return true;" title="Event Expiration Day">
                                <?
                                $TDate = date("d", mktime(0, 0, 1, 1, substr($EDate, 8, 2), date("Y")));
                                for ($n = 0; $n < 31; $n++) {
                                    $TDate2 = date("d", mktime(0, 0, 1, 1, ($n + 1), date("Y")));
                                    ?>
                            <option value="<? echo $TDate2; ?>" title="<? echo $TDate2; ?>"<? if ($TDate == $TDate2) echo ' selected="selected"'; ?>><? echo $TDate2; ?></option>
                        <? } ?>
                    </select>
                </div>
                <div style="float:left; clear:none;">
                    <select name="Expiration Year" id="Expiration_Year" class="CstmFrmElmnt64" onblur="javascript:setDateRel();"  onmouseover="window.status = 'Event Expiration Year';
                        return true;" onmouseout="window.status = '';
                        return true;" title="Event Expiration Year">
                                <?
                                $TDate = date("Y", mktime(0, 0, 1, 1, 1, substr($EDate, 0, 4)));
                                for ($n = -2; $n < 5; $n++) {
                                    $TDate2 = date("Y", mktime(0, 0, 1, 1, 1, (date("Y") + $n)));
                                    ?>
                            <option value="<? echo $TDate2; ?>" title="<? echo $TDate2; ?>"<? if ($TDate == $TDate2) echo ' selected="selected"'; ?>><? echo $TDate2; ?></option>
                        <? } ?>
                    </select>
                </div>
                <div id="BtnCalendar"><a href="javascript:StartCalDate('ExpireCalendar','Expiration_Year','Expiration_Month','Expiration_Day','setDateRel');" onmouseover="window.status = 'Start Calendar';
                        return true;" onmouseout="window.status = '';
                        return true;" title="Start Calendar" id="ExpireCalendar">Calendar</a></div>
            </span>
            <!-- <span style="width:205px; padding-left:15px;">
            <label for="" class="CstmFrmElmntLabel">Notify Guestbook # days before end of event</label>
            <input type="text" name="Notify" id="Notify" onfocus="javascript:this.className='CstmFrmElmntInputNavSel';" onblur="javascript:this.className='CstmFrmElmntInput';" onmouseover="window.status='Notify Guestbook # days before end of event'; return true;" onmouseout="window.status=''; return true;" title="Notify Guestbook # days before end of event" class="CstmFrmElmntInput" value="<? echo $ENote; ?>" />
            </span> -->
            <br clear="all" />
            <br clear="all" />
        </div>
        <div id="Bottom"></div>
    </div>
    <br clear="all" />
    <h1 id="HdrType2-5" class="EvntShpng2">
        <div>Shipping Options</div>
    </h1>
    <div id="btnCollapse"><a href="#" onclick="javascript: Open_Sec('EventShip', this);
                        return false;" onmouseover="window.status = 'Expand Event Pick Up / Shipping Options';
                        return true;" onmouseout="window.status = '';
                        return true;" title="Expand Event Pick Up / Shipping Options">+</a></div>
    <div id="HdrLinks"><?php if ($cont == 'add') { ?><a href="#" onclick="javascript: Save_Default_Info();
                            return false;" onmouseover="window.status = 'Save Default Settings';
                            return true;" onmouseout="window.status = '';
                            return true;" title="Save Default Settings" class="BtnSaveDefault">Save Defaults</a>
                                                        <?php } ?>
        <a href="#" onclick="javascript:<? echo $onclick; ?> return false;" onmouseover="window.status = 'Save Event Information';
                        return true;" onmouseout="window.status = '';
                        return true;" title="Save Event Information" class="BtnSave<?php if(implode(',',$path) == 'Evnt,Evnt' && $cont=='add') echo 'Upload'; ?>">Save</a></div>
    <div id="RecordTable-5" class="White"> <a id="EventShip"></a>
        <div id="Top"></div>
        <div id="Records" class="Colmn2"> <span style="width:329px; padding-left:15px;">
                <? if ($ShipStud == 'n') { ?>
                    <sup id="Default"><strong>*&nbsp;</strong></sup>
                <? } ?>
                <p>
                    <input type="radio" name="ShipClient" id="ShipClient" value="n" class="CstmFrmElmnt" title="Ship all our orders directly to our clients" onmouseover="window.status = 'Ship all our orders directly to our clients';
                        return true;" onmouseout="window.status = '';
                        return true;"<? if ($ShipStud == 'n') echo ' checked="checked"'; ?> />
                    <font class="fontSpecial">Ship all our orders directly to our clients</font><br clear="all" />
                </p>
            </span><span style="width:329px; padding-left:15px;">
                <? if ($ShipStud == 'y') { ?>
                    <sup id="Default"><strong>*&nbsp;</strong></sup>
                <? } ?>
                <p>
                    <input type="radio" name="ShipClient" id="ShipClient" value="y" class="CstmFrmElmnt" title="Ship all our orders directly to our studio" onmouseover="window.status = 'Ship all our orders directly to our studio';
                        return true;" onmouseout="window.status = '';
                        return true;"<? if ($ShipStud == 'y') echo ' checked="checked"'; ?> />
                    <font class="fontSpecial">Ship all our orders directly to our studio</font><br clear="all" />
                </p>
                <br clear="all" />
            </span> <br clear="all" />
        </div>
        <div id="Bottom"></div>
    </div>
    <br clear="all" />
    <h1 id="HdrType2-5" class="EvntProcc2">
        <div>Order Processing Options</div>
    </h1>
    <div id="btnCollapse"><a href="#" onclick="javascript: Open_Sec('EventProc', this);
                        return false;" onmouseover="window.status = 'Expand Event Order Processing Options';
                        return true;" onmouseout="window.status = '';
                        return true;" title="Expand Event Order Processing Options">+</a></div>
    <div id="HdrLinks"><?php if ($cont == 'add') { ?><a href="#" onclick="javascript: Save_Default_Info();
                            return false;" onmouseover="window.status = 'Save Default Settings';
                            return true;" onmouseout="window.status = '';
                            return true;" title="Save Default Settings" class="BtnSaveDefault">Save Defaults</a>
                                                        <?php } ?>
        <a href="#" onclick="javascript:<? echo $onclick; ?> return false;" onmouseover="window.status = 'Save Event Information';
                        return true;" onmouseout="window.status = '';
                        return true;" title="Save Event Information" class="BtnSave<?php if(implode(',',$path) == 'Evnt,Evnt' && $cont=='add') echo 'Upload'; ?>">Save</a></div>
    <div id="RecordTable-5" class="White"> <a id="EventProc"></a>
        <div id="Top"></div>
        <div id="Records" class="Colmn2"><span style="width:329px; padding-left:15px;">
                <label class="CstmFrmElmntLabel">When orders are placed do you want them to 
                    automatically be sent to the lab?</label>
                <? if ($ToLab == 'y') { ?>
                    <sup id="Default"><strong>*&nbsp;</strong></sup>
                <? } ?>
                <p>
                    <input type="radio" name="SendLab" id="SendLab" value="y" class="CstmFrmElmnt" title="Send all orders for this event directly to the lab" onmouseover="window.status = 'Send all orders for this event directly to the lab';
                        return true;" onmouseout="window.status = '';
                        return true;"<? if ($ToLab == 'y') echo ' checked="checked"'; ?> />
                    <font class="fontSpecial">Please send orders directly to lab</font><br clear="all" />
                </p>
                <? if ($ToLab == 'n') { ?>
                    <sup id="Default"><strong>*&nbsp;</strong></sup>
                <? } ?>
                <p>
                    <input type="radio" name="SendLab" id="SendLab" value="n" class="CstmFrmElmnt" title="Approve all orders for this event before they are sent to the lab" onmouseover="window.status = 'Approve all orders for this event before they are sent to the lab';
                        return true;" onmouseout="window.status = '';
                        return true;"<? if ($ToLab == 'n') echo ' checked="checked"'; ?> />
                    <font class="fontSpecial">Approve orders before they are sent to the lab</font><br clear="all" />
                </p>
            </span><span style="width:329px; padding-left:15px;">
                <label class="CstmFrmElmntLabel">How would you like us to take care of 
                    printing of orders?<br />
                    &nbsp; </label>
                <? if ($ColorCrt == 'y') { ?>
                    <sup id="Default"><strong>*&nbsp;</strong></sup>
                <? } ?>
                <p>
                    <input type="radio" name="Correct" id="Correct" value="y" class="CstmFrmElmnt" title="I would like the lab to take care of color, density & contrast" onmouseover="window.status = 'I would like the lab to take care of color, density & contrast';
                        return true;" onmouseout="window.status = '';
                        return true;"<? if ($ColorCrt == 'y') echo ' checked="checked"'; ?> />
                    <font class="fontSpecial">I would like the lab to correct my images</font><br clear="all" />
                </p>
                <? if ($ColorCrt == 'n') { ?>
                    <sup id="Default"><strong>*&nbsp;</strong></sup>
                <? } ?>
                <p>
                    <input type="radio" name="Correct" id="Correct" value="n" class="CstmFrmElmnt" title="I correct all my own images and do not require any corrections" onmouseover="window.status = 'I will correct all my own images and do not require any corrections';
                        return true;" onmouseout="window.status = '';
                        return true;"<? if ($ColorCrt == 'n') echo ' checked="checked"'; ?> />
                    <font class="fontSpecial">I will correct all my own images</font><br clear="all" />
                </p>
                <br clear="all" />
            </span> <br clear="all" />
        </div>
        <div id="Bottom"></div>
    </div>
    <br clear="all" />
<? } ?>
