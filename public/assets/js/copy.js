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
});