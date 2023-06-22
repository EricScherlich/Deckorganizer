<?php
session_start();
// Change this to your connection info.
$DATABASE_HOST = 'db';
$DATABASE_USER = 'root';
$DATABASE_PASS = 'MYSQL_ROOT_PASSWORD';
$DATABASE_NAME = 'phplogin';
// Try and connect using the info above.
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if ( mysqli_connect_errno() ) {
	// If there is an error with the connection, stop the script and display the error.
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

// Prepare our SQL, preparing the SQL statement will prevent SQL injection.
if ($stmt = $con->prepare('DELETE FROM decks WHERE id = ?')) {
	// Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
	$stmt->bind_param('i', $_POST['deckID']);
	if($stmt->execute()){
	    header('Location: deckoverview.php');
    }
    else {
        echo "Error inserting record: " . $stmt->error;
    }

	$stmt->close();
}
?>