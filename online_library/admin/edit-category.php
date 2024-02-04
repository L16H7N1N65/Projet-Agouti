<?php
session_start();
error_log('Session started manage-categories.php');
error_log("edit-categories" . print_r($_SESSION, true));
error_log("edit-categories" . print_r($_POST, true));
error_log("edit-categories" . print_r($_GET, true));


include('includes/config.php');

// Si l'utilisateur n'est plus logué
if (strlen($_SESSION['alogin']) == 0) {
    // On le redirige vers la page de login
    header('location:../index.php');
} 

else {
    // Sinon
    $id = isset($_GET['id']) ? intval($_GET['id']) : "" ;
    // Apres soumission du formulaire de categorie
    if (isset($_POST['id'])) {
        // On recupere l'identifiant, le statut, le nom
        

        $categoryName = isset($_POST['name']) ? $_POST['name'] : "Inconnue" ;
        
        $Status = isset($_POST['Status']) ? intval($_POST['Status']) : "0" ;
        
        error_log("id =".$_POST['id']);
        error_log("stus =".$Status);
        error_log("name = ".$categoryName);

        // On prepare la requete de mise a jour
        $sql = "UPDATE tblcategory SET CategoryName=:categoryName,Status=:Status WHERE id=:id";

        //$sql = "UPDATE tblcategory SET CategoryName='$categoryName',Status=$Status WHERE id=".$_POST['id'];
        //error_log($sql);


        $query = $dbh->prepare($sql);
        $query->bindParam(':categoryName', $categoryName, PDO::PARAM_STR);
        $query->bindParam(':Status', $Status, PDO::PARAM_STR);
        $query->bindParam(':id', $_POST['id'], PDO::PARAM_INT);
        // error_log(gettype( $Status));

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
        header('Location: manage-categories.php');
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
        <form method="post" action="edit-category.php">
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
        
        if (isset($Status) && $Status == 1) : ?>
            <!-- On coche le bouton radio "actif"-->
            <input type="radio" name="status" value="1" checked> Actif
            <input type="radio" name="status" value="0"> Inactif

        <?php else: ?>

            <!-- Sinon-->
            <!-- On coche le bouton radio "inactif"-->
            <input type="radio" name="Status" value="1"> Actif
            <input type="radio" name="Status" value="0" checked> Inactif

        <?php endif; ?>
        
        <!-- Un bouton « Mettre à jour » -->
        <div class="form-group">
            <br>
            <button type="submit" class="btn btn-primary" name="id" value="<?php echo $id?>">Mettre à jour </button>
        </div>
        </div>
        <!-- CONTENT-WRAPPER SECTION END-->
        <?php include('includes/footer.php'); ?>
        <!-- FOOTER SECTION END-->
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>