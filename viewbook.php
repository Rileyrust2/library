<?php include "databases.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Books</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>Books in Library</h2>

    <!-- Search & Filter Form -->
    <form method="get">
        <input type="text" name="search" placeholder="Search by title or author" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
        <select name="genre">
            <option value="">All Genres</option>
            <option value="history" <?php if(isset($_GET['genre']) && $_GET['genre']=="history") echo "selected"; ?>>History</option>
            <option value="science" <?php if(isset($_GET['genre']) && $_GET['genre']=="science") echo "selected"; ?>>Science</option>
            <option value="fiction" <?php if(isset($_GET['genre']) && $_GET['genre']=="fiction") echo "selected"; ?>>Fiction</option>
        </select>
        <button type="submit">Search</button>
    </form>

    <table>
        <tr>
            <th>ID</th><th>Title</th><th>Author</th><th>Genre</th><th>Year</th><th>Publisher</th><th>Copies</th>
        </tr>
        <?php
        // Build query with search and filter
        $sql = "SELECT * FROM book WHERE 1";
        if (!empty($_GET['search'])) {
            $search = "%" . $conn->real_escape_string($_GET['search']) . "%";
            $sql .= " AND (title LIKE '$search' OR author LIKE '$search')";
        }
        if (!empty($_GET['genre'])) {
            $genre = $conn->real_escape_string($_GET['genre']);
            $sql .= " AND genre = '$genre'";
        }
        $sql .= " ORDER BY id DESC";

        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['title']}</td>
                        <td>{$row['author']}</td>
                        <td>{$row['genre']}</td>
                        <td>{$row['publication_year']}</td>
                        <td>{$row['publisher']}</td>
                        <td>{$row['copies']}</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='7'>No books found.</td></tr>";
        }
        ?>
    </table>
</div>
</body>
</html>