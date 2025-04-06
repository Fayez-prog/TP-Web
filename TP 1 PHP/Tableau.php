<?php
/**
 * Fonction pour afficher proprement un tableau
 * @param array $array Tableau à afficher
 * @param bool $return Si true, retourne le résultat au lieu de l'afficher
 * @return string|null Le résultat formaté si $return est true
 */
function displayArray(array $array, bool $return = false): ?string {
    $output = "<pre>" . htmlspecialchars(print_r($array, true)) . "</pre>";
    
    if ($return) {
        return $output;
    }
    
    echo $output;
    return null;
}

// 1. Tableau indexé
$indexedArray = [10, 15, 14];

// Afficher le tableau indexé
displayArray($indexedArray);

// Accéder à un élément spécifique
echo "Element à l'index 1 : " . $indexedArray[1] . "<br>"; // Affiche 15

// Ajouter un élément
$indexedArray[] = 17;

// Modifier un élément
$indexedArray[2] = 14.5;

// Afficher le tableau modifié
displayArray($indexedArray);

// 2. Tableau associatif
$assocArray = [
    "nom" => "IIT",
    "ville" => "Sfax",
    "tel" => 29459854,
    "email" => "IIT@example.com"  // Correction: remplacé * par example.com
];

displayArray($assocArray);

// Opérations sur le tableau associatif
// Ajouter un élément
$assocArray["adresse"] = "Elons";

// Supprimer un élément
unset($assocArray['tel']);

// Modifier un élément
$assocArray['nom'] = "ISBS";

// Afficher le tableau modifié
displayArray($assocArray);

// 3. Fonctions utiles pour les tableaux
echo "Nombre d'éléments : " . count($assocArray) . "<br>";

// 4. Autres opérations utiles
// Vérifier si une clé existe
if (array_key_exists('ville', $assocArray)) {
    echo "La clé 'ville' existe avec la valeur : " . $assocArray['ville'] . "<br>";
}

// Liste des clés
echo "Clés du tableau : " . implode(', ', array_keys($assocArray)) . "<br>";

// Liste des valeurs
echo "Valeurs du tableau : " . implode(', ', array_values($assocArray)) . "<br>";

// 5. Tableau multidimensionnel (exemple supplémentaire)
$multiDimArray = [
    "etudiants" => [
        ["nom" => "Dupont", "age" => 20],
        ["nom" => "Martin", "age" => 22]
    ],
    "professeurs" => [
        ["nom" => "Smith", "matiere" => "Informatique"],
        ["nom" => "Johnson", "matiere" => "Mathématiques"]
    ]
];

displayArray($multiDimArray);
?>