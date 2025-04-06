<?php
/**********************************************
 *                STYLE CSS                   *
 **********************************************/
echo <<<HTML
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Système Bancaire</title>
    <style>
        :root {
            --primary: #3498db;
            --success: #2ecc71;
            --danger: #e74c3c;
            --warning: #f39c12;
            --light: #ecf0f1;
            --dark: #2c3e50;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background-color: #f8f9fa;
            color: #343a40;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        h1, h2, h3 {
            color: var(--dark);
        }
        h1 {
            text-align: center;
            border-bottom: 3px solid var(--primary);
            padding-bottom: 10px;
            margin-bottom: 30px;
        }
        h2 {
            border-left: 5px solid var(--primary);
            padding-left: 15px;
            background-color: var(--light);
            margin-top: 40px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            box-shadow: 0 2px 3px rgba(0,0,0,0.1);
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: var(--primary);
            color: white;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #e9f7fe;
        }
        .success {
            color: var(--success);
            font-weight: bold;
        }
        .error {
            color: var(--danger);
            font-weight: bold;
        }
        .warning {
            color: var(--warning);
        }
        .info {
            color: var(--primary);
        }
        .transaction {
            margin: 10px 0;
            padding: 15px;
            border-left: 4px solid var(--primary);
            background-color: var(--light);
            border-radius: 4px;
            display: flex;
            align-items: center;
        }
        .account-card {
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            margin: 15px 0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .section {
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 1px dashed #eee;
        }
        .emoji {
            font-size: 1.4em;
            margin-right: 10px;
        }
        .balance {
            font-size: 1.2em;
            font-weight: bold;
            margin: 10px 0;
        }
        .btn {
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            margin-right: 10px;
        }
        .btn-primary {
            background-color: var(--primary);
            color: white;
        }
        .flex-row {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }
        .account-info {
            flex: 1;
            min-width: 300px;
        }
    </style>
</head>
<body>
<div class="container">
HTML;

/**********************************************
 *         CLASSE COMPTE BANCAIRE             *
 **********************************************/
class CompteBancaire {
    private string $titulaire;
    private float $solde;
    private string $numeroCompte;
    private array $historiqueTransactions;
    private const PLAFOND_RETRAIT = 1000;
    private const FRAIS_TRANSFERT = 5; // Frais en pourcentage

    public function __construct(string $titulaire, float $solde = 0) {
        $this->titulaire = $this->validerTitulaire($titulaire);
        $this->solde = $this->validerSoldeInitial($solde);
        $this->numeroCompte = $this->genererNumeroCompte();
        $this->historiqueTransactions = [];
    }

    // Validation des données
    private function validerTitulaire(string $titulaire): string {
        if (empty(trim($titulaire))) {
            throw new InvalidArgumentException("Le nom du titulaire ne peut pas être vide.");
        }
        return $titulaire;
    }

    private function validerSoldeInitial(float $solde): float {
        if ($solde < 0) {
            throw new InvalidArgumentException("Le solde initial ne peut pas être négatif.");
        }
        return $solde;
    }

    private function validerMontant(float $montant): void {
        if ($montant <= 0) {
            throw new InvalidArgumentException("Le montant doit être positif.");
        }
    }

    private function genererNumeroCompte(): string {
        return 'FR' . str_pad((string)mt_rand(0, 999999999), 10, '0', STR_PAD_LEFT);
    }

    private function ajouterHistorique(string $type, float $montant, ?string $details = null): void {
        $this->historiqueTransactions[] = [
            'date' => date('Y-m-d H:i:s'),
            'type' => $type,
            'montant' => $montant,
            'solde' => $this->solde,
            'details' => $details
        ];
    }

    // Opérations bancaires
    public function deposer(float $montant): void {
        $this->validerMontant($montant);
        $this->solde += $montant;
        $this->ajouterHistorique('Dépôt', $montant);
        echo "<div class='transaction'><span class='emoji'>💰</span> Dépôt de <span class='success'>" . number_format($montant, 2) . " €</span> effectué. Nouveau solde : <span class='info'>" . number_format($this->solde, 2) . " €</span></div>";
    }

    public function retirer(float $montant): void {
        $this->validerMontant($montant);
        
        if ($montant > self::PLAFOND_RETRAIT) {
            throw new RuntimeException("Le retrait dépasse le plafond autorisé de " . self::PLAFOND_RETRAIT . " €");
        }
        
        if ($montant > $this->solde) {
            throw new RuntimeException("Fonds insuffisants pour ce retrait.");
        }
        
        $this->solde -= $montant;
        $this->ajouterHistorique('Retrait', $montant);
        echo "<div class='transaction'><span class='emoji'>💸</span> Retrait de <span class='warning'>" . number_format($montant, 2) . " €</span> effectué. Nouveau solde : <span class='info'>" . number_format($this->solde, 2) . " €</span></div>";
    }

    public function virement(float $montant, CompteBancaire $compteDestinataire): void {
        $this->validerMontant($montant);
        
        // Calcul des frais de transfert
        $frais = $montant * (self::FRAIS_TRANSFERT / 100);
        $montantTotal = $montant + $frais;
        
        if ($montantTotal > $this->solde) {
            throw new RuntimeException("Fonds insuffisants pour ce virement (inclut " . self::FRAIS_TRANSFERT . "% de frais).");
        }
        
        $this->solde -= $montantTotal;
        $compteDestinataire->deposer($montant);
        
        $this->ajouterHistorique('Virement envoyé', $montant, "À: " . $compteDestinataire->getTitulaire());
        $this->ajouterHistorique('Frais de virement', $frais);
        $compteDestinataire->ajouterHistorique('Virement reçu', $montant, "De: " . $this->titulaire);
        
        echo "<div class='transaction'><span class='emoji'>🔄</span> Virement de <span class='info'>" . number_format($montant, 2) . " €</span> vers " . $compteDestinataire->getTitulaire() . " effectué. Frais : <span class='warning'>" . number_format($frais, 2) . " €</span>. Nouveau solde : <span class='info'>" . number_format($this->solde, 2) . " €</span></div>";
    }

    public function afficherSolde(): void {
        echo "<div class='balance'>Solde actuel de " . $this->titulaire . " : <span class='info'>" . number_format($this->solde, 2) . " €</span></div>";
    }

    public function afficherHistorique(): void {
        echo "<h3><span class='emoji'>📊</span> Historique des transactions pour " . $this->titulaire . "</h3>";
        echo "<table>";
        echo "<tr><th>Date</th><th>Type</th><th>Montant</th><th>Détails</th><th>Solde</th></tr>";
        
        foreach ($this->historiqueTransactions as $transaction) {
            $colorClass = '';
            if (strpos($transaction['type'], 'Dépôt') !== false || strpos($transaction['type'], 'reçu') !== false) {
                $colorClass = 'success';
            } elseif (strpos($transaction['type'], 'Retrait') !== false || strpos($transaction['type'], 'Frais') !== false) {
                $colorClass = 'warning';
            }
            
            echo "<tr>";
            echo "<td>" . $transaction['date'] . "</td>";
            echo "<td>" . $transaction['type'] . "</td>";
            echo "<td class='" . $colorClass . "'>" . number_format($transaction['montant'], 2) . " €</td>";
            echo "<td>" . ($transaction['details'] ?? '') . "</td>";
            echo "<td>" . number_format($transaction['solde'], 2) . " €</td>";
            echo "</tr>";
        }
        
        echo "</table>";
    }

    // Getters
    public function getTitulaire(): string {
        return $this->titulaire;
    }

    public function getSolde(): float {
        return $this->solde;
    }

    public function getNumeroCompte(): string {
        return $this->numeroCompte;
    }

    public function __toString(): string {
        return "<div class='account-card'>
            <h3><span class='emoji'>🏦</span> Compte Bancaire</h3>
            <p><strong>Numéro :</strong> " . $this->numeroCompte . "</p>
            <p><strong>Titulaire :</strong> " . $this->titulaire . "</p>
            <p><strong>Solde :</strong> <span class='info'>" . number_format($this->solde, 2) . " €</span></p>
        </div>";
    }
}

/**********************************************
 *              TEST DU SYSTEME               *
 **********************************************/
echo "<h1><span class='emoji'>🏦</span> Système Bancaire Amélioré</h1>";

try {
    // Section création des comptes
    echo "<section class='section'>";
    echo "<h2><span class='emoji'>👤</span> Création des comptes</h2>";
    echo "<div class='flex-row'>";
    
    $compte1 = new CompteBancaire("Jean Dupont", 1500);
    $compte2 = new CompteBancaire("Marie Curie", 2000);
    $compte3 = new CompteBancaire("Pierre Martin", 500);
    
    echo $compte1;
    echo $compte2;
    echo $compte3;
    
    echo "</div>";
    echo "</section>";
    
    // Section opérations bancaires
    echo "<section class='section'>";
    echo "<h2><span class='emoji'>💳</span> Opérations bancaires</h2>";
    
    $compte1->deposer(500);
    $compte1->retirer(300);
    $compte2->virement(200, $compte3);
    $compte3->deposer(100);
    $compte2->deposer(800);
    
    // Tentative de retrait au-delà du plafond
    echo "<h3><span class='emoji'>⚠️</span> Test des limites</h3>";
    try {
        $compte1->retirer(1200);
    } catch (RuntimeException $e) {
        echo "<div class='error'>" . $e->getMessage() . "</div>";
    }
    
    // Tentative de virement avec fonds insuffisants
    try {
        $compte3->virement(400, $compte1);
    } catch (RuntimeException $e) {
        echo "<div class='error'>" . $e->getMessage() . "</div>";
    }
    
    echo "</section>";
    
    // Section soldes finaux
    echo "<section class='section'>";
    echo "<h2><span class='emoji'>💰</span> Soldes finaux</h2>";
    $compte1->afficherSolde();
    $compte2->afficherSolde();
    $compte3->afficherSolde();
    echo "</section>";
    
    // Section historique des transactions
    echo "<section class='section'>";
    echo "<h2><span class='emoji'>📋</span> Historique des transactions</h2>";
    $compte1->afficherHistorique();
    $compte2->afficherHistorique();
    $compte3->afficherHistorique();
    echo "</section>";
    
} catch (Exception $e) {
    echo "<div class='error'>Une erreur critique est survenue: " . $e->getMessage() . "</div>";
}

echo "</div>"; // Fermeture du container
echo "</body></html>";
?>