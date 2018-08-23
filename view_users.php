<?php # Script 10.5 - #5
// This script retrieves all the records from the users table.
// This new version allows the results to be sorted in different ways.

$page_title = 'View the Current Users';
session_start();
include ('includes/header.html');
echo '<h3 align="center"> Registered Users </h3>';
require 'includes/mysqli_connect.php';

// Number of records to show per page:
$display = 10;

// Determine how many pages there are...
if (isset($_GET['p']) && is_numeric($_GET['p'])) { // Already been determined.
	$pages = $_GET['p'];
} else { // Need to determine.
 	// Count the number of records:
	$q = "SELECT COUNT(user_id) FROM users";
	$r = @mysqli_query ($dbc, $q);
	$row = @mysqli_fetch_array ($r, MYSQLI_NUM);
	$records = $row[0];
	// Calculate the number of pages...
	if ($records > $display) { // More than 1 page.
		$pages = ceil ($records/$display);
	} else {
		$pages = 1;
	}
} // End of p IF.

// Determine where in the database to start returning results...
if (isset($_GET['s']) && is_numeric($_GET['s'])) {
	$start = $_GET['s'];
} else {
	$start = 0;
}

// Determine the sort...
// Default is by registration date.
$sort = (isset($_GET['sort'])) ? $_GET['sort'] : 'rd';

// Determine the sorting order:
switch ($sort) {
	case 'lnAsc':
		$order_by = 'last_name ASC';
		break;
	case 'fnAsc':
		$order_by = 'first_name ASC';
		break;
	case 'stAsc':
		$order_by = 'status ASC';
		break;
	case 'rdAsc':
		$order_by = 'registration_date ASC';
		break;
	case 'lnDesc':
		$order_by = 'last_name DESC';
		break;
	case 'fnDesc':
		$order_by = 'first_name DESC';
		break;
	case 'stDesc':
		$order_by = 'status DESC';
		break;
	case 'rdDesc':
		$order_by = 'registration_date DESC';
		break;
	default:
		$order_by = 'registration_date ASC';
		$sort = 'rd';
		break;
}
	
// Define the query:
$q = "SELECT last_name, first_name, DATE_FORMAT(registration_date, '%M %d, %Y') AS dr, status, user_id FROM users ORDER BY $order_by LIMIT $start, $display";		
$r = @mysqli_query ($dbc, $q); // Run the query.
//$num = @mysqli_num_rows($r);
//echo "<p align='center'>there are currently $num registered users.</p>\n";

// Table header:
echo '<div class="table-responsive"> <table class=" table table-striped">
<tr>
	<td align="left">Edit</td>
	<td align="left">Delete</td>
	<td align="left">
		Last Name
		<a href="view_users.php?sort=lnAsc">↑</a>
		<a href="view_users.php?sort=lnDesc">↓</a>
	</td>
	<td align="left">
		First Name
		<a href="view_users.php?sort=fnAsc">↑</a>
		<a href="view_users.php?sort=fnDesc">↓</a>
	</td>
	<td align="left">
		Date Registered
		<a href="view_users.php?sort=rdAsc">↑</a>
		<a href="view_users.php?sort=rdDesc">↓</a>
	</td>
	<td align="left">
		Status
		<a href="view_users.php?sort=stAsc">↑</a>
		<a href="view_users.php?sort=stDesc">↓</a>
	</td>
</tr>
';

// Fetch and print all the records....

while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {

		echo '<tr>
		<td align="left"><a href="edit_user.php?id=' . $row['user_id'] . '">Edit</a></td>
		<td align="left"><a href="delete_user.php?id=' . $row['user_id'] . '">Delete</a></td>
		<td align="left">' . $row['last_name'] . '</td>
		<td align="left">' . $row['first_name'] . '</td>
		<td align="left">' . $row['dr'] . '</td>
		<td align="left">' . $row['status'] . '</td>
	</tr>
	';
} // End of WHILE loop.

echo '</table> </div>';
mysqli_free_result ($r);
mysqli_close($dbc);

// Make the links to other pages, if necessary.
if ($pages > 1) {
	 
	echo '<br /><div class="container" align="center">';
	$current_page = ($start/$display) + 1;
	
	// If it's not the first page, make a Previous button:
	if ($current_page != 1) {
		echo '<a href="view_users.php?s=' . ($start - $display) . '&p=' . $pages . '&sort=' . $sort . '">Previous</a> ';
	}
	
	// Make all the numbered pages:
	for ($i = 1; $i <= $pages; $i++) {
		if ($i != $current_page) {
			echo '<a href="view_users.php?s=' . (($display * ($i - 1))) . '&p=' . $pages . '&sort=' . $sort . '">' . $i . '</a> ';
		} else {
			echo $i . ' ';
		}
	} // End of FOR loop.
	
	// If it's not the last page, make a Next button:
	if ($current_page != $pages) {
		echo '<a href="view_users.php?s=' . ($start + $display) . '&p=' . $pages . '&sort=' . $sort . '">Next</a>';
	}
	
	echo '</div>'; // Close the paragraph.
	
} // End of links section.
	

?>