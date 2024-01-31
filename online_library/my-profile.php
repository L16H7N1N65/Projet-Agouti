<?php
// On récupère la session courante
session_start();
error_log('Session started');

// On inclue le fichier de configuration et de connexion à la base de données
include('includes/config.php');
error_log('Config file included');

// Si l'utilisateur n'est plus logué
if (!isset($_SESSION['login']) || $_SESSION['login'] == '') {
// On le redirige vers la page de login
header('location:index.php');
error_log('User is not logged in, redirected to index.php');
exit();


} else {
// Sinon on peut continuer. Après soumission du formulaire de profil
if (isset($_POST['submit'])) {
// On recupere l'id du lecteur (cle secondaire)
$readerId = $_SESSION['rdid'];
error_log(print_r($readerId, 1));

// On recupere le nom complet du lecteur
$fullName = $_POST['fullname'];
// On recupere le numero de portable
$mobileNumber = $_POST['mobilenumber'];
// On recupere le mail
$email = $_POST['emailid'];
// On recupere le mail
// On update la table tblreaders avec ces valeurs
$sqlUpdate = "UPDATE tblreaders SET FullName = :fullname, MobileNumber = :mobilenumber, emailId =: emailid WHERE ReaderId = :rdid";
$queryUpdate = $dbh->prepare($sqlUpdate);
$queryUpdate->bindParam(':fullname', $fullName, PDO::PARAM_STR);
$queryUpdate->bindParam(':mobilenumber', $mobileNumber, PDO::PARAM_STR);
$queryUpdate->bindParam(':emailid', $email, PDO::PARAM_STR);
$queryUpdate->bindParam(':rdid', $readerId, PDO::PARAM_STR);

// On execute la requête
$queryUpdate->execute();
error_log('Query Update executed');

// On informe l'utilisateur du resultat de l'operation
if ($queryUpdate) {
    echo "<script>alert('Profil mis à jour avec succès')</script>";
} else {
    echo "<script>alert('Erreur lors de la mise à jour du profil')</script>";
}
}
// On souhaite voir la fiche de lecteur courant.
// On recupere l'id de session dans $_SESSION
}

$readerId = $_SESSION['rdid'];
 // On prepare la requete permettant d'obtenir les informations du lecteur
$sql = "SELECT * FROM tblreaders WHERE ReaderId = :rdid";
$query = $dbh->prepare($sql);
$query->bindParam(':rdid', $readerId, PDO::PARAM_STR);
// On stocke le résultat de recherche dans une variable $result

$query->execute();
error_log('Query issue line 60');
$result = $query->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="FR">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <title>Gestion de bibliotheque en ligne | Profil</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />

</head>

<body>
    <!-- On inclue le fichier header.php qui contient le menu de navigation-->
    <div class="container">
        <div class="row">
            <div class="col">
                <h3>EDITION DU PROFIL</h3>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-8 offset-md-3">
                <form method="post" action="">
                    <!-- On affiche l'identifiant - non editable -->
<div class="form-group">
    <label>Identifiant</label>
    <input type="text" value="<?php echo isset($result['ReaderId']) ? $result['ReaderId'] : ''; ?>" readonly>
</div>
<!-- On affiche la date d'enregistrement - non editable -->
<div class="form-group">
    <label>Date d'enregistrement</label>
    <input type="text" value="<?php echo isset($result['RegDate']) ? $result['RegDate']: ''; ?>" readonly>
</div>
<!-- On affiche la date de dernière mise à jour - non editable -->
<div class="form-group">
    <label>Date de dernière mise à jour</label>
    <input type="text" value="<?php echo isset($result['UpdateDate']) ? $result['UpdateDate'] : ''; ?>" readonly>
</div>
<!-- On affiche la statut du lecteur - non editable -->
<div class="form-group">
    <label>Statut</label>
    <input type="text" value="<?php echo isset($result['Status']) ? $result['Status'] : ''; ?>" readonly>
</div>
<!-- On affiche le nom complet - editable -->
<div class="form-group">
    <label>Nom complet</label>
    <input type="text" name="fullname" value="<?php echo isset($result['FullName']) ? $result['FullName'] : ''; ?>" required>
</div>
<!-- On affiche le numéro de portable- editable -->
<div class="form-group">
    <label>Numéro de portable</label>
    <input type="text" name="mobilenumber" value="<?php echo isset($result['MobileNumber']) ? $result['MobileNumber'] : ''; ?>" required>
</div>
<!-- On affiche l'email- editable -->
<div class="form-group">
    <label>Email</label>
    <input type="email" name="email" value="<?php echo isset($result['EmailId']) ? $result['EmailId'] : ''; ?>" required>
</div>


                    <button type="submit" name="submit" class="btn btn-info">Mettre à jour le profil</button>
                </form>
            </div>
        </div>
    </div>


    <?php include('includes/footer.php'); ?>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>