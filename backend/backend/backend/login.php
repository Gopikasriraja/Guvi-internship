<?php
header('Content-Type: application/json');
require_once _DIR_.'/connection.php';

$username = $conn->real_escape_string($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

if (!$username || !$password) {
  echo json_encode(['success'=>false,'message'=>'Missing username or password']);
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
