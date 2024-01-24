<?php 

error_reporting(E_ALL);
ini_set("display_errors", 1);

// On inclue le fichier de configuration et de connexion a la base de donnees
require_once("includes/config.php");

// On recupere dans $_GET l'email soumis par l'utilisateur
$email = $_GET['email'] ;

error_log($email);

// On verifie que l'email est un email valide (fonction php filter_var)
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    // Si l'email n'est pas valide, on signale l'erreur
		error_log(print_r($email, 1));
    $errors['email'] = "Veuillez entrer une adresse e-mail valide.";
} else {
    // Si c'est bon, on prepare la requete qui recherche la presence de l'email dans la table tblreaders
    $sql = "SELECT EmailId FROM tblreaders WHERE EmailId = :email";
    $query = $dbh->prepare($sql);
    $query->bindParam(':email', $email, PDO::PARAM_STR);

    // On execute la requete et on stocke le resultat de recherche
    $query->execute();
    $result = $query->fetch(PDO::FETCH_ASSOC);
error_log(print_r($result, true));

    // Si le resultat n'est pas vide, on signale a l'utilisateur que cet email existe deja et on desactive le bouton
    // de soumission du formulaire
    if (!empty($result)) {
        echo '{"response":"fail"}';
    } else {
        // Sinon, on signale a l'utilisateur que l'email est disponible et on active le bouton du formulaire
        echo '{"response":"ok"}';
    }
}
//issue from &0 til 11 main point its if filter then cary on th rest of code 

?>
