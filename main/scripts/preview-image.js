// @license magnet:?xt=urn:btih:0b31508aeb0634b347b8270c7bee4d411b5d4109&dn=agpl-3.0.txt AGPL-3.0
const images = document.querySelectorAll('.image-result');
const overlay = document.getElementById('overlay');
const visitButton = document.getElementById('visitLink');
const body = document.body;

function showOverlay(url, title, weblink) {
    imageUrl = url;
    overlay.classList.add('active');
    body.style.overflow = 'hidden';
  
    const overlayImage = overlay.querySelector('img');
    const overlayTitle = overlay.querySelector('h2');
    const downloadButton = document.getElementById('downloader');
  
    overlayImage.setAttribute('src', imageUrl);
    overlayTitle.textContent = title;

    visitButton.setAttribute('href', weblink);
    downloadButton.setAttribute('href', imageUrl);
}

function hideOverlay() {
    overlay.classList.remove('active');
    body.style.overflow = 'auto';
}

images.forEach((image) => {
    image.addEventListener('click', () => {
        const imageAttribute = image.querySelector('img');
        let imageUrl = imageAttribute.getAttribute('src');
        let imageTitle = imageAttribute.getAttribute('alt');
        let imageWeblink = image.querySelector('a');
        let imageWeblinkUrl = imageWeblink.getAttribute('alt');
        showOverlay(imageUrl, imageTitle, imageWeblinkUrl);
    });
});

overlay.addEventListener('click', (event) => {
    if (event.target === overlay) {
        hideOverlay();
    }
});
// @license-end