<?php
require_once 'database.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $per_day = (int) $_POST['per_day'];

    if (!empty($title) && $per_day > 0) {
        try {
            $sql = "INSERT INTO daily_task (title, per_day) VALUES (:title, :per_day)";
            $stmt = $pdo->prepare($sql);
            
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':per_day', $per_day, PDO::PARAM_INT);
            
            if ($stmt->execute()) {
                $message = "<p style='color: green;'>Task '<strong>" . htmlspecialchars($title) . "</strong>' added successfully!</p>";
            }
        } catch (\PDOException $e) {
            $message = "<p style='color: red;'>Database error: " . htmlspecialchars($e->getMessage()) . "</p>";
        }
    } else {
        $message = "<p style='color: red;'>Please provide a valid title and frequency.</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TaskMaster - Add Daily Task</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 500px; margin: 40px auto; padding: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; font-weight: bold; margin-bottom: 5px; }
        input[type="text"], input[type="number"] { width: 100%; padding: 8px; box-sizing: border-box; }
        button { padding: 10px 15px; background-color: #007BFF; color: white; border: none; cursor: pointer; }
        button:hover { background-color: #0056b3; }
    </style>
</head>
<body>
    <h1>Add a New Daily Task</h1>
    
    <?= $message ?>

    <form action="index.php" method="POST">
        <div class="form-group">
            <label for="title">Task Name:</label>
            <input type="text" id="title" name="title" required placeholder="e.g., Drink water">
        </div>

        <div class="form-group">
            <label for="per_day">Times per day:</label>
            <input type="number" id="per_day" name="per_day" min="1" value="1" required>
        </div>

        <button type="submit">Save Task</button>
    </form>
</body>
</html>