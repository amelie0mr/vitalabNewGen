<?php
session_start();
?>

<!DOCTYPE html>
<html lang="FR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vitalab App</title>
    <link rel="stylesheet" type="text/css" href="comptable.css"/>
    <link rel="icon" href="images/logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body >
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <nav class="navbar">
        <div class="container1"> 
          <img src="images/logo.png" alt="Bootstrap" class="img-nav">
        </div>
        <center><p><h4>Vitalab New Gen</h4></p></center>
        <div class="dropdown">
          <button href="" class="btn41-43 btn-42" onclick="logoutcp()">Déconnexion</button>

          <script>
            //Fonction pour se déconnecter
            function logoutcp() {
              window.location.href = "index.php";
            }
            </script>
        </div>
    </nav>
    <nav class="container2">
        <div class="right">
          <h3><center>Liste notes de frais</center></h3>
            <?php
                // Informations d'identification
                $serveur = "vitalab-new-gen.mysql.database.azure.com";
                $dbname = "vitalab-new-gen";
                $user = "albinrvi";
                $pass = "Ari69.008";                    
                
                // On récupère l'id de l'utilisateur connecté
                if (isset($_SESSION['id_utilisateur'])) {
                  $id_utilisateur_connecte = filter_var($_SESSION['id_utilisateur'], FILTER_VALIDATE_INT);
                } else {
                  // Redirection vers la page de connexion si l'utilisateur n'est pas connecté
                  header("Location: login.php");
                  exit();
                }
      
                try {
                    // Connexion à la base de données
                    $dsn = "mysql:host=$serveur;dbname=$dbname";
                    $pdo = new PDO($dsn, $user, $pass);
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    // Requête SQL pour récupérer les notes de frais de l'utilisateur connecté
                    $sql = "SELECT n.date_facture, n.montant_facture, n.lieu_facture, f.type_frais, n.statut, n.id_note_de_frais, n.intitule
                    FROM note_de_frais n 
                    INNER JOIN type_de_frais f ON n.id_frais = f.id_frais
                    WHERE n.statut = 'En attente' OR n.statut = 'en attente'";
                    $req = $pdo->prepare($sql);
                    $req->execute();

                    // Afficher les notes de frais sous forme de cartes
                    while ($row = $req->fetch(PDO::FETCH_ASSOC)) {

                      $liste_notes_html .= "<div class='card'>";
                      $liste_notes_html .= "<div class='card-body'>";
                      $liste_notes_html .= "<h2 class='card-title'>" . htmlspecialchars($row['intitule']) . "</h2>";
                      $liste_notes_html .= "<h5 class='card-text'>Id de la note de frais : " . htmlspecialchars($row['id_note_de_frais']) . "</h5>";
                      $liste_notes_html .= "<p class='card-title'>Date de facture: " . htmlspecialchars($row['date_facture']) . "</p>";
                      $liste_notes_html .= "<p class='card-text'>Montant: " . htmlspecialchars($row['montant_facture']). " € </p>";
                      $liste_notes_html .= "<p class='card-text'>Lieu: " . htmlspecialchars($row['lieu_facture']) . "</p>";
                      $liste_notes_html .= "<p class='card-text'>Type de frais: " . htmlspecialchars($row['type_frais']) . "</p>";
                      $liste_notes_html .= "<p class='card-text'>Statut: " . htmlspecialchars($row['statut']) . "</p>";
                      $liste_notes_html .= "<form method='post' action='accepter_ndf.php'>";
                      $liste_notes_html .= "<input type='hidden' name='id_note_de_frais' value='" . htmlspecialchars($row['id_note_de_frais']) . "' />";
                      $liste_notes_html .= "<button type='submit' class='btn btn-danger'>Accepter</button>";
                      $liste_notes_html.= "</form>";
                      $liste_notes_html .= "<form method='post' action='refuser_ndf.php'>";
                      $liste_notes_html .= "<input type='hidden' name='id_note_de_frais' value='" . htmlspecialchars($row['id_note_de_frais']) . "' />";
                      $liste_notes_html .= "<button type='submit' class='btn btn-danger'>Refuser</button>";
                      $liste_notes_html.= "</form>";
                      $liste_notes_html .= "</div>";
                      $liste_notes_html .= "</div>";
                  }

                  // Afficher les notes de frais
                  echo $liste_notes_html;

                //Gestion des erreurs
                } catch (PDOException $e) {echo "Erreur : " . $e->getMessage();}

                // Fermer la connexion à la base de données
                $pdo = null;
              ?>
        </div>
    </nav>
</body>
</html>