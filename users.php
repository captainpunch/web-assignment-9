<?php 
$pagetitle = 'Current Users';
include 'include/header.html';
require 'includes/mysqli_connect.php';

$concat = "SELECT CONCAT(last_name, ', ', first_name) AS name, DATE_FORMAT(registration_date, '%M %d, %Y') 
	AS dr FROM users ORDER BY registration_date ASC";
echo '<h1>Register Users</h1>';
$results = mysqli_query($dbc, $concat);
$num = @mysqli_num_rows($results);

 if ($num > 0 ) 
{
	echo "<p>there are currently $num registered users.</p>\n";
	echo 
	'<table align ="center" cellspacing="3" cellpadding="3" width"75%">
	<tr>
		<td align =left">	<br>Name</br>				</td>
		<td align="left">	<br>Date Registered</br>	</td>
	</tr>';
	while ($row =mysqli_fetch_array($results, MYSQLI_ASSOC)) 
		{echo 
	'<tr>
		<td align="left"> ' . $row['name'] . '	</td>
		<td align=left">  ' .$row['dr'] . '		</td>
	</tr>';}
	echo '</table>';
	mysqli_free_result ($results);
}
else
{
	echo '<p class="alert">The current users could not be retrieved. We apologize for any inconvenience.</p>';
	echo '<p>' .mysqli_error($dbc) . '</br></br> Query: ' . $concat . '</p>';
}
mysqli_close($dbc);
include 'include/footer.html';
?>
