<?php
// add_member.php
require 'db.php';
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);

    $stmt = $mysqli->prepare("INSERT INTO members (name, email, phone, address) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $phone, $address);
    if ($stmt->execute()) $msg = "Member added.";
    else $msg = "Error: " . $stmt->error;
    $stmt->close();
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Add Member</title></head>
<body>
  <a href="members.php">? Back</a>
  <link rel="stylesheet" href="assets/style.css">
  <h2>Add Member</h2>
  <?php if($msg) echo "<p style='color:green;'>$msg</p>"; ?>
  <form method="post">
    <label>Name<br><input name="name" required></label><br><br>
    <label>Email<br><input name="email" type="email"></label><br><br>
    <label>Phone<br><input name="phone"></label><br><br>
    <label>Address<br><textarea name="address"></textarea></label><br><br>
    <button type="submit">Add Member</button>
  </form>
</body>
</html>
