<?php
// On recupere la session courante
session_start();
error_log('Session started manage-categories.php');
error_log("manage-categories" . print_r($_SESSION, 1));

// On inclue le fichier de configuration et de connexion a la base de donn�es
include('includes/config.php');


// Si l'utilisateur est déconnecté
// L'utilisateur est renvoyé vers la page de login : index.php
if (!isset($_SESSION['alogin']) || $_SESSION['alogin'] != 'admin') {
    // Redirect to login page
    header('Location:../index.php');
    exit();

}
error_log('Redirected due to unauthorized access');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // On recupere l'identifiant de la catégorie a supprimer
    $id = isset($_POST['id']) ? $_POST['id'] : null;
    error_log('id ' . $id);
    error_log(print_r($_SERVER, 1));
    error_log(print_r($_id,1));

    // On prepare la requete de suppression
    if ($id !== null) {
        $stmt = $dbh->prepare("DELETE FROM tblcategory WHERE id = :id");

        // On lie l'identifiant à la requête préparée
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        error_log('stmt ' . print_r($stmt, 1));

    }
    // On execute la requete
    if ($stmt->execute()) {
        // On informe l'utilisateur du resultat de loperation
        $_SESSION['msg'] = "Catégorie supprimée avec succès";
    } else {
        $_SESSION['error'] = "Quelque chose s'est mal passé. Veuillez réessayer";
    }
}

// On redirige l'utilisateur vers la page manage-categories.php
// header('location:../manage.php');
//Supprime toutes les entrées de la table 

?>

<!DOCTYPE html>
<html lang="FR">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <title>Gestion de bibliothèque en ligne | Gestion categories</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
</head>

<body>
    
    <?php include('includes/header.php'); ?>
   
    <!-- On affiche le titre de la page-->
    <div class="container 50px-">
        <h2>Gestion des catégories</h2>
      
        <div class="row">
            <div class="col-md-12">
                <?php
                // On verifie si la variable de session msg est definie
                if (isset($_SESSION['msg']) && !empty($_SESSION['msg'])) {
                    // On affiche le message de succes
                    echo "<span class='alert alert-success'>" . $_SESSION['msg'] . "</span>";
                    // On detruit la variable de session msg
                    if (isset($_SESSION['msg'])) {
                        unset($_SESSION['msg']);
                    }
                    error_log("msg " . print_r($_SESSION, 1));
                }
                // On verifie si la variable de session error est definie
                if (isset($_SESSION['error']) && !empty($_SESSION['error'])) {
                    // On affiche le message d'erreur
                    echo "<span class='alert alert-danger'>" . $_SESSION['error'] . "</span>";
                    // On detruit la variable de session error
                    if (isset($_SESSION['error'])) {
                        unset($_SESSION['error']);
                    }
                    error_log("error " . print_r($_SESSION, 1));
                }
                ?>
            </div>


            <div class="row">
                <div class="col-md-12">
                    <form action="process-category.php" method="POST">
                        <div class="form-group">
                            <label for="id">Nom de la catégorie:</label>
                            <input type="text" class="form-control" id="id" name="id" required>
                        </div>
                        <button type="submit" class="btn btn-primary">supprimer</button>
                    </form>
                </div>
            </div>


        </div>

        <!-- CONTENT-WRAPPER SECTION END-->
        <?php include('includes/footer.php'); ?>
        <!-- FOOTER SECTION END-->
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>