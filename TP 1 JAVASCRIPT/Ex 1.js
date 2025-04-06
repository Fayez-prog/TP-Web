const somme = () => {
    var nbr1, nbr2, sum;
    nbr1 = Number(document.getElementById("nbr1").value);
    nbr2 = Number(document.getElementById("nbr2").value);
    sum = nbr1 + nbr2;
    document.getElementById("sum").value = sum;
}