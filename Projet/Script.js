// Déclarer le tableau des produits dans la portée globale
let produits = [{
    "id": 1,
    "reference": "A001",
    "intitule": "Téléphone Portable Samsung Galaxy A10 / Noir ",
    "categorieId": 1,
    "quantite": 10,
    "prix": 420,
    "namephoto": "A10.jpg",

    "photo": "https://firebasestorage.googleapis.com/v0/b/gestcom-4a752.appspot.com/o/Mode-femme.png?alt=media&token=a1b79f4b-c013-4d0f-b319-b25a932862e9"
},
{
    "id": 2,
    "reference": "A002",
    "intitule": "SAMSUNG GALAXY A20 BLEU",
    "categorieId": 1,
    "quantite": 15,
    "prix": 550,
    "namephoto": "A20.jpg",

    "photo": "https://firebasestorage.googleapis.com/v0/b/gestcom-4a752.appspot.com/o/A12.jpg?alt=media&token=06ab0e7d-1499-4446-bc0b-28fe7559f443"

},
{
    "id": 3,
    "reference": "A003",
    "intitule": "Smartphone Xiaomi Redmi Note 11 Gris 4go 128go",
    "categorieId": 1,
    "quantite": 15,
    "prix": 880,
    "namephoto": "Pro11.jpg",

    "photo": "http://res.cloudinary.com/iset-sfax/image/upload/v1658754339/images/samsungGalaxy1.jpg.jpg"

},
{
    "id": 4,
    "reference": "A004",
    "intitule": "REDMI-NOTE10PRO/8G-128G/GB",
    "categorieId": 1,
    "quantite": 30,

    "prix": 1000,
    "namephoto": "Pro10.jpg",

    "photo": "http://res.cloudinary.com/iset-sfax/image/upload/v1658754656/images/infinix2.jpg.jpg"

},
{
    "id": 5,
    "reference": "A005",
    "intitule": "Smartphone Oppo Reno 8T 4G Noir",
    "categorieId": 1,
    "quantite": 25,
    "prix": 1100,
    "namephoto": "reno8T.jpg",

    "photo": "http://res.cloudinary.com/iset-sfax/image/upload/v1658754036/images/delicedelio.jpg.jpg"

},
{
    "id": 6,
    "reference": "A006",
    "intitule": "Téléphone Oppo Reno 8T",
    "categorieId": 1,
    "quantite": 25,
    "prix": 1100,
    "namephoto": "reno8T.jpg",

    "photo": "http://res.cloudinary.com/iset-sfax/image/upload/v1658755235/images/fnarThon.jpg.jpg"

},
{
    "id": 7,
    "reference": "A007",
    "intitule": "SMARTPHONE XIAOMI 12 PRO / 12 GO / 256 GO / BLEU",
    "categorieId": 1,
    "quantite": 5,
    "prix": 3700,
    "namephoto": "pro11.jpg",

    "photo": "https://firebasestorage.googleapis.com/v0/b/gestcom-4a752.appspot.com/o/Aliments-En-Conserve-M.png?alt=media&token=5a1fc48a-0e91-4216-8d6d-4a77c46e1525"
},
{
    "id": 8,
    "reference": "A008",
    "intitule": "Mi Camera 2K Magnetic Mount",
    "categorieId": 5,
    "quantite": 40,
    "prix": 139,
    "namephoto": "2k.jpg",

    "photo": "http://res.cloudinary.com/iset-sfax/image/upload/v1658754135/images/deliceorange.jpg.jpg"

},
{
    "id": 9,
    "reference": "A009",
    "intitule": "Mi Smart Clock",
    "categorieId": 5,
    "quantite": 20,
    "prix": 219,
    "namephoto": "smartClock.jpg",

    "photo": "https://firebasestorage.googleapis.com/v0/b/gestcom-4a752.appspot.com/o/Boissons.png?alt=media&token=082e9cef-62f9-4c3a-a75d-88930545d425"

},
{
    "id": 10,
    "reference": "A010",
    "intitule": "Mi Temperature and Humidity Monitor Pro",
    "categorieId": 5,
    "quantite": 10,
    "prix": 90,
    "namephoto": "tempHum.jpg",

    "photo": "https://firebasestorage.googleapis.com/v0/b/gestcom-4a752.appspot.com/o/Boissons.png?alt=media&token=082e9cef-62f9-4c3a-a75d-88930545d425"

},
{
    "id": 11,
    "reference": "A011",
    "intitule": "Xiaomi Smart Band 7 Pro",
    "categorieId": 5,
    "quantite": 50,
    "prix": 379,
    "namephoto": "smartBand7Pro.jpg",

    "photo": "http://res.cloudinary.com/iset-sfax/image/upload/v1658754339/images/samsungGalaxy1.jpg.jpg"

},
{
    "id": 12,
    "reference": "A012",
    "intitule": "Xiaomi FlipBuds Pro Ecouteur Sans Fil",
    "categorieId": 5,
    "quantite": 30,
    "prix": 720,
    "namephoto": "flipBudsPro.jpg",

    "photo": "http://res.cloudinary.com/iset-sfax/image/upload/v1658754339/images/samsungGalaxy1.jpg.jpg"

},
{
    "id": 13,

    "reference": "A013",
    "intitule": "Xiaomi Smart Band 7 Pro",
    "categorieId": 5,
    "quantite": 10,
    "prix": 379,
    "namephoto": "smartBand7Pro.jpg",

    "photo": "http://res.cloudinary.com/iset-sfax/image/upload/v1658754339/images/samsungGalaxy1.jpg.jpg"

},
{
    "id": 14,
    "reference": "A014",
    "intitule": "Xiaomi Watch S1",
    "categorieId": 5,
    "quantite": 5,
    "prix": 649,
    "namephoto": "WatchS1Active.jpg",

    "photo": "http://res.cloudinary.com/iset-sfax/image/upload/v1658754339/images/samsungGalaxy1.jpg.jpg"
},
{
    "id": 15,
    "reference": "A015",
    "intitule": "Mi Smart Kettle Pro Bouilloire Intelligente",
    "categorieId": 3,
    "quantite": 100,
    "prix": 229,
    "namephoto": "smartKettlepProBlanco.jpg",
    "photo": "http://res.cloudinary.com/iset-sfax/image/upload/v1658754339/images/samsungGalaxy1.jpg.jpg"

},
{
    "id": 16,
    "reference": "A016",
    "intitule": "TV XIAOMI 55'' SMART ANDROID A3 ULTRA HD 4K",
    "categorieId": 4,
    "quantite": 5,
    "prix": 2100,
    "namephoto": "TV.jpg",

    "photo": "http://res.cloudinary.com/iset-sfax/image/upload/v1658754339/images/samsungGalaxy1.jpg.jpg"

},
{
    "id": 17,
    "reference": "A017",
    "intitule": "RÉFRIGÉRATEUR MONTBLANC FGE23 230 LITRES DEFROST -SILVER",
    "categorieId": 3,
    "quantite": 12,

    "prix": 739,
    "namephoto": "RefMonblanc.jpg",

    "photo": "http://res.cloudinary.com/iset-sfax/image/upload/v1658754339/images/samsungGalaxy1.jpg.jpg"

},
{
    "id": 18,
    "reference": "A018",
    "intitule": "Mini bar acer",
    "categorieId": 3,
    "quantite": 14,
    "prix": 920,
    "namephoto": "miniBarAcer.jpg",

    "photo": "http://res.cloudinary.com/iset-sfax/image/upload/v1658754339/images/samsungGalaxy1.jpg.jpg"

},
{
    "id": 19,
    "reference": "A019",
    "intitule": "DFFF",
    "categorieId": 5,
    "quantite": 12,
    "prix": 3433,
    "namephoto": "smartClock.jpg",

    "photo": "http://res.cloudinary.com/iset-sfax/image/upload/v1658754339/images/samsungGalaxy1.jpg.jpg"

},
{
    "id": 20,
    "reference": "A020",
    "intitule": "Parasol de plage PARUV Windstop turquoise jaune vert foncé UPF50+ 2 places",
    "categorieId": 3,
    "quantite": 20,
    "prix": 135,
    "namephoto": "parasol.jpg",

    "photo": "http://res.cloudinary.com/iset-sfax/image/upload/v1658754339/images/samsungGalaxy1.jpg.jpg"

}];

// Initialiser l'application au chargement de la page
window.onload = function () {
    afficher(produits); // Afficher les produits au chargement
};

// Afficher les produits dans le tableau
function afficher(produits) {
    const tbody = document.querySelector("#tbody");
    tbody.innerHTML = ""; // Effacer le contenu existant

    produits.forEach(prod => {
        const row = tbody.insertRow();
        row.innerHTML = `
            <td>${prod.id}</td>
            <td>${prod.reference}</td>
            <td>${prod.intitule.substring(0, 30)}</td>
            <td>${prod.quantite}</td>
            <td>${prod.prix}</td>
            <td><img src="${prod.photo}" alt="${prod.namephoto}" width="80" height="80"></td>
            <td><button onclick="minusCart(${prod.id})">-</button></td>
            <td><button onclick="plusCart(${prod.id})">+</button></td>
            <td><button onclick="removeFromCart(${prod.id})">Supprimer</button></td>
            <td><button onclick="editProduct(${prod.id})">Modifier</button></td>
        `;
    });

    calculer(); // Mettre à jour le total
}

// Ajouter un produit
function addCart() {
    const id = document.querySelector("#id").value;
    const reference = document.querySelector("#reference").value;
    const intitule = document.querySelector("#intitule").value;
    const quantite = parseInt(document.querySelector("#quantite").value);
    const prix = parseFloat(document.querySelector("#prix").value);
    const photo = document.querySelector("#photo").value; // Récupérer l'URL de l'image

    const article = {
        id: id,
        reference: reference,
        intitule: intitule,
        quantite: quantite,
        prix: prix,
        photo: photo, // Utiliser l'URL de l'image
        namephoto: intitule
    };

    // Vérifier si le produit existe déjà
    const productIndex = produits.findIndex(item => item.id === article.id);
    if (productIndex < 0) {
        produits.push(article);
        afficher(produits);
    } else {
        alert("Un produit avec cet ID existe déjà.");
    }
}

// Diminuer la quantité d'un produit
function minusCart(productId) {
    console.log("minusCart appelé avec productId:", productId); // Debug
    const productIndex = produits.findIndex(item => item.id == productId); // Utilisez == au lieu de ===
    if (productIndex >= 0 && produits[productIndex].quantite > 0) {
        produits[productIndex].quantite--;
        afficher(produits); // Réafficher le tableau après la modification
    } else {
        console.error("Produit non trouvé ou quantité invalide.");
    }
}

// Augmenter la quantité d'un produit
function plusCart(productId) {
    console.log("plusCart appelé avec productId:", productId); // Debug
    const productIndex = produits.findIndex(item => item.id == productId); // Utilisez == au lieu de ===
    if (productIndex >= 0) {
        produits[productIndex].quantite++;
        afficher(produits); // Réafficher le tableau après la modification
    } else {
        console.error("Produit non trouvé.");
    }
}

// Supprimer un produit
function removeFromCart(productId) {
    console.log("removeFromCart appelé avec productId:", productId); // Debug
    produits = produits.filter(item => item.id != productId); // Utilisez != au lieu de !==
    afficher(produits); // Réafficher le tableau après la suppression
}

// Calculer le total de la facture
function calculer() {
    const totalElement = document.getElementById("total");
    const total = produits.reduce((sum, p) => sum + p.prix * p.quantite, 0);
    totalElement.textContent = `Total Facture: ${total.toFixed(2)} DT`;
}

// Filtrer les produits par intitulé
function filter() {
    const inputFiltre = document.querySelector("#intitulef");
    const search = inputFiltre.value.toLowerCase();
    const filteredProduits = produits.filter(p => p.intitule.toLowerCase().includes(search));
    afficher(filteredProduits);
}

// Fonction pour modifier un produit
function editProduct(productId) {
    const productIndex = produits.findIndex(item => item.id == productId);
    if (productIndex >= 0) {
        const product = produits[productIndex];

        // Remplir le formulaire avec les informations du produit
        document.querySelector("#id").value = product.id;
        document.querySelector("#reference").value = product.reference;
        document.querySelector("#intitule").value = product.intitule;
        document.querySelector("#quantite").value = product.quantite;
        document.querySelector("#prix").value = product.prix;
        document.querySelector("#photo").value = product.photo;

        // Changer le texte du bouton Ajouter en "Modifier"
        const addButton = document.querySelector("#formProduit button");
        addButton.textContent = "Modifier";
        addButton.onclick = function () { updateProduct(productId); };
    } else {
        console.error("Produit non trouvé.");
    }
}

// Fonction pour mettre à jour un produit
function updateProduct(productId) {
    const productIndex = produits.findIndex(item => item.id == productId);
    if (productIndex >= 0) {
        // Mettre à jour les informations du produit
        produits[productIndex].id = document.querySelector("#id").value;
        produits[productIndex].reference = document.querySelector("#reference").value;
        produits[productIndex].intitule = document.querySelector("#intitule").value;
        produits[productIndex].quantite = parseInt(document.querySelector("#quantite").value);
        produits[productIndex].prix = parseFloat(document.querySelector("#prix").value);
        produits[productIndex].photo = document.querySelector("#photo").value;

        // Réafficher le tableau
        afficher(produits);

        // Réinitialiser le formulaire et le bouton
        document.querySelector("#formProduit").reset();
        const addButton = document.querySelector("#formProduit button");
        addButton.textContent = "Ajouter";
        addButton.onclick = function () { addCart(); };
    } else {
        console.error("Produit non trouvé.");
    }
}

// Fonction pour trier par intitulé
function sortByIntitule(order) {
    if (order === 'asc') {
        produits.sort((a, b) => a.intitule.localeCompare(b.intitule));
    } else if (order === 'desc') {
        produits.sort((a, b) => b.intitule.localeCompare(a.intitule));
    }
    afficher(produits); // Réafficher le tableau après le tri
}

// Fonction pour trier par prix
function sortByPrix(order) {
    if (order === 'asc') {
        produits.sort((a, b) => a.prix - b.prix);
    } else if (order === 'desc') {
        produits.sort((a, b) => b.prix - a.prix);
    }
    afficher(produits); // Réafficher le tableau après le tri
}