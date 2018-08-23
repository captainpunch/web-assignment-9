<?php # Script 10.3 - edit_user.php
// This page is for editing a user record.
// This page is accessed through view_users.php.

$page_title = 'Edit a User';
session_start();
include ('includes/header.html');
echo '<h1>Edit a User</h1>';

// Check for a valid user ID, through GET or POST:
if ( (isset($_GET['id'])) && (is_numeric($_GET['id'])) ) { // From view_users.php
	$id = $_GET['id'];
} elseif ( (isset($_POST['id'])) && (is_numeric($_POST['id'])) ) { // Form submission.
	$id = $_POST['id'];
} else { // No valid ID, kill the script.
	echo '<p class="alert alert-warning">This page has been accessed in error.</p>';
	echo '<a class="btn btn-primary" href= "view_users.php" >follow this to go back</a>';
	include ('includes/footer.html'); 
	exit();
}

require_once ('includes/mysqli_connect.php'); 

// Check if the form has been submitted:
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	$errors = array();
	
	// Check for a first name:
	if (empty($_POST['first_name'])) {
		$errors[] = 'You forgot to enter your first name.';
	} else {
		$fn = mysqli_real_escape_string($dbc, trim($_POST['first_name']));
	}
	
	// Check for a last name:
	if (empty($_POST['last_name'])) {
		$errors[] = 'You forgot to enter your last name.';
	} else {
		$ln = mysqli_real_escape_string($dbc, trim($_POST['last_name']));
	}

	// Check for an email address:
	if (empty($_POST['email'])) {
		$errors[] = 'You forgot to enter your email address.';
	} else {
		$e = mysqli_real_escape_string($dbc, trim($_POST['email']));
	}
	if (empty($_POST['Status'])) {
		$errors[] = 'You forgot to enter your status.';
	} else {
		If ($_POST['Status'] == 'A')
		{
		$Status = 'A';
		}
	else
		{
		$Status = 'I';
		}
		$s = mysqli_real_escape_string($dbc, trim($_POST['Status']));
	}
	
		
	
	
	if (empty($errors)) { // If everything's OK.
	
		//  Test for unique email address:
		$q = "SELECT user_id,status FROM users WHERE email='$e' AND user_id != $id";
		$r = @mysqli_query($dbc, $q);
		$num = @mysqli_num_rows($r);
		if (mysqli_num_rows($r) == 0) {

			// Make the query:
			$row = mysqli_fetch_array($r, MYSQLI_NUM);
			if ($row[4] != $s)
			{
				$q = "UPDATE users SET first_name='$fn', last_name='$ln', email='$e', status='$s' WHERE user_id=$id LIMIT 1";
				$r = @mysqli_query ($dbc, $q);
				if (mysqli_affected_rows($dbc) == 1) { // If it ran OK.

					// Print a message:
					echo '<p>The user has been edited.</p>';	
				
				} else { // If it did not run OK.
				
					echo '<p class="alert">The user could not be edited due to a system error. We apologize for any inconvenience.</p>'; // Public message.
					echo '<p>' . mysqli_error($dbc) . '<br />Query: ' . $q . '</p>'; // Debugging message.
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
			

		} else { // Already registered.
			echo '<p class="alert">The email address has already been registered.</p>';
				}
		
	} else { // Report the errors.

		echo '<p class="alert">The following error(s) occurred:<br />';
		foreach ($errors as $msg) { // Print each error.
			echo " - $msg<br />\n";
		}
		echo '</p><p>Please try again.</p>';
	
	} // End of if (empty($errors)) IF.

} // End of submit conditional.

// Always show the form...

// Retrieve the user's information:
$q = "SELECT first_name, last_name, email, status FROM users WHERE user_id=$id";		
$r = @mysqli_query ($dbc, $q);

if (mysqli_num_rows($r) == 1) { // Valid user ID, show the form.

	// Get the user's information:
	$row = mysqli_fetch_array ($r, MYSQLI_NUM);
	
	// Create the form:

	echo '<form action="edit_user.php" method="post">
<p>First Name: <input type="text" name="first_name" size="15" maxlength="15" value="' . $row[0] . '" /></p>
<p>Last Name: <input type="text" name="last_name" size="15" maxlength="30" value="' . $row[1] . '" /></p>
<p>Email Address: <input type="text" name="email" size="20" maxlength="60" value="' . $row[2] . '"  /> </p>
<p> Status is currently <b>' . $row[3] . '</b> for this user.</p>
<p><input type="radio" name="Status" id="A"  value ="A" />  Active</p>
<p><input type="radio" name="Status" id="I"  Value ="I" /> Inactive</p>
<p><input type="submit" name="submit" value="Submit" /></p>
<input type="hidden" name="id" value="' . $id . '" />
</form>';
echo '<a class="btn btn-primary" href= "view_users.php" >follow this to go back</a>';

} else { // Not a valid user ID.
	echo '<p class="alert">This page has been accessed in error.</p>';
	echo '<a class="btn btn-primary" href= "view_users.php" >follow this to go back</a>';
}

mysqli_close($dbc);
include ('includes/footer.html');
?>