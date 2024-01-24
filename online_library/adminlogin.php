<?php
// On demarre ou on recupere la session courante
session_start();

error_log('Session started');

error_log("SESSION : ".print_r($_SESSION, 1));
error_log("POST : ".print_r($_POST, 1));

// On inclue le fichier de configuration et de connexion � la base de donn�es
include('includes/config.php');

// On invalide le cache de session $_SESSION['alogin'] = ''
if (isset($_SESSION['alogin']) && $_SESSION['alogin'] != '') {
    $_SESSION['alogin'] = '';
}

// A faire :
if (isset($_POST['alogin'])) {
// Apres la soumission du formulaire de login (plus bas dans ce fichier)
// On verifie si le code captcha est correct en comparant ce que l'utilisateur a saisi dans le formulaire

// $_POST["vercode"] et la valeur initialis�e $_SESSION["vercode"] lors de l'appel a captcha.php (voir plus bas
    if (isset($_POST["vercode"]) && isset($_SESSION["vercode"])) {
    // Vérification supplementaire sur le Captcha !<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<< HERE!
        error_log('Session vercode: ' . $_SESSION["vercode"]. ' ' . gettype($_SESSION["vercode"]));
        if ((int)$_POST["vercode"] !== (int)$_SESSION["vercode"]) {
        echo "<script>alert('Code de vérification incorrect')</script>";
// Le code est correct, on peut continuer
} else {

// On recupere le nom de l'utilisateur saisi dans le formulaire
$adminUserName = isset($_POST['UserName']) ? $_POST['UserName'] : '';
// On recupere le mot de passe saisi par l'utilisateur et on le crypte (fonction md5)
$motDePasse = isset($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : '';

// On construit la requete qui permet de retrouver l'utilisateur a partir de son nom et de son mot de passe

$sqlAdmin = "SELECT UserName, Password FROM `admin` WHERE UserName = :UserName ";
error_log($sqlAdmin);
$query = $dbh->prepare($sqlAdmin);
$query->bindParam(':UserName', $adminUserName, PDO::PARAM_STR); 


$query->execute();
error_log('Query executed');
$results = $query->fetch();
error_log(print_r($results, 1));


// depuis la table admin
// Si le resultat de recherche n'est pas vide 
if ($query->rowCount() > 0) {

    if (password_verify($motDePasse, $results['Password']));

    // On stocke le nom de l'utilisateur  $_POST['username'] en session $_SESSION
    $_SESSION['UserName'] = $results['UserName'];
   
    // On redirige l'utilisateur vers le tableau de bord administration (n'existe pas encore)
    
    // sinon le login est refuse. On le signal par une popup
    } else {
        echo "<script>alert('Login refusé !')</script>"; 
    } 

        }
    }
}




?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <title>Gestion de bibliotheque en ligne</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
</head>

<body>
    <!-- On inclue le fichier header.php qui contient le menu de navigation-->
    <?php include('includes/header.php'); ?>

    <div class="content-wrapper">
        <!--On affiche le titre de la page-->

        <!--On affiche le formulaire de login-->
        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-8 offset-md-3">
                
                <form method="post" action="adminlogin.php" onsubmit="return valid();">

                    <div class="form-group">
                        <label>Admin ID</label>
                        <input type="text" name="UserName" value="<?php echo isset($_POST['UserName']) ? $_POST['UserName'] : ''; ?>" required>
                      

                        <p id="name-error" class="text-danger"></p>
                    </div>

                    <div class="form-group">
						<label>Entrez votre mot de passe</label>
						<input type="password" name="password" required>
						<p>
							<a href="user-forgot-password.php">Mot de passe oublié ?</a>
						</p>
					</div>
					<!--A la suite de la zone de saisie du captcha, on insere l'image cree par captcha.php : <img src="captcha.php">  -->
					<div class="form-group">
						<label>Code de vérification</label>
						<input type="text" name="vercode" required style="height:25px;">&nbsp;&nbsp;&nbsp;<img src="captcha.php">
					</div>

					<button type="submit" name="alogin" class="btn btn-info">LOGIN</button>&nbsp;&nbsp;&nbsp;
                     <!-- <a href="signup.php">Je n'ai pas de compte</a> -->
				</form>
			</div>
		</div>
</div>
        <!--A la suite de la zone de saisie du captcha, on ins�re l'image cr��e par captcha.php : <img src="captcha.php">  -->
    </div>
    <!-- CONTENT-WRAPPER SECTION END-->
    <?php include('includes/footer.php'); ?>
    <!-- FOOTER SECTION END-->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>