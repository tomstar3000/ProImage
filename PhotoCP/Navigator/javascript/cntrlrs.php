<?
session_start(); if(isset($_SESSION['JSNavigator']) && $_SESSION['JSNavigator']==true){ header("Content-type: text/javascript"); ob_start();
if(isset($_GET['data'])){
  $data = unserialize(base64_decode(urldecode($_GET['data'])));
  foreach($data as $k => $v) $$k = $v;
} else { die("Hacking Attempt;"); }
if(!isset($Value) || $Value != true) die("Hacking Attempt;");

$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-1; $r_path = ""; for($n=0;$n<$count;$n++)$r_path .= "../";
$r_path = "../";
require_once $r_path.'includes/cp_connection.php';
require_once $r_path.'scripts/encrypt.php';
require_once $r_path.'scripts/fnct_clean_entry.php';

header("Cache-Control: private");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Pragma: private"); 
header('Content-type: application/x-javascript'); ?>
<script type="text/javascript">
// JavaScript Document
var xmlHttp = null;
var XMLFold = '../xml'; // XML Folder
var XMLLoad = XMLFold+'/load_folders.php'; // Were to go to find the folders
var XMLNw_Fldr = XMLFold+'/new_folders.php'; // Were to go to create a folder
var XMLRnm_Fldr = XMLFold+'/rename_folders.php'; // Were to go to rename a folder
var XMLRmv_Fldr = XMLFold+'/remove_folders.php'; // Were to go to remove a folder
var XMLMv_Fldr = XMLFold+'/move_folders.php'; // Were to go to move a folder
var XMLImgLoad = XMLFold+'/load_images.php'; // Were to go to find the images
var XMLMvImg = XMLFold+'/move_images.php'; // Were to go to move the images
var XMLDelImg = XMLFold+'/delete_images.php'; // Were to go to delete the images 
var XMLEffImg = XMLFold+'/effect_images.php'; // Were to go to apply effects to images
var XMLTrshImg = XMLFold+'/trash_images.php'; // Were to go to trash the images
var XMLTrshEpy = XMLFold+'/trash_empty.php'; // Were to go to empty the trash
var XMLFavImg = XMLFold+'/favorite_images.php'; // Were to go to make an image a favorite
var XMLCvrImg = XMLFold+'/cover_images.php'; // Were to go to make an image a cover image
var PrssGraph = '<p align="center"><img src="/PhotoCP/images/Processing.gif" alt="Processing" width="147" height="105" vspace="50" /> </p>';
var ImgTrack = true; // Detection for when we roll over the subnavigation of an image
var Org_Fold_Sel = null; // What folder is currently selected
var Org_Fold_Over = null; // What folder we have the mouse over.
var Org_Fold_Def = 0; // The Default Folder
var Org_Img_Scroll = false;
var Chng_Fld_Nm = false;
var MrgnSpce=134;
var t = 0; var lDelay = 3; var lCount = 0; var pause = 100; // Pop Up Menu hide timer

// Mouse Position Detection
if (document.layers) { document.captureEvents(Event.MOUSEMOVE); document.onmousemove = captureMousePosition; /* Netscape */ } 
else if (document.all) document.onmousemove = captureMousePosition; /* Internet Explorer */ 
else if (document.getElementById) document.onmousemove = captureMousePosition; /* Netcsape 6 */ 
xMousePos = 0; yMousePos = 0; // Horizontal and Vertical position of the mouse on the screen
function captureMousePosition(e) { if (document.layers) { // Mouse positions in Netscape
    xMousePos = e.pageX; yMousePos = e.pageY;
  } else if (document.all) { // Mouse positions in IE
    xMousePos = window.event.x+document.body.scrollLeft; yMousePos = window.event.y+document.body.scrollTop;
  } else if (document.getElementById) { // Netscape 6 behaves the same as Netscape 4 in this regard 
    xMousePos = e.pageX; yMousePos = e.pageY; } }
function Org_Initiate(){ // Initiate the xmlHTTP gateway
  xmlHttp=GetXmlHttpObject(); // Create a new Object
  if (xmlHttp==null) { alert ("Your browser does not support AJAX!"); return; } // Alert people that their browswer doesn't support Ajax
}
function GetXmlHttpObject(){ // Set up our gateway
  try { Gateway=new ActiveXObject("Microsoft.XMLHTTP"); Gateway.async="false"; } // Internet Explorer
  catch (e) { try{ Gateway = new XMLHttpRequest(); } // Firefox, Opera 8.0+, Safari
              catch(e){ Gateway=new ActiveXObject('MSXML2.XMLHTTP.3.0');} } // Internet Explorer 5.5 and 6
  return Gateway; }
// Folder Menu controlls
function Org_PopMenuMove(MOVE, OBJ){ t = 2; lCount = 0; // Pause Timers and Stop the Counter
  var Pop = document.getElementById('FltMenuList'); var List = Pop.getElementsByTagName('li'); // Get Pop Menu List of Elements
  Pop.style.display="block"; /* Display the menu */
  if(MOVE == true){ // Check if we are moving the menu
    Org_Fold_Over = OBJ.parentNode; // Get our the folder that we are over at the moment
    if(Org_Fold_Over.value == 0){ var Name = Org_Fold_Over.getElementsByTagName('div')[0].innerHTML;
      if(Name.indexOf( "<? echo $EvntName; ?>") > -1 ){ var ACTArry = Array(false,true,false,false,false,false,false,true);
      } else if(Name.indexOf( "Trash" ) > -1 ){ var ACTArry = Array(true,false,false,false,false,false,true,false);
      } else if(Name.indexOf( "Ungrouped" ) > -1 ){ var ACTArry = Array(true,false,false,false,true,true,false,false);
      } else { var ACTArry = Array(true,true,true,false,false,false,false,false); }
    } else {  var ACTArry = Array(true,true,true,true,true,true,false,false); }
    for(var n = 0; n < ACTArry.length; n++){List[n].style.display = ((ACTArry[n]==true)?"block":"none"); }
    var offset_y = 0;
    if (parseInt(navigator.appVersion)>3) { if (navigator.appName=="Netscape") var offset_y = 0;
      else if (navigator.appName.indexOf("Microsoft")!=-1) var offset_y = document.documentElement.scrollTop; } // Set the offset position.
    Pop.style.top=(yMousePos+offset_y-15)+"px";
  } }
function Org_PopMenuHide(){
  if (t == 0){ document.getElementById('FltMenuList').style.display="none"; lCount = 0; return false; // If timer is over Hide the PopMenu
  } if (t == 2){ lCount = 0; return false; } // Reset the Counter
  if (t == 1){ lCount = lCount + 1; // Increment the Counter
    if (lDelay <= lCount) t = 0; if (lDelay >= lCount) setTimeout('Org_PopMenuHide(' + t + ')',pause); // Set Timer to recursively call function
  } }
function Org_PopMenu_Over(){ Org_Fold_Over.getElementsByTagName('a')[0].style.backgroundPosition = 'bottom left'; }
function Org_PopMenu_Out(){ if(Org_Fold_Over) Org_Fold_Over.getElementsByTagName('a')[0].removeAttribute('style'); }
function Org_PopMenu_Open(){ FldrOpen(Org_Fold_Over.getElementsByTagName('a')[0]); Org_Fold_Over = null; lCount=lDelay; t=0; Org_PopMenuHide(); }
function Org_PopMenu_Add(){ FldrOpen(Org_Fold_Over.getElementsByTagName('a')[0]); Org_Fold_Over = null; Org_Create_Folder(); lCount=lDelay; t=0; Org_PopMenuHide(); }
function Org_PopMenu_Rnme(){ FldrOpen(Org_Fold_Over.getElementsByTagName('a')[0]); Org_Fold_Over = null; Org_Rnm_Fldr(Org_Fold_Sel.getElementsByTagName('a')[0],true); lCount=lDelay; t=0; Org_PopMenuHide(); }
function Org_PopMenu_Rmv() { FldrOpen(Org_Fold_Over.getElementsByTagName('a')[0]); Org_Fold_Over = null; Org_Rmv_Fldr(); }
function Org_PopMenu_Upld(){ FldrOpen(Org_Fold_Over.getElementsByTagName('a')[0]); Org_Fold_Over = null; Org_Fldr_Upld(); }
function Org_PopMenu_Trsh(){ FldrOpen(Org_Fold_Over.getElementsByTagName('a')[0]); Org_Fold_Over = null; Org_Trsh_Imgs(); lCount=lDelay; t=0; Org_PopMenuHide(); }
function Org_PopMenu_Epty_Trsh(){ FldrOpen(Org_Fold_Over.getElementsByTagName('a')[0]); Org_Fold_Over = null; Org_Epty_Trsh(); lCount=lDelay; t=0; Org_PopMenuHide(); }
// ***************************************************************************************************************
// *********************************            Folder Functions             *************************************
// ***************************************************************************************************************
function TrckImg(VAL){ ImgTrack = VAL } // Set Image Tracker
// Open a folder
function FldrOpen(OBJ){ // Lets check to see if we have already selected this folder
  if(Chng_Fld_Nm == true) return; // Need to check if we are re-naming a folder
  SendMsg("Opening Folder "+Chng_Fld_Nm,true);
  if(Org_Fold_Sel != null && Org_Fold_Sel.getElementsByTagName('a')[0] && Org_Fold_Sel != OBJ.parentNode) Org_Fold_Sel.getElementsByTagName('a')[0].className = "";
  Org_Fold_Sel = OBJ.parentNode; // Set the selected folder
  if((Org_Fold_Sel.value == 0 || Org_Fold_Sel.value == "0") && (Org_Fold_Sel.parentNode.parentNode.value == "undefined" || Org_Fold_Sel.parentNode.parentNode.value == undefined)) return;
  if(Org_Fold_Sel.id == "C"){ Org_Fold_Sel.id = "O"; // Open or close the folder 
    OBJ = Org_Fold_Sel.getElementsByTagName('ul')[0]; OBJ.style.display='block'; }
  else if (Org_Fold_Sel.id == "O" && Org_Fold_Sel.getElementsByTagName('a')[0].className == "NavSel"){ Org_Fold_Sel.id = "C";
    OBJ = Org_Fold_Sel.getElementsByTagName('ul')[0]; OBJ.style.display='none'; }
  Org_Fold_Sel.getElementsByTagName('a')[0].className = "NavSel"; // Set the select state of the folder
  Org_Get_Images(); // Get Images for the selected folder.
}
// Set a default folder selection
function SelFolder(ID){ var LI = document.getElementById('FoldList').getElementsByTagName('li'); // Find a list of elements
  if(parseInt(ID) == 0){ FldrOpen(LI[0].getElementsByTagName('a')[0]); } else { // If ID is 0 select the first folder
  for(var n=0; n<LI.length; n++){ // Else lets find out folder
    if(parseInt(LI[n].value) == ID){ FldrOpen(LI[n].getElementsByTagName('a')[0]); OpenPrnt(LI[n]); break; } } }
}
// Open parent folder of our child folder
function OpenPrnt(OBJ){ // We store a value to the list element so lets check for that.
  if(OBJ.parentNode.parentNode != null && OBJ.parentNode.parentNode.value != "0" && OBJ.parentNode.parentNode.value != 0){
    OBJ.parentNode.parentNode.id = "O"; OpenPrnt(OBJ.parentNode.parentNode); // As long as we have an value open the folder
  } }
function Org_Load_Fold(ID){ // Load Folders
  SendMsg("Loading Folders",true); Org_Fold_Def = ID; // Set the Default Folder
  Org_Initiate(); if(xmlHttp != null){  var url = XMLLoad+"?data=<? echo base64_encode($EvntID); ?>&master=<? echo base64_encode($PID); ?>";
    xmlHttp.open('get', url); xmlHttp.onreadystatechange = Org_Build_Fold; xmlHttp.send(''); } }
function Org_Build_Fold(){ // Start building Folders
  if (xmlHttp.readyState==4){ var xmlDoc=xmlHttp.responseXML.documentElement; // Get XML information
    //SendMsg(xmlHttp.responseText); // Test the XML recieved
    var HTML = '<ul><li id="O" value="0"><a href="#"'
              +' onclick="javascript: FldrOpen(this); return false;"'
              +' onmousemove="javascript:Org_PopMenuMove(true,this);"'
              +' onmouseover="javascript:window.status=\'Open \'; Org_PopMenuMove(true,this); return true;"'
              +' onmouseout="javascript:window.status=\'\'; t = 1; Org_PopMenuHide(); return true;"'
              +' title="Open"'
              +'><div class="folder-title">('+xmlDoc.getAttribute("count")+') <? echo $EvntName; ?></div></a>';
        HTML += Org_Build_Item(xmlDoc,0)+'</ul>'; Org_Build_DropDown(xmlDoc);// Build Folder List SendMsg(HTML); // Test the HTML recieved
    document.getElementById('FoldList').innerHTML=HTML; SelFolder(Org_Fold_Def);
  } }
function Org_Move_Fold(){ // Move our selected folder to another folder
  var SelFoldId = Org_Fold_Sel.value; var MoveFoldId = document.getElementById('Move_Folder_to_Folder').value;
  if(parseInt(SelFoldId) == parseInt(MoveFoldId)) alert("Your selected groups are the same.");
  else if(xmlHttp != null){ Org_Fold_Def = SelFoldId; var url = XMLMv_Fldr+"?data=<? echo base64_encode($EvntID); ?>&master=<? echo base64_encode($PID); ?>&fid="+SelFoldId+"&nid="+MoveFoldId;
    xmlHttp.open('get',url); xmlHttp.onreadystatechange = Org_Fldr_Chng; xmlHttp.send(''); }
}
function Org_Build_Item(Nodes,z){ // Build Folder Items
  SendMsg("Building Folders",true);
  var HTML = ''; var a = 0; if(Nodes.hasChildNodes){ HTML += '<ul>'+"\r\n"; // Create Sub List and Hide it.
    for(var n = 0; n< Nodes.childNodes.length; n++){ // For each Node
      var Node = Nodes.childNodes[n]; var ClassName = ""; if(Node.nodeType==1) { a++; // Make sure we are not redering spaces.
        if(a%2) {} else ClassName = "T"; // Set Even or Odd
        if(Node.childNodes.length == 0) ListType = "N"; else ListType = "C"; // Set has Child Groups or Not
        ID = Node.getAttribute("id"); if(parseInt(ID)<=0) ID = 0; 
        if(parseInt(Org_Fold_Def) == 0 && parseInt(z) == 0 && parseInt(a) == 1) Org_Fold_Def = ID;
        HTML += '<li id="'+ListType+'"'+(ClassName!=""?' class="'+ClassName+'"':'')+' value="'+ID+'"><a href="#"'
              +' onclick="javascript: FldrOpen(this); return false;"'
              +((parseInt(ID)>0)?' ondblclick="javascript:Org_Rnm_Fldr(this,true); return true;"':'')
              +' onmousemove="javascript:Org_PopMenuMove(true,this);"'
              +' onmouseover="javascript:window.status=\'Open '+Node.getAttribute("name").replace("'","\\'")+'\'; Org_PopMenuMove(true,this); return true;"'
              +' onmouseout="javascript:window.status=\'\'; t = 1; Org_PopMenuHide(); return true;"'
              +' title="Open '+Node.getAttribute("name").replace("'","\\'")+'"'
              +'><div class="folder-title">('+Node.getAttribute("count")+') '+Node.getAttribute("name")+'</div></a>';
        if(parseInt(Node.getAttribute("id"))>0){ // Cannot rename the Trash and Ungrouped Folders
          HTML +='<input type="text" name="Fld_Nm" id="Fld_Nm" value="'+Node.getAttribute("name")+'"'
              +' onblur="javascript:Org_Rnm_Fldr(this,false);"'
              +' onkeyup="javascript:Org_Rnm_Key_Fldr(event,this);"'
              +' style="display:none;">';
          HTML += '<input type="hidden" name="Strd_Fld_Nm" id="Strd_Fld_Nm" value="'+Node.getAttribute("name")+'" />';} 
          if(Node.childNodes.length > 0) HTML += Org_Build_Item(Node,(z+1)); 
          else if(z<3) HTML += '<ul><li class="'+((a%2)?'':'T')+'" id="N" style="display:none"><input type="text" name="Fld_Nm" id="Fld_Nm"'
                            +' onblur="javascript:Org_Save_Nw_Fldr(this);"'
                            +' onkeyup="javascript:Org_Key_Nw_Fldr(event,this);"'
                            +' /></li>'+"\r\n"+'</ul>';
          HTML += '</li>';
      } } HTML += '<li class="'+((a%2)?'':'T')+'" id="N" style="display:none"><input type="text" name="Fld_Nm" id="Fld_Nm"'
                 +' onblur="javascript:Org_Save_Nw_Fldr(this);"'
                 +' onkeyup="javascript:Org_Key_Nw_Fldr(event,this);"'
                 +' /></li>'+"\r\n"+'</ul>'+"\r\n"; // Add New Folder Field
  } return HTML; // Return HTML
}
function Org_Create_Folder(){
  var Test1 = Org_Fold_Sel; var Test2 = Test1.parentNode.parentNode; var Test3 = Test2.parentNode.parentNode; var Test4 = Test3.parentNode.parentNode;
  var OBJ = Org_Fold_Sel;
  if((Test1.value == "0" || Test1.value == 0) && (Test2.value == "0" || Test2.value == 0)) return;
  SendMsg(Test1.value+" "+Test2.value+" "+Test3.value+" "+Test4.value,true);
  if(Test4.value == "0" || Test4.value == 0) Level=4; else if(Test3.value == "0" || Test3.value == 0) Level=3;
  else if(Test2.value == "0" || Test2.value == 0) Level=2; else if(Test1.value == "0" || Test1.value == 0) Level=1;
  else {OBJ = Org_Fold_Sel.parentNode.parentNode; FldrOpen(OBJ.getElementsByTagName('a')[0]); Level=4; }
  SendMsg("Creating new folder on level "+Level,true); if(OBJ.id=="C") OBJ.id = "O";
  if(OBJ.getElementsByTagName('ul').length > 0){
    OBJ = OBJ.getElementsByTagName('ul')[0]; OBJ.style.display='block';
    OBJ = OBJ.getElementsByTagName('li')[(OBJ.getElementsByTagName('li').length-1)]; OBJ.style.display='block';
    OBJ.getElementsByTagName('input')[0].focus(); }
}
function Org_Key_Nw_Fldr(e, OBJ){ // Capture Key Press
  var key=e.keyCode || e.which; if (key==13) Org_Save_Nw_Fldr(OBJ); // If Entery Key save new folder
}
function Org_Save_Nw_Fldr(OBJ){ // Save new Folder
  if(OBJ.value != "" && Chng_Fld_Nm == false) { Chng_Fld_Nm=true; Org_Initiate(); // Send to gateway
    if(xmlHttp != null){ var url = XMLNw_Fldr+"?data=<? echo base64_encode($EvntID); ?>&fid="+OBJ.parentNode.parentNode.parentNode.value+"&val="+escape(OBJ.value)+"&master=<? echo base64_encode($PID); ?>";
    xmlHttp.open('get',url); xmlHttp.onreadystatechange = Org_Fldr_Chng; xmlHttp.send(''); }
  } OBJ.parentNode.style.display="none"; // Hide Input field
}
function Org_Rnm_Fldr(OBJ,EDIT){ 
  if(EDIT == true){ OBJ.parentNode.getElementsByTagName('a')[0].style.display = 'none'; OBJ.parentNode.getElementsByTagName('input')[0].style.display = 'block';
    OBJ.parentNode.getElementsByTagName('input')[0].focus(); // Turn on the input field and set the focus.
    if(OBJ.parentNode.id != "N") {Org_Fold_Sel=null; OBJ.parentNode.id="C"; FldrOpen(OBJ); }// Function to display the edit folder name field
  } else { // Turn the edit field Off
    OBJ.parentNode.getElementsByTagName('a')[0].style.display = 'block'; OBJ.parentNode.getElementsByTagName('input')[0].style.display = 'none'; 
    if(Chng_Fld_Nm==false) Org_Save_Rnm_Fldr(OBJ); }
}
function Org_Rnm_Key_Fldr(e, OBJ){ // Capture Key Press
  var key=e.keyCode || e.which; if (key==13) Org_Rnm_Fldr(OBJ,false);  // If Enter Key save new folder name
}
function Org_Save_Rnm_Fldr(OBJ){ // Gateway to send the value of the renamed folder.
  Chng_Fld_Nm=true; var VAL = OBJ.parentNode.getElementsByTagName('input')[1].value; // Get the Current Stored Value
  if(OBJ.value != VAL) { Org_Initiate(); // Send to gateway
    SendMsg("Re-naming Folder",true); if(xmlHttp != null){
    var url = XMLRnm_Fldr+"?data=<? echo base64_encode($EvntID); ?>&fid="+OBJ.parentNode.value+"&val="+escape(OBJ.value)+"&master=<? echo base64_encode($PID); ?>";
    OBJ.parentNode.getElementsByTagName('a')[0].style.display = 'block'; OBJ.parentNode.getElementsByTagName('input')[0].style.display = 'none'; 
    xmlHttp.open('get',url); xmlHttp.onreadystatechange = Org_Fldr_Chng; xmlHttp.send(''); }
  } else { Chng_Fld_Nm=false; } 
}
function Org_Rmv_Fldr(){ // Removing Folder
  if(Org_Fold_Sel != null){ SendMsg("Removing Folder "+Org_Fold_Sel.value,true);
    Org_Initiate(); if(xmlHttp != null){ var url = XMLRmv_Fldr+"?data=<? echo base64_encode($EvntID); ?>&master=<? echo base64_encode($PID); ?>&fid="+Org_Fold_Sel.value;
    FldrOpen(Org_Fold_Sel.parentNode.parentNode.getElementsByTagName('a')[0]); xmlHttp.open('get', url); xmlHttp.onreadystatechange = Org_Fldr_Chng; xmlHttp.send(''); } } }
function Org_Epty_Trsh(){
  if(Org_Fold_Sel != null){ SendMsg("Empting Trash",true);
    Org_Initiate(); if(xmlHttp != null){ var url = XMLTrshEpy+"?data=<? echo base64_encode($EvntID); ?>&master=<? echo base64_encode($PID); ?>";
    xmlHttp.open('get', url); xmlHttp.onreadystatechange = Org_Imgs_Chngd; xmlHttp.send(''); }
  } }
function Org_Fldr_Chng(){ // After the folder has been re-named perform some tasks
  if (xmlHttp.readyState==4){ SendMsg(xmlHttp.responseText,true); // Test the XML recieved
    var xmlDoc=xmlHttp.responseXML.documentElement;
    var htmlDoc=document.getElementById('FoldList');
    var HTML = '<ul><li id="O" value="0"><a href="#"'
              +' onclick="javascript: FldrOpen(this); return false;"'
              +' onmouseover="javascript:window.status=\'Open \'; ShowMenu(true); return true;"'
              +' onmouseout="javascript:window.status=\'\'; ShowMenu(false); return true;"'
              +' title="Open"'
              +'><div class="folder-title">('+xmlDoc.getAttribute("count")+') <? echo $EvntName; ?></div></a>';
       HTML += Org_Build_Item(xmlDoc,0)+'</ul>'; Org_Build_DropDown(xmlDoc);// Get New Folder Structure and set New Drop Down List
    //Org_Build_DropDown(xmlDoc,0); 
    var tempDiv = document.createElement('div'); tempDiv.innerHTML = HTML; htmlNew = tempDiv; // Create Temparay Element for new folder structure
    var oldNodes = htmlDoc.getElementsByTagName('ul')[0].getElementsByTagName('li'); // Old List Elements
    var saveScroll = document.getElementById('FoldList').offsetTop; // Save Scroll Positions of Folder Structure
    var newNodes = htmlNew.getElementsByTagName('ul')[0].getElementsByTagName('li'); // New List Elements
    var a=0, b=0; for(var n=0; n<newNodes.length; n++){ // For each New Node
      if(oldNodes[a] && newNodes[(a+b)]){ var oldNode = oldNodes[a]; var newNode = newNodes[(a+b)]; var convert = true; // Set Offset of new and old elements
      var oldSel = oldNode.getElementsByTagName('a');
      if(oldNode.value != newNode.value) { b++; convert = false; } else a++; // If Ids don't equal set offset and not converted
      if(convert == true){ if(oldSel.length > 0){ // If Id exists start checking class names.
          var oldType = oldNode.id; var newType = newNode.id; // Old and New Type Open, Close, None
          oldSel = oldSel[0].className; // Get Old Selected Folder
          if(oldType == "O" && newNode.getElementsByTagName('ul').length > 0) newType = "O"; // Open Folders
          newNode.id = newType; // Set New Folder Tyle
          if(oldSel == "NavSel") newNode.getElementsByTagName('a')[0].className = "NavSel";  } } } // Set Old Selected Folder to New Folders
    } document.getElementById('FoldList').innerHTML=tempDiv.innerHTML; delete tempDiv; // Set HTML and delete temp HTML element
    document.getElementById('FoldList').scrollTop = saveScroll; // Set Old Scrolling
    var htmlDoc=document.getElementById('FoldList');
    var Nodes = htmlDoc.getElementsByTagName('ul')[0].getElementsByTagName('li'); // List Elements
    for(var n=0; n<Nodes.length; n++){ var Node = Nodes[n]; Alist = Node.getElementsByTagName('a');
      if(Alist.length > 0 && Alist[0].className == "NavSel"){ Org_Fold_Sel = Node; break; } } // Set Selected Folder
    Chng_Fld_Nm = false; if(parseInt(Org_Fold_Def) != 0) SelFolder(Org_Fold_Def);
  } }
function Org_Fldr_Upld(){ document.location.href='<? echo $r_path.'includes/uploader.php?data='.$_GET['data']; ?>&fid='+Org_Fold_Sel.value; }
// ***************************************************************************************************************
// ***************************             Move Image Select Functions             *******************************
// ***************************************************************************************************************
function Org_Build_DropDown(Nodes){ // Build MoveTo Drop Down
 var list = document.getElementById('Move_to_Folder');
  if(list){ var Val = list.options[list.selectedIndex].value;
    list.options.length=1; /* Reset Options */ Org_Build_Options(Nodes,0,Val,'Move_to_Folder'); // Build Options
  }
  var list = document.getElementById('Move_Folder_to_Folder');
  if(list){ var Val = list.options[list.selectedIndex].value;
    list.options.length=1; /* Reset Options */ list.options[0] = new Option("Main Group",0,false,false); Org_Build_Options(Nodes,0,Val,'Move_Folder_to_Folder'); // Build Options
  } }
function Org_Build_Options(Nodes,z,Val,ID){ // Build Drop Down List Options
  var a = 0; var list = document.getElementById(ID);
  if(Nodes.hasChildNodes){ // See if the Nodes have children
    for(var n = 0; n< Nodes.childNodes.length; n++){ var Node = Nodes.childNodes[n]; var ClassName = ""; // For each child
      if(Node.nodeType==1) { a++; var length = list.options.length; // Make sure we are not on a space and set variables
        var Value = Node.getAttribute("name"); var Dash = ""; var Space = ""; // Set Depth Levels
        for(var m = 0; m<z; m++){ Dash = "- "; Space += "--"}Value = Space+Dash+Value; //Set Value
        var Selected = (parseInt(Val)==parseInt(Node.getAttribute("id")))?true:false; // See if option is selected
        list.options[length]=new Option(Value,parseInt(Node.getAttribute("id")),false,Selected); // Create Selection
        if(Node.childNodes.length > 0) Org_Build_Options(Node,(z+1),Val,ID); // Find Child Nodes
      } } } }
// ***************************************************************************************************************
// *********************************             Image Functions             *************************************
// ***************************************************************************************************************
// Tracker for when we roll over and select an image.
function OverImg(ID,OVER,SEL){ var DIV = ID.getElementsByTagName('div')[0];
  // If we are tracking an image, no sub-navigation
  if(ImgTrack == true){ if(SEL == false){ // Check to see if the image is selected
    if(DIV.id == "S" || DIV.id == "SO"){ if(OVER == true) DIV.id = "SO"; else DIV.id = "S";
    } else { if(OVER == true) DIV.id = "O"; else DIV.id = "N"; }
  } else { if(DIV.id == "S" || DIV.id == "SO") DIV.id = "O"; else DIV.id = "SO"; }
} }
function Org_Sel_Images(VAL){ // Select an image on click
  var OBJ = document.getElementById('Images'); var DIV = OBJ.getElementsByTagName('div');
  for(var n = 0; n<DIV.length; n++){
    if(VAL == true){ if(DIV[n].id == "N") DIV[n].id = "S";
    } else { if(DIV[n].id == "S") DIV[n].id = "N"; }
  } }
function Org_Get_Images(){ // Load our images for our selected folder
  Org_Initiate(); var SrchVal = 0;
  switch(parseInt(Org_Fold_Sel.value)){
    case 0: if(Org_Fold_Sel.getElementsByTagName('a')[0].getElementsByTagName('div')[0].innerHTML.indexOf( "Trash" ) > -1 )SrchVal=-1; break;
    default: SrchVal = Org_Fold_Sel.value; break;
  } SendMsg("Loading Images ID: "+SrchVal,true);
  var url = XMLImgLoad+"?data="+SrchVal+"&master=<? echo base64_encode($PID); ?>&handle=<? echo base64_encode(urlencode($HNDL)); ?>&email=<? echo base64_encode(urlencode($EML)); ?>"; xmlHttp.open('get',url); xmlHttp.onreadystatechange = Org_BuildImage; xmlHttp.send('');
}
function Org_BuildImage(){ // Lets build our images
  var d = new Date(); var t = d.getTime(); if(Org_Img_Scroll == true) var saveScroll = document.getElementById('Images').scrollTop; // Save Scroll Positions of Folder Structure
  var HTML = ''; if (xmlHttp.readyState==4){ //SendMsg(xmlHttp.responseText,true); // Test the XML recieved
    SendMsg("Building Images",true); var xmlDoc=xmlHttp.responseXML.documentElement; var CoverID = 0;
    if(xmlDoc.hasChildNodes){ for(var n = 0; n< xmlDoc.childNodes.length; n++){ var Node = xmlDoc.childNodes[n]; // For Each XML Node
        if(Node.nodeType==1) { MRGN = Math.round((MrgnSpce-Node.getAttribute("height"))/2);
          if(Node.getAttribute("cover")=='y') CoverID = Node.getAttribute("id");
          HTML += '<div id="'+Node.getAttribute("id")+'" class="Image" onmouseover="javascript:OverImg(this,true,false);" onmouseout="javascript:OverImg(this,false,false);" onmousedown="javascript:OverImg(this,false,true);" title="'+Node.getAttribute("name")+'">'
               +'<div id="N"><p>'+Node.getAttribute("shortname")+'<ul id="Btns">'
              // +'<li class="BtnImgPub"><a href="#" onmousedown="javascript:TrckImg(false);" onmouseup="javascript:TrckImg(true);" title="Make this image public">Make Public</a></li>'
               +'<li class="BtnImgFav"><a href="javascript: Org_Fav_Img(\''+Node.getAttribute("id")+'\');" onmousedown="javascript:TrckImg(false);" onmouseup="javascript:TrckImg(true);" onmouseover="javascript:window.status=\'Make image: '+Node.getAttribute("name")+', a favorite\'; return true;" onmouseout="javascript:window.status=\'\'; return true;" title="Make image: '+Node.getAttribute("name")+', a favorite" '+((Node.getAttribute("fav")=='y')?' class="NavSel"':'')+' >Make Favorite</a></li>'
               +'<li class="BtnEvntCvr"><a href="javascript: Org_Cvr_Img(\''+Node.getAttribute("id")+'\');" onmousedown="javascript:TrckImg(false);" onmouseup="javascript:TrckImg(true);"  onmouseover="javascript:window.status=\'Make image: '+Node.getAttribute("name")+', the event cover\'; return true;" onmouseout="javascript:window.status=\'\'; return true;" title="Make image: '+Node.getAttribute("name")+', the event cover"'+((Node.getAttribute("cover")=='y')?' class="NavSel"':'')+'>Make Event Cover</a></li>'
               +'</ul><img src="'+Node.getAttribute("path").replace("&amp;","&")+'" width="'+Node.getAttribute("width")+'" height="'+Node.getAttribute("height")+'" style="margin-top:'+MRGN+'px; margin-bottom:'+MRGN+'px; -webkit-transition: 1s ease-in-out;-moz-transition: 1s ease-out; -o-transition: 1s ease-out; transition: 1s ease-out;" alt="'+Node.getAttribute("name")+'" />'
               +'<div id="Nav"><ul>'
               +'<li class="BtnImgRotateClck"><a href="javascript: Org_Rot_Img(\''+Node.getAttribute("id")+'\',\'90\'); rotate(\''+Node.getAttribute("id")+'\',\'90\');" onmousedown="javascript:TrckImg(false);" onmouseup="javascript:TrckImg(true);" title="Rotate this image Clockwise">Rotate Image Clockwise</a></li>'
               +'<li class="BtnImgRotateCntClck"><a href="javascript: Org_Rot_Img(\''+Node.getAttribute("id")+'\',\'-90\'); rotate(\''+Node.getAttribute("id")+'\',\'-90\');" onmousedown="javascript:TrckImg(false);" onmouseup="javascript:TrckImg(true);" title="Rotate this image Counter Clockwise">Rotate Image Counter Clockwise</a></li>'
               +'<li class="BtnImgRmv"><a href="javascript:Org_Del_Img('+Node.getAttribute("id")+',true);" onmousedown="javascript:TrckImg(false);" onmouseup="javascript:TrckImg(true);" onmouseover="javascript:window.status=\'Delete image: '+Node.getAttribute("name")+'\'; return true;" onmouseout="javascript:window.status=\'\'; return true;" onclick="return confirm(\'Are you sure you want to remove image '+Node.getAttribute("name")+'\');" title="Delete image: '+Node.getAttribute("name")+'">Delete Image</a></li>'
               +'<li class="BtnImgUpdt"><a href="javascript: Org_Rplc_Img(\''+Node.getAttribute("id")+'\',\''+Node.getAttribute("name")+'\');" onmousedown="javascript:TrckImg(false);" onmouseup="javascript:TrckImg(true);" title="Update / Replace this image">Update Image</a></li>'
               +'<li class="BtnImgView"><a href="javascript:Org_View_Lrg(\''+Node.getAttribute("path2")+'\',\''+Node.getAttribute("width2")+'\',\''+Node.getAttribute("height2")+'\',\''+Node.getAttribute("name")+'\');" onmousedown="javascript:TrckImg(false);" onmouseup="javascript:TrckImg(true);" onmouseover="javascript:window.status=\'View larger image: '+Node.getAttribute("name")+'\'; return true;" onmouseout="javascript:window.status=\'\'; return true;" title="View larger image: '+Node.getAttribute("name")+'\">View Larger</a></li>'
               +'</ul></div></p></div></div>'+"\r\n";
        } } } document.getElementById('Images').innerHTML=HTML; // Set Images HTML SendMsg(HTML);
        document.getElementById("CoverID").value = CoverID;
  if(Org_Img_Scroll == true){ document.getElementById('Images').scrollTop = saveScroll; // Set Old Scrolling
  Org_Img_Scroll = false; } else { document.getElementById('Images').scrollTop = 0; } } }
function Org_Move_Imgs(){ var fid = document.getElementById('Move_to_Folder').value; // Move our images to our selected folder
  if(fid != 0){ SendMsg("Moving Images to folder "+fid,true);
  var OBJ = document.getElementById('Images'); var DIV = OBJ.getElementsByTagName('div');
  var ImgArry = new Array(); // Let create an array of images that need to be moved
  for(var n = 0; n<DIV.length; n++){ if(DIV[n].id == "S"){ ImgArry.push(DIV[n].parentNode.id); }
  } if(ImgArry.length>0){ SendMsg("Moving Images "+ImgArry,true);
    ImgArry = serialize(ImgArry); Org_Initiate(); // Serialize the array for PHP
    if(xmlHttp != null) {var url = XMLMvImg+"?data="+escape(ImgArry)+"&master=<? echo base64_encode($PID); ?>&fid="+fid;
      xmlHttp.open('get',url); xmlHttp.onreadystatechange = Org_Imgs_Chngd; xmlHttp.send('');
  } } } }
function Org_Rot_Img(VAL,VAL2){ SendMsg("Rotate Image "+VAL,true); Org_Initiate(); // Lets set an image as the event cover
  var EffArray = new Array('Rotate.'+VAL2); var ImgArry = new Array(); ImgArry.push(VAL); ImgArry = serialize(ImgArry);
  if(xmlHttp != null) {var url = XMLEffImg+"?data="+escape(ImgArry)+"&master=<? echo base64_encode($PID); ?>&eff="+escape(serialize(EffArray));
    xmlHttp.open('get',url); xmlHttp.send('');
  } }
function Org_Eff_Imgs(EFF,VAL){ // Apply Effect to the selected images
  var OBJ = document.getElementById('Images'); var DIV = OBJ.getElementsByTagName('div'); // Create a list of images that need to be removed
  var EffArray = new Array(EFF+'.'+VAL);
  var ImgArry = new Array(); for(var n = 0; n<DIV.length; n++){ if(DIV[n].id == "S"){ ImgArry.push(DIV[n].parentNode.id); }
  } rotateAll( ImgArry, VAL );
  if(ImgArry.length>0){ SendMsg("Image Effect "+EffArray+" "+ImgArry,true);
  ImgArry = serialize(ImgArry); Org_Initiate(); // Serialize the array for PHP
  if(xmlHttp != null) {var url = XMLEffImg+"?data="+escape(ImgArry)+"&master=<? echo base64_encode($PID); ?>&eff="+escape(serialize(EffArray));
    xmlHttp.open('get',url); xmlHttp.send('');
  } } }
function Org_Del_Img(VAL,ACT){
  Remove_Selected_Fav( VAL );
  Remove_Selected_Cover_Image( VAL );
  var OBJ = document.getElementById('Images'); var DIV = OBJ.getElementsByTagName('div'); // Create a list of images that need to be removed
  for(var n = 0; n<DIV.length; n++){ if(DIV[n].id == VAL){ DIV[n].parentNode.removeChild(DIV[n]); break; } }
  if(ACT==true){ var ImgArry = new Array(); ImgArry.push(VAL); // Delete a single image
    SendMsg("Deleting Image "+ImgArry,true); ImgArry = serialize(ImgArry); Org_Initiate(); // Serialize the array for PHP
    if(xmlHttp != null) {var url = XMLDelImg+"?data="+escape(ImgArry)+"&master=<? echo base64_encode($PID); ?>";
      xmlHttp.open('get',url); xmlHttp.onreadystatechange = Org_Imgs_Chngd_No_Load; xmlHttp.send('');
    } } }
function Org_Del_Imgs(){ // Delete the selected images
  var OBJ = document.getElementById('Images'); var DIV = OBJ.getElementsByTagName('div'); // Create a list of images that need to be removed
  var ImgArry = new Array(); for(var n = 0; n<DIV.length; n++){ if(DIV[n].id == "S"){ ImgArry.push(DIV[n].parentNode.id); }
  } if(ImgArry.length>0){ SendMsg("Deleting Images "+ImgArry,true); for(var n=0; n<ImgArry.length; n++){ Org_Del_Img(ImgArry[n]); }
    ImgArry = serialize(ImgArry); Org_Initiate(); // Serialize the array for PHP
    if(xmlHttp != null) {var url = XMLDelImg+"?data="+escape(ImgArry)+"&master=<? echo base64_encode($PID); ?>";
      xmlHttp.open('get',url); xmlHttp.onreadystatechange = Org_Imgs_Chngd_No_Load; xmlHttp.send('');
  } } }
function Org_Trsh_Imgs(){Org_Initiate(); SendMsg("Trashing Images "+Org_Fold_Sel.value,true); // Let send all the images in a group to the Trash
  if(xmlHttp != null) {var url = XMLTrshImg+"?data="+Org_Fold_Sel.value+"&master=<? echo base64_encode($PID); ?>";
    xmlHttp.open('get',url); xmlHttp.onreadystatechange = Org_Imgs_Chngd; xmlHttp.send('');
  } }
function Org_Get_Btn(VAL,NME){ var BTN = null;
  for(var n=0; n<document.getElementById(VAL).getElementsByTagName('ul')[0].getElementsByTagName('li').length; n++){
    if(document.getElementById(VAL).getElementsByTagName('ul')[0].getElementsByTagName('li')[n].className == NME){
      var BTN = document.getElementById(VAL).getElementsByTagName('ul')[0].getElementsByTagName('li')[n]; break;
    } } return BTN; }
function Remove_Selected_Cover_Image(VAL){
  // console.log( document.getElementById("CoverID").value==VAL );
  SendMsg("Cover Image "+VAL,true); Org_Initiate(); // Lets set an image as the event cover
  if(document.getElementById("CoverID").value==VAL){
    /*if(document.getElementById("CoverID").value != '' && document.getElementById("CoverID").value != '0'){*/
    var BTN = Org_Get_Btn(document.getElementById("CoverID").value, "BtnEvntCvr"); 
    BTN.getElementsByTagName('a')[0].className="";
    // console.log( document.getElementById("CoverID").value );
    if(xmlHttp != null){
      var url = XMLCvrImg+"?data=<? echo base64_encode($EvntID); ?>&master=<? echo base64_encode($PID); ?>&fid=";
      xmlHttp.open('get',url); xmlHttp.onreadystatechange = Org_Imgs_Chngd_No_Load; xmlHttp.send('');
    }
  }
}
function Remove_Selected_Fav(VAL){
  var ImgArry = new Array(); 
  ImgArry.push(VAL); // Set a single image as a favorite
  var BTN = Org_Get_Btn(VAL,"BtnImgFav"); var ACT = "false";
  if(BTN.getElementsByTagName('a')[0].className=="NavSel"){
    // console.log( 'remove ' + VAL );
    SendMsg("Favorites Image "+ImgArry,true); ImgArry = serialize(ImgArry); Org_Initiate(); // Serialize the Array for PHP
    if(xmlHttp != null) {
      var url = XMLFavImg+"?data="+escape(ImgArry)+"&master=<? echo base64_encode($PID); ?>&handle=<? echo base64_encode(urlencode($HNDL)); ?>&email=<? echo base64_encode(urlencode($EML)); ?>&action="+ACT;
      xmlHttp.open('get',url); xmlHttp.onreadystatechange = Org_Imgs_Chngd_No_Load; xmlHttp.send('');
    }
  }
}
function Org_Fav_Img(VAL){ var ImgArry = new Array(); ImgArry.push(VAL); // Set a single image as a favorite
  var BTN = Org_Get_Btn(VAL,"BtnImgFav"); var ACT = "false";
  if(BTN.getElementsByTagName('a')[0].className=="NavSel"){ BTN.getElementsByTagName('a')[0].className=""; }
  else { BTN.getElementsByTagName('a')[0].className="NavSel"; ACT = "true"; }
  SendMsg("Favorites Image "+ImgArry,true); ImgArry = serialize(ImgArry); Org_Initiate(); // Serialize the Array for PHP
  if(xmlHttp != null) {var url = XMLFavImg+"?data="+escape(ImgArry)+"&master=<? echo base64_encode($PID); ?>&handle=<? echo base64_encode(urlencode($HNDL)); ?>&email=<? echo base64_encode(urlencode($EML)); ?>&action="+ACT;
    xmlHttp.open('get',url); xmlHttp.onreadystatechange = Org_Imgs_Chngd_No_Load; xmlHttp.send('');
  } }
function Org_Fav_Imgs(){ // Let make our selected images our event favorites
  var OBJ = document.getElementById('Images'); var DIV = OBJ.getElementsByTagName('div');
  var ImgArry = new Array(); for(var n = 0; n<DIV.length; n++){ if(DIV[n].id == "S"){ ImgArry.push(DIV[n].parentNode.id);
    var BTN = Org_Get_Btn(DIV[n].parentNode.id, "BtnImgFav"); BTN.getElementsByTagName('a')[0].className="NavSel"; } // Create a list of selected images
  } if(ImgArry.length>0){ SendMsg("Favorites Images "+ImgArry,true);
    ImgArry = serialize(ImgArry); Org_Initiate(); // Serialize the array for PHP
    if(xmlHttp != null) {var url = XMLFavImg+"?data="+escape(ImgArry)+"&master=<? echo base64_encode($PID); ?>&handle=<? echo base64_encode(urlencode($HNDL)); ?>&email=<? echo base64_encode(urlencode($EML)); ?>&action=true";
      xmlHttp.open('get',url); xmlHttp.onreadystatechange = Org_Imgs_Chngd_No_Load; xmlHttp.send('');
  } } }
function Org_Cvr_Img(VAL){  SendMsg("Cover Image "+VAL,true); Org_Initiate(); // Lets set an image as the event cover
  if(document.getElementById("CoverID").value!=VAL){
    if(document.getElementById("CoverID").value != '' && document.getElementById("CoverID").value != '0'){
      var BTN1 = Org_Get_Btn(document.getElementById("CoverID").value, "BtnEvntCvr"); BTN1.getElementsByTagName('a')[0].className=""; }
    var BTN2 = Org_Get_Btn(VAL, "BtnEvntCvr"); BTN2.getElementsByTagName('a')[0].className="NavSel"; }
  if(xmlHttp != null) {var url = XMLCvrImg+"?data=<? echo base64_encode($EvntID); ?>&master=<? echo base64_encode($PID); ?>&fid="+VAL;
    xmlHttp.open('get',url); xmlHttp.onreadystatechange = Org_Imgs_Chngd_No_Load; xmlHttp.send('');
  } }
function Org_Rplc_Img(ID,NME){
  HTML = '<h1>Upload new image for '+NME+'</h1>'
        +'<form method="post" enctype="multipart/form-data" name="UploadNewImageForm" id="UploadNewImageForm" action="<? echo $NavFolder; ?>/includes/update.php" target="HiddenForm">'
        +'<input type="file" name="Image" id="Image" /><br />'
        //+'<div id="BtnImgSbmt" onclick="javascript:document.getElementById(\'UploadNewImageForm\').submit();"><input type="submit" name="Submit" id="Submit" value="Submit" /></div>'
        +'<input type="submit" name="Submit" id="Submit" value="Submit" />'
        +'<input type="hidden" name="Image Id" id="Image_Id" value="'+ID+'" />'
        +'<input type="hidden" name="Controller" id="Controller" value="true" />'
        +'</form>';
  SendMsg("Upload New Image: "+ID+" "+NME+" "+HTML,true);
  parent.send_Msg(HTML,true,null,null); }
function Org_Rplc_Img_Done(){
  SendMsg("Image Updated",true); Org_Imgs_Chngd();
  parent.send_Msg('',false,null,null); }
function Org_View_Lrg(PTH,WDTH,HGHT,NME){ SendMsg("Loading Image: "+PTH+" "+WDTH+" x "+HGHT,true);
  if(HGHT>800){ PERC = 800/HGHT; WDTH = WDTH*PERC; HGHT = 800; }
  var HTML = '<p align="center"><img src="'+PTH+'" width="'+WDTH+'" height="'+HGHT+'" alt="'+NME+'" /></p>';
  parent.send_Msg(HTML,true,null,null); }
function Org_Imgs_Chngd(){ // Called once we recieve a successful return from the Ajax gateway
  if (xmlHttp.readyState==4){ Org_Img_Scroll = true; var OldScroll = document.getElementById('Images').scrollTop;
  SendMsg("Reloading Images",true); Org_Get_Images(); // Reload the images
  
  var SelFoldId = Org_Fold_Sel.value;
  Org_Load_Fold( SelFoldId ); // Reload folder counts

  /* document.getElementById('Images').scrollTop = OldScroll; */ } }
function Org_Imgs_Chngd_No_Load(){ // Called once we recieve a successful return from the Ajax gateway
  if (xmlHttp.readyState==4){
    SendMsg("Images Saved",true); 
    
    var SelFoldId = Org_Fold_Sel.value;
    Org_Load_Fold( SelFoldId ); // Reload folder counts
  }
}
function Org_Sel_Imgs_Chngd(){ if (xmlHttp.readyState==4){ SendMsg("Reloading Selected Images",true); // Refresh Images for selected images only
    var IMGS = document.getElementById('Images').getElementsByTagName('div'); var RE = new RegExp("t=[\\d\\w]*"); var Now=new Date(); // Get List of Images
    for(var n = 0; n < IMGS.length; n++){ if(IMGS[n].getElementsByTagName('div')){ var DIV = IMGS[n].getElementsByTagName('div'); // For each image
        if(DIV.length > 0 && (DIV[0].id=='S' || DIV[0].id=="SO")){ var IMG = DIV[0].getElementsByTagName('img'); // Make sure it has an image and it is selected
          // Need evals to send dynamic variables to function, so we don't step on our own toes.
          var NSrc = IMG[0].src.replace(RE,("t="+Now.getTime())); eval("var Img"+n+" = IMG[0];"); eval("var TImg"+n+" = new Image();"); 
          // create an onload function for our image and set the source
          eval("TImg"+n+".name = 'TImg'+n;"); eval("TImg"+n+".onload = function(){ Org_Sel_Img_Ld(TImg"+n+", Img"+n+"); };"); eval("TImg"+n+".src = NSrc;");
        } } } } }
function Org_Sel_Img_Chngd(ID){ if (xmlHttp.readyState==4){ SendMsg("Reloading Selected Images",true); // Refresh Images for selected images only
    var IMGS = document.getElementById('Images').getElementsByTagName('div'); var RE = new RegExp("t=[\\d\\w]*"); var Now=new Date(); // Get List of Images
    for(var n = 0; n < IMGS.length; n++){ if(IMGS[n].id == ID && IMGS[n].getElementsByTagName('div')){ var DIV = IMGS[n].getElementsByTagName('div'); // For each image
        if(DIV.length > 0 && DIV[0].getElementsByTagName('img')){ var IMG = DIV[0].getElementsByTagName('img'); // Make sure we have an image
          // Need evals to send dynamic variables to function, so we don't step on our own toes.
          var NSrc = IMG[0].src.replace(RE,("t="+Now.getTime())); var Img = IMG[0]; var TImg = new Image(); 
          // create an onload function for our image and set the source
          TImg.name = 'TImg'+n; TImg.onload = function(){ Org_Sel_Img_Ld(TImg, Img); }; TImg.src = NSrc;
        } } } } }
function Org_Sel_Img_Ld(TMP, IMG){ // Once the temp image has loaded lets refresh our selected image
  var W=TMP.width; var H=TMP.height; var MRGN = Math.round((MrgnSpce-H)/2);
  IMG.src=TMP.src; IMG.width=W; IMG.height=H; IMG.style.marginTop=IMG.style.marginBottom=MRGN+"px"; delete TMP; delete IMG; return true; }
function SendMsg(VAL,AMND){ return false; if(AMND == true) document.getElementById('Testing').value = VAL+'\r\n'+document.getElementById('Testing').value; else document.getElementById('Testing').value = VAL; }// Send Message to our testing textarea

function rotate( id, val ){
  var img = document.getElementById( id ).getElementsByTagName( 'img' )[0];
  var deg = img.getAttribute( 'rotation' ) | 0;

  // console.log( val );
  deg += parseInt( val, 10 );
  // console.log( deg );
  img.setAttribute( 'rotation', deg );
  img.style.webkitTransform = 'rotate('+deg+'deg)'; 
  img.style.mozTransform    = 'rotate('+deg+'deg)'; 
  img.style.msTransform     = 'rotate('+deg+'deg)'; 
  img.style.oTransform      = 'rotate('+deg+'deg)'; 
  img.style.transform       = 'rotate('+deg+'deg)';
}

function rotateAll( arr, val ){
  // console.log( arr );
  for( var i = 0; i < arr.length; ++i ){
    rotate( arr[i], val );
  }
}
</script>
<? $Buff = ob_get_contents(); ob_end_clean(); echo str_replace(array('<script type="text/javascript">','</script>'),'',$Buff); } $_SESSION['JSNavigator'] = false; ?>