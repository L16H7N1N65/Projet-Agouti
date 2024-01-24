<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

// On récupère la session courante
session_start();
error_log('Session issued-books started');

// On inclue le fichier de configuration et de connexion à la base de données
include('includes/config.php');
error_log('Config file included');

// Si l'utilisateur n'est pas connecte, on le dirige vers la page de login
if (!isset($_SESSION['rdid']) || $_SESSION['rdid'] == '') {
    header('location:index.php');
    error_log('User did not connect, redirecting to index.php');
    exit();
    error_log('exited');
// Sinon on peut continuer
} else {
//	Si le bouton de suppression a ete clique($_GET['del'] existe)
// if (isset($_GET['del'])) {
 
//On recupere l'identifiant du livre
$retrieveUserId = $_SESSION['rdid'];

$retrieveBookId = isset($_POST['id']) ? $_POST['id'] : '';
        error_log('id: ' . $retrieveBookId);

// You need to add a query to retrieve books issued for the session
$retrieveBookIssuedJointure = "SELECT ('x.id x.ISBNNumber y.Issuesdate y.ReturnDate')  FROM tblissuedbookdetails y JOIN tblbooks x ON BookId  = ISBNNumber WHERE ReaderID = :rdid";

// $retrieveBookIssuedJointure = "SELECT ('tblbook.id tblbook.ISBNNumber tblissuedbookdetails.Issuesdate tblissuedbookdetails.ReturnDate')  FROM tblissuedbookdetails y JOIN tblbooks x ON tblbook.BookId  = tblissuedbookdetails.ISBNNumber WHERE ReaderID = :rdid";

$stmt_j = $dbh->prepare($retrieveBookIssuedJointure);
$stmt_j->bindParam(':rdid', $retrieveUserId, PDO::PARAM_STR); 


$stmt_j->execute();
error_log('Query stmt_j -->executed effectively');


$result = $stmt_j->fetchALL(PDO::FETCH_ASSOC);
error_log(print_r($result, 1));

}
// On redirige l'utilisateur vers issued-book.php
header('issued-books.php');

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
    <!--On insere ici le menu de navigation T-->
    <?php include('includes/header.php'); ?>
    <!-- On affiche le titre de la page : LIVRES SORTIS -->

   
    <!-- On affiche la liste des sorties contenus dans $results sous la forme d'un tableau -->

    <!-- Si il n'y a pas de date de retour, on affiche non retourne -->


    <?php include('includes/footer.php'); ?>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>