<?php
header('Content-Type: application/json');
require_once __DIR__.'/connection.php';

// Accept POST fields: username OR email (frontend may send email)
$username = trim($_POST['username'] ?? '');
if (!$username) {
  // try email if username not provided
  $username = trim($_POST['email'] ?? '');
}
// Optional: use full name if you want to store separately
$fullname = trim($_POST['fullname'] ?? $_POST['full_name'] ?? '');

$password = $_POST['password'] ?? '';

if (!$username || !$password) {
  echo json_encode(['success'=>false,'message'=>'Missing username/email or password']);
  exit;
}

// check if user already exists
$stmt = $conn->prepare("SELECT id FROM users WHERE username = ? LIMIT 1");
$stmt->bind_param('s', $username);
$stmt->execute();
$res = $stmt->get_result();
if ($res && $res->num_rows > 0) {
  echo json_encode(['success'=>false,'message'=>'User already exists']);
  exit;
}
$stmt->close();

$hash = password_hash($password, PASSWORD_DEFAULT);
$stmt = $conn->prepare("INSERT INTO users (username, password_hash) VALUES (?, ?)");
$stmt->bind_param('ss', $username, $hash);
if ($stmt->execute()) {
  echo json_encode(['success'=>true,'message'=>'Registered']);
} else {
  echo json_encode(['success'=>false,'message'=>'DB error: '.$stmt->error]);
}
$stmt->close();
?>
