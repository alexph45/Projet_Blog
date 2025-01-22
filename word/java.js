// Global vars
const $carouselItems = $('.carousel-item');
let currentItemPosition = 0;
let carouselInterval;

// Functions
const goToNextSlide = () => {
    const lastItem = `.item-${currentItemPosition}`;
    currentItemPosition = (currentItemPosition + 1) % $carouselItems.length;
    const currentItem = `.item-${currentItemPosition}`;

    setNodeAttributes(lastItem, currentItem);
};

const setNodeAttributes = (lastItem, currentItem) => {
    $(lastItem).css('display', 'none').attr('aria-hidden', 'true');
    $(currentItem).css('display', 'block').attr('aria-hidden', 'false');
};

// Events
$(document).ready(function () {
    // Initialise le premier slide visible dès le chargement
    $('.item-0').css('display', 'block').attr('aria-hidden', 'false');

    // Cache les autres slides
    $carouselItems.not('.item-0').css('display', 'none').attr('aria-hidden', 'true');

    // Démarre le carrousel
    carouselInterval = setInterval(() => goToNextSlide(), 5000);
});



function togglePopup() {
   let popup = document.querySelector("#popup-overlay");
   popup.classList.toggle("open")
}
