let OpenModalInvoiving = document.getElementById('modal-invo');

async function AddModalinvoicing(index, id_table, value_request, status_request, date_request) {

    window.alert("Cliquei");

    const btnrequestInvoicing = document.getElementById('btn-list-request-invoicing-' + index)
    const value_request_total = document.getElementById('total-request');
    const date_request_invo = document.getElementById('date-request');
    const status_request_invo = document.getElementById('status-request');

    console.log(id_table, value_request, status_request, date_request);

    if ((OpenModalInvoiving.style.display = 'none')) {
        OpenModalInvoiving.style.display = 'block';
    }

}