<?php
// Informations d'identification de la base de données
$serveur = "vitalab-new-gen.mysql.database.azure.com";
$dbname = "vitalab-new-gen";
$user = "albinrvi";
$pass = "Ari69.008";

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    session_start();
    
    // Valider l'existence et le type de données de session
    if (isset($_SESSION['id_utilisateur']) && is_numeric($_SESSION['id_utilisateur'])) {
           $id_utilisateur = $_SESSION['id_utilisateur'];
    } else {
        // Rediriger vers la page de connexion
        $_SESSION['error_message'] = "Identifiant ou mot de passe incorrect.";
        header("Location:index.php");
        exit;
    }

    // Récupérer les données du formulaire
    $intitule = filter_input(INPUT_POST, 'intitule');
    $date_facture = filter_input(INPUT_POST, 'date');
    $montant_facture = filter_input(INPUT_POST, 'montant', FILTER_VALIDATE_FLOAT);
    $lieu_facture = filter_input(INPUT_POST, 'lieu');
    $id_frais = filter_input(INPUT_POST, 'id_frais', FILTER_VALIDATE_INT);
    $id_note_de_frais = filter_input(INPUT_POST, 'id_modif', FILTER_VALIDATE_INT);

    // Vérifier si les données sont valides
    if ($intitule && $date_facture && $montant_facture !== false && $lieu_facture && $id_frais && $id_note_de_frais) {
        try {
            // Se connecter à la base de données de manière sécurisée
            $pdo = new PDO("mysql:host=$serveur;dbname=$dbname", $user, $pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Requête sécurisée pour récupérer le statut de la note de frais
            $statut = $pdo->query("SELECT statut FROM note_de_frais WHERE id_note_de_frais = $id_note_de_frais")->fetch(PDO::FETCH_ASSOC);

            if ($statut['statut'] == 'En attente' || $statut['statut'] == 'en attente') {
                $stmt = $pdo->prepare("UPDATE note_de_frais
                SET intitule = :intitule,
                    date_facture = :date_facture,
                    montant_facture = :montant_facture,
                    lieu_facture = :lieu_facture,
                    id_frais = :id_frais
                WHERE id_note_de_frais = :id_note_de_frais");

                // Liaison des valeurs aux paramètres liés
                $stmt->bindParam(':intitule', $intitule);
                $stmt->bindParam(':date_facture', $date_facture);
                $stmt->bindParam(':montant_facture', $montant_facture);
                $stmt->bindParam(':lieu_facture', $lieu_facture);
                $stmt->bindParam(':id_frais', $id_frais, PDO::PARAM_INT);
                $stmt->bindParam(':id_note_de_frais', $id_note_de_frais, PDO::PARAM_INT);

                // Exécution de la requête
                $stmt->execute();

                // Message de succès
                $_SESSION['success_message'] = "Note de frais modifiée avec succès.";
                header("Location: commercial.php");
                exit();

            } else {
                // Message d'erreur
                $_SESSION['success_message'] = "La note de frais ne peut pas être modifiée.";
                header("Location: commercial.php");
                exit();
            }

        // Gestion des erreurs
        } catch (PDOException $e) {
            echo "Erreur : " . $e->getMessage();
        } finally {
            // Fermer la connexion à la base de données
            $pdo = null;
        }
    } else {
        // Message d'erreur
        $_SESSION['success_message'] = "Veuillez remplir tous les champs.";
        header("Location: commercial.php");
        exit();
    }
    $_SESSION['success_message'] = "Un problème est survenu, veuillez réessayez !.";
    header("Location: commercial.php");
    exit();
}
?>