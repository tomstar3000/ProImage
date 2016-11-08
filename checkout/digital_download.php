<? $b = 0; $AlNum = "0123456789abcdefghijklmnopqrstuvwxyz"; $Key = ""; while($b<5){ $RNum = rand(0, 36); $Key .= $AlNum[$RNum]; $b++; } ?>

<p>Dear, <? echo $row_get_bill['cust_bill_fname']." ".$row_get_bill['cust_bill_lname']; ?> </p>
<p>Thank you for purchasing digital files from photographer. </p>
<p>At your leisure you can <a href="http://www.proimagesoftware.com/download.php?invoice=<? echo $encnum.md5($Key); ?>">download
  your digital files here.</a></p>
<p>Please enter your key code is <strong><? echo $Key; ?></strong></p>
<p>Please note you have 15 days to download the files, should you encounter problems you may revisit this link to re-download
 your files. </p>
<p>If you need further assistance please contact us at <a href="mailto:support@proimagesoftware.com">support@proimagesoftware.com</a></p>
<p>Sincerely Photographer.</p>
<br clear="all" />
