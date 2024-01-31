<?php
// On recupere la session courante
session_start();
error_log('Session started');

// On inclue le fichier de configuration et de connexion à la base de données
include('includes/config.php');
error_log('Config file included');

// Si l'utilisateur n'est pas logué, on le redirige vers la page de login (index.php)
if (strlen($_SESSION['rdid']) == 0) {
	header('location:index.php');
     error_log('User is not logged in, redirected to index.php');
		 
// sinon, on peut continuer,

} else { $readerId = $_SESSION['rdid'];
	error_log(print_r($readerId, 1));
}
// si le formulaire a ete envoye : $_POST['change'] existe
if (isset($_POST['change'])) {

// On recupere le mot de passe et on le crypte (fonction php password_hash)
$newPassword = password_hash($_POST['newpassword'], PASSWORD_DEFAULT);

// On recupere l'email de l'utilisateur dans le tabeau $_SESSION
$email = $_SESSION[''];

// On construit la requete SQL pour mettre a jour le mot de passe dans la table tblreader
// On cherche en base l'utilisateur avec ce mot de passe et cet email
$sql = "SELECT ReaderId FROM tblreader WHERE EmailId=:email AND Password=:newpassword";

// Si le resultat de recherche n'est pas vide
if ($stmt = $dbh->prepare($sql)) {
// On met a jour en base le nouveau mot de passe (tblreader) pour ce lecteur
$stmt->bindParam(':email', $email, PDO::PARAM_STR);	
// On stocke le message d'operation reussie
$success = "Votre mot de passe a ete mis a jour avec succes";
} else {
// sinon (resultat de recherche vide)
// On stocke le message "mot de passe invalide"
$error = "Mot de passe invalide";
}
}

?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

	<title>Gestion de bibliotheque en ligne | changement de mot de passe</title>
	<!-- BOOTSTRAP CORE STYLE  -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
	<!-- FONT AWESOME STYLE  -->
	<link href="assets/css/font-awesome.css" rel="stylesheet" />
	<!-- CUSTOM STYLE  -->
	<link href="assets/css/style.css" rel="stylesheet" />

	<!-- Penser au code CSS de mise en forme des message de succes ou d'erreur -->

</head>
<script type="text/javascript">
	/* On cree une fonction JS valid() qui verifie si les deux mots de passe saisis sont identiques 
	Cette fonction retourne un booleen*/
</script>

<body>
	<!-- Mettre ici le code CSS de mise en forme des message de succes ou d'erreur -->
	<?php include('includes/header.php'); ?>
	<!--On affiche le titre de la page : CHANGER MON MOT DE PASSE-->
	<!--  Si on a une erreur, on l'affiche ici -->
	<!--  Si on a un message, on l'affiche ici -->

	<!--On affiche le formulaire-->
	<!-- la fonction de validation de mot de passe est appelee dans la balise form : onSubmit="return valid();"-->


	<?php include('includes/footer.php'); ?>
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>