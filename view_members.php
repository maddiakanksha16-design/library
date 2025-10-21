<?php
// view_member.php
require 'db.php';
$id = intval($_GET['id'] ?? 0);
if (!$id) { header('Location: members.php'); exit; }
$stmt = $mysqli->prepare("SELECT * FROM members WHERE id = ?");
$stmt->bind_param("i",$id); $stmt->execute();
$member = $stmt->get_result()->fetch_assoc(); $stmt->close();
$issues = $mysqli->prepare("SELECT i.*, b.title FROM issues i JOIN books b ON b.id=i.book_id WHERE i.member_id=? ORDER BY i.issue_date DESC");
$issues->bind_param("i",$id); $issues->execute();
$iss_res = $issues->get_result();
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Member Details</title></head>
<body>
  <a href="members.php">? Back</a>
  <link rel="stylesheet" href="assets/style.css">

  <h2><?php echo htmlspecialchars($member['name']) ?></h2>
  <p>Email: <?php echo htmlspecialchars($member['email']) ?></p>
  <p>Phone: <?php echo htmlspecialchars($member['phone']) ?></p>
  <p>Address: <?php echo nl2br(htmlspecialchars($member['address'])) ?></p>

  <h3>Borrowing History</h3>
  <table border="1" cellpadding="6">
    <tr><th>Book</th><th>Issue Date</th><th>Due</th><th>Return</th><th>Status</th></tr>
    <?php while($r = $iss_res->fetch_assoc()): ?>
      <tr>
        <td><?php echo htmlspecialchars($r['title']) ?></td>
        <td><?php echo $r['issue_date'] ?></td>
        <td><?php echo $r['due_date'] ?></td>
        <td><?php echo $r['return_date'] ?? '-' ?></td>
        <td><?php echo $r['status'] ?></td>
      </tr>
    <?php endwhile; ?>
  </table>
</body>
</html>
