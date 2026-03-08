/* script.js */
document.addEventListener("DOMContentLoaded", function () {
    const searchButton = document.querySelector(".search-button");
    searchButton.addEventListener("click", function () {
        alert("Search functionality coming soon!");
    });

    const bookNowButton = document.querySelector(".book-now");
    bookNowButton.addEventListener("click", function () {
        alert("Redirecting to booking page...");
    });

    const serviceCards = document.querySelectorAll(".service-card");
    serviceCards.forEach(card => {
        card.addEventListener("click", function () {
            alert("Navigating to " + card.querySelector("h3").innerText + " services...");
        });
    });
});