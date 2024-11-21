const editButtons = document.querySelectorAll(".accessnivel");
const menuMapping = {
  "list-users": "Listar Usuários",
  "register-users": "Registrar Usuários",
  "edit-users": "Editar Usuários",
  "edit-products": "Editar Produtos",
  "list-clients": "Listar Clientes",
  "register-clients": "Registrar Clientes",
  "edit-clients": "Editar Clientes",
  "list-suppliers": "Lista Fornecedores",
  "register-suppliers": "Registrar Fornecedores",
  "edit-suppliers": "Editar Fornecedores",
  "register-sales": "Registrar Vendas",
  "list-sales": "Lista Vendas",
  "register-request": "Registrar Pedido",
  "list-request": "Lista Pedidos",
  "register-table": "Registrar Mesa",
  "register-boxpdv": "Registrar Caixa PDV",
  "list-boxpdv": "Lista Caixa PDV",
  "shopping-request": "Solicitação de Compra",
  "list-purchase-request": "Lista Solicitações de Compra",
  "list-products": "Lista Produtos",
  "register-stockcontrol": "Registrar Controle de Estoque",
  "stock-inventory": "Inventario de Estoque",
  "dashboard": "Painel de Controle",
  "list-companys": "Lista Empresas",
  "financial-control": "Controle Financeiro",
  "list-inventary": "Lista Inventario",
  "register-portions": "Criar Porção",
  "register-companys": "Registrar Empresa",
  "edit-companys": "Editar Empresa",
  "conditional-itens": "Faturar Condicional"
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
      idCell.textContent = product.product_id;
      row.appendChild(idCell);

      const nameCell = document.createElement('td');
      nameCell.textContent = product.product_name;
      row.appendChild(nameCell);

      const stockEntryCell = document.createElement('td');
      stockEntryCell.textContent = product.total_entry;
      row.appendChild(stockEntryCell);

      const stockExitCell = document.createElement('td');
      stockExitCell.textContent = product.total_exit;
      row.appendChild(stockExitCell);

      const valueCell = document.createElement('td');
      valueCell.textContent = product.stock_difference;
      row.appendChild(valueCell);

      const actionsCell = document.createElement('td');
      actionsCell.innerHTML = `
        <button class="btn btn-warning btn-sm" onclick="SelectedProduct('${product.product_id}', '${product.product_name}', ${product.stock_difference})">Selecionar</button>
      `;
      row.appendChild(actionsCell);

      productList.appendChild(row);
    });
  } catch (error) {
    console.log('Erro na requisição: ' + error);
  }
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
    console.log(dataresponseNoticeBoard)
    const query_warnings = dataresponseNoticeBoard.query_warnings;

    if (!query_warnings || query_warnings.length === 0) {
      showMessage('Nenhum aviso encontrado.', 'warning');
      return;
    }
    
    console.log(query_warnings)

    const noticeBoardContainer = document.getElementById('notice-board');
    noticeBoardContainer.innerHTML = ''; 

    const today = new Date();
    const limiteDataVencimento = new Date(today);
    limiteDataVencimento.setDate(today.getDate() + 5);

    query_warnings.forEach(warning => {
      const [datePart, timePart] = warning.transaction_date.split(' ');
      const [year, month, day] = datePart.split('-');
      const [hour, minute, second] = timePart.split(':');
      const dateVenciment = new Date(`${year}-${month}-${day}T${hour}:${minute}:${second}`);

      if (isNaN(dateVenciment)) {
        showMessage('Data inválida: ' + warning.transaction_date, 'warning');
        return;
      }

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
      } else if (dateVenciment <= limiteDataVencimento) {
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
      dataCell.innerHTML = `${day}/${month}/${year} ${hour}:${minute}:${second}`;

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
      <div class="card-body d-flex flex-column align-items-center">
        <h5 class="card-title">${menuName}</h5>
        <div class="form-check mb-3">
          <input type="checkbox" class="form-check-input" id="check-${access.menu}" ${access.released === "1" ? 'checked' : ''} disabled>
          <label class="form-check-label" for="check-${access.menu}"></label>
        </div>
        <p class="card-text">Descrição do menu: ${menuName}</p>
      </div>
      <button onclick="DeleteMenuAccess('${OriginalMenuUser}', ${UserIDMenu})" class="btn btn-danger mt-2">
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
  console.log(userMenus.menu);
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
                <div class="form-check mb-3">
                  <input type="checkbox" class="form-check-input" id="check-${menuKey}"> 
                </div>
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

  console.log(UserIDMenu);
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

      const responseBody = await response.text();
      console.log(responseBody);

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
function searchProducts() {
  const input = document.getElementById('searchInput');
  const filter = input.value.toLowerCase();
  const table = document.getElementById('productTable');
  const rows = table.getElementsByTagName('tr');

  for (let i = 0; i < rows.length; i++) {
    const cells = rows[i].getElementsByTagName('td');
    let match = false;

    for (let j = 0; j < cells.length; j++) {
      if (cells[j]) {
        const cellValue = cells[j].textContent || cells[j].innerText;
        if (cellValue.toLowerCase().indexOf(filter) > -1) {
          match = true;
          break;
        }
      }
    }
    rows[i].style.display = match ? "" : "none";
  }
}
function setBoxID(button) {
  const boxId = button.getAttribute('data-id');
  document.getElementById('boxId').value = boxId;
}
const getFieldReopen = () => {
  return {
    values: {
      reason: document.getElementById('reopenReason').value,
      boxId: document.getElementById('boxId').value
    },
    type: {
      type: 'submitReaopenBoxPdv',
    }
  }
}
async function submitReopenReason() {
  const { values, type } = await getFieldReopen();

  if (values.reason == "") {
    showMessage("Campo vazio, por favor preencha", "warning");
    return;
  }

  let responseReopenBox = {
    boxId: values.boxId,
    reason: values.reason,
    type: type.type
  }

  console.log(responseReopenBox);

  continueMessage("Deseja realmente reabrir o caixa?", "Sim", "Não", async function () {
    try {
      let url = `${BASE_CONTROLLERS}registers.php`;

      const response = await fetch(url, {
        method: "POST",
        headers: {
          "Content-Type": "application/"
        },
        body: JSON.stringify(responseReopenBox)
      })

      const responseBody = await response.json();

      if (responseBody.success) {
        showMessage("Caixa aberto novamente", "success");
      } else {
        showMessage("Erro ao reabrir o caixa: " + responseBody.message, "error");
      }


    } catch (error) {
      console.error("Erro ao fazer requisição" + error.message)
    }
  }, function () {
    showMessage("Operação cancelada", "warning");
  })
}
function searchListProduct() {
  // Obtém o valor do campo de pesquisa
  let searchValue = document.getElementById("searchProduct").value.toLowerCase();

  // Obtém todas as linhas da tabela
  let tableRows = document.querySelectorAll("table tbody tr");

  // Percorre todas as linhas e esconde as que não correspondem à pesquisa
  tableRows.forEach(row => {
      let productName = row.querySelector("th:nth-child(2)").textContent.toLowerCase();
      
      // Verifica se o nome do produto contém o valor da busca
      if (productName.includes(searchValue)) {
          row.style.display = "";  // Exibe a linha
      } else {
          row.style.display = "none";  // Oculta a linha
      }
  });
}
