<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jeu de Devinette Aléatoire</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f9fa;
            color: #212529;
        }
        .game-container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #2c3e50;
            text-align: center;
            margin-bottom: 30px;
        }
        .result {
            padding: 20px;
            margin: 20px 0;
            background-color: #e8f5e9;
            border-left: 5px solid #4caf50;
            border-radius: 5px;
            font-size: 1.2em;
            text-align: center;
        }
        .stats {
            background-color: #e3f2fd;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .btn {
            display: inline-block;
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .btn:hover {
            background-color: #388e3c;
        }
        .progress-container {
            height: 10px;
            background-color: #e9ecef;
            border-radius: 5px;
            margin: 20px 0;
            overflow: hidden;
        }
        .progress-bar {
            height: 100%;
            background-color: #4CAF50;
            width: 0%;
            transition: width 0.5s;
        }
    </style>
</head>
<body>
    <div class="game-container">
        <h1>Jeu de Devinette Aléatoire</h1>
        
        <div class="stats">
            <p>Recherche du nombre <strong>789</strong> entre 0 et 1000</p>
            <div class="progress-container">
                <div class="progress-bar" id="progressBar"></div>
            </div>
        </div>

        <?php
        // Configuration
        $nombreAChercher = 789;
        $min = 0;
        $max = 1000;
        $tentatives = [];
        
        // Boucle de recherche optimisée
        for($coup = 1; ; $coup++) {
            $proposition = random_int($min, $max);
            $tentatives[] = $proposition;
            
            if ($proposition == $nombreAChercher) {
                break;
            }
            
            // Limite de sécurité pour éviter les boucles infinies
            if ($coup >= 10000) {
                echo '<div class="result" style="background-color:#ffebee;border-color:#f44336;">';
                echo "Arrêt après 10 000 tentatives sans trouver le nombre";
                echo "</div>";
                exit;
            }
        }
        
        // Calcul du pourcentage de l'intervalle testé
        $pourcentage = round(($coup / ($max - $min + 1)) * 100, 2);
        ?>
        
        <div class="result">
            <p>Nombre <strong><?= $nombreAChercher ?></strong> trouvé en <strong><?= $coup ?></strong> tentative<?= $coup > 1 ? 's' : '' ?></p>
            <p>Probabilité initiale: 0.1% (1/1001)</p>
            <p>Pourcentage de l'intervalle testé: <?= $pourcentage ?>%</p>
        </div>
        
        <a href="<?= $_SERVER['PHP_SELF'] ?>" class="btn">Nouvelle partie</a>
        
        <script>
            // Animation de la barre de progression
            document.addEventListener('DOMContentLoaded', function() {
                const progressBar = document.getElementById('progressBar');
                const percentage = <?= $pourcentage ?>;
                progressBar.style.width = Math.min(percentage, 100) + '%';
            });
        </script>
    </div>
</body>
</html>