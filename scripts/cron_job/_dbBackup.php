<? $count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-1; $r_path = ""; for($n=0;$n<$count;$n++)$r_path .= "../";

$r_path = "/srv/proimage/current/";
require_once $r_path.'Connections/cp_connection.php';
require_once $r_path.'includes/AWS_S3.2.0/sdk.class.php';

$s3 = new AmazonS3();
$bucket = 'probackups';
$DB_Backup = $database_cp_connection.'_'.date('md').'.sql';

$out = array();
exec ('mysqldump -u '.escapeshellcmd($username_cp_connection).' -p'.escapeshellcmd($password_cp_connection).' '.escapeshellcmd($database_cp_connection).' > /tmp/'.escapeshellcmd($DB_Backup).' 2>&1', $out);
print_r($out);

$response = $s3->create_mpu_object($bucket, $DB_Backup, array(
	'fileUpload' => '/tmp/'.$DB_Backup
));

@unlink('/tmp/'.$DB_Backup);
?>