<?php // Do not put any HTML above this line
session_start();

if ( isset($_POST['cancel'] ) ) {
    header("Location: logout.php");
    return;
}

$salt = 'XyZzy12*_';
$stored_hash = '1a52e17fa899cf40fb04cfc42e6352f1';  // Pw is php123

// Check to see if we have some POST data, if we do process it
if ( isset($_POST["email"]) && isset($_POST['pass']) ) {
    if ( strlen($_POST["email"]) < 1 || strlen($_POST['pass']) < 1 ) {
        $failure = "User name and password are required";
    } elseif (strpos($_POST["email"], '@') == false) {
                     $failure =  "Email must contain '@' symbol";
	} else {
		unset($_SESSION["email"]);  // Logout current user
        $check = hash('md5', $salt.$_POST['pass']);
        if ( $check == $stored_hash ) {
			$_SESSION["email"] = $_POST["email"];
			error_log("Login success ".$_POST["email"]);
			
            // Redirect the browser to view.php
            header("Location: index.php");			
            return;
        } else {
            $_SESSION["error"] = "Incorrect password.";
			error_log("Login fail ".$_POST["email"]." $check");
            header( "Location: login.php" ) ;
            return;			
        }
    }
}

?>
<!DOCTYPE html>
<html>
<head>
<?php require_once "bootstrap.php"; ?>
<title>73574de5 Login Page</title>
</head>
<body>
<div class="container">
<h1>Please Log In</h1>

<?php
    if ( isset($_SESSION["error"]) ) {
        echo('<p style="color:red">'.$_SESSION["error"]."</p>\n");
        unset($_SESSION["error"]);
    }
?>

<form method="POST">
<label for="email">User Name</label>
<input type="text" name="email" id="email"><br/>
<label for="pass">Password</label>
<input type="text" name="pass" id="pass"><br/>
<input type="submit" value="Log In">
<a href="index.php">Cancel</a>
</form>
<p>
For a password hint, view source and find a password hint
in the HTML comments.
</p>
<!--- Password is php123 -->
</div>
</body>
