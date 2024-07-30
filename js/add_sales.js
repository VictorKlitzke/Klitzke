let selectedProducts = [];
let selectedPortion = [];
let selectedClientId;
let Portion;
let OverlayPortion;
let portionValues;

const trProduct = document.getElementById("product-result");
const tdButton = document.getElementById("button-product");

const ModalSalesPortion = document.getElementById('portion-sales');
const overlayPortion = document.getElementById('overlay-portion');

const saveButton = document.getElementById('button-portion');
const descPortionTbody = document.getElementById('desc-portion');
const totalPortionElement = document.getElementById('total-portion-sales');
const totalAmountElement = document.getElementById('totalAmount');
const portionTotalInput = document.getElementById('portion-total');

document.addEventListener('DOMContentLoaded', function () {
    Portion = document.querySelector('.portion-sales');
    OverlayPortion = document.querySelector('.overlay-portion');
});

function AddSelectProducts(index, id, name, stock_quantity, value) {
    let productAlreadyExists = false;

    for (let i = 0; i < selectedProducts.length; i++) {
        if (selectedProducts[i].id === id) {
            let number = selectedProducts[i].stock_quantity + 1;

            validateStock(stock_quantity, number, function(isValid) {
                if (isValid) {
                    selectedProducts[i].stock_quantity = number;
                    let productQuantityCell = document.getElementById("product-quantity-" + id);
                    if (productQuantityCell) {
                        productQuantityCell.textContent = number;
                    }
                    updateProductQuantity(id, number);
                    calculateTotal();
                }
            });

            productAlreadyExists = true;
            break; 
        }
    }

    if (!productAlreadyExists) {
        validateStock(stock_quantity, 1, function(isValid) {
            if (isValid) {
                let newProduct = {
                    id: id,
                    name: name,
                    stock_quantity: 1,
                    value: parseFloat(value.replace(',', '.'))
                };

                selectedProducts.push(newProduct);

                let newRow = trProduct.insertRow();
                newRow.id = "row-" + id;
                newRow.innerHTML = "<td id='product-id'>" + id + "</td>" +
                    "<td id='product-name'>" + name + "</td>" +
                    "<td id='product-quantity-" + id + "'>" + 1 + "</td>" +
                    "<td id='product-value' class='content-form'>" +
                    "<input type='text' class='form-control' id='value" + id + "' value='" + value + "' />" +
                    "</td>" +
                    "<td style='margin: 6px; padding: 6px;'>" +
                    "<div>" +
                    "<button onclick='removeProduct(" + id + ")' id='button-delete-" + id + "' class='btn btn-danger' type='button'>Deletar</button>" +
                    "<button onclick='editProductValue(" + id + ")' class='btn btn-info' style='margin-left: 5px;' type='button'>Editar Valor</button>" +
                    "</div>" +
                    "</td>";

                calculateTotal();
            }
        });
    }
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

document.addEventListener('DOMContentLoaded', function () {

    function checkPaymentMethod() {
        var selectedPaymentMethod = document.getElementById('id_payment_method').value;
        if (selectedPaymentMethod === '3') {
            openCreditModal();
        } else {
            closeCreditModal();
        }
    }

    document.getElementById('id_payment_method').addEventListener('change', checkPaymentMethod);

    const finishButtonPortion = document.getElementById('finish-portion');

    if (saveButton) {
        saveButton.addEventListener('click', function () {
            portionValues = calculateAndDisplayPortions();
        });
    }

    // if (finishButtonPortion) {
    //     finishButtonPortion.addEventListener('click', function() {
    //         finalizeSalePortion();
    //     });
    // }

    function calculateAndDisplayPortions() {
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

            selectedPortion.push({
                portionValue: portionValue,
                portionTotal: i
            })
        }

        totalPortionElement.textContent = 'R$ ' + portionValue.toFixed(2);

        return {
            selectedPortion: selectedPortion
        };
    }
});

async function finalizeSalePortion() {

    let totalAmountElement = document.getElementById('totalAmount');
    let totalValuezPortion = 0;
    if (totalAmountElement) {
        totalValuezPortion = parseFloat(totalAmountElement.textContent.replace('R$ ', '')) || 0;
    }

    let selectedPaymentMethodPortion = document.getElementById('id_payment_method').value;
    let idSalesClientPortion = selectedClientId || '';

    if (selectedPaymentMethodPortion === '3') {
        openCreditModal();

        let requestDataPortion = {
            idPaymentMethod: selectedPaymentMethodPortion,
            salesIdClient: idSalesClientPortion,
            totalValue: totalValuezPortion,
            selectedPortion: selectedPortion,
            products: selectedProducts
        };

        if (selectedProducts.length === 0) {

            showMessage('Erro ao registrar venda, nenhum produto selecionado', 'warning');

        } else {

            try {
                const responsePortion = await fetch(`${BASE_URL}add_sales_portion.php`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(requestDataPortion),
                });

                const responseBodyPortion = await responsePortion.text();
                const responseDataPortion = JSON.parse(responseBodyPortion);

                if (responseDataPortion && responseDataPortion.success) {
                    showMessage('Venda finalizada com sucesso!', 'success');

                    overlayPortion.style.display = 'none';
                    ModalSalesPortion.style.display = 'none';
                } else {
                    showMessage('Erro ao registrar venda:', responseDataPortion ? responseDataPortion.error : 'Resposta vazia', 'error');
                }
            } catch (error) {
                showMessage('Erro ao enviar dados para o PHP:', error, 'error');
            }
        }
    } else {
        return false;
    }
}

async function finalizeSale() {

    let rowProducts = document.getElementById('row-1');

    let totalAmountElement = document.getElementById('totalAmount');
    let totalValue = 0;
    if (totalAmountElement) {
        totalValue = parseFloat(totalAmountElement.textContent.replace('R$ ', '')) || 0;
    }

    let selectedPaymentMethod = document.getElementById('id_payment_method').value;
    let idSalesClient = selectedClientId || '';

    let requestData = {
        idPaymentMethod: selectedPaymentMethod,
        salesIdClient: idSalesClient,
        totalValue: totalValue,
        products: selectedProducts
    };

    if (selectedProducts.length === 0) {
        showMessage('Erro ao registrar venda, nenhum produto selecionado', 'warning');

    } else {
        try {
            let url = `${BASE_URL}add_sales.php`;

            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(requestData),
            });

            const responseBody = await response.text();
            const responseData = JSON.parse(responseBody);

            if (responseData && responseData.success) {
                showMessage('Venda finalizada com sucesso!', 'success');
            } else {
                showMessage('Caixa não foi aberto, para essa operação', 'error');
            }

        } catch (error) {
            showMessage('Erro ao enviar dados para o PHP:' + error, 'error');
        }
    }
}

function showQRCode(qrCodeDataUri) {
    let qrCodeContainer = document.getElementById('qrcode');

    if (!qrCodeContainer) {
        qrCodeContainer = document.createElement('div');
        qrCodeContainer.id = 'qrcode';
        document.body.appendChild(qrCodeContainer);
    }

    qrCodeContainer.innerHTML = `<img src="${qrCodeDataUri}" style="width: 500px; display: flex; justify-content: center; align-items: center; z-index: 999" />`;
    qrCodeContainer.style.display = 'block';
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
                let clientSearch = document.getElementById('client-search-sales');
                let overlay = document.getElementById('overlay');

                clientSearch.style.display = 'none';
                overlay.style.display = 'none';

                salesPageElement.innerHTML =
                    "Codigo do cliente: " + selectedClientId + " Nome do cliente: " + clientName;
            }
        });
    });
});

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

function validateStock(stock_quantity, qnt, callback) {
    if (stock_quantity < qnt) {
        continueMessage("Você não possui estoque suficiente. Deseja continuar?", "Sim", "Não", function () {
            callback(true); 
        }, function () {
            showMessage('Operação cancelada', 'warning');
            callback(false);
        });
    } else {
        callback(true);
    }
}
async function closeModalPortion() {
    const portionSalesModal = document.getElementById('portion-sales');
    const overlayModalPortion = document.getElementById('overlay-portion');
    const closeModalPortion = document.getElementById('close-portion');

    if ((portionSalesModal.style.display === 'block' && overlayModalPortion.style.display === 'block')) {
        portionSalesModal.style.display = 'none';
        overlayModalPortion.style.display = 'none';
    }
}