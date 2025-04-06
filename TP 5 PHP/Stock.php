<?php
/**
 * Classe Article conforme aux spécifications
 */
class Article
{
    private $id;
    private $designation;
    private $prix;
    private $qteStock;

    public function __construct($id, $designation, $prix, $qteStock)
    {
        $this->id = $id;
        $this->designation = $designation;
        $this->prix = $prix;
        $this->qteStock = $qteStock;
    }

    // Getters
    public function getId() { return $this->id; }
    public function getDesignation() { return $this->designation; }
    public function getPrix() { return $this->prix; }
    public function getQteStock() { return $this->qteStock; }

    // Setters
    public function setId($id) { $this->id = $id; }
    public function setDesignation($designation) { $this->designation = $designation; }
    public function setPrix($prix) { $this->prix = $prix; }
    public function setQteStock($qteStock) { $this->qteStock = $qteStock; }

    // Méthode ToString
    public function __toString()
    {
        return sprintf(
            "ID: %04d | %-20s | Prix: %6.2f€ | Stock: %3d",
            $this->id,
            substr($this->designation, 0, 20),
            $this->prix,
            $this->qteStock
        );
    }

    // Mettre à jour le stock après vente
    public function vendre($quantite)
    {
        if ($quantite <= $this->qteStock) {
            $this->qteStock -= $quantite;
            return true;
        }
        return false;
    }

    // Appliquer une remise
    public function appliquerRemise($pourcentage)
    {
        $this->prix = $this->prix * (1 - $pourcentage/100);
    }
}

/**
 * Classe de test avec présentation élégante
 */
class TestArticle
{
    public static function run()
    {
        echo <<<HTML
        <!DOCTYPE html>
        <html>
        <head>
            <title>Tests Articles</title>
            <style>
                body {
                    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                    line-height: 1.6;
                    max-width: 900px;
                    margin: 0 auto;
                    padding: 20px;
                    background: #f5f5f5;
                    color: #333;
                }
                h1 {
                    color: #2c3e50;
                    text-align: center;
                    border-bottom: 2px solid #3498db;
                    padding-bottom: 10px;
                    margin-bottom: 30px;
                }
                h2 {
                    color: #2980b9;
                    margin-top: 30px;
                    border-left: 4px solid #3498db;
                    padding-left: 10px;
                }
                .article-container {
                    display: grid;
                    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                    gap: 20px;
                    margin: 20px 0;
                }
                .article-card {
                    background: white;
                    border-radius: 8px;
                    padding: 20px;
                    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
                    transition: transform 0.3s ease;
                }
                .article-card:hover {
                    transform: translateY(-5px);
                    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
                }
                .article-header {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    margin-bottom: 15px;
                    padding-bottom: 10px;
                    border-bottom: 1px solid #eee;
                }
                .article-title {
                    font-size: 1.2em;
                    font-weight: bold;
                    color: #3498db;
                    margin: 0;
                }
                .article-id {
                    background: #3498db;
                    color: white;
                    padding: 3px 8px;
                    border-radius: 4px;
                    font-size: 0.8em;
                }
                .article-details {
                    margin: 10px 0;
                }
                .price {
                    font-size: 1.4em;
                    font-weight: bold;
                    color: #27ae60;
                }
                .stock {
                    display: inline-block;
                    padding: 3px 8px;
                    background: #f1f1f1;
                    border-radius: 4px;
                    font-size: 0.9em;
                }
                .low-stock { background: #fff3cd; }
                .out-of-stock { background: #fdecea; color: #e74c3c; }
                .test-section {
                    background: white;
                    border-radius: 8px;
                    padding: 20px;
                    margin-bottom: 30px;
                    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
                }
                .test-case {
                    padding: 15px;
                    margin: 10px 0;
                    background: #f8f9fa;
                    border-radius: 5px;
                    border-left: 4px solid #3498db;
                }
                .success {
                    color: #27ae60;
                    font-weight: bold;
                }
                .error {
                    color: #e74c3c;
                    font-weight: bold;
                }
                .operation {
                    padding: 10px;
                    margin: 8px 0;
                    background: #f0f8ff;
                    border-radius: 4px;
                }
                pre {
                    background: #f5f5f5;
                    padding: 10px;
                    border-radius: 4px;
                    overflow-x: auto;
                    font-family: 'Consolas', monospace;
                }
            </style>
        </head>
        <body>
            <h1>Tests Complets sur les 3 Articles</h1>
        HTML;

        // Création des 3 articles
        $articles = [
            new Article(1001, 'Ordinateur Portable', 899.99, 10),
            new Article(1002, 'Souris Sans Fil', 29.99, 50),
            new Article(1003, 'Clavier Mécanique', 99.50, 0)
        ];

        // Affichage initial
        echo "<div class='test-section'>";
        echo "<h2>1. Création des articles</h2>";
        echo "<div class='article-container'>";
        foreach ($articles as $article) {
            self::afficherArticle($article);
        }
        echo "</div>";
        echo "</div>";

        // Test des ventes
        echo "<div class='test-section'>";
        echo "<h2>2. Tests de vente</h2>";
        
        $quantitesAVendre = [3, 60, 1]; // Quantités à tester
        
        foreach ($articles as $index => $article) {
            echo "<div class='test-case'>";
            echo "<h3>Article " . ($index + 1) . ": " . $article->getDesignation() . "</h3>";
            echo "<div class='article-details'>État initial: <pre>" . $article . "</pre></div>";
            
            foreach ($quantitesAVendre as $quantite) {
                echo "<div class='operation'>";
                echo "Tentative de vente de <strong>$quantite unité(s)</strong>... ";
                
                if ($article->vendre($quantite)) {
                    echo "<span class='success'>✓ Vente réussie</span>";
                    echo "<br>Nouveau stock: " . $article->getQteStock();
                } else {
                    echo "<span class='error'>✗ Échec: stock insuffisant</span>";
                }
                
                echo "</div>";
            }
            
            echo "</div>";
        }
        
        echo "</div>";

        // Test des remises
        echo "<div class='test-section'>";
        echo "<h2>3. Tests de remise</h2>";
        
        $remises = [10, 50, 15]; // Pourcentages de remise à tester
        
        foreach ($articles as $index => $article) {
            echo "<div class='test-case'>";
            echo "<h3>Article " . ($index + 1) . ": " . $article->getDesignation() . "</h3>";
            echo "<div class='article-details'>État initial: <pre>" . $article . "</pre></div>";
            
            foreach ($remises as $remise) {
                $prixAvant = $article->getPrix();
                $article->appliquerRemise($remise);
                
                echo "<div class='operation'>";
                echo "Application d'une remise de <strong>$remise%</strong><br>";
                echo "Prix: $prixAvant € → <strong>" . $article->getPrix() . " €</strong>";
                echo "</div>";
            }
            
            echo "</div>";
        }
        
        echo "</div>";

        // Affichage final
        echo "<div class='test-section'>";
        echo "<h2>4. État final des articles</h2>";
        echo "<div class='article-container'>";
        foreach ($articles as $article) {
            self::afficherArticle($article, true);
        }
        echo "</div>";
        echo "</div>";

        echo "</body></html>";
    }

    private static function afficherArticle(Article $article, bool $showDetails = false)
    {
        $stockClass = '';
        if ($article->getQteStock() === 0) {
            $stockClass = 'out-of-stock';
        } elseif ($article->getQteStock() < 5) {
            $stockClass = 'low-stock';
        }
        
        echo "<div class='article-card'>";
        echo "<div class='article-header'>";
        echo "<h3 class='article-title'>" . $article->getDesignation() . "</h3>";
        echo "<span class='article-id'>#" . $article->getId() . "</span>";
        echo "</div>";
        
        echo "<div class='article-details'>";
        echo "<div class='price'>" . number_format($article->getPrix(), 2, ',', ' ') . " €</div>";
        echo "<div class='stock $stockClass'>Stock: " . $article->getQteStock() . " unités</div>";
        
        if ($showDetails) {
            echo "<div class='article-details'><pre>" . $article . "</pre></div>";
        }
        
        echo "</div>";
        echo "</div>";
    }
}

// Lancement des tests
TestArticle::run();
?>