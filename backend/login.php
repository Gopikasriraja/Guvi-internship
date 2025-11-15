<?php
header('Content-Type: application/json');
require_once __DIR__.'/connection.php';

// Accept username OR email as login identifier
$username = trim($_POST['username'] ?? '');
if (!$username) {
  $username = trim($_POST['email'] ?? '');
}
$password = $_POST['password'] ?? '';

if (!$username || !$password) {
  echo json_encode(['success'=>false,'message'=>'Missing username/email or password']);
  exit;
}

$stmt = $conn->prepare("SELECT id, password_hash FROM users WHERE username = ? LIMIT 1");
$stmt->bind_param('s', $username);
$stmt->execute();
$res = $stmt->get_result();
if ($res && $res->num_rows === 1) {
  $row = $res->fetch_assoc();
  if (password_verify($password, $row['password_hash'])) {
    echo json_encode(['success'=>true,'message'=>'Login success']);
  } else {
    echo json_encode(['success'=>false,'message'=>'Invalid credentials']);
  }
} else {
  echo json_encode(['success'=>false,'message'=>'User not found']);
}
$stmt->close();
?>
