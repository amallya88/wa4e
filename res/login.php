<?php // Do not put any HTML above this line
session_start();
require_once "pdo.php";

if ( isset($_POST['cancel'] ) ) {
    header("Location: logout.php");
    return;
}

$salt = 'XyZzy12*_';

// Check to see if we have some POST data, if we do process it
if ( isset($_POST['email']) && isset($_POST['pass']) ) {
    if ( strlen($_POST['email']) < 1 || strlen($_POST['pass']) < 1 ) {
        $failure = "User name and password are required";
    } elseif (strpos($_POST["email"], '@') == false) {
        $failure =  "Email must contain '@' symbol";
	} else {
		unset($_SESSION["name"]);  
		unset($_SESSION["user_id"]);  // Logout current user
        $check = hash('md5', $salt.$_POST['pass']);
        $stmt = $pdo->prepare('SELECT user_id, name FROM users
			WHERE email = :em AND password = :pw');
		$stmt->execute(array( ':em' => $_POST['email'], ':pw' => $check));
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		if ( $row !== false ) {
			$_SESSION['name'] = $row['name'];
			$_SESSION['user_id'] = $row['user_id'];			
			
			// Redirect the browser to index.php
			error_log("Login success ".$_POST["email"]);
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
<script type="text/javascript" src="validate_login.js"></script>
<title>Akarsh Mallya Login Page</title>
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
<label for="email">Email</label>
<input type="text" name="email" id="email_1"><br/>
<label for="pass">Password</label>
<input type="password" name="pass" id="pass_1"><br/>
<input type="submit" onclick="return loginForm_doValidate('email_1', 'pass_1');" value="Log In">
<input type="submit" name="cancel" value="Cancel">
</form>
<p>
For a password hint, view source and find an account and password hint in the HTML comments.
</p>
<!--- The account is umsi@umich.edu Password is php123 -->
</div>
</body>
</html>
