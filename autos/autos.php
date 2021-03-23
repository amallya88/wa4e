<?php
require_once "pdo.php";

// Demand a GET parameter
if ( ! isset($_GET['name']) || strlen($_GET['name']) < 1  ) {
    die('Name parameter missing');
}

// If the user requested logout go back to index.php
if ( isset($_POST['logout']) ) {
    header('Location: index.php');
    return;
}

// If we have no POST data
$failure = false;  
$success = false;

if ( isset($_POST['Add']) ) {
	if ( empty($_POST['make']) ) {
		$failure = "Make is required";
	} else if ( !is_numeric($_POST['year']) || !is_numeric($_POST['mileage']) ) {
		$failure = "Mileage and year must be numeric";
	} else {	
		$sql = "INSERT INTO autos (make, year, mileage) 
				  VALUES (:make, :year, :mileage)";
		
		$stmt = $pdo->prepare($sql);
		$count = $stmt->execute(array(
			':make' => $_POST['make'],
			':year' => $_POST['year'],
			':mileage' => $_POST['mileage'])
			);
		
		$query = $pdo->query("SELECT * FROM autos ORDER BY make");
			
		if ($count > 0) {
			$success = "Record inserted";
			$rows = $query->fetchAll(PDO::FETCH_ASSOC);
			foreach($rows as $rec) {
				$records_array[] = $rec["year"]." ".$rec["make"]." / ".$rec["mileage"];	
			}			
		}
	}	
}
?>

<!DOCTYPE html>
<html>
<head>
<title>de166929 Automobile Tracker</title>
<?php require_once "bootstrap.php"; ?>
</head>
<body>
<div class="container">
<h1>Tracking Autos for <?= htmlentities($_REQUEST['name']); ?></h1>
<?php
// Note triple not equals and think how badly double
// not equals would work here...
if ( $failure !== false ) {
    // Look closely at the use of single and double quotes
    echo('<p style="color: red;">'.htmlentities($failure)."</p>\n");
	error_log($failure);
}
if ( $success !== false ){
	echo('<p style="color: green;"><strong>'.htmlentities($success)."</strong></p>\n");
}
?>
<form method="post">
<p>Make:
<input type="text" name="make" size="60"/></p>
<p>Year:
<input type="text" name="year"/></p>
<p>Mileage:
<input type="text" name="mileage"/></p>
<input type="submit" name="Add" value="Add">
<input type="submit" name="logout" value="Logout">
</form>

<h2>Automobiles</h2>
<ul>
<p>
<?php
	if( isset($records_array) ) {
		foreach($records_array as $rec) {
			echo '<li>'.htmlentities($rec).'</li>';		
		}		
	}
	?>
</p>
</ul>
</div>
</html>
