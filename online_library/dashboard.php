<?php
// On recupere la session courante
session_start();
error_log('Session dashboard started');

// On inclue le fichier de configuration et de connexion a la base de donn�es
include('includes/config.php');
error_log('Config file included');

if (strlen($_SESSION['rdid']) == 0) {
     // Si l'utilisateur est déconnecté
     // L'utilisateur est renvoyé vers la page de login : index.php

     header('location:index.php');
     error_log('User is not logged in, redirected to index.php');
} else {
     // On récupère l'identifiant du lecteur dans le tableau $_SESSION
     $readerId = $_SESSION['rdid'];
     error_log(print_r($readerId, 1));


     // On veut savoir combien de livres ce lecteur a emprunte
     $issuedBooksQuery = "SELECT COUNT(*) as total_emprunts FROM tblissuedbookdetails WHERE ReaderId = :readerId";
     // On construit la requete permettant de le savoir a partir de la table tblissuedbookdetails
     
     $issuedBooksStmt = $dbh->prepare($issuedBooksQuery);
     $issuedBooksStmt->bindParam(':readerId', $readerId, PDO::PARAM_STR);
     $issuedBooksStmt->execute();

     $issuedBooksResult = $issuedBooksStmt->fetch(PDO::FETCH_ASSOC);
     $totalEmprunts = $issuedBooksResult['total_emprunts'];
     error_log('Total emprunts retrieved: ' . $totalEmprunts);

     // On stocke le résultat dans une variable
     $unreturnedBooksQuery = "SELECT COUNT(*) as total_non_rendus FROM tblissuedbookdetails WHERE ReaderId = :readerId AND ReturnStatus = 0";
     // On veut savoir combien de livres ce lecteur n'a pas rendu
     $unreturnedBooksStmt = $dbh->prepare($unreturnedBooksQuery);
     $unreturnedBooksStmt->bindParam(':readerId', $readerId, PDO::PARAM_STR);
     $unreturnedBooksStmt->execute();
     error_log(print_r($unreturnedBooksStmt, 1));
     // On construit la requete qui permet de compter combien de livres sont associ�s � ce lecteur avec le ReturnStatus � 0 
     $unreturnedBooksResult = $unreturnedBooksStmt->fetch(PDO::FETCH_ASSOC);
     $totalNonRendus = $unreturnedBooksResult['total_non_rendus'];
     error_log('Total non rendus retrieved: ' . $totalNonRendus);
     // On stocke le résultat dans une variable
     
     
    

}   
?>

     <!DOCTYPE html>
     <html lang="FR">

     <head>
          <meta charset="utf-8" />
          <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
          <title>Gestion de librairie en ligne | Tableau de bord utilisateur</title>
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
          <div class="container ">
               <h2>Tableau de bord utilisateur</h2>

        <!-- On affiche la quantité de livres empruntés -->
               <div class="card ">
                    <div class="card-body">
                         <h4 class="card-title ">Livres empruntés</h4>
                         <p class="card-text ">Vous avez emprunté <?php echo $totalEmprunts; ?> livres.</p>
                    </div>
               </div>

        <!-- On affiche la quantité de livres non rendus -->
               <div class="card ">
                    <div class="card-body">
                         <h4 class="card-title ">Livres non rendus</h4>
                         <p class="card-text ">Vous avez <?php echo $totalNonRendus; ?> livres non rendus.</p>
                    </div>
               </div>
</div>

          <?php include('includes/footer.php'); ?>
          <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
          <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
     </body>

     </html>
?>