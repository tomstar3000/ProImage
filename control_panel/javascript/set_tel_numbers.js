function set_tel_number(field,code_val){
	document.getElementById(field).value = document.getElementById(code_val+'1').value+document.getElementById(code_val+'2').value+document.getElementById(code_val+'3').value;
}