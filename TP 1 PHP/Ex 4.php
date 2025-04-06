<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jeu de Devinette Aléatoire</title>
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
        .attempts {
            max-height: 300px;
            overflow-y: auto;
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            font-family: monospace;
        }
        .attempt {
            padding: 3px 0;
        }
        .success {
            color: #2e7d32;
            font-weight: bold;
        }
        .result {
            padding: 15px;
            margin-top: 20px;
            background-color: #e8f5e9;
            color: #2e7d32;
            border-left: 5px solid #4caf50;
            border-radius: 5px;
            font-size: 1.2em;
            text-align: center;
        }
        .stats {
            margin-top: 20px;
            padding: 15px;
            background-color: #e3f2fd;
            border-left: 5px solid #2196f3;
            border-radius: 5px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 12px 20px;
            margin-top: 10px;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #388e3c;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Jeu de Devinette Aléatoire</h1>
        
        <div class="stats">
            <p>Recherche du nombre <strong>789</strong> entre 0 et 1000</p>
        </div>

        <div class="attempts">
            <?php
            // Configuration
            $nombreAChercher = 789;
            $min = 0;
            $max = 1000;
            $compteur = 0;
            $tentatives = [];
            
            // Boucle de recherche
            do {
                $proposition = random_int($min, $max);
                $compteur++;
                $estTrouve = ($proposition == $nombreAChercher);
                
                // Enregistrer la tentative
                $tentatives[] = [
                    'numero' => $compteur,
                    'valeur' => $proposition,
                    'trouve' => $estTrouve
                ];
                
                // Afficher la tentative
                echo '<div class="attempt' . ($estTrouve ? ' success' : '') . '">';
                echo "Tentative #$compteur: $proposition";
                if ($estTrouve) {
                    echo " ← Trouvé!";
                }
                echo '</div>';
                
            } while ($proposition != $nombreAChercher);
            ?>
        </div>
        
        <div class="result">
            <?php
            echo "Nombre $nombreAChercher trouvé en $compteur tentative" . ($compteur > 1 ? 's' : '');
            
            // Calcul des statistiques
            $pourcentage = round(($compteur / ($max - $min + 1)) * 100, 2);
            echo "<br>Probabilité initiale: 0.1% (1/1001)";
            echo "<br>Nombre d'essais: $compteur ($pourcentage% de l'intervalle)";
            ?>
        </div>
        
        <button onclick="window.location.reload()">Nouvelle partie</button>
    </div>
</body>
</html>