<?php
$employeeid = $_POST['employeeid'] ?? null;
$message = '';

$connection = new mysqli('localhost','root','','taxation');
if($connection->connect_error){
    die("Database connection failed: " . $connection->connect_error);
}

if ($employeeid !== null && is_numeric($employeeid)) {
    $stmt = $connection->prepare("DELETE FROM registration WHERE id = ?");
    $stmt->bind_param("i", $employeeid);
    if ($stmt->execute()) {
        $message = "Employee ID $employeeid deleted successfully.";
    } else {
        $message = "Error deleting record: " . $stmt->error;
    }
    $stmt->close();
} else {
    $message = "Invalid employee id provided.";
}

$connection->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deletion Result</title>
    <style>
        body{ font-family: Arial, sans-serif; padding: 20px; background-color: #f5f5f5; }
        .card{ max-width: 800px; margin: 40px auto; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); }
        .btn{ display:inline-block; margin-top: 12px; padding: 10px 16px; background:#162938; color:#fff; border-radius:6px; text-decoration:none; }
    </style>
</head>
<body>
    <div class="card">
        <h2>Deletion Result</h2>
        <p><?php echo htmlspecialchars($message); ?></p>
        <a class="btn" href="display_data.php">View Registered Employees</a>
        <a class="btn" href="../index.html">Return Home</a>
    </div>
</body>
</html>