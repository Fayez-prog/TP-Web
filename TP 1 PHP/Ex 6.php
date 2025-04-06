<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Premier Programme PHP</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
            color: #333;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 30px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #2c3e50;
            text-align: center;
            margin-top: 0;
        }
        .output {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            margin: 10px 0;
            font-family: monospace;
        }
        .section {
            margin: 20px 0;
            padding: 15px;
            background-color: #e3f2fd;
            border-left: 5px solid #2196f3;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Introduction à PHP</h1>
        
        <div class="section">
            <h2>Affichage de base</h2>
            <div class="output">
                <?php
                // Affichage d'un message de bienvenue
                echo "Bonjour le monde !";
                ?>
            </div>
        </div>
        
        <div class="section">
            <h2>Utilisation des constantes</h2>
            <div class="output">
                <?php
                // Définition de constantes (meilleure pratique que les variables globales)
                const TAUX_TVA = 0.19; // Constante moderne (PHP 5.3+)
                define('REMISE_CLIENT', 0.10); // Ancienne syntaxe mais toujours utile
                
                echo "Taux de TVA: " . (TAUX_TVA * 100) . "%<br>";
                echo "Remise client: " . (REMISE_CLIENT * 100) . "%";
                ?>
            </div>
        </div>
        
        <div class="section">
            <h2>Fonctions et variables</h2>
            <div class="output">
                <?php
                // Déclaration de variables
                $nombreDeBase = 10;
                
                // Fonction avec typage (PHP 7+)
                function multiplier(int $multiplicateur): float {
                    global $nombreDeBase; // Meilleure alternative à $GLOBALS
                    return $nombreDeBase * $multiplicateur;
                }
                
                // Appel de la fonction
                echo "Résultat de la multiplication: " . multiplier(3);
                ?>
            </div>
        </div>
        
        <div class="section">
            <h2>Bonnes pratiques</h2>
            <ul>
                <li>Utilisez <code>const</code> pour les constantes simples</li>
                <li>Évitez les variables globales quand possible</li>
                <li>Utilisez le typage des fonctions (PHP 7+)</li>
                <li>Organisez votre code en sections logiques</li>
                <li>Commentez votre code</li>
            </ul>
        </div>
    </div>
</body>
</html>