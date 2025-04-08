<?php
// Configuration de la base de données
define('DB_HOST', 'localhost');
define('DB_NAME', 'shopping');
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

            // Création des tables
            $this->createTables($tempPdo);

            $this->pdo = $tempPdo;
        } catch (PDOException $e) {
            die('Erreur de connexion: ' . $e->getMessage());
        }
    }

    private function createTables($pdo) {
        // Table article
        $pdo->exec("CREATE TABLE IF NOT EXISTS article (
            ref VARCHAR(20) PRIMARY KEY,
            libelle VARCHAR(20),
            prix INT(4),
            `Qt en stock` INT(4)
        )");

        // Table fournisseur
        $pdo->exec("CREATE TABLE IF NOT EXISTS fournisseur (
            id VARCHAR(20) PRIMARY KEY,
            nom VARCHAR(20)
        )");

        // Table article_fournisseur avec ON DELETE CASCADE
        $pdo->exec("CREATE TABLE IF NOT EXISTS article_fournisseur (
            ref VARCHAR(20),
            id VARCHAR(20),
            PRIMARY KEY (ref, id),
            FOREIGN KEY (ref) REFERENCES article(ref) ON DELETE CASCADE,
            FOREIGN KEY (id) REFERENCES fournisseur(id) ON DELETE CASCADE
        )");

        // Données de test
        $this->insertTestData($pdo);
    }

    private function insertTestData($pdo) {
        // Articles
        $pdo->exec("INSERT IGNORE INTO article VALUES 
            ('REF001', 'Ordinateur', 800, 10),
            ('REF002', 'Smartphone', 500, 20),
            ('REF003', 'Tablette', 300, 15)");

        // Fournisseurs
        $pdo->exec("INSERT IGNORE INTO fournisseur VALUES 
            ('FOURN001', 'TechDistrib'),
            ('FOURN002', 'ElectroPlus'),
            ('FOURN003', 'DigitalWorld')");

        // Relations article-fournisseur
        $pdo->exec("INSERT IGNORE INTO article_fournisseur VALUES 
            ('REF001', 'FOURN001'),
            ('REF001', 'FOURN002'),
            ('REF002', 'FOURN002'),
            ('REF003', 'FOURN003')");
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance->pdo;
    }
}

class Article {
    public static function ajouter($article) {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("INSERT INTO article (ref, libelle, prix, `Qt en stock`) VALUES (?, ?, ?, ?)");
        return $stmt->execute([
            $article['ref'],
            $article['libelle'],
            $article['prix'],
            $article['qt_stock']
        ]);
    }

    public static function modifier($ref, $champs) {
        $pdo = Database::getInstance();
        $sql = "UPDATE article SET ";
        $params = [];
        
        foreach ($champs as $key => $value) {
            // Échapper les noms de colonnes avec des espaces
            $escapedKey = strpos($key, ' ') !== false ? "`$key`" : $key;
            $sql .= "$escapedKey = ?, ";
            $params[] = $value;
        }
        
        $sql = rtrim($sql, ', ') . " WHERE ref = ?";
        $params[] = $ref;
        
        $stmt = $pdo->prepare($sql);
        return $stmt->execute($params);
    }

    public static function supprimer($ref) {
        $pdo = Database::getInstance();
        
        // Solution avec transaction et suppression en cascade manuelle
        $pdo->beginTransaction();
        
        try {
            // 1. D'abord supprimer les relations dans article_fournisseur
            $stmt = $pdo->prepare("DELETE FROM article_fournisseur WHERE ref = ?");
            $stmt->execute([$ref]);
            
            // 2. Ensuite supprimer l'article lui-même
            $stmt = $pdo->prepare("DELETE FROM article WHERE ref = ?");
            $stmt->execute([$ref]);
            
            $pdo->commit();
            return true;
        } catch (PDOException $e) {
            $pdo->rollBack();
            return false;
        }
    }

    public static function tousLesArticles($filtres = []) {
        $pdo = Database::getInstance();
        $sql = "SELECT * FROM article WHERE 1=1";
        $params = [];
        
        if (!empty($filtres['ref'])) {
            $sql .= " AND ref LIKE ?";
            $params[] = '%' . $filtres['ref'] . '%';
        }
        
        if (!empty($filtres['libelle'])) {
            $sql .= " AND libelle LIKE ?";
            $params[] = '%' . $filtres['libelle'] . '%';
        }
        
        if (!empty($filtres['prix_min'])) {
            $sql .= " AND prix >= ?";
            $params[] = $filtres['prix_min'];
        }
        
        if (!empty($filtres['prix_max'])) {
            $sql .= " AND prix <= ?";
            $params[] = $filtres['prix_max'];
        }
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

class Fournisseur {
    public static function tousLesFournisseurs() {
        $pdo = Database::getInstance();
        $stmt = $pdo->query("SELECT * FROM fournisseur");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

// Traitement des actions
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    try {
        switch ($_POST['action']) {
            case 'ajouter_article':
                $article = [
                    'ref' => $_POST['ref'],
                    'libelle' => $_POST['libelle'],
                    'prix' => $_POST['prix'],
                    'qt_stock' => $_POST['qt_stock']
                ];
                
                if (Article::ajouter($article)) {
                    $message = "Article ajouté avec succès!";
                } else {
                    $message = "Erreur lors de l'ajout de l'article";
                }
                break;
                
            case 'modifier_article':
                $champs = [];
                if (!empty($_POST['new_libelle'])) $champs['libelle'] = $_POST['new_libelle'];
                if (!empty($_POST['new_prix'])) $champs['prix'] = $_POST['new_prix'];
                if (!empty($_POST['new_qt_stock'])) $champs['Qt en stock'] = $_POST['new_qt_stock'];
                
                if (Article::modifier($_POST['ref'], $champs)) {
                    $message = "Article modifié avec succès!";
                } else {
                    $message = "Erreur lors de la modification de l'article";
                }
                break;
                
            case 'supprimer_article':
                if (Article::supprimer($_POST['ref'])) {
                    $message = "Article supprimé avec succès!";
                } else {
                    $message = "Erreur lors de la suppression de l'article";
                }
                break;
        }
    } catch (PDOException $e) {
        $message = "Erreur: " . $e->getMessage();
    }
}

// Récupération des données
$articles = Article::tousLesArticles($_GET);
$fournisseurs = Fournisseur::tousLesFournisseurs();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Articles | Shopping</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --primary: #4361ee;
            --primary-dark: #3f37c9;
            --secondary: #4cc9f0;
            --danger: #f72585;
            --success: #4ade80;
            --warning: #f59e0b;
            --light: #f8fafc;
            --dark: #1e293b;
            --gray: #94a3b8;
            --gray-light: #e2e8f0;
            --radius: 0.5rem;
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f1f5f9;
            color: var(--dark);
            line-height: 1.6;
        }

        .container {
            max-width: 1400px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        header {
            background: white;
            padding: 1.5rem;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            margin-bottom: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        h1 {
            color: var(--primary);
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        h1 i {
            color: var(--primary-dark);
        }

        .card {
            background: white;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        h2 {
            font-size: 1.25rem;
            margin-bottom: 1rem;
            color: var(--primary);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        h2 i {
            font-size: 1rem;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1rem;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--dark);
        }

        input, select {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid var(--gray-light);
            border-radius: var(--radius);
            font-family: inherit;
            transition: var(--transition);
        }

        input:focus, select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.2);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: var(--radius);
            font-family: inherit;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
            text-decoration: none;
        }

        .btn-primary {
            background-color: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
        }

        .btn-danger {
            background-color: var(--danger);
            color: white;
        }

        .btn-danger:hover {
            background-color: #d0006e;
            transform: translateY(-2px);
        }

        .btn-sm {
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
        }

        .message {
            padding: 1rem;
            margin-bottom: 1.5rem;
            border-radius: var(--radius);
            font-weight: 500;
        }

        .message-success {
            background-color: rgba(74, 222, 128, 0.2);
            color: #16a34a;
            border: 1px solid rgba(74, 222, 128, 0.5);
        }

        .message-error {
            background-color: rgba(247, 37, 133, 0.2);
            color: var(--danger);
            border: 1px solid rgba(247, 37, 133, 0.5);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: var(--shadow);
        }

        th, td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid var(--gray-light);
        }

        th {
            background-color: var(--primary);
            color: white;
            font-weight: 500;
        }

        tr:hover {
            background-color: #f8fafc;
        }

        .actions {
            display: flex;
            gap: 0.5rem;
        }

        .search-form {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .reset-link {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
        }

        .reset-link:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
            
            .search-form {
                grid-template-columns: 1fr;
            }
            
            .actions {
                flex-direction: column;
                gap: 0.25rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1><i class="fas fa-shopping-cart"></i> Gestion des Articles</h1>
            <a href="?" class="btn btn-primary">
                <i class="fas fa-sync-alt"></i> Actualiser
            </a>
        </header>
        
        <?php if (!empty($message)): ?>
            <div class="message <?= strpos($message, 'Erreur') !== false ? 'message-error' : 'message-success' ?>">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>
        
        <div class="card">
            <h2><i class="fas fa-plus-circle"></i> Ajouter un article</h2>
            <form method="POST" class="form-grid">
                <input type="hidden" name="action" value="ajouter_article">
                
                <div class="form-group">
                    <label for="ref">Référence</label>
                    <input type="text" id="ref" name="ref" maxlength="20" required>
                </div>
                
                <div class="form-group">
                    <label for="libelle">Libellé</label>
                    <input type="text" id="libelle" name="libelle" maxlength="20" required>
                </div>
                
                <div class="form-group">
                    <label for="prix">Prix (€)</label>
                    <input type="number" id="prix" name="prix" min="0" step="0.01" required>
                </div>
                
                <div class="form-group">
                    <label for="qt_stock">Quantité en stock</label>
                    <input type="number" id="qt_stock" name="qt_stock" min="0" required>
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Ajouter l'article
                    </button>
                </div>
            </form>
        </div>
        
        <div class="card">
            <h2><i class="fas fa-edit"></i> Modifier un article</h2>
            <form method="POST" class="form-grid">
                <input type="hidden" name="action" value="modifier_article">
                
                <div class="form-group">
                    <label for="ref_mod">Référence de l'article</label>
                    <input type="text" id="ref_mod" name="ref" maxlength="20" required>
                </div>
                
                <div class="form-group">
                    <label for="new_libelle">Nouveau libellé</label>
                    <input type="text" id="new_libelle" name="new_libelle" maxlength="20">
                </div>
                
                <div class="form-group">
                    <label for="new_prix">Nouveau prix (€)</label>
                    <input type="number" id="new_prix" name="new_prix" min="0" step="0.01">
                </div>
                
                <div class="form-group">
                    <label for="new_qt_stock">Nouvelle quantité</label>
                    <input type="number" id="new_qt_stock" name="new_qt_stock" min="0">
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-edit"></i> Modifier l'article
                    </button>
                </div>
            </form>
        </div>
        
        <div class="card">
            <h2><i class="fas fa-trash-alt"></i> Supprimer un article</h2>
            <form method="POST">
                <input type="hidden" name="action" value="supprimer_article">
                
                <div class="form-group">
                    <label for="ref_supp">Référence de l'article</label>
                    <input type="text" id="ref_supp" name="ref" maxlength="20" required>
                </div>
                
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-trash-alt"></i> Supprimer l'article
                </button>
            </form>
        </div>
        
        <div class="card">
            <h2><i class="fas fa-search"></i> Rechercher des articles</h2>
            <form method="GET" class="search-form">
                <div class="form-group">
                    <label for="search_ref">Référence</label>
                    <input type="text" id="search_ref" name="ref" maxlength="20" value="<?= htmlspecialchars($_GET['ref'] ?? '') ?>">
                </div>
                
                <div class="form-group">
                    <label for="search_libelle">Libellé</label>
                    <input type="text" id="search_libelle" name="libelle" maxlength="20" value="<?= htmlspecialchars($_GET['libelle'] ?? '') ?>">
                </div>
                
                <div class="form-group">
                    <label for="prix_min">Prix min (€)</label>
                    <input type="number" id="prix_min" name="prix_min" min="0" value="<?= htmlspecialchars($_GET['prix_min'] ?? '') ?>">
                </div>
                
                <div class="form-group">
                    <label for="prix_max">Prix max (€)</label>
                    <input type="number" id="prix_max" name="prix_max" min="0" value="<?= htmlspecialchars($_GET['prix_max'] ?? '') ?>">
                </div>
                
                <div class="form-group">
                    <label>Fournisseurs</label>
                    <select>
                        <option value="">Tous les fournisseurs</option>
                        <?php foreach ($fournisseurs as $fournisseur): ?>
                            <option value="<?= htmlspecialchars($fournisseur['id']) ?>">
                                <?= htmlspecialchars($fournisseur['nom']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group" style="align-self: flex-end;">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Rechercher
                    </button>
                    <a href="?" class="reset-link">
                        <i class="fas fa-times"></i> Réinitialiser
                    </a>
                </div>
            </form>
            
            <h2><i class="fas fa-list"></i> Liste des articles</h2>
            <div style="overflow-x: auto;">
                <table>
                    <thead>
                        <tr>
                            <th>Référence</th>
                            <th>Libellé</th>
                            <th>Prix (€)</th>
                            <th>Stock</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($articles)): ?>
                            <tr>
                                <td colspan="5" style="text-align: center;">Aucun article trouvé</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($articles as $article): ?>
                                <tr>
                                    <td><?= htmlspecialchars($article['ref']) ?></td>
                                    <td><?= htmlspecialchars($article['libelle']) ?></td>
                                    <td><?= number_format($article['prix'], 2, ',', ' ') ?></td>
                                    <td><?= htmlspecialchars($article['Qt en stock']) ?></td>
                                    <td class="actions">
                                        <form method="POST" style="display: inline;">
                                            <input type="hidden" name="action" value="supprimer_article">
                                            <input type="hidden" name="ref" value="<?= htmlspecialchars($article['ref']) ?>">
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="fas fa-trash-alt"></i> Supprimer
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>