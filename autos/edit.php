<?php
require_once "pdo.php";
session_start();

// If the user pressed cancel editing go back to index.php
if ( isset($_POST['cancel']) ) {
    header('Location: index.php');
    return;
}

if ( isset($_POST['make']) && isset($_POST['model'])
     && isset($_POST['year']) && isset($_POST['mileage']) ) {

    // Data validation
	if 	(	empty($_POST['make']) ||
			empty($_POST['model']) || 
			empty($_POST['year']) ||
			empty($_POST['mileage'])
	) {
		$_SESSION["error"] = "All fields are required";
	} else {
		if ( !is_numeric($_POST['year']) ) $_SESSION["error"] = "Year must be numeric";
		if ( !is_numeric($_POST['mileage']) ) $_SESSION["error"] = "Mileage must be numeric";
	} 
	if( isset($_SESSION['error']) ) {
		header("Location: edit.php?autos_id=".$_POST['autos_id']);
        return;
    }

    $sql = "UPDATE autos SET make = :make, model = :mdl,
            year = :yr, mileage = :mile
            WHERE auto_id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ':make' => $_POST['make'],
        ':mdl' => $_POST['model'],
        ':yr' => $_POST['year'],
        ':mile' => $_POST['mileage'],
		':id' => $_POST['autos_id']));
    $_SESSION['success'] = 'Record updated';
    header( 'Location: index.php' ) ;
    return;
}

// Guardian: Make sure that user_id is present
if ( ! isset($_GET['autos_id']) ) {
  $_SESSION['error'] = "Missing auto_id";
  header('Location: index.php');
  return;
}

$stmt = $pdo->prepare("SELECT * FROM autos where auto_id = :key");
$stmt->execute(array(":key" => $_GET['autos_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ( $row === false ) {
    $_SESSION['error'] = 'Bad value for auto_id';
    header( 'Location: index.php' ) ;
    return;
}

$make = htmlentities($row['make']);
$mdl = htmlentities($row['model']);
$yr = htmlentities($row['year']);
$mile = htmlentities($row['mileage']);
$id = $row['auto_id'];
?>

<!DOCTYPE html>
<html>
<head>
<title>73574de5 Automobile Tracker</title>
<?php require_once "bootstrap.php"; ?>
</head>
<body>
<div class="container">
<h1>Editing Automobile</h1>
<?php
    if ( isset($_SESSION["error"]) ) {
        echo('<p style="color:red">'.$_SESSION["error"]."</p>\n");
        unset($_SESSION["error"]);
    }
?>
<form method="post">
<p>Make:
<input type="text" name="make" size="60" value="<?= $make ?>"/></p>
<p>Model:
<input type="text" name="model" size="60" value="<?= $mdl ?>"/></p>
<p>Year:
<input type="text" name="year" value="<?= $yr ?>"/></p>
<p>Mileage:
<input type="text" name="mileage" value="<?= $mile ?>"/></p>
<input type="hidden" name="autos_id" value="<?= $id ?>">
<input type="submit" name="save" value="Save">
<input type="submit" name="cancel" value="Cancel">
</form>
</div>
</html>

