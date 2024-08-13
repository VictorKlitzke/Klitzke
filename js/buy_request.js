let selectedProducts = [];
let SendSelectedProduct = [];

const OverlayModal = document.getElementById('overlay-forn');
const goRequest = document.getElementById('go-request');
const selectedProductsList = document.getElementById('selected-products-list');
const SendRequestProduct = document.getElementById('send-request-products');
const ModalForn = document.getElementById('modal-forn');
const SelectedForn = document.getElementById('selected-forn');

async function GoRequest() {
  if (goRequest.style.display === 'none') {
      goRequest.style.display = 'block'
  }
}

function handleSolicitarClick(product) {
  const existingProduct = selectedProducts.find(p => p.name === product.name);
  if (goRequest.style.display === 'block') {
      if (existingProduct) {
          existingProduct.quantity++;
      } else {
          product.quantity = 1;
          selectedProducts.push(product);
      }
      updateSelectedProductsTable();
  } else {
      showMessage('Modal não foi ativado, Clicar no botão Iniciar Solicitação', 'warning');
  }
}

function updateSelectedProductsTable() {

  selectedProductsList.innerHTML = '';

  selectedProducts.forEach(product => {
      const row = document.createElement('tr');
      row.id = `product-row-${product.name}`;

      const nameCell = document.createElement('th');
      nameCell.textContent = product.name;
      row.appendChild(nameCell);

      const quantityCell = document.createElement('th');
      quantityCell.textContent = product.quantity;
      row.appendChild(quantityCell);

      const actionCell = document.createElement('th');

      const buttonRemove = document.createElement('button');
      buttonRemove.className = 'btn btn-danger';
      buttonRemove.textContent = 'Remover';
      buttonRemove.onclick = () => handleRemoveClick(product);
      actionCell.appendChild(buttonRemove);

      row.appendChild(actionCell);
      selectedProductsList.appendChild(row);

      const existingProduct = SendSelectedProduct.find(p => p.name === product.name);
      if (existingProduct) {
          existingProduct.quantity = product.quantity;
      } else {
          SendSelectedProduct.push({
              type: 'RequestPurchase',
              name: product.name,
              quantity: product.quantity,
          });
      }
  });

}

function handleRemoveClick(product) {
  const index = selectedProducts.findIndex(p => p.name === product.name);
  const rowToDelete = document.getElementById(`product-row-${product.name}`);
  const number = product.quantity - 1;

  if (number >= 1) {
      product.quantity = number;
      selectedProducts[index] = product;
  } else if (number <= 0) {
      if (rowToDelete) {
          rowToDelete.remove();
      }
      selectedProducts.splice(index, 1);
  }
  updateSelectedProductsTable();
}

async function Selectedforns() {
  ModalForn.style.display = 'block';
  OverlayModal.style.display = 'block';
}

async function SendRequestWhatsApp() {

  console.log(SelectedForn.textContent);

  if (!SendRequestProduct) {
      showMessage('Erro ao selecionar produtos', 'warning')
  }

  let = responseSend = {
      SendSelectedProduct: SendSelectedProduct
  }

  console.log(responseSend);

  continueMessage("Deseja realmente realizar essa solicitação?", "Sim", "Não", async function() {

      try {

          let url = `${BASE_CONTROLLERS}registers.php`;

                  const response = await fetch(url, {
                      method: 'POST',
                      headers: {
                          'Content-Type': 'application/json'
                      },
                      body: JSON.stringify(responseSend)
                  });

                  const responseBody = await response.json();

                  if (responseBody.success) {
                      showMessage('Solicitação enviada com sucesso para o fornecedor', 'success')
                  } else {
                      showMessage('Erro ao fazer solicitação' || responseBody.message, 'error')
                  }

      } catch (error) {
          showMessage('Erro na requisição' + error, 'error')
      }

  }, function() {
      showMessage('Operação cancelada', 'warning')
  })
}