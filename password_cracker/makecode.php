<?php
$error = false;
$md5 = false;
$code = "";
if ( isset($_GET['code']) ) {
    $code = $_GET['code'];
	$pattn = "!^[0-9]{4}$!u";
    if ( strlen($code) != 4 ) {
        $error = "Input must be exactly four digits";
    } else if ( preg_match($pattn, $code) != 1) {
        $error = "Input must only contain digits";
    } else {
        $md5 = hash('md5', $code);
    }
}
?>
<!DOCTYPE html>
<head><title>Akarsh Mallya PIN Code</title></head>
<body>
<h1>MD5 PIN Maker</h1>
<?php
if (!isset($error)) {
    print '<p style="color:red">';
    print htmlentities($error);
    print "</p>\n";
}

if ( $md5 !== false ) {
    print "<p>MD5 value: ".htmlentities($md5)."</p>";
}
?>
<p>Please enter a four-digit key for encoding.</p>
<form>
<input type="text" name="code" value="<?= htmlentities($code) ?>"/>
<input type="submit" value="Compute MD5 for CODE"/>
</form>
<p><a href="makecode.php">Reset</a></p>
<p><a href="index.php">Back to Cracking</a></p>
</body>
</html>
