<?

/* https start*/
if(empty($_SERVER["HTTPS"]) || $_SERVER["HTTPS"] !== "on"){
  header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
  exit();
}
/* https end*/

/* cors start*/
// Allow from any origin
if (isset($_SERVER['HTTP_ORIGIN'])) {
  header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
  header('Access-Control-Allow-Credentials: true');
  header('Access-Control-Max-Age: 86400');    // cache for 1 day
}

// Access-Control headers are received during OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
  if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])){
    // header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Methods: POST, OPTIONS");
  }
  if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])){
    header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}, X-Auth-Token");
  }
  exit(0);
}
/* cors end*/

header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

?>