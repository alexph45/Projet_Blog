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