function change_div_visabililty(checkfield,div1,div2,div3,div4){
	if(document.getElementById(checkfield).checked){
		document.getElementById(div1).style.display = "none";
		document.getElementById(div2).style.display = "block";
	} else {
		document.getElementById(div1).style.display = "block";
		document.getElementById(div2).style.display = "none";
	}
	if(div3){
		document.getElementById(div3).style.display = document.getElementById(div1).style.display;
		document.getElementById(div4).style.display = document.getElementById(div2).style.display;
	}
}