<?php
include 'db_connect.php';

if (isset($_GET['employeeID'])) {
    $employeeID = intval($_GET['employeeID']);
    
    $sql = "SELECT * FROM records WHERE employeeID = $employeeID ORDER BY timeStamp DESC";
    $result = $conn->query($sql);

    $rows = [];
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }

    header('Content-Type: application/json');
    echo json_encode($rows);
} else {
    echo json_encode(["error" => "employeeID not provided"]);
}
?>
