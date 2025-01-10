document.getElementById('contact-form').addEventListener('submit', async function (event) {
    event.preventDefault();

    const email = document.getElementById('email').value;
    const content = document.getElementById('content').value;

    try {
        const response = await fetch('index.html', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ email, content }),
        });

        if (response.status === 201) {
            console.log('Message envoyé avec succès');
        } else {
            console.error('Erreur lors de l\'envoi');
        }
    } catch (error) {
        console.error('Erreur réseau ou autre problème', error);
    }
});

document.addEventListener('keydown', function (event) {
    if (event.key === 'Escape') {
        togglePopup();
    }
});

function togglePopup() {
    const overlay = document.getElementById('popup-overlay');
    overlay.style.display = overlay.style.display === 'none' ? 'block' : 'none';
}

// Carrousel
const slides = document.querySelectorAll('.carousel-slide');
let currentIndex = 0;

function showSlide(index) {
    slides.forEach((slide, i) => {
        slide.classList.toggle('active', i === index);
    });
}

document.getElementById('prev-btn').addEventListener('click', function () {
    currentIndex = (currentIndex === 0) ? slides.length - 1 : currentIndex - 1;
    showSlide(currentIndex);
});

document.getElementById('next-btn').addEventListener('click', function () {
    currentIndex = (currentIndex + 1) % slides.length;
    showSlide(currentIndex);
});

// Initial display
showSlide(currentIndex);
