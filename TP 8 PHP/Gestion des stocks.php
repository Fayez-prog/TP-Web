<?php
session_start();

// Configuration de la base de données
define('DB_HOST', 'localhost');
define('DB_NAME', 'stock');
define('DB_USER', 'root');
define('DB_PASS', '');

class Database {
    private static $instance = null;
    private $pdo;

    private function __construct() {
        try {
            // Connexion initiale sans spécifier de base
            $tempPdo = new PDO(
                'mysql:host='.DB_HOST.';charset=utf8',
                DB_USER,
                DB_PASS
            );
            $tempPdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Création de la base si elle n'existe pas
            $tempPdo->exec("CREATE DATABASE IF NOT EXISTS ".DB_NAME);
            $tempPdo->exec("USE ".DB_NAME);

            // Connexion à la base spécifique
            $this->pdo = new PDO(
                'mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8',
                DB_USER,
                DB_PASS
            );
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Création des tables
            $this->createTables();
        } catch (PDOException $e) {
            die('Erreur de connexion: ' . $e->getMessage());
        }
    }

    private function createTables() {
        $tables = [
            "CREATE TABLE IF NOT EXISTS users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                username VARCHAR(50) NOT NULL UNIQUE,
                password VARCHAR(255) NOT NULL
            )",
            "CREATE TABLE IF NOT EXISTS articles (
                reference VARCHAR(20) PRIMARY KEY,
                name VARCHAR(50) NOT NULL,
                price DECIMAL(10,2) NOT NULL CHECK (price > 0),
                quantity INT NOT NULL CHECK (quantity >= 0)
            )",
            "CREATE TABLE IF NOT EXISTS suppliers (
                id VARCHAR(20) PRIMARY KEY,
                name VARCHAR(50) NOT NULL,
                contact VARCHAR(50),
                email VARCHAR(50)
            )",
            "CREATE TABLE IF NOT EXISTS sales (
                id INT AUTO_INCREMENT PRIMARY KEY,
                date DATE NOT NULL,
                client VARCHAR(50) NOT NULL,
                amount DECIMAL(10,2) NOT NULL CHECK (amount > 0)
            )",
            "CREATE TABLE IF NOT EXISTS article_supplier (
                article_ref VARCHAR(20),
                supplier_id VARCHAR(20),
                PRIMARY KEY (article_ref, supplier_id),
                FOREIGN KEY (article_ref) REFERENCES articles(reference) ON DELETE CASCADE,
                FOREIGN KEY (supplier_id) REFERENCES suppliers(id) ON DELETE CASCADE
            )"
        ];

        foreach ($tables as $table) {
            $this->pdo->exec($table);
        }

        // Insertion des données de test
        $this->insertTestData();
    }

    private function insertTestData() {
        // Vérifier si les données existent déjà
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM users");
        if ($stmt->fetchColumn() == 0) {
            $password = password_hash('admin123', PASSWORD_DEFAULT);
            $this->pdo->exec("INSERT INTO users VALUES (1, 'admin', '$password')");
            
            $this->pdo->exec("INSERT INTO articles VALUES 
                ('REF001', 'Ordinateur Portable', 799.99, 10),
                ('REF002', 'Smartphone', 499.99, 20),
                ('REF003', 'Tablette', 299.99, 15),
                ('REF004', 'Écran 24\"', 199.99, 8),
                ('REF005', 'Clavier sans fil', 49.99, 30)");
                
            $this->pdo->exec("INSERT INTO suppliers VALUES 
                ('SUP001', 'TechDistrib', 'Jean Dupont', 'contact@techdistrib.com'),
                ('SUP002', 'ElectroPlus', 'Marie Martin', 'contact@electroplus.fr'),
                ('SUP003', 'InnoTech', 'Pierre Lambert', 'pierre@innotech.com'),
                ('SUP004', 'Digital Solutions', 'Sophie Bernard', 'sophie@digitalsolutions.fr'),
                ('SUP005', 'HighTech Import', 'Thomas Leroy', 'thomas@hightechimport.com'),
                ('SUP006', 'ComponentPro', 'Laura Petit', 'laura@componentpro.eu')");
                
            $this->pdo->exec("INSERT INTO sales VALUES 
                (1, '2023-01-15', 'Client A', 1299.98),
                (2, '2023-01-16', 'Client B', 799.99),
                (3, '2023-01-18', 'Client C', 349.98)");
                
            $this->pdo->exec("INSERT INTO article_supplier VALUES
                ('REF001', 'SUP001'),
                ('REF001', 'SUP002'),
                ('REF002', 'SUP002'),
                ('REF002', 'SUP003'),
                ('REF003', 'SUP004'),
                ('REF004', 'SUP005'),
                ('REF004', 'SUP001'),
                ('REF005', 'SUP006'),
                ('REF005', 'SUP003')");
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance->pdo;
    }
}

class Auth {
    public static function login($username, $password) {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        
        if ($user = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if (password_verify($password, $user['password'])) {
                $_SESSION['user'] = $user;
                return true;
            }
        }
        return false;
    }

    public static function isLoggedIn() {
        return isset($_SESSION['user']);
    }

    public static function logout() {
        session_unset();
        session_destroy();
    }
}

class Article {
    public static function getAll($filters = []) {
        $pdo = Database::getInstance();
        $sql = "SELECT * FROM articles WHERE 1=1";
        $params = [];
        
        if (!empty($filters['search'])) {
            $sql .= " AND (reference LIKE ? OR name LIKE ?)";
            $params[] = '%'.$filters['search'].'%';
            $params[] = '%'.$filters['search'].'%';
        }
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function create($data) {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("INSERT INTO articles (reference, name, price, quantity) VALUES (?, ?, ?, ?)");
        return $stmt->execute([
            htmlspecialchars($data['reference']),
            htmlspecialchars($data['name']),
            floatval($data['price']),
            intval($data['quantity'])
        ]);
    }

    public static function update($reference, $data) {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("UPDATE articles SET name = ?, price = ?, quantity = ? WHERE reference = ?");
        return $stmt->execute([
            htmlspecialchars($data['name']),
            floatval($data['price']),
            intval($data['quantity']),
            htmlspecialchars($reference)
        ]);
    }

    public static function delete($reference) {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("DELETE FROM articles WHERE reference = ?");
        return $stmt->execute([htmlspecialchars($reference)]);
    }
}

class Fournisseur {
    public static function getAll($filters = []) {
        $pdo = Database::getInstance();
        $sql = "SELECT * FROM suppliers WHERE 1=1";
        $params = [];
        
        if (!empty($filters['search'])) {
            $sql .= " AND (id LIKE ? OR name LIKE ? OR contact LIKE ? OR email LIKE ?)";
            $searchTerm = '%'.$filters['search'].'%';
            $params = array_fill(0, 4, $searchTerm);
        }
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getForArticle($articleRef) {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("SELECT s.* FROM suppliers s 
                              JOIN article_supplier a_s ON s.id = a_s.supplier_id
                              WHERE a_s.article_ref = ?");
        $stmt->execute([htmlspecialchars($articleRef)]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function create($data) {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("INSERT INTO suppliers (id, name, contact, email) VALUES (?, ?, ?, ?)");
        return $stmt->execute([
            htmlspecialchars($data['id']),
            htmlspecialchars($data['name']),
            htmlspecialchars($data['contact']),
            htmlspecialchars($data['email'])
        ]);
    }

    public static function update($id, $data) {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("UPDATE suppliers SET name = ?, contact = ?, email = ? WHERE id = ?");
        return $stmt->execute([
            htmlspecialchars($data['name']),
            htmlspecialchars($data['contact']),
            htmlspecialchars($data['email']),
            htmlspecialchars($id)
        ]);
    }

    public static function delete($id) {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("DELETE FROM suppliers WHERE id = ?");
        return $stmt->execute([htmlspecialchars($id)]);
    }
}

class Vente {
    public static function getAll($filters = []) {
        $pdo = Database::getInstance();
        $sql = "SELECT * FROM sales WHERE 1=1";
        $params = [];
        
        if (!empty($filters['search'])) {
            $sql .= " AND (client LIKE ? OR amount = ?)";
            $params[] = '%'.$filters['search'].'%';
            
            // Vérifie si la recherche est un montant numérique
            if (is_numeric($filters['search'])) {
                $params[] = floatval($filters['search']);
            } else {
                $params[] = 0; // Valeur qui ne correspondra à aucun montant
            }
        }
        
        $sql .= " ORDER BY date DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function create($data) {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("INSERT INTO sales (date, client, amount) VALUES (?, ?, ?)");
        return $stmt->execute([
            htmlspecialchars($data['date']),
            htmlspecialchars($data['client']),
            floatval($data['amount'])
        ]);
    }

    public static function delete($id) {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("DELETE FROM sales WHERE id = ?");
        return $stmt->execute([intval($id)]);
    }
}

// Initialisation de la base de données
$db = Database::getInstance();

// Traitement des actions
$message = '';
$error = '';
$current_section = isset($_GET['section']) ? htmlspecialchars($_GET['section']) : 'articles';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (isset($_POST['login'])) {
            if (Auth::login($_POST['username'], $_POST['password'])) {
                header("Location: ?");
                exit;
            } else {
                $error = "Identifiants incorrects";
            }
        }
        
        if (isset($_POST['logout'])) {
            Auth::logout();
            header("Location: ?");
            exit;
        }
        
        if (isset($_POST['add_article'])) {
            if (Article::create([
                'reference' => $_POST['reference'],
                'name' => $_POST['name'],
                'price' => $_POST['price'],
                'quantity' => $_POST['quantity']
            ])) {
                $message = "Article ajouté avec succès";
            } else {
                $error = "Erreur lors de l'ajout de l'article";
            }
            header("Location: ?section=articles&message=".urlencode($message)."&error=".urlencode($error));
            exit;
        }
        
        if (isset($_POST['update_article'])) {
            if (Article::update($_POST['reference'], [
                'name' => $_POST['name'],
                'price' => $_POST['price'],
                'quantity' => $_POST['quantity']
            ])) {
                $message = "Article modifié avec succès";
            } else {
                $error = "Erreur lors de la modification de l'article";
            }
            header("Location: ?section=articles&message=".urlencode($message)."&error=".urlencode($error));
            exit;
        }
        
        if (isset($_POST['delete_article'])) {
            if (Article::delete($_POST['reference'])) {
                $message = "Article supprimé avec succès";
            } else {
                $error = "Erreur lors de la suppression de l'article";
            }
            header("Location: ?section=articles&message=".urlencode($message)."&error=".urlencode($error));
            exit;
        }

        if (isset($_POST['add_supplier'])) {
            if (Fournisseur::create([
                'id' => $_POST['id'],
                'name' => $_POST['name'],
                'contact' => $_POST['contact'],
                'email' => $_POST['email']
            ])) {
                $message = "Fournisseur ajouté avec succès";
            } else {
                $error = "Erreur lors de l'ajout du fournisseur";
            }
            header("Location: ?section=fournisseurs&message=".urlencode($message)."&error=".urlencode($error));
            exit;
        }

        if (isset($_POST['update_supplier'])) {
            if (Fournisseur::update($_POST['id'], [
                'name' => $_POST['name'],
                'contact' => $_POST['contact'],
                'email' => $_POST['email']
            ])) {
                $message = "Fournisseur modifié avec succès";
            } else {
                $error = "Erreur lors de la modification du fournisseur";
            }
            header("Location: ?section=fournisseurs&message=".urlencode($message)."&error=".urlencode($error));
            exit;
        }

        if (isset($_POST['delete_supplier'])) {
            if (Fournisseur::delete($_POST['id'])) {
                $message = "Fournisseur supprimé avec succès";
            } else {
                $error = "Erreur lors de la suppression du fournisseur";
            }
            header("Location: ?section=fournisseurs&message=".urlencode($message)."&error=".urlencode($error));
            exit;
        }

        if (isset($_POST['add_sale'])) {
            if (Vente::create([
                'date' => $_POST['date'],
                'client' => $_POST['client'],
                'amount' => $_POST['amount']
            ])) {
                $message = "Vente enregistrée avec succès";
            } else {
                $error = "Erreur lors de l'enregistrement de la vente";
            }
            header("Location: ?section=ventes&message=".urlencode($message)."&error=".urlencode($error));
            exit;
        }

        if (isset($_POST['delete_sale'])) {
            if (Vente::delete($_POST['id'])) {
                $message = "Vente supprimée avec succès";
            } else {
                $error = "Erreur lors de la suppression de la vente";
            }
            header("Location: ?section=ventes&message=".urlencode($message)."&error=".urlencode($error));
            exit;
        }
    } catch (PDOException $e) {
        $error = "Erreur: " . $e->getMessage();
        header("Location: ?section=$current_section&error=".urlencode($error));
        exit;
    }
}

// Récupération des données
$articles = Article::getAll(['search' => $_GET['search'] ?? '']);
$fournisseurs = Fournisseur::getAll(['search' => $_GET['search_supplier'] ?? '']);
$ventes = Vente::getAll(['search' => $_GET['search_sale'] ?? '']);
$message = isset($_GET['message']) ? htmlspecialchars($_GET['message']) : '';
$error = isset($_GET['error']) ? htmlspecialchars($_GET['error']) : '';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion de Stock</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        .sidebar {
            min-height: 100vh;
            background: #f8f9fa;
            border-right: 1px solid #dee2e6;
        }
        .sidebar .nav-link {
            border-radius: 5px;
            margin-bottom: 5px;
        }
        .sidebar .nav-link.active {
            background: #0d6efd;
            color: white;
        }
        .main-content {
            padding: 20px;
        }
        .table-responsive {
            overflow-x: auto;
        }
        .logo {
            font-weight: bold;
            color: #0d6efd;
        }
        .form-control:invalid {
            border-color: #dc3545;
        }
    </style>
</head>
<body>
    <?php if (!Auth::isLoggedIn()): ?>
        <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
            <div class="card" style="width: 400px;">
                <div class="card-body">
                    <h2 class="card-title text-center mb-4">Connexion</h2>
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?= $error ?></div>
                    <?php endif; ?>
                    <form method="POST">
                        <div class="mb-3">
                            <label for="username" class="form-label">Nom d'utilisateur</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Mot de passe</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <button type="submit" name="login" class="btn btn-primary w-100">Se connecter</button>
                    </form>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="container-fluid">
            <div class="row">
                <!-- Sidebar -->
                <div class="col-md-3 col-lg-2 sidebar p-3">
                    <h4 class="mb-4 logo">GestionStock</h4>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link <?= $current_section === 'articles' ? 'active' : '' ?>" href="?section=articles">
                                <i class="bi bi-box-seam"></i> Articles
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= $current_section === 'fournisseurs' ? 'active' : '' ?>" href="?section=fournisseurs">
                                <i class="bi bi-truck"></i> Fournisseurs
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= $current_section === 'ventes' ? 'active' : '' ?>" href="?section=ventes">
                                <i class="bi bi-cash-stack"></i> Ventes
                            </a>
                        </li>
                        <li class="nav-item mt-4">
                            <form method="POST">
                                <button type="submit" name="logout" class="nav-link text-danger">
                                    <i class="bi bi-box-arrow-right"></i> Déconnexion
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>

                <!-- Main Content -->
                <div class="col-md-9 col-lg-10 main-content">
                    <?php if ($message): ?>
                        <div class="alert alert-success alert-dismissible fade show">
                            <?= $message ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($error): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <?= $error ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if ($current_section === 'articles'): ?>
                        <h2 class="mb-4"><i class="bi bi-box-seam"></i> Gestion des Articles</h2>
                        
                        <!-- Formulaire de recherche -->
                        <form class="mb-4">
                            <div class="input-group">
                                <input type="hidden" name="section" value="articles">
                                <input type="text" class="form-control" name="search" placeholder="Rechercher..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                                <button class="btn btn-primary" type="submit">
                                    <i class="bi bi-search"></i> Rechercher
                                </button>
                                <a href="?section=articles" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-counterclockwise"></i> Réinitialiser
                                </a>
                            </div>
                        </form>
                        
                        <!-- Liste des articles -->
                        <div class="card mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Liste des articles</h5>
                                <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addArticleModal">
                                    <i class="bi bi-plus"></i> Ajouter
                                </button>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>Référence</th>
                                                <th>Nom</th>
                                                <th>Prix</th>
                                                <th>Quantité</th>
                                                <th>Fournisseurs</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($articles as $article): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($article['reference']) ?></td>
                                                    <td><?= htmlspecialchars($article['name']) ?></td>
                                                    <td><?= number_format($article['price'], 2, ',', ' ') ?> €</td>
                                                    <td><?= $article['quantity'] ?></td>
                                                    <td>
                                                        <?php 
                                                        $suppliers = Fournisseur::getForArticle($article['reference']);
                                                        echo count($suppliers) > 0 
                                                            ? implode(', ', array_map('htmlspecialchars', array_column($suppliers, 'name'))) 
                                                            : 'Aucun';
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editArticleModal" 
                                                            data-reference="<?= htmlspecialchars($article['reference']) ?>"
                                                            data-name="<?= htmlspecialchars($article['name']) ?>"
                                                            data-price="<?= htmlspecialchars($article['price']) ?>"
                                                            data-quantity="<?= htmlspecialchars($article['quantity']) ?>">
                                                            <i class="bi bi-pencil"></i>
                                                        </button>
                                                        <form method="POST" style="display: inline;">
                                                            <input type="hidden" name="reference" value="<?= htmlspecialchars($article['reference']) ?>">
                                                            <button type="submit" name="delete_article" class="btn btn-danger btn-sm" onclick="return confirm('Confirmer la suppression ?')">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Modal Ajout Article -->
                        <div class="modal fade" id="addArticleModal" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form method="POST" id="addArticleForm">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Ajouter un article</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Référence</label>
                                                <input type="text" class="form-control" name="reference" required 
                                                       pattern="[A-Za-z0-9]{3,20}" title="3 à 20 caractères alphanumériques">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Nom</label>
                                                <input type="text" class="form-control" name="name" required minlength="3" maxlength="50">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Prix</label>
                                                <input type="number" step="0.01" min="0.01" class="form-control" name="price" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Quantité</label>
                                                <input type="number" min="0" class="form-control" name="quantity" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                            <button type="submit" name="add_article" class="btn btn-primary">Enregistrer</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Modal Modification Article -->
                        <div class="modal fade" id="editArticleModal" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form method="POST" id="editArticleForm">
                                        <input type="hidden" name="reference" id="editReference">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Modifier l'article</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Nom</label>
                                                <input type="text" class="form-control" name="name" id="editName" required minlength="3" maxlength="50">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Prix</label>
                                                <input type="number" step="0.01" min="0.01" class="form-control" name="price" id="editPrice" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Quantité</label>
                                                <input type="number" min="0" class="form-control" name="quantity" id="editQuantity" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                            <button type="submit" name="update_article" class="btn btn-primary">Enregistrer</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        
                    <?php elseif ($current_section === 'fournisseurs'): ?>
                        <h2 class="mb-4"><i class="bi bi-truck"></i> Gestion des Fournisseurs</h2>
                        
                        <!-- Formulaire de recherche -->
                        <form class="mb-4">
                            <div class="input-group">
                                <input type="hidden" name="section" value="fournisseurs">
                                <input type="text" class="form-control" name="search_supplier" placeholder="Rechercher fournisseur..." value="<?= htmlspecialchars($_GET['search_supplier'] ?? '') ?>">
                                <button class="btn btn-primary" type="submit">
                                    <i class="bi bi-search"></i> Rechercher
                                </button>
                                <a href="?section=fournisseurs" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-counterclockwise"></i> Réinitialiser
                                </a>
                            </div>
                        </form>
                        
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Liste des fournisseurs</h5>
                                <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addSupplierModal">
                                    <i class="bi bi-plus"></i> Ajouter
                                </button>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Nom</th>
                                                <th>Contact</th>
                                                <th>Email</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($fournisseurs as $fournisseur): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($fournisseur['id']) ?></td>
                                                    <td><?= htmlspecialchars($fournisseur['name']) ?></td>
                                                    <td><?= htmlspecialchars($fournisseur['contact']) ?></td>
                                                    <td><?= htmlspecialchars($fournisseur['email']) ?></td>
                                                    <td>
                                                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editSupplierModal"
                                                            data-id="<?= htmlspecialchars($fournisseur['id']) ?>"
                                                            data-name="<?= htmlspecialchars($fournisseur['name']) ?>"
                                                            data-contact="<?= htmlspecialchars($fournisseur['contact']) ?>"
                                                            data-email="<?= htmlspecialchars($fournisseur['email']) ?>">
                                                            <i class="bi bi-pencil"></i>
                                                        </button>
                                                        <form method="POST" style="display: inline;">
                                                            <input type="hidden" name="id" value="<?= htmlspecialchars($fournisseur['id']) ?>">
                                                            <button type="submit" name="delete_supplier" class="btn btn-danger btn-sm" onclick="return confirm('Confirmer la suppression ?')">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Ajout Fournisseur -->
                        <div class="modal fade" id="addSupplierModal" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form method="POST" id="addSupplierForm">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Ajouter un fournisseur</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">ID Fournisseur</label>
                                                <input type="text" class="form-control" name="id" required 
                                                       pattern="[A-Za-z0-9]{3,20}" title="3 à 20 caractères alphanumériques">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Nom</label>
                                                <input type="text" class="form-control" name="name" required minlength="3" maxlength="50">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Contact</label>
                                                <input type="text" class="form-control" name="contact" maxlength="50">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Email</label>
                                                <input type="email" class="form-control" name="email" maxlength="50">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                            <button type="submit" name="add_supplier" class="btn btn-primary">Enregistrer</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Modification Fournisseur -->
                        <div class="modal fade" id="editSupplierModal" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form method="POST" id="editSupplierForm">
                                        <input type="hidden" name="id" id="editSupplierId">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Modifier le fournisseur</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Nom</label>
                                                <input type="text" class="form-control" name="name" id="editSupplierName" required minlength="3" maxlength="50">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Contact</label>
                                                <input type="text" class="form-control" name="contact" id="editSupplierContact" maxlength="50">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Email</label>
                                                <input type="email" class="form-control" name="email" id="editSupplierEmail" maxlength="50">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                            <button type="submit" name="update_supplier" class="btn btn-primary">Enregistrer</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        
                    <?php elseif ($current_section === 'ventes'): ?>
                        <h2 class="mb-4"><i class="bi bi-cash-stack"></i> Gestion des Ventes</h2>
                        
                        <!-- Formulaire de recherche -->
                        <form class="mb-4">
                            <div class="input-group">
                                <input type="hidden" name="section" value="ventes">
                                <input type="text" class="form-control" name="search_sale" placeholder="Rechercher vente..." value="<?= htmlspecialchars($_GET['search_sale'] ?? '') ?>">
                                <button class="btn btn-primary" type="submit">
                                    <i class="bi bi-search"></i> Rechercher
                                </button>
                                <a href="?section=ventes" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-counterclockwise"></i> Réinitialiser
                                </a>
                            </div>
                        </form>
                        
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Historique des ventes</h5>
                                <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addSaleModal">
                                    <i class="bi bi-plus"></i> Nouvelle vente
                                </button>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Date</th>
                                                <th>Client</th>
                                                <th>Montant</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($ventes as $vente): ?>
                                                <tr>
                                                    <td><?= $vente['id'] ?></td>
                                                    <td><?= date('d/m/Y', strtotime($vente['date'])) ?></td>
                                                    <td><?= htmlspecialchars($vente['client']) ?></td>
                                                    <td><?= number_format($vente['amount'], 2, ',', ' ') ?> €</td>
                                                    <td>
                                                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#viewSaleModal"
                                                            data-id="<?= $vente['id'] ?>"
                                                            data-date="<?= date('Y-m-d', strtotime($vente['date'])) ?>"
                                                            data-client="<?= htmlspecialchars($vente['client']) ?>"
                                                            data-amount="<?= $vente['amount'] ?>">
                                                            <i class="bi bi-eye"></i>
                                                        </button>
                                                        <form method="POST" style="display: inline;">
                                                            <input type="hidden" name="id" value="<?= $vente['id'] ?>">
                                                            <button type="submit" name="delete_sale" class="btn btn-danger btn-sm" onclick="return confirm('Confirmer la suppression ?')">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Ajout Vente -->
                        <div class="modal fade" id="addSaleModal" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form method="POST" id="addSaleForm">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Nouvelle vente</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Date</label>
                                                <input type="date" class="form-control" name="date" required value="<?= date('Y-m-d') ?>">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Client</label>
                                                <input type="text" class="form-control" name="client" required minlength="3" maxlength="50">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Montant</label>
                                                <input type="number" step="0.01" min="0.01" class="form-control" name="amount" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                            <button type="submit" name="add_sale" class="btn btn-primary">Enregistrer</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Voir Vente -->
                        <div class="modal fade" id="viewSaleModal" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Détails de la vente</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">ID</label>
                                            <p id="viewSaleId"></p>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Date</label>
                                            <p id="viewSaleDate"></p>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Client</label>
                                            <p id="viewSaleClient"></p>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Montant</label>
                                            <p id="viewSaleAmount"></p>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // Gestion des modals
    const editArticleModal = document.getElementById('editArticleModal');
    if (editArticleModal) {
        editArticleModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            document.getElementById('editReference').value = button.getAttribute('data-reference');
            document.getElementById('editName').value = button.getAttribute('data-name');
            document.getElementById('editPrice').value = button.getAttribute('data-price');
            document.getElementById('editQuantity').value = button.getAttribute('data-quantity');
        });
    }

    const editSupplierModal = document.getElementById('editSupplierModal');
    if (editSupplierModal) {
        editSupplierModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            document.getElementById('editSupplierId').value = button.getAttribute('data-id');
            document.getElementById('editSupplierName').value = button.getAttribute('data-name');
            document.getElementById('editSupplierContact').value = button.getAttribute('data-contact');
            document.getElementById('editSupplierEmail').value = button.getAttribute('data-email');
        });
    }

    const viewSaleModal = document.getElementById('viewSaleModal');
    if (viewSaleModal) {
        viewSaleModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            document.getElementById('viewSaleId').textContent = button.getAttribute('data-id');
            document.getElementById('viewSaleDate').textContent = button.getAttribute('data-date');
            document.getElementById('viewSaleClient').textContent = button.getAttribute('data-client');
            document.getElementById('viewSaleAmount').textContent = parseFloat(button.getAttribute('data-amount')).toFixed(2) + ' €';
        });
    }

    // Validation des formulaires
    const forms = document.querySelectorAll('form[id$="Form"]');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!this.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
            }
            this.classList.add('was-validated');
        }, false);
    });

    // Fermer automatiquement les messages d'alerte après 5 secondes
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    });
    </script>
</body>
</html>