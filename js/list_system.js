const ButtonSearchBuyRequest = document.getElementById('button-search');
const FieldFormBuyRequest = document.getElementById('input-buy-request');
const FieldFormVariationValues = document.getElementById('input-variation-values');
const FieldFormFinancialControl = document.getElementById('input-financial-control');

let AddVariation = {};
let notificationQueue = [];
let SelectedFat = [];

window.onload = ListProducts();
window.onload = ListForn();
window.onload = ListBuyRequest();
window.onload = ListVariationValues();
window.onload = loadValuesFromLocalStorage;
window.onload = AddVariationValues();
window.onload = calculateTotalsListAPrazo();
window.onload = ListInventary();

document.addEventListener('DOMContentLoaded', function () {
    ListDetailsAprazo();
    ListAPrazo();
    ListConditional();
    reopenModalIfSaved();
    restoreBillingItems();

    const isModalOpen = localStorage.getItem('fullScreenModal') === 'true';
    if (isModalOpen) {
        const modal = new bootstrap.Modal(document.getElementById('fullScreenModal'));
        modal.show();
    }
});

document.getElementById('fullScreenModal').addEventListener('hidden.bs.modal', () => {
    RemoveModalItens();
});

function showToast(message, id) {

    const toastContainer = document.getElementById('toastContainer');
    const toastElement = document.createElement('div');

    toastElement.className = 'toast';
    toastElement.setAttribute('role', 'alert');
    toastElement.setAttribute('aria-live', 'assertive');
    toastElement.setAttribute('aria-atomic', 'true');
    toastElement.dataset.id = id;

    toastElement.innerHTML = `
        <div class="toast-header">
            <img src="https://via.placeholder.com/20" class="rounded me-2" alt="Notification">
            <strong class="me-auto">Notificação</strong>
            <small class="text-body-secondary">Agora</small>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close" onclick="removeNotification(${id})"></button>
        </div>
        <div class="toast-body">
            ${message}
            <div class="mt-2">
                <button class="btn btn-success btn-sm" onclick="updateStatus(${id})">Marcar como resolvido</button>
            </div>
        </div>
    `;

    toastContainer.appendChild(toastElement);
    const toast = new bootstrap.Toast(toastElement, {
        animation: true,
        autohide: false
    });
    toast.show();
}
function removeNotification(id) {
    const toastElement = document.querySelector(`#toastContainer .toast[data-id="${id}"]`);
    if (toastElement) {
        toastElement.remove();
    }
}
function addNotificationToQueue(message, id) {
    showToast(message, id);
}

async function InativarInvo(button) {

    const id_request_inativar = button.getAttribute('data-id');

    if (!id_request_inativar) {
        showMessage('ID indentificado', 'warning');
        return;
    }

    const continueInativar = confirm("Desseja realmente inativar pedido?");

    if (continueInativar) {
        try {

            let url = `${BASE_URL}inativar_request.php`;

            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ id_inativar: id_request_inativar })
            })

            const responseText = await response.text();

            let result;
            try {
                result = JSON.parse(responseText);
            } catch (e) {
                showMessage('Erro inesperado ao processar a inativação do pedido. Entre em contato com o suporte.', 'error');
                return;
            }

            if (result.success) {
                showMessage('Pedido inativado com suceesso', 'success');
                window.location.reload();
            } else {
                showMessage('Erro ao inativar pedido: ' + result.message, 'error');
            }

        } catch (error) {
            showMessage('Erro ao fazer requisiçao, entre em contato com o suporte! ' + error, 'error');
        }
    }
}
async function ShowOnPage(button) {

    const id_product_page = button.getAttribute('data-id');

    if (!id_product_page) {
        window.alert("Impossivel continuar sem o ID do produto");
        return;
    }

    const continuePage = confirm("Deseja realmente mostrar o produto na pagina?")

    if (continuePage) {
        try {

            let url = `${BASE_URL}show_on_page.php`;

            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ id_product: id_product_page })
            })

            const responseText = response.text();

            let result;
            try {
                result = JSON.parse(responseText);
            } catch (e) {
                window.alert("Erro inesperado ao tentar mostrar produto. Entre em contato com o suporte.");
                return;
            }

            if (result.success) {
                window.alert("Produto está mostrando na pagina com sucesso")
            } else {
                window.alert("Erro ao mostrar produto na pagina" + result.message)
            }

        } catch (error) {
            window.alert(" Erro ao fazer requisiçao, entre em contato com o suporte! " + error);
        }
    }

}
async function CancelSales(button) {
    const id_sales_cancel = button.getAttribute('data-id');;

    if (!id_sales_cancel) {
        showMessage('ID da venda nao identificado', 'warning');
        return;
    }

    const constinueCancel = confirm("Deseja realmente cancelar essa venda?");

    if (constinueCancel) {
        try {
            let url = `${BASE_URL}cancel_sales.php`;

            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ id_sales_cancel: id_sales_cancel })
            })

            const responseText = await response.text();
            let result;

            try {
                result = JSON.parse(responseText)
            } catch (error) {
                showMessage('Erro interno, entre em contato com o suporte' + error, 'error')
            }

            if (result.success) {
                showMessage('Venda cancelada com sucesso!', 'success');
                window.location.reload();
            } else {
                showMessage('Erro ao tentar cancelar a venda' + result.getMessage(), 'error');
            }

        } catch (error) {
            showMessage('Erro interno, entre em contato com o suporte' + error, 'error')
            return;
        }
    }
}
async function ReopenSales(button) {

    const id_sales_reopen = button.getAttribute("data-id");

    if (!id_sales_reopen) {
        window.alert("ID da venda nao encontrado");
        return;
    }

    const continueReopen = confirm("Deseja realmente reabrir venda?");

    if (continueReopen) {
        try {
            let url = `${BASE_URL}reopen_sales.php`;

            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ id_sales_reopen: id_sales_reopen })
            })

            const responseText = await response.text();
            let result;

            try {
                result = JSON.parse(responseText);
            } catch (error) {
                window.alert("Erro interno ao tentar reabrir a venda" + error);
            }

            if (result.success) {
                window.alert("Venda reaberta com sucesso");
                window.location.reload();
            } else {
                window.alert("Erro ao reabrir venda" + result.error);
            }

        } catch (error) {
            window.alert("Erro interno, entre em contato com o suporte" + error);
            return;
        }
    }
}
async function PrintOut(button) {

    const id_print_out = button.getAttribute('data-id');

    if (!id_print_out) {
        window.alert('ID da venda nao foi encontrado');
    }

    const continuePrintOut = confirm("Deseja realmente imprimir essa venda?");

    if (continuePrintOut) {
        try {

            let url = `${BASE_URL}print_out.php`;

            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ id_print_out: id_print_out })
            })

            const result = await response.json();

            if (result.success) {
                const items = result.items;

                let printWindow = window.open('', '', 'width=800,height=600');
                printWindow.document.write('<html><head><title>Imprimir Venda</title></head><body>');
                printWindow.document.write('<h1>Venda ID: ' + id_print_out + '</h1>');
                printWindow.document.write('<table border="1"><tr><th>Produto</th><th>Quantidade</th><th>Preço</th></tr>');

                items.forEach(item => {
                    printWindow.document.write('<tr>');
                    printWindow.document.write('<th>' + item.name + '</th>');
                    printWindow.document.write('<th>' + item.amount + '</th>');
                    printWindow.document.write('<th>' + item.price_sales + '</th>');
                    printWindow.document.write('</tr>');
                });

                printWindow.document.write('</table>');
                printWindow.document.write('</body></html>');
                printWindow.document.close();
                printWindow.print();
            } else {
                window.alert('Erro ao buscar itens da venda: ' + result.error);
            }

        } catch (error) {
            window.alert("Erro interno ao tentar imprimir vanda" + error)
        }
    }
}
async function Details(button) {

    const id_detals = button.getAttribute('data-id');
    const modalDetails = document.getElementById('modal-print');

    modalDetails.style.display = 'block';

    if (!id_detals) {
        window.alert("ID da venda nao encontrado");
    }

    try {
        let url = `${BASE_URL}details_sales.php`;

        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ id_detals: id_detals })
        });

        const result = await response.json();

        if (result.success) {
            const items = result.items;

            const saleIdElement = document.getElementById('saleId');
            const modalTableBody = document.getElementById('modalTable').getElementsByTagName('tbody')[0];

            saleIdElement.textContent = id_detals;
            modalTableBody.innerHTML = '';

            items.forEach(item => {
                let row = modalTableBody.insertRow();
                if (item.clients == null) {
                    item.clients = "Cliente Consumidor";
                }
                row.insertCell(0).textContent = item.clients;
                row.insertCell(1).textContent = item.status_sales;
                row.insertCell(2).textContent = item.name;
                row.insertCell(3).textContent = item.amount;
                row.insertCell(4).textContent = item.price_sales;
                row.insertCell(5).textContent = item.form_payment;
                row.insertCell(6).textContent = item.users;
            });
            modalDetails.style.display = 'block';
        } else {
            window.alert('Erro ao buscar itens da venda: ' + result.error);
        }

    } catch (error) {
        window.alert("Erro interno ao tentar visualizar os detalhes da venda: " + error);
    }
}
async function CloseModalInfo() {

    const modalDetails = document.getElementById('modal-print');

    if ((modalDetails.style.display === 'block')) {
        modalDetails.style.display = 'none';
    }

}
async function DetailsOrder(button) {

    const id_pedido_details = button.getAttribute('data-id');
    const ModalOpenDetails = document.getElementById('modal-print-request');

    if (!id_pedido_details) {
        window.alert("ID do pedido nao encontrado!");
        return;
    }

    try {
        let url = `${BASE_URL}details_order.php`;

        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ id_pedido_details: id_pedido_details })
        });

        const result = await response.json();

        if (result.success) {
            const items = result.items;

            const requestIdElement = document.getElementById('requestID');
            const modalTableBodyRequest = document.getElementById('modalTable-request').getElementsByTagName('tbody')[0];

            requestIdElement.textContent = id_pedido_details;
            modalTableBodyRequest.innerHTML = '';
            items.forEach(item => {
                let row = modalTableBodyRequest.insertRow();
                row.insertCell(0).textContent = item.comanda;
                row.insertCell(1).textContent = item.name;
                row.insertCell(2).textContent = item.quantity;
                row.insertCell(3).textContent = item.price_request;
                row.insertCell(4).textContent = item.users;
                row.insertCell(5).textContent = item.form_payment;
                row.insertCell(6).textContent = item.pagamento_por_forma;
                row.insertCell(7).textContent = item.status_request;
                row.insertCell(8).textContent = item.total_request;
            });

            ModalOpenDetails.style.display = 'block';
        } else {
            window.alert('Erro ao buscar itens da venda: ' + result.error);
        }

    } catch (error) {
        window.alert("Erro interno, entre em contato com o suporte" + error);
    }
}
async function CloseModalInfoRequest() {

    const modalDetails = document.getElementById('modal-print-request');

    if ((modalDetails.style.display === 'block')) {
        modalDetails.style.display = 'none';
    }

}
async function InativarUsers(button) {
    const id_users_inativar = button.getAttribute('data-id');

    if (!id_users_inativar) {
        showMessage('Usuário não foi encontrado!', 'warning');
        return;
    }

    continueMessage("Deseja realmente desativar esse usuário?", "Sim", "Não", async function () {
        try {
            let url = `${BASE_URL}disable.php`;

            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ id_users_inativar: id_users_inativar })
            });

            if (!response.ok) {
                throw new Error(`Erro HTTP! Status: ${response.status}`);
            }

            const responseText = await response.text();

            let responseBody;
            try {
                responseBody = JSON.parse(responseText);
            } catch (error) {
                throw new Error('Erro ao converter resposta para JSON: ' + error.message);
            }
            if (responseBody.success) {
                showMessage('Usuário com ID ' + id_users_inativar + ' inativado com sucesso!', 'success');
                setTimeout(() => {
                    window.location.reload();
                }, 3000);
            } else {
                showMessage('Erro ao inativar usuário: ' + responseBody.message, 'error');
            }

        } catch (error) {
            showMessage('Erro ao fazer requisição: ' + error.message, 'error');
        }
    }, function () {
        showMessage('Operação cancelada', 'warning');
    });
}

async function ListConditional() {
    try {
        let url = `${BASE_CONTROLLERS}lists.php`;

        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ type: 'listconditional' })
        });

        if (!response.ok) {
            showMessage('Erro ao fazer requisição do servidor ' + response.statusText, 'warning');
        }

        const data = await response.json();

        if (data.success) {
            const conditional = data.result_condicional;
            const ConditionalList = document.getElementById('list-conditional');

            console.log(conditional);

            ConditionalList.innerHTML = '';

            conditional.forEach(c => {
                const row = document.createElement('tr')

                const idCell = document.createElement('th');
                idCell.textContent = c.id;
                idCell.id = 'id-conditional';
                row.appendChild(idCell);

                const clientCell = document.createElement('th');
                clientCell.textContent = c.clients;
                row.appendChild(clientCell);

                const userCell = document.createElement('th');
                userCell.textContent = c.users;
                row.appendChild(userCell);

                const statusCell = document.createElement('th');
                statusCell.textContent = c.status;
                row.appendChild(statusCell);

                const dateCell = document.createElement('th');
                const [dateTransaction, timeTransaction] = c.creation_date.split(' ');
                const [year, month, day] = dateTransaction.split('-');
                const [hour, minute, second] = timeTransaction.split(':');
                dateCell.textContent = `${day}/${month}/${year} ${hour}:${minute}:${second}`;
                row.appendChild(dateCell);

                const dateReturnCell = document.createElement('th');
                const [dateTransaction1, timeTransaction1] = c.date_return.split(' ');
                const [year1, month1, day1] = dateTransaction1.split('-');
                const [hour1, minute1, second1] = timeTransaction1.split(':');
                dateReturnCell.textContent = `${day1}/${month1}/${year1} ${hour1}:${minute1}:${second1}`;
                row.appendChild(dateReturnCell);

                const subTotalCell = document.createElement('th');
                const subtotal = numberFormat(c.total);
                subTotalCell.textContent = subtotal;
                row.appendChild(subTotalCell);

                const discountCell = document.createElement('th');
                const discount = numberFormat(c.discount);
                discountCell.textContent = discount;
                row.appendChild(discountCell);

                const totalCell = document.createElement('th');
                const total_final = numberFormat(c.final_total);
                totalCell.textContent = total_final;
                row.appendChild(totalCell);

                const buttonCell = document.createElement('th');

                if (c.status === 'Faturado') {
                    const faturadoButton = document.createElement('button');
                    faturadoButton.classList.add('btn', 'btn-success', 'btn-sm', 'me-1');
                    faturadoButton.textContent = 'Fatutado';
                    buttonCell.appendChild(faturadoButton);
                } else if (c.status === 'Em Aberto') {
                    const faturarButton = document.createElement('button');
                    faturarButton.classList.add('btn', 'btn-info', 'btn-sm', 'me-1');
                    faturarButton.textContent = 'Mais Detalhes';
                    faturarButton.addEventListener('click', () => ListConditionalItens(c.id, c.client_id, c.user_id));
                    buttonCell.appendChild(faturarButton);
                }

                if (c.status === 'Em Aberto') {
                    const cancelarButton = document.createElement('button');
                    cancelarButton.classList.add('btn', 'btn-danger', 'btn-sm', 'me-1');
                    cancelarButton.textContent = 'Cancelar';
                    cancelarButton.addEventListener('click', () => cancelarCod(c.id));
                    buttonCell.appendChild(cancelarButton);
                } else if (c.status === 'Cancelada') {
                    const CancelButton = document.createElement('button');
                    CancelButton.classList.add('btn', 'btn-danger', 'btn-sm', 'me-1');
                    CancelButton.textContent = 'Cancelada';
                    buttonCell.appendChild(CancelButton);
                }
                row.appendChild(buttonCell);
                ConditionalList.appendChild(row);
            });
        } else {
            showMessage('Erro ao buscar dados: ' + data.message, 'error')
        }
    } catch (error) {
        console.log('Erro ao fazer requisição: ' + error)
    }
}
async function ListConditionalItens(id, client_id, user_id) {
    if (!id) {
        showMessage('Não foi encontrado a condicional', 'warning');
        return;
    }

    try {
        const url = `${BASE_CONTROLLERS}lists.php`;

        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ type: 'listconditionaldetails' }),
        });

        if (!response.ok) {
            showMessage('Erro ao fazer requisição do servidor ' + response.statusText, 'warning');
            return;
        }

        const data = await response.json();

        if (data.success) {
            const filterId = data.result_itens.filter(ci => ci.conditional_id === id);
            localStorage.setItem('conditionalItens', JSON.stringify({ items: filterId, client_id, user_id, id }));

            populateTable(filterId, client_id, user_id, id);
            OpenModalItens();
        }
    } catch (error) {
        showMessage('Erro ao fazer requisição: ' + error, 'error');
    }
}

const cancelarCod = async (id) => {

    if (!id) {
        showMessage('Id da condicional não encontrado', 'warning');
        return;
    }

    let responseCodDelete = {
        id: id,
        type: 'updateconditional'
    }

    continueMessage("Realmente deseja cancelar a condicional?", "Sim", "Não", async function () {

        try {
            let url = `${BASE_CONTROLLERS}edits.php`;

            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(responseCodDelete)
            });

            const responseBody = await response.json();

            if (responseBody.success) {
                showMessage('Condiconal cancelada com sucesso', 'success');
            } else {
                showMessage('Erro ao cancelar condicional ' + responseBody.message, 'error');
            }

        } catch (error) {
            showMessage('Error interno no servidor, contante o suporte ' + error, 'error');
        }

    }, function () {
        showMessage('Operação cancelada', 'warning');
    })
}

function reopenModalIfSaved() {
    const savedData = localStorage.getItem('conditionalItens');

    if (savedData) {
        const parsedData = JSON.parse(savedData);

        const { items, user_id, client_id } = parsedData;

        populateTable(items, user_id, client_id);

        const modal = new bootstrap.Modal(document.getElementById('fullScreenModal'));
        modal.show();
    }
}

function populateTable(items, client_id, user_id, id) {
    const resultItensList = document.getElementById('conditional-itens');
    if (!resultItensList) {
        showMessage('Elemento com o ID "conditional-itens" não foi encontrado no DOM.', 'error');
        return;
    }

    resultItensList.innerHTML = '';

    items.forEach(ci => {

        const row = document.createElement('tr');

        const idCell = document.createElement('th');
        idCell.textContent = ci.id;
        row.appendChild(idCell);

        const productCell = document.createElement('th');
        productCell.textContent = ci.product;
        row.appendChild(productCell);

        const quantityCell = document.createElement('th');
        quantityCell.textContent = ci.quantity;
        row.appendChild(quantityCell);

        const unitPriceCell = document.createElement('th');
        unitPriceCell.textContent = numberFormat(ci.unit_price);
        row.appendChild(unitPriceCell);

        const subtotalCell = document.createElement('th');
        subtotalCell.textContent = numberFormat(ci.subtotal);
        row.appendChild(subtotalCell);

        const actionCell = document.createElement('th');
        const selectButton = document.createElement('button');
        selectButton.textContent = 'Selecionar';
        selectButton.className = 'btn btn-primary btn-sm';
        selectButton.addEventListener('click', () => InvoiceSelect(ci, client_id, user_id, id));
        actionCell.appendChild(selectButton);
        row.appendChild(actionCell);

        resultItensList.appendChild(row);
    });
}

const InvoiceSelect = (item, client_id, user_id, id) => {
    if (!item) {
        showMessage('Erro ao selecionar item', 'warning');
        return;
    }

    const billingItemsList = document.getElementById('billing-items');
    if (!billingItemsList) {
        console.error('Tabela de faturamento não encontrada.');
        return;
    }

    const storedItems = JSON.parse(localStorage.getItem('billingItems')) || [];
    const existingItemIndex = storedItems.findIndex(storedItem => storedItem.id === item.id);

    if (existingItemIndex !== -1) {
        const storedItem = storedItems[existingItemIndex];
        if (storedItem.quantity >= item.quantity) {
            showMessage('Quantidade máxima atingida para este item.', 'warning');
            return;
        }

        storedItem.quantity += 1;
        storedItem.subtotal = storedItem.quantity * item.unit_price;
        storedItems[existingItemIndex] = storedItem;

        const existingRow = Array.from(billingItemsList.children).find(row => {
            return row.querySelector('td:first-child')?.textContent === item.id.toString();
        });

        if (!existingRow) {
            console.error(`Linha para o item com ID ${item.id} não encontrada na tabela.`);
            return;
        }

        const quantityCell = existingRow.querySelector('td:nth-child(3)');
        const subtotalCell = existingRow.querySelector('td:nth-child(5)');

        quantityCell.textContent = storedItem.quantity;
        subtotalCell.textContent = numberFormat(storedItem.subtotal);

        localStorage.setItem('billingItems', JSON.stringify(storedItems));
        showMessage('Quantidade incrementada no faturamento.', 'info');
        Total();
        return;
    }

    const newItem = {
        id: item.id,
        productId: item.product_id,
        product: item.product,
        quantity: 1,
        unit_price: item.unit_price,
        subtotal: item.unit_price,
        client_id: client_id || item.client_id,
        user_id: user_id || item.user_id,
        conditionalId: id
    };
    storedItems.push(newItem);
    localStorage.setItem('billingItems', JSON.stringify(storedItems));

    const row = document.createElement('tr');

    const idCell = document.createElement('td');
    idCell.textContent = newItem.id;
    row.appendChild(idCell);

    const productCell = document.createElement('td');
    productCell.textContent = newItem.product;
    row.appendChild(productCell);

    const quantityCell = document.createElement('td');
    quantityCell.textContent = newItem.quantity;
    row.appendChild(quantityCell);

    const unitPriceCell = document.createElement('td');
    unitPriceCell.textContent = numberFormat(newItem.unit_price);
    row.appendChild(unitPriceCell);

    const subtotalCell = document.createElement('td');
    subtotalCell.classList = 'total-final-invoice';
    subtotalCell.textContent = numberFormat(newItem.subtotal);
    row.appendChild(subtotalCell);

    billingItemsList.appendChild(row);
    showMessage('Item adicionado ao faturamento.', 'success');
    Total();
};
function restoreBillingItems() {
    const billingItemsList = document.getElementById('billing-items');
    if (!billingItemsList) {
        console.error('Tabela de faturamento não encontrada.');
        return;
    }

    const storedItems = JSON.parse(localStorage.getItem('billingItems')) || [];

    storedItems.forEach(item => {
        const row = document.createElement('tr');

        const idCell = document.createElement('td');
        idCell.textContent = item.id;
        row.appendChild(idCell);

        const productCell = document.createElement('td');
        productCell.textContent = item.product;
        row.appendChild(productCell);

        const quantityCell = document.createElement('td');
        quantityCell.textContent = item.quantity;
        row.appendChild(quantityCell);

        const unitPriceCell = document.createElement('td');
        unitPriceCell.textContent = numberFormat(item.unit_price);
        row.appendChild(unitPriceCell);

        const subtotalCell = document.createElement('td');
        subtotalCell.textContent = numberFormat(item.subtotal);
        subtotalCell.classList = 'total-final-invoice';
        row.appendChild(subtotalCell);

        billingItemsList.appendChild(row);

        Total();
        SelectedFat.push(item);
    });
}
const Total = () => {
    const rows = document.querySelectorAll('#billing-items tr');
    let total = 0;

    rows.forEach(row => {
        const finalTotal = row.querySelector('.total-final-invoice');
        if (finalTotal) {
            const totalCell = parseFloat(finalTotal.textContent.replace(',', '.'));
            if (!isNaN(totalCell)) {
                total += totalCell;
            }
        }
    });

    document.getElementById('total-value').textContent = total.toFixed(2).replace('.', ',');
};

const loadPaymentOptions = async () => {
    try {
        const response = await fetch(`${BASE_CONTROLLERS}lists.php`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ type: 'listpaymentmethods' })
        });

        if (!response.ok) {
            showMessage('Erro ao carregar formas de pagamento.', 'error');
            return;
        }

        const data = await response.json();
        if (data.success) {
            const paymentOptions = data.result_form;
            renderPaymentOptions(paymentOptions);
        } else {
            showMessage('Nenhuma forma de pagamento encontrada.', 'warning');
        }
    } catch (error) {
        showMessage('Erro ao buscar formas de pagamento: ' + error.message, 'error');
    }
};

const renderPaymentOptions = (options) => {
    const container = document.getElementById('payment-options');
    container.innerHTML = '';

    options.forEach(option => {
        const paymentRow = document.createElement('div');
        paymentRow.className = 'row align-items-center mb-2';

        paymentRow.innerHTML = `
        <div class="col-6">
            <label class="form-check-label">${option.name}</label>
        </div>
        <div class="col-6">
            <input type="number" class="form-control payment-input" data-id="${option.id}" placeholder="Valor (R$)" />
        </div>
        `;

        container.appendChild(paymentRow);
    });
    document.querySelectorAll('.payment-input').forEach(input => {
        input.addEventListener('input', updateSelectedTotal);
    });
};

const updateSelectedTotal = () => {
    let total = 0;
    document.querySelectorAll('.payment-input').forEach(input => {
        const value = parseFloat(input.value) || 0;
        total += value;
    });

    document.getElementById('selected-total').textContent = `R$ ${total.toFixed(2)}`;
};

document.getElementById('confirmPayment').addEventListener('click', () => {
    const totalValue = parseFloat(document.getElementById('total-value').textContent.replace(',', '.'));
    const selectedPayments = Array.from(document.querySelectorAll('.payment-input'))
        .filter(input => parseFloat(input.value) > 0)
        .map(input => ({
            id: input.dataset.id,
            amount: parseFloat(input.value)
        }));

    const selectedTotal = selectedPayments.reduce((sum, payment) => sum + payment.amount, 0);

    if (selectedTotal !== totalValue) {
        showMessage('O total das formas de pagamento deve ser igual ao valor total da fatura.', 'warning');
        return;
    }

    processPayment(selectedPayments, SelectedFat);
});

const processPayment = async (payments, SelectedFat) => {
    const totalValue = parseFloat(document.getElementById('total-value').textContent.replace(',', '.'));

    let responseFatCond = {
        payments: payments,
        SelectedFat: SelectedFat,
        totalValue: totalValue,
        type: 'registerfatconditional'
    }

    if (SelectedFat.length === null) {
        showMessage('Nenhum item inserido!', 'warning');
        return;
    }

    continueMessage("Deseja realmente faturar essa condicional?", "Sim", "Não", async function () {
        try {

            let url = `${BASE_CONTROLLERS}registers.php`;

            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(responseFatCond)
            })

            if (!response.ok) {
                showMessage('Erro ao enviar dados, contate o suporte', 'error');
                return;
            }

            const responseBody = await response.json();

            if (responseBody && responseBody.success) {
                showMessage('Condicional faturada com sucesso ', 'success');

                setTimeout( () => {
                    location.reload();
                    RemoveModalItens();
                }, 3000)

            } else {
                showMessage('Erro ao faturar a condicinal ' + responseBody.message, 'error');
            }

        } catch (error) {
            showMessage('Erro ao fazer requisição: ' + error.message, 'error')
        }

    }, function () {
        showMessage('Operação cancelada', 'warning');
    })
};

const FatConditional = () => {
    const totalValue = parseFloat(document.getElementById('total-value').textContent.replace(',', '.'));
    if (totalValue <= 0) {
        showMessage('Não há itens para faturar.', 'warning');
        return;
    }
    loadPaymentOptions();
    const paymentModal = new bootstrap.Modal(document.getElementById('paymentModal'));
    paymentModal.show();
};

function OpenModalItens() {
    const modal = new bootstrap.Modal(document.getElementById('fullScreenModal'));
    modal.show();

    localStorage.setItem('fullScreenModal', 'true');
}
function RemoveModalItens() {
    const modalElement = document.getElementById('fullScreenModal');
    const modalInstance = bootstrap.Modal.getInstance(modalElement);
    modalInstance.hide();

    localStorage.removeItem('fullScreenModal');
    localStorage.removeItem('conditionalItens');
    localStorage.removeItem('billingItems');
}

async function ListForn() {
    try {
        let url = `${BASE_CONTROLLERS}lists.php`;

        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ type: 'listforn' })
        });

        const data = await response.json();

        if (data.success) {
            const forn = data.forn;
            const fornList = document.getElementById('forn-list');

            fornList.innerHTML = '';

            forn.forEach(f => {
                const row = document.createElement('tr');

                const selectCell = document.createElement('th');
                const checkbox = document.createElement('input');
                checkbox.type = 'checkbox';
                checkbox.className = 'form-check-input mb-3';
                checkbox.value = f.id;
                checkbox.dataset.id = f.company;
                selectCell.appendChild(checkbox);
                row.appendChild(selectCell);

                const idCell = document.createElement('th');
                idCell.textContent = f.id;
                row.appendChild(idCell);

                const nameCell = document.createElement('th');
                nameCell.textContent = f.company;
                row.appendChild(nameCell);

                fornList.appendChild(row);
            });
        }
    } catch (error) {
        console.log('Erro ao fazer requisição: ' + error.message)
    }
}
async function ListBuyRequest(searchTerm = '') {

    let formData = {
        type: 'listbuyrequest',
        searchTerm: searchTerm
    }

    try {
        let url = `${BASE_CONTROLLERS}lists.php`;

        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(formData)
        });

        const data = await response.json();

        if (data.success) {
            const buyrequest = data.buyrequest;
            const BuyrequestList = document.getElementById('table-buy-request').querySelector('tbody');

            BuyrequestList.innerHTML = '';

            if (!Array.isArray(buyrequest)) {
                throw new Error('buyrequest não é um array');
            }

            buyrequest.forEach(b => {
                const row = document.createElement('tr');

                const idCell = document.createElement('th');
                idCell.textContent = b.id;
                row.appendChild(idCell);

                const ProductCell = document.createElement('th');
                ProductCell.textContent = b.product;
                row.appendChild(ProductCell);

                const CompanyCell = document.createElement('th');
                CompanyCell.textContent = b.company;
                row.appendChild(CompanyCell);

                const QuantityCell = document.createElement('th');
                QuantityCell.textContent = b.quantity;
                row.appendChild(QuantityCell);

                const MessageCell = document.createElement('th');
                MessageCell.textContent = b.message;
                row.appendChild(MessageCell);

                const DateRequestCell = document.createElement('th');
                DateRequestCell.textContent = b.date_request;
                row.appendChild(DateRequestCell);

                BuyrequestList.appendChild(row);
            });
        } else {
            showMessage('Erro ao listar solicitações', 'error');
        }
    } catch (error) {
        console.log('Erro ao fazer requisição: ' + error.message);
    }
}
async function ListVariationValues(searchTermVariation = '') {

    let formDataVariation = {
        type: 'listvariationvalues',
        searchTermVariation: searchTermVariation
    }

    try {

        let url = `${BASE_CONTROLLERS}lists.php`;

        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(formDataVariation)
        });

        if (!response.ok) {
            throw new Error('Erro na resposta da rede');
        }

        const data = await response.json();

        if (data.success) {
            const variationValues = data.variationvalues;
            const variationValuesList = document.getElementById('table-variation-values').querySelector('tbody');

            variationValuesList.innerHTML = '';

            if (!Array.isArray(variationValues)) {
                throw new Error('variationvalues não é um array');
            }

            variationValues.forEach(v => {
                const row = document.createElement('tr');

                const idCell = document.createElement('th');
                idCell.textContent = v.id;
                idCell.className = 'id-variation-values';
                row.appendChild(idCell);

                const ProductCell = document.createElement('th');
                ProductCell.textContent = v.product;
                row.appendChild(ProductCell);

                const CompanyCell = document.createElement('th');
                CompanyCell.textContent = v.company;
                row.appendChild(CompanyCell);

                const QuantityCell = document.createElement('th');
                QuantityCell.textContent = v.quantity;
                row.appendChild(QuantityCell);

                const inputCell = document.createElement('th');
                const inputValues = document.createElement('input');
                inputValues.type = 'number';
                inputValues.className = 'form-control border-dark input-variation-values';
                inputCell.addEventListener('change', AddVariationValues)
                inputCell.appendChild(inputValues);
                row.appendChild(inputCell);

                variationValuesList.appendChild(row);
            });
        } else {
            showMessage('Erro ao listar solicitações', 'error');
        }

    } catch (error) {
        console.log('Erro ao fazer requisição: ' + error.message);
    }

}
async function AddVariationValues() {
    const rowns = document.querySelectorAll('#table-variation-values tbody tr');
    const storedVariations = JSON.parse(localStorage.getItem('tableVariations')) || [];

    rowns.forEach(r => {
        const idCell = r.querySelector('.id-variation-values');
        const valueInput = r.querySelector('.input-variation-values');

        const idBuyRequest = idCell.textContent.trim();
        const value = valueInput.value;

        if (value && !AddVariation[idBuyRequest]) {
            AddVariation[idBuyRequest] = value;
        }
    })

    const formattedAddVariation = Object.keys(AddVariation).map(idBuyRequest => ({
        idBuyRequest: idBuyRequest,
        value: AddVariation[idBuyRequest]
    }));

    storedVariations.push(...formattedAddVariation);
    saveValuesToLocalStorage(storedVariations);

    let ResponseVariation = {
        type: 'variation',
        AddVariation: formattedAddVariation
    }

    try {

        let url = `${BASE_CONTROLLERS}registers.php`;

        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(ResponseVariation)
        })

        const text = await response.text();
        const data = JSON.parse(text);

        if (data.success) {
            showMessage('Valores salvos com sucesso!', 'success');
            updateTableWithNewValues(data.new_values_variation);
        } else {
            showMessage('Erro ao salvar valores: ', 'error');
        }
    } catch (error) {
        console.log('Erro ao fazer requisição: ' + error.message);
    }
}
function saveValuesToLocalStorage(variations) {
    localStorage.setItem('tableVariations', JSON.stringify(variations));
}
function loadValuesFromLocalStorage() {
    const storedVariations = JSON.parse(localStorage.getItem('tableVariations')) || [];
    if (storedVariations.length > 0) {
        updateTableWithNewValues(storedVariations);
    }
}
function updateTableWithNewValues(new_values_variation) {
    setTimeout(() => {
        new_values_variation.forEach(variation => {
            const row = Array.from(document.querySelectorAll('#table-variation-values tbody tr'))
                .find(row => row.querySelector('.id-variation-values').textContent.trim() === variation.idBuyRequest);

            if (row) {
                const inputCell = row.querySelector('.input-variation-values');
                if (inputCell) {
                    const valueSpan = document.createElement('span');
                    valueSpan.className = 'input-variation-values';
                    valueSpan.textContent = variation.value;
                    inputCell.parentNode.replaceChild(valueSpan, inputCell);
                }
            }
        });
    }, 100);
}
async function ListProducts() {
    try {
        let url = `${BASE_CONTROLLERS}lists.php`;

        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ type: 'listproduct' })
        });

        const data = await response.json();

        if (data.success) {
            const products = data.products;

            if (!Array.isArray(products)) {
                throw new Error('Products is not an array');
            }

            const productList = document.getElementById('table-product').querySelector('tbody');

            productList.innerHTML = '';

            products.forEach(product => {
                const row = document.createElement('tr');

                const idCell = document.createElement('th');
                idCell.textContent = product.id;
                row.appendChild(idCell);

                const nameCell = document.createElement('th');
                nameCell.textContent = product.name;
                row.appendChild(nameCell);

                const stockCell = document.createElement('th');
                stockCell.textContent = product.stock_quantity;
                row.appendChild(stockCell);

                const valueCell = document.createElement('th');
                const value = parseFloat(product.value_product);
                valueCell.textContent = numberFormat(value);
                row.appendChild(valueCell);

                const statusCell = document.createElement('th');
                statusCell.textContent = product.status_product;
                row.appendChild(statusCell);

                const buttonCell = document.createElement('th');
                buttonCell.style.justifyContent = 'center';

                const buttonRequest = document.createElement('button');
                buttonRequest.className = 'btn btn-success';
                buttonRequest.textContent = 'Solicitar';
                buttonRequest.onclick = () => handleSolicitarClick(product);
                buttonCell.appendChild(buttonRequest);

                row.appendChild(buttonCell);
                productList.appendChild(row);
            });
        } else {
            showMessage('Erro ao listar produtos', 'error');
        }

    } catch (error) {
        console.log('Erro ao fazer requisição: ' + error.message);
    }
}
async function calculateTotalsListAPrazo() {

    let sumresponse = {
        type: 'sumcontrolfinancial'
    }

    try {

        let url = `${BASE_CONTROLLERS}lists.php`;

        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(sumresponse)
        });

        if (!response.ok) {
            throw new Error('Erro na resposta da rede');
        }

        const data = await response.json();

        if (data.success) {
            const result_payable = data.result_payable;
            const sum_payable = document.getElementById('totalContasAPagar');

            const result_control = data.result_control;
            const sum_control = document.getElementById('totalContasPagas');

            const result_aprazo = data.result_aprazo;
            const sum_aprazo = document.getElementById('totalContasVencidas');

            const total_sal = data.total_sal;
            const sum_saldo = document.getElementById('saldoAtual');

            const result_salesAll = data.result_salesAll;
            const sum_result_salesAll = document.getElementById('totalAllVendas');

            sum_payable.innerHTML = '';
            sum_aprazo.innerHTML = '';
            sum_control.innerHTML = '';
            sum_saldo.innerHTML = '';
            sum_result_salesAll.innerHTML = '';

            total_sal.forEach(ts => {
                const span = document.createElement('span');
                const valor = parseFloat(ts.TotalSaldo);
                span.textContent = numberFormat(valor);
                sum_saldo.appendChild(span);
            });

            result_payable.forEach(rp => {
                const span = document.createElement('span');
                const valor = parseFloat(rp.TotalContasPagar) || 0;
                span.textContent = numberFormat(valor);
                sum_payable.appendChild(span);
            });

            result_aprazo.forEach(ra => {
                const span = document.createElement('span');
                const valor = parseFloat(ra.TotalContasNaoRecebidas) || 0;
                if (valor == null) {
                    span.textContent = 'Todas as contas em dia';
                } else {
                    span.textContent = numberFormat(valor);
                }
                sum_aprazo.appendChild(span);
            });

            result_control.forEach(rc => {
                const span = document.createElement('span');
                const valor = parseFloat(rc.TotalContasReceber) || 0;
                span.textContent = numberFormat(valor);
                sum_control.appendChild(span);
            });

            result_salesAll.forEach(sa => {
                const span = document.createElement('span');
                const valor = parseFloat(sa.TotalTodasVendas) || 0;
                span.textContent = numberFormat(valor);

                sum_result_salesAll.appendChild(span);
            });

        } else {
            showMessage('Não foi possivel trazer valores', 'error');
        }

    } catch (error) {
        console.log('Erro ao fazer requisição!');
    }
}
async function ListAPrazo(searchTermFinancialControl = '') {

    let formFinancialControl = {
        type: 'listFinancialControl',
        searchTermFinancialControl: searchTermFinancialControl
    }

    try {

        let url = `${BASE_CONTROLLERS}lists.php`;

        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(formFinancialControl)
        });

        if (!response.ok) {
            throw new Error('Erro na resposta da rede');
        }

        const data = await response.json();

        if (data.success) {
            const salesData = data.salesData || [];
            const financialControlData = data.financialcontrol || [];
            const allsales = data.AllSales || [];
            const entryData = data.EntryData || [];

            const financialcontrosaleslList = document.getElementById('sales-result');
            const financialControlList = document.getElementById('result-financial-control');
            const EntryDataControlList = document.getElementById('result-entry');
            const allSalesList = document.getElementById('allsales-result');

            allSalesList.innerHTML = '';
            financialcontrosaleslList.innerHTML = '';
            EntryDataControlList.innerHTML = '';
            financialControlList.innerHTML = '';

            salesData.forEach(f => {
                const rowSales = document.createElement('tr');
                rowSales.className = f.status_aprazo === 'em andamento' ? 'table-warning' :
                    f.status_aprazo === 'nenhum pagamento' ? 'table-secondary' :
                        f.status_aprazo === 'paga' ? 'table-success' : '';

                const idCell = document.createElement('td');
                idCell.textContent = f.id || 'N/A';
                rowSales.appendChild(idCell);

                const clientCell = document.createElement('td');
                clientCell.textContent = f.client || 'Sem cliente';
                rowSales.appendChild(clientCell);

                const formPagamentCell = document.createElement('td');
                formPagamentCell.textContent = f.formpagament || 'Sem pagamento';
                rowSales.appendChild(formPagamentCell);

                const portionCell = document.createElement('td');
                portionCell.textContent = f.portion_aprazo || 'Sem parcelas';
                rowSales.appendChild(portionCell);

                const statusCell = document.createElement('td');
                statusCell.textContent = f.status_aprazo || 'Sem status';
                rowSales.appendChild(statusCell);

                const buttonCell = document.createElement('td');
                const inputButton = document.createElement('button');
                inputButton.type = 'button';
                inputButton.className = 'btn btn-info btn-sm';
                inputButton.innerHTML = 'Mais Detalhes';
                inputButton.onclick = function () {
                    ListDetailsAprazo(f.id);
                };
                buttonCell.appendChild(inputButton);
                rowSales.appendChild(buttonCell);

                financialcontrosaleslList.appendChild(rowSales);
            });

            financialControlData.forEach(fc => {
                const rowFinancialControl = document.createElement('tr');
                rowFinancialControl.className = 'table-light';

                const idCell = document.createElement('td');
                idCell.textContent = fc.id || 'N/A';
                rowFinancialControl.appendChild(idCell);

                const descriptionCell = document.createElement('td');
                descriptionCell.textContent = fc.description || 'Sem descrição';
                rowFinancialControl.appendChild(descriptionCell);

                const valueCell = document.createElement('td');
                const value = parseFloat(fc.value) || 0;
                valueCell.textContent = `R$ ${numberFormat(value)}`;
                rowFinancialControl.appendChild(valueCell);

                const dateCell = document.createElement('td');
                if (fc.transaction_date) {
                    const [dateTransaction, timeTransaction] = fc.transaction_date.split(' ');
                    const [year, month, day] = dateTransaction.split('-');
                    const [hour, minute, second] = timeTransaction.split(':');
                    dateCell.textContent = `${day}/${month}/${year} ${hour}:${minute}:${second}`;
                } else {
                    dateCell.textContent = 'Data não disponível';
                }
                rowFinancialControl.appendChild(dateCell);

                const date_settlementCell = document.createElement('td');
                if (fc.date_settlement) {
                    const [datePart, timePart] = fc.date_settlement.split(' ');
                    const [year, month, day] = datePart.split('-');
                    const [hour, minute, second] = timePart.split(':');
                    date_settlementCell.textContent = `${day}/${month}/${year} ${hour}:${minute}:${second}`;
                } else {
                    date_settlementCell.textContent = 'Sem Pagamento';
                }
                rowFinancialControl.appendChild(date_settlementCell);

                const typeCell = document.createElement('td');
                typeCell.textContent = fc.type || 'Sem tipo';
                rowFinancialControl.appendChild(typeCell);

                const actionCell = document.createElement('td');
                if (fc.pay == null && fc.withdrawal == null) {
                    const inputButton = document.createElement('button');
                    inputButton.type = 'button';
                    inputButton.className = 'btn btn-dark btn-sm';
                    inputButton.innerHTML = 'Faturar';
                    inputButton.onclick = function () {
                        InvoiceAccountsPayable(fc.id);
                    };
                    actionCell.appendChild(inputButton);
                } else {
                    actionCell.textContent = fc.pay;
                }
                rowFinancialControl.appendChild(actionCell);

                financialControlList.appendChild(rowFinancialControl);
            });

            entryData.forEach(fb => {
                const rowEntry = document.createElement('tr');
                if (fb.status_aprazo === 'Receita') {
                    rowEntry.className = 'table-success';
                }

                const idCell = document.createElement('td');
                idCell.textContent = fb.id || 'N/A';
                rowEntry.appendChild(idCell);

                const descriptionCell = document.createElement('td');
                descriptionCell.textContent = fb.description || 'Sem descrição';
                rowEntry.appendChild(descriptionCell);

                const valueCell = document.createElement('td');
                const value = parseFloat(fb.value) || 0;
                valueCell.textContent = `R$ ${numberFormat(value)}`;
                rowEntry.appendChild(valueCell);

                const dateCell = document.createElement('td');
                if (fb.transaction_date) {
                    const [year, month, day] = fb.transaction_date.split('-');
                    dateCell.textContent = `${day}/${month}/${year}`;
                } else {
                    dateCell.textContent = 'Data não disponível';
                }
                rowEntry.appendChild(dateCell);

                const typeCell = document.createElement('td');
                typeCell.textContent = fb.type || 'Sem tipo';
                rowEntry.appendChild(typeCell);

                const StatusCell = document.createElement('td');
                typeCell.textContent = fb.status_aprazo || 'Sem Status';
                rowEntry.appendChild(StatusCell);

                EntryDataControlList.appendChild(rowEntry);
            });

            allsales.forEach(fs => {
                const allSalesRow = document.createElement('tr');
                allSalesRow.className = 'table-light';

                const idCell = document.createElement('td');
                idCell.textContent = fs.id || 'N/A';
                allSalesRow.appendChild(idCell);

                const clientCell = document.createElement('td');
                clientCell.textContent = fs.clients || 'Sem cliente';
                allSalesRow.appendChild(clientCell);

                const formPagamentCell = document.createElement('td');
                formPagamentCell.textContent = fs.form_payment || 'Sem pagamento';
                allSalesRow.appendChild(formPagamentCell);

                const totalValueCell = document.createElement('td');
                const value = parseFloat(fs.total_value) || 0;
                totalValueCell.textContent = `R$ ${numberFormat(value)}`;
                allSalesRow.appendChild(totalValueCell);

                const UserCell = document.createElement('td');
                UserCell.textContent = fs.user || 'Sem Usuário';
                allSalesRow.appendChild(UserCell);

                const dateSalesCell = document.createElement('td');
                if (fs.date_sales) {
                    const datePart = fs.date_sales.split(' ')[0].split('/')[0];
                    const [year, month, day] = datePart.split('-');
                    dateSalesCell.textContent = `${day}/${month}/${year}`;
                } else {
                    dateSalesCell.textContent = 'Data não disponível';
                }

                allSalesRow.appendChild(dateSalesCell);

                allSalesList.appendChild(allSalesRow);
            });

        } else {
            showMessage('Erro ao listar solicitações', 'error');
        }

    } catch (error) {
        console.log('Erro ao fazer requisição: ' + error.message);
    }
}
async function ListDetailsAprazo(id_detals) {

    const modal = new bootstrap.Modal(document.getElementById('detailsModal'));

    if (!id_detals) {
        showMessage('ID do pagamento não encontrado', 'warning');
        return;
    }

    let formFinancialControlDetals = {
        type: 'listFinancialControlDetals',
        id_detals: id_detals
    }

    try {
        const url = `${BASE_CONTROLLERS}lists.php`;

        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(formFinancialControlDetals)
        })

        const data = await response.json();

        if (data.success) {
            const financialcontroldetals = data.financialcontroldetals;
            const financialcontrolDetalsList = document.querySelector('.table-financial-control-detals');

            financialcontrolDetalsList.innerHTML = '';

            if (!Array.isArray(financialcontroldetals)) {
                throw new Error('financialcontrol não é um array');
            }

            financialcontroldetals.forEach(fp => {
                const row = document.createElement('tr');

                if (fp.status === 'paga') {
                    row.classList.add('table-success');
                }

                const selectCell = document.createElement('td');
                const checkbox = document.createElement('input');
                checkbox.type = 'checkbox';
                checkbox.className = 'form-check-input';
                checkbox.value = fp.id;
                checkbox.dataset.id = fp.sale_id;
                selectCell.appendChild(checkbox);
                row.appendChild(selectCell);

                const idCell = document.createElement('td');
                idCell.textContent = fp.id;
                row.appendChild(idCell);

                const DateVencimentCell = document.createElement('td');
                const dateString = fp.date_venciment;
                const [year, month, day] = dateString.split('-');
                const formattedDate = `${day}/${month}/${year}`;
                DateVencimentCell.textContent = formattedDate;
                row.appendChild(DateVencimentCell);

                const ValuePagamentCell = document.createElement('td');
                ValuePagamentCell.textContent = fp.value_aprazo;
                row.appendChild(ValuePagamentCell);

                const StatusCell = document.createElement('td');
                StatusCell.textContent = fp.status;
                row.appendChild(StatusCell);

                const TypeCell = document.createElement('td');
                TypeCell.textContent = fp.type;
                row.appendChild(TypeCell);

                financialcontrolDetalsList.appendChild(row);
            });

        } else {
            showMessage('Erro ao listar solicitações', 'error');
        }

    } catch (error) {
        showMessage('Erro ao fazer requisição!' + error, 'error');
    }
    modal.show();
}
async function InvoiceAccountsPayable(id_account) {
    if (!id_account) {
        showMessage('ID do pagamento não encontrado', 'warning');
        return;
    }

    let responseEditAccountsPayable = {
        type: 'editaccountpayable',
        id_account: id_account
    }

    continueMessage("Deseja continuar o faturamento?", "Sim", "Não", async function () {
        try {

            let url = `${BASE_CONTROLLERS}edits.php`;

            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(responseEditAccountsPayable)
            })

            const responseBody = await response.json();

            if (responseBody.success) {
                showMessage("Contas a pagar faturado com sucesso!", 'success');
                location.reload();
            } else {
                showMessage(responseBody.message || "Erro ao tentar faturar contas a pagar ", 'error');
            }

        } catch (error) {
            showMessage('Erro ao fazer requisição' + error, 'error')
        }
    }, function () {
        showMessage('Operação cancelada', 'warning')
    })
}
async function ListInventary() {

    try {

        let url = `${BASE_CONTROLLERS}lists.php`;

        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ type: 'listinventary' })
        });

        const data = await response.json()

        if (data.success) {

            const result_inventary = data.result_inventary;

            if (!Array.isArray(result_inventary)) {
                throw new Error('result_inventary is not an array');
            }

            const inventaryList = document.getElementById('list-inventary').querySelector('tbody');

            inventaryList.innerHTML = '';

            result_inventary.forEach(ri => {
                const row = document.createElement('tr');

                const idCell = document.createElement('th');
                idCell.textContent = ri.id;
                row.appendChild(idCell);

                const UserCell = document.createElement('th');
                UserCell.textContent = ri.user;
                row.appendChild(UserCell);

                const ObsCell = document.createElement('th');
                ObsCell.textContent = ri.observation;
                row.appendChild(ObsCell);

                const statusCell = document.createElement('th');
                statusCell.textContent = ri.status;
                row.appendChild(statusCell);

                const created_atCell = document.createElement('th');
                const dateString = ri.created_at;
                const [year, month, day] = dateString.split('-');
                const formattedDate = `${day}/${month}/${year}`;
                created_atCell.textContent = formattedDate;
                row.appendChild(created_atCell);

                const buttonCell = document.createElement('th');
                buttonCell.style.justifyContent = 'center';

                const buttonRequest = document.createElement('button');
                buttonRequest.className = 'btn btn-info';
                buttonRequest.textContent = 'Mais Detalhes';
                buttonRequest.onclick = () => handleInventaryItensClick(ri.id);
                buttonCell.appendChild(buttonRequest);

                row.appendChild(buttonCell);

                inventaryList.appendChild(row);
            });
        } else {
            showMessage('Erro ao buscar lista de inventario', 'error')
        }

    } catch (error) {
        console.log('Erro ao fazer requisição' + error);
        console.clear();
    }
}
async function handleInventaryItensClick(idInventary) {

    if (!idInventary) {
        showMessage('Código do inventário não encontrado', 'warning');
        return;
    }

    let responseInventaryItens = {
        idInventary: idInventary,
        type: 'inventaryitens'
    }

    try {
        let url = `${BASE_CONTROLLERS}lists.php`;
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(responseInventaryItens)
        });

        const data = await response.json();

        if (data.success) {
            const detailsHtml = `
        <div class="modal-body">
            <div class="mb-4 d-flex justify-content-between">
                <div class="flex-fill me-3">
                    <h6 class="text-primary fw-bold">Observação:</h6>
                    <p class="text-muted">${data.result_itens[0].observation}</p>
                </div>
                <div class="flex-fill me-3">
                    <h6 class="text-primary fw-bold">Status:</h6>
                    <p class="badge rounded-pill bg-${data.result_itens[0].status === 'ativo' ? 'success' : 'danger'} text-uppercase">${data.result_itens[0].status}</p>
                </div>
                <div class="flex-fill">
                    <h6 class="text-primary fw-bold">Criado em:</h6>
                    <p class="text-muted">${new Date(data.result_itens[0].created_at).toLocaleDateString('pt-BR')}</p>
                </div>
            </div>
            <hr class="border-bottom" />
                <div class="mb-4">
                    <h6 class="text-primary fw-bold">Itens do Inventário:</h6>
                    <ul class="list-group">
                        ${data.result_itens.map(item => `
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="my-0 fw-bold">${item.product}</h6>
                                    <small class="text-muted">Quantidade Contada: <strong>${item.counted_quantity}</strong></small>
                                </div>
                                <span class="badge rounded-pill bg-secondary">${item.system_quantity} (Sistema)</span>
                            </li>`).join('')}
                    </ul>
                </div>
        </div>
        `;
            document.getElementById('modalContent').innerHTML = detailsHtml;
            const detailsModal = new bootstrap.Modal(document.getElementById('detailsModal'));
            detailsModal.show();
        } else {
            showMessage('Erro ao buscar detalhes do inventário', 'error');
        }

    } catch (error) {
        showMessage('Erro ao fazer a requisição.' + error, 'error');
    }
}

function filterInventary() {
    const input = document.getElementById('searchInput');
    const filter = input.value.toLowerCase();
    const table = document.getElementById('list-inventary');
    const rows = table.getElementsByTagName('tr');

    for (let i = 1; i < rows.length; i++) {
        const cells = rows[i].getElementsByTagName('th');
        let match = false;
        for (let j = 0; j < cells.length; j++) {
            if (cells[j]) {
                const cellText = cells[j].textContent || cells[j].innerText;
                if (cellText.toLowerCase().includes(filter)) {
                    match = true;
                    break;
                }
            }
        }
        rows[i].style.display = match ? '' : 'none';
    }
}
function formatDate(date) {
    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0'); // Mês começa do 0, por isso somamos 1
    const year = date.getFullYear();
    return `${day}/${month}/${year}`;
}
function numberFormat(value) {
    return value.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}

FieldFormBuyRequest.addEventListener('input', async function () {
    const searchTerm = FieldFormBuyRequest.value.trim();
    await ListBuyRequest(searchTerm);
});
FieldFormVariationValues.addEventListener('input', async function () {
    const searchTermVariation = FieldFormVariationValues.value.trim();
    await ListVariationValues(searchTermVariation);
});
FieldFormFinancialControl.addEventListener('input', async function () {
    const searchTermFinancialControl = FieldFormFinancialControl.value.trim();
    await ListAPrazo(searchTermFinancialControl);
});