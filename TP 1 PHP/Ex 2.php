<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vérification des multiples</title>
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
            max-width: 600px;
            margin: 20px auto;
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
        .result {
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
            font-size: 1.2em;
            text-align: center;
        }
        .multiple {
            background-color: #e8f5e9;
            color: #2e7d32;
            border-left: 5px solid #4caf50;
        }
        .not-multiple {
            background-color: #ffebee;
            color: #c62828;
            border-left: 5px solid #f44336;
        }
        .input-form {
            margin-top: 30px;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 5px;
        }
        input[type="number"] {
            padding: 10px;
            width: 100%;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            box-sizing: border-box;
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
            width: 100%;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #388e3c;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Vérification des multiples</h1>
        
        <?php
        // Fonction pour vérifier si un nombre est multiple de 3 et 5
        function estMultipleDe3Et5($nombre) {
            return ($nombre % 3 == 0 && $nombre % 5 == 0);
        }

        // Valeur par défaut ou valeur soumise
        $x = isset($_POST['nombre']) ? (int)$_POST['nombre'] : 1245;
        
        // Vérification et affichage du résultat
        if (estMultipleDe3Et5($x)) {
            echo '<div class="result multiple">';
            echo htmlspecialchars($x) . ' est divisible par 3 et 5 simultanément';
        } else {
            echo '<div class="result not-multiple">';
            echo htmlspecialchars($x) . ' n\'est pas divisible par 3 et 5 simultanément';
        }
        echo '</div>';
        ?>
        
        <div class="input-form">
            <form method="post">
                <label for="nombre">Tester un autre nombre :</label>
                <input type="number" id="nombre" name="nombre" required 
                       value="<?php echo htmlspecialchars($x); ?>">
                <button type="submit">Vérifier</button>
            </form>
        </div>
    </div>
</body>
</html>