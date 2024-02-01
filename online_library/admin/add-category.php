<?php
session_start();

error_log('Session started add-category.php');
error_log("add-category" . print_r($_SESSION, 1));



include('includes/config.php');
error_log('config included');

// Si l'utilisateur n'est plus logué
if (strlen($_SESSION['alogin']) == 0) {
    
// if (!isset($_SESSION['alogin']) || $_SESSION['alogin'] != 'admin') {
    $_SESSION['error'] = "Something went wrong. Please try again";

    // On le redirige vers la page de login
    header('Location:../index.php');
   

// Sinon on peut continuer. Après soumission du formulaire de creation
} 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // On recupere le nom et le statut de la categorie
    $categoryName = $_POST['categoryName'];
    error_log(gettype( $categoryName));
    
    $categoryStatus = array_key_exists('Status', $_POST) ? $_POST['Status']: 'default_value';
    error_log(gettype( $categoryStatus));

    // On prepare la requete d'insertion dans la table tblcategory
    $sql = "INSERT INTO tblcategory (CategoryName, Status) VALUES (:CategoryName, :Status)";
    $query = $dbh->prepare($sql);
    $query->bindParam(':CategoryName', $categoryName, PDO::PARAM_STR);
    $query->bindParam(':Status', $categoryStatus, PDO::PARAM_STR);

    // On execute la requête
    $query->execute();
    error_log('Query executed');


} 

?>

<!DOCTYPE html>
<html lang="FR">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <title>Gestion de bibliothèque en ligne | Ajout de categories</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
</head>

<body>
    <!-- MENU SECTION START-->
    <?php include('includes/header.php'); ?>
    <!-- MENU SECTION END-->

    <!-- Page title -->
    <h1>Ajout de categories</h1>

    <!-- Form for category creation -->
    <form method="post" action="add-category.php">
        <label for="categoryName">Nom de la catégorie:</label>
        <input type="text" id="categoryName" name="categoryName" required>
        <label for="categoryStatus">Statut:</label>
        <select id="categoryStatus" name="categoryStatus" required>
            <option value="Active">Active</option>
            <option value="Inactive">Inactive</option>
        </select>
        <input type="submit" value="Ajouter la catégorie">
    </form>

    <!-- CONTENT-WRAPPER SECTION END-->
    <?php include('includes/footer.php'); ?>
    <!-- FOOTER SECTION END-->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>


</html>
