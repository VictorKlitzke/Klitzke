const editButtons = document.querySelectorAll(".accessnivel");

window.onload = function () {
  NivelAccess();
  NoticeBoard();
}
async function NivelAccess() {
  try {
    let response = await fetch(`${BASE_CONTROLLERS}querys.php`);

    if (!response.ok) {
      showMessage('Erro na requisição: ' + response.statusText, 'warning');
      return;
    }

    let data = await response.json();
    const userAccess = data.access;

    editButtons.forEach(button => {
      if (userAccess < 50) {
        button.style.display = 'none';
      }
    });

  } catch (error) {
    console.log('Erro na requisição: ' + error);
  }
}
async function NoticeBoard() {
  try {
    let responseNoticeBoard = await fetch(`${BASE_CONTROLLERS}querys.php`);

    if (!responseNoticeBoard.ok) {
      showMessage('Erro na requisição: ' + responseNoticeBoard.statusText, 'warning');
      return;
    }

    let dataresponseNoticeBoard = await responseNoticeBoard.json();
    const query_warnings = dataresponseNoticeBoard.query_warnings;

    const noticeBoardContainer = document.querySelector('.notice-board');
    noticeBoardContainer.innerHTML = '';

    const today = new Date();

    query_warnings.forEach(warning => {
      const [year, month, day] = warning.transaction_date.split('-');
      const dateVenciment = new Date(`${year}-${month}-${day}`);

      if (isNaN(dateVenciment)) {
        showMessage('Data inválida:' + warning.transaction_date, 'warning');
        return;
      }

      let avisoTexto = '';
      let icone = '';
      let classeCor = '';

      if (warning.pay === 'paga') {
        icone = '<i class="fas fa-check-circle"></i>';
        classeCor = 'text-success';
        avisoTexto = `${icone} <strong>Conta:</strong> ${warning.description || 'Sem descrição'} - <strong>Valor:</strong> <span class="text-bold">R$ ${warning.value || 'N/A'}</span> - <strong>Pago em:</strong> ${day}/${month}/${year}`;
      } else if (dateVenciment < today) {
        icone = '<i class="fas fa-exclamation-circle"></i>';
        classeCor = 'text-danger';
        avisoTexto = `${icone} <strong>Conta:</strong> ${warning.description || 'Sem descrição'} - <strong>Valor:</strong> <span class="text-bold">R$ ${warning.value || 'N/A'}</span> - <strong>Vencida em:</strong> ${day}/${month}/${year}`;
      } else if (dateVenciment <= new Date(today.setDate(today.getDate() + 5))) {
        icone = '<i class="fas fa-hourglass-half"></i>';
        classeCor = 'text-warning';
        avisoTexto = `${icone} <strong>Conta:</strong> ${warning.description || 'Sem descrição'} - <strong>Valor:</strong> <span class="text-bold">R$ ${warning.value || 'N/A'}</span> - <strong>Vencimento em:</strong> ${day}/${month}/${year}`;
      } else {
        return;
      }

      const avisoElement = document.createElement('p');
      avisoElement.className = `${classeCor} mb-2`;
      avisoElement.innerHTML = avisoTexto;

      const separador = document.createElement('hr');
      separador.className = 'my-2';

      noticeBoardContainer.appendChild(avisoElement);
      noticeBoardContainer.appendChild(separador);
    });

  } catch (error) {
    console.log('Erro na requisição: ' + error);
  }
}
function formatDate(dateString) {
  if (!dateString) return 'Data inválida';
  const [year, month, day] = dateString.split('-');
  return `${day}/${month}/${year}`;
}
async function MoreDetailsClient(button) {
  const clientId = button.getAttribute('data-id');

  if (!clientId) {
    showMessage('Código do cliente não foi encontrado', 'warning');
    return;
  }

  let responseDetalis = {
    type: 'detailsclients',
    id_client_detals: clientId
  };

  try {
    let url = `${BASE_CONTROLLERS}querys.php`;

    const response = await fetch(url, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(responseDetalis)
    });

    const responseClientDetals = await response.json();
    if (responseClientDetals.error) {
      console.error('Erro na requisição:', responseClientDetals.error);
      showMessage(responseClientDetals.error, 'error');
      return;
    }
    showClientDetails(responseClientDetals.details_param_clients);
  } catch (error) {
    console.error('Erro na requisição:', error);
  }

  const modalElement = document.getElementById('details-modal');
  const modal = new bootstrap.Modal(modalElement);
  modal.show();
}

function showClientDetails(clientDetails) {
  const clientDetailsContainer = document.getElementById('client-sales');
  clientDetailsContainer.innerHTML = '';

  clientDetails.forEach(detail => {
    const card = document.createElement('div');
    card.classList.add('col-md-4', 'mb-4');
    card.innerHTML = `
          <div class="card"> 
              <div class="card-header">
                  <h5 class="card-title">${detail.product}</h5>
              </div>
              <div class="card-body">
                  <p class="card-text">Cliente: ${detail.client}</p>
                  <p class="card-text">Quantidade: ${detail.quantity}</p>
                  <p class="card-text">Valor Unitário: R$ ${parseFloat(detail.value_unit).toFixed(2)}</p>
                  <p class="card-text">Total: R$ ${parseFloat(detail.total_value).toFixed(2)}</p>
                  <p class="card-text">Forma de Pagamento: ${detail.form_payment}</p>
              </div>
              <div class="card-footer text-muted">
                  ID da Venda: ${detail.sale_id}
              </div>
          </div>
      `;
    clientDetailsContainer.appendChild(card); 
  });
}


