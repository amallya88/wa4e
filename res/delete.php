<?php
require_once "pdo.php";
session_start();

if ( ! isset($_SESSION["name"]) ) {
    die('Not logged in');
}

// If the user cancelled deleting go back to index.php
if ( isset($_POST['cancel']) ) {
    header('Location: index.php');
    return;
}

if ( isset($_POST['delete']) && isset($_POST['profile_id']) ) {
    $sql = "DELETE FROM profile WHERE profile_id = :key";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':key' => $_POST['profile_id']));
    $_SESSION['success'] = 'Record deleted';
    header( 'Location: index.php' ) ;
    return;
}

// Guardian: Make sure that user_id is present
if ( ! isset($_GET['profile_id']) ) {
  $_SESSION['error'] = "Missing profile_id";
  header('Location: index.php');
  return;
}

$stmt = $pdo->prepare("SELECT profile_id, first_name, last_name FROM profile where profile_id = :key");
$stmt->execute(array(":key" => $_GET['profile_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ( $row === false ) {
    $_SESSION['error'] = 'Bad value for profile_id';
    header( 'Location: index.php' ) ;
    return;
}

?>

<!DOCTYPE html>
<html>

<head>
<title>Akarsh Mallya Profile Delete</title>
<?php require_once "bootstrap.php"; ?>
</head>

<body>

<div class="container">
<h1>Deleting Profile</h1>
<p>First Name: 
<?= htmlentities($row['first_name']) ?>
</p>
<p>Last Name:
<?= htmlentities($row['last_name']) ?>
</p>

<form method="post">
<input type="hidden" name="profile_id" value="<?= $row['profile_id'] ?>">
<input type="submit" value="Delete" name="delete">
<input type="submit" value="Cancel" name="cancel">
</form>
</div>

</body>

</html>
