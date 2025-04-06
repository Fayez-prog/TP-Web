<?php
// Fichier: Traitement.php

// Vérification et sécurisation des données
function sanitizeInput($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

// Initialisation des variables
$nom = '';
$prenom = '';

// Traitement des données selon la méthode (GET ou POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['valider'])) {
    // Traitement du formulaire POST
    $nom = isset($_POST['nom']) ? sanitizeInput($_POST['nom']) : '';
    $prenom = isset($_POST['prenom']) ? sanitizeInput($_POST['prenom']) : '';
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && (isset($_GET['nom']) || isset($_GET['prenom']))) {
    // Traitement des paramètres GET
    $nom = isset($_GET['nom']) ? sanitizeInput($_GET['nom']) : '';
    $prenom = isset($_GET['prenom']) ? sanitizeInput($_GET['prenom']) : '';
}

// Affichage des résultats avec une structure HTML complète
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultats du traitement</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .result-container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        h1 {
            color: #2c3e50;
            margin-top: 0;
        }
        .info {
            background-color: #e8f4fc;
            padding: 15px;
            border-left: 4px solid #3498db;
            margin: 10px 0;
        }
        .back-link {
            display: inline-block;
            margin-top: 20px;
            color: #3498db;
            text-decoration: none;
        }
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="result-container">
        <h1>Résultats du traitement</h1>
        
        <?php if (!empty($nom) || !empty($prenom)): ?>
            <div class="info">
                <p><strong>Nom:</strong> <?php echo $nom; ?></p>
                <p><strong>Prénom:</strong> <?php echo $prenom; ?></p>
            </div>
        <?php else: ?>
            <div class="info">
                <p>Aucune donnée valide n'a été reçue.</p>
            </div>
        <?php endif; ?>
        
        <a href="index.html" class="back-link">← Retour au formulaire</a>
    </div>
</body>
</html>
