const OpenModal = document.getElementById('modal-content-details-reports');
const overlayModal = document.getElementById('overlay-details-reports')

async function ReportSales() {


}

async function ModalReports() {

    OpenModal.style.display = 'block';
    overlayModal.style.display = 'block';

}

function closeModalReports() {
    if ((OpenModal.style.display === 'block' &&
        overlayModal.style.display === 'block')) {
            overlayModal.style.display = 'none';
            OpenModal.style.display = 'none';
    }
}