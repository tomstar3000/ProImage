function center_window(){
	if (parseInt(navigator.appVersion)>3) {
	 if (navigator.appName=='Netscape') {
	  winW = window.innerWidth;
	  winH = window.innerHeight;
	 }
	 if (navigator.appName.indexOf('Microsoft')!=-1) {
	  winW = document.body.offsetWidth;
	  winH = document.body.offsetHeight;
	 }
	}
	x=(screen.width/2)-(winW/2)-8;
	y=(screen.height/2)-(winH/2)-8;
	window.moveTo(x,y);	
}
function size_window(run_again){
	doc_height = document.getElementById('Sized_Element').offsetHeight+100;
	doc_width = document.getElementById('Sized_Element').offsetWidth+30;
	if(!run_again){
		window.resizeTo(doc_width,doc_height);
		center_window();
	} else {
		size_window(false)
	}
}