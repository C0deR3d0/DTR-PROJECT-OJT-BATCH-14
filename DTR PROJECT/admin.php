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