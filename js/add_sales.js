const selectedProducts = [];
let selectedClientId;

const trProduct = document.getElementById("product-result");
const tdButton = document.getElementById("button-product");

let Portion;
let OverlayPortion;

document.addEventListener('DOMContentLoaded', function() {
    Portion = document.querySelector('.portion-sales');
    OverlayPortion = document.querySelector('.overlay-portion');
});


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
            value: parseFloat(value.replace(',', '.'))
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
            "<td style='margin: 6px; padding: 6px;'>" +
            "<div>" +
            "<div>" +
            "<button onclick='removeProduct(" + id + ")' id='button-delete-" + id + "' class='btn-delete' type='button'>Deletar</button>" +
            "<button onclick='editProductValue(" + id + ")' class='btn-edit' style='margin-left: 5px;' type='button'>Editar Valor</button>" +
            "</div>" +
            "</div>" +
            "</td>";
    }
    calculateTotal();
}

function editProductValue(id) {
    let valueInput = document.getElementById("value" + id);

    if (!valueInput) {
        console.error('Elemento valueInput não encontrado.');
        return;
    }

    let promptResult = prompt("Digite o novo valor do produto:", valueInput.value);

    if (promptResult !== null && !isNaN(promptResult.trim())) {
        let editedValue = parseFloat(promptResult.replace(',', '.'));

        if (!isNaN(editedValue)) {
            valueInput.value = editedValue;


            let productIndex = selectedProducts.findIndex(product => product.id === String(id));

            if (productIndex !== -1) {
                selectedProducts[productIndex].value = editedValue;

            } else {
                console.error('Produto não encontrado no array selectedProducts.');
            }
        } else {
            console.error('O valor do produto não é um número válido.');
        }
    }
    calculateTotal();
}

document.addEventListener('DOMContentLoaded', function() {

    function openCreditModal() {
        if (Portion && OverlayPortion) {
            Portion.style.display = 'block';
            OverlayPortion.style.display = 'block';
        }
    }

    function closeCreditModal() {
        if (Portion && OverlayPortion) {
            Portion.style.display = 'none';
            OverlayPortion.style.display = 'none';
        }
    }

    function checkPaymentMethod() {
        var selectedPaymentMethod = document.getElementById('id_payment_method').value;
        if (selectedPaymentMethod === '3') {
            openCreditModal();
        } else {
            closeCreditModal();
        }
    }

    document.getElementById('id_payment_method').addEventListener('change', checkPaymentMethod);

});

async function finalizeSale() {
    try {

        let totalAmountElement = document.getElementById('totalAmount');
        let totalValue = 0;
        if (totalAmountElement) {
            totalValue = parseFloat(totalAmountElement.textContent.replace('R$ ', '')) || 0;
        }


        let selectedPaymentMethod = document.getElementById('id_payment_method').value;
        console.log(selectedPaymentMethod);
        if (selectedPaymentMethod === '3') {
            openCreditModal();
        }
        let idSalesClient = selectedClientId;

        let requestData = {
            idPaymentMethod: selectedPaymentMethod,
            salesIdClient: idSalesClient,
            totalValue: totalValue,
            products: selectedProducts
        };

        if (selectedProducts.length === 0) {
            showErrorMessage('Erro ao registrar venda, nenhum produto selecionado');
            return;
        } else {
            const response = await fetch('http://localhost/Klitzke/ajax/add_sales.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(requestData),
            });

            const responseBody = await response.text();

            const responseData = JSON.parse(responseBody);

            if (responseData && responseData.success) {
                showSuccessMessage('Venda finalizada com sucesso!');
                // const saleId = responseData.id;
                // window.location.href = 'pages/proof.php?sale_id=' + saleId;
            } else {
                console.error('Erro ao registrar venda:', responseData ? responseData.error : 'Resposta vazia');
            }
        }
    } catch (error) {
        console.error('Erro ao enviar dados para o PHP:', error);
    }
}

function updateTotalAmount(total) {

    let totalAmountElement = document.getElementById('totalAmount');

    if (totalAmountElement) {
        totalAmountElement.textContent = 'R$ ' + total.toFixed(2);
    }
}

function calculateTotal() {

    let total = 0;

    selectedProducts.forEach(product => {
        let quantityElement = document.getElementById('product-quantity-' + product.id);
        let valueElement = document.getElementById('value' + product.id);

        if (quantityElement && valueElement) {
            let quantityElementTotal = parseInt(quantityElement.textContent) || 0;
            let value = parseFloat(valueElement.value) || 0;

            total += quantityElementTotal * value;
        } else {
            console.error('Elementos não encontrados para o produto ID:', product.id);
        }
    });

    let totalAmountElement = document.getElementById('totalAmount');
    if (totalAmountElement) {
        totalAmountElement.textContent = 'R$ ' + total.toFixed(2);
    }

    updateTotalAmount(total);

    return total.toFixed(2);
}

document.addEventListener('DOMContentLoaded', function() {
    const saveButton = document.querySelector('.button-portion');
    const descPortionTbody = document.getElementById('desc-portion');
    const totalPortionElement = document.getElementById('total-portion-sales');
    const totalAmountElement = document.getElementById('totalAmount');
    const paymentMethodInput = document.getElementById('id_payment_method');

    function calculateAndDisplayPortions() {
        const portionTotalInput = document.getElementById('portion-total');
        const portionTotal = parseInt(portionTotalInput.value) || 1;

        if (portionTotal <= 0) {
            alert('Por favor, insira um número válido de parcelas.');
            return;
        }

        const totalAmount = parseFloat(totalAmountElement.textContent.replace('R$ ', '')) || 0;
        const portionValue = totalAmount / portionTotal;

        descPortionTbody.innerHTML = '';

        for (let i = 1; i <= portionTotal; i++) {
            const newRow = descPortionTbody.insertRow();
            const cellNumber = newRow.insertCell(0);
            const cellPortion = newRow.insertCell(1);
            const cellValue = newRow.insertCell(2);

            cellNumber.textContent = i;
            cellPortion.textContent = i;
            cellValue.textContent = 'R$ ' + portionValue.toFixed(2);
        }

        totalPortionElement.textContent = 'R$ ' + portionValue.toFixed(2);
    }

    if (saveButton) {
        saveButton.addEventListener('click', function() {
            calculateAndDisplayPortions();
            const portionTotal = parseInt(portionTotalInput.value) || 1;
            const paymentMethod = paymentMethodInput ? paymentMethodInput.value : '';
            savePortionsToDatabase(portionTotal, paymentMethod);
        });
    }

    calculateAndDisplayPortions();
});

async function savePortionsToDatabase(portionValue, paymentMethod) {
    try {
        let totalAmount = parseFloat(totalAmountElement.textContent.replace('R$ ', '')) || 0;
        let idSalesClient = selectedClientId;

        let requestDataPortion = {
            paymentMethod: paymentMethod,
            salesIdClient: idSalesClient,
            totalValue: totalAmount,
            portionValue: portionValue,
            products: selectedProducts
        };

        const response = await fetch('http://localhost/Klitzke/ajax/save_portion_sales.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(requestDataPortion),
        });

        const responseData = await response.json();

        if (responseData && responseData.success) {
            console.log('Parcelas salvas com sucesso!');
        } else {
            console.error('Erro ao salvar parcelas:', responseData ? responseData.error : 'Resposta vazia');
        }
    } catch (error) {
        console.error('Erro ao enviar dados para o PHP:', error);
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
    calculateTotal();
}

// if (process.env.NODE_ENV !== 'production') {
//     console.log('Valor sensível:', valorSensivel);
// }

// if (process.env.NODE_ENV === 'development') {
//     console.log('Somente exibido em ambiente de desenvolvimento');
// }

document
    .getElementById("sales-search-form")
    .addEventListener("submit", function(event) {
        event.preventDefault();

        let searchInput = document.getElementById("clientSelectedSales").value;
        let tableRows = document.querySelectorAll(".tbody-selected tr");

        tableRows.forEach(function(row) {
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

document.addEventListener("DOMContentLoaded", function() {
    let tableRows = document.querySelectorAll(".tbody-selected");
    tableRows.forEach(function(row) {
        row.addEventListener("dblclick", function() {
            let clientName = row.querySelector("td:nth-child(2)").textContent;
            let salesPageElement = document.getElementById("sales-page");

            selectedClientId = row.querySelector("td:first-child").textContent;

            console.log(selectedClientId, clientName);

            if (salesPageElement) {
                salesPageElement.innerHTML =
                    "Codigo do cliente: " + selectedClientId + " Nome do cliente: " + clientName;
            }
        });
    });
});

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