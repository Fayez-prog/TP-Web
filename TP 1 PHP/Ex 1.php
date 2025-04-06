<!DOCTYPE html>
<html lang="fr">  <!-- Changé à 'fr' pour un site en français -->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenue sur notre site</title>  <!-- Titre plus descriptif -->
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
            color: #333;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        h1 {
            color: #2c3e50;
            margin-top: 0;
        }
        .welcome-message {
            font-size: 1.2em;
            margin-bottom: 20px;
            color: #3498db;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php
        // Déclaration de variable avec commentaire
        $nombreVisites = 10;  // Variable renommée pour plus de clarté
        
        // Affichage du message de bienvenue
        echo '<h1>Bienvenue sur notre site</h1>';  // Correction de l'orthographe
        echo '<p class="welcome-message">Nous sommes heureux de vous accueillir</p>';
        
        // Affichage de la variable avec contexte
        echo '<p>Ce site a déjà été visité ' . htmlspecialchars($nombreVisites) . ' fois.</p>';
        ?>
    </div>
</body>
</html>