<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Check the user's credentials against a database or some other data source
    if ($username == 'adithyakrishnapn' && $password == '7560989659dragoncorex') {
        // If the credentials are valid, set a session variable to indicate that the user is logged in
        $_SESSION['loggedin'] = true;
        
        // Redirect the user to a restricted page
        header('Location: index.php');
        exit();
    } else {
        // If the credentials are invalid, display an error message
        $error = 'Invalid username or password';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Security</title>
    <link rel="icon" type="image/x-icon" href="img/logo.jpg">

    <style>
        body {
  background-color: orange;
  font-family: Arial, sans-serif;
  font-size: 16px;
  line-height: 1.5;
  margin: 0;
  padding: 0;
}

.container {
  position: fixed;
  top: 50%;
  left: 50%;
  transform: translate(-50%,-50%);
  width: 70%;
  margin: 0 auto;
  padding: 20px;
}

form {
  background-color: #fff;
  border-radius: 5px;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
  padding: 20px;
}

form input[type="text"],
form input[type="password"],
form input[type="submit"] {
  border: 1px solid #ccc;
  border-radius: 3px;
  box-sizing: border-box;
  font-size: 16px;
  margin: 10px 0;
  padding: 10px;
  width: 100%;
}

form input[type="submit"] {
  background-color: #4CAF50;
  border: none;
  color: #fff;
  cursor: pointer;
  font-size: 16px;
  font-weight: bold;
  padding: 10px;
  text-transform: uppercase;
  transition: background-color 0.2s ease-in-out;
}

form input[type="submit"]:hover {
  background-color: #3e8e41;
}

    </style>
</head>
<body>
    <?php if (isset($error)) { ?>
    <p><?php echo $error; ?></p>
    <?php } ?>
    <div class="container">
    <form method="post">
        <input type="text" name="username" placeholder="Username" id="username"><br>
        <input type="password" name="password" placeholder="Password" id="username"><br>
        <input type="submit" value="Log in">
    </form>
    </div>
    
</body>
</html>