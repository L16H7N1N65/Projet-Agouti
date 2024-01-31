<?php
// On récupère la session courante
session_start();

$userId = $_SESSION['rdid'];

// On inclue le fichier de configuration et de connexion à la base de données
include('includes/config.php');

// Si l'utilisateur n'est pas logué, on le redirige vers la page de login (index.php)
if (strlen($userId) == 0) {
    // On le redirige vers la page de login
    header('location:index.php');
} else {
    // sinon, on peut continuer,
    // si le formulaire a été envoyé : $_POST['change'] existe
    if (isset($_POST['change'])) {
        // On récupère le mot de passe actuel et le nouveau mot de passe
        $userOldPassword = $_POST['currentpassword'];
        $userChangePassword = password_hash($_POST['newpassword'], PASSWORD_DEFAULT);

        // On cherche en base l'utilisateur avec cet ID
        $sql = "SELECT Password FROM tblreaders WHERE ReaderId=:rdid";
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':rdid', $userId, PDO::PARAM_STR);
        $stmt->execute();
        $find = $stmt->fetch(PDO::FETCH_OBJ);

        if ($find && password_verify($userOldPassword, $find->Password)) {
            // Si le résultat de la recherche n'est pas vide
            // On met à jour en base le nouveau mot de passe (tblreader) pour ce lecteur
            $change = "UPDATE tblreaders SET Password=:mdp WHERE ReaderId=:rdid";
            $query = $dbh->prepare($change);
            $query->bindParam(':mdp', $userChangePassword, PDO::PARAM_STR);
            $query->bindParam(':rdid', $userId, PDO::PARAM_STR);

            if ($query->execute()) {
                $msgValidCreation = 'Votre nouveau mot de passe a bien été créé';
                echo '<script type="text/javascript">window.confirm("' . $msgValidCreation . '");</script>';
            } else {
                $msgInvalidCreation = 'Votre création a échoué, veuillez essayer de nouveau, si le problème persiste alors contactez notre service de maintenance.';
                echo '<script type="text/javascript">window.confirm("' . $msgInvalidCreation . '");</script>';
            }
        }
    }
}
?>


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
	function valid() {
		if (document.chngpwd.newpassword.value != document.chngpwd.confirmpassword.value) {
			alert("Les deux mots de passe saisis ne sont pas identiques");
			document.chngpwd.confirmpassword.focus();
			return false;
		}
		return true;
	}
</script>

<body>
	<!-- Mettre ici le code CSS de mise en forme des message de succes ou d'erreur -->
	<?php include('includes/header.php'); ?>
	<!--On affiche le titre de la page : CHANGER MON MOT DE PASSE-->
	<div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-11 offset-md-1 col-xl-10 offset-xl-2">
                <br>
                <h3 class="title-container">CHANGEMENT MOT DE PASSE</h3>
            </div>
        </div>
        <br>
	<!--  Si on a une erreur, on l'affiche ici -->

	<!--  Si on a un message, on l'affiche ici -->

	<!--On affiche le formulaire-->

	<!-- la fonction de validation de mot de passe est appelee dans la balise form : onSubmit="return valid();"-->
	<form name="chngpwd" method="post" onSubmit="return valid();">
		<!-- On affiche le champ mot de passe actuel -->
		<div class="form-group">
			<label for="currentpassword">Mot de passe actuel</label>
			<input type="password" class="form-control" id="currentpassword" name="currentpassword" placeholder="Mot de passe actuel" required />
		</div>
		<!-- On affiche le champ nouveau mot de passe -->
		<div class="form-group">
			<label for="newpassword">Nouveau mot de passe</label>
			<input type="password" class="form-control" id="newpassword" name="newpassword" placeholder="Nouveau mot de passe" required />
		</div>
		<!-- On affiche le champ confirmation du nouveau mot de passe -->
		<div class="form-group">
			<label for="confirmpassword">Confirmer le nouveau mot de passe</label>
			<input type="password" class="form-control" id="confirmpassword" name="confirmpassword" placeholder="Confirmer le nouveau mot de passe" required />
		</div>
		<!-- On affiche le bouton de validation du formulaire -->
		<button type="submit" name="change" class="btn btn-primary">Changer</button>


	<?php include('includes/footer.php'); ?>
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>