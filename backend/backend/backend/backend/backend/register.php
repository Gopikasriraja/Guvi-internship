<?php
header('Content-Type: application/json');
require_once __DIR__.'/connection.php';

$username = $conn->real_escape_string($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

if (!$username || !$password) {
  echo json_encode(['success'=>false,'message'=>'Missing username or password']);
  exit;
}

$hash = password_hash($password, PASSWORD_DEFAULT);
$stmt = $conn->prepare("INSERT INTO users (username, password_hash) VALUES (?, ?)");
$stmt->bind_param('ss', $username, $hash);
if ($stmt->execute()) {
  echo json_encode(['success'=>true,'message'=>'Registered']);
} else {
  echo json_encode(['success'=>false,'message'=>$stmt->error]);
}
$stmt->close();
?>
