<?php
session_start();
require_once "pdo.php";

if ( ! isset($_SESSION["name"]) ) {
    die('Not logged in');
}

// If the user requested logout go back to index.php
if ( isset($_POST['cancel']) ) {
    header('Location: index.php');
    return;
}

if ( isset($_POST['add']) ) {
	
	if (empty($_POST['first_name']) ||
		empty($_POST['last_name']) || 
		empty($_POST['email']) ||
		empty($_POST['headline']) || 
		empty($_POST['summary'])
	) {
		$_SESSION["error"] = "All fields are required";
	} elseif (strpos($_POST["email"], '@') == false) {
        $failure =  "Email must contain '@' symbol";
	} else {
		$sql = "INSERT INTO profile (user_id, first_name, last_name, email, headline, summary) 
					  VALUES (:uid, :fst, :lst, :email, :headline, :summary)";
			
			$stmt = $pdo->prepare($sql);
			$count = $stmt->execute(array(
				':uid' => $_SESSION['user_id'],
				':fst' => $_POST['first_name'],
				':lst' => $_POST['last_name'],
				':email' => $_POST['email'],
				':headline' => $_POST['headline'],
				':summary' => $_POST['summary'])
				);			
			if ($count > 0) $_SESSION['success'] = "Record added";
			
			// Redirect the browser to view.php
			header("Location: index.php");			
			return;	
	}
	// Redirect the browser to add.php
	header("Location: add.php");			
	return;	
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Akarsh Mallya Profile Add</title>
<?php require_once "bootstrap.php"; ?>
<script type="text/javascript" src="validate_addupdate.js"></script>
</head>

<body>

<div class="container">

<h1>Adding Profile for <?= htmlentities($_SESSION["name"]); ?></h1>

<?php
    if ( isset($_SESSION["error"]) ) {
        echo('<p style="color:red">'.$_SESSION["error"]."</p>\n");
        unset($_SESSION["error"]);
    }
?>

<form method="post">
<p>First Name:
<input type="text" name="first_name" id="first_name_1" size="60"/></p>
<p>Last Name:
<input type="text" name="last_name" id="last_name_1" size="60"/></p>
<p>Email:
<input type="text" name="email" id="email_1" size="30"/></p>
<p>Headline:<br/>
<input type="text" name="headline" id="headline_1" size="80"/></p>
<p>Summary:<br/>
<textarea name="summary" rows="8" id="summary_1" cols="80"></textarea>
<p>
<input type="submit" onClick="return addUpdateForm_doValidate('first_name_1','last_name_1','email_1','headline_1','summary_1')" name="add" value="Add">
<input type="submit" name="cancel" value="Cancel">
</p>
</form>

</div>
</body>
</html>

