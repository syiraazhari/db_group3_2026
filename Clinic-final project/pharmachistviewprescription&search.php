<?php
require("../config/db.php");

if (isset($_POST['search'])) {
    $search = "%" . $_POST['search'] . "%";

    $stmt = $pdo->prepare("
        SELECT PR.*, P.PatientName
        FROM PRESCRIPTION PR
        JOIN PATIENT P ON PR.PatientID = P.PatientID
        WHERE PR.MedicationDetails LIKE ?
    ");
    $stmt->execute([$search]);
} else {
    $stmt = $pdo->query("
        SELECT PR.*, P.PatientName
        FROM PRESCRIPTION PR
        JOIN PATIENT P ON PR.PatientID = P.PatientID
    ");
}

$data = $stmt->fetchAll();
?>

<form method="POST">
<input name="search" placeholder="Search medication">
<button>Search</button>
</form>

<?php foreach ($data as $row) { ?>
    <p>
        <?= $row['PatientName'] ?> |
        <?= $row['MedicationDetails'] ?> |
        <?= $row['DispensingStatus'] ?>
        <a href="update.php?id=<?= $row['PrescriptionID'] ?>">Dispense</a>
    </p>
<?php } ?>