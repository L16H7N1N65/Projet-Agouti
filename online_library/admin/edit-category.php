<?php
session_start();
error_log('Session started manage-categories.php');
error_log("edit-categories" . print_r($_SESSION, true));

include('includes/config.php');

// Si l'utilisateur n'est plus logué
if (strlen($_SESSION['alogin']) == 0) {
    // On le redirige vers la page de login
    header('location:../index.php');
} 

else {
    // Sinon
    // Apres soumission du formulaire de categorie
    if (isset($_POST['submit'])) {
        // On recupere l'identifiant, le statut, le nom
        $id = intval($_GET['id']);
        $categoryName = $_POST['CategoryName'];

        $categoryStatus = $_POST['categoryStatus'];
        error_log(gettype( $categoryStatus));
        
        // On prepare la requete de mise a jour
        $sql = "UPDATE tblcategory SET CategoryName=:categoryName,Status=:categoryStatus WHERE id=:id";

        $query = $dbh->prepare($sql);
        $query->bindParam(':CategoryName', $categoryName, PDO::PARAM_STR);
        $query->bindParam(':Status', $categoryStatus, PDO::PARAM_STR);
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        error_log(gettype( $categoryStatus));

        // $categoryStatus = :  fetch = $query->fetch(PDO::FETCH_OBJ);

        // On execute la requete
        if ($query->execute()) {
            // On stocke dans $_SESSION le message "Categorie mise a jour"
            $_SESSION['msg'] = "Categorie mise a jour";
        } 
        else {
            // On stocke dans $_SESSION le message "Quelque chose s'est mal passé. Veuillez réessayer"
            $_SESSION['error'] = "Quelque chose s'est mal passé. Veuillez réessayer";
        }

        // On redirige l'utilisateur vers edit-categories.php
        header('Location: edit-category.php');
        exit;
    }

}
?>

<!DOCTYPE html>
<html lang="FR">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <title>Gestion de bibliothèque en ligne | Categories</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
</head>


<body>
    <!------MENU SECTION START-->
    <?php include('includes/header.php'); ?>
    <!-- MENU SECTION END-->
    <!-- On affiche le titre de la page "Editer la categorie-->
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h4 class="header-line">Editer la categorie</h4>
            </div>
        </div>
        <!-- On affiche le formulaire dedition-->
        <!-- Un champ de saisie du nom -->
        <div class="form-group">
            <label for="name">Nom de la catégorie</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                <!-- On affiche ici le formulaire d'édition -->
            </div>
        </div>
        <!-- Si la categorie est active (status == 1)-->
        <?php 
        
        if ($categoryStatus == 1) :'status'?>
            <!-- On coche le bouton radio "actif"-->
            <input type="radio" name="status" value="1" checked> Actif
            <input type="radio" name="status" value="0"> Inactif

        <?php else: ?>

            <!-- Sinon-->
            <!-- On coche le bouton radio "inactif"-->
            <input type="radio" name="status" value="1"> Actif
            <input type="radio" name="status" value="0" checked> Inactif

        <?php endif; ?>
        
        <!-- Un bouton « Mettre à jour » -->
        <div class="form-group">
            <button type="submit" class="btn btn-primary">Mettre à jour</button>
        </div>
        <!-- CONTENT-WRAPPER SECTION END-->
        <?php include('includes/footer.php'); ?>
        <!-- FOOTER SECTION END-->
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>