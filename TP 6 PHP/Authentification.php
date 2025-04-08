<?php
/**
 * Exercice 1 - Solution complète en un fichier
 * Authentification avec la classe User
 */

class User {
    private $login;
    private $password;

    public function __construct($login, $password) {
        $this->login = $login;
        $this->password = $password;
    }

    public function __get($property) {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
    }

    public function __set($property, $value) {
        if (property_exists($this, $property)) {
            $this->$property = $value;
        }
        return $this;
    }

    public function __isset($property) {
        return isset($this->$property);
    }

    public function __toString() {
        return "Connexion réussie pour l'utilisateur: " . $this->login;
    }

    public function connect() {
        return ($this->login === 'admin' && $this->password === 'admin');
    }
}

// Traitement du formulaire si soumis
$error = false;
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = $_POST['login'] ?? '';
    $password = $_POST['password'] ?? '';
    
    $user = new User($login, $password);
    
    if ($user->connect()) {
        $success = true;
    } else {
        $error = true;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authentification</title>
    <style>
        :root {
            --primary-color: #3498db;
            --success-color: #2ecc71;
            --error-color: #e74c3c;
            --text-color: #2c3e50;
            --light-gray: #ecf0f1;
            --medium-gray: #bdc3c7;
        }
        
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            color: var(--text-color);
            line-height: 1.6;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 450px;
            overflow: hidden;
            transition: transform 0.3s ease;
        }
        
        .container:hover {
            transform: translateY(-5px);
        }
        
        .header {
            background: var(--primary-color);
            color: white;
            padding: 20px;
            text-align: center;
        }
        
        .header h1 {
            font-size: 1.8rem;
            font-weight: 600;
        }
        
        .content {
            padding: 30px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
        }
        
        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid var(--medium-gray);
            border-radius: 6px;
            font-size: 1rem;
            transition: border 0.3s;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            outline: none;
        }
        
        .btn {
            display: inline-block;
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 600;
            width: 100%;
            transition: all 0.3s;
        }
        
        .btn:hover {
            background: #2980b9;
            transform: translateY(-2px);
        }
        
        .alert {
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .alert-error {
            background-color: #fadbd8;
            color: var(--error-color);
        }
        
        .alert-success {
            background-color: #d5f5e3;
            color: #27ae60;
            padding: 25px;
            margin-bottom: 30px;
        }
        
        .user-info {
            background: var(--light-gray);
            padding: 15px;
            border-radius: 6px;
            margin-top: 20px;
        }
        
        .user-info p {
            margin-bottom: 10px;
        }
        
        .logout-link {
            display: inline-block;
            margin-top: 20px;
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
        }
        
        .logout-link:hover {
            text-decoration: underline;
        }
        
        @media (max-width: 480px) {
            .content {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Système d'Authentification</h1>
        </div>
        
        <div class="content">
            <?php if ($success): ?>
                <div class="alert alert-success">
                    <h2>Bienvenue!</h2>
                    <p><?php echo $user; ?></p>
                </div>
                
                <div class="user-info">
                    <p><strong>Login:</strong> <?php echo htmlspecialchars($user->__get('login')); ?></p>
                    <p><strong>Statut:</strong> Administrateur</p>
                </div>
                
                <a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="logout-link">Se déconnecter</a>
                
            <?php else: ?>
                <?php if ($error): ?>
                    <div class="alert alert-error">
                        Identifiants incorrects! Veuillez réessayer.
                    </div>
                <?php endif; ?>
                
                <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <div class="form-group">
                        <label for="login">Nom d'utilisateur</label>
                        <input type="text" class="form-control" id="login" name="login" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Mot de passe</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    
                    <button type="submit" class="btn">Se connecter</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>