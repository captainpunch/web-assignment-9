<?php # Script 12.4 - loggedin.php
// The user is redirected here from login.php.



// If no cookie is present, redirect the user:
if (!isset($_COOKIE['user_id'])) {

	// Need the functions:
	require ('login_functions.inc.php');
	redirect_user();	

}

// Set the page title and include the HTML header:
$page_title = 'Logged In!';
include ('includes/header.html');

// Print a customized message:
echo '<h1>Logged In!</h1>';
echo '<p>You are now logged in ';

//check for cookie presence
if (isset($_COOKIE['user_id'])) {
   echo $_COOKIE["user_id"];
  }
echo  '!</p>';

echo '<p><a class="btn" href="logout.php">Logout</a></p>';
 

include ('includes/footer.html');
?>