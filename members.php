<?php
// members.php
require 'db.php';
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $id = intval($_POST['id']);
    $stmt = $mysqli->prepare("DELETE FROM members WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    $msg = "Member deleted.";
}
$members = $mysqli->query("SELECT * FROM members ORDER BY created_at DESC");
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Members</title></head>
<body>
  <a href="index.php">? Dashboard</a> | <a href="add_member.php">Add Member</a>
  <link rel="stylesheet" href="assets/style.css">

  <h2>Members</h2>
  <?php if($msg) echo "<p style='color:green;'>$msg</p>"; ?>
  <table border="1" cellpadding="6">
    <tr><th>ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Actions</th></tr>
    <?php while($m = $members->fetch_assoc()): ?>
      <tr>
        <td><?php echo $m['id'] ?></td>
        <td><?php echo htmlspecialchars($m['name']) ?></td>
        <td><?php echo htmlspecialchars($m['email']) ?></td>
        <td><?php echo htmlspecialchars($m['phone']) ?></td>
        <td>
          <a href="view_member.php?id=<?php echo $m['id'] ?>">View</a>
          <form style="display:inline" method="post" onsubmit="return confirm('Delete member?')">
            <input type="hidden" name="id" value="<?php echo $m['id'] ?>">
            <input type="hidden" name="action" value="delete">
            <button type="submit">Delete</button>
          </form>
        </td>
      </tr>
    <?php endwhile; ?>
  </table>
</body>
</html>
