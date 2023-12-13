// @license magnet:?xt=urn:btih:0b31508aeb0634b347b8270c7bee4d411b5d4109&dn=agpl-3.0.txt AGPL-3.0
function openTab(tabId) {
    const tabContents = document.getElementsByClassName("tab-content");

    for (let i = 0; i < tabContents.length; i++) {
        tabContents[i].style.display = "none";
    }
    
    document.getElementById(tabId).style.display = "block";
}

document.addEventListener("DOMContentLoaded", function () {
    openTab('general-tab');
});
// @license-end