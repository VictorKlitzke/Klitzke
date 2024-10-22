const editButtons = document.querySelectorAll(".accessnivel");
const menuMapping = {
  "list-users": "Listar Usuários",
  "register-users": "Registrar Usuários",
  "edit-users": "Editar Usuários",
  "edit-products": "Editar Produtos",
  "list-clients": "Listar Clientes",
  "register-clients": "Registrar Clientes",
  "edit-clients": "Editar Clientes",
  "list-suppliers": "Listar Fornecedores",
  "register-suppliers": "Registrar Fornecedores",
  "edit-suppliers": "Editar Fornecedores",
  "register-sales": "Registrar Vendas",
  "list-sales": "Listar Vendas",
  "register-request": "Registrar Pedido",
  "list-request": "Listar Pedidos",
  "register-table": "Registrar Mesa",
  "register-boxpdv": "Registrar Caixa PDV",
  "list-boxpdv": "Listar Caixa PDV",
  "shopping-request": "Solicitação de Compra",
  "list-purchase-request": "Listar Solicitações de Compra",
  "list-products": "Listar Produtos",
  "register-stockcontrol": "Registrar Controle de Estoque",
  "stock-inventory": "Inventario de Estoque",
  "dashboard": "Painel de Controle",
  "list-companys": "Listar Empresas",
  "financial-control": "Controle Financeiro"
};

window.onload = function () {
  NivelAccess();
  NoticeBoard();
  QueryListProducts();
}

async function QueryListProducts() {
  try {
    let url = `${BASE_CONTROLLERS}querys.php`;
    let response = await fetch(url);

    if (!response.ok) {
      showMessage('Erro na requisição: ' + response.statusText, 'warning');
      return;
    }

    const responseListProd = await response.json();

    const productList = document.getElementById("productTable");
    productList.innerHTML = '';

    responseListProd.list_products.forEach(product => {
      const row = document.createElement('tr');

      const idCell = document.createElement('td');
      idCell.textContent = product.id;
      row.appendChild(idCell);

      const nameCell = document.createElement('td');
      nameCell.textContent = product.name;
      row.appendChild(nameCell);

      const stockCell = document.createElement('td');
      stockCell.textContent = product.stock_quantity;
      row.appendChild(stockCell);

      const valueCell = document.createElement('td');
      valueCell.textContent = product.value_product;
      row.appendChild(valueCell);

      const actionsCell = document.createElement('td');
      actionsCell.innerHTML = `
        <button class="btn btn-warning btn-sm" onclick="SelectedProduct('${product.id}', '${product.name}', ${product.stock_quantity}, '${product.value_product}')">Selecionar</button>
      `;
      row.appendChild(actionsCell);

      productList.appendChild(row);
    });
  } catch (error) {
    console.log('Erro na requisição: ' + error);
  }
}

function SelectedProduct(id, name, stock_quantity, value_product) {
  document.getElementById('productID').value = id;
  document.getElementById('productName').value = name;
  document.getElementById('productQuantity').value = stock_quantity;
  document.getElementById('productPrice').value = value_product;
}

function toggleNoticeBoard() {
  let noticeBoard = document.getElementById('notice-board');
  let toggleIcon = document.getElementById('toggle-icon');

  if (noticeBoard.classList.contains('d-none')) {
    noticeBoard.classList.remove('d-none');
    toggleIcon.classList.remove('fa-chevron-down');
    toggleIcon.classList.add('fa-chevron-up');
  } else {
    noticeBoard.classList.add('d-none');
    toggleIcon.classList.remove('fa-chevron-up');
    toggleIcon.classList.add('fa-chevron-down');
  }
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

    const noticeBoardContainer = document.getElementById('notice-board');
    noticeBoardContainer.innerHTML = ''; // Limpa o conteúdo anterior

    const today = new Date();

    query_warnings.forEach(warning => {
      const [year, month, day] = warning.transaction_date.split('-');
      const dateVenciment = new Date(`${year}-${month}-${day}`);

      if (isNaN(dateVenciment)) {
        showMessage('Data inválida: ' + warning.transaction_date, 'warning');
        return;
      }

      let avisoTexto = '';
      let icone = '';
      let classeCor = '';
      let statusTexto = '';

      if (warning.pay === 'paga') {
        icone = '<i class="fas fa-check-circle"></i>';
        classeCor = 'table-success';
        statusTexto = 'Pago';
      } else if (dateVenciment < today) {
        icone = '<i class="fas fa-exclamation-circle"></i>';
        classeCor = 'table-danger';
        statusTexto = 'Vencido';
      } else if (dateVenciment <= new Date(today.setDate(today.getDate() + 5))) {
        icone = '<i class="fas fa-hourglass-half"></i>';
        classeCor = 'table-warning';
        statusTexto = 'A vencer';
      } else {
        return;
      }

      const avisoRow = document.createElement('tr');
      avisoRow.className = `${classeCor} mb-2`;

      const contaCell = document.createElement('td');
      contaCell.innerHTML = `${warning.description || 'Sem descrição'}`;

      const valorCell = document.createElement('td');
      valorCell.innerHTML = `R$ ${warning.value || 'N/A'}`;

      const dataCell = document.createElement('td');
      dataCell.innerHTML = `${day}/${month}/${year}`;

      const statusCell = document.createElement('td');
      statusCell.innerHTML = `${icone} ${statusTexto}`;

      avisoRow.appendChild(contaCell);
      avisoRow.appendChild(valorCell);
      avisoRow.appendChild(dataCell);
      avisoRow.appendChild(statusCell);
      noticeBoardContainer.appendChild(avisoRow);
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
      showMessage('Erro ao fazer parse do JSON:' + error + responseText, 'error');
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

    const responseTextUser = await response.text();

    try {
      const responseBodyUser = JSON.parse(responseTextUser);
      showAccessMenuUser(responseBodyUser.menu_user)
      showAddMenus(responseBodyUser.menu_user)
    } catch (error) {
      showMessage('Erro ao fazer parse do JSON:' + error + responseTextUser, 'error');
    }

  } catch (error) {
    console.log('Erro na requisição', error);
  }
  const modalElement = document.getElementById('menu-access-user');
  const modal = new bootstrap.Modal(modalElement);
  modal.show();
}
function showAccessMenuUser(userMenus) {
  const modalUser = document.getElementById('remover-menus-user');
  modalUser.innerHTML = "";

  userMenus.forEach(access => {
    const OriginalMenuUser = access.menu;
    const menuName = menuMapping[access.menu] || access.menu; // Faz o mapeamento ou exibe o nome original se não existir
    const UserIDMenu = access.user_id;

    const card = document.createElement('div');
    card.classList.add('col-md-4', 'mb-4');

    card.innerHTML = `
      <div class="card h-100 text-center shadow">
        <div class="card-body">
          <h5 class="card-title">${menuName}</h5>
          <input type="checkbox" class="form-check-input" id="check-${access.menu}" ${access.released === "1" ? 'checked' : ''} disabled>
          <p class="card-text">Descrição do menu: ${menuName}</p>
        </div>
        <button onclick="DeleteMenuAccess('${OriginalMenuUser}', ${UserIDMenu})" class="btn btn-danger">
          <i class="fas fa-trash-alt"></i>
        </button>
      </div>
    `;
    modalUser.appendChild(card);
  });
}

function showAddMenus(userMenus) {
  const modalUser = document.getElementById('edit-menus-user');
  modalUser.innerHTML = "";

  const UserIDMenu = userMenus.length > 0 ? userMenus[0].user_id : null;
  const assignedMenus = userMenus.map(menu => menu.menu);
  const unassignedMenus = Object.keys(menuMapping).filter(menuKey => !assignedMenus.includes(menuKey));

  if (unassignedMenus.length === 0) {
    modalUser.innerHTML = "<h1>O usuário já tem acesso a todos os menus.</h1>";
    return;
  }

  unassignedMenus.forEach(menuKey => {
    const card = document.createElement('div');
    const menuName = menuMapping[menuKey];

    card.classList.add('col-md-4', 'mb-4');
    card.innerHTML = `
        <div class="card h-100 text-center shadow">
            <div class="card-body">
                <h5 class="card-title">${menuName}</h5>
                <input type="checkbox" class="form-check-input" id="check-${menuKey}"> 
                <p class="card-text">Descrição do menu: ${menuName}</p>
            </div>
        </div>
    `;
    modalUser.appendChild(card);
  });

  const addButton = document.createElement('button');
  addButton.classList.add('btn', 'btn-primary', 'mt-3');
  addButton.textContent = 'Adicionar Menus';
  addButton.onclick = () => {
    AddMenuAccess(UserIDMenu);
  };

  modalUser.appendChild(addButton);
}
async function AddMenuAccess(UserIDMenu) {
  const modalUser = document.getElementById('edit-menus-user');
  const checkboxes = modalUser.querySelectorAll('input[type="checkbox"]');

  let menusToAdd = [];

  checkboxes.forEach(checkbox => {
    const menuKey = checkbox.id.replace('check-', '');
    if (checkbox.checked) {
      menusToAdd.push(menuKey);
    }
  });

  if (menusToAdd.length === 0) {
    showMessage("Nenhum menu foi selecionado para adicionar.", 'warning');
    return;
  }

  // Aqui, você já está pegando a chave em inglês diretamente do menuKey
  const menusInEnglish = menusToAdd.map(menuKey => {
    const menuInPortuguese = menuMapping[menuKey];
    if (!menuInPortuguese) {
      console.error(`Menu ${menuKey} não encontrado no mapeamento.`);
    }
    return menuKey;
  });

  let responseMenuaccess = {
    type: 'addaccessmenu',
    userID: UserIDMenu,
    menus: menusInEnglish
  };

  console.log(responseMenuaccess);

  continueMessage("Adicionar menus ao usuário?", "Sim", "Não", async function () {
    try {
      let url = `${BASE_CONTROLLERS}registers.php`;

      const response = await fetch(url, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(responseMenuaccess)
      });

      const responseBody = await response.json();

      if (responseBody.success) {
        showMessage("Menus adicionados com sucesso!", 'success');
        setTimeout(() => {
          location.reload();
        }, 2000);
      } else {
        showMessage("Erro ao adicionar menus: " + responseBody.message, 'error');
      }
    } catch (error) {
      showMessage('Erro na requisição: ' + error.message, 'error');
    }
  });
}