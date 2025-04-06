<?php
// Tableau multidimensionnel des matières avec leurs notes
$matieres = [
    "Java" => [14, 15, 8],
    "HTML" => [12.5, 15, 11],
    "JavaScript" => [19.25, 13, 17],
    "CSS" => [12, 17, 15],
    "Angular" => [8, 14, 13],
    "Node.js" => [12, 10, 12]
];

// Fonction pour calculer la moyenne
function calculerMoyenne($notes) {
    return round(array_sum($notes) / count($notes), 2);
}

// Début du tableau HTML avec style CSS intégré
echo '
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notes des matières</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #e9e9e9;
        }
        .moyenne {
            font-weight: bold;
        }
        caption {
            font-size: 1.5em;
            margin-bottom: 10px;
            font-weight: bold;
        }
    </style>
</head>
<body>
<table>
    <caption>Notes des matières et moyennes</caption>
    <thead>
        <tr>
            <th>Matière</th>
            <th>Note 1</th>
            <th>Note 2</th>
            <th>Note 3</th>
            <th>Moyenne</th>
        </tr>
    </thead>
    <tbody>';

// Affichage des données
foreach ($matieres as $matiere => $notes) {
    $moyenne = calculerMoyenne($notes);
    
    echo "<tr>
            <td>$matiere</td>";
    
    foreach ($notes as $note) {
        // Ajout d'une couleur conditionnelle pour les notes
        $style = $note < 10 ? 'style="color: red;"' : '';
        echo "<td $style>$note</td>";
    }
    
    // Ajout d'une couleur conditionnelle pour la moyenne
    $styleMoyenne = $moyenne < 10 ? 'style="color: red;"' : '';
    echo "<td class=\"moyenne\" $styleMoyenne>$moyenne</td>
          </tr>";
}

// Fin du tableau HTML
echo '
    </tbody>
</table>
</body>
</html>';
?>