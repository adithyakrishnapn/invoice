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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="style.css" rel="stylesheet" type="text/css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <title>HOME</title>
    <link rel="icon" type="image/x-icon" href="img/logo.jpg">
</head>
<body style="background-color: orange; margin: 0%;">
    <nav>
		<div class="nav-left">
			<a href="index.php">Home</a>
		</div>
		<div class="nav-right">
			<a href="" style="padding-right: 20px;">PDF</a>
			<a href="invoices.php">INVOICES</a>
		</div>
	</nav>


    <div class="select p-3">
        <h2 class="pt-2 pb-2" style="text-align: center; background-color: rgba(0, 0, 0, 0.753); color: white;">VIEW DOWNLOADED PDF's</h2>
        <div class="container-fluid">
            <div class="row">
                <div class="iq col-6 p-3">
                    <h5 style="text-align: center; color: white;">INVOICE</h5><BR>
                        <center><a href="download.php" class="btn btn-warning">ENTER</a></center>
                </div>
                <div class="iq col-6 p-3">
                    <h5 style="text-align: center; color: white;">QUOTATION</h5><BR>
                        <center><a href="quodownload.php" class="btn btn-warning">ENTER</a></center>
                </div>
            </div>
        </div>
    </div>
</body>
</html>