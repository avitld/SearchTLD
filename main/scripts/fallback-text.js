// @license magnet:?xt=urn:btih:0b31508aeb0634b347b8270c7bee4d411b5d4109&dn=agpl-3.0.txt AGPL-3.0
let method;
const resultsContainer = document.getElementById('results');
const noRes = document.getElementById('noResults');

const queryInput = document.getElementById('query-info');
const pageInput = document.getElementById('page-info');
const typeInput = document.getElementById('type-info');

const query = queryInput.value;
const page = pageInput.value;
const type = typeInput.value;

let searchType;

function countResults() {
    return document.querySelectorAll('.text-result').length;
}

function fetchFallbackResults() {
    if (query && page) {
        const url = `../misc/js/fallback.php?q=${encodeURIComponent(query)}&pg=${encodeURIComponent(page)}&me=${encodeURIComponent(method)}&type=${encodeURIComponent(searchType)}`;

        fetch(url)
            .then(response => response.text())
            .then(html => {
                resultsContainer.innerHTML += html;
            })
            .catch(error => {
                console.error('Error executing fallback:', error);
            })
            .finally(() => {
                setTimeout(() => {
                    const fallbackingMessage = document.getElementById('fallbacking');
                    if (fallbackingMessage && countResults() > 2) {
                        fallbackingMessage.remove();
                    }
                }, 500);
            });
    }
}

function runFallbackCheck() {
    if (countResults() < 2 && !noRes && type == 0) {
        method = (method === "google" || method === "brave" || method === "bing") ? method : "google";
        method = (method === "google") ? "brave" : "bing";
        searchType = "text";
        fetchFallbackResults();
    } else if (countResults() < 2 && !noRes && type == 3) {
        method = "startpage";
        searchType = "news";
        fetchFallbackResults();
    }
}

document.addEventListener('DOMContentLoaded', function () {
    if (countResults() < 2 && !noRes && type !== 2 && type !== 1 && type !== 4) {
        const fallbackingMessage = document.createElement('img');
        fallbackingMessage.id = 'fallbacking';
        fallbackingMessage.src = 'static/img/loading.svg';
        resultsContainer.appendChild(fallbackingMessage);
    }
    runFallbackCheck();
    setTimeout(runFallbackCheck, 5000);
});
// @license-end