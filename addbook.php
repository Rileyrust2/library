<?php include "databases.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Book</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>Add New Book</h2>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $title = trim($_POST["title"]);
        $author = trim($_POST["author"]);
        $genre = $_POST["genre"];
        $year = (int)$_POST["publication_year"];
        $publisher = trim($_POST["publisher"]);
        $copies = (int)$_POST["copies"];

        if ($title && $author && $genre && $year && $copies > 0 && $year <= date("Y")) {
            
            $check = $conn->prepare("SELECT id FROM book WHERE title = ?");
            $check->bind_param("s", $title);
            $check->execute();
            $check->store_result();

            if ($check->num_rows > 0) {
                echo "<p class='msg' style='color:red;'>A book with this title already exists!</p>";
            } else {
                $stmt = $conn->prepare("INSERT INTO book (title, author, genre, publication_year, publisher, copies) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("sssisi", $title, $author, $genre, $year, $publisher, $copies);
                if ($stmt->execute()) {
                    echo "<p class='msg' style='color:green;'>Book added successfully!</p>";
                } else {
                    echo "<p class='msg' style='color:red;'>Error: " . $conn->error . "</p>";
                }
            }
        } else {
            echo "<p class='msg' style='color:red;'>Invalid input. Please check your data.</p>";
        }
    }
    ?>
    <form method="post">
        <label>Title </label>
        <input type="text" name="title" required>
        <label>Author </label>
        <input type="text" name="author" required>
        <label>Genre </label>
        <select name="genre" required>
            <option value="">--Select Genre--</option>
            <option value="history">History</option>
            <option value="science">Science</option>
            <option value="fiction">Fiction</option>
        </select>
        <label>Publication Year </label>
        <input type="number" name="publication_year" required>
        <label>Publisher</label>
        <input type="text" name="publisher">
        <label>Copies </label>
        <input type="number" name="copies" value="1" min="1" required>
        <button type="submit">Add Book</button>
    </form>
</div>
</body>
</html>