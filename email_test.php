<?
define('Allow Scripts',true);
if(isset($r_path)===false){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-1;
	$r_path = "";
	for($n=0;$n<$count;$n++){
		$r_path .= "../";
	}
}
require_once($r_path.'scripts/fnct_send_email.php');
include $r_path.'scripts/fnct_clean_entry.php';
$Email = "development@proimagesoftware.com";
$encnum = "999c7f3eb6523bf296013353bbcb5e9b";
								
ob_start();
include ($r_path.'checkout/invoice_signup.php');
$msg = ob_get_contents();
ob_end_clean();
send_email("info@photoexpresspro.com", $Email, "Pro Image Software: Invoice", $msg , true, false, false);
//send_email($Email, "info@photoexpresspro.com", "Pro Image Software: Invoice", $msg , true, false, false);
?>