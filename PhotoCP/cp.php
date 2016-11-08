<?
$count = count(explode("/", substr($_SERVER['PHP_SELF'], 1))) - 2;
$r_path = "";
for ($n = 0; $n < $count; $n++)
    $r_path .= "../";
define("PhotoExpress Pro", true);
define('Allow Scripts', true);
include $r_path . 'security.php';

//if(!isset($_SESSION['JSEventInfo'])) session_register('JSEventInfo'); 
$_SESSION['JSEventInfo'] = true;

//require_once $r_path.'../scripts/cart/ssl_paths.php';
require_once($r_path . '../Connections/cp_connection.php');
require_once($r_path . 'scripts/fnct_sql_processor.php');
require_once($r_path . 'scripts/fnct_get_variable.php');
require_once($r_path . 'scripts/fnct_clean_entry.php');
require_once($r_path . 'scripts/fnct_format_date.php');
require_once($r_path . 'config.php');

if ($loginsession[1] >= 10 || (isset($_GET['admin']) && $_GET['admin'] == "true")) {
    require_once($r_path . 'includes/get_user_information.php');
}

require_once($r_path . 'includes/query_customers_account_disabled.php');
require_once($r_path . 'scripts/fnct_find_parents.php');
require_once($r_path . 'scripts/fnct_find_children.php');
$pathing = (isset($_POST['Path'])) ? explode(",", $_POST['Path']) : array("");

if (isset($_POST['form_path']) || isset($_POST['path'])) {
    $path = (isset($_POST['form_path'])) ? clean_variable($_POST['form_path'], true) : ((isset($_POST['path'])) ? clean_variable($_POST['path'], true) : "Orders,Open");
    $cont = (isset($_POST['form_cont'])) ? clean_variable($_POST['form_cont'], true) : ((isset($_POST['cont'])) ? clean_variable($_POST['cont'], true) : "query");
    $sort = (isset($_POST['form_sort'])) ? clean_variable($_POST['form_sort'], true) : ((isset($_POST['sort'])) ? clean_variable($_POST['sort'], true) : "");
    $rcrd = (isset($_POST['form_rcrd'])) ? clean_variable($_POST['form_rcrd'], true) : ((isset($_POST['rcrd'])) ? clean_variable($_POST['rcrd'], true) : "");
} else {
    $path = (isset($_GET['form_path'])) ? clean_variable($_GET['form_path'], true) : ((isset($_GET['path'])) ? clean_variable($_GET['path'], true) : "Orders,Open");
    $cont = (isset($_GET['form_cont'])) ? clean_variable($_GET['form_cont'], true) : ((isset($_GET['cont'])) ? clean_variable($_GET['cont'], true) : "query");
    $sort = (isset($_GET['form_sort'])) ? clean_variable($_GET['form_sort'], true) : ((isset($_GET['sort'])) ? clean_variable($_GET['sort'], true) : "");
    $rcrd = (isset($_GET['form_rcrd'])) ? clean_variable($_GET['form_rcrd'], true) : ((isset($_GET['rcrd'])) ? clean_variable($_GET['rcrd'], true) : "");
}
$path = explode(",", $path);

if( $isAccountDisabled == 1 ){
  if( $path[0] != "Pers" ){
    // echo "account disabled";
    $GoTo = "/".$AevNet_Path."/cp.php?path=Pers,Info&cont=view&sort=&rcrd=".$token;
    header(sprintf("Location: %s", $GoTo));
  }
}
else{
  if ($path[0] == "Busn" && ($path[1] == "Mrch" || $path[1] == "Past")) {
      if ($_SERVER['HTTPS'] != "on") {
          $GoTo = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING'];
          header(sprintf("Location: %s", $GoTo));
      }
  } else {
      if ($_SERVER['HTTPS'] == "on") {
          $GoTo = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING'];
          header(sprintf("Location: %s", $GoTo));
      }
  }
}

$header = $pathing[0];
switch ($header) {
    case "Events": $header = "Events";
        break;
}

$Notifications = array();
$Reads = array();
$getNotifications = new sql_processor($database_cp_connection, $cp_connection, $gateways_cp_connection);
$getNotifications->mysql("SELECT * 
    FROM notifications_notifications
    WHERE notification_id NOT IN (
        SELECT notification_id FROM notifications_user
        WHERE cust_id = $CustId
     ) AND notification_active = 1
     AND (notification_expire IS NULL 
        OR notification_expire = '0000-00-00'
        OR notification_expire > NOW()
     );");

foreach ($getNotifications->Rows() as $k => $r) {
    $Notifications[] = array(
        'id' => $r['notification_id'],
        'date' => date('m/d/Y', strtotime($r['notification_date'])),
        'name' => $r['notification_name'],
        'body' => $r['notification_body'],
    );
    $Reads[] = '(' . $r['notification_id'] . ',' . $CustId . ', NOW())';
}
if (count($Reads) > 0) {
    $getNotifications->mysql("INSERT INTO notifications_user VALUES " . implode(',', $Reads) . ";");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Photo Control Panel</title>
        <script type="text/javascript" src="/javascript/jquery-1.5.2.min.js"></script>
        <script type="text/javascript" src="/javascript/jquery.tablesorter.min.js"></script>
        <script type="text/javascript" src="/javascript/BrowserDetect.js"></script>
        <script type="text/javascript" src="/javascript/custom-form-elements.js"></script>
        <script type="text/javascript" src="/PhotoCP/javascript/standard_functions.js"></script>
        <link href="/PhotoCP/css/ControlPanel.css" rel="stylesheet" type="text/css" media="screen" />
        <link href="/PhotoCP/css/Print.css" rel="stylesheet" type="text/css" media="print" />
        <script type="text/javascript">
            if (is_ie && is_ie7lower)
                document.write('<link href="/PhotoCP/css/png.css" rel="stylesheet" type="text/css" media="screen" />');

<?php echo 'notifications = ' . json_encode($Notifications) . ';'; ?>
        </script>
        <script type="text/JavaScript">
            <!--
            var PrssGraph = '<p align="center"><img src="/PhotoCP/images/Processing.gif" alt="Processing" width="147" height="105" vspace="50" /> </p>';
            var SaveWarn = false;
            function set_warn(){ SaveWarn = true; }
            function set_form(Form, Path, Cont, Sort, Rcrd){
            if(SaveWarn) { alert('You did not save your information.'); return; }
            document.getElementById(Form+'path').value = Path;
            document.getElementById(Form+'cont').value = Cont;
            document.getElementById(Form+'sort').value = Sort;
            document.getElementById(Form+'rcrd').value = Rcrd;
            if(document.getElementById('From')) var Add = '&From='+document.getElementById('From').value+'&To='+document.getElementById('To').value;
            else var Add = '';
            //document.getElementById(Form+'action_form').submit();
            document.location.href = '<? echo $_SERVER['PHP_SELF']; ?>?path='+Path+'&cont='+Cont+'&sort='+Sort+'&rcrd='+Rcrd+Add+'<? echo $token; ?>';
            }
            function send_Msg(VAL,DIS,DOM,Fnct){ if(DOM != null) DOM = "'"+DOM+"'";
            VAL = '<p align="right"><a href="javascript:send_Msg(\'\',false,'+DOM+','+TArry+');">Close Window</a></p>'+VAL;
            if(Fnct != null){ var TArry = new Array();
            for(var n=0;n<Fnct.length;n++) TArry.push("'"+Fnct[n]+"'");	
            TArry = "Array("+TArry.join(",")+")";
            } else { var TArry = null; }
            VAL += '<p align="right"><a href="javascript:send_Msg(\'\',false,'+DOM+','+TArry+');">Close Window</a></p><br clear="all" />';
            document.getElementById('MessageContent').innerHTML = VAL;
            if(DIS == true){
            document.getElementById('MessageBackground').style.display = "block";
            document.getElementById('MessageWindow').style.display = "block";
            } else {
            document.getElementById('MessageBackground').style.display = "none";
            document.getElementById('MessageWindow').style.display = "none";
            if(Fnct != null){ var Params = new Array();
            if(Fnct.length>1){ for(var n=1; n<Fnct.length; n++) Params.push("'"+Fnct[n]+"'"); }
            if(DOM != null) eval('document.getElementById('+DOM+').contentWindow.'+Fnct[0]+"("+Params.join(",")+");"); 
            else eval(Fnct[0]+"("+Params.join(",")+");"); 
            } } }
            function HotMenu(STR,ACT){ var BTN = document.getElementById('BtnHotMenu'); 
            if(ACT == 1){ BTN.href = "javascript:HotMenu('"+STR+"',2,this);"; BTN.className='BtnHotMenuAdded'; }
            else { BTN.href = "javascript:HotMenu('"+STR+"',1,this);"; BTN.className='BtnHotMenu'; }
            xmlHotMenu=AEV_GetXmlHttpObject(); // Create a new Object
            if(xmlHotMenu==null) { alert ("Your browser does not support AJAX!"); return; }
            url = '/PhotoCP/xml/hotMenu.php?id=<? echo $CustId; ?>&btn='+escape(STR)+'&act='+ACT;
            xmlHotMenu.open('get',url); xmlHotMenu.onreadystatechange = HotMenuLoaded; xmlHotMenu.send('');
            }
            function HotMenuLoaded(){ // Rebuild Hot Menu
            var HTML = ''; if (xmlHotMenu.readyState==4){ var xmlDoc=xmlHotMenu.responseXML.documentElement; // Get XML information
            if(xmlDoc.hasChildNodes && xmlDoc.childNodes.length > 0){ for(var n = 0; n<xmlDoc.childNodes.length; n++){ var Node = xmlDoc.childNodes[n]; // For Each XML Node
            if(Node.nodeType==1) { var Act = Node.getAttribute("act").split(","); var Check = false;
            <? if (count($path) > 1) { ?>
                if('<? echo $path[0]; ?>'==Act[0] && '<? echo $path[1]; ?>'==Act[1] && '<? echo $cont; ?>'==Node.getAttribute("cont")) Check = true;
            <? } else { ?>
                if('<? echo $path[0]; ?>'==Act[0] && '<? echo $cont; ?>'==Node.getAttribute("cont")) Check = true;
            <? } ?>
            HTML += '<li'+((Check)?' class="TNavSel"':'')+'><a href="#" onclick="javascript:set_form(\'\',\''+Node.getAttribute("act")+'\',\''+Node.getAttribute("cont")+'\',\'\',\'\'); return false" onmouseover="window.status=\''+Node.getAttribute("alt")+'\'; return true;" onmouseout="window.status=\'\'; return true;" title="'+Node.getAttribute("alt")+'"><div>'+Node.getAttribute("name")+'</div></a></li>'; document.getElementById('NavHotMenu').innerHTML = HTML;
            } } } else document.getElementById('NavHotMenu').innerHTML = '<li id="HotNon">Your Hot Menu is empty</li>'; } }
            //-->
        </script>
    </head>
    <body>
        <div id="MessageBackground"></div>
        <div id="MessageWindow">
            <div id="MessageConainter">
                <div id="TL"></div>
                <div id="T"></div>
                <div id="TR"></div>
                <div id="MessageContent"></div>
                <div id="BL"></div>
                <div id="B"></div>
                <div id="BR"></div>
                <br clear="all" />
            </div>
        </div>
        <div id="Container">
            <div id="Navigation">
                <div id="Floral">
                    <div> <img src="/PhotoCP/images/Logo_print.jpg" alt="Pro Image Software. Capture and Grow" width="397" height="112" />
                        <h1 id="Logo"><a href="javascript:set_form('form_','','','');" onmouseover="javascript:window.status = 'Pro Image Software. Capture and Grow';
                return true;" onmouseout="javascript:window.status = '';
                return true;" title="Pro Image Software. Capture and Grow">Pro Image Software. Capture and Grow</a></h1>
                        <ul>
                            <li id="BtnHome"<? if (count($path) == 0) echo ' class="NavSel"'; ?>><a href="javascript:set_form('form_','','','');" onmouseover="javascript:window.status = 'Home';
                return true;" onmouseout="javascript:window.status = '';
                return true;" title="Home">Home</a></li>
                            <li id="BtnComm"<? if ($path[0] == "Comm") echo ' class="NavSel"'; ?>><a href="javascript:set_form('','Comm,Update','','');" onmouseover="javascript:window.status = 'Community and Resources';
                return true;" onmouseout="javascript:window.status = '';
                return true;" title="Community and Resources">Community and Resources</a></li>
                            <li id="BtnProd"<? if ($path[0] == "Prods") echo ' class="NavSel"'; ?>><a href="javascript:set_form('','Prods,Prods','','');" onmouseover="javascript:window.status = 'Products and Resources';
                return true;" onmouseout="javascript:window.status = '';
                return true;" title="Products and Resources">Products and Resources</a></li>
                            <li id="BtnSupp"<? if ($path[0] == "Contact") echo ' class="NavSel"'; ?>><a href="javascript:set_form('','Contact,Comment','','');" onmouseover="javascript:window.status = 'Support';
                return true;" onmouseout="javascript:window.status = '';
                return true;" title="Support">Support</a></li>
                            <li id="BtnLog"><a href="/PhotoCP/logout.php">Log Our</a></li>
                        </ul>
                        <br clear="all" />
                    </div>
                </div>
            </div>
            <div id="MainNav">
                <div id="Left"></div>
                <div id="Space">
                    <div id="PhotoIcn" style="background:url(/PhotoCP/images/photo_icn.jpg); cursor:pointer;" onclick="javascript:set_form('', 'Pers,Info', 'view', '<? echo $sort; ?>', '<? echo $rcrd; ?>');
                return false;">
                        <div></div>
                    </div>
                    <h4><? echo $CName; ?></h4>
                    <p><? echo $CHandle; ?></p>
                    <?
                    if ($DueDate != "0000-00-00 00:00:00" && $UsrPaid == 'n')
                        echo '<p style="color:#FF0000">Warning: Our records show that you missed a payment, and your account is currently suspended!</p>';
                    else {
                        ?>
                        <p>
                            <? if ($DueDate != "0000-00-00 00:00:00") { ?>
                                Account Due: <?
                                echo format_date($DueDate, 'Short', false, true, false);
                            } else {
                                echo "Free Account";
                            }
                            ?></p>
    <!-- <p>Sign Up Date: <? echo format_date($SignUpDate, 'Short', false, true, false); ?></p> -->
                        <p><? echo round(($MBUsed / 1024) * 100) / 100; ?>GB / <? echo round(($Quota / 1024) * 100) / 100; ?>GB</p>
                        <div id="StorageBar">
                            <div style="width:<? echo $PercUsed * 156; ?>px;"></div>
                        </div>
                    <? } ?>
                </div>
                <ul>
                    <li id="BtnEvntMngr"<? if ($path[0] == "Evnt") echo ' class="NavSel"'; ?>><a href="javascript:set_form('','Evnt','query','<? echo $sort; ?>','<? echo $rcrd; ?>');" onmouseover="window.status = 'Event Manager';
                return true;" onmouseout="window.status = '';
                return true;" title="Event Manager">Event Manager</a></li>
                    <li id="BtnClntMngr"<? if ($path[0] == "Clnt") echo ' class="NavSel"'; ?>><a href="javascript:set_form('','Clnt,Clnt','query','<? echo $sort; ?>','<? echo $rcrd; ?>');" onmouseover="window.status = 'Client Manager';
                return true;" onmouseout="window.status = '';
                return true;" title="Client Manager">Client Manager</a></li>
                    <li id="BtnBsnMngr"<? if ($path[0] == "Busn") echo ' class="NavSel"'; ?>><a href="javascript:set_form('','Busn,Open','query','<? echo $sort; ?>','<? echo $rcrd; ?>');" onmouseover="window.status = 'Business Manager';
                return true;" onmouseout="window.status = '';
                return true;" title="Business Manager">Business Manager</a></li>
                    <li id="BtnWebMngr"<? if ($path[0] == "Web") echo ' class="NavSel"'; ?>><a href="javascript:set_form('','Web,Home','query','<? echo $sort; ?>','<? echo $rcrd; ?>');" onmouseover="window.status = 'Website Manager';
                return true;" onmouseout="window.status = '';
                return true;" title="Website Manager">Website Manager</a></li>
                    <!-- <li id="BtnAlbDsnr"<? if ($path[0] == "Album") echo ' class="NavSel"'; ?>><a href="javascript:set_form('','Album,Album','query','<? echo $sort; ?>','<? echo $rcrd; ?>');" onmouseover="window.status='Album Designer'; return true;" onmouseout="window.status=''; return true;" title="Album Designer">Album Designer</a></li> -->
                </ul>
                <div id="Right"></div>
                <br clear="all" />
            </div>
            <div id="Content">
                <? include $r_path . 'includes/_navigation.php'; ?>
                <div<? if ($path[0] != "Prods") { ?> id="RightPanel"<? } ?>><br clear="all" />
                    <form action="<? echo $_SERVER['PHP_SELF'] . '?' . $QueryString . $token; ?>" name="form_action_form" id="form_action_form" method="post" enctype="multipart/form-data">
                        <?
                        require_once($r_path . 'includes/_photo_tree.php');
                        if (!defined('NoSave') && ($cont == "add" || $cont == "edit")) {
                            if (isset($required_string))
                                $onclick = '"MM_validateForm(' . $required_string . '); if(document.MM_returnValue == true){document.getElementById(\'Controller\').value = \'Save\'; this.disabled=true; this.form.submit();}"';
                            else
                                $onclick = '"document.getElementById(\'Controller\').value = \'Save\'; this.disabled=true; this.form.submit();"';
                            ?>
                            <div id="Save_Btn">
                                <input type="button" name="btnSave" id="btnSave" value="Save Information" alt="Save Information" onmouseup=<? echo $onclick; ?> />
                            </div>
                        <? } ?>
                        <input type="hidden" name="form_path" id="form_path" value="<? echo implode(",", $path); ?>" />
                        <input type="hidden" name="form_cont" id="form_cont" value="<? echo $cont; ?>" />
                        <input type="hidden" name="form_sort" id="form_sort" value="<? echo $sort; ?>" />
                        <input type="hidden" name="form_rcrd" id="form_rcrd" value="<? echo $rcrd; ?>" />
                        <input type="hidden" name="Time" id="Time" value="<? echo time(); ?>" />
                        <input type="hidden" name="Controller" id="Controller" value="false" />
                    </form>
                    <iframe id="HiddenForm" name="HiddenForm" style="display:none;"></iframe>
                </div>
                <br clear="all" />
            </div>
            <br clear="all" />
        </div>
        <?php
        if (defined('SAVEMESSAGE')) {
            echo '<script type="text/javascript">send_Msg(\'' . SAVEMESSAGE . '\', true, null, null);</script>';
        }
        ?>
        <script type="text/javascript">
            if (notifications.length > 0) {
                $buff = '<h1>A message from your Pro Image administrator</h1>';
                for (index in notifications) {
                    $buff += '<h2>' + notifications[index]['name'] + '</h2><sub>' + notifications[index]['date'] + '</sub><br />' + notifications[index]['body'];
                }
                send_Msg($buff, true, null, null);
            }
        </script>
    </body>
</html>