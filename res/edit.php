<?php
require_once "pdo.php";
session_start();

if ( ! isset($_SESSION["name"]) ) {
    die('Not logged in');
}

// If the user cancelled editing go back to index.php
if ( isset($_POST['cancel']) ) {
    header('Location: index.php');
    return;
}

if ( isset($_POST['first_name']) && isset($_POST['last_name'])
     && isset($_POST['email']) && isset($_POST['headline']) && isset($_POST['summary']) ) {

    // Data validation
	if (empty($_POST['first_name']) ||
		empty($_POST['last_name']) || 
		empty($_POST['email']) ||
		empty($_POST['headline']) || 
		empty($_POST['summary'])
	) {
		$_SESSION["error"] = "All fields are required";
	} elseif (strpos($_POST["email"], '@') == false) {
        $failure =  "Email must contain '@' symbol";
	}
	if( isset($_SESSION['error']) ) {
		header("Location: edit.php?user_id=".$_SESSION['user_id']);
        return;
    }

    $sql = "UPDATE profile SET first_name = :fst, last_name = :lst,
            email = :em, headline = :hd, summary = :sm, user_id = :uid
            WHERE profile_id = :pid";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ':uid' => $_SESSION['user_id'],
		':fst' => $_POST['first_name'],
        ':lst' => $_POST['last_name'],
        ':em' => $_POST['email'],
        ':hd' => $_POST['headline'],
		':sm' => $_POST['summary'],
		':pid' => $_POST['profile_id']));
    $_SESSION['success'] = 'Record updated';
    header( 'Location: index.php' ) ;
    return;
}

// Guardian: Make sure that user_id is present
if ( ! isset($_GET['profile_id']) ) {
  $_SESSION['error'] = "Missing profile_id";
  header('Location: index.php');
  return;
}

$stmt = $pdo->prepare("SELECT * FROM profile where profile_id = :key");
$stmt->execute(array(":key" => $_GET['profile_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
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
$pid = $row['profile_id'];
?>

<!DOCTYPE html>
<html>

<head>
<title>Akarsh Mallya Profile Edit</title>
<?php require_once "bootstrap.php"; ?>
<script type="text/javascript" src="validate_addupdate.js"></script>
</head>

<body>

<div class="container">
<h1>Editing Profile for <?= htmlentities($_SESSION["name"]); ?></h1>

<?php
    if ( isset($_SESSION["error"]) ) {
        echo('<p style="color:red">'.$_SESSION["error"]."</p>\n");
        unset($_SESSION["error"]);
    }
?>

<form method="post">
<p>First Name:
<input type="text" name="first_name" id="first_name_1" size="60" value="<?= $fst ?>"/></p>
<p>Last Name:
<input type="text" name="last_name" id="last_name_1" size="60" value="<?= $lst ?>"/></p>
<p>Email:
<input type="text" name="email" id="email_1" size="30" value="<?= $em ?>"/></p>
<p>Headline:<br/>
<input type="text" name="headline" id="headline_1" size="80" value="<?= $hd ?>"/></p>
<p>Summary:<br/>
<textarea name="summary" rows="8" id="summary_1" cols="80"><?= $sm ?></textarea>
<input type="hidden" name="profile_id" value="<?= $pid ?>">
<p>
<input type="submit" onClick="return addUpdateForm_doValidate('first_name_1','last_name_1','email_1','headline_1','summary_1')" value="Save">
<input type="submit" name="cancel" value="Cancel">
</p>
</form>

</div>

</body>

</html>

