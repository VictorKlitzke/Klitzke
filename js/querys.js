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

    const responseText = await response.text();

    try {
      const responseClientDetals = JSON.parse(responseText);
      if (responseClientDetals.details_param_clients) {
        showClientDetails(responseClientDetals.details_param_clients);
      } else {
        showMessage('Erro ao consultar valores da aba de vendas', 'warning');
      }

    } catch (error) {
      console.error('Erro ao fazer parse do JSON:', error, responseText);
    }


  } catch (error) {
    console.error('Erro na requisição:', error);
  }

  const modalElement = document.getElementById('details-modal');
  const modal = new bootstrap.Modal(modalElement);
  modal.show();
}
function showClientDetails(salesDetails) {
  const clientDetailsContainer = document.getElementById('client-sales');
  clientDetailsContainer.innerHTML = '';

  const salesMap = {};

  salesDetails.forEach(detail => {
    const saleId = detail.sale_id;

    if (salesMap[saleId]) {
      salesMap[saleId].products.push({
        product: detail.product,
        quantity: detail.quantity,
        value_unit: detail.value_unit
      });
    } else {
      salesMap[saleId] = {
        client: detail.client,
        total_value: detail.total_value,
        form_payment: detail.form_payment,
        products: [{
          product: detail.product,
          quantity: detail.quantity,
          value_unit: detail.value_unit
        }]
      };
    }
  });

  for (const saleId in salesMap) {
    const sale = salesMap[saleId];

    const card = document.createElement('div');
    card.classList.add('col-md-4', 'mb-4');

    let productsHtml = '';
    sale.products.forEach(productDetail => {
      productsHtml += `
        <p class="card-text">Produto: ${productDetail.product}</p>
        <p class="card-text">Quantidade: ${productDetail.quantity}</p>
        <p class="card-text">Valor Unitário: R$ ${parseFloat(productDetail.value_unit).toFixed(2)}</p>
      `;
    });

    card.innerHTML = `
      <div class="card h-100"> 
        <div class="card-header">
          <h5 class="card-title">Codigo da Venda: ${saleId}</h5>
        </div>
        <div class="card-body">
          <p class="card-text">Cliente: ${sale.client}</p>
          ${productsHtml}
          <p class="card-text">Total: R$ ${parseFloat(sale.total_value).toFixed(2)}</p>
          <p class="card-text">Forma de Pagamento: ${sale.form_payment}</p>
        </div>
        <div class="card-footer text-muted">
          Codigo da Venda: ${saleId}
        </div>
      </div>
    `;
    clientDetailsContainer.appendChild(card);
  }
}
async function AccessUsers(button) {
  const userID = button.getAttribute('data-id');

  if (!userID) {
    showMessage('ID do usuário não encontrado', 'warning');
    return;
  }

  let responseUser = {
    type: 'queryuser',
    id_user_menu: userID
  }

  try {

    let url = `${BASE_CONTROLLERS}querys.php`;

    const response = await fetch(url, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(responseUser)
    })

    const responseBodyUser = response.json();

  } catch (error) {
    console.log('Erro na requisição', error);
  }
  const modalElement = document.getElementById('menu-access-user');
  const modal = new bootstrap.Modal(modalElement);
  modal.show();
}
function showAccessMenuUser(user) {
  const modalUser = document.getElementById('menu-access-user');
}