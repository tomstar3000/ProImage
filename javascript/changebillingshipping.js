function same_bill(){
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