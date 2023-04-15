<?php
session_start();

// Check whether the user is logged in
if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
    // If the user is not logged in, redirect them back to the login page
    header('Location: login.php');
    exit();
}
?>

<?php
$dir = 'uploads/invoice/ingst/';
$files = array_diff(scandir($dir), array('..', '.'));
$search = isset($_GET['search']) ? $_GET['search'] : '';

if ($search) {
    $filtered_files = array_filter($files, function($file) use ($search) {
        // case-insensitive match for single word
        return preg_match('/\b' . preg_quote($search, '/') . '\b/i', $file);
    });
} else {
    $filtered_files = $files;
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Download PDFs</title>
    <link rel="stylesheet" type="text/css" href="./style.css">
    <link rel="icon" type="image/x-icon" href="img/logo.jpg">
    <style>
      body{
        background-color: orange;
      }
        /* main container */
.container {
  max-width: 800px;
  margin: auto;
}

/* search form */
.search-form {
  margin: 20px 0;
  display: flex;
  justify-content: center;
}

.search-input {
  width: 300px;
  padding: 10px;
  border-radius: 5px;
  border: 1px solid #ccc;
  font-size: 16px;
}

.search-button {
  background-color: #4CAF50;
  color: white;
  border: none;
  border-radius: 5px;
  padding: 10px;
  margin-left: 10px;
  cursor: pointer;
  font-size: 16px;
}

/* PDF list */
.pdf-list {
  margin-top: 20px;
}

.pdf-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 5px;
  margin-bottom: 10px;
}

.pdf-name {
  font-size: 18px;
}

.delete-button {
  background-color: #f44336;
  color: white;
  border: none;
  border-radius: 5px;
  padding: 10px;
  cursor: pointer;
  font-size: 16px;
}
  .pdf-item {
  border: 1px solid black;
  padding: 10px;
  margin-bottom: 10px;
}

.pdf-item a {
  font-weight: bold;
}

.pdf-item p {
  margin: 0;
}

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


    <h1 style="text-align: center; background-colo: white;">SEE ALL PDF's</h1><br>
    <div class="container">
		<a href="download.php" class="btn">GST Included</a>
		<a href="in_download.php" class="btn">GST Excluded</a>
	</div><br>
  <h3 style="text-align: center; color: white;">GST INCLUDED</h3><br>
    <form action="download.php" method="get">
        <input type="text" name="search" placeholder="Search for PDFs">
        <button type="submit">Search</button>
    </form>
    <?php if ($filtered_files): ?>
        <ul>
        <?php foreach ($filtered_files as $file) { ?>
  <div class="pdf-item">
    <a href="uploads/invoice/ingst/<?php echo $file; ?>" target="_blank"><?php echo $file; ?></a>
    <form action="delete.php" method="POST">
      <input type="hidden" name="filename" value="<?php echo $file; ?>">
      <button type="submit">Delete</button>
    </form>
  </div>
<?php } ?>

        </ul>
    <?php else: ?>
        <p>No PDFs found.</p>
    <?php endif; ?>

</body>
</html>
