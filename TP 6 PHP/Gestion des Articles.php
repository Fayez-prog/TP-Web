<?php
/**
 * Exercice 2 - Solution complète en un fichier
 * Gestion d'articles et fournisseurs
 */

class Fournisseur {
    private $id;
    private $nom;
    private $adresse;
    private $telephone;

    public function __construct($id, $nom, $adresse = '', $telephone = '') {
        $this->id = $id;
        $this->nom = $nom;
        $this->adresse = $adresse;
        $this->telephone = $telephone;
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
        return '<option value="' . $this->id . '">' . $this->nom . '</option>';
    }
}

class Article {
    private $id;
    private $libelle;
    private $prix;
    private $fournisseur;
    private $quantite;

    public function __construct($id, $libelle, $prix, Fournisseur $fournisseur, $quantite = 0) {
        $this->id = $id;
        $this->libelle = $libelle;
        $this->prix = $prix;
        $this->fournisseur = $fournisseur;
        $this->quantite = $quantite;
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
        return '<tr>
            <td>' . $this->id . '</td>
            <td>' . $this->libelle . '</td>
            <td>' . number_format($this->prix, 2, ',', ' ') . ' €</td>
            <td>' . $this->fournisseur->__get('nom') . '</td>
            <td>' . $this->quantite . '</td>
        </tr>';
    }
}

// Données de test
$fournisseurs = [
    new Fournisseur(1, 'TechnoImport', '12 Rue des Gadgets', '01 23 45 67 89'),
    new Fournisseur(2, 'ElectroPlus', '45 Avenue des Circuits', '02 34 56 78 90'),
    new Fournisseur(3, 'MegaStock', '78 Boulevard des Composants', '03 45 67 89 01')
];

$articles = [
    new Article(101, 'Clavier mécanique', 89.99, $fournisseurs[0], 15),
    new Article(102, 'Souris gaming', 59.50, $fournisseurs[1], 22),
    new Article(103, 'Écran 27" 4K', 349.90, $fournisseurs[2], 8),
    new Article(104, 'Casque audio', 129.99, $fournisseurs[0], 12),
    new Article(105, 'Webcam HD', 79.95, $fournisseurs[1], 18)
];

// Traitement du formulaire
$nouvelArticle = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = count($articles) + 105 + 1;
    $libelle = $_POST['libelle'] ?? '';
    $prix = floatval($_POST['prix'] ?? 0);
    $quantite = intval($_POST['quantite'] ?? 0);
    $fournisseurId = intval($_POST['fournisseur'] ?? 0);
    
    // Trouver le fournisseur sélectionné
    $selectedFournisseur = null;
    foreach ($fournisseurs as $fournisseur) {
        if ($fournisseur->__get('id') === $fournisseurId) {
            $selectedFournisseur = $fournisseur;
            break;
        }
    }
    
    if ($selectedFournisseur && !empty($libelle)) {
        $nouvelArticle = new Article($id, $libelle, $prix, $selectedFournisseur, $quantite);
        $articles[] = $nouvelArticle;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Articles</title>
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2ecc71;
            --dark-color: #2c3e50;
            --light-color: #ecf0f1;
            --danger-color: #e74c3c;
            --border-color: #bdc3c7;
        }
        
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            color: var(--dark-color);
            line-height: 1.6;
            padding: 20px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        header {
            background-color: var(--primary-color);
            color: white;
            padding: 20px;
            border-radius: 8px 8px 0 0;
            margin-bottom: 20px;
            text-align: center;
        }
        
        h1, h2 {
            margin-bottom: 15px;
        }
        
        .card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 30px;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
        }
        
        input, select {
            width: 100%;
            padding: 10px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            font-size: 16px;
        }
        
        input:focus, select:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.2);
        }
        
        .btn {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: all 0.3s;
        }
        
        .btn:hover {
            background-color: #2980b9;
            transform: translateY(-2px);
        }
        
        .btn-success {
            background-color: var(--secondary-color);
        }
        
        .btn-success:hover {
            background-color: #27ae60;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
        }
        
        th {
            background-color: var(--primary-color);
            color: white;
        }
        
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        
        tr:hover {
            background-color: #e9e9e9;
        }
        
        .success-message {
            background-color: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .form-row {
            display: flex;
            gap: 15px;
        }
        
        .form-row .form-group {
            flex: 1;
        }
        
        @media (max-width: 768px) {
            .form-row {
                flex-direction: column;
                gap: 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Gestion des Articles et Fournisseurs</h1>
            <p>Application de gestion de stock</p>
        </header>
        
        <div class="card">
            <h2>Ajouter un nouvel article</h2>
            
            <?php if ($nouvelArticle): ?>
                <div class="success-message">
                    Article ajouté avec succès: <?php echo $nouvelArticle->__get('libelle'); ?>
                </div>
            <?php endif; ?>
            
            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <div class="form-row">
                    <div class="form-group">
                        <label for="libelle">Libellé de l'article</label>
                        <input type="text" id="libelle" name="libelle" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="prix">Prix unitaire (€)</label>
                        <input type="number" id="prix" name="prix" step="0.01" min="0" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="quantite">Quantité en stock</label>
                        <input type="number" id="quantite" name="quantite" min="0" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="fournisseur">Fournisseur</label>
                        <select id="fournisseur" name="fournisseur" required>
                            <option value="">Sélectionnez un fournisseur</option>
                            <?php foreach ($fournisseurs as $fournisseur): ?>
                                <?php echo $fournisseur; ?>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-success">Ajouter l'article</button>
            </form>
        </div>
        
        <div class="card">
            <h2>Liste des Articles</h2>
            
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Libellé</th>
                        <th>Prix</th>
                        <th>Fournisseur</th>
                        <th>Quantité</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($articles as $article): ?>
                        <?php echo $article; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <div class="card">
            <h2>Liste des Fournisseurs</h2>
            
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Adresse</th>
                        <th>Téléphone</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($fournisseurs as $fournisseur): ?>
                        <tr>
                            <td><?php echo $fournisseur->__get('id'); ?></td>
                            <td><?php echo $fournisseur->__get('nom'); ?></td>
                            <td><?php echo $fournisseur->__get('adresse'); ?></td>
                            <td><?php echo $fournisseur->__get('telephone'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>