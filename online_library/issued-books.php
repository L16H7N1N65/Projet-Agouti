<?php
// On récupère la session courante
session_start();
// On inclue le fichier de configuration et de connexion à la base de données
include('includes/config.php');
// Si l'utilisateur n'est pas connecte, on le dirige vers la page de login
if (strlen($_SESSION['rdid']) == 0) {
    // On le redirige vers la page de login
    header('location:index.php');
} else {
    // Sinon on peut continuer

    // On recupere l'id du lecteur (cle secondaire)

    $userIssuedBooksId = $_SESSION['rdid'];

    $sql = "SELECT tblbooks.BookName, tblbooks.ISBNNumber, tblissuedbookdetails.IssuesDate, tblissuedbookdetails.ReturnDate FROM 
    tblbooks JOIN tblissuedbookdetails ON tblbooks.ISBNNumber = tblissuedbookdetails.BookId  WHERE ReaderId =:rdid";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':rdid', $userIssuedBooksId, PDO::PARAM_STR);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    error_log(print_r($results, 1));
    //error_log(print_r($stmt, 1));
    error_log(print_r($sql, 1));
    error_log(print_r($userIssuedBooksId, 1));
    error_log(print_r($stmt->rowCount(), 1));

}
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

    <script type="text/javascript">

    </script>
</head>

<body>
    
    <!--On insere ici le menu de navigation T-->
    <?php include('includes/header.php'); ?>
    <!-- On affiche le titre de la page : LIVRES SORTIS -->
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-11 offset-md-1 col-xl-10 offset-xl-2">
                <br>
                <h3 class="title-container-login">LIVRES EMPRUNTES</h3>
            </div>
        </div>
        <br>
        <!-- On affiche la liste des sorties contenus dans $results sous la forme d'un tableau -->
        <table class="table">
            <thead>
                <tr>
                    <th scope="col" class="table-bordered border-dark" id="number-culomn">#</th>
                    <th scope="col" class="table-bordered border-dark">Titre</th>
                    <th scope="col" class="table-bordered border-dark">ISBN</th>
                    <th scope="col" class="table-bordered border-dark">Date de sortie</th>
                    <th scope="col" class="table-bordered border-dark">Date de Retour</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 0;
                foreach ($results as $result) {
                    $status = $result['ReturnDate'];
                    $i++;
                    if ($status === NULL) {
                        $statusLabel = "NON RETOURNÉ";
                        $style = "color: red;";
                    } else {
                        $statusLabel = $status;
                        $style = "color: green;"; 
                    }
                    error_log(print_r($status, 1));
                ?>
                    <tr>
                        <th scope="row" class="table-secondary table-bordered border-dark"><?php echo $i ?></th>
                        <td class="table-secondary table-bordered border-dark"><?php echo $result['BookName'] ?></td>
                        <td class="table-secondary table-bordered border-dark"><?php echo $result['ISBNNumber'] ?></td>
                        <td class="table-secondary table-bordered border-dark"><?php echo $result['IssuesDate'] ?></td>
                        <!-- Si il n'y a pas de date de retour, on affiche non retourne -->
                        <td class="table-secondary table-bordered border-dark" style="<?php echo $style ?>"><?php echo $statusLabel ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <br>
    <?php include('includes/footer.php'); ?>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>