document.querySelector("form").addEventListener("submit", function(event) {
    const anneeInput = document.getElementById("annee");
    const anneeValue = anneeInput.value;
    const currentYear = new Date().getFullYear();

    // Vérifier si l'année est bien un nombre de 4 chiffres
    if (!/^\d{4}$/.test(anneeValue)) {
        alert("Veuillez entrer une année au format AAAA.");
        event.preventDefault();  // Empêcher la soumission du formulaire
        return;
    }

    // Vérifier si l'année est dans l'intervalle correct
    if (anneeValue < 1950 || anneeValue > currentYear) {
        alert("L'année doit être entre 1950 et " + currentYear + ".");
        event.preventDefault();  // Empêcher la soumission du formulaire
        return;
    }
});

document.addEventListener('DOMContentLoaded', function() {
    const select = document.getElementById('categories-select');
    select.addEventListener('mousedown', function(e) {
        e.preventDefault(); // Empêche le comportement par défaut du clic
        const option = e.target;
        if (option.tagName === 'OPTION') {
            // Inverse l'état de sélection de l'option
            option.selected = !option.selected;
        }
    });
});