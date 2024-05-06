
let OpenModalInvoiving = document.getElementById('modal-invo');
let overlayModalInvoiving = document.getElementById('overlay-invo');

// CloseModalInvo.addEventListener("click", function () {
//     if ((OpenModalInvoiving.style.display = "block")) {
//         OpenModalInvoiving.style.display = "none";
//         overlayModalInvoiving.style.display = "none";
//         OpenModalInvoiving.style.transition = "transform 0.9s";
//     }
// });

async function AddModalinvoicing(id_table, date_request, total_request, STATUS_REQUEST) {

    const btnrequestInvoicing = document.getElementById('btn-list-request-invoicing-' + id_table)
    const value_request_total = document.getElementById('total-request');
    const date_request_invo = document.getElementById('date-request');
    const status_request_invo = document.getElementById('status-request');

    console.log(id_table);
    console.log(OpenModalInvoiving);

    if (OpenModalInvoiving.style.display === 'none') {
        OpenModalInvoiving.style.display = 'block';
        overlayModalInvoiving.style.display = 'block';
    }

}