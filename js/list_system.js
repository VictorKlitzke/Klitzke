const ButtonSearchBuyRequest = document.getElementById('button-search');
const FieldFormBuyRequest = document.getElementById('input-buy-request');
const FieldFormVariationValues = document.getElementById('input-variation-values');
const FieldFormFinancialControl = document.getElementById('input-financial-control');
const AddVariationForn = document.getElementById('add-variation-forn');

let AddVariation = {};

window.onload = ListProducts();
window.onload = ListForn();
window.onload = ListBuyRequest();
window.onload = ListVariationValues();
window.onload = loadValuesFromLocalStorage;
window.omload = AddVariationValues();
window.onload = ListAPrazo();

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

    const id_users_inativar = button.getAttribute('data-id')

    if (!id_users_inativar) {
        showMessage('Usuário não foi encontrado!', 'warning');
    }

    const continueInativar = confirm("Deseja continuar com a instivação do usuário?")

    if (continueInativar) {
        try {

            const url = `${BASE_URL}disable.php`;

            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ id_users_inativar: id_users_inativar })
            })

            const responseBody = await response.json();

            if (responseBody.success) {
                window.location.reload();
                showMessage('Usuário com ID ' + id_users_inativar + ' inativado com sucesso!', 'success');
            } else {
                showMessage('Erro ao inativar usuários' + responseBody.message, 'error');
            }

        } catch (error) {
            showMessage('Erro ao fazer requisição!' + error, 'error');
        }
    }
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
                checkbox.className = 'form-check-input';
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
        console.clear();
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
        console.clear();
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
                inputValues.className = 'form-control input-variation-values';
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
        console.clear();
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
        console.clear();
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
                valueCell.textContent = product.value_product;
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
        console.clear();
    }
}

function calculateTotals(financialControlData) {
    let totalReceitas = 0;
    let totalDespesas = 0;
    let saldoAtual = 0;

    financialControlData.forEach(f => {
        if (f.pay === null) {
            totalDespesas += parseFloat(f.value);
        } else if (f.pay === 'paga') {
            saldoAtual += parseFloat(f.value);
        } 
        if (f.status_aprazo === 'paga' && f.type === 'Receita') {
            totalReceitas += parseFloat(f.value_aprazo);
            console.log(f.value);
        }
    });
    // saldoAtual = totalReceitas - totalDespesas;
    document.getElementById('saldoAtual').textContent = `R$ ${saldoAtual.toFixed(2)}`;
    document.getElementById('receitasMes').textContent = `R$ ${totalReceitas.toFixed(2)}`;
    document.getElementById('despesasMes').textContent = `R$ ${totalDespesas.toFixed(2)}`;
    document.getElementById('resultadoMes').textContent = `R$ ${(totalReceitas - totalDespesas).toFixed(2)}`;
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
            const financialcontrosaleslList = document.getElementById('table-sales').querySelector('tbody');
            const financialcontrolList = document.getElementById('table-financial-control').querySelector('tbody');

            financialcontrolList.innerHTML = '';
            financialcontrosaleslList.innerHTML = '';

            salesData.forEach(f => {
                const row = document.createElement('tr');

                if (f.status_aprazo === 'em andamento') {
                    row.className = 'table-warning';
                } else if (f.status_aprazo === 'nenhum pagamento') {
                    row.className = 'table-secondary';
                } else if (f.status_aprazo === 'paga') {
                    row.className = 'table-success';
                }

                const idCell = document.createElement('th');
                idCell.textContent = f.id;
                idCell.className = 'id-financial-control-values';
                row.appendChild(idCell);

                const clientCell = document.createElement('th');
                clientCell.textContent = f.client;
                row.appendChild(clientCell);

                const formPagamentCell = document.createElement('th');
                formPagamentCell.textContent = f.formpagament;
                row.appendChild(formPagamentCell);

                const portionCell = document.createElement('th');
                portionCell.textContent = f.portion_aprazo;
                row.appendChild(portionCell);

                const statusCell = document.createElement('th');
                statusCell.textContent = f.status_aprazo;
                row.appendChild(statusCell);

                const buttonCell = document.createElement('th');
                const inputButton = document.createElement('button');
                inputButton.type = 'button';
                inputButton.className = 'btn btn-info btn-sm';
                inputButton.innerHTML = 'Mais Detalhes';
                inputButton.onclick = function () {
                    ListDetailsAprazo(f.id);
                };
                buttonCell.appendChild(inputButton);
                row.appendChild(buttonCell);

                financialcontrosaleslList.appendChild(row);
            });

            financialControlData.forEach(f => {
                const row = document.createElement('tr');

                row.className = 'table-light';

                const idCell = document.createElement('th');
                idCell.textContent = f.id;
                row.appendChild(idCell);

                const descriptionCell = document.createElement('th');
                descriptionCell.textContent = f.description || 'Sem descrição';
                row.appendChild(descriptionCell);

                const valueCell = document.createElement('th');
                valueCell.textContent = f.value || 'Sem valor';
                row.appendChild(valueCell);

                const dateCell = document.createElement('td');
                const dateString = f.transaction_date;
                const [year, month, day] = dateString.split('-');
                const formattedDate = `${day}/${month}/${year}`;
                dateCell.textContent = formattedDate;
                row.appendChild(dateCell);

                const typeCell = document.createElement('th');
                typeCell.textContent = f.type || 'Sem tipo';
                row.appendChild(typeCell);

                if (f.pay == null) {
                    const buttonCell = document.createElement('th');
                    const inputButton = document.createElement('button');
                    inputButton.type = 'button';
                    inputButton.className = 'btn btn-dark btn-sm';
                    inputButton.innerHTML = 'Faturar';
                    inputButton.onclick = function () {
                        InvoiceAccountsPayable(f.id);
                    };
                    buttonCell.appendChild(inputButton);
                    row.appendChild(buttonCell);

                } else {
                    const PayCell = document.createElement('th');
                    PayCell.textContent = f.pay;
                    row.appendChild(PayCell);

                }
                financialcontrolList.appendChild(row);
            });

            calculateTotals(financialControlData)

        } else {
            showMessage('Erro ao listar solicitações', 'error');
        }

    } catch (error) {
        console.log('Erro ao fazer requisição: ' + error.message);
    }
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

    console.log(responseEditAccountsPayable);

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
            const financialcontrolDetalsList = document.getElementById('table-financial-control-detals').querySelector('tbody');

            financialcontrolDetalsList.innerHTML = '';

            if (!Array.isArray(financialcontroldetals)) {
                throw new Error('financialcontrol não é um array');
            }

            financialcontroldetals.forEach(fp => {
                const row = document.createElement('tr');

                if (fp.status === 'paga') {
                    row.className = 'table-success';
                }

                const selectCell = document.createElement('th');
                const checkbox = document.createElement('input');
                checkbox.type = 'checkbox';
                checkbox.className = 'form-check-input';
                checkbox.value = fp.id;
                checkbox.dataset.id = fp.sale_id;
                selectCell.appendChild(checkbox);
                row.appendChild(selectCell);

                const idCell = document.createElement('th');
                idCell.textContent = fp.id;
                row.appendChild(idCell);

                const DateVencimentCell = document.createElement('td');
                const dateString = fp.date_venciment;
                const [year, month, day] = dateString.split('-');
                const formattedDate = `${day}/${month}/${year}`;
                DateVencimentCell.textContent = formattedDate;
                row.appendChild(DateVencimentCell);

                const ValuePagamentCell = document.createElement('th');
                ValuePagamentCell.textContent = fp.value_aprazo;
                row.appendChild(ValuePagamentCell);

                const StatusCell = document.createElement('th');
                StatusCell.textContent = fp.status;
                row.appendChild(StatusCell);

                const TypeCell = document.createElement('th');
                TypeCell.textContent = fp.type;
                row.appendChild(TypeCell);

                financialcontrolDetalsList.appendChild(row);
            });

            calculateTotals(financialControlData)

        } else {
            showMessage('Erro ao listar solicitações', 'error');
        }

    } catch (error) {
        showMessage('Erro ao fazer requisição!' + error, 'error');
    }
    modal.show();
}
function ShowModalAddVariation() {
    if (AddVariationForn.style.display === 'block') {
        AddVariationForn.style.display = 'none';
    } else {
        AddVariationForn.style.display = 'block';
    }
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