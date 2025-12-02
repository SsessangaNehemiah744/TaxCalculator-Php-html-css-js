<?php
$id = $_POST['id'] ?? null;
$field = $_POST['field'] ?? null;
$updateValue = $_POST['update'] ?? null;
$message = '';

$allowedFields = ['EmployeeName','GrossSalary','PAYE','NSSF','NETINCOME'];
if (!$id || !is_numeric($id) || !$field || !$updateValue) {
    $message = 'Invalid input provided.';
} elseif (!in_array($field, $allowedFields, true)) {
    $message = 'Invalid field selected.';
} else {
    $connection = new mysqli('localhost','root','','taxation');
    if ($connection->connect_error) {
        die('Database connection failed: ' . $connection->connect_error);
    }

    if ($field === 'GrossSalary') {
        $gross = floatval($updateValue);
        // Recalculate dependent values
        $tax = 0;
        if ($gross > 410000) {
            $tax = (($gross - 410000) * 0.3) + 45500;
        } elseif ($gross > 235000) {
            $tax = ($gross * 0.2) + 10500;
        } elseif ($gross > 130000) {
            $tax = ($gross * 0.1);
        }
        $nssf = ($gross * 0.1) + ($gross * 0.05);
        $net = $gross - ($tax + $nssf);

        // Use prepared statements to update fields
        $stmt = $connection->prepare('UPDATE registration SET GrossSalary = ?, PAYE = ?, NSSF = ?, NETINCOME = ? WHERE id = ?');
        $stmt->bind_param('ddddi', $gross, $tax, $nssf, $net, $id);
        $ok = $stmt->execute();
        $stmt->close();
        $message = $ok ? 'Record updated successfully (GrossSalary and dependent fields recalculated).' : 'Update failed: ' . $connection->error;

    } else {
        // Single-field update using prepared statement
        $sql = "UPDATE registration SET $field = ? WHERE id = ?";
        $stmt = $connection->prepare($sql);
        if ($field === 'EmployeeName') {
            $stmt->bind_param('si', $updateValue, $id);
        } else {
            // Numeric fields
            $numVal = floatval($updateValue);
            $stmt->bind_param('di', $numVal, $id);
        }
        $ok = $stmt->execute();
        $stmt->close();
        $message = $ok ? 'Record updated successfully.' : 'Update failed: ' . $connection->error;
    }

    $connection->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Update Result</title>
    <style>
        body{ font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5; }
        .card{ max-width:800px; margin:40px auto; background:#fff; padding:20px; border-radius:8px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); }
        .btn{ display:inline-block; margin-top:12px; padding:10px 16px; background:#162938; color:#fff; border-radius:6px; text-decoration:none; }
    </style>
</head>
<body>
    <div class="card">
        <h2>Update Result</h2>
        <p><?php echo htmlspecialchars($message); ?></p>
        <a class="btn" href="display_data.php">View Registered Employees</a>
        <a class="btn" href="../index.html">Return Home</a>
    </div>
</body>
</html>