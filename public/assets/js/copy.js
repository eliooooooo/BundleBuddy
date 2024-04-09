async function copyToClipboard(element) {
    let text = document.querySelector(element).innerText;
    let copy = document.getElementById('copy');
    try {
        await navigator.clipboard.writeText(text);
        copy.style.color = 'rgba(216,58,36,1)';
        console.log('Text copied to clipboard');
    } catch (err) {
        console.error('Error in copying text: ', err);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    let copy = document.getElementById('copy');

    if (copy) {
        copy.addEventListener('click', function() {
            copyToClipboard('#install');
        });
    }

    let dl = document.querySelector('.button-dl');

    if (dl) {
        dl.addEventListener('click', function() {
            // Récupérer le contenu de la balise
            let content = document.querySelector('.content').innerText;

            // Créer un nouveau Blob avec le contenu
            let blob = new Blob([content], { type: 'text/plain' });

            // Créer un nouveau lien avec le Blob comme source
            let url = URL.createObjectURL(blob);
            let link = document.createElement('a');
            link.href = url;
            link.download = 'package.json';

            document.body.appendChild(link);
            link.click();

            document.body.removeChild(link);
            URL.revokeObjectURL(url);
        });
    }
});