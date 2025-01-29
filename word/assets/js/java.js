function togglePopup() {
    let popup = document.querySelector("#popup-overlay");
    popup.classList.toggle("open")
}

function toggleDropdown(event, menuId) {
        const menu = document.getElementById(menuId);

        // Masque les autres menus avant d'afficher celui sélectionné
        document.querySelectorAll('.dropdown-menu').forEach((dropdown) => {
            if (dropdown !== menu) {
                dropdown.style.display = 'none';
            }
        });

        // Affiche ou masque le menu sélectionné
        if (menu.style.display === 'block') {
            menu.style.display = 'none';
        } else {
            menu.style.display = 'block';
        }

        // Empêche le comportement par défaut (comme le défilement)
        event.preventDefault();
}

// Ferme tous les menus déroulants si on clique ailleurs
document.addEventListener('click', function (event) {
    document.querySelectorAll('.dropdown-menu').forEach((menu) => {
        if (!menu.contains(event.target) && !event.target.closest('.nav')) {
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

// Carrousel

// Global vars
const $carouselItems = $('.carousel-item');
let currentItemPosition = 0;
let carouselInterval;

// Functions
const goToNextSlide = () => {
    const lastItem = $carouselItems.eq(currentItemPosition);
    currentItemPosition = (currentItemPosition + 1) % $carouselItems.length;
    const currentItem = $carouselItems.eq(currentItemPosition);

    setNodeAttributes(lastItem, currentItem);
};

const setNodeAttributes = (lastItem, currentItem) => {
    lastItem.css('display', 'none').attr('aria-hidden', 'true');
    currentItem.css('display', 'block').attr('aria-hidden', 'false');
};

// Events
$(document).ready(function () {
    // Initialise le premier slide visible dès le chargement
    $carouselItems.eq(0).css('display', 'block').attr('aria-hidden', 'false');

    // Cache les autres slides
    $carouselItems.not(':first').css('display', 'none').attr('aria-hidden', 'true');

    // Démarre le carrousel
    carouselInterval = setInterval(() => goToNextSlide(), 5000);
});