let selectedProducts = [];
let selectedPortion = [];
let selectedAprazo = [];

let selectedClientId;
let Portion;
let OverlayPortion;
let portionValues;


const ModalSalesPortion = document.getElementById('portion-sales');
const overlayPortion = document.getElementById('overlay-portion');

const APrazoModal = document.getElementById('aprazo-sales');
const OverlayAPrazo = document.getElementById('overlay-aprazo');

const saveButton = document.getElementById('button-portion');
const descPortionTbody = document.getElementById('desc-portion');
const totalPortionElement = document.getElementById('total-portion-sales');
const totalAmountElement = document.getElementById('total-display');
const portionTotalInput = document.getElementById('portion-total');

const RowProducts = document.getElementById('selected-products-body');

document.addEventListener('DOMContentLoaded', function () {
    Portion = document.querySelector('.portion-sales');
    OverlayPortion = document.querySelector('.overlay-portion');
});

async function searchClients(event) {

    event.preventDefault();
    const clientSearch = document.getElementById('client-search').value;
    const xhr = new XMLHttpRequest();

    try {

        let url = `${BASE_CONTROLLERS}searchs.php`;
        xhr.open("POST", url, true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    document.getElementById('client-results').innerHTML = xhr.responseText;
                } else {
                    document.getElementById('client-results').innerHTML = "Erro na busca dos clientes.";
                }
            }
        }
    } catch (error) {
        showMessage('Erro na requisição', 'error')
    }

    xhr.send(`client_search=${encodeURIComponent(clientSearch)}`);
}

function addClientSales(clientId, clienteName) {
    const selectedClient = document.getElementById("selected-client");

    if (selectedClient.children.length === 0) {
        const NewP = document.createElement("p");
        NewP.innerHTML = `<span><strong id="client-sales">${clientId}</strong> - ${clienteName}</span>`;
        selectedClient.appendChild(NewP);

        selectedClientId = clientId;

        document.getElementById('client-search').value = "";
        document.getElementById('client-results').innerHTML = "";
        document.getElementById('client-search').disabled = true;
        document.querySelector('button[onclick="searchClients(event)"]').disabled = true;
    }
}

async function searchProduct(event) {
    event.preventDefault();

    const productSearch = document.getElementById('product-search').value;
    const xhr = new XMLHttpRequest();

    try {

        let url = `${BASE_CONTROLLERS}searchs.php`;
        xhr.open("POST", url, true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    document.getElementById('search-results').innerHTML = xhr.responseText;
                } else {
                    document.getElementById('search-results').innerHTML = "Erro na busca dos produtos.";
                }
            }
        };

    } catch (error) {
        showMessage('Erro na requisição', 'error')
    }

    xhr.send(`product_search=${encodeURIComponent(productSearch)}`);
}

function addProductToTable(productId, productName, productPrice) {
    const tbody = document.getElementById('selected-products-body');
    const newRow = document.createElement('tr');

    if (typeof productPrice === 'undefined' || isNaN(productPrice)) {
        showMessage('Produto indefinido ou valor do produto indefinido', 'warning');
        return;
    }

    newRow.innerHTML = `
        <td>${productId}</td>
        <td>${productName}</td>
        <td>
            <input class="quantitySales" id="quantitySales" value="1" type="number" onchange="updateQuantity(this, ${productPrice})">
        </td>
        <td>
            <input id="price-sales" type="text" value="${productPrice.toFixed(2).replace('.', ',')}" onchange="updatePrice(this, 1)">
        </td>
        <td class="total-price">R$ ${numberFormat(productPrice)}</td>
        <td><button class="btn" onclick="removeProduct(this)">🗑️</button></td>
    `;

    tbody.appendChild(newRow);

    selectedProducts.push({
        productId: productId,
        productName: productName,
        productPrice: productPrice,
        quantity: 0
    });

    clearSearch();
    updateTotalDisplay();
}

async function updatePrice(input) {
    const price = parseFloat(input.value.replace('R$ ', '').replace('.', '').replace(',', '.'));
    const row = input.closest('tr');
    const quantityInput = row.querySelector('#quantitySales');
    const quantity = parseInt(quantityInput.value) || 1;
    const totalPriceCell = row.querySelector('.total-price');

    if (!price || isNaN(price) || price <= 0) {
        showMessage('Problema no preço, insira um valor válido', 'warning');
        totalPriceCell.innerText = 'R$ 0,00';
        return;
    }

    const totalPrice = price * quantity;
    totalPriceCell.innerText = `R$ ${numberFormat(totalPrice)}`;
    await updateTotalDisplay();
}

function updateQuantity(inputElement, productPrice) {

    const quantity = parseInt(inputElement.value) || 0;

    const row = inputElement.closest('tr');
    const productId = row.cells[0].innerText;

    const productIndex = selectedProducts.findIndex(product => product.productId === productId);
    if (productIndex !== -1) {
        selectedProducts[productIndex].quantity = quantity;
    }

    const totalPrice = quantity * productPrice;
    row.querySelector('.total-price').innerText = `R$ ${totalPrice.toFixed(2).replace('.', ',')}`;
}

async function updateTotalDisplay() {
    const rows = document.querySelectorAll('#selected-products-body tr');
    let total = 0;

    rows.forEach(row => {
        const priceCell = row.querySelector('.total-price').innerText
            .replace('R$ ', '')
            .replace('.', '')
            .replace(',', '.');
        total += parseFloat(priceCell) || 0;
    });

    document.getElementById('total-display').innerText = `R$ ${numberFormat(total)}`;
}

async function removeProduct(button) {
    const row = button.closest('tr');
    const quantityInput = row.querySelector('#quantitySales');
    let quantity = parseInt(quantityInput.value);

    selectedProducts = selectedProducts.map(product => {
        if (quantity > 1) {
            quantity--;
            quantityInput.value = quantity;
            return product;
        } else if (quantity === 0 || quantity === 1) {
            row.remove();
        } else {
            const totalPriceCell = row.querySelector('.total-price');
            const price = parseFloat(row.querySelector('input[type="number"]').value.replace(',', '.'));
            totalPriceCell.innerText = `R$ ${numberFormat(price * quantityInput.value)}`;
        }
    }).filter(product => product !== null);

    updateTotalDisplay();
}

function clearSearch() {
    document.getElementById('product-search').value = "";
    document.getElementById('search-results').innerHTML = "";
}

function numberFormat(value) {
    return value.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
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
        let selectedPayment = document.querySelector('input[name="id_payment_method"]:checked');
        if (selectedPayment) {
            let selectedPaymentMethod = selectedPayment.value;

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
    }

    const paymentRadios = document.querySelectorAll('input[name="id_payment_method"]');
    paymentRadios.forEach(radio => {
        radio.addEventListener('change', checkPaymentMethod);
    });

    checkPaymentMethod();

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
    let totalAmountElement = document.getElementById('total-display');
    let totalValuezAPrazo = 0;

    if (totalAmountElement) {
        let totalText = totalAmountElement.textContent.replace('R$ ', '').replace('.', '').replace(',', '.');
        totalValuezAPrazo = parseFloat(totalText) || 0;
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

    let totalAmountElement = document.getElementById('total-display');
    let totalValue = 0;

    if (totalAmountElement) {
        let totalText = totalAmountElement.textContent.replace('R$ ', '').replace('.', '').replace(',', '.');
        totalValue = parseFloat(totalText) || 0;
    }

    let selectedPayment = document.querySelector('input[name="id_payment_method"]:checked');
    if (selectedPayment == null) {
        showMessage('Selecione uma forma de pagamento', 'warning');
        return;
    }
    let selectedPaymentMethodAprazo = selectedPayment.value;

    if (selectedPaymentMethodAprazo === '5') {
        openAPrazoModal();

        let requestDataAPrazo = {
            idPaymentMethod: selectedPaymentMethodAprazo,
            selectedClientId: selectedClientId,
            totalValue: totalValue,
            selectedAprazo: selectedAprazo,
            selectedProducts: selectedProducts
        };

        if (selectedClientId == 0) {
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
                        selectedProducts: selectedProducts.map(product => ({
                            productName: product.productName,
                            productPrice: parseFloat(product.productPrice),
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

    let totalAmountElement = document.getElementById('total-display');
    let totalValue = 0;

    if (totalAmountElement) {
        let totalText = totalAmountElement.textContent.replace('R$ ', '').replace('.', '').replace(',', '.');
        totalValue = parseFloat(totalText) || 0;
    }

    let selectedPayment = document.querySelector('input[name="id_payment_method"]:checked');
    if (selectedPayment == null) {
        showMessage('Selecione uma forma de pagamento', 'warning');
        return;
    }
    let selectedPaymentMethodPortion = selectedPayment.value;

    if (selectedPaymentMethodPortion === '3') {
        openCreditModal();

        let requestDataPortion = {
            idPaymentMethod: selectedPaymentMethodPortion,
            selectedClientId: selectedClientId,
            totalValue: totalValue,
            selectedPortion: selectedPortion,
            selectedProducts: selectedProducts
        };

        if (selectedProducts == 0) {

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
                        selectedProducts: selectedProducts.map(product => ({
                            productName: product.productName,
                            productPrice: parseFloat(product.productPrice),
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
                                ${saleDetails.selectedProducts.map(product => `
                                    <li class="list-group-item d-flex justify-content-between align-items-center receipt-item">
                                        <span>${product.productName}</span>
                                        <span>R$ ${product.productPrice.toFixed(2)}</span>
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

function calculateChange() {
    let trocoSales = document.getElementById('change-sale');
    let quantiyChange = document.getElementById('change-amount');
    let totalAmountElement = document.getElementById('total-display');

    if (totalAmountElement) {
        let totalText = totalAmountElement.textContent.replace('R$ ', '').replace('.', '').replace(',', '.');
        totalValue = parseFloat(totalText) || 0;
    }

    let receivedValue = parseFloat(trocoSales.value.replace('.', '').replace(',', '.')) || 0;
    let TotalChange = receivedValue - totalValue;

    quantiyChange.textContent = 'R$ ' + TotalChange.toFixed(2).replace('.', ',');
}

async function finalizeSale() {
    let totalAmountElement = document.getElementById('total-display');
    let totalValue = 0;

    if (totalAmountElement) {
        let totalText = totalAmountElement.textContent.replace('R$ ', '').replace('.', '').replace(',', '.');
        totalValue = parseFloat(totalText) || 0;
    }
    let quantiyChange = document.getElementById('change-amount').value;
    let selectedPayment = document.querySelector('input[name="id_payment_method"]:checked');
    if (selectedPayment == null) {
        showMessage('Selecione uma forma de pagamento', 'warning');
        return;
    }
    let selectedPaymentMethod = selectedPayment.value;

    let requestData = {
        idPaymentMethod: selectedPaymentMethod,
        selectedClientId: selectedClientId,
        totalValue: totalValue,
        quantiyChange: quantiyChange,
        selectedProducts: selectedProducts
    };

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
                clientName: requestData.selectedClientId,
                totalValue: requestData.totalValue,
                selectedProducts: requestData.selectedProducts.map(product => ({
                    productName: product.productName,
                    productPrice: parseFloat(product.productPrice),
                }))
            };

            setTimeout(() => {
                continueMessage("Deseja imprimir comprovante?", "Sim", "Não", async function () {
                    printReceipt(printSales);
                }, function () {
                    showMessage('Operação cancelada', 'warning');
                });
            }, 5000);

            setTimeout(() => {
                location.reload();
            }, 8000);


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