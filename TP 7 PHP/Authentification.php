<?php
// Configuration de la base de données
define('DB_HOST', 'localhost');
define('DB_NAME', 'shopping');
define('DB_USER', 'root');
define('DB_PASS', '');

class AuthSystem {
    private $pdo;
    
    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        try {
            // Tentative de connexion à la base spécifique
            $this->pdo = new PDO(
                'mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8',
                DB_USER,
                DB_PASS
            );
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            if ($e->getCode() == 1049) { // Base inconnue
                $this->createDatabase();
            } else {
                die('Erreur de connexion: ' . $e->getMessage());
            }
        }
    }
    
    private function createDatabase() {
        try {
            // Connexion sans spécifier de base de données
            $tempPdo = new PDO(
                'mysql:host='.DB_HOST.';charset=utf8',
                DB_USER,
                DB_PASS
            );
            $tempPdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Création de la base de données
            $tempPdo->exec("CREATE DATABASE IF NOT EXISTS ".DB_NAME);
            $tempPdo->exec("USE ".DB_NAME);
            
            // Création de la table users
            $tempPdo->exec("CREATE TABLE IF NOT EXISTS users (
                login VARCHAR(20) PRIMARY KEY,
                password VARCHAR(20) NOT NULL
            )");
            
            // Insertion d'un utilisateur test
            $stmt = $tempPdo->prepare("INSERT IGNORE INTO users (login, password) VALUES (?, ?)");
            $stmt->execute(['admin', 'admin123']);
            
            // Réinitialisation de la connexion PDO avec la nouvelle base
            $this->pdo = $tempPdo;
        } catch (PDOException $e) {
            die('Erreur lors de la création de la base: ' . $e->getMessage());
        }
    }
    
    public function connect($login, $password) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE login = ? AND password = ?");
        $stmt->execute([$login, $password]);
        
        if ($user = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $_SESSION['user'] = $user;
            return true;
        }
        return false;
    }
    
    public function isConnected() {
        return isset($_SESSION['user']);
    }
    
    public function disconnect() {
        unset($_SESSION['user']);
        session_destroy();
    }
}

// Traitement des actions
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $auth = new AuthSystem();
    
    switch ($_POST['action']) {
        case 'login':
            if ($auth->connect($_POST['login'], $_POST['password'])) {
                $message = "Connexion réussie!";
            } else {
                $message = "Identifiants incorrects!";
            }
            break;
            
        case 'logout':
            $auth->disconnect();
            $message = "Déconnexion réussie!";
            break;
    }
}

// Affichage
$auth = new AuthSystem();
$isConnected = $auth->isConnected();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authentification</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --success-color: #4cc9f0;
            --danger-color: #f72585;
            --light-color: #f8f9fa;
            --dark-color: #212529;
            --border-radius: 8px;
            --box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            color: var(--dark-color);
        }

        .container {
            background-color: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            width: 100%;
            max-width: 450px;
            padding: 40px;
            transition: var(--transition);
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
            color: var(--primary-color);
            font-weight: 600;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--dark-color);
        }

        input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: var(--border-radius);
            font-family: inherit;
            font-size: 16px;
            transition: var(--transition);
        }

        input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.2);
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: var(--border-radius);
            font-family: inherit;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
            margin-top: 10px;
        }

        button:hover {
            background-color: var(--secondary-color);
            transform: translateY(-2px);
        }

        .message {
            padding: 15px;
            margin-bottom: 25px;
            border-radius: var(--border-radius);
            text-align: center;
            font-weight: 500;
        }

        .success {
            background-color: rgba(76, 201, 240, 0.2);
            color: #0a9396;
            border: 1px solid rgba(76, 201, 240, 0.5);
        }

        .error {
            background-color: rgba(247, 37, 133, 0.2);
            color: var(--danger-color);
            border: 1px solid rgba(247, 37, 133, 0.5);
        }

        .welcome-message {
            text-align: center;
            margin-bottom: 30px;
            font-size: 18px;
        }

        .logout-btn {
            background-color: var(--danger-color);
        }

        .logout-btn:hover {
            background-color: #d0006e;
        }

        @media (max-width: 480px) {
            .container {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        
        <?php if (!empty($message)): ?>
            <div class="message <?= strpos($message, 'incorrect') !== false ? 'error' : 'success' ?>">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>
        
        <?php if ($isConnected): ?>
            <div class="welcome-message">
                <p>Bienvenue, <strong><?= htmlspecialchars($_SESSION['user']['login']) ?></strong>!</p>
                <p>Vous êtes maintenant connecté.</p>
            </div>
            <form method="POST">
                <input type="hidden" name="action" value="logout">
                <button type="submit" class="logout-btn">Se déconnecter</button>
            </form>
        <?php else: ?>
            <h1>Connexion</h1>
            <form method="POST">
                <input type="hidden" name="action" value="login">
                
                <div class="form-group">
                    <label for="login">Nom d'utilisateur</label>
                    <input type="text" id="login" name="login" required maxlength="20" placeholder="Entrez votre login">
                </div>
                
                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" required maxlength="20" placeholder="Entrez votre mot de passe">
                </div>
                
                <button type="submit">Se connecter</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>