window.onload = function () {
  getProduct();
  getClients();
  getUsers();
  updateTotal();
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
      users.forEach(users => {
        const option = document.createElement("option");
        option.textContent = users.name;
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
      clients.forEach(client => {
        const option = document.createElement("option");
        option.textContent = client.name;
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
      document.getElementById('quantity').value = selectedProduct.stock_quantity;
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
  const priceUnit = parseFloat(document.getElementById('price-unit').value.replace(',', '.'));

  if (selectedProduct && quantity && priceUnit) {
    const tableBody = document.getElementById('productTableBody');
    const newRow = document.createElement('tr');
    const total = quantity * priceUnit;

    newRow.innerHTML = `
        <td>${selectedProduct.name}</td>
        <td>${quantity}</td>
        <td>${numberFormat(priceUnit).replace('.', ',')}</td>
        <td>${numberFormat(total).replace('.', ',')}</td>
        <td><button type="button" class="btn btn-danger btn-sm">Excluir</button></td>
      `;

    tableBody.appendChild(newRow);
    document.getElementById('addProductForm').reset();

    const modal = bootstrap.Modal.getInstance(document.getElementById('addProductModal'));
    modal.hide();
  } else {
    showMessage('Preencha todos os campos corretamente!', 'warning');
  }
};

const updateTotal = () => {
  const rows = document.querySelectorAll('#productTableBody tr');
  const discount = parseFloat(document.getElementById('discount').value.replace(',', '.'));
  let total = 0;

  rows.forEach(row => {
    const totalCell = row.cells[3];
    const totalValue = parseFloat(totalCell.textContent.replace(',', '.'));
    total += totalValue;
  });

  total -= discount;

  document.getElementById('total').textContent = numberFormat(total).replace('.', ',');
};
