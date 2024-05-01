<?php
// Informations d'identification de la base de données
$serveur = "vitalab-new-gen.mysql.database.azure.com";
$dbname = "vitalab-new-gen";
$user = "albinrvi";
$pass = "Ari69.008";

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    session_start( );
    $id_utilisateur = $_SESSION['id_utilisateur'];

    // Récupérer les données du formulaire
    $id_note_de_frais = $_POST["id_modif"];
    $intitule = $_POST["intitule"];
    $date_facture = $_POST["date"];
    $montant_facture = $_POST["montant"];
    $lieu_facture = $_POST["lieu"];
    $type_frais = $_POST["id_frais"];

    if (!empty($intitule) && !empty($date_facture) && !empty($montant_facture) && !empty($lieu_facture) && !empty($type_frais) && !empty($id_utilisateur)){
        try {
            // Se connecter à la base de données
            $dsn = "mysql:host=$serveur;dbname=$dbname";
            $pdo = new PDO($dsn, $user, $pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            //Requete qui récupère la valeur du statut de la note de frais
            $statut = $pdo->query("SELECT statut FROM note_de_frais WHERE id_note_de_frais = $id_note_de_frais")->fetch(PDO::FETCH_ASSOC);

            if ($statut['statut'] == 'En attente') {
                $sql = $pdo->query("UPDATE note_de_frais
                SET intitule = $intitule,
                    date_facture = $date_facture,
                    montant_facture = $montant_facture,
                    lieu_facture = $lieu_facture,
                    type_frais = $type_frais,
                WHERE id_note_de_frais = $id_note_de_frais");


                // Message de succès
                session_start();
                $_SESSION['success_message'] = "Note de frais ajoutée avec succès.";

                // Rediriger vers une autre page
                header("Location: commercial.php");
                exit();
            } else {
                // Message d'erreur
                session_start();
                $_SESSION['success_message'] = "La note de frais ne peut pas être modifiée.";
                
                // Rediriger vers une autre page
                header("Location: commercial.php");
                exit();
            }

        // Gestion des erreurs
        } catch (PDOException $e) {
            echo "Erreur : " . $e->getMessage();
        }

    } else {
        // Message d'erreur
        session_start();
        $_SESSION['success_message'] = "Veuillez remplir tous les champs.";

        // Rediriger vers une autre page
        header("Location: commercial.php");
        exit();
    }
    // Fermer la connexion à la base de données
    $pdo = null;
}
?>