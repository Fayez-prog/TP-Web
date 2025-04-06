// Fonction pour valider si le CIN est numérique
function ValideCIN() {
    let cin = document.forms["f"]["cin"].value;
    if (isNaN(cin)) {
        alert("Le numéro CIN doit être numérique !");
        return false;
    }
    return true;
}

// Fonction pour vérifier si les deux CIN correspondent
function VerifCIN() {
    let cin1 = document.forms["f"]["cin"].value;
    let cin2 = document.forms["f"]["cin2"].value;
    if (cin1 !== cin2) {
        alert("Les numéros CIN ne correspondent pas !");
        return false;
    }
    return true;
}

// Fonction pour déterminer si l'étudiant a le droit au logement
function Resultat() {
    let redoublant = document.forms["f"]["redouble"].checked;
    let sexe = document.forms["f"]["sexe"].value;
    let nbLogement = document.forms["f"]["nb"].value;

    if (redoublant) {
        alert("Pas de logement dans tous les cas (redoublant).");
    } else {
        if (sexe === "male") {
            if (nbLogement == 0) {
                alert("Vous avez le droit au logement.");
            } else {
                alert("Vous n'avez pas le droit au logement.");
            }
        } else if (sexe === "femelle") {
            if (nbLogement <= 1) {
                alert("Vous avez le droit au logement.");
            } else {
                alert("Vous n'avez pas le droit au logement.");
            }
        }
    }
}