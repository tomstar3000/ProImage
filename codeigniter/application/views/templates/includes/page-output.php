<p>Handle: <?php echo $handle; ?><br/>
<?php

if( isset( $code )){
  echo "Code: ".$code."<br/>";
}

if( isset( $page )){
  echo "Page: ".$page."<br/>";
}
?></p>

<p>Page rendered in <strong>{elapsed_time}</strong> seconds</p>