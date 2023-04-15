<?php
session_start();

// Check whether the user is logged in
if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
    // If the user is not logged in, redirect them back to the login page
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Invoices Table</title>
    <link rel="stylesheet" type="text/css" href="./invoice.css">
	<link rel="icon" type="image/x-icon" href="img/logo.jpg">
</head>
<body>

<nav>
		<div class="nav-left">
			<a href="index.php">Home</a>
		</div>
		<div class="nav-right">
			<a href="sd.php" style="padding-right: 20px;">PDF</a>
			<a href="invoices.php">INVOICES</a>
		</div>
	</nav>


	<h1 style="text-align: center;">Invoices Table</h1>
	<div class="container">
		<a href="invoicesio.php" class="btn">GST Included</a>
		<a href="invoices_io.php" class="btn">GST Excluded</a>
	</div><br>
	<table>
		<thead>
			<tr>
                <th>Id</th>
				<th>Client Name</th>
				<th>Email</th>
				<th>Billing Address</th>
				<th>Total</th>
			</tr>
		</thead>
		<tbody>
			<?php
			// Connect to database
			$conn = mysqli_connect("localhost", "root", "", "db");

			// Check connection
			if (!$conn) {
			    die("Connection failed: " . mysqli_connect_error());
			}

			// Fetch data from table
			$sql = "SELECT id, client_name, email, address, total FROM ingst";
			$result = mysqli_query($conn, $sql);

			// Loop through the data and display in table
			if (mysqli_num_rows($result) > 0) {
			    while($row = mysqli_fetch_assoc($result)) {
			        echo "<tr>";
                    echo "<td>" . $row["id"] . "</td>";
			        echo "<td>" . $row["client_name"] . "</td>";
			        echo "<td>" . $row["email"] . "</td>";
			        echo "<td>" . $row["address"] . "</td>";
			        echo "<td>" . $row["total"] . "</td>";
			        echo "</tr>";
			    }
			} else {
			    echo "No invoices found.";
			}

			// Close database connection
			mysqli_close($conn);
			?>
		</tbody>
	</table>
</body>
</html>
