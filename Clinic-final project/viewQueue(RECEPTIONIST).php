<?php
$con = mysqli_connect('localhost','root','','florist_db');
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

$qry = mysqli_query($con, "SELECT * FROM customer_tbl");
?>

<!DOCTYPE html>
<html>
<head>
<title>Customer List</title>
<style>
    table{ 
		border-collapse: collapse; 
		width: 80%; 
		margin: auto; 
	}
    th, td{ 
		border: 1px solid black; 
		padding: 8px; 
		text-align: 
		center; 
	}
    th{ 
		background-color: #f2f2f2; 
	}
</style>
</head>
<body>
    <h1>Florist Customer List</h1>
    <form action="registerCustomer.php" method="POST">
        <input type="submit" value="Add New Customer">
    </form>
    <br>
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Contact No.</th>
            <th>Username</th>
            <th>Password</th>
            <th colspan="2">Action</th>
        </tr>
        <?php
        while ($row = mysqli_fetch_assoc($qry)) {
            echo "<tr>";
            echo "<td>".$row['id']."</td>";
            echo "<td>".$row['customer_name']."</td>";
            echo "<td>".$row['contact_no']."</td>";
            echo "<td>".$row['Username']."</td>";
            echo "<td>".$row['Password']."</td>";
            echo "<td>
                    <form action='updateCustomer.php' method='POST'>
                        <input type='hidden' name='id' value='".$row['id']."'>
                        <input type='submit' value='Edit'>
                    </form>
                  </td>";
            echo "<td>
                    <form action='deleteCustomer.php' method='POST'>
                        <input type='hidden' name='id' value='".$row['id']."'>
                        <input type='submit' value='Delete'>
                    </form>
                  </td>";
            echo "</tr>";
        }
        ?>
    </table>
</body>
</html>

<?php mysqli_close($con); ?>