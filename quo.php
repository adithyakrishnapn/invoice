<?php
session_start();

// Check whether the user is logged in
if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
    // If the user is not logged in, redirect them back to the login page
    header('Location: login.php');
    exit();
}


//sql connection and insertion
include('config.php');


// Get the last invoice number from the database and increment it by 1
$sql = "SELECT MAX(id) as last_quotation_no FROM quotation";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
  $row = $result->fetch_assoc();
  $quotation_no = $row["last_quotation_no"] + 1;
} else {
  $quotation_no = 1;
}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Invoice Form</title>
	<link rel="stylesheet" type="text/css" href="./style.css">
	<link rel="icon" type="image/x-icon" href="img/logo.jpg">
	<style>
		.btn {
			display: inline-block;
			padding: 10px 20px;
			background-color: #4CAF50;
			color: white;
			text-align: center;
			text-decoration: none;
			font-size: 16px;
			border-radius: 5px;
			margin: 10px;
			border: none;
			cursor: pointer;
			box-shadow: 0px 3px 10px rgba(0,0,0,0.2);
			transition: background-color 0.3s ease;
		}

		.btn:hover {
			background-color: #3e8e41;
		}

		.container {
			display: flex;
			justify-content: center;
			align-items: center;
		}
	</style>
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


	<h1>QUOTATION FORM</h1>
	<div class="container">
		<a href="quo.php" class="btn">Include GST</a>
		<a href="quo_main.php" class="btn">Exclude GST</a>
	</div>
	<h3 style="text-align: center; color: white;">GST INCLUDED</h3>
	<form id="invoice-form" action="quopdf.php" method="POST">
	    <label for="quotation_type">Select Quotation Type:</label>
        <select id="quotation_type" name="quotation_type">
        <option value="sales">Sales Quotation</option>
        <option value="service">Service Quotation</option>
        </select>
        <br>
        <input type="hidden" name="pdf-data" id="pdf-data">
		<label for="invoice_no">Quotation Number:</label>
        <input type="text" name="invoice_no" value="<?php echo $quotation_no; ?>" readonly><br><br>
		<label for="client-name">Client Name:</label>
		<input type="text" id="client" name="client" required>
		<div>
			<label for="email">Email:</label>
			<input type="email" id="email" name="mail">
		</div>
		
		<label for="invoice-date">Quotation Date:</label>
		<input type="date" id="invoice-date" name="invoice-date" required>

		<label for="address">Customer-Address:</label>
        <textarea id="address" name="address" required></textarea>
      </div>

	  <div style="background-color: grey; padding: 15px; border-radius: 0.2cm;">
  <h2>Terms and Conditions</h2>
  <ul id="termsList">
  <li><input type="text" name="term[]" value="Term 1"><button type="button" class="removeTermBtn">Remove</button></li>
  <li><input type="text" name="term[]" value="Term 2"><button type="button" class="removeTermBtn">Remove</button></li>
</ul>
<button type="button" id="addTermBtn">Add New Term</button>

</div>


 <div class="product-section">
    <h2>Products</h2>
	<label for="gst">Include GST:</label>
		<div class="radio-buttons">
			<input type="radio" id="yes" name="gst" value="yes">
			<label for="yes">Yes</label>
			<input type="radio" id="no" name="gst" value="no" checked>
			<label for="no">No</label>
		</div><br>
    <table>
      <tbody>
        <tr class="product-row">
          <td><input type="text" class="product-input" name="product[]" placeholder="Product Name" required></td>
          <td><input type="number" class="product-input" name="quantity[]" min="1" placeholder="Quantity" required></td>
          <td><input type="number" class="product-input" name="price[]" min="0" step="0.01" placeholder="Price" required></td>
          <td class="product-total"></td>
          <td><button type="button" class="remove-product">Remove</button></td>
        </tr>
      </tbody>
    </table>

		<button type="button" id="add-product">Add New Product</button>

		<div class="total-section">
			<label>Total:</label>
			<span id="total-amount">Rs 0.00</span>
		</div>

		<button type="submit">Submit</button>
	</form>

	<script src="script.js"></script>
</body>
</html>