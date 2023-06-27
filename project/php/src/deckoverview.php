<?php
// Change this to your connection info.
$DATABASE_HOST = 'db';
$DATABASE_USER = 'root';
$DATABASE_PASS = 'MYSQL_ROOT_PASSWORD';
$DATABASE_NAME = 'phplogin';

// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	header('Location: index.php');
	exit;
}

$con = mysqli_connect( $DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME );
if ( mysqli_connect_errno() ) {
    // If there is an error with the connection, stop the script and display the error.
    exit( 'Failed to connect to MySQL: ' . mysqli_connect_error() );
}

$decks = $con->prepare('SELECT name, id FROM decks WHERE owner = ?');
$decks->bind_param('i',$_SESSION['id']);
$decks->execute();
$decks->bind_result($deckname, $deckid);
$results = array(); // Create an array to store the deck names

while ($decks->fetch()) {
    $results[] = [$deckname,$deckid]; // Store each deck name in the array
}

$decks->close();
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Decks List</title>
		<link href="./css/style.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer">
	</head>
	<body class="loggedin">
		<nav class="navtop">
			<div>
				<h1>Deckliste</h1>
				<a href="deckoverview.php"><i class="fa-sharp fa-solid fa-layer-group"></i>Deckliste</a>
				<a href="profile.php"><i class="fas fa-user-circle"></i>Profile</a>
				<a href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
			</div>
		</nav>
		<div class="content">
			<h2>Decklist</h2>
			<div>
			<form action="newDeck.php" method="post">
				<input type="text" name="name" placeholder="Deckname" id="deckname" required>
				<input type="submit" value="Create Deck">
			</form>
			</div>
			<div>
				<p>Your decks are below:</p>
				<ul>
					<?php foreach ($results as $deck): ?>
						<li><?php echo ('<a href="/decklist.php?deck_id='.$deck[1].'">'.$deck[0]); ?>
						<form action="deleteDeck.php" method="post">
							<input type="hidden" value="<?php echo $deck[1]?>" name="deckID">
							<input type="submit" value="Delete Deck">
						</form>
					</li>
						
					<?php endforeach; ?>
				</ul>
			</div>
		</div>
	</body>
</html>