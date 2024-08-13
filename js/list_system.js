window.onload = ListProducts();
window.onload = ListForn();

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
                showMessage(responseBody.message, 'error');
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
            body: JSON.stringify( { type: 'listforn' } )
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
                checkbox.value = f.company;
                checkbox.dataset.id = f.id;
                selectCell.appendChild(checkbox);
                row.appendChild(selectCell);

                const nameCell = document.createElement('th');
                nameCell.textContent = f.company;
                row.appendChild(nameCell);

                fornList.appendChild(row);
            });
        } else {
            showMessage('Erro ao listar fornecedores', 'error');
        }

    } catch (error) {
        showMessage('Erro ao fazer requisição: ' + error.message, 'error');
    }
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
        showMessage('Erro ao fazer requisição: ' + error.message, 'error');
    }
}