<script type="text/javascript">
var CalYear = null;
var CalMonth = null;
var CalDay = null;
var CalTracker = false;
var CalFunct = null;
function SetDates(){ 
	CalYear = document.getElementById('Event_Year'); CalMonth = document.getElementById('Event_Month'); CalDay = document.getElementById('Event_Day');
	if(CalYear != null){
	var TDate=new Date(parseInt(CalYear.value,10), parseInt(CalMonth.value,10)-1, CalDay.value);
	var Inc = document.getElementsByTagName('input');
	if(document.getElementById('Expiration_Month')){
		for(var i=0; i < Inc.length; i++){ if (Inc[i].id == "Event_Duration" && Inc[i].checked){ Inc = Inc[i].value; break; } }
		if(parseInt(Inc) != 0){
			TDate.setDate(parseInt(TDate.getDate())+parseInt(Inc));
			var List = document.getElementById('Expiration_Month');
			for(var i=0; i < List.options.length; i++){ if(parseInt(List.options[i].value,10) == (TDate.getMonth()+1)){ List.selectedIndex=i; List.onchange(); break; } }
			var List = document.getElementById('Expiration_Day');
			for(var i=0; i < List.options.length; i++){ if(parseInt(List.options[i].value,10) == TDate.getDate()){ List.selectedIndex=i; List.onchange(); break; } }
			var List = document.getElementById('Expiration_Year');
			for(var i=0; i < List.options.length; i++){ if(parseInt(List.options[i].value,10) == TDate.getFullYear()){ List.selectedIndex=i; List.onchange(); break; } }
	} } } }
function setDateRel(){
	var Inc = document.getElementsByTagName('input');
	for(var i=0; i < Inc.length; i++){ if (Inc[i].id == "Event_Duration" && Inc[i].value == 0){ Inc[i].checked = true; Inc[i].onchange(); break; } }
}
function SetCalDate(SDate){ CalTracker = true; SDate = SDate.split("-");
	var MonthArry = new Array("January","February","March","April","May","June","July","August","September","October","November","December");
	var Start=new Date(parseInt(SDate[0],10),(parseInt(SDate[1],10)-1),1); var End=new Date(parseInt(SDate[0],10),parseInt(SDate[1],10),0);
	var SLast=new Date(parseInt(SDate[0],10),(parseInt(SDate[1],10)-1),(0-(Start.getDay()-1))); var ELast=new Date(parseInt(SDate[0],10),(parseInt(SDate[1],10)-1),0);
	var SNext=new Date(parseInt(SDate[0],10),parseInt(SDate[1],10),1);
	var List = document.getElementById('Calendar_Month'); 
	for(var i=0; i < List.options.length; i++){ if(parseInt(List.options[i].value,10) == parseInt(SDate[1],10)){ List.selectedIndex=i; List.onchange(); break; } }
	var List = document.getElementById('Calendar_Year');
	for(var i=0; i < List.options.length; i++){ if(parseInt(List.options[i].value,10) == parseInt(SDate[0],10)){ List.selectedIndex=i; List.onchange(); break; } }
	var z = 0; var b = 0; var Run = false; var Next = false; if(Start.getDay() == 0){ Run = true; b = 1; }
	for(var n=0; n<6; n++){ for(var a=0; a<7; a++){ var Day = document.getElementById('Day'+z);
		Day.getElementsByTagName('a')[0].onclick = null; Day.getElementsByTagName('a')[0].onmouseout=new Function ("window.status=''; return true;");
		Day.getElementsByTagName('a')[0].className = ""; Day.className = "CalDay";
		if(Day!=null){ if(Run == false){ Day.getElementsByTagName('a')[0].innerHTML = SLast.getDate()+b;
				Day.className = Day.className+"Disabled"; Day.getElementsByTagName('a')[0].onclick = new Function ('return false;');
				Day.getElementsByTagName('a')[0].onmouseover=new Function ("window.status='"+MonthArry[ELast.getMonth()]+" "+(SLast.getDate()+b)+" "+ELast.getFullYear()+"'; return true;");
				Day.getElementsByTagName('a')[0].title=MonthArry[ELast.getMonth()]+" "+(SLast.getDate()+b)+" "+ELast.getFullYear();
				if((SLast.getDate()+b) == ELast.getDate()){	Run = true; b=0; } } else { Day.getElementsByTagName('a')[0].innerHTML = b;
				Day.getElementsByTagName('a')[0].onmouseover=new Function ("window.status='"+MonthArry[Start.getMonth()]+" "+b+" "+Start.getFullYear()+"'; return true;");
				Day.getElementsByTagName('a')[0].title=MonthArry[Start.getMonth()]+" "+b+" "+Start.getFullYear();
				Day.getElementsByTagName('a')[0].onclick = new Function ('SelCalDate(\''+Start.getFullYear()+'-'+(Start.getMonth()+1)+'-'+b+'\'); return false;');
				if(Next == true){ Day.className = Day.className+"Disabled"; Day.getElementsByTagName('a')[0].onclick = new Function ('return false;');
					Day.getElementsByTagName('a')[0].onmouseover=new Function ("window.status='"+MonthArry[SNext.getMonth()]+" "+b+" "+SNext.getFullYear()+"'; return true;");
					Day.getElementsByTagName('a')[0].title=MonthArry[SNext.getMonth()]+" "+b+" "+SNext.getFullYear();
				} else if(b==parseInt(SDate[2],10)) Day.getElementsByTagName('a')[0].className = "NavSel";
				if(b == End.getDate()){ Next = true; b=0; } } b++; } z++; } } CalTracker = false; }
function StartCalDate(ID,YEAR,MONTH,DAY,FNCT){ if(FNCT != null && FNCT != "null") CalFunct = FNCT; else CalFunct = null;
	var agt=navigator.userAgent.toLowerCase();
	var Cal = document.getElementById('Calendar');  Cal.style.display = "block"; var Elem = document.getElementById(ID);
	var Offsets = AEV_FindOffset(Elem); Xpos = Offsets[0]-Cal.offsetWidth+Elem.offsetWidth+5; Ypos = Offsets[1]-5;	
	if(navigator.appName.indexOf("Microsoft")!=-1 && (agt.indexOf("msie 7.")!=-1 || agt.indexOf("msie 6.")!=-1)){ Ypos-=20; }
	Cal.style.left = Xpos+"px"; Cal.style.top = Ypos+"px";
	CalYear = document.getElementById(YEAR); CalMonth = document.getElementById(MONTH); CalDay = document.getElementById(DAY);
	SetCalDate(CalYear.value+"-"+CalMonth.value+"-"+CalDay.value); }
function ChangeCalDate(){ var Month = document.getElementById('Calendar_Month'); var Year = document.getElementById('Calendar_Year');
	if(CalTracker == false){
		if(parseInt(CalYear.value,10) == parseInt(Year.value,10) && parseInt(CalMonth.value,10) == parseInt(Month.value,10)) SetCalDate(Year.value+"-"+Month.value+"-"+CalDay.value);
		else SetCalDate(Year.value+"-"+Month.value+"-0");
	} }
function SelCalDate(SDate){SDate = SDate.split("-");
	for(var i=0; i<CalDay.options.length; i++){ 	if(parseInt(CalDay.options[i].value,10) == parseInt(SDate[2])){ 	CalDay.selectedIndex=i; 	break; } }
	for(var i=0; i<CalMonth.options.length; i++){ if(parseInt(CalMonth.options[i].value,10) == parseInt(SDate[1])){ CalMonth.selectedIndex=i; break; } }
	for(var i=0; i<CalYear.options.length; i++){ 	if(parseInt(CalYear.options[i].value,10) == parseInt(SDate[0])){ 	CalYear.selectedIndex=i; 	break; } }
	CalDay.onchange(); CalMonth.onchange(); CalYear.onchange();	if(CalFunct != null) eval(CalFunct+'();'); StopCalDate();
}
function StopCalDate(){ var Cal = document.getElementById('Calendar'); Cal.style.display = "none"; }
</script>

<div id="Calendar">
  <div style="float:left; clear:none; margin-right:3px;">
    <select name="Calendar Month" id="Calendar_Month" class="CstmFrmElmnt88" onmouseover="window.status='Calendar Month'; return true;" onmouseout="window.status=''; return true;" onChange="javascript:ChangeCalDate();" title="Calendar Month">
      <? $TDate = date("m",mktime(0,0,1,substr($Date,5,2),1,date("Y")));
				for($n = 0; $n < 12; $n++){ $TDate2 = date("m",mktime(0,0,1,($n+1),1,date("Y"))); ?>
      <option value="<? echo $TDate2; ?>" title="<? echo date("F",mktime(0,0,1,($n+1),1,date("Y"))); ?>"><? echo date("F",mktime(0,0,1,($n+1),1,date("Y"))); ?></option>
      <? } ?>
    </select>
  </div>
  <div style="float:left; clear:none;">
    <select name="Calendar Year" id="Calendar_Year" class="CstmFrmElmnt64" onmouseover="window.status='Calendar Year'; return true;" onmouseout="window.status=''; return true;" onChange="javascript:ChangeCalDate();"  title="Calendar Year">
      <? $TDate = date("Y",mktime(0,0,1,1,1,substr($Date,0,4)));
				for($n = -5; $n < 8; $n++){ $TDate2 = date("Y",mktime(0,0,1,1,1,(date("Y")+$n))); ?>
      <option value="<? echo $TDate2; ?>" title="<? echo $TDate2; ?>"><? echo $TDate2; ?></option>
      <? } ?>
    </select>
  </div>
  <div id="BtnCloseCalendar"><a href="javascript:StopCalDate();"  onmouseover="window.status='Close Calendar'; return true;" onMouseOut="window.status=''; return true;" title="Close Calendar">Close Calendar</a></div>
  <br clear="all" />
  <div id="CalDays">
    <? for($n = 0; $n < 7; $n++){ ?>
    <div class="CalHead"><? echo date("D",mktime(0,0,1,1,($n+1),1973)); ?></div>
    <? } echo '<br clear="all" />'; $z = 0; for($n = 0; $n < 6; $n++){ for($a = 0; $a < 7; $a++){ ?>
    <div id="Day<? echo $z; ?>" class="CalDay"><a href="#">1</a></div>
    <? $z++; if($a==6) echo '<br clear="all" />';} } ?>
  </div>
</div>
