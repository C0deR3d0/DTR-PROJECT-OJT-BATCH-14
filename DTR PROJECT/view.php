<?php
include 'connect.php';

if (isset($_GET['employeeID'])) {
    $employeeID = intval($_GET['employeeID']);
    
    $stmt = $conn->prepare("SELECT logID, employeeID, timeStamp FROM records WHERE employeeID = ? ORDER BY timeStamp DESC");
    $stmt->bind_param("i", $employeeID);
    $stmt->execute();
    $result = $stmt->get_result();

    $rows = $result->fetch_all(MYSQLI_ASSOC);

    header('Content-Type: application/json');
    echo json_encode($rows);
} else {
    echo json_encode(["error" => "employeeID not provided"]);
}
