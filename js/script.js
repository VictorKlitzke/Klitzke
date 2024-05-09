let OpenModalInvoicing = document.getElementById('modal-invo');
let overlayModalInvoicing = document.getElementById('overlay-invo');
let closeModalInvo = document.getElementById('modal-invo-close');

closeModalInvo.addEventListener("click", function () {
    OpenModalInvoicing.style.display = "none";
    overlayModalInvoicing.style.display = "none";
});

function ModalFaturamento(id_table, date_request, total_request, STATUS_REQUEST) {
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
}
