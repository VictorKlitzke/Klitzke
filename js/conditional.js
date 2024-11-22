let SelectedProducts = [];

window.onload = function () {
  getProduct();
  getClients();
  getUsers();
};

const getUsers = async () => {
  try {

    let url = `${BASE_CONTROLLERS}searchs.php`;

    const response = await fetch(url, {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: new URLSearchParams({ users_list: "true" }),
    })

    if (!response.ok) {
      showMessage('Erro ao buscar vendedor', 'error');
      return;
    }

    const users = await response.json();

    if (users) {
      const usersElement = document.getElementById('users');
      users.forEach(({ id, name }) => {
        const option = document.createElement("option");
        option.value = JSON.stringify(id, name)
        option.textContent = name;
        usersElement.appendChild(option);
      });
    }

  } catch (error) {
    console.log('Erro ao fazer requisição: ' + error.message);
  }
}
const getClients = async () => {
  try {

    let url = `${BASE_CONTROLLERS}searchs.php`;

    const response = await fetch(url, {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: new URLSearchParams({ client_list: "true" }),
    })

    if (!response.ok) {
      showMessage('Erro ao buscar cliente', 'error');
      return;
    }

    const clients = await response.json();

    if (clients) {
      const clientsElement = document.getElementById('clients');
      clients.forEach(({ id, name }) => {
        const option = document.createElement("option");
        option.value = JSON.stringify(id, name);
        option.textContent = name;
        clientsElement.appendChild(option);
      });
    }

  } catch (error) {
    console.log('Erro ao fazer requisição: ' + error.message);
  }
}
const getProduct = async () => {
  try {

    let url = `${BASE_CONTROLLERS}searchs.php`;
    const response = await fetch(url, {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: new URLSearchParams({ product_list: "true" }),
    });

    if (!response.ok) throw new Error('Erro ao fazer a requisição');

    const products = await response.json();

    if (products.error) {
      console.error(products.error);
      return;
    }

    const selectElement = document.getElementById('product-conditional');
    products.forEach(({ id, name, value_product, stock_quantity }) => {
      const option = document.createElement("option");
      option.value = JSON.stringify({ id, name, value_product, stock_quantity });
      option.textContent = name;
      selectElement.appendChild(option);
    });

    selectElement.addEventListener('change', () => {
      const selectedProduct = JSON.parse(selectElement.value);
      document.getElementById('quantity').value = 1;
      document.getElementById('price-unit').value = selectedProduct.value_product;
    });
  } catch (error) {
    console.log('Erro ao buscar produtos: ' + error.message);
  }
};

const addProductTable = () => {
  const selectElement = document.getElementById('product-conditional');
  const selectedProduct = JSON.parse(selectElement.value);
  const quantity = parseInt(document.getElementById('quantity').value, 10);
  const priceUnit = parseFloat(document.getElementById('price-unit').value);

  if (selectedProduct && quantity && priceUnit) {
    const tableBody = document.getElementById('productTableBody');
    const newRow = document.createElement('tr');
    const total = quantity * priceUnit;

    newRow.innerHTML = `
      <td>${selectedProduct.name}</td>
      <td><input type="number" value="${quantity}" onchange="UpdateQuantity(this, '${selectedProduct.id}')" class="form-control quantity-input" /></td>
      <td><input type="number" value="${priceUnit}" class="form-control price-input" /></td>
      <td class="total-cell">${numberFormat(total).replace('.', ',')}</td>
      <td><button type="button" class="btn btn-danger btn-sm">Excluir</button></td>
    `;

    SelectedProducts.push({
      ProductId: selectedProduct.id,
      ProductName: selectedProduct.name,
      ProductQuantity: quantity,
      ProductPrice: priceUnit
    });

    tableBody.appendChild(newRow);
    addInputEventListeners(newRow);
    document.getElementById('addProductForm').reset();
    updateTotal();
    updateSubTotal();

    const modal = bootstrap.Modal.getInstance(document.getElementById('addProductModal'));
    modal.hide();
  } else {
    showMessage('Preencha todos os campos corretamente!', 'warning');
  }
};

function UpdateQuantity(element, id) {
  const newQuantity = parseInt(element.value, 10);
  const productIndex = SelectedProducts.findIndex(product => product.ProductId === id);

  if (productIndex !== -1) {
    SelectedProducts[productIndex].ProductQuantity = newQuantity;

    const priceUnit = SelectedProducts[productIndex].ProductPrice;
    const total = newQuantity * priceUnit;

    const row = element.closest('tr');
    const totalCell = row.querySelector('.total-cell');
    totalCell.textContent = numberFormat(total).replace('.', ',');

    updateTotal();
    updateSubTotal();
  } else {
    console.log('Produto não encontrado no array selecionado.');
  }
}
const addInputEventListeners = (row) => {
  const quantityInput = row.querySelector('.quantity-input');
  const priceInput = row.querySelector('.price-input');
  const totalCell = row.querySelector('.total-cell');

  const updateRowTotal = () => {
    const quantity = parseInt(quantityInput.value, 10) || 0;
    const priceUnit = parseFloat(priceInput.value.replace(',', '.')) || 0;
    const total = quantity * priceUnit;

    totalCell.textContent = total.toFixed(2);
    updateTotal();
  };

  quantityInput.addEventListener('input', updateRowTotal);
  priceInput.addEventListener('input', updateRowTotal);
};

const updateSubTotal = () => {
  const rows = document.querySelectorAll('#productTableBody tr');
  let total = 0;

  rows.forEach(row => {
    const totalCell = row.querySelector('.total-cell');
    const totalValue = parseFloat(totalCell.textContent.replace(',', '.'));
    total += totalValue;
  });
  document.getElementById('sub-total').value = total.toFixed(2).replace('.', ',');
}
const updateTotal = () => {
  const rows = document.querySelectorAll('#productTableBody tr');
  const discountInput = document.getElementById('discount');

  let discount = parseFloat(discountInput.value.replace(',', '.'));
  let total = 0;

  rows.forEach(row => {
    const totalCell = row.querySelector('.total-cell');
    const totalValue = parseFloat(totalCell.textContent.replace(',', '.'));
    total += totalValue;
  });

  if (isNaN(discount)) {
    discount = 0;
  }

  total -= discount;

  document.getElementById('total').value = total.toFixed(2).replace('.', ',');
};

const getFields = () => {
  const User1 = document.getElementById('users');
  const Client1 = document.getElementById('clients');

  return {
    type: {
      type: 'registerconditional'
    },
    values: {
      dateReturn: document.getElementById('date-return').value,
      dateNow: document.getElementById('date-now').value,

      subTotal: document.getElementById('sub-total').value.replace(',', '.'),
      total: document.getElementById('total').value.replace(',', '.'),
      discount: document.getElementById('discount').value.replace(',', '.'),

      obs: document.getElementById('obs').value,

      User: User1,
      Client: Client1,
      UserId: JSON.parse(User1.value),
      ClientId: JSON.parse(Client1.value),
    },
  }
}
const RegisterConditional = async () => {

  const { values, type } = await getFields();

  if (!values.ClientId || values.ClientId === "") {
    showMessage('Cliente não foi selecionado', 'warning');
    return;
  }
  if (!values.UserId || values.UserId === "") {
    showMessage('Usuário não foi selecionado', 'warning');
    return;
  }

  if (SelectedProducts.length === 0) {
    showMessage('Nenhum Produto selecionado', 'warning');
    return;
  }

  let responseCond = {
    type: type.type,
    dateNow: values.dateNow,
    dateReturn: values.dateReturn,
    subTotal: values.subTotal,
    discount: values.discount,
    total: values.total,
    obs: values.obs,
    UserId: values.UserId,
    ClientId: values.ClientId,
    SelectedProducts: SelectedProducts
  }

  continueMessage("Deseja realmente registrar essa condicional?", "Sim", "Não", async function () {
    try {

      let url = `${BASE_CONTROLLERS}registers.php`;

      const response = await fetch(url, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(responseCond)
      })

      if (!response.ok) {
        showMessage('Erro ao enviar dados, contate o suporte', 'error');
        return;
      }

      const responseBody = await response.json();

      if (responseBody && responseBody.success) {
        showMessage('Condicional aberta com sucesso ', 'success');
      } else {
        showMessage('Erro ao finalizar condicinal ' + response.message, 'error');
      }

    } catch (error) {
      showMessage('Erro ao fazer requisição ' + error, 'error');
    }
  })
}
