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
$cardsArray = array();
$cards = explode("\n",$_POST['decklist']);
foreach($cards as $singleCard):
    $card = explode(" ",$singleCard,2);
    $card[0] = (int)$card[0];
    $card[1] = rtrim($card[1],"\r");
    $cardsArray[] = $card;
endforeach;

$cardsCount = count($cardsArray);

foreach($cardsArray as $index => $card){
    for($i=0; $i<$card[0];$i++){
    // Prepare our SQL, preparing the SQL statement will prevent SQL injection.
    if ($stmt = $con->prepare('INSERT INTO cardsXdecks (deck_id, card_id) SELECT ?, id FROM `default-cards` WHERE `name` = ? LIMIT 1')) {
	    // Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
	    $stmt->bind_param('is', $_POST['deckID'],$card[1]);
	    if ($stmt->execute()) {
            // Last iterration
            if ($index === $cardsCount - 1 && $i+1 === $card[0]) {
                header('Location: decklist.php?deck_id=' . $_POST['deckID']); // Redirect back to Decklist
                exit(); // Terminate script execution after redirect
            }
        } else {
            echo "Error inserting record: " . $stmt->error;
        }
        $stmt->close();
    }
    }
}
?>