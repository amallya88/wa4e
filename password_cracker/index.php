<!DOCTYPE html>
<head><title>Akarsh Mallya MD5 Cracker</title></head>
<body>
<h1>MD5 cracker</h1>
<p>This application takes an MD5 hash
of a four-digit number and 
attempts to hash all four-digit combinations
to determine the original four digit PIN.</p>
<pre>
Debug Output:
<?php
$goodtext = "Not found";
// If there is no parameter, this code is all skipped
if ( isset($_GET['md5']) ) {
    $time_pre = microtime(true);
    $md5 = $_GET['md5'];

	// alphabet for possible PINs
	$gAlphabet = str_split("0123456789");
	$gAlpha_count = count($gAlphabet);
		
	// input $prev_PIN : array representing individual characters of a PIN
	// the PIN is "incremented" starting from character at position idx
	// character at position idx in $prev_PIN looked up in $gAlphabet array 
	// and substituted with next char in alphabet array
	// example: input: '0000' -> '0001', '9999' -> '0000'
	function get_next_in_seq(array &$prev_PIN, int $idx) {
	  $size_prev = count($prev_PIN)-1;
	  global $gAlphabet, $gAlpha_count;
	  if( ($idx < 0) || ($idx > $size_prev) )  return;
	  
	  $i = array_search($prev_PIN[$idx], $gAlphabet);
	  if($i < $gAlpha_count - 1){
		$prev_PIN[$idx] = $gAlphabet[$i+1];
		return;
	  } else {
		$prev_PIN[$idx] = $gAlphabet[0];
		return get_next_in_seq($prev_PIN, $idx-1);
	  }
	}
	
    // This is our alphabet
    $show = 15;
	$start_guess = '0000';
	$try = $start_guess;
	$try_len = strlen($try);
	
    // Outer loop go go through the alphabet for the
    // first position in our "possible" pre-hash
    // text
	do{	
		// Run the hash and then check to see if we match
		$check = hash('md5', $try);
		if ( $check == $md5 ) {
			$goodtext = $try;	
		} else {
			$guess_arr = str_split($try);
			get_next_in_seq($guess_arr, $try_len-1);
			$try = implode($guess_arr);
		}
		
		// Debug output until $show hits 0
		if ( $show > 0 ) {
			print "$check $try\n";
			$show = $show - 1;
		}
        
	} while( ($try != $start_guess) && ($check != $md5) );
	
    // Compute elapsed time
    $time_post = microtime(true);
    print "Elapsed time: ";
    print $time_post-$time_pre;
    print "\n";
}
?>
</pre>
<!-- Use the very short syntax and call htmlentities() -->
<p>Original Text: <?= htmlentities($goodtext); ?></p>
<form>
<input type="text" name="md5" size="60" />
<input type="submit" value="Crack MD5"/>
</form>
<ul>
<li><a href="index.php">Reset</a></li>
<li><a href="md5.php">MD5 Encoder</a></li>
<li><a href="makecode.php">MD5 Code Maker</a></li>
<li><a
href="https://github.com/amallya88/wa4e"
target="_blank">Source code for this application</a></li>
</ul>
</body>
</html>

