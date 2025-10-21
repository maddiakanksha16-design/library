<?php
// issues.php
require 'db.php';
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'return') {
        $issue_id = intval($_POST['issue_id']);
        // mark return_date and status; increase book available
        $stmt = $mysqli->prepare("UPDATE issues SET return_date = CURDATE(), status = 'returned' WHERE id = ? AND status='issued'");
        $stmt->bind_param("i", $issue_id);
        $stmt->execute();
        if ($stmt->affected_rows) {
            // increment book available
            $stmt2 = $mysqli->prepare("SELECT book_id FROM issues WHERE id = ?");
            $stmt2->bind_param("i", $issue_id);
            $stmt2->execute();
            $bid = $stmt2->get_result()->fetch_assoc()['book_id'];
            $stmt2->close();
            $stmt3 = $mysqli->prepare("UPDATE books SET available = available + 1 WHERE id = ?");
            $stmt3->bind_param("i", $bid);
            $stmt3->execute();
            $stmt3->close();
            $msg = "Book returned.";
        }
        $stmt->close();
    }
}
$issues = $mysqli->query("SELECT i.*, b.title AS book_title, m.name AS member_name FROM issues i
  JOIN books b ON b.id = i.book_id
  JOIN members m ON m.id = i.member_id
  ORDER BY i.created_at DESC");
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Issues</title></head>
<body>
  <a href="index.php">? Dashboard</a> | <a href="issue_book.php">Issue Book</a>
  <link rel="stylesheet" href="assets/style.css">

  <h2>Issue / Return Records</h2>
  <?php if($msg) echo "<p style='color:green;'>$msg</p>"; ?>
  <table border="1" cellpadding="6">
    <tr><th>ID</th><th>Book</th><th>Member</th><th>Issue Date</th><th>Due Date</th><th>Return Date</th><th>Status</th><th>Action</th></tr>
    <?php while($r = $issues->fetch_assoc()): ?>
      <tr>
        <td><?php echo $r['id'] ?></td>
        <td><?php echo htmlspecialchars($r['book_title']) ?></td>
        <td><?php echo htmlspecialchars($r['member_name']) ?></td>
        <td><?php echo $r['issue_date'] ?></td>
        <td><?php echo $r['due_date'] ?></td>
        <td><?php echo $r['return_date'] ?? '-' ?></td>
        <td><?php echo $r['status'] ?></td>
        <td>
          <?php if($r['status'] === 'issued'): ?>
            <form method="post" style="display:inline" onsubmit="return confirm('Mark returned?')">
              <input type="hidden" name="action" value="return">
              <input type="hidden" name="issue_id" value="<?php echo $r['id'] ?>">
              <button type="submit">Return</button>
            </form>
          <?php else: echo '-'; endif; ?>
        </td>
      </tr>
    <?php endwhile; ?>
  </table>
</body>
</html>
