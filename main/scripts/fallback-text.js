// @license magnet:?xt=urn:btih:0b31508aeb0634b347b8270c7bee4d411b5d4109&dn=agpl-3.0.txt AGPL-3.0
let method;
const resultsContainer = document.getElementById('results');
const noRes = document.getElementById('nores');
let interval = 0;

const urlParams = new URLSearchParams(window.location.search);
const query = urlParams.get('q');
const page = urlParams.get('pg');
const type = urlParams.get('tp');

function countResults() {
    return document.querySelectorAll('.a-result').length;
}

function fetchFallbackResults() {

    if (query && page) {
        const url = `../other/fallback.php?q=${encodeURIComponent(query)}&pg=${encodeURIComponent(page)}&me=${encodeURIComponent(method)}`;

        fetch(url)
        .then(response => response.text())
        .then(html => {
            resultsContainer.innerHTML += html;
        })
        .catch(error => {
            console.error('Error executing fallback:', error);
        })
        .finally(() => {
            setInterval(() => {
                const fallbackingMessage = document.getElementById('fallbacking');
                if (fallbackingMessage) {
                    fallbackingMessage.remove();
                }
            }, 10000);
        });
    }
}

function runFallbackCheck() {
    if ( countResults() < 2 && !noRes && type == 0) {
        if (method !== "duck") {
            method = "duck";
        } else {
            method = "brave";
        }

        fetchFallbackResults();
    }
}

document.addEventListener('DOMContentLoaded', function () {
    if ( countResults() < 2 && type == 0) {
        const fallbackingMessage = document.createElement('p');
        fallbackingMessage.id = 'fallbacking';
        fallbackingMessage.textContent = 'Results failed, testing fallbacks. Please wait';
        resultsContainer.appendChild(fallbackingMessage);
        console.log(type);
        console.log(type);
        console.log(type);
        console.log(type);
        console.log(type);
        console.log(type);
        runFallbackCheck();
        setInterval(() => {
            if (interval < 1) {
                runFallbackCheck();
                interval++
            }
        }, 5000);
    }

});
// @license-end
