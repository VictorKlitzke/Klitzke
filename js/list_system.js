const ButtonSearchBuyRequest = document.getElementById('button-search');
const FieldFormBuyRequest = document.getElementById('input-buy-request');
const FieldFormVariationValues = document.getElementById('input-variation-values');
const FieldFormFinancialControl = document.getElementById('input-financial-control');

let AddVariation = {};
let notificationQueue = [];

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

document.addEventListener('DOMContentLoaded', function () {
    ListConditional();
    
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

                    const faturarButton = document.createElement('button');
                    faturarButton.classList.add('btn', 'btn-info', 'btn-sm', 'me-1');
                    faturarButton.textContent = 'Mais Detalhes';
                    faturarButton.addEventListener('click', () => ListConditionalItens(c.id));
                    buttonCell.appendChild(faturarButton);

                    const cancelarButton = document.createElement('button');
                    cancelarButton.classList.add('btn', 'btn-danger', 'btn-sm', 'me-1');
                    cancelarButton.textContent = 'Cancelar';
                    cancelarButton.addEventListener('click', () => cancelar(c.id));
                    buttonCell.appendChild(cancelarButton);

                    const editarButton = document.createElement('button');
                    editarButton.classList.add('btn', 'btn-warning', 'btn-sm');
                    editarButton.textContent = 'Editar';
                    editarButton.addEventListener('click', () => editar(c.id));
                    buttonCell.appendChild(editarButton);

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

    async function ListConditionalItens(id) {
        if (!id) {
            showMessage('Não foi encontrado a condicional', 'warning');
            return;
        }

        try {
            let url = `${BASE_CONTROLLERS}lists.php`;

            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ type: 'listconditionaldetails' })
            });

            if (!response.ok) {
                showMessage('Erro ao fazer requisição do servidor ' + response.statusText, 'warning');
                return;
            }

            const data = await response.json();

            if (data.success) {
                const result_itens = data.result_itens;
                const filterId = result_itens.filter(ci => {
                    return ci.conditional_id === id;
                });

                setTimeout(function () {
                    const resultItensList = document.getElementById('conditional-itens');

                    if (!resultItensList) {
                        console.error('Elemento com o ID "conditional-itens" não foi encontrado no DOM.');
                        return;
                    }

                    resultItensList.innerHTML = '';

                    filterId.forEach(ci => {
                        const row = document.createElement('tr');

                        const idCell = document.createElement('th');
                        idCell.textContent = ci.id;
                        row.appendChild(idCell);

                        const productCell = document.createElement('th');
                        productCell.textContent = ci.product_id;
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

                        resultItensList.appendChild(row);
                    });

                    window.open('http://localhost:3000/Klitzke/conditional-itens');
                }, 500); 
            }

        } catch (error) {
            showMessage('Erro ao fazer requisição: ' + error, 'error');
        }
    }

})


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