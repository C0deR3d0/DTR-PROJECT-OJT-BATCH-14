<?php
include 'connect.php'; 
$conn = new mysqli("localhost", "root", "", "dtr_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}



// Fetch all logs with employee info
$query = "SELECT 
            r.logID, 
            r.employeeID, 
            CONCAT(u.firstName, ' ', u.middleName, ' ', u.lastName) AS fullName,
            r.timeStamp
          FROM records r
          JOIN users u ON r.employeeID = u.employeeID
          ORDER BY r.timeStamp DESC";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>STBP UNIVER7</title>
    <link rel="stylesheet" href="style.css">

</head>
<body>

    <header>
        <div class="logo">
            <img src="image/miller logo.svg" alt="University Logo">
            <h1><span>Miller</span> Solution </span> Development </span> Inc.</h1>
        </div>
        <nav class="navigation">
            <button><i class="fas fa-sign-in-alt"></i> LogOut</button>
        </nav>
    </header>
    </body>
</html>