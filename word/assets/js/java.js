function togglePopup() {
    let popup = document.querySelector("#popup-overlay");
    popup.classList.toggle("open")
}

function toggleDropdown(event, menuId) {
    event.preventDefault(); // Empêcher la navigation

    const menu = document.getElementById(menuId);
    const link = event.target.closest('.nav-container'); // Récupère le parent du lien

    // Fermer tous les autres menus avant d'afficher celui-ci
    document.querySelectorAll('.dropdown-menu').forEach(el => {
        if (el !== menu) el.style.display = 'none';
    });

    if (menu.style.display === 'block') {
        menu.style.display = 'none';
    } else {
        menu.style.display = 'block';
    }

    event.stopPropagation(); // Empêcher la propagation du clic
}

// Fermer les menus si on clique ailleurs
document.addEventListener('click', function(event) {
    document.querySelectorAll('.dropdown-menu').forEach(menu => {
        if (!menu.contains(event.target)) {
            menu.style.display = 'none';
        }
    });
});


document.addEventListener('DOMContentLoaded', function() {
    const filterLinks = document.querySelectorAll('.filtre a');
    const projects = document.querySelectorAll('.projet');
    
    filterLinks.forEach(link => {
        link.addEventListener('click', function(event) {
            event.preventDefault();
            const category = link.getAttribute('data-category');
            
            projects.forEach(project => {
                const projectCategories = project.getAttribute('data-categories').split(' / ');
                
                if (category === 'all' || projectCategories.includes(category)) {
                    project.style.display = 'block';
                } else {
                    project.style.display = 'none';
                }
            });
        });
    });
});