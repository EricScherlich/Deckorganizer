<?php
// Change this to your connection info.
$DATABASE_HOST = 'db';
$DATABASE_USER = 'root';
$DATABASE_PASS = 'MYSQL_ROOT_PASSWORD';
$DATABASE_NAME = 'phplogin';

// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if ( !isset( $_SESSION[ 'loggedin' ] ) ) {
    header( 'Location: index.php' );
    exit;
}

$con = mysqli_connect( $DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME );
if ( mysqli_connect_errno() ) {
    // If there is an error with the connection, stop the script and display the error.
    exit( 'Failed to connect to MySQL: ' . mysqli_connect_error() );
}

//check if deck is owned by account
$permission = $con->prepare('SELECT owner from decks where id = ?');
$permission->bind_param( 'i', $_GET[ 'deck_id' ] );
$permission->execute();
$permission->bind_result($owner);
$permission->fetch();
$permission->close();

if($owner != $_SESSION['id']){
    header('Location: deckoverview.php');
    exit;
}



$deck = $con->prepare( 'SELECT `default-cards`.name , `default-cards`.image_uris, cardsXdecks.id FROM cardsXdecks LEFT JOIN `default-cards` ON cardsXdecks.card_id = `default-cards`.id WHERE deck_id = ?' );
$deck->bind_param( 'i', $_GET[ 'deck_id' ] );
$deck->execute();
$deck->bind_result( $cardname, $card_image, $id );
$results = array();
// Create an array to store the deck names

while ( $deck->fetch() ) {
    $imgs = json_decode( str_replace( "'", '"', $card_image ), true );
    $results[] = [ $cardname, $imgs[ 'normal' ],$id ];
    // Store each deck name in the array
}

$deck->close();
?>

<!DOCTYPE html>
<html>
<head>
<meta charset = 'utf-8'>
<title>Deckliste</title>
<link href = './css/style.css' rel = 'stylesheet' type = 'text/css'>
<link rel = 'stylesheet' href = 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css' integrity = 'sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==' crossorigin = 'anonymous' referrerpolicy = 'no-referrer'>
</head>
<body class = 'loggedin'>
<nav class = 'navtop'>
<div>
<h1>Deckliste</h1>
<a href = 'deckoverview.php'><i class = 'fa-sharp fa-solid fa-layer-group'></i>Deckliste</a>
<a href = 'profile.php'><i class = 'fas fa-user-circle'></i>Profile</a>
<a href = 'logout.php'><i class = 'fas fa-sign-out-alt'></i>Logout</a>
</div>
</nav>
<div class = 'content'>
<h2>Deckliste</h2>
<div>
Deck Import:
<form action = 'importCards.php' method = 'post'>
<label for = 'decklist'>
</label>
<textarea  name = 'decklist' placeholder = "1 Adrix and Nev, Twincasters
1 Animist's Awakening
1 Arcane Signet
1 Avenger of Zendikar
1 Awaken the Woods
1 Barkchannel Pathway
1 Beast Within
1 Beastmaster Ascension
1 Birds of Paradise
1 Cancel
1 Champion of Lambholt
1 Coiling Oracle
1 Combine Chrysalis
1 Command Tower
1 Counterspell
1 Cultivate
1 Curiosity Crafter
1 Dark Depths
1 Deep Forest Hermit
1 Double Major
1 End-Raze Forerunners
1 Esika's Chariot
1 Eternal Witness
1 Eureka Moment
1 Exotic Orchard
1 Ezuri's Predation
1 Faerie Mastermind
9 Forest
1 Helm of the Host
1 Heroic Intervention
1 Hornet Queen
1 Incubation/Incongruity
1 Invasion of Ikoria
1 Irenicus's Vile Duplication
8 Island
1 Jaheira, Friend of the Forest
1 Junk Winder" id = 'decklist' rows = '10' cols = '50' required></textarea>
<input type = 'hidden' value = "<?php echo $_GET[ 'deck_id' ]?>" name = 'deckID'>
<input type = 'submit' value = 'Add to Deck'>
</form>
</div>
<div>
<p>Your decks are below:</p>
<ul>
<?php foreach ( $results as $card ): ?>
<li><?php echo ( '<p>'.$card[ 0 ].'</p>' )  ?>
<img src = "<?php echo $card[1]?>">
<form action="deleteCard.php" method="post">
							<input type="hidden" value="<?php echo $card[2]?>" name="id">
                            <input type="hidden" value="<?php echo $_GET[ 'deck_id' ]?>" name="deckID">
							<input type="submit" value="Remove Card">
						</form>
</li>
<?php endforeach;
?>
</ul>
</div>
</div>
</body>
</html>