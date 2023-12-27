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

document
  .getElementById("sales-search-form")
  .addEventListener("submit", function (event) {
    event.preventDefault();

    let searchInput = document.getElementById("clientSelectedSales").value;
    let tableRows = document.querySelectorAll(".tbody-selected tr");

    tableRows.forEach(function (row) {
      let clientName = row
        .querySelector("td:nth-child(2)")
        .textContent.toLowerCase();
      if (clientName.includes(searchInput.toLowerCase())) {
        row.style.display = "";
      } else {
        row.style.display = "none";
      }
    });
  });

document.addEventListener("DOMContentLoaded", function () {
  let tableRows = document.querySelectorAll(".tbody-selected");
  tableRows.forEach(function (row) {
    row.addEventListener("dblclick", function () {
      let clientName = row.querySelector("td:nth-child(2)").textContent;
      let salesPageElement = document.getElementById("sales-page");
      let clientId = row.querySelector("td:first-child").textContent;
      if (salesPageElement) {
        salesPageElement.innerHTML =
          "Codigo do cliente: " + clientId + " Nome do cliente: " + clientName;
      }
    });
  });
});
