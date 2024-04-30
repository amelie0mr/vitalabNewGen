<?php
// Informations d'identification de la base de données
$serveur = "vitalab-new-gen.mysql.database.azure.com";
$dbname = "vitalab-new-gen";
$user = "albinrvi";
$pass = "Ari69.008";

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {


    // Récupérer les données du formulaire
    $intitule = $_POST["intitule"];
    $date_facture = $_POST["date"];
    $montant_facture = $_POST["montant"];
    $lieu_facture = $_POST["lieu"];
    $type_frais = $_POST["id_frais"];
    $id_utilisateur = $_POST["id_utilisateur"];
    $statut = "En attente"; // Statut par défaut

    echo $montant_facture,$date_facture,$lieu_facture;

    try {
        // Se connecter à la base de données
        $dsn = "mysql:host=$serveur;dbname=$dbname";
        $pdo = new PDO($dsn, $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = $pdo->query("INSERT INTO note_de_frais (intitule, date_facture, montant_facture, lieu_facture, image_facture, id_frais, id_utilisateur, statut) VALUES ('$intitule', '$date_facture', '$montant_facture', '$lieu_facture', 'image', '$type_frais', '$id_utilisateur', '$statut')");

        echo "La note de frais a été ajoutée avec succès.";

    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }

    // Fermer la connexion à la base de données
    $pdo = null;
}
?>