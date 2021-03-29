<?php
session_start();
require_once "pdo.php";

if ( ! isset($_SESSION["email"]) ) {
    die('ACCESS DENIED');
}

// If the user requested logout go back to index.php
if ( isset($_POST['cancel']) ) {
    header('Location: index.php');
    return;
}

if ( isset($_POST['add']) ) {
	if 	(	empty($_POST['make']) ||
			empty($_POST['model']) || 
			empty($_POST['year']) ||
			empty($_POST['mileage'])
	) {
		$_SESSION["error"] = "All fields are required";
	} else {
		if ( !is_numeric($_POST['year']) ) $_SESSION["error"] = "Year must be numeric";
		else if ( !is_numeric($_POST['mileage']) ) $_SESSION["error"] = "Mileage must be numeric";
		else {	
			$sql = "INSERT INTO autos (make, model, year, mileage) 
					  VALUES (:make, :model, :year, :mileage)";
			
			$stmt = $pdo->prepare($sql);
			$count = $stmt->execute(array(
				':make' => $_POST['make'],
				':model' => $_POST['model'],
				':year' => $_POST['year'],
				':mileage' => $_POST['mileage'])
				);			
			if ($count > 0) $_SESSION['success'] = "Record added";
			
			// Redirect the browser to view.php
			header("Location: index.php");			
			return;	
		}
	}
	// Redirect the browser to add.php
	header("Location: add.php");			
	return;	
}
?>

<!DOCTYPE html>
<html>
<head>
<title>73574de5 Automobile Tracker</title>
<?php require_once "bootstrap.php"; ?>
</head>
<body>
<div class="container">
<h1>Tracking Autos for <?= htmlentities($_SESSION["email"]); ?></h1>
<?php
    if ( isset($_SESSION["error"]) ) {
        echo('<p style="color:red">'.$_SESSION["error"]."</p>\n");
        unset($_SESSION["error"]);
    }
?>
<form method="post">
<p>Make:
<input type="text" name="make" size="60"/></p>
<p>Model:
<input type="text" name="model" size="60"/></p>
<p>Year:
<input type="text" name="year"/></p>
<p>Mileage:
<input type="text" name="mileage"/></p>
<input type="submit" name="add" value="Add">
<input type="submit" name="cancel" value="Cancel">
</form>
</div>
</html>
