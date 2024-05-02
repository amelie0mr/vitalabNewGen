<?php
session_start();
$_SESSION['error_message'] = "";
$_SESSION['success_message'] = "";
$_SESSION['id_utilisateur'] = "";
$_SESSION['role'] = "";

// Informations d'identification
$serveur = "vitalab-new-gen.mysql.database.azure.com";
$dbname = "vitalab-new-gen";
$user = "albinrvi";
$pass = "Ari69.008";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Récupère les données du formulaire
    $login = filter_input(INPUT_POST, 'login');
    $password = filter_input(INPUT_POST, 'password');

    try {
        // Connexion à la base de données
        $dbco = new PDO("mysql:host=$serveur;dbname=$dbname", $user, $pass);
        $dbco->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        //On vérifie si le login existe déjà et si le mot de passe correspond
        $sql = $dbco->prepare("SELECT * FROM utilisateur WHERE Nom_utilisateur = :login AND mot_de_passe = :password");
        $sql->bindParam('login', $login);
        $sql->bindParam('password', $password);
        $sql->execute();
        $row = $sql->fetch(PDO::FETCH_ASSOC);

        // Vérifie si une ligne a été retournée et ajoute des valeurs aux varaibles
        if ($row) {
            $id_utilisateur = $row['id_utilisateur'];
            $role = $row['id_role'];
        }

        // Si le login et le mot de passe correspondent, on redirige vers la page correspondante
        if ($role == '1') {
            $_SESSION['id_utilisateur'] = $id_utilisateur;
            header("Location:admin.php");
            exit;
        } else if ($role == '2') {
            $_SESSION['id_utilisateur'] =  $id_utilisateur;
            header("Location:comptable.php");
            exit;
        } else if ($role == '3') {
            $_SESSION['id_utilisateur'] =  $id_utilisateur;
            header("Location:commercial.php");
            exit;

        // Sinon on affiche un message d'erreur
        } else {
            $_SESSION['error_message_not_enleve'] = "Identifiant ou mot de passe incorrect.";
            header("Location:index.php");
            exit;
        }

    // Gestion des erreurs
    } catch (PDOException $e) {
        $_SESSION['error_message_not_enleve'] = "Une erreur s'est produite lors de la connexion à la base de données.";
        header("Location: index.php");
        exit();
    }}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vitalab App</title>
    <link rel="stylesheet" type="text/css" href="formulaire.css" />
    <link rel="icon" href="#">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <?php echo '<div class="card">
        <center><img src="images/logo.png" class="img-com-so" style="width: 18rem;"></center>
        <form method="post" action=""> 
            <p>&nbsp;</p>
            <p class="card-text" >Identifiant</p>
            <input type="text" name="login">
            <p class="card-text" >Mot de passe</p>
            <input type="password" name="password"> 
            <center><button type="submit" class="bn1">Connexion</button></center> 
        </form>
    </div>'; ?>
    
    <div>
        <?php     
        // Vérifier si un message d'erreur est défini dans la session
            if (isset($_SESSION['error_message_not_enleve'])) {
                // Afficher le message d'erreur'
                echo "<p>" . $_SESSION['error_message_not_enleve'] . "</p>";

                // Supprimer le message de la session pour qu'il ne s'affiche plus après un rafraîchissement de la page
                unset($_SESSION['error_message_not_enleve']);}
          ?>
    </div>
</body>
</html>
