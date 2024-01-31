# Projet Agouti - Gestion d'une bibliothèque en ligne

## Partie 1 - Technologies utilisées : HTML, CSS, Bootstrap, JS, PHP, MySQL

Ce projet vise à développer une application de gestion d'une bibliothèque en ligne, avec des interfaces distinctes pour les lecteurs et les administrateurs.

### Interface Lecteur

L'interface lecteur comprend un menu en entête donnant accès à l'administration, la création de compte, et la page d'accueil. Ce menu est partagé entre ces pages et celle de récupération du mot de passe.

La page d'accueil propose un formulaire de connexion avec champs pour l'email, le mot de passe, et un code de vérification (captcha). Une fois connecté, le lecteur est redirigé vers son tableau de bord.

#### Processus d'authentification (login):

1. Une session utilisateur est créée (session_start()) pour accéder à $_SESSION.
2. Le tableau $_SESSION est vidé pour invalider le cache de session.
3. L'utilisateur saisit ses identifiants et le captcha, puis soumet le formulaire.
4. Le script captcha.php génère un nombre aléatoire stocké dans $_SESSION['vercode'].
5. Les valeurs de captcha de $_SESSION['vercode'] et $_POST['vercode'] sont comparées.
6. Si égales, recherche de l'utilisateur dans la base avec l'email saisi.
7. Si trouvé, vérification du mot de passe, puis stockage dans $_SESSION['login'] et $_SESSION['id'].
8. Redirection vers la page du tableau de bord (dashboard.php).
9. Vérification que $_SESSION['id'] n'est pas vide sur le tableau de bord. Sinon, redirection vers la page de login.
10. Répétition des étapes 8 et 9 pendant la navigation jusqu'à la déconnexion.

### Page "Créer un compte"

Le formulaire sur cette page comprend des champs pour le nom complet, le numéro de portable, l'email (unique en base de données), mot de passe, et le code de vérification (captcha). Deux fonctions JS sont incluses pour la validation du mot de passe et la vérification de disponibilité de l'email. Un bouton d'enregistrement déclenche une fenêtre pop-up affichant le résultat.

**Note importante:** Les lecteurs sont identifiés par un identifiant unique de la forme 'SIDnnn'.

### Page "Mot de passe oublié"

Le formulaire sur cette page inclut des champs pour l'email, le numéro de portable, le nouveau mot de passe, la confirmation, et le code de vérification (captcha). La fonction JS valid() vérifie l'identité, et si correcte, met à jour le mot de passe dans la base de données.

### Tableau de bord

Le tableau de bord propose un menu donnant accès à différentes pages et un bouton de déconnexion. Deux cartes affichent la quantité de livres empruntés et non rendus par l'utilisateur.

### Page "Mon compte"

Cette page présente un formulaire avec des champs éditables et non éditables pour les informations du lecteur. Un bouton de mise à jour déclenche une fenêtre pop-up avec le résultat de l'opération.

### Page "Livres empruntés"

Une table liste les livres empruntés et rendus par le lecteur avec les détails correspondants.

### Page "Changer mon mot de passe"

Le formulaire sur cette page permet de changer le mot de passe avec vérification de l'identité. Une fenêtre pop-up affiche le résultat.

### Page "Administration"

Cette page inclut un formulaire pour l'administration avec les champs nom, mot de passe, et code de vérification (captcha). Le bouton "login" est actuellement inopérant pour cette partie.

### Base de données

La base de données "library" comprend 6 tables pour les administrateurs, auteurs, livres, catégories, emprunts, et lecteurs.

### Méthodologie de développement

1. Décompresser l'archive Online_library_Part_1.zip sous le répertoire "www" de Wampserver.
2. Créer la base de données "library" via phpMyAdmin et importer le fichier library.sql fourni.
3. Certains codes sont déjà fournis et commentés, notamment l'installation de Bootstrap 4 et Font-Awsome.
4. Suivre l'ordre de développement indiqué dans les commentaires des fichiers fournis.
