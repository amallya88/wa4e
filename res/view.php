<?php
require_once "pdo.php";
session_start();

// Guardian: Make sure that user_id is present
if ( ! isset($_GET['profile_id']) ) {
  $_SESSION['error'] = "Missing profile_id";
  header('Location: index.php');
  return;
} else {
	$stmt = $pdo->prepare('SELECT first_name, last_name, email, headline, summary FROM profile
							WHERE profile_id= :pid');
	$stmt->execute(array( ':pid' => $_GET['profile_id']) );
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
}

if ( $row === false ) {
    $_SESSION['error'] = 'Bad value for profile_id';
    header( 'Location: index.php' ) ;
    return;
}

$fst = htmlentities($row['first_name']);
$lst = htmlentities($row['last_name']);
$em = htmlentities($row['email']);
$hd = htmlentities($row['headline']);
$sm = htmlentities($row['summary']);

?>

<!DOCTYPE html>
<html>

<head>
<title>Akarsh Mallya Profile View</title>
<?php require_once "bootstrap.php"; ?>
</head>

<body>

<div class="container">
<h1>Profile information</h1>
<p>First Name: <?= $fst ?></p>
<p>Last Name: <?= $lst ?></p>
<p>Email: <?= $em ?></p>
<p>Headline:<br/>
<?= $hd ?>
</p>
<p>Summary:<br/>
<?= $sm ?>
<p>
</p>
<a href="index.php">Done</a>
</div>

</body>

</html>
