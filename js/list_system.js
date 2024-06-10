const BASE_URL = "http://localhost:3000/Klitzke/ajax/";

async function InativarInvo(button) {

    const id_request_inativar = button.getAttribute('data-id');

    if (!id_request_inativar) {
        window.alert("ID indentificado");
        return;
    }

    const continueInativar = confirm("Desseja realmente inativar pedido?");

    if (continueInativar) {
        try {

            let url = "http://localhost/Klitzke/ajax/inativar_request.php";

            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({id_inativar: id_request_inativar})
            })

            const responseText = await response.text();

            let result;
            try {
                result = JSON.parse(responseText);
            } catch (e) {
                window.alert("Erro inesperado ao processar a inativação do pedido. Entre em contato com o suporte.");
                return;
            }

            if (result.success) {
                window.alert("Pedido inativado com suceesso");
            } else {
                window.alert("Erro ao inativar pedido: " + result.message);
            }

        } catch (error) {
            window.alert(" Erro ao fazer requisiçao, entre em contato com o suporte! " + error);
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
                body: JSON.stringify({id_product: id_product_page})
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
        window.alert("ID da venda nao identificado");
        return;
    }

    const constinueCancel = confirm("Deseja realmente cancelar essa venda?");

    if (constinueCancel) {
       try {
           let url = `${BASE_URL}cancel_sales.php`;

           const response = await fetch(url,{
               method: 'POST',
               headers: {
                   'Content-Type': 'application/json',
               },
               body: JSON.stringify({id_sales_cancel: id_sales_cancel})
           })

           const responseText = await response.text();
           let result;

           try {
               result = JSON.parse(responseText)
           } catch (error) {
               window.alert("Erro interno, entre em contato com o suporte" + error)
           }

           if (result.success) {
               window.alert("Venda cancelada com sucesso!");
               window.location.reload();
           } else {
               window.alert("Erro ao tentar cancelar a venda" + result.getMessage());
           }

       } catch (error) {
           window.alert("Erro interno, entre em contato com o suporte" + error)
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

            const response = await fetch(url,{
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({id_sales_reopen: id_sales_reopen})
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
                body: JSON.stringify({id_print_out: id_print_out})
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
                    printWindow.document.write('<td>' + item.name + '</td>');
                    printWindow.document.write('<td>' + item.amount + '</td>');
                    printWindow.document.write('<td>' + item.price_sales + '</td>');
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
    const overlayDetails = document.getElementById('overlay-details');
    const modalDetails = document.getElementById('modal-print');

    overlayDetails.style.display = 'block';
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

            overlayDetails.style.display = 'block';
            modalDetails.style.display = 'block';
        } else {
            window.alert('Erro ao buscar itens da venda: ' + result.error);
        }

    } catch (error) {
        window.alert("Erro interno ao tentar visualizar os detalhes da venda: " + error);
    }
}

async function CloseModalInfo() {

    const overlayDetails = document.getElementById('overlay-details');
    const modalDetails = document.getElementById('modal-print');

    if ((overlayDetails.style.display === 'block' && modalDetails.style.display === 'block')) {
        overlayDetails.style.display = 'none';
        modalDetails.style.display = 'none';
    }

}

async function DetailsOrder(button) {

    const id_pedido_details = button.getAttribute('data-id');
    const ModalOpenDetails = document.getElementById('modal-print-request');
    const overlayDetailsrequest = document.getElementById('overlay-details-request');

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
            overlayDetailsrequest.style.display = 'block';
        } else {
            window.alert('Erro ao buscar itens da venda: ' + result.error);
        }

    } catch (error) {
        window.alert("Erro interno, entre em contato com o suporte" + error);
    }
}

async function CloseModalInfoRequest() {

    const overlayDetails = document.getElementById('overlay-details-request');
    const modalDetails = document.getElementById('modal-print-request');

    if ((overlayDetails.style.display === 'block' && modalDetails.style.display === 'block')) {
        overlayDetails.style.display = 'none';
        modalDetails.style.display = 'none';
    }

}

