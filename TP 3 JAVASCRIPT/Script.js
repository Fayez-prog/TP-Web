document.addEventListener("DOMContentLoaded", function () {
    // Afficher la date système au format jour/mois/année
    const dateField = document.getElementById("date");
    const today = new Date();
    const formattedDate = `${today.getDate()}/${today.getMonth() + 1}/${today.getFullYear()}`;
    dateField.value = formattedDate;

    // Définir les prix des produits
    const produitSelect = document.getElementById("produit");
    const prixField = document.getElementById("prix");
    const produits = {
        Clavier: 7, // Prix du clavier : 7 DT
        Souris: 10, // Prix de la souris : 10 DT
        "Flash Disk": 15, // Prix du flash disk : 15 DT
    };

    // Afficher le prix du produit sélectionné
    produitSelect.addEventListener("change", function () {
        const selectedProduit = produitSelect.value;
        prixField.value = produits[selectedProduit];
    });

    // Calculer le total en fonction de la quantité
    const quantiteField = document.getElementById("quantite");
    const totalField = document.getElementById("total");

    quantiteField.addEventListener("input", function () {
        const quantite = quantiteField.value;

        // Vérifier si la quantité est un nombre
        if (isNaN(quantite)) {
            alert("La quantité doit être un nombre.");
            quantiteField.value = "";
            totalField.value = "";
        } else {
            const prix = parseFloat(prixField.value);
            const total = prix * quantite;
            totalField.value = total.toFixed(3); // Afficher le total avec 3 décimales
        }
    });

    // Valider l'adresse et activer/désactiver le bouton "Commander"
    const adresseField = document.getElementById("adresse");
    const commanderButton = document.getElementById("commander");

    adresseField.addEventListener("blur", function () {
        const adresse = adresseField.value.trim();

        // Vérifier si l'adresse est vide
        if (adresse === "") {
            alert("L'adresse ne peut pas être vide.");
            commanderButton.disabled = true;
        }
        // Vérifier si l'adresse contient "sfax"
        else if (!adresse.toLowerCase().includes("sfax")) {
            alert("L'adresse doit être à Sfax.");
            commanderButton.disabled = true;
        }
        // Si tout est valide, activer le bouton "Commander"
        else {
            commanderButton.disabled = false;
        }
    });
});