<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

// On récupère la session courante
session_start();
error_log('Session issued-books started');

// On inclue le fichier de configuration et de connexion à la base de données
include('includes/config.php');
error_log('Config file included');
error_log(print_r($_SESSION, 1));
error_log(print_r($_POST, 1));

// Si l'utilisateur n'est pas connecte, on le dirige vers la page de login
if (!isset($_SESSION['rdid']) || $_SESSION['rdid'] == '') {
    header('location:index.php');
    error_log('User did not connect, redirecting to index.php');
    exit();

// Sinon on peut continuer
} else {
//	Si le bouton de suppression a ete clique($_GET['del'] existe)
// if (isset($_GET['del'])) {
 
//On recupere l'identifiant du livre
$retrieveUserId = $_SESSION['rdid'];

$retrieveBookId = isset($_POST['id']) ? $_POST['id'] : '';
        error_log('id: ' . $retrieveBookId);

// You need to add a query to retrieve books issued for the session
$retrieveBookIssuedJointure = "SELECT x.ISBNNumber, y.Issuesdate, y.ReturnDate FROM tblissuedbookdetails y
                                JOIN tblbooks x ON y.BookId  = x.ISBNNumber
                                WHERE ReaderID = :rdid";

// $retrieveBookIssuedJointure = "SELECT ('tblbook.id tblbook.ISBNNumber tblissuedbookdetails.Issuesdate tblissuedbookdetails.ReturnDate')  FROM tblissuedbookdetails JOIN tblbook  ON tblbook.Id  = tblissuedbookdetails.ISBNNumber WHERE ReaderID = :rdid";

$stmt_j = $dbh->prepare($retrieveBookIssuedJointure);
$stmt_j->bindParam(':rdid', $retrieveUserId, PDO::PARAM_STR); 


$stmt_j->execute();
error_log('Query stmt_j -->executed effectively');


$result = $stmt_j->fetchALL(PDO::FETCH_ASSOC);
error_log(print_r($result, 1));

}
// On redirige l'utilisateur vers issued-book.php
// header('issued-books.php');

// Requête pour récupérer le nombre total de livres non rendus
$unreturnedBooksQuery = "SELECT x.ISBNNumber, x.Image, x.BookName, y.Issuesdate, y.ReturnDate 
                        FROM tblissuedbookdetails y
                        JOIN tblbooks x ON y.BookId = x.ISBNNumber
                        WHERE ReaderID = :readerId AND ReturnStatus = 0";
$unreturnedBooksStmt = $dbh->prepare($unreturnedBooksQuery);
$unreturnedBooksStmt->bindParam(':readerId', $retrieveUserId, PDO::PARAM_STR);
$unreturnedBooksStmt->execute();

// On récupère le résultat
$unreturnedBooksResult = $unreturnedBooksStmt->fetch(PDO::FETCH_ASSOC);
$totalNonRendus = $unreturnedBooksResult['total_non_rendus'];
error_log('Total non rendus retrieved: ' . $totalNonRendus);
?>
?>

<!DOCTYPE html>
<html lang="FR">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <title>Gestion de bibliotheque en ligne | Gestion des livres</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
</head>
<body>
          <!--On inclue ici le menu de navigation includes/header.php-->
          <?php include('includes/header.php'); ?>
          <!-- On affiche le titre de la page : Tableau de bord utilisateur-->
          <div class="container 50px-">
               <h2>Tableau de bord utilisateur</h2>

        <!-- On affiche la quantité de livres empruntés -->
               <div class="card 20px- 1pxsolidccc- 15px- ">
                    <div class="card-body">
                         <h4 class="card-title 007bff-">Livres empruntés</h4>
                         <p class="ccard-text fs-18px">Vous avez emprunté <?php echo count($result); ?> livres.</p>
                    </div>
               </div>

        <!-- On affiche la quantité de livres non rendus -->
               <div class="card 20px- 1pxsolidccc- 15px- ">
                    <div class="card-body">
                         <h4 class="card-title 007bff-">Livres non rendus</h4>
                         foreach ($unreturnedBooksResults as $book) {
            echo "<div class='book-info'>";
            echo "<img src='path/to/your/images/{$book['Image']}' alt='Book Image' class='book-image'>";
            echo "<p>Livre : {$book['BookName']}, ISBN : {$book['ISBNNumber']}, Date d'emprunt : {$book['Issuesdate']}, ";
            
            // Si il n'y a pas de date de retour, on affiche non retourné
            if ($book['ReturnDate'] != null) {
                echo "Date de retour : {$book['ReturnDate']}";
            } else {
                echo "Non retourné";
            }

            echo "</p>";
            echo "</div>";
        }
        ?>
    </div>
</div>
</div>
        
    <!-- On affiche le titre de la page : LIVRES SORTIS -->

    <!-- On affiche la liste des sorties contenus dans $results sous la forme d'un tableau -->
    <!-- <?php foreach ($result as $book) {
            echo "<p>Livre ISBN : {$book['ISBNNumber']}, Date d'emprunt : {$book['Issuesdate']}, ";
           
    //  Si il n'y a pas de date de retour, on affiche non retourne 
        if ($result[0]['ReturnDate'] != null) {
            echo "{$result[0]['ReturnDate']}";
        } else {
            echo "Non retourné";
        } 
    }    
?> -->
<?php
foreach ($result as $book) {
    echo "<p>Livre ISBN : {$book['ISBNNumber']}, Date d'emprunt : {$book['Issuesdate']}, ";

    // Si il n'y a pas de date de retour, on affiche non retourne 
    if ($book['ReturnDate'] != null) {
        echo "Date de retour : {$book['ReturnDate']}";
    } else {
        echo "Non retourné";
    }

    echo "</p>";
}
?>

    <?php include('includes/footer.php'); ?>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>