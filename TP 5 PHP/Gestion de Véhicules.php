<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Concessionnaire Automobile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1, h2 {
            color: #333;
            text-align: center;
        }
        .vehicles {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }
        .vehicle-card {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
            width: 300px;
            background-color: #f9f9f9;
        }
        .vehicle-card h3 {
            margin-top: 0;
            color: #2c3e50;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }
        .vehicle-info {
            margin-bottom: 10px;
        }
        .label {
            font-weight: bold;
            color: #34495e;
        }
        .voiture {
            border-left: 4px solid #3498db;
        }
        .moto {
            border-left: 4px solid #e74c3c;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Concessionnaire Automobile</h1>
        
        <?php
        // Classe mère Vehicule
        class Vehicule {
            protected $marque;
            protected $modele;
            protected $annee;
            protected $prix;
            
            public function __construct($marque, $modele, $annee, $prix) {
                $this->marque = $marque;
                $this->modele = $modele;
                $this->annee = $annee;
                $this->prix = $prix;
            }
            
            // Getters
            public function getMarque() { return $this->marque; }
            public function getModele() { return $this->modele; }
            public function getAnnee() { return $this->annee; }
            public function getPrix() { return $this->prix; }
            
            // Setters
            public function setMarque($marque) { $this->marque = $marque; }
            public function setModele($modele) { $this->modele = $modele; }
            public function setAnnee($annee) { $this->annee = $annee; }
            public function setPrix($prix) { $this->prix = $prix; }
            
            public function __toString() {
                return "Marque: " . $this->marque . 
                       ", Modèle: " . $this->modele . 
                       ", Année: " . $this->annee . 
                       ", Prix: " . number_format($this->prix, 2, ',', ' ') . " €";
            }
        }
        
        // Classe fille Voiture
        class Voiture extends Vehicule {
            private $nombrePortes;
            
            public function __construct($marque, $modele, $annee, $prix, $nombrePortes) {
                parent::__construct($marque, $modele, $annee, $prix);
                $this->nombrePortes = $nombrePortes;
            }
            
            // Getter et Setter
            public function getNombrePortes() { return $this->nombrePortes; }
            public function setNombrePortes($nombrePortes) { $this->nombrePortes = $nombrePortes; }
            
            public function __toString() {
                return parent::__toString() . ", Nombre de portes: " . $this->nombrePortes;
            }
        }
        
        // Classe fille Moto
        class Moto extends Vehicule {
            private $cylindree;
            
            public function __construct($marque, $modele, $annee, $prix, $cylindree) {
                parent::__construct($marque, $modele, $annee, $prix);
                $this->cylindree = $cylindree;
            }
            
            // Getter et Setter
            public function getCylindree() { return $this->cylindree; }
            public function setCylindree($cylindree) { $this->cylindree = $cylindree; }
            
            public function __toString() {
                return parent::__toString() . ", Cylindrée: " . $this->cylindree . " cm³";
            }
        }
        
        // Création des véhicules
        $vehicules = [
            new Voiture("Peugeot", "308", 2022, 25000, 5),
            new Moto("Yamaha", "MT-07", 2023, 7500, 689),
            new Voiture("Renault", "Clio", 2021, 18000, 3),
            new Moto("Honda", "CBR 650R", 2023, 8500, 649),
            new Voiture("Tesla", "Model 3", 2023, 45000, 5),
            new Moto("Kawasaki", "Ninja 400", 2023, 5500, 399)
        ];
        ?>
        
        <h2>Notre Sélection de Véhicules</h2>
        
        <div class="vehicles">
            <?php foreach ($vehicules as $vehicule): ?>
                <div class="vehicle-card <?php echo ($vehicule instanceof Voiture) ? 'voiture' : 'moto'; ?>">
                    <h3><?php echo $vehicule->getMarque() . ' ' . $vehicule->getModele(); ?></h3>
                    <div class="vehicle-info">
                        <span class="label">Année:</span> <?php echo $vehicule->getAnnee(); ?>
                    </div>
                    <div class="vehicle-info">
                        <span class="label">Prix:</span> <?php echo number_format($vehicule->getPrix(), 2, ',', ' '); ?> €
                    </div>
                    <?php if ($vehicule instanceof Voiture): ?>
                        <div class="vehicle-info">
                            <span class="label">Nombre de portes:</span> <?php echo $vehicule->getNombrePortes(); ?>
                        </div>
                    <?php elseif ($vehicule instanceof Moto): ?>
                        <div class="vehicle-info">
                            <span class="label">Cylindrée:</span> <?php echo $vehicule->getCylindree(); ?> cm³
                        </div>
                    <?php endif; ?>
                    <div class="vehicle-info">
                        <span class="label">Détails complets:</span><br>
                        <?php echo $vehicule; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>