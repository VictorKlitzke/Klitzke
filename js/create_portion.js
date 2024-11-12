const cardAddProductPosryion = document.getElementById('portion-products');
const idPortion = document.getElementById('id-portion');
const listprodPortion = document.getElementById('list-product-portion');

selectedProductPortions = [];

window.onload = function () {
  ListPortionProduct();
}

const getFieldsPortionProduct = () => {
  return {
    type: {
      type: 'createportion',
    },
    values: {
      namePortion: document.getElementById('nameportion').value,
      obsportion: document.getElementById('obsportion').value,
      valuePortion: document.getElementById('valuePortion').value,
      quantityPortion: document.getElementById('quantityPortion').value
    },
    inputs: {
      namePortion: document.getElementById('nameportion'),
      obsportion: document.getElementById('obsportion'),
      valuePortion: document.getElementById('valuePortion'),
      quantityPortion: document.getElementById('quantityPortion')
    }
  }
}

async function CreatePortion() {
  const { type, values, inputs } = await getFieldsPortionProduct();

  if (values.namePortion == "" || values.valuePortion == "" || values.quantityPortion == "") {
    showMessage('Campo nome ou valor da porção não pode ser vazio', 'warning');

    if (values.namePortion == "") inputs.namePortion.classList.add('error');
    if (values.valuePortion == "") inputs.valuePortion.classList.add('error');
    if (values.quantityPortion == "") inputs.quantityPortion.classList.add('error');
    setTimeout(() => {
      inputs.namePortion.classList.remove('error');
      inputs.valuePortion.classList.remove('error');
      inputs.quantityPortion.classList.remove('error');
    }, 3000);

    return;
  }

  function parseCurrency(value) {
    value = value.replace(/[^0-9,.]/g, '');
    value = value.replace(',', '.');
    return parseFloat(value);
  }
  const valuePortion = parseCurrency(values.valuePortion);


  let responsePortion = {
    type: type.type,
    namePortion: values.namePortion,
    obsportion: values.obsportion,
    valuePortion: valuePortion,
    quantityPortion: values.quantityPortion
  }

  continueMessage("Deseja realizar a criação da porção?", "Sim", "Não", async function () {

    try {

      let url = `${BASE_CONTROLLERS}registers.php`;

      const response = await fetch(url, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(responsePortion)
      });

      const responseBody = await response.json();

      if (responseBody.success) {
        showMessage('Nome criado, agora adicinar os produtos', 'success')

        PortionId = responseBody.data.id;
        localStorage.setItem('PortionId', PortionId)

        console.log(responseBody.data.id);

        idPortion.textContent = PortionId;
        cardAddProductPosryion.style.display = 'block';
        listprodPortion.style.display = 'block';
      } else {
        showMessage('Erro na crição da porção' + responseBody.message, 'error');
      }

    } catch (error) {
      showMessage('Erro na requisição: ' + error.message, 'error');
    }

  }, function () {
    showMessage('Operação cancelada', 'warning');
  })
}

async function ListPortionProduct() {
  try {
    let url = `${BASE_CONTROLLERS}querys.php`;
    let response = await fetch(url);

    if (!response.ok) {
      showMessage('Erro na requisição: ' + response.statusText, 'warning');
      return;
    }

    const responseListProd = await response.json();

    const productList = document.getElementById("product-portion");
    productList.innerHTML = '';

    responseListProd.list_portion.forEach(product => {
      const row = document.createElement('tr');

      const idCell = document.createElement('td');
      idCell.textContent = product.id;
      row.appendChild(idCell);

      const nameCell = document.createElement('td');
      nameCell.textContent = product.name;
      row.appendChild(nameCell);

      const stockEntryCell = document.createElement('td');
      stockEntryCell.textContent = product.stock_quantity;
      row.appendChild(stockEntryCell);

      const actionsCell = document.createElement('td');
      actionsCell.innerHTML = `
        <button class="btn btn-warning btn-sm" onclick="selectProductPortion('${product.id}', '${product.name}')">Selecionar</button>      `;
      row.appendChild(actionsCell);

      productList.appendChild(row);
    });
  } catch (error) {
    console.log('Erro na requisição: ' + error);
  }
}

async function AddProductPortion(id, name) {
  const newRow = `
    <tr id="products-rows">
      <td><input type="text" class="form-control border-dark" value="${id}" disabled placeholder="ID: 3"></td>
      <td><input type="text" class="form-control border-dark" value="${name}" disabled placeholder="Ex: Notebook"></td>
      <td><input type="number" class="form-control border-dark" value="1" onchange="updateSelectedQuantityPortion(this, '${id}')" placeholder="Ex: 5"></td>
      <td>
        <button class="btn btn-warning btn-sm" onclick="removeProductRowPortion(this)">🗑️</button>
      </td>
    </tr>
  `;
  document.getElementById('product-portion-row').insertAdjacentHTML('beforeend', newRow);

  selectedProductPortions.push({
    id: id,
    name: name,
    productQuantity: 1,
    productValue: productValue
  });
}
function selectProductPortion(id, name) {
  AddProductPortion(id, name);
}

function updateSelectedQuantityPortion(element, id) {
  const newQuantity = parseInt(element.value);
  const productIndex = selectedProductPortions.findIndex(product => product.id === id);

  if (productIndex !== -1) {
    selectedProductPortions[productIndex].productQuantity = newQuantity;
  } else {
    console.log('Produto não encontrado no array selecionado.');
  }
}

function removeProductRowPortion(button) {
  const row = button.closest('tr');
  row.remove();

  const productId = row.querySelector('input[type="text"]').value;
  const index = selectedProductPortions.findIndex(product => product.id === productId);
  if (index !== -1) {
    selectedProductPortions.splice(index, 1);
  }
}

async function RegisterProductPortion() {
  const PortionID = idPortion.textContent;

  let responseproductPortion = {
    selectedProductPortions: selectedProductPortions,
    PortionID: PortionID,
    type: 'createproductsportion'
  }

  continueMessage("Deseja realizar a criação dos produtos?", "Sim", "Não", async function () {

    try {

      let url = `${BASE_CONTROLLERS}registers.php`;

      const response = await fetch(url, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(responseproductPortion)
      });

      const responseBody = await response.json();

      if (responseBody.success) {
        showMessage('Criação da porção completa', 'success')

        cardAddProductPosryion.style.display = 'none';
        listprodPortion.style.display = 'none';

        setTimeout(() => {
          location.reload();
        }, 2000);

      } else {
        showMessage('Erro na crição da porção' + responseBody.message, 'error');
      }

    } catch (error) {
      showMessage('Erro na requisição: ' + error.message, 'error');
    }

  }, function () {
    showMessage('Operação cancelada', 'warning');
  })
}
