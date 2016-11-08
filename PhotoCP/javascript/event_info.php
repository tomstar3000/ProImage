<?
  $count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2; $r_path = ""; for($n=0;$n<$count;$n++)$r_path .= "../";
  define("PhotoExpress Pro", true);
  define('Allow Scripts',true);
  include $r_path.'../security.php';
  require_once($r_path.'../Connections/cp_connection.php');
  
  header("Content-type: text/javascript");
  ob_start();

  ?>
  <script type="text/javascript">
      // JavaScript Document
      var xmlHttp = null;
      var XMLFold = 'xml'; // XML Folder
      var IncFold = 'includes'; // XML Folder
      var XMLSave = XMLFold + '/event_info.php'; // Were to go to save Event Information
      var XMLDefault = XMLFold + '/event_defaults.php'; // Were to go to save Event Default
      var XMLGiftCert = XMLFold + '/event_gifts.php'; // Were to go to save Event Gift Certificates
      var XMLPhotoSave = XMLFold + '/event_photographer_info.php'; // Were to go to get Photographer Information
      var XMLPricSave = XMLFold + '/event_pricing_info.php'; // Were to go to get Photographer Information
      var FormPhoto = IncFold + '/form_event_photographer.php'; // Were to go to find Photographer Form
      var FormPric = IncFold + '/form_event_pricing.php'; // Were to go to find Pricing Form
      var PrssGraph = '<p align="center"><img src="/PhotoCP/images/Processing.gif" alt="Processing" width="147" height="105" vspace="50" /> </p>';
      function Org_Initiate() { // Initiate the xmlHTTP gateway
          xmlHttp = GetXmlHttpObject(); // Create a new Object
          if (xmlHttp == null) {
              alert("Your browser does not support AJAX!");
              return;
          } // Alert people that their browswer doesn't support Ajax
      }
      function GetXmlHttpObject() { // Set up our gateway
          try {
              Gateway = new ActiveXObject("Microsoft.XMLHTTP");
              Gateway.async = "false";
          } // Internet Explorer
          catch (e) {
              try {
                  Gateway = new XMLHttpRequest();
              } // Firefox, Opera 8.0+, Safari
              catch (e) {
                  Gateway = new ActiveXObject('MSXML2.XMLHTTP.3.0');
              }
          } // Internet Explorer 5.5 and 6
          return Gateway;
      }
      function serialize(mixed_value) {
          // http://kevin.vanzonneveld.net
          // +   original by: Arpad Ray (mailto:arpad@php.net)
          // +   improved by: Dino
          // +   bugfixed by: Andrej Pavlovic
          // %          note: We feel the main purpose of this function should be to ease the transport of data between php & js
          // %          note: Aiming for PHP-compatibility, we have to translate objects to arrays
          // *     example 1: serialize(['Kevin', 'van', 'Zonneveld']);
          // *     returns 1: 'a:3:{i:0;s:5:"Kevin";i:1;s:3:"van";i:2;s:9:"Zonneveld";}'
          // *     example 2: serialize({firstName: 'Kevin', midName: 'van', surName: 'Zonneveld'});
          // *     returns 2: 'a:3:{s:9:"firstName";s:5:"Kevin";s:7:"midName";s:3:"van";s:7:"surName";s:9:"Zonneveld";}'
          var _getType = function(inp) {
              var type = typeof inp, match;
              var key;
              if (type == 'object' && !inp) {
                  return 'null';
              }
              if (type == "object") {
                  if (!inp.constructor) {
                      return 'object';
                  }
                  var cons = inp.constructor.toString();
                  if (match = cons.match(/(\w+)\(/)) {
                      cons = match[1].toLowerCase();
                  }
                  var types = ["boolean", "number", "string", "array"];
                  for (key in types) {
                      if (cons == types[key]) {
                          type = types[key];
                          break;
                      }
                  }
              }
              return type;
          };
          var type = _getType(mixed_value);
          var val, ktype = '';
          switch (type) {
              case "function":
                  val = "";
                  break;
              case "undefined":
                  val = "N";
                  break;
              case "boolean":
                  val = "b:" + (mixed_value ? "1" : "0");
                  break;
              case "number":
                  val = (Math.round(mixed_value) == mixed_value ? "i" : "d") + ":" + mixed_value;
                  break;
              case "string":
                  val = "s:" + mixed_value.length + ":\"" + mixed_value + "\"";
                  break;
              case "array":
              case "object":
                  val = "a";
                  /* if (type == "object") { var objname = mixed_value.constructor.toString().match(/(\w+)\(\)/);
                   if (objname == undefined) { return; }
                   objname[1] = serialize(objname[1]); val = "O" + objname[1].substring(1, objname[1].length - 1); } */
                  var count = 0;
                  var vals = "";
                  var okey;
                  var key;
                  for (key in mixed_value) {
                      var ktype = _getType(mixed_value[key]);
                      //alert(key + ' type is ' + ktype);
                      if (ktype != "function" && ktype != "object") {
                          okey = (key.match(/^[0-9]+$/) ? parseInt(key) : key);
                          vals += serialize(okey) + serialize(mixed_value[key]);
                          count++;
                      }
                  }
                  val += ":" + count + ":{" + vals + "}";
                  break;
          }
          if (type != "object" && type != "array")
              val += ";";
          return val;
      }
      function Save_Evnt_Shp_Opt(ID) { // Load Folders
          var data = new Array();
          data['ID'] = ID;
          data['toLab'] = ((document.getElementById('SendLab').checked) ? document.getElementById('SendLab').value : 'n');
          data['pickUp'] = ((document.getElementById('PickUp').checked) ? document.getElementById('PickUp').value : 'n');
          data['shipTo'] = ((document.getElementById('ShipTo').checked) ? document.getElementById('ShipTo').value : 'n');
          Org_Initiate();
          if (xmlHttp != null) {
              var url = XMLSave + "?data=" + escape(serialize(data));
              xmlHttp.open('get', url);
              xmlHttp.onreadystatechange = Saved_Evnt_Shp_Opt;
              xmlHttp.send('');
          }
      }
      function Saved_Evnt_Shp_Opt() { // Start building Folders
          if (xmlHttp.readyState == 4) {
              var xmlDoc = xmlHttp.responseXML.documentElement; // Get XML information
              //alert(xmlHttp.responseText);
          }
      }

      // *****************************************************************************************************
      // ***                                  Photographer Information                                     ***
      // *****************************************************************************************************
      function PhotoInfo() {
          send_Msg(PrssGraph, true, null, null);
          Org_Initiate();
          if (xmlHttp != null) {
              xmlHttp.onreadystatechange = PhotoFormLoad;
              xmlHttp.open("GET", FormPhoto, true);
              xmlHttp.send('');
          }
      }
      function PhotoFormLoad() {
          if (xmlHttp.readyState == 4) {
              var ID = document.getElementById('Photographer').value;
              var HTML = xmlHttp.responseText;
              send_Msg(HTML, true, null, null);
              Custom.setElem(document.getElementById('Photographer_Country'));
              if (parseInt(ID) > 0) {
                  Org_Initiate();
                  if (xmlHttp != null) {
                      var url = XMLPhotoSave + "?action=1&id=" + ID + "&user=<? echo $CustId; ?>";
                      xmlHttp.open('get', url);
                      xmlHttp.onreadystatechange = Get_Photo_Info;
                      xmlHttp.send('');
                  }
              } else {
                  AEV_GetState('', '', '', '');
              }
          }
      }
      function Get_Photo_Info() { // Start building Folders
          if (xmlHttp.readyState == 4) {
              var xmlDoc = xmlHttp.responseXML.documentElement; // Get XML information
              for (var n = 0; n < xmlDoc.childNodes.length; n++) {
                  var Node = xmlDoc.childNodes[n]; // For Each XML Node
                  if (Node.nodeType == 1) {
                      var Form = document.getElementById('PhotographerForm');
                      var Inpts = Form.getElementsByTagName('input');
                      var Sels = Form.getElementsByTagName('select');
                      Inpts[0].value = Node.getAttribute("fname");
                      Inpts[1].value = Node.getAttribute("lname");
                      Inpts[2].value = Node.getAttribute("add");
                      Inpts[3].value = Node.getAttribute("sapt");
                      Inpts[4].value = Node.getAttribute("add2");
                      Inpts[5].value = Node.getAttribute("city");
                      Inpts[7].value = Node.getAttribute("zip");
                      for (var n = 0; n < Sels[0].options.length; n++) {
                          var Opt = Sels[0].options[n];
                          if (Opt.value == Node.getAttribute("country")) {
                              Sels[0].selectedIndex = n;
                              Sels[0].onchange();
                              break;
                          }
                      }
                      AEV_GetState(Node.getAttribute("country"), Node.getAttribute("state"), '', 'Photographer_');
                      Inpts[8].value = Node.getAttribute("p1");
                      Inpts[9].value = Node.getAttribute("p2");
                      Inpts[10].value = Node.getAttribute("p3");
                      Inpts[11].value = Node.getAttribute("phone");
                      Inpts[12].value = Node.getAttribute("email");
                  }
              }
          }
      }
      function Save_Photo_Info() {
          var ID = document.getElementById('Photographer').value;
          var Form = document.getElementById('PhotographerForm');
          var Inpts = Form.getElementsByTagName('input');
          var Sels = Form.getElementsByTagName('select');
          var DATA = new Array();
          for (var n = 0; n < Inpts.length; n++)
              DATA[Inpts[n].id] = Inpts[n].value;
          for (var n = 0; n < Sels.length; n++)
              DATA[Sels[n].id] = Sels[n].value;
          Org_Initiate();
          if (xmlHttp != null) {
              var url = XMLPhotoSave + "?action=2&id=" + ID + "&user=<? echo $CustId; ?>&data=" + escape(serialize(DATA));
              xmlHttp.open('get', url);
              xmlHttp.onreadystatechange = Saved_Photo_Info;
              xmlHttp.send('');
              send_Msg(PrssGraph, true, null, null);
          }
      }
      function Saved_Photo_Info() {
          var Photog = document.getElementById('Photographer');
          var SortArray = new Array();
          var IDsArray = new Array();
          var MapArray = new Array();
          var SelID = 0;
          var Added = false;
          if (xmlHttp.readyState == 4) {
              var xmlDoc = xmlHttp.responseXML.documentElement; // Get XML information
              for (var n = 0; n < xmlDoc.childNodes.length; n++) {
                  var Node = xmlDoc.childNodes[n]; // For Each XML Node
                  if (Node.nodeType == 1) {
                      if (Node.childNodes[0].nodeValue == "Added")
                          Added = true;
                      if (Added == true) {
                          SelID = parseInt(Node.getAttribute("id"));
                          SortArray.push(Node.childNodes[0].nodeValue);
                          IDsArray.push(parseInt(Node.getAttribute("id")));
                          MapArray[parseInt(Node.getAttribute("id"))] = Node.childNodes[0].nodeValue;
                      }
                  }
              }
              if (Added == true) {
                  for (var n = 0; n < Photog.options.length; n++) {
                      SortArray.push(Photog.options[n].text);
                      IDsArray.push(parseInt(Photog.options[n].value));
                      MapArray[parseInt(Photog.options[n].value)] = Photog.options[n].text;
                  }
                  while (Photog.options.length > 0)
                      Photog.remove(0);
                  SortArray.sort();
                  var i = 0;
                  for (var n = 0; n < SortArray.length; n++) {
                      if (SortArray[n] != "undefined" && SortArray[n] != undefined) {
                          for (z in MapArray) {
                              if (MapArray[z] == SortArray[n]) {
                                  var NewOption = document.createElement('option');
                                  NewOption.text = SortArray[n];
                                  NewOption.value = z;
                                  NewOption.title = SortArray[n];
                                  try {
                                      Photog.add(NewOption, null);
                                  } // standards compliant; doesn't work in IE
                                  catch (ex) {
                                      Photog.add(NewOption);
                                  } // IE only
                                  if (parseInt(z) == parseInt(SelID))
                                      Photog.selectedIndex = i;
                                  i++;
                              }
                          }
                      }
                  }
                  Custom.setElem(Photog);
              }
              send_Msg('', false, null, null);
          }
      }

      // *****************************************************************************************************
      // ***                                     Pricing Information                                       ***
      // *****************************************************************************************************
      function PricInfo() {
          send_Msg('', true, null, null);
          Org_Initiate();
          if (xmlHttp != null) {
              xmlHttp.onreadystatechange = PricFormLoad;
              xmlHttp.open("GET", FormPric, true);
              xmlHttp.send('');
          }
      }
      function PricFormLoad() {
          var ID = document.getElementById('Pricing_Group').value;
          send_Msg(PrssGraph, true, null, null);
          if (xmlHttp.readyState == 4) {
              var HTML = xmlHttp.responseText;
              send_Msg(HTML, true, null, null);
              if (parseInt(ID) > 0) {
                  Org_Initiate();
                  if (xmlHttp != null) {
                      var url = XMLPricSave + "?action=1&id=" + ID + "&user=<? echo $CustId; ?>";
                      xmlHttp.open('get', url);
                      xmlHttp.onreadystatechange = Get_Price_Info;
                      xmlHttp.send('');
                  }
              }
          }
      }
      function Get_Price_Info() { // Start building Folders
          if (xmlHttp.readyState == 4) {
              var Form = document.getElementById('PricingForm');
              var Inpts = Form.getElementsByTagName('input');
              var xmlDoc = xmlHttp.responseXML.documentElement;
              var z = 0;
              for (var n = 0; n < xmlDoc.childNodes.length; n++) {
                  var Node = xmlDoc.childNodes[n]; // For Each XML Node
                  if (Node.nodeType == 1) {
                      if (z == 0)
                          Inpts[0].value = Node.childNodes[0].nodeValue
                      else {
                          for (var m = 0; m < Node.childNodes.length; m++) {
                              var Node2 = Node.childNodes[m]; // For Each XML Node
                              if (Node2.nodeType == 1) {
                                  for (var o = 1; o < Inpts.length; o += 4) {
                                      if (Inpts[o].value == Node2.getAttribute("id")) {
                                          Inpts[o].checked = true;
                                          Inpts[(o + 1)].value = Node2.getAttribute("price");
                                          Inpts[(o + 2)].value = Node2.getAttribute("order");
                                          break;
                                      }
                                  }
                              }
                          }
                      }
                      z++;
                  }
              }
          }
      }
      function Save_Price_Info() {
          var ID = document.getElementById('Pricing_Group').value;
          var Form = document.getElementById('PricingForm');
          var Inpts = Form.getElementsByTagName('input');
          var DATA = new Array();
          for (var o = 1; o < Inpts.length; o += 4) {
              if (Inpts[o].checked == true) {
                  DATA[Inpts[o].value] = Array();
                  DATA[Inpts[o].value][0] = Inpts[(o + 1)].value;
                  DATA[Inpts[o].value][1] = Inpts[(o + 2)].value;
              }
          }
          Org_Initiate();
          if (xmlHttp != null) {
              var url = XMLPricSave + "?action=2&id=" + ID + "&user=<? echo $CustId; ?>&name=" + Inpts[0].value + "&data=" + escape(serialize(DATA));
              xmlHttp.open('get', url);
              xmlHttp.onreadystatechange = Saved_Price_Info;
              xmlHttp.send('');
              send_Msg(PrssGraph, true, null, null);
          }
      }
      function Saved_Price_Info() {
          var Photog = document.getElementById('Pricing_Group');
          var SortArray = new Array();
          var IDsArray = new Array();
          var MapArray = new Array();
          var SelID = 0;
          var Added = false;
          if (xmlHttp.readyState == 4) {
              var xmlDoc = xmlHttp.responseXML.documentElement; // Get XML information
              for (var n = 0; n < xmlDoc.childNodes.length; n++) {
                  var Node = xmlDoc.childNodes[n]; // For Each XML Node
                  if (Node.nodeType == 1) {
                      if (Node.childNodes[0].nodeValue == "Added")
                          Added = true;
                      if (Added == true) {
                          SelID = parseInt(Node.getAttribute("id"));
                          SortArray.push(Node.childNodes[0].nodeValue);
                          IDsArray.push(parseInt(Node.getAttribute("id")));
                          MapArray[parseInt(Node.getAttribute("id"))] = Node.childNodes[0].nodeValue;
                      }
                  }
              }
              if (Added == true) {
                  for (var n = 0; n < Photog.options.length; n++) {
                      SortArray.push(Photog.options[n].text);
                      IDsArray.push(parseInt(Photog.options[n].value));
                      MapArray[parseInt(Photog.options[n].value)] = Photog.options[n].text;
                  }
                  while (Photog.options.length > 0)
                      Photog.remove(0);
                  SortArray.sort();
                  var i = 0;
                  for (var n = 0; n < SortArray.length; n++) {
                      if (SortArray[n] != "undefined" && SortArray[n] != undefined) {
                          for (z in MapArray) {
                              if (MapArray[z] == SortArray[n]) {
                                  var NewOption = document.createElement('option');
                                  NewOption.text = SortArray[n];
                                  NewOption.value = z;
                                  NewOption.title = SortArray[n];
                                  try {
                                      Photog.add(NewOption, null);
                                  } // standards compliant; doesn't work in IE
                                  catch (ex) {
                                      Photog.add(NewOption);
                                  } // IE only
                                  if (parseInt(z) == parseInt(SelID))
                                      Photog.selectedIndex = i;
                                  i++;
                              }
                          }
                      }
                  }
                  Custom.setElem(Photog);
              }
              send_Msg('', false, null, null);
          }
      }

      // *****************************************************************************************************
      // ***                                     Default Information                                       ***
      // *****************************************************************************************************
      Array.prototype.inArray = function(value) {
          var i;
          for (i = 0; i < this.length; i++) {
              if (this[i] === value)
                  return true;
          }
          return false;
      };
      Array.prototype.ArrayIndex = function(value) {
          var i;
          for (i = 0; i < this.length; i++) {
              if (this[i] === value)
                  return i;
          }
          return false;
      };

      function Save_Default_Info() {
          var DATA = new Array();
          var NameArray = new Array('Public_Event', 'Event_Duration', 'ShipClient', 'At_Lab', 'At_Studio', 'SendLab', 'Correct');
          for (i = 0; i < document.getElementsByTagName('input').length; i++) {
              if (document.getElementsByTagName('input')[i].checked == true && NameArray.inArray(document.getElementsByTagName('input')[i].id)) {
                  INDX = NameArray.ArrayIndex(document.getElementsByTagName('input')[i].id);
                  DATA[NameArray[INDX]] = document.getElementsByTagName('input')[i].value;
              }
          }

          DATA['Expiration_Month'] = document.getElementById('Expiration_Month').value;
          DATA['Expiration_Day'] = document.getElementById('Expiration_Day').value;
          DATA['Expiration_Year'] = document.getElementById('Expiration_Year').value;
          DATA['Expiration_Year'] = document.getElementById('Expiration_Year').value;
          DATA['Owner'] = document.getElementById('Owner').value;
          DATA['Phone'] = document.getElementById('P1').value + document.getElementById('P2').value + document.getElementById('P3').value;
          DATA['Owner_Email'] = document.getElementById('Owner_Email').value;
          DATA['Location'] = document.getElementById('Location').value;
          DATA['City'] = document.getElementById('City').value;
          DATA['State'] = document.getElementById('State').value;
          DATA['Country'] = document.getElementById('Country').value;

          if (document.getElementById('MrkEvntCodes') != null) {
              var Elems = document.getElementById('MrkEvntCodes').getElementsByTagName('input');
              if (Elems.length > 0) {
                  for (i = 0; i < Elems.length; i++) {
                      if (Elems[i].checked == true) {
                          var ElemName = (Elems[i].name == "Event Marketing[]") ? "Event_Marketing" : "Event_Marketing_Codes";
                          if (!DATA[ElemName])
                              DATA[ElemName] = new Array();
                          DATA[ElemName].push(Elems[i].value);
                      }
                  }
              }
          }
          if (document.getElementById('PresetRecordList') != null) {
              var Elems = document.getElementById('PresetRecordList').getElementsByTagName('input');
              if (Elems.length > 0) {
                  for (i = 0; i < Elems.length; i++) {
                      if (Elems[i].checked == true) {
                          var ElemName = 'Discount_items';
                          if (!DATA[ElemName])
                              DATA[ElemName] = new Array();
                          DATA[ElemName].push(Elems[i].value);
                      }
                  }
              }
          }
          if (document.getElementById('GiftCerts') != null) {
              var Elems = document.getElementById('GiftCerts').getElementsByTagName('input');
              if (Elems.length > 0) {
                  for (i = 0; i < Elems.length; i++) {
                      if (Elems[i].checked == true) {
                          var ElemName = 'Gift_Certs';
                          if (!DATA[ElemName])
                              DATA[ElemName] = new Array();
                          DATA[ElemName].push(Elems[i].value);
                      }
                  }
              }
          }
          if (document.getElementById('ActiveCoupons') != null) {
              var Elems = document.getElementById('ActiveCoupons').getElementsByTagName('input');
              if (Elems.length > 0) {
                  for (i = 0; i < Elems.length; i++) {
                      if (Elems[i].checked == true) {
                          var ElemName = 'Coupons';
                          if (!DATA[ElemName])
                              DATA[ElemName] = new Array();
                          DATA[ElemName].push(Elems[i].value);
                      }
                  }
              }
          }
          if (document.getElementById('AllEventNotificationRecords') != null) {
              var Elems = document.getElementById('AllEventNotificationRecords').getElementsByTagName('input');
              if (Elems.length > 0) {
                  for (i = 0; i < Elems.length; i++) {
                      if (Elems[i].checked == true) {
                          var ElemName = 'EventNotifications';
                          if (!DATA[ElemName])
                              DATA[ElemName] = new Array();
                          DATA[ElemName].push(Elems[i].value);
                      }
                  }
              }
          }
          DATA['Watermark'] = document.getElementById('Watermark').value;
          DATA['Watermark_Frequency'] = document.getElementById('Watermark_Frequency').value;
          DATA['Watermark_Opacity'] = document.getElementById('Watermark_Opacity').value;
          DATA['Photographer'] = document.getElementById('Photographer').value;
          DATA['Pricing_Group'] = document.getElementById('Pricing_Group').value;
          console.log(DATA);
          Org_Initiate();
          if (xmlHttp != null) {
              var url = XMLDefault + "?id=<? echo $CustId; ?>&data=" + escape(serialize(DATA));
              xmlHttp.open('get', url);
              xmlHttp.onreadystatechange = Saved_Default_Info;
              xmlHttp.send('');
          }
      }
      function Saved_Default_Info() {
          if (xmlHttp.readyState == 4) {
              var xmlDoc = xmlHttp.responseXML.documentElement; // Get XML information
              send_Msg('<p>Your Defaults have been saved.</p>', true, null, null);
          }
      }
      // *****************************************************************************************************
      // ***                                Gift Certificates Information                                  ***
      // *****************************************************************************************************
      function Save_Gift_Info() {
          var DATA = new Array();
          DATA['Gift_Name'] = document.getElementById('Gift_Name').value;
          DATA['Gift_Email'] = document.getElementById('Gift_Email').value;
          DATA['Gift_Amount'] = document.getElementById('Gift_Amount').value;
          DATA['Gift_Code'] = document.getElementById('Gift_Code').value;
          if (DATA['Gift_Name'] == '' || DATA['Gift_Email'] == '' || DATA['Gift_Amount'] == '' || DATA['Gift_Code'] == '') {
              alert('Please completly fill out the Gift Certificate form.');
              return;
          }
          Org_Initiate();
          if (xmlHttp != null) {
              var url = XMLGiftCert + "?id=<? echo $CustId; ?>&data=" + escape(serialize(DATA));
              xmlHttp.open('get', url);
              xmlHttp.onreadystatechange = Saved_Gift_Info;
              xmlHttp.send('');
          }
      }
      function Saved_Gift_Info() {
          var k = 0;
          var HTML = '';
          var ELEMS = new Array();
          if (xmlHttp.readyState == 4) {
              var xmlDoc = xmlHttp.responseXML.documentElement; // Get XML information
              for (var n = 0; n < xmlDoc.childNodes.length; n++) {
                  var Node = xmlDoc.childNodes[n]; // For Each XML Node
                  if (Node.nodeType == 1) {
                      ELEMS.push('GiftCerts_' + k);
                      HTML += '<div class="fontSpecial5"><p>'
                              + '<input type="checkbox" name="GiftCerts[]" id="GiftCerts_' + k + '" value="' + Node.getAttribute("id") + '" class="CstmFrmElmnt" title="' + Node.getAttribute("name") + '" onmouseover="window.status=\'' + Node.getAttribute("name") + '\'; return true;" onmouseout="window.status=\'\'; return true;" />'
                              + Node.getAttribute("name") + '</p>'
                              + '<p>' + Node.getAttribute("email") + '</p>'
                              + '<p>' + Node.getAttribute("price") + '</p>'
                              + '<p>' + Node.getAttribute("redem") + '</p>'
                              + '<p>' + Node.getAttribute("code") + '</p></div>';
                      k++;
                  }
              }
              document.getElementById('GiftCerts').innerHTML = HTML;
              document.getElementById('Gift_Name').value = '';
              document.getElementById('Gift_Email').value = '';
              document.getElementById('Gift_Amount').value = '';
              document.getElementById('Gift_Code').value = '';
              for (var z = 0; z < ELEMS.length; z++) {
                  Custom.setElem(document.getElementById(ELEMS[z]));
              }
          }
      }
  </script>
  <?
  $Buff = ob_get_contents();
  ob_end_clean();
  echo str_replace(array('<script type="text/javascript">', '</script>'), '', $Buff);
?>