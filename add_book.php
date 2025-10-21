<?php
// add_book.php
require 'db.php';
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $isbn = trim($_POST['isbn']);
    $title = trim($_POST['title']);
    $author = trim($_POST['author']);
    $publisher = trim($_POST['publisher']);
    $year = intval($_POST['year']) ?: null;
    $copies = intval($_POST['copies']) ?: 1;
    $available = $copies;

    $stmt = $mysqli->prepare("INSERT INTO books (isbn, title, author, publisher, year, copies, available) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssiii", $isbn, $title, $author, $publisher, $year, $copies, $available);
    if ($stmt->execute()) {
        $msg = "Book added successfully.";
    } else {
        $msg = "Error: " . $stmt->error;
    }
    $stmt->close();
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Add Book</title></head>
<body>
  <a href="books.php">? Back to books</a>
  <link rel="stylesheet" href="assets/style.css">

  <h2>Add Book</h2>
  <?php if($msg) echo "<p style='color:green;'>$msg</p>"; ?>
  <form method="post">
    <label>ISBN<br><input name="isbn"></label><br><br>
    <label>Title<br><input name="title" required></label><br><br>
    <label>Author<br><input name="author"></label><br><br>
    <label>Publisher<br><input name="publisher"></label><br><br>
    <label>Year<br><input name="year" type="number" min="1900" max="2100"></label><br><br>
    <label>Copies<br><input name="copies" type="number" min="1" value="1"></label><br><br>
    <button type="submit">Add Book</button>
  </form>
</body>
</html>
