// choix.js

// Déclaration des tableaux contenant les données des carreaux
const tabLargeur = [0.3, 0.2, 0.25, 0.3]; // Largeurs des carreaux en mètres
const tabHauteur = [0.4, 0.4, 0.3, 0.45]; // Hauteurs des carreaux en mètres
const tabPrix = [4, 3, 2, 5]; // Prix des carreaux en DTA

// Fonction pour actualiser les champs en fonction du motif sélectionné
function actualiser() {
    // Récupérer l'indice du motif sélectionné
    const indice = document.fc.motifs.selectedIndex;

    // Mettre à jour les champs avec les valeurs correspondantes
    document.getElementById("larg").value = tabLargeur[indice];
    document.getElementById("haut").value = tabHauteur[indice];
    document.getElementById("prix").value = tabPrix[indice];
    document.getElementById("surf").value = (tabLargeur[indice] * tabHauteur[indice]).toFixed(2);

    // Recalculer le nombre de carreaux et le prix total
    calculer();
}

// Fonction pour actualiser la surface de la cuisine
function actualiserSurfCui() {
    // Récupérer les valeurs de largeur et hauteur de la cuisine
    const largeur = parseFloat(document.getElementById("largCuisine").value) || 0;
    const hauteur = parseFloat(document.getElementById("hautCuisine").value) || 0;

    // Calculer la surface de la cuisine
    const surface = (largeur * hauteur).toFixed(2);
    document.getElementById("surfCuisine").value = surface;

    // Recalculer le nombre de carreaux et le prix total
    calculer();
}

// Fonction pour calculer le nombre de carreaux et le prix total
function calculer() {
    // Récupérer les valeurs nécessaires
    const surfCarreau = parseFloat(document.getElementById("surf").value) || 0;
    const surfCuisine = parseFloat(document.getElementById("surfCuisine").value) || 0;
    const prixCarreau = parseFloat(document.getElementById("prix").value) || 0;

    // Valider les valeurs
    if (surfCarreau <= 0 || surfCuisine <= 0 || prixCarreau <= 0) {
        alert("Veuillez vérifier les valeurs saisies. Tous les champs doivent être positifs.");
        return;
    }

    // Calculer le nombre de carreaux nécessaires (arrondi à l'entier supérieur)
    const nbCarreau = Math.ceil(surfCuisine / surfCarreau);

    // Calculer le prix total
    const prixTotal = (prixCarreau * nbCarreau).toFixed(2);

    // Mettre à jour les champs du résultat
    document.getElementById("nbCarreaux").value = nbCarreau;
    document.getElementById("prixTotal").value = prixTotal;
}

// Événement pour actualiser les champs au chargement de la page
window.onload = function () {
    actualiser(); // Initialiser les champs avec les valeurs par défaut
};