// Aevium Standard Functions copyright Aevium 2007
function AEV_new_window(url,id,width,height,toolbar,scrollbar,location,statusbar,menubar,resizable,fullscreen){
	self.moveTo(0,0);
	self.resizeTo(screen.availWidth,screen.availHeight);

	var isWin=(navigator.appVersion.indexOf("Win")!=-1)? true : false;
	var isIE=(navigator.appVersion.indexOf("MSIE")!=-1)? true : false;
	var toolbar=(toolbar == true || toolbar == null)? "yes" : "no";
	var scrollbar=(scrollbar == true || scrollbar == null)? "yes" : "no";
	var location=(location == true || location == null)? "yes" : "no";
	var statusbar=(statusbar == true || statusbar == null)? "yes" : "no";
	var menubar=(menubar == true || menubar == null)? "yes" : "no";
	var resizable=(resizable == true || resizable == null)? "yes" : "no";
	var fullscreen=(fullscreen == false || fullscreen == null)? false : true;
	
	if(fullscreen == true){
		if(isWin&&isIE){
			var fullwindow = window.open(url,id,"toolbar="+toolbar+",scrollbars="+scrollbar+",location="+location+",statusbar="+statusbar+",menubar="+menubar+",resizable="+resizable+",fullscreen=yes");
			fullwindow.focus();
		} else {
			var fullwindow = window.open(url,id,"toolbar="+toolbar+",scrollbars="+scrollbar+",location="+location+",statusbar="+statusbar+",menubar="+menubar+",resizable="+resizable+",width="+screen.availWidth+",height="+screen.availHeight);
			fullwindow.moveTo(0,0);
			fullwindow.focus();
		}
	} else {
		var fullwindow = window.open(url,id,"toolbar="+toolbar+",scrollbars="+scrollbar+",location="+location+",statusbar="+statusbar+",menubar="+menubar+",resizable="+resizable+",width="+width+",height="+height);
		fullwindow.focus();
	}
}
function AEV_center_window(){
	if (typeof(window.innerHeight) == 'number') {
		win_height = window.innerHeight;
		win_width = window.innerWidth;
	}	else {
		if (document.documentElement && document.documentElement.clientHeight) {
			win_height = document.documentElement.clientHeight;
			win_width = document.documentElement.clientWidth;
		}
		else {
			if (document.body && document.body.clientHeight) {
				win_height = document.body.clientHeight;
				win_width = document.body.clientWidth;
			}
		}
	}
	x=(screen.width/2)-(win_width/2)-8;
	y=(screen.height/2)-(win_height/2)-8;
	window.moveTo(x,y);	
}
function AEV_size_window(width,height){
	window.resizeTo(width,height);
}
function AEV_format_currency(amount){
	var i = parseFloat(amount);
	if(isNaN(i)) { i = 0.00; }
	var minus = '';
	if(i < 0) { minus = '-'; }
	i = Math.abs(i);
	i = parseInt((i + .005) * 100);
	i = i / 100;
	s = new String(i);
	if(s.indexOf('.') < 0) { s += '.00'; }
	if(s.indexOf('.') == (s.length - 2)) { s += '0'; }
	s = minus + s;
	return s;
}
function AEV_set_tel_number(field,code_val){
	document.getElementById(field).value = document.getElementById(code_val+'1').value+document.getElementById(code_val+'2').value+document.getElementById(code_val+'3').value;
}
function AEV_move_tel_number(field, code_val){
	if(document.getElementById(field+code_val).value.length == 3 && code_val < 3){
		document.getElementById(field+(parseInt(code_val)+1)).focus();
	}
}
<!-- AJAX State List -->
var AEV_XMLSPList = '/XML/SPList.php';
var AEV_State_Tab = 0; var AEV_State = '';
function AEV_Org_Initiate(){ // Initiate the xmlHTTP gateway
	xmlHttp=AEV_GetXmlHttpObject(); // Create a new Object
	if (xmlHttp==null) { alert ("Your browser does not support AJAX!"); return; } // Alert people that their browswer doesn't support Ajax
}
function AEV_GetState(ID,STATE,TAB,PREFIX){
	if(TAB != "false" && TAB != false) AEV_State_Tab = TAB; if(STATE != "false" && STATE != false) { AEV_State = STATE;
	} else if(document.getElementById(PREFIX+'State')) {
	if(document.getElementById(PREFIX+'State').getElementsByTagName('select').length > 0){
		var Index = document.getElementById(PREFIX+'State').selectedIndex;
		AEV_State = document.getElementById(PREFIX+'State')[Index].value;
	} else AEV_State = document.getElementById(PREFIX+'State').value; }
AEV_Org_Initiate(); // Create a new Object
	var url = AEV_XMLSPList+"?ID="+ID; // Set URL
	xmlHttp.open('get',url); xmlHttp.onreadystatechange = AEV_GetStateLoaded; xmlHttp.send(''); }
function AEV_GetStateLoaded(){ // Start building Folders
	if (xmlHttp.readyState==4){ var xmlDoc=xmlHttp.responseXML.documentElement; // Get XML information
	if(xmlDoc.hasChildNodes && xmlDoc.childNodes.length > 0){
		HTML = '<select name="State" id="State" tabindex="'+AEV_State_Tab+'">';
		HTML += '<option value=""'+((AEV_State=='')?' selected="selected"':'')+'>Select</option>';
		for(var n = 0; n<xmlDoc.childNodes.length; n++){ var Node = xmlDoc.childNodes[n]; // For Each XML Node
			if(Node.nodeType==1) { HTML += '<option value="'+Node.childNodes[0].nodeValue+'"'+((AEV_State==Node.childNodes[0].nodeValue)?' selected="selected"':'')+'>'+Node.childNodes[0].nodeValue+'</option>';
			} } HTML += '</select>';
	} else HTML = '<input name="State" type="text" id="State" tabindex="'+AEV_State_Tab+'" value="'+AEV_State+'" size="3" style="width:110px;" />';
	document.getElementById('State_Box').innerHTML = HTML;} }
function AEV_GetXmlHttpObject(){
	try { Gateway=new ActiveXObject("Microsoft.XMLHTTP"); Gateway.async="false"; } // Internet Explorer
	catch (e) { try{ Gateway = new XMLHttpRequest(); } // Firefox, Opera 8.0+, Safari
							catch(e){ Gateway=new ActiveXObject('MSXML2.XMLHTTP.3.0');} } // Internet Explorer 5.5 and 6
	return Gateway; }
<!-- END AJAX State List -->
function AEV_change_state(prefix,value){
	if(value == "USA"){
		document.getElementById(prefix+'State_Text').style.display='none';
		document.getElementById(prefix+'State_US').style.display='block';
		document.getElementById(prefix+'State_CAN').style.display='none';
		for(var n=0; n<document.getElementById(prefix+'State_US').getElementsByTagName('select')[0].options.length; n++){
			if(document.getElementById(prefix+'State_US').getElementsByTagName('select')[0].options[n].value
				 == document.getElementById(prefix+'State_Text').getElementsByTagName('input')[0].value){
				document.getElementById(prefix+'State_US').getElementsByTagName('select')[0].options[n].selected = true; 
				break;
			}
		}
		document.getElementById(prefix+'State_Text').getElementsByTagName('input')[0].disabled=true;
		document.getElementById(prefix+'State_US').getElementsByTagName('select')[0].disabled=false;
		document.getElementById(prefix+'State_CAN').getElementsByTagName('select')[0].disabled=true;
	} else if(value == "CAN"){
		document.getElementById(prefix+'State_Text').style.display='none';
		document.getElementById(prefix+'State_US').style.display='none';
		document.getElementById(prefix+'State_CAN').style.display='block';
		for(var n=0; n<document.getElementById(prefix+'State_CAN').getElementsByTagName('select')[0].options.length; n++){
			if(document.getElementById(prefix+'State_CAN').getElementsByTagName('select')[0].options[n].value
				 == document.getElementById(prefix+'State_Text').getElementsByTagName('input')[0].value){
				document.getElementById(prefix+'State_CAN').getElementsByTagName('select')[0].options[n].selected = true; 
				break;
			}
		}
		document.getElementById(prefix+'State_Text').getElementsByTagName('input')[0].disabled=true;
		document.getElementById(prefix+'State_US').getElementsByTagName('select')[0].disabled=true;
		document.getElementById(prefix+'State_CAN').getElementsByTagName('select')[0].disabled=false;
	} else {
		document.getElementById(prefix+'State_Text').style.display='block';
		document.getElementById(prefix+'State_US').style.display='none';
		document.getElementById(prefix+'State_CAN').style.display='none';
		document.getElementById(prefix+'State_Text').getElementsByTagName('input')[0].disabled=false;
		document.getElementById(prefix+'State_US').getElementsByTagName('select')[0].disabled=true;
		document.getElementById(prefix+'State_CAN').getElementsByTagName('select')[0].disabled=true;
	}
}
function AEV_same_bill(){
	if(document.getElementById('Same_as_Billing').checked){
		document.getElementById('Shipping_First_Name').value = document.getElementById('Billing_First_Name').value;
		document.getElementById('Shipping_Last_Name').value = document.getElementById('Billing_Last_Name').value;
		document.getElementById('Shipping_Address').value = document.getElementById('Billing_Address').value;
		document.getElementById('Shipping_City').value = document.getElementById('Billing_City').value;
		document.getElementById('Shipping_State').value = document.getElementById('Billing_State').value;
		document.getElementById('Shipping_Zip').value = document.getElementById('Billing_Zip').value;
		document.getElementById('Shipping_First_Name').disabled = true;
		document.getElementById('Shipping_Last_Name').disabled = true;
		document.getElementById('Shipping_Address').disabled = true;
		document.getElementById('Shipping_City').disabled = true;
		document.getElementById('Shipping_State').disabled = true;
		document.getElementById('Shipping_Zip').disabled = true;
		if(document.getElementById('Billing_SuiteApt')){
			document.getElementById('Shipping_SuiteApt').value = document.getElementById('Billing_SuiteApt').value;
			document.getElementById('Shipping_SuiteApt').disabled = true;
		}
		if(document.getElementById('Shipping_Address_2')){
			document.getElementById('Shipping_Address_2').value = document.getElementById('Billing_Address_2').value;
			document.getElementById('Shipping_Address_2').disabled = true;
		}
		if(document.getElementById('Billing_Country')){
			document.getElementById('Shipping_Country').value = document.getElementById('Billing_Country').value;
			document.getElementById('Shipping_Country').disabled = true;
		}
	} else {
		document.getElementById('Shipping_First_Name').disabled = false;
		document.getElementById('Shipping_Last_Name').disabled = false;
		document.getElementById('Shipping_Address').disabled = false;
		document.getElementById('Shipping_City').disabled = false;
		document.getElementById('Shipping_State').disabled = false;
		document.getElementById('Shipping_Zip').disabled = false;	
		if(document.getElementById('Billing_SuiteApt')){
			document.getElementById('Shipping_SuiteApt').disabled = false;
		}
		if(document.getElementById('Shipping_Address_2')){
			document.getElementById('Shipping_Address_2').disabled = false;
		}
		if(document.getElementById('Billing_Country')){
			document.getElementById('Shipping_Country').disabled = false;
		}
	}
}
// Macromedia Standard Functions
function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}

function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}

function MM_validateForm() { //v5.5  // Amended by Chad Serpan, development@proimagesoftware.com
	// Added Checkbox and Radio capability and ability to pass alternative form names.
  var i,p,q,nm,test,num,min,max,errors='',args=MM_validateForm.arguments;
  for (i=0; i<(args.length-2); i+=3) { test=args[i+2]; val=MM_findObj(args[i]);	subName = args[i+1];
	if(subName.length != 0) nm = subName;	
	if (val) {if(val[0] && (val[0].type=="radio" || val[0].type=="checkbox")){ 
	ck = false;	for(z=0;z<val.length; z++){	nm=val[z].name;	
	if(subName.length != 0)nm = subName; if(val[z].checked==true)ck = true;}
	if(ck==false)errors+='- '+nm+' is required.\n';	} else { nm=val.name;	
	if(subName.length != 0)nm = subName; if(val.type=="radio" || val.type=="checkbox") ck=val.checked;
	if(test.indexOf('isSelect')!=-1) ck=val; if ((val=val.value)!="") {
	if(test.indexOf('isCheck')!=-1){ if (ck == false) errors+='- '+nm+' is required.\n';
	} else if (test.indexOf('isSelect')!=-1) { if(ck.selectedIndex <= 0) errors+='- '+nm+' is required.\n';
	} else if (test.indexOf('isEmail')!=-1) { p=val.indexOf('@'); q=val.lastIndexOf('.');
	if (p<1 || p==(val.length-1) || (q<1 || q==(val.length-1) || q<p)) errors+='- '+nm+' must contain an e-mail address.\n';
	} else if (test!='R') { num = parseFloat(val); if (isNaN(val)) errors+='- '+nm+' must contain a number.\n';
	if (test.indexOf('inRange') != -1) { p=test.indexOf(':');	min=test.substring(8,p); max=test.substring(p+1);
	if (num<min || max<num) errors+='- '+nm+' must contain a number between '+min+' and '+max+'.\n'; } }
	} else if (test.charAt(0) == 'R') errors += '- '+nm+' is required.\n'; } } } 
	if (errors) alert('The following error(s) occurred:\n'+errors);
  document.MM_returnValue = (errors == '');
}
function serialize( mixed_value ) {
    // http://kevin.vanzonneveld.net
    // +   original by: Arpad Ray (mailto:arpad@php.net)
    // +   improved by: Dino
    // %          note: We feel the main purpose of this function should be to ease the transport of data between php & js
    // %          note: Aiming for PHP-compatibility, we have to translate objects to arrays
    // *     example 1: serialize(['Kevin', 'van', 'Zonneveld']);
    // *     returns 1: 'a:3:{i:0;s:5:"Kevin";i:1;s:3:"van";i:2;s:9:"Zonneveld";}'
    // *     example 2: serialize({firstName: 'Kevin', midName: 'van', surName: 'Zonneveld'});
    // *     returns 2: 'a:3:{s:9:"firstName";s:5:"Kevin";s:7:"midName";s:3:"van";s:7:"surName";s:9:"Zonneveld";}'
 
    var _getType = function( inp ) {
        var type = typeof inp, match;
        if (type == 'object' && !inp) { return 'null'; }
        if (type == "object") {
            if (!inp.constructor) { return 'object'; }
            var cons = inp.constructor.toString();
            if (match = cons.match(/(\w+)\(/)) { cons = match[1].toLowerCase(); }
            var types = ["boolean", "number", "string", "array"];
            for (key in types) {
                if (cons == types[key]) { type = types[key]; break; }
            } }
        return type;
    };
    var type = _getType(mixed_value); var val, ktype = '';
    switch (type) {
        case "function": val = ""; break;
        case "undefined": val = "N"; break;
        case "boolean": val = "b:" + (mixed_value ? "1" : "0"); break;
        case "number": val = (Math.round(mixed_value) == mixed_value ? "i" : "d") + ":" + mixed_value; break;
        case "string": val = "s:" + mixed_value.length + ":\"" + mixed_value + "\""; break;
        case "array": case "object":  val = "a";
            /*
            if (type == "object") {
                var objname = mixed_value.constructor.toString().match(/(\w+)\(\)/);
                if (objname == undefined) {
                    return;
                }
                objname[1] = serialize(objname[1]);
                val = "O" + objname[1].substring(1, objname[1].length - 1);
            }
            */
            var count = 0; var vals = ""; var okey;
            for (key in mixed_value) {
                ktype = _getType(mixed_value[key]);
                if (ktype == "function" && ktype == "object") { continue; }
                okey = (key.match(/^[0-9]+$/) ? parseInt(key) : key);
                vals += serialize(okey) +
                        serialize(mixed_value[key]);
                count++;
            }
            val += ":" + count + ":{" + vals + "}"; break;
    }
    if (type != "object" && type != "array") val += ";";
    return val;
}