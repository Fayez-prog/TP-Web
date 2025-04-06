<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Système de Vente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            padding-bottom: 50px;
        }
        .container {
            max-width: 1200px;
        }
        .table-responsive {
            max-height: 400px;
            overflow-y: auto;
            margin-bottom: 20px;
        }
        .table thead th {
            position: sticky;
            top: 0;
            background-color: #f8f9fa;
            z-index: 10;
        }
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                font-size: 12pt;
                background-color: white;
                padding: 0;
            }
            .container {
                padding: 0;
                max-width: 100%;
            }
            .table {
                width: 100%;
                font-size: 11pt;
            }
            .table th, .table td {
                padding: 4px 8px;
            }
            .card, .card-body {
                border: none;
                padding: 0;
            }
        }
        .card {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            margin-bottom: 20px;
        }
        .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid rgba(0, 0, 0, 0.125);
        }
        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
        }
        .btn-warning {
            background-color: #ffc107;
            border-color: #ffc107;
        }
        .btn-info {
            background-color: #17a2b8;
            border-color: #17a2b8;
        }
        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }
        .form-label {
            font-weight: 500;
            margin-bottom: 0.25rem;
        }
        .total-display {
            font-size: 1.25rem;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <?php
    session_start();
    
    // Liste des produits disponibles
    $tab_prod = [
        ["nom" => "Ecran", "prix" => 250, "ref" => "ECR001"],
        ["nom" => "Clavier", "prix" => 50, "ref" => "CLV002"],
        ["nom" => "Souris", "prix" => 25, "ref" => "SRS003"],
        ["nom" => "PC Asus", "prix" => 2500, "ref" => "PCA004"]
    ];
    
    // Initialisation des ventes
    $ventes = $_SESSION["ventes"] ?? [];
    $net = 0;
    
    // Traitement des actions
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST["submit"])) {
            // Ajout d'une nouvelle vente
            $produit = htmlspecialchars($_POST['produit']);
            $qte = (int)$_POST['qte'];
            $prix = (float)$_POST['prix'];
            
            if ($qte > 0 && $prix > 0) {
                $ventes[] = [
                    "nom" => $produit,
                    "prix" => $prix,
                    "qte" => $qte,
                    "total" => $prix * $qte,
                    "date" => date('Y-m-d H:i:s')
                ];
                $_SESSION['ventes'] = $ventes;
            }
        } elseif (isset($_POST["new"])) {
            // Nouvelle vente (vider le panier)
            unset($_SESSION["ventes"]);
            $ventes = [];
        } elseif (isset($_POST["delete"])) {
            // Supprimer un élément du panier
            $index = (int)$_POST["delete"];
            if (isset($ventes[$index])) {
                unset($ventes[$index]);
                $_SESSION['ventes'] = array_values($ventes); // Réindexer le tableau
            }
        }
        
        // Redirection pour éviter la resoumission du formulaire
        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    }
    
    // Calcul du total net
    foreach($ventes as $vente) {
        $net += $vente["total"];
    }
    ?>
    
    <div class="container pt-4 pb-5">
        <h1 class="text-center mb-4">Système de Vente</h1>
        
        <!-- Formulaire d'ajout de vente -->
        <div class="card mb-4 no-print">
            <div class="card-body">
                <form action="" method="post">
                    <h2 class="text-center h4 mb-3">Ajouter une vente</h2>
                    <div class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label" for="produit">Produit</label>
                            <select class="form-select" name="produit" id="produit" required>
                                <option value="">--- Choisir un produit ---</option>
                                <?php foreach($tab_prod as $prod): ?>
                                    <option value="<?= htmlspecialchars($prod["nom"]) ?>" 
                                            data-prix="<?= $prod["prix"] ?>">
                                        <?= htmlspecialchars($prod["nom"]) ?> (<?= $prod["prix"] ?>TND)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label" for="prix">Prix unitaire (TND)</label>
                            <input class="form-control" type="number" name="prix" id="prix" step="0.01" min="0" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label" for="qte">Quantité</label>
                            <input class="form-control" type="number" name="qte" value="1" id="qte" min="1" required>
                        </div>
                        <div class="col-md-4 d-flex align-items-end gap-2">
                            <button type="submit" name="submit" class="btn btn-success flex-grow-1">
                                Ajouter
                            </button>
                            <button type="reset" class="btn btn-outline-secondary">
                                Annuler
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Ticket de vente -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h2 class="h4 mb-0">Ticket de vente</h2>
                <div class="d-flex gap-2 no-print">
                    <button type="button" class="btn btn-info" onclick="window.print()">
                        Imprimer
                    </button>
                    <form action="" method="post">
                        <button type="submit" name="new" class="btn btn-warning">
                            Nouvelle vente
                        </button>
                    </form>
                </div>
            </div>
            
            <div class="card-body">
                <?php if (empty($ventes)): ?>
                    <div class="alert alert-info">Aucune vente enregistrée</div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Produit</th>
                                    <th>PU (TND)</th>
                                    <th>Qté</th>
                                    <th>Total (TND)</th>
                                    <th class="no-print">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($ventes as $index => $vente): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($vente["nom"]) ?></td>
                                        <td><?= number_format($vente["prix"], 2) ?></td>
                                        <td><?= $vente["qte"] ?></td>
                                        <td><?= number_format($vente["total"], 2) ?></td>
                                        <td class="no-print">
                                            <form action="" method="post" class="d-inline">
                                                <input type="hidden" name="delete" value="<?= $index ?>">
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    Supprimer
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr class="table-active">
                                    <th colspan="3">Net à payer</th>
                                    <th colspan="2"><?= number_format($net, 2) ?> TND</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    
                    <div class="mt-3 text-end">
                        <p class="h5">Total: <strong><?= number_format($net, 2) ?> TND</strong></p>
                        <p class="text-muted small no-print">Date: <?= date('d/m/Y H:i') ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Mise à jour automatique du prix lors de la sélection d'un produit
        document.getElementById('produit').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption.value) {
                document.getElementById('prix').value = selectedOption.dataset.prix;
            }
        });
        
        // Focus sur le premier champ au chargement
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('produit').focus();
        });
    </script>
</body>
</html>