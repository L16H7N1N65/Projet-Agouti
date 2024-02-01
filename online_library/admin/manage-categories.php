<?php
// On recupere la session courante
session_start();
error_log('Session started manage-categories.php');
error_log("manage-categories" . print_r($_SESSION, true));

// On inclue le fichier de configuration et de connexion a la base de donn�es
include('includes/config.php');


// Si l'utilisateur est déconnecté
// L'utilisateur est renvoyé vers la page de login : index.php
if (!isset($_SESSION['alogin']) || $_SESSION['alogin'] != 'admin') {
    // Redirect to login page
    header('Location:../index.php');
    exit();
  
} else {

}
try {
    $sql_cat = "SELECT id, CategoryName, Status, CreationDate, UpdationDate FROM tblcategory";
    $stmt_cat = $dbh->prepare($sql_cat);
    $stmt_cat->execute();
    $results = $stmt_cat->fetchAll(PDO::FETCH_ASSOC);

    error_log('Categories fetched successfully');
    error_log(print_r($results, true));
} catch (PDOException $e) {
    error_log('Error fetching categories: ' . $e->getMessage());
}


error_log(print_r($results, 1));

error_log(print_r($stmt_cat->rowCount(), 1));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? $_POST['id'] : null;

    if (isset($_POST['edit'])) {
        error_log('Editing category with ID: ' . $id);
        // Redirige vers la page d'édition avec l'ID
        header("Location: edit-category.php?id=$id");
        exit();
    } 
    elseif (isset($_POST['delete'])) {
        error_log('Deleting category with ID: ' . $id);
        // On prépare la requête de suppression
        if ($id !== null) {
            try {
                $stmt = $dbh->prepare("DELETE FROM tblcategory WHERE Id = :id");
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);

                // On exécute la requête
                if ($stmt->execute()) {
                    $_SESSION['msg'] = "Catégorie supprimée avec succès";
                    error_log('Category deleted successfully');
                } 
                else {
                    $_SESSION['error'] = "Quelque chose s'est mal passé. Veuillez réessayer";
                    error_log('Error deleting category');
                }
            } 
            catch (PDOException $e) {
                error_log('Error executing delete query: ' . $e->getMessage());
            }
        }
    }
}



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
    <div class="container ">
    <br>
        <h2>Gestion des catégories</h2>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-11 offset-md-1 col-xl-12 offset-xl-2">
            </div>
        </div>
        <br>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col" class="table-bordered border-dark" id="number-culomn">#</th>
                    <th scope="col" class="table-bordered border-dark">Nom</th>
                    <th scope="col" class="table-bordered border-dark">Statut</th>
                    <th scope="col" class="table-bordered border-dark">Crée le</th>
                    <th scope="col" class="table-bordered border-dark">Mise à jour le</th>
                    <th scope="col" class="table-bordered border-dark">Action
                       
                       
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($results as $i => $result) {
                    $i++; 
                ?>
                    <tr>
                        <th scope="row" class="table-secondary table-bordered border-dark"><?php echo $i ?></th>
                        <td class="table-secondary table-bordered border-dark"><?php echo $result['CategoryName'] ?></td>
                        <td class="table-secondary table-bordered border-dark"><?php echo $result['Status'] ?></td>
                        <td class="table-secondary table-bordered border-dark"><?php echo $result['CreationDate'] ?></td>
                        <td class="table-secondary table-bordered border-dark"><?php echo $result['UpdationDate'] ?></td>
                        <td class="table-secondary table-bordered border-dark">
                            <form method="post" action="">
                                <input type="hidden" name="id" value="<?php echo $result['id'] ?>">
                                <button type="submit" class="btn btn-primary" name="edit">Editer</button>
                                <button type="submit" class="btn btn-warning" name="delete" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette catégorie ?')">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <br>

        <!-- CONTENT-WRAPPER SECTION END-->
        <?php include('includes/footer.php'); ?>
        <!-- FOOTER SECTION END-->
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>
