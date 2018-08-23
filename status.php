<?php # Script 9.7 - password.php

$page_title = 'Change Your Status';
include ('includes/header.html');

// Check for form submission:
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	require ('includes/mysqli_connect.php'); // Connect to the db.
		
	$errors = array(); // Initialize an error array.
	
	// Check for an email address:
	if (empty($_POST['email'])) {
		$errors[] = 'You forgot to enter your email address.';
	} else {
		$e = mysqli_real_escape_string($dbc, trim($_POST['email']));
	}

	// Check for the current password:
	if (empty($_POST['pass'])) {
		$errors[] = 'You forgot to enter your current password.';
	} else {
		$p = mysqli_real_escape_string($dbc, trim($_POST['pass']));
	}
	If ($_POST['Status'] == 'A')
		{
		$Status = 'A';
		}
	else
		{
		$Status = 'I';
		}
	
	if (empty($_POST['Status'])) {
		$errors[] = 'You forgot to enter your status.';
	} else {
		$s = mysqli_real_escape_string($dbc, trim($_POST['Status']));
	}
	
	if (empty($errors)) { // If everything's OK.
		// Check that they've entered the right email address/password combination:
		$q = "SELECT user_id,status FROM users WHERE (email='$e' AND pass=SHA1('$p'))";
		$r = @mysqli_query($dbc, $q);
		$num = @mysqli_num_rows($r);
		
		if ($num == 1) { // Match was made.
	
			// Get the user_id:
			$row = mysqli_fetch_array($r, MYSQLI_NUM);
			if ($row[1] != $s)
			{
				// Make the UPDATE query:
				$q = "UPDATE users SET status='$s' WHERE user_id=$row[0]";		
				$r = @mysqli_query($dbc, $q);
			
				if (mysqli_affected_rows($dbc) == 1) { // If it ran OK.

				// Print a message.
				echo '<h1>Thank you!</h1>
				<p>Your status has been updated.</p><p><br /></p>';	
				echo '<a class="btn btn-primary" href= "view_users.php" >follow this to go back</a>';

				} else { // If it did not run OK.

				// Public message:
				echo '<h1>System Error</h1>
				<p class="alert">Your status could not be changed due to a system error. We apologize for any inconvenience.</p>'; 
				echo '<a class="btn btn-primary" href= "view_users.php" >follow this to go back</a>';
	
				// Debugging message:
				echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $q . '</p>';
	
				}
			}
			else {
					if( $s == 'A')
					{
					echo '<p>Your Status Is already Active </p>';
					}
					else
					{
					echo '<p>Your Status Is already Inactive </p>';
					}
				 }
				
			mysqli_close($dbc); // Close the database connection.

			// Include the footer and quit the script (to not show the form). 
			exit();
				
		} else { // Invalid email address/password combination.
			echo '<h1>Error!</h1>
			<p class="alert">The email address and or password do not match those on file.</p>';
		}
		
	} else { // Report the errors.

		echo '<h1>Error!</h1>
		<p class="alert">The following error(s) occurred:<br />';
		foreach ($errors as $msg) { // Print each error.
			echo " - $msg<br />\n";
		}
		echo '</p><p>Please try again.</p><p><br /></p>';
	
	} // End of if (empty($errors)) IF.

	mysqli_close($dbc); // Close the database connection.
		
} // End of the main Submit conditional.
?>
<h1>Change Your Status</h1>
<form action="status.php" method="post">
	<p>Email Address: <input type="text" name="email" size="20" maxlength="60" value="<?php if (isset($_POST['email'])) echo $_POST['email']; ?>"  /> </p>
	<p>Password: <input type="password" name="pass" size="30" maxlength="50" value="<?php if (isset($_POST['pass'])) echo $_POST['pass']; ?>"  /></p>
	<p>Status:</p>
	<p><input type="radio" name="Status" id="A"  value ="A" required="required"/> Active</p>
	<p><input type="radio" name="Status" id="I"  Value ="I" required="required" /> Inactive</p>
	<p><input type="submit" name="submit" value="Change Status" /></p>
</form>
<?php 
echo '<a class="btn btn-primary" href= "view_users.php" >follow this to go back</a>';
include ('includes/footer.html'); ?>