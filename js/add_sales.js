const selectedProducts = [];
let selectedClientId;

const trProduct = document.getElementById("product-result");
const tdButton = document.getElementById("button-product");

function AddSelectProducts(index, id, name, stock_quantity, value) {
    let productAlreadyExists = false;

    for (let i = 0; i < selectedProducts.length; i++) {
        if (selectedProducts[i].id === id) {
            let number = selectedProducts[i].stock_quantity;
            number++;

            if (validateStock(stock_quantity, number)) {
                selectedProducts[i].stock_quantity = number;
                let productQuantityCell = document.getElementById("product-quantity-" + id);
                if (productQuantityCell) {
                    productQuantityCell.textContent = number;
                }
            }

            updateProductQuantity(id, number);

            productAlreadyExists = true;
        }
    }

    if (!productAlreadyExists) {
        let newProduct = {
            id: id,
            name: name,
            stock_quantity: 1,
            value: value
        };

        validateStock(stock_quantity, 1);
        selectedProducts.push(newProduct);

        let newRow = trProduct.insertRow();
        newRow.id = "row-" + id;
        newRow.innerHTML = "<td id='product-id'>" + id + "</td>" +
            "<td id='product-name'>" + name + "</td>" +
            "<td id='product-quantity-" + id + "'>" + 1 + "</td>" +
            "<td id='product-value' class='content-form'>" +
            "<input type='text' id='value" + id + "' value='" + value + "' />" +
            "</td>" +
            "<td id='total-item'>" + id + "</td>" +
            "<td style='margin: 6px; padding: 6px;'>" +
            "<div>" +
            "<button onclick='removeProduct(" + id + ")' id='button-delete-" + id + "' class='btn-delete' type='button'>Deletar</button>" +
            "</div>" +
            "</td>";
    }
}

function removeProduct(id) {

    let rowToRemove = document.getElementById("row-" + id);
    if (selectedProducts.length > 0) {

        let productIndex = selectedProducts.findIndex(product => product.id = id);
        if (productIndex !== -1) {

            let product = selectedProducts[productIndex];
            let productQuantityCell = document.getElementById("product-quantity-" + id);

            if (productQuantityCell) {
                let number = product.stock_quantity - 1;

                if (number >= 1) {
                    product.stock_quantity = number;
                    productQuantityCell.textContent = number;
                } else {
                    selectedProducts.splice(productIndex, 1);
                    rowToRemove.remove();
                }
            }
        } else {
            console.error("Produto não encontrado no array.");
        }
    } else {
        console.error("Array de produtos está vazio");
    }
}

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
      
      selectedClientId = row.querySelector("td:first-child").textContent;

      if (salesPageElement) {
        salesPageElement.innerHTML =
          "Codigo do cliente: " + selectedClientId + " Nome do cliente: " + clientName;
      }
    });
  });
});

async function finalizeSale() {

    let selectedPaymentMethod = document.getElementById('id_payment_method').value;
    let idSalesUser = document.getElementById("user_id").value;
    let idSalesClient = selectedClientId;
    
    console.log(selectedClientId);

    let requestData = {
        id_payment_method: selectedPaymentMethod,
        sales_id_client: idSalesClient,
        idSalesUser: idSalesUser,
        products: selectedProducts
    };

    if (selectedProducts.length === 0) {
        showErrorMessage('Erro ao registrar venda, nenhum produto selecionado');
        return;
    } else {
        try {
            const response = await fetch('http://localhost/Klitzke/ajax/add_sales.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(requestData)
            });

            if (!response.ok) {
                throw new Error('Erro na solicitação erro na url do backend: ' + response.statusText);
            }

            const data = await response.json();

            if (data && data.success) {
                showSuccessMessage('Venda finalizada com sucesso!');
                const saleId = data.id;
                window.location.href = 'pages/proof.php?sale_id=' + id;
            } else {
                console.error('Erro ao registrar venda:', data ? data.error : 'Resposta vazia');
            }
        } catch (error) {
            console.error('Erro ao enviar dados para o PHP:', error);
        }
    }
}

function showErrorMessage(message) {
    const errorContainer = document.getElementById('error-container');
    const errorMessageElement = document.getElementById('error-message');
    errorMessageElement.textContent = message;
    errorContainer.style.display = 'flex';
    setTimeout(() => {
        errorMessageElement.textContent = '';
        errorContainer.style.display = 'none';
    }, 3000);
}

function showSuccessMessage(message) {
    const successContainer = document.getElementById('success-container');
    const successMessageElement = document.getElementById('success-message');
    successMessageElement.textContent = message;
    successContainer.style.display = 'flex';
    setTimeout(() => {
        successMessageElement.textContent = '';
        successContainer.style.display = 'none';
    }, 3000);
}

const finishButton = document.getElementById('finish-sales');

if (finishButton) {
    finishButton.onclick = finalizeSale;
}

function updateProductQuantity(id, stock_quantity) {

    for (let i = 0; i < selectedProducts.length; i++) {
        if (selectedProducts[i].id === id) {
            selectedProducts[i].stock_quantity = stock_quantity;
            break;
        }
    }
}

function addProductToArray(id, name, stock_quantity, value) {
    selectedProducts.push({ id, name, stock_quantity, value });
}

function validateStock(stock_quantity, qnt) {

    if (stock_quantity < qnt) {
        window.alert("Você não possui estoque suficiente!");
        return false;
    }
    return true;
}