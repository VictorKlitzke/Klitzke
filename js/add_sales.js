let selectedProducts = [];
let selectedPortion = [];
let selectedAprazo = [];

let selectedClientId;
let Portion;
let OverlayPortion;
let portionValues;

const trProduct = document.getElementById("product-result");
const tdButton = document.getElementById("button-product");

const ModalSalesPortion = document.getElementById('portion-sales');
const overlayPortion = document.getElementById('overlay-portion');

const APrazoModal = document.getElementById('aprazo-sales');
const OverlayAPrazo = document.getElementById('overlay-aprazo');

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

            validateStock(stock_quantity, number, function (isValid) {
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
        validateStock(stock_quantity, 1, function (isValid) {
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
                newRow.className = 'sales-sales';
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

function openAPrazoModal() {
    if (APrazoModal && OverlayAPrazo) {
        APrazoModal.style.display = 'block';
        OverlayAPrazo.style.display = 'block';
    }
}

function closeAPrazoModal() {
    if (APrazoModal && OverlayAPrazo) {
        APrazoModal.style.display = 'none';
        OverlayAPrazo.style.display = 'none';
    }
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

        if (selectedPaymentMethod === '5') {
            openAPrazoModal();
        } else {
            closeAPrazoModal();
        }
    }

    document.getElementById('id_payment_method').addEventListener('change', checkPaymentMethod);

    const finishButtonPortion = document.getElementById('finish-portion');

    if (saveButton) {
        saveButton.addEventListener('click', function () {
            portionValues = calculateAndDisplayPortions();
        });
    }

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

function calculateInstallments() {

    let numInstallments = parseInt(document.getElementById('aprazo-number').value);
    let daysBetweenInstallments = parseInt(document.getElementById('aprazo-venciment').value);
    let startDate = document.getElementById('aprazo-venciment-date').value;

    let totalAmountElement = document.getElementById('totalAmount');
    let totalValuezAPrazo = 0;
    if (totalAmountElement) {
        totalValuezAPrazo = parseFloat(totalAmountElement.textContent.replace('R$ ', '').replace(',', '.')) || 0;
    }

    if (isNaN(numInstallments) || isNaN(daysBetweenInstallments) || isNaN(totalValuezAPrazo) || numInstallments <= 0 ||
        daysBetweenInstallments < 0 || totalValuezAPrazo <= 0 || !startDate) {
        showMessage("Por favor, insira valores válidos para o número de parcelas, dias de vencimento e valor total.", 'warning');
        return;
    }

    function parseDate(dateStr) {
        const [year, month, day] = dateStr.split('-');
        return new Date(year, month - 1, day);
    }
    let currentDate = parseDate(startDate);

    let installmentValue = totalValuezAPrazo / numInstallments;
    let tableBody = document.getElementById('desc-aprazo');
    tableBody.innerHTML = '';

    for (let i = 1; i <= numInstallments; i++) {
        let dueDate = new Date(currentDate);
        dueDate.setDate(currentDate.getDate() + (i - 1) * daysBetweenInstallments);

        let formattedDate = dueDate.toLocaleDateString('pt-BR');
        let row = `<tr>
                        <td>${i}</td>
                        <td>${formattedDate}</td>
                        <td>R$ ${installmentValue.toFixed(2)}</td>
                   </tr>`;
        tableBody.innerHTML += row;

        selectedAprazo.push({
            portionAprazo: i,
            date_venciment: formattedDate,
            installmentValue: installmentValue
        });
    }
    document.getElementById('total-aprazo-sales').innerText = `Total a Pagar: R$ ${totalValuezAPrazo.toFixed(2)}`;
}

async function FinalizeAprazo() {
    const saleSales = document.querySelector('.sales-sales');

    let totalAmountElement = document.getElementById('totalAmount');
    let totalValuezAPrazo = 0;
    if (totalAmountElement) {
        totalValuezAPrazo = parseFloat(totalAmountElement.textContent.replace('R$ ', '')) || 0;
    }

    let selectedPaymentMethodAprazo = document.getElementById('id_payment_method').value;
    let idSalesClientAprazo = selectedClientId || '';

    if (selectedPaymentMethodAprazo === '5') {
        openAPrazoModal();

        let requestDataAPrazo = {
            idPaymentMethod: selectedPaymentMethodAprazo,
            salesIdClient: idSalesClientAprazo,
            totalValue: totalValuezAPrazo,
            selectedAprazo: selectedAprazo,
            products: selectedProducts
        };

        if (idSalesClientAprazo.length === 0) {
            showMessage('Erro nenhum cliente informado', 'warning');
            return;
        }

        if (selectedProducts.length === 0) {

            showMessage('Erro ao registrar venda, nenhum produto selecionado', 'warning');

        } else {

            try {

                let url = `${BASE_URL}add_sales_aprazo.php`;

                const responseAPrazo = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(requestDataAPrazo),
                });

                const responseBodyAPrazo = await responseAPrazo.text();
                const responseDataAPrazo = JSON.parse(responseBodyAPrazo);

                if (responseDataAPrazo && responseDataAPrazo.success) {
                    showMessage('Venda finalizada com sucesso!', 'success');

                    let printSales = {
                        date: new Date().toLocaleString(),
                        clientName: idSalesClient,
                        totalValue: totalValue,
                        products: selectedProducts.map(product => ({
                            name: product.name,
                            value: parseFloat(product.value),
                        }))
                    }

                    setTimeout(() => {
                        continueMessage("Deseja imprimir comprovante?", "Sim", "Não", async function () {
                            printReceipt(printSales);
                        }, function () {
                            showMessage('Operação cancelada', 'warning')
                        })
                    }, 5000);

                    OverlayAPrazo.style.display = 'none';
                    APrazoModal.style.display = 'none';
                    saleSales.innerHTML = "";
                    saleSales.innerText = "";
                } else {
                    showMessage('Erro ao registrar venda:' + responseDataAPrazo.error, 'error');
                }
            } catch (error) {
                showMessage('Erro ao enviar dados para o PHP:' + error, 'error');
            }
        }
    } else {
        return false;
    }
}

async function finalizeSalePortion() {

    const saleSales = document.querySelector('.sales-sales');

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

                    let printSales = {
                        date: new Date().toLocaleString(),
                        clientName: idSalesClient,
                        totalValue: totalValue,
                        products: selectedProducts.map(product => ({
                            name: product.name,
                            value: parseFloat(product.value),
                        }))
                    }

                    setTimeout(() => {
                        continueMessage("Deseja imprimir comprovante?", "Sim", "Não", async function () {
                            printReceipt(printSales);
                        }, function () {
                            showMessage('Operação cancelada', 'warning')
                        })
                    }, 5000);

                    overlayPortion.style.display = 'none';
                    ModalSalesPortion.style.display = 'none';
                    saleSales.innerHTML = "";
                    saleSales.innerText = "";
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

async function printReceipt(saleDetails) {
    let printWindow = window.open('', '_blank', 'width=800,height=600');

    let receiptContent = `
        <html>
            <head>
                <title>Comprovante de Venda</title>
                <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css">
                <style>
                    .receipt-container {
                        border: 1px solid #dee2e6;
                        padding: 20px;
                        max-width: 400px;
                        margin: 0 auto;
                    }
                    .receipt-header {
                        background-color: #f8f9fa;
                        padding: 15px;
                        border-bottom: 1px solid #dee2e6;
                    }
                    .receipt-footer {
                        border-top: 1px solid #dee2e6;
                        padding-top: 15px;
                        margin-top: 20px;
                    }
                    .receipt-items {
                        border-bottom: 1px solid #dee2e6;
                        padding-bottom: 15px;
                        margin-bottom: 15px;
                    }
                    .receipt-item {
                        font-size: 14px;
                    }
                    .receipt-total {
                        font-size: 18px;
                        font-weight: bold;
                    }
                </style>
            </head>
            <body>
                <div class="container mt-5">
                    <div class="receipt-container shadow-sm rounded">
                        <div class="receipt-header text-center mb-3">
                            <h3>Comprovante de Venda</h3>
                            <p><strong>Data:</strong> ${saleDetails.date}</p>
                            <p><strong>Cliente:</strong> ${saleDetails.clientName || 'N/A'}</p>
                        </div>

                        <div class="receipt-items">
                            <h5 class="mb-3">Itens</h5>
                            <ul class="list-group">
                                ${saleDetails.products.map(product => `
                                    <li class="list-group-item d-flex justify-content-between align-items-center receipt-item">
                                        <span>${product.name}</span>
                                        <span>R$ ${product.value.toFixed(2)}</span>
                                    </li>
                                `).join('')}
                            </ul>
                        </div>

                        <div class="receipt-footer text-end">
                            <p class="receipt-total">Total: R$ ${saleDetails.totalValue.toFixed(2)}</p>
                        </div>
                    </div>
                </div>
            </body>
        </html>
    `;

    printWindow.document.write(receiptContent);
    printWindow.document.close();
    printWindow.focus();
    printWindow.print();
}

async function finalizeSale() {
    const saleSales = document.querySelector('.sales-sales');
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

    console.log(requestData);

    if (selectedProducts.length === 0) {
        showMessage('Erro ao registrar venda, nenhum produto selecionado', 'warning');
        return;
    }

    if (selectedPaymentMethod == 1) {
        let urlQrCode = `${BASE_URL}qrcode.php`;
        const requestDataQr = { totalValue: totalValue };

        try {
            const responseQr = await fetch(urlQrCode, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(requestDataQr),
            });

            const responseBodyQr = await responseQr.json();

            if (responseBodyQr && responseBodyQr.success) {
                generateQRCode(responseBodyQr.qrCodePIX);
                openQRCodeModal();
                continueMessage("Pagamento recebido?", "Pago", "Cancelado", async function () {
                    await registerSale(requestData);
                    CloseQRCodeModal()
                }, function () {
                    showMessage('Operação cancelada', 'error');
                })
            } else {
                showMessage('Erro ao gerar QR Code: ' + responseBodyQr.message, 'error');
            }
        } catch (error) {
            showMessage('Erro na comunicação com o servidor ao gerar QR Code', 'error');
        }
    } else {
        await registerSale(requestData);
    }
}

async function registerSale(requestData) {
    let urlSales = `${BASE_URL}add_sales.php`;

    try {
        const responseSales = await fetch(urlSales, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(requestData),
        });

        const responseBody = await responseSales.text();
        const responseData = JSON.parse(responseBody);

        if (responseData && responseData.success) {
            showMessage('Venda finalizada com sucesso!', 'success');

            let printSales = {
                date: new Date().toLocaleString(),
                clientName: requestData.salesIdClient,
                totalValue: requestData.totalValue,
                products: requestData.products.map(product => ({
                    name: product.name,
                    value: parseFloat(product.value),
                }))
            };

            setTimeout(() => {
                continueMessage("Deseja imprimir comprovante?", "Sim", "Não", async function () {
                    printReceipt(printSales);
                }, function () {
                    showMessage('Operação cancelada', 'warning');
                });
            }, 5000);

            const saleSales = document.querySelector('.sales-sales');
            saleSales.innerHTML = "";
            saleSales.innerText = "";
        } else {
            showMessage('Caixa não foi aberto, para essa operação', 'error');
        }
    } catch (error) {
        showMessage('Erro ao enviar dados para o PHP: ' + error, 'error');
    }
}


function generateQRCode(qrCodeData) {
    const qrCodeContainer = document.getElementById('qrCodeContainer');

    if (!qrCodeContainer) {
        console.error('Elemento qrCodeContainer não encontrado.');
        return;
    }

    qrCodeContainer.innerHTML = "";

    const qrCodeImage = document.createElement('img');
    qrCodeImage.style.margin = 'auto';
    qrCodeImage.style.height = '50%';
    qrCodeImage.style.width = '50%';

    qrCodeImage.src = `data:image/png;base64,${qrCodeData}`;

    qrCodeImage.alt = "QR Code PIX";

    qrCodeContainer.appendChild(qrCodeImage);
}

function openQRCodeModal() {
    const qrCodeModal = new bootstrap.Modal(document.getElementById('qrCodeModal'));
    qrCodeModal.show();
}
function CloseQRCodeModal() {
    const qrCodeModal = document.getElementById('qrCodeModal');
    const modalInstance = bootstrap.Modal.getInstance(qrCodeModal);
    modalInstance.hide();
}


async function confirmPayment() {
    return new Promise((resolve) => {
        setTimeout(() => {
            resolve(true);
        }, 3000);
    });
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
            let clientName = row.querySelector("th:nth-child(2)").textContent;
            let salesPageElement = document.getElementById("sales-page");

            selectedClientId = row.querySelector("th:first-child").textContent;

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

async function CloseModalAPrazo() {
    const aprazoSalesModal = document.getElementById('aprazo-sales');
    const overlayModalaprazo = document.getElementById('overlay-aprazo');
    const closeModalPortion = document.getElementById('close-aprazo');

    if ((aprazoSalesModal.style.display === 'block' && overlayModalaprazo.style.display === 'block')) {
        aprazoSalesModal.style.display = 'none';
        overlayModalaprazo.style.display = 'none';
    }
}