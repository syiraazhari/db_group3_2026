<?php
require("../config/db.php");

$stmt = $pdo->query("
    SELECT Q.*, P.PatientName
    FROM QUEUE Q
    JOIN PATIENT P ON Q.PatientID = P.PatientID
    WHERE Q.QueueStatus='Waiting'
    ORDER BY Q.QueueNumber ASC
");

$data = $stmt->fetchAll();

foreach ($data as $row) {
    echo $row['QueueNumber'] . " - " . $row['PatientName'] . "<br>";
}
?>