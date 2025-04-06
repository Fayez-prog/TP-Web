<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Générateur de nombres aléatoires</title>
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
        .numbers {
            font-family: monospace;
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            overflow-x: auto;
        }
        .result {
            padding: 15px;
            margin-top: 20px;
            background-color: #e8f5e9;
            color: #2e7d32;
            border-left: 5px solid #4caf50;
            border-radius: 5px;
            font-weight: bold;
        }
        .criteria {
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
        <h1>Générateur de nombres aléatoires</h1>
        
        <div class="criteria">
            <h3>Conditions de sortie :</h3>
            <ul>
                <li>x doit être pair (x % 2 == 0)</li>
                <li>y doit être impair (y % 2 == 1)</li>
                <li>z doit être impair (z % 2 == 1)</li>
            </ul>
        </div>

        <div class="numbers">
            <?php
            // Initialisation du compteur
            $compteur = 0;
            // Tableau pour stocker les tentatives
            $tentatives = [];
            
            do {
                // Génération des nombres aléatoires
                $x = random_int(0, 1000);
                $y = random_int(0, 1000);
                $z = random_int(0, 1000);
                $compteur++;
                
                // Stockage de la tentative
                $tentatives[] = [
                    'x' => $x,
                    'y' => $y,
                    'z' => $z,
                    'estValide' => ($x % 2 == 0 && $y % 2 == 1 && $z % 2 == 1)
                ];
                
                // Affichage de la tentative
                echo "Tentative $compteur: $x (x), $y (y), $z (z)";
                if (end($tentatives)['estValide']) {
                    echo " <strong>→ Condition remplie!</strong>";
                }
                echo "<br>";
                
            } while (!($x % 2 == 0 && $y % 2 == 1 && $z % 2 == 1));
            ?>
        </div>
        
        <div class="result">
            <?php
            echo "Résultat obtenu en $compteur tentative" . ($compteur > 1 ? 's' : '');
            echo "<br>Dernière combinaison valide : ";
            echo "x = $x (pair), y = $y (impair), z = $z (impair)";
            ?>
        </div>
    </div>
</body>
</html>