
<?php
include 'connect.php';

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

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Registration</title>
    <link rel="stylesheet" href="style.css">
    <style>
table, th, td {
  border: 1px solid black;
}
</style>
</head>

<body>
   <header>
        <div class="logo">
            <img src="image/miller logo.svg" alt="University Logo">
            <h1><span>Miller</span> Solution </span> Development </span> Inc.</h1>
        </div>
        <nav class="navigation">
            <button><i class="fas fa-sign-in-alt"></i> BACK</button>
        </nav>
   </header>
<div class="month">
<h3>Name of the Intern:</h3>
<h3>For the month of:</h3>
<h3>Official Hours for (Regular Days) </h3>

    <table style="width:100%">
        <tr>
        <th rowspan="2">Day</th>
        <th colspan="2">AM</th>
        <th colspan="2">PM</th>
        <th rowspan="2"> Undertime Hrs/Mins </th>
        </tr>

        <tr>
            <th class="AM_arrival">Arrival</th>
            <th class="AM_departure">Departure</th>
            <th class="PM_arrival">Arrival</th>
            <th class="PM_departure">Departure</th>
        </tr>
        <td>
        
        </td>
        </div>

    </table>

      <script>
    function loadDTR() {
        let id = document.getElementById("employeeID").value;
        fetch("view_dtr.php?employeeID=" + id)
            .then(res => res.json())
            .then(data => {
                let tbody = document.querySelector("#dtrTable tbody");
                tbody.innerHTML = "";
                data.forEach(row => {
                    let tr = document.createElement("tr");
                    tr.innerHTML = `<td>${row.logID}</td><td>${row.employeeID}</td><td>${row.timeStamp}</td>`;
                    tbody.appendChild(tr);
                });
            });
    }
    </script>
    
</body>

