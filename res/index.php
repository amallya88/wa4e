<?php
require_once "pdo.php";
session_start();
?>

<!DOCTYPE html>
<html>
<head>
<title>Akarsh Mallya Resume Registry</title>
<?php require_once "bootstrap.php"; ?>
</head>
<body>
<div class="container">
<h1>Akarsh Mallya's Resume Registry</h1>
<?php
	if ( isset($_SESSION['error']) ) {
		echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
		unset($_SESSION['error']);
	}
	if ( isset($_SESSION['success']) ) {
		echo '<p style="color:green">'.$_SESSION['success']."</p>\n";
		unset($_SESSION['success']);
	}
	
	if( isset($_SESSION['name']) ){
		echo('<p><a href="logout.php">Logout</a></p>');
		echo('<p><a href="add.php">Add New Entry</a></p>');
	}
	else echo('<p><a href="login.php">Please log in</a></p>');
	
	$stmt = $pdo->query("SELECT profile_id, first_name, last_name, headline FROM profile");
	if( $stmt->rowCount() > 0) {
		echo('<table border="1">'."\n");
		echo('<tr>
		<td><strong>Name</strong></td>
		<td><strong>Headline</strong></td>			
		<td><strong>Action</strong></td>
		</tr>');
		
		while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
			echo '<tr><td><a href="view.php?profile_id='.$row["profile_id"].'">';
			echo(htmlentities($row['first_name']).' '.htmlentities($row['last_name']));
			echo('</a></td><td>');
			echo(htmlentities($row['headline']));
			echo('</a></td><td>');
			echo('<a href="edit.php?profile_id='.$row['profile_id'].'">Edit</a> / ');
			echo('<a href="delete.php?profile_id='.$row['profile_id'].'">Delete</a>');
			echo("</td></tr>\n");
		}
		echo('</table>');
	}
	else echo('<p>No rows found</p>'."\n");
?>
</div>
</body>
</html>
