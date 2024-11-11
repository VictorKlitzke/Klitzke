const cardAddProductPosryion = document.getElementById('portion-products');
const idPortion = document.getElementById('id-portion');
const listprodPortion = document.getElementById('list-product-portion');

let SelectedProductPortion = [];

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
    },
    inputs: {
      namePortion: document.getElementById('nameportion'),
      obsportion: document.getElementById('obsportion'),
    }
  }
}
async function CreatePortion() {
  const { type, values, inputs } = await getFieldsPortionProduct();

  if (values.namePortion == "") {
    showMessage('Campo nome da porção não pode ser vazio', 'warning');

    if (values.namePortion == "") inputs.namePortion.classList.add('error');
    setTimeout(() => {
      inputs.namePortion.classList.remove('error');
    }, 3000);

    return;
  }

  let responsePortion = {
    type: type.type,
    namePortion: values.namePortion,
    obsportion: values.obsportion
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
        <button class="btn btn-warning btn-sm" onclick="SelectedProductPortion('${product.id}', '${product.name}')">Selecionar</button>
      `;
      row.appendChild(actionsCell);

      productList.appendChild(row);
    });
  } catch (error) {
    console.log('Erro na requisição: ' + error);
  }
}
async function AddProductPortion(id, name) {
  const existingRow = document.querySelector(`#product-portion-row tr[data-product-id="${id}"]`);

  if (existingRow) {
    showMessage('Não pode adicionar o mesmo produto', 'warning');
    return;
  }

  const newRow = `
  <tr id="products-rows">
    <td><input type="text" class="form-control border-dark" value="${id}" disabled placeholder="ID: 3"></td>
    <td><input type="text" class="form-control border-dark" value="${name}" disabled placeholder="Ex: Notebook"></td>
    <td><input type="number" class="form-control border-dark" value="1" onchange(updateSelectedQuantity(this)) placeholder="Ex: 5"></td>
  <td>
    <button class="btn btn-warning btn-sm" onclick="removeProductRow(this)">🗑️</button>
  </td>
</tr>
`;
  document.getElementById('product-portion-row').insertAdjacentHTML('beforeend', newRow);

  SelectedProductPortion.push({
    productId: id,
    productQuantity: 1
  })
}
function SelectedProductPortion(id, name) {
  AddProductPortion(id, name);
}
function updateSelectedQuantity(input) {
  const row = input.closest('tr');
  const index = Array.from(document.querySelectorAll('#product-portion-row tr')).indexOf(row);

  if (index >= 0) {
    SelectedProductsRows[index].productQuantity = input.value;
  }
}
function removeProductRow(button) {
  const row = button.closest('tr');
  if (row) {
    row.remove();
  }
}
// async function RegisterProductPortion() {
//   const PortionID = idPortion.textContent;

//   let responseproductPortion = {
//     SelectedProductPortion: SelectedProductPortion,
//     PortionID: PortionID,
//     type: 'createproductsportion'
//   }

//   continueMessage("Deseja realizar a criação dos produtos?", "Sim", "Não", async function () {

//     try {

//       let url = `${BASE_CONTROLLERS}registers.php`;

//       const response = await fetch(url, {
//         method: 'POST',
//         headers: {
//           'Content-Type': 'application/json'
//         },
//         body: JSON.stringify(responseproductPortion)
//       });

//       const responseBody = await response.json();

//       if (responseBody.success) {
//         showMessage('Criação da porção completa', 'success')

//         cardAddProductPosryion.style.display = 'none';
//         listprodPortion.style.display = 'none';
//       } else {
//         showMessage('Erro na crição da porção' + responseBody.message, 'error');
//       }

//     } catch (error) {
//       showMessage('Erro na requisição: ' + error.message, 'error');
//     }

//   }, function () {
//     showMessage('Operação cancelada', 'warning');
//   })
// }
