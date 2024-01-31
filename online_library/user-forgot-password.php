<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);


// On récupère la session courante
session_start();
error_log('Session started');
error_log(print_r($_POST, 1));
error_log(print_r($_SESSION, 1));


// On inclut le fichier de configuration et de connexion à la base de données
include('includes/config.php');

// Après la soumission du formulaire de login ($_POST['change'] existe)
if (isset($_POST['change'])) {
    // On vérifie si le code captcha est correct en comparant ce que l'utilisateur a saisi dans le formulaire
    // $_POST["vercode"] et la valeur initialisee $_SESSION["vercode"] lors de l'appel a captcha.php (voir plus bas)
    if (isset($_POST["vercode"]) && isset($_SESSION["vercode"])) {
     // Vérification supplementaire sur le Captcha !<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<< HERE!
     error_log('Submitted vercode: ' . $_POST["vercode"]. ' ' . gettype($_POST["vercode"]));
     error_log('Session vercode: ' . $_SESSION["vercode"]. ' ' . gettype($_SESSION["vercode"]));

     if ((int)$_POST["vercode"] !== (int)$_SESSION["vercode"]) {
         echo "<script>alert('Code de vérification incorrect')</script>";
     
//     if (isset($_POST["vercode"]) && isset($_SESSION["vercode"]) && $_POST["vercode"] !== $_SESSION["vercode"]) {
        
    } else {
        
        // On continue
        // On récupère l'email et le numéro de portable saisi par l'utilisateur
        $email = isset($_POST['emailid']) ? $_POST['emailid'] : '';
        error_log('Email: ' . $email);

        $numeroPortable = isset($_POST['mobilenumber']) ? $_POST['mobilenumber'] : '';
        error_log('Phone Number: ' . $numeroPortable);

        // On vérifie en base de données s'il existe un lecteur avec cet email et ce numéro de téléphone
        $sql = "SELECT * FROM tblreaders WHERE EmailId = :email AND MobileNumber = :mobileNumber";
        $query = $dbh->prepare($sql);
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->bindParam(':mobileNumber', $numeroPortable, PDO::PARAM_STR);
        $query->execute();
        error_log('Query executed');

        // Si le résultat de recherche n'est pas vide
        if ($query->rowCount() > 0) {
            // On génère un nouveau mot de passe
            $newPassword = generateRandomPassword();
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

            // On met à jour la table tblreaders avec le nouveau mot de passe
            $updateSql = "UPDATE tblreaders SET Password = :password WHERE EmailId = :email AND MobileNumber = :mobileNumber";
            $updateQuery = $dbh->prepare($updateSql);
            $updateQuery->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
            $updateQuery->bindParam(':email', $email, PDO::PARAM_STR);
            $updateQuery->bindParam(':mobileNumber', $numeroPortable, PDO::PARAM_STR);
            $updateQuery->execute();
            error_log('Password updated');

            // Informer l'utilisateur par une fenêtre popup de la réussite de l'opération
            echo "<script>alert('Mot de passe réinitialisé avec succès. Votre nouveau mot de passe est : " . $newPassword . "')</script>";
        } else {
            // Informer l'utilisateur par une fenêtre popup de l'échec de l'opération
            echo "<script>alert('Aucun compte trouvé avec l\'email et le numéro de portable fournis. Veuillez réessayer.')</script>";
          }
     }
 } else {
     // Vérification supplementaire sur le Captcha !<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<< HERE!
     error_log('Vercode is not set ');
     echo "<script>alert('Code de vérification manquant')</script>";
 }
}

// Fonction pour générer un mot de passe aléatoire
function generateRandomPassword($length = 6) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $password = '';
    for ($i = 0; $i < $length; $i++) {
        $password .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $password;
}
?>



<!DOCTYPE html>
<html lang="FR">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <title>Gestion de bibliotheque en ligne | Recuperation de mot de passe </title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />

    <script type="text/javascript">
    // On crée une fonction nommée valid() qui vérifie que les deux mots de passe saisis par l'utilisateur sont identiques.
    // Fonction de validation
    function valid() {
        let test = document.getElementById("test");
        let password = document.getElementById('password');
        let confirmPassword = document.getElementById('checkPassword');
        console.log('Validation function called');
        console.log('Password value:', password.value);
        console.log('Confirm Password value:', confirmPassword.value);

        // Vérification si les mots de passe correspondent
        if (password.value === confirmPassword.value) {
            test.style.color = "green";
            console.log('Les mots de passe correspondent.');
            return true;
        } else {
            test.style.color = "red";
            console.log('Les mots de passe ne correspondent pas. Veuillez réessayer. 😕');
            return false;
        }
     }
     </script>


</head>

<body>
    <!-- On inclut ici le menu de navigation includes/header.php-->
    <?php include('includes/header.php'); ?>
    <!-- On insère le titre de la page (RECUPERATION MOT DE PASSE -->
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-9 col-lg-7 offset-md-3 form-control">
                <h3>Mot de passe oublié</h3>
            </div>
        </div>
        <div class="row">
            <!--On insère le formulaire de login-->
            <div class="col-xs-12 col-sm-12 col-md-9 col-lg-7 offset-md-3 form-control">

                <form method="post" action="user-forgot-password.php" onsubmit="return valid()">

                <div class="form-group">
                        <label>Entrez votre email</label>
                        <input type="text" name="emailid" id="email" value="<?php echo isset($_POST['emailid']) ? $_POST['emailid'] : ''; ?>" required oninput="validateEmailAvailability(this.value) ">
                        <p id="email-error" class="text-danger"></p>
                    </div>

                    <div class="form-group">
                        <label>Numéro de portable</label>
                        <input type="text" name="mobilenumber" value="<?php echo isset($_POST['mobilenumber']) ? $_POST['mobilenumber'] : ''; ?>" required>
                        <!-- Affichage du message d'erreur pour le numéro de portable -->
                        <p id="mobile-error" class="text-danger"></p>
                    </div>

                    <div class="form-group">
                        <label>Entrez votre mot de passe</label>
                        <input type="password" name="password" required autocomplete="new-password" id ="password">
                        <!-- Affichage du message d'erreur pour le mot de passe -->
                        <p id="password-error" class="text-danger"></p>
                    </div>

                        <div class="form-group">
                            <label id="test">Confirmez votre nouveau mot de passe</label>
                            <input type="password" name="checkPassword" onkeyup="valid()" id="checkPassword" required>
                        </div>
                        <!--A la suite de la zone de saisie du captcha, on insère l'image créée par captcha.php : <img src="captcha.php">  -->
                        <div class="form-group">
						<label>Code de vérification</label>
						<input type="text" name="vercode" required style="height:25px;">&nbsp;&nbsp;&nbsp;<img src="captcha.php">
					</div>

                        <button type="submit" name="change" class="btn btn-info">SUBMIT</button>&nbsp;&nbsp;&nbsp;<a
                            href="signup.php">Je n'ai pas de compte</a>
                </form>
            </div>
        </div>
    </div>
    </div>
</body>

</html>


