// document.addEventListener("DOMContentLoaded", function () {
const ButtonSelectedClient = document.getElementById("selected-client");
const modalContainer = document.getElementById("client-search-sales");
const inputButton = document.getElementById("input-click");
const CloseSearchClient = document.getElementById("close-search-client");
const overlay = document.getElementById("overlay");

CloseSearchClient.addEventListener("click", async (e) => {
  if ((modalContainer.style.display = "block")) {
    modalContainer.style.display = "none";
    overlay.style.display = "none";
    modalContainer.style.display = "block";
    modalContainer.style.transition = "transform 0.9s";
  }
});

ButtonSelectedClient.addEventListener("click", async (e) => {
  if ((modalContainer.style.display = "none")) {
    modalContainer.style.display = "block";
    modalContainer.style.transition = "transform 0.9s";
  }
});

ButtonSelectedClient.addEventListener("click", async (e) => {
  if ((overlay.style.display = "none")) {
    overlay.style.display = "flex";
  }
});