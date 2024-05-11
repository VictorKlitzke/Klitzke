const { Button } = require("bootstrap");

let OpenModalInvoicing = document.getElementById('modal-invo');
let overlayModalInvoicing = document.getElementById('overlay-invo');
let closeModalInvo = document.getElementById('modal-invo-close');
let ButtonFatInvo = document.querySelectorAll('.Invo-forms');

let SelectedInvos = [];

closeModalInvo.addEventListener("click", function () {
    OpenModalInvoicing.style.display = "none";
    overlayModalInvoicing.style.display = "none";
});

function ModalFaturamento(id_table, date_request, total_request, STATUS_REQUEST) {
    if (STATUS_REQUEST === "AGRUPADOS") {
        window.alert("Esse pedido esta agrupados");
        return;
    } else {
        OpenModalInvoicing.style.display = 'block';
        overlayModalInvoicing.style.display = 'block';

        const id_table_invo = document.getElementById('id-table');
        const value_request_total = document.getElementById('total-request');
        const date_request_invo = document.getElementById('date-request');
        const status_request_invo = document.getElementById('status-request');

        id_table_invo.textContent = "Comanda: " + id_table;
        value_request_total.innerHTML = "Total pedido " + '<input type="text" id="total_request" value="' + total_request + '" />';
        date_request_invo.textContent = "Data do pedido: " + date_request;
        status_request_invo.textContent = "Status: " + STATUS_REQUEST;

        ButtonFatInvo.forEach(function (button) {
            button.addEventListener("click", function () {
                button.style.background = "rgb(58, 204, 82)";
            })
            button.addEventListener("dblclick", function () {
                button.style.background = "";
            })
        });
    }

    let orderPedidos = {
        id_table: id_table,
        date_request: date_request,
        total_request: total_request,
        status_request: STATUS_REQUEST,
        Button: ButtonFatInvo
    }

    SelectedInvos.push(orderPedidos);
}

async function CloseInvo() {

    let responseInvo = {
        SelectedInvos: orderPedidos,
    }

    console.log(responseInvo);

    try {
        const responseserver = await fetch("", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(responseInvo),
        })

        const response = await responseserver.text();
        console.log('Response from server:', response);
        const responseDataInvo = JSON.parse(response);

        if (responseDataInvo && responseDataInvo.success) {
            window.alert('Pedido finalizada com sucesso!');
            // const saleId = responseData.id;
            // window.location.href = 'pages/proof.php?sale_id=' + saleId;
        } else {
            console.error('Erro ao faturar pedido:', responseDataInvo ? responseDataInvo.error : 'Resposta vazia');
        }
    } catch (error) {
        console.log("Erro ao faturar Pedido" + SelectedInvos.id_table + error);
    }

}
