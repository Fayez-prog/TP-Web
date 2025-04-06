<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire de traitement</title>
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
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #2c3e50;
            text-align: center;
            margin-bottom: 30px;
        }
        .quick-link {
            display: block;
            text-align: center;
            margin-bottom: 20px;
        }
        form {
            display: grid;
            gap: 20px;
        }
        .form-group {
            display: grid;
            gap: 5px;
        }
        label {
            font-weight: 600;
        }
        input[type="text"] {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        input[type="text"]:focus {
            border-color: #3498db;
            outline: none;
            box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.2);
        }
        button {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 12px;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #2980b9;
        }
        @media (max-width: 480px) {
            .container {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Formulaire d'information</h1>
        
        <a href="Traitement.php?nom=IIT-2024&prenom=Sfax" class="quick-link">
            Afficher les valeurs par défaut
        </a>
        
        <form action="Traitement.php" method="post">
            <div class="form-group">
                <label for="nom">Votre nom</label>
                <input type="text" name="nom" id="nom" required 
                       placeholder="Entrez votre nom">
            </div>
            
            <div class="form-group">
                <label for="prenom">Votre prénom</label>
                <input type="text" name="prenom" id="prenom" required
                       placeholder="Entrez votre prénom">
            </div>
            
            <button type="submit" name="valider">Valider</button>
        </form>
    </div>
</body>
</html>