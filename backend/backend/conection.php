<?php
// connection.php - reads host, port, user, pass, db from ENV and connects
$host = getenv('DB_HOST') ?: 'localhost';
$port = intval(getenv('DB_PORT') ?: 3306);
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASS') ?: '';
$db   = getenv('DB_NAME') ?: 'guvi_db';

// pass port to mysqli constructor (host, user, pass, db, port)
$conn = new mysqli($host, $user, $pass, $db, $port);

if ($conn->connect_error) {
  http_response_code(500);
  // show safe message for debugging in your logs / browser
  echo json_encode(['error'=>'DB connection failed: '.$conn->connect_error]);
  exit;
}
$conn->set_charset('utf8mb4');
?>
