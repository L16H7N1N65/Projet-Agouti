<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

// On récupère la session courante
session_start();

// On inclut le fichier de configuration et de connexion à la base de données
include('includes/config.php');

// Tableau initial pour stocker les erreurs de validation
$errors = array();

// Après la soumission du formulaire de compte (plus bas dans ce fichier)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // On vérifie si le code captcha est correct en comparant ce que l'utilisateur a saisi dans le formulaire
    if ($_POST['vercode'] != $_SESSION['vercode']) {
    // $_POST["vercode"] et la valeur initialisée $_SESSION["vercode"] lors de l'appel à captcha.php (voir plus bas)
    echo "<script>alert('Code de vérification incorrect')</script>";
} else {
    // On lit le contenu du fichier readerid.txt au moyen de la fonction 'file'. Ce fichier contient le dernier identifiant lecteur créé.
    $ressourceLue = file('readerid.txt');
    error_log(print_r($ressourceLue, 1));
    // On incrémente de 1 la valeur lue
    $ressourceIncr = ++$ressourceLue[0];
    error_log(print_r($ressourceIncr, 1));
    // On ouvre le fichier readerid.txt en écriture
    $ressource = fopen('readerid.txt', 'c+b');
    // On écrit dans ce fichier la nouvelle valeur
    fwrite($ressource, $ressourceIncr);
    // On referme le fichier
    fclose($ressource);
    error_log(print_r($ressource, 1));

    // On récupère le nom saisi par le lecteur
    $nomComplet = isset($_POST['fullname']) ? $_POST['fullname'] : '';

    // On récupère le numéro de portable
    $numeroPortable = isset($_POST['mobilenumber']) ? $_POST['mobilenumber'] : '';

    // On récupère l'email
    $email = isset($_POST['emailid']) ? $_POST['emailid'] : '';

    // On récupère le mot de passe
    $motDePasse = isset($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : '';

    // On fixe le statut du lecteur à 1 par défaut (actif)
    $statut = 1;

    // Valider le nom complet
    if (empty($nomComplet) || !preg_match("/^[a-zA-Z ]*$/", $nomComplet)) {
        $errors['nom'] = "Le nom complet ne doit contenir que des lettres et des espaces.";
    }

    // Valider le numéro de portable
    if (empty($numeroPortable) || !is_numeric($numeroPortable)) {
        $errors['portable'] = "Le numéro de portable ne doit contenir que des chiffres.";
    }

    // Valider l'email
    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Veuillez entrer une adresse e-mail valide.";
    }

    // Valider le mot de passe
    if (empty($_POST['password']) || strlen($_POST['password']) < 4) {
        $errors['motdepasse'] = "Le mot de passe doit contenir au moins 4 caractères.";
    }

    // Valider la confirmation du mot de passe
    if ($_POST['password'] !== $_POST['confirmPassword']) {
        $errors['confirmation_motdepasse'] = "Les mots de passe ne correspondent pas.";
    }

    if (empty($errors)) {
        error_log(print_r($ressource, 1));
        // On prépare la requête d'insertion en base de données de toutes ces valeurs dans la table tblreaders
        $sqlInsert = "INSERT INTO tblreaders (ReaderId, FullName, MobileNumber, EmailId, Password, Status) VALUES (:readerid, :fullname, :mobilenumber, :email, :password, :status)";
        $queryInsert = $dbh->prepare($sqlInsert);
        $queryInsert->bindParam(':readerid', $ressourceIncr, PDO::PARAM_STR);
        $queryInsert->bindParam(':fullname', $nomComplet, PDO::PARAM_STR);
        $queryInsert->bindParam(':mobilenumber', $numeroPortable, PDO::PARAM_STR);
        $queryInsert->bindParam(':email', $email, PDO::PARAM_STR);
        $queryInsert->bindParam(':password', $motDePasse, PDO::PARAM_STR);
        $queryInsert->bindParam(':status', $statut, PDO::PARAM_INT);

        // On éxecute la requête
        $queryInsert->execute();
        error_log(print_r($ressource, 1));
        // On récupère le dernier id inséré en bd (fonction lastInsertId)
        $lastInsertId = $dbh->lastInsertId();

        // Si ce dernier id existe, on affiche dans une pop-up que l'opération s'est bien déroulée,
        // et on affiche l'identifiant lecteur (valeur de $lastInsertId après incrémentation)
        if ($lastInsertId) {
            error_log(print_r($ressource, 1));
            echo "<script>alert('Bonjour " . $nomComplet . " créé avec succès. Votre identifiant est : " . $ressourceIncr . "')</script>";
            
        } else {
            // Sinon on affiche qu'il y a eu un problème
            echo "<script>alert('Erreur lors de la création du compte')</script>";
        }
    };
}
}
?>

<!DOCTYPE html>
<html lang="FR">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <!--[if IE]>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <![endif]-->
    <title>Gestion de bibliothèque en ligne | Inscription</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
    <!-- GOOGLE FONT -->
    <!-- link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' / -->
    <script type="text/javascript">

        // Fonction pour vérifier la disponibilité de l'email

async function validateEmailAvailability(email) {

    const url = `check_availability.php?email=${encodeURIComponent(email)}`;
console.log(email);
    try {
        const response = await fetch(url);
        console.log(response);
        if (response.ok) {
            const data = await response.json().catch(error => console.error('Erreur de parsing JSON:', error));
            console.log(data);
            const emailErrorElement = document.getElementById('email-error');
            if (data.response === 'ok') {
                emailErrorElement.innerHTML = "";
            } else {
                emailErrorElement.innerHTML = "L'email existe déjà. Veuillez en choisir un autre.";
                emailErrorElement.style.color = "red";
            }
        } else {
            console.error(`Erreur: ${response.status} - ${response.statusText}`);
        }
    } catch (error) {
        console.error('Mon erreur de fetch:', error);
    }
}


// Fonction de validation
function valid() {
    let test = document.getElementById("test");
    let password = document.getElementById('password');
    let confirmPassword = document.getElementById('confirmPassword');
console.log(test);
console.log(password.value);
console.log(confirmPassword.value);

    // Vérification si les mots de passe correspondent
    if (password.value === confirmPassword.value) {
        test.style.color = "green";
        // alert('Les mots de passe ne correspondent pas. Veuillez réessayer. 😕');
        return true;
    } else {
        test.style.color ="red";
        return false;
}
}   

// Fonction pour la validation en temps réel
function validateInput(input, regex, errorMessageId) {
    let value = input.value;
    let errorMessageElement = document.getElementById(errorMessageId);

    if (!regex.test(value)) {
        errorMessageElement.innerHTML = "Format invalide.";
        errorMessageElement.style.color = "red";
        return false;
    } else {
        errorMessageElement.innerHTML = "";
        return true;
    }
}

// Gestionnaire d'événement pour la validation en temps réel
document.addEventListener("DOMContentLoaded", function () {
    let fullNameInput = document.getElementsByName('fullname')[0];
    let mobileNumberInput = document.getElementsByName('mobilenumber')[0];
    let emailInput = document.getElementsByName('emailid')[0];

    fullNameInput.addEventListener("keyup", function () {
        validateInput(fullNameInput, /^[a-zA-Z ]*$/, 'name-error');
    });

    mobileNumberInput.addEventListener("keyup", function () {
        validateInput(mobileNumberInput, /^\d*$/, 'mobile-error');
    });

    emailInput.addEventListener("keyup", function () {
        validateInput(emailInput, /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/, 'email-error');
        validateEmailAvailability(emailInput.value);
    });
});

    </script>
</head>

<body>
    <!-- On inclut le fichier header.php qui contient le menu de navigation-->
    <?php include('includes/header.php'); ?>
    <!--On affiche le titre de la page : CREER UN COMPTE-->
    <!--On affiche le formulaire de creation de compte-->
    <div class="container">
        <div class="row">
            <div class="col">
                <h3>CRÉER UN COMPTE</h3>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-8 offset-md-3">
                
                <form method="post" action="signup.php" onsubmit="return valid();">

                    <div class="form-group">
                        <label>Nom complet</label>
                        <input type="text" name="fullname" value="<?php echo isset($_POST['fullname']) ? $_POST['fullname'] : ''; ?>" required>
                        <!-- Affichage du message d'erreur pour le nom -->
                        <p id="name-error" class="text-danger"></p>
                    </div>

                    <div class="form-group">
                        <label>Numéro de portable</label>
                        <input type="text" name="mobilenumber" value="<?php echo isset($_POST['mobilenumber']) ? $_POST['mobilenumber'] : ''; ?>" required>
                        <!-- Affichage du message d'erreur pour le numéro de portable -->
                        <p id="mobile-error" class="text-danger"></p>
                    </div>

                    <div class="form-group">
                        <label>Entrez votre email</label>
                        <input type="text" name="emailid" id="email" value="<?php echo isset($_POST['emailid']) ? $_POST['emailid'] : ''; ?>" required oninput="validateEmailAvailability(this.value)">
                        <!-- CHECK AGAIN TOMORROW !!! Affichage du message d'erreur pour l'email -->
                        <p id="email-error" class="text-danger"></p>
                    </div>

                    <div class="form-group">
                        <label>Entrez votre mot de passe</label>
                        <input type="password" name="password" required autocomplete="new-password" id ="password">
                        <!-- Affichage du message d'erreur pour le mot de passe -->
                        <p id="password-error" class="text-danger"></p>
                    </div>

                    <div class="form-group">
                        <label id ="test">Confirmez le mot de passe</label>
                        <input type="password" name="confirmPassword" required autocomplete="new-password" id ="confirmPassword" onkeyup="valid()">

                        <!-- Affichage du message d'erreur pour la confirmation du mot de passe -->
                        <p id="confirm-password-error" class="text-danger"></p>
                    </div>
                    
                    <div class="form-group">
						<label>Code de vérification</label>
						<input type="text" name="vercode" required style="height:25px;">&nbsp;&nbsp;&nbsp;<img src="captcha.php">
					</div>

                    <button type="submit" name="signup" class="btn btn-info">CRÉER UN COMPTE</button>

                </form>
            </div>
        </div>
    </div>
    <?php include('includes/footer.php'); ?>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>

<!-- // Note personnelle : -->
<!-- // La syntaxe <<<EOL en PHP est utilisée pour définir une chaîne de caractères en utilisant la syntaxe heredoc. Cela permet de créer une chaîne sur plusieurs lignes sans recourir à la concaténation. EOL est un marqueur, et j'aurais pu choisir n'importe quel identifiant (usually les choix courants sont EOL, EOF, etc). Dans cet exemple, la chaîne commence après <<<EOL et se poursuit jusqu'à ce qu'elle rencontre EOL; au début d'une ligne. Utile pour créer de grands blocs de HTML ou d'autres contenus au sein du code PHP de manière lisible et propre. -->
<!-- 
// Note personnelle :
// La ligne de code "$nomInputInput = document.getElementsByName('$nomInput')[0];" en JS est utilisée pour obtenir la référence de l'élément HTML associé à un input particulier. Le "$nomInput" est une variable qui contient le nom de l'input que je veux cibler. En l'attribuant à "$nomInputInput", je peux interagir avec lui dans le script. Pour effectuer des opérations telles que la vérification des valeurs, la gestion des événements, ou la modification dynamique du contenu de l'élément.
// Fonction pour valider un champ input en temps réel. -->
