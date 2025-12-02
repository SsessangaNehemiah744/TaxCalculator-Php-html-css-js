<?php
$Name = $_POST['name'] ?? '';
$GrossSalary = $_POST['grosssalary'] ?? 0;

$PAYE = PAYEcalculator($GrossSalary);
$NSSF = NSSFcalculator($GrossSalary);
$NETINCOME = $GrossSalary - ($PAYE + $NSSF);

function PAYEcalculator($GrossSalary){
    switch(true){
        case ($GrossSalary > 410000):
            $excess = $GrossSalary - 410000;
            $PAYE = ($excess * 0.3) + 45500;
            break;
        case ($GrossSalary > 235000 && $GrossSalary <= 410000):
            $PAYE = ($GrossSalary * 0.2) + 10500;
            break;
        case ($GrossSalary > 130000 && $GrossSalary <= 235000):
            $PAYE = ($GrossSalary * 0.1);
            break;
        default:
            $PAYE = 0;
    }
    return $PAYE;
}

function NSSFcalculator($GrossSalary){
    $Employeecontribution = 0.1 * $GrossSalary;
    $Employercontribution = 0.05 * $GrossSalary;

    $NSSF = $Employeecontribution + $Employercontribution;

    return $NSSF;
}

$connection = new mysqli('localhost','root','','taxation');
if ($connection-> connect_error){
    die("Database connection failed: " . $connection->connect_error);
}

$statement = $connection->prepare("INSERT INTO registration (EmployeeName,GrossSalary,PAYE,NSSF,NETINCOME) VALUES (?,?,?,?,?)");
if (!$statement) {
    $error = $connection->error;
    $connection->close();
    die("Prepare failed: " . htmlspecialchars($error));
}
$statement->bind_param("sdddd", $Name, $GrossSalary, $PAYE, $NSSF, $NETINCOME);
$execval = $statement->execute();
$result = $execval ? "Registration Successful" : "Insert failed: " . htmlspecialchars($statement->error ?: $connection->error);
$statement->close();
$connection->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submission Result</title>
    <style>
    *{ margin:0; padding: 0; box-sizing: border-box; font-family:'Poppins',sans-serif; }
    body{ display: flex; justify-content: center; align-items: center; min-height: 100vh; background: url("../assets/Bookkeeping-Payroll-.png") no-repeat; background-size: cover; background-position: center; }
    header { position: fixed; top: 0; left: 0; width: 100%; padding: 20px 100px; background: rgb(2, 4, 19); display: flex; justify-content: space-between; align-items: center; z-index: 99; }
    .logo{ font-size:2em; color: #fff; user-select: none; }
    .navigation a { position: relative; font-size: 1.1em; color: #fff; text-decoration: none; font-weight: 500; margin-left: 40px; }
    .wrapper{ position: relative; width: 400px; height: auto; background: transparent; border: 2px solid rgba(255,255,255 .5s); border-radius: 20px; backdrop-filter: blur(20px); box-shadow: 0 0 30px rgba(0,0,0 .5s); display: flex; justify-content: center; align-items: center; font-size: 1.1em; color: #fff; font-weight: 500; margin-left:40px; padding: 20px; }
    .btn{ width: 150px; height: 45px; background: #162938; border:none; outline: none; border-radius: 6px; cursor: pointer; font-size: 1em; color: #fff; font-weight: 500; }
    .btn:hover{ background: #098b10; }
    </style>
</head>
<body>
<header>
    <h2 class="logo">Employee Details</h2>
    <nav class="navigation">
        <a href="display_data.php">Review Registered Employees</a>
    </nav>
</header>
<div class="wrapper">
    <div>
    <?php
    echo "<strong>Tax Calculation Results</strong><br><br>";
    echo "Employee Name: " . htmlspecialchars($Name) . "<br>";
    echo "Gross Salary: " . htmlspecialchars(number_format($GrossSalary, 2)) . "<br><br>";
    echo "PAYE (Tax): " . htmlspecialchars(number_format($PAYE, 2)) . "<br>";
    echo "NSSF (Contribution): " . htmlspecialchars(number_format($NSSF, 2)) . "<br>";
    echo "Net Income: " . htmlspecialchars(number_format($NETINCOME, 2)) . "<br><br>";
    echo "<strong>" . htmlspecialchars($result) . "</strong>";
    ?>
    <br><br>
    <a href="../index.html"><button class="btn">Return Home</button></a>
    </div>
</div>

</body>
</html>