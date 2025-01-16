document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('projectForm');

    form.addEventListener('submit', function(event) {
        const anneeInput = document.getElementById('annee').value;
        const currentYear = new Date().getFullYear();

        // Vérifier que l'année est un nombre de 4 chiffres entre 1900 et l'année actuelle
        if (!/^\d{4}$/.test(anneeInput) || anneeInput < 1900 || anneeInput > currentYear) {
            alert("Veuillez entrer une année valide (format : AAAA, entre 1900 et l'année actuelle).");
            event.preventDefault();
        }
    });
});
