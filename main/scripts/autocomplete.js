// @license magnet:?xt=urn:btih:0b31508aeb0634b347b8270c7bee4d411b5d4109&dn=agpl-3.0.txt AGPL-3.0
let searchInput = document.getElementById("searchInput");
const autocompleteList = document.getElementById("autocompleteList");
let previousValue = "";

function sendAutocompleteRequest(query) {
    const apiUrl = `../misc/js/autocomplete.php?q=${query}`;

    fetch(apiUrl)
        .then((response) => response.json())
        .then((data) => {
            autocompleteList.innerHTML = "";
            autocompleteList.style.display = "none";

            const suggestions = data[1];
            if (suggestions) {
                autocompleteList.style.display = "block";
                suggestions.forEach((suggestion) => {
                    const listItem = document.createElement("li");
                    listItem.textContent = suggestion;
                    listItem.classList.add("suggestion");
                    autocompleteList.appendChild(listItem);
                    listItem.addEventListener("click", () => {
                        searchInput.value = suggestion;
                        autocompleteList.innerHTML = "";
                        autocompleteList.style.display = "none";
                    });
                });
            }
        })
        .catch((error) => {
            console.error("Error fetching autocomplete suggestions:", error);
        });
}

function checkInputChange() {
    const currentValue = searchInput.value.trim();
  
    if (currentValue !== previousValue) {
        previousValue = currentValue;

        if (currentValue.length >= 3) {
            sendAutocompleteRequest(currentValue);
        } else {
            autocompleteList.innerHTML = "";
            autocompleteList.style.display = "none";
        }
    }
}

setInterval(checkInputChange, 700);
// @license-end