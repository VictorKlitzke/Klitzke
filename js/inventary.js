SelectedProductsRows = [];

function addProductRow(product_id, product_name, stock_difference) {
  const newRow = `
      <tr id="products-rows">
        <td><input type="text" class="form-control border-dark" value="${product_id}" disabled placeholder="ID: 3"></td>
        <td><input type="text" class="form-control border-dark" value="${product_name}" disabled placeholder="Ex: Notebook"></td>
        <td><input type="number" class="form-control border-dark" value="${stock_difference}" disabled placeholder="Ex: 5"></td>
      <td><input type="number" class="form-control border-dark" value="1" placeholder="Ex: 10" onchange="updateSelectedQuantity(this)"></td>
      <td>
        <button class="btn btn-warning btn-sm" onclick="removeProductRow(this)">🗑️</button>
      </td>
    </tr>
  `;
  document.getElementById('productRows').insertAdjacentHTML('beforeend', newRow);

  SelectedProductsRows.push({
    product_id: product_id,
    product_name: product_name,
    stock_difference: stock_difference,
    quantity_updated: 1
  });
}

function updateSelectedQuantity(input) {
  const row = input.closest('tr');
  const index = Array.from(document.querySelectorAll('#productRows tr')).indexOf(row);

  if (index >= 0) {
    SelectedProductsRows[index].quantity_updated = input.value;
  }
}

function removeProductRow(button) {
  const row = button.closest('tr');
  if (row) {
    row.remove();
  }
}

function SelectedProduct(product_id, product_name, stock_difference) {
  addProductRow(product_id, product_name, stock_difference);
}

async function RegisterUpdateInventaryItens() {
  const ProductsItensRow = document.getElementById('products-rows');
  const idInventary = document.getElementById('idInventary');

  let id_inventary = idInventary.textContent;

  if (!ProductsItensRow || ProductsItensRow.innerHTML.trim() === "") {
    showMessage('Lista de inventário está vazia!', 'warning');
    return;
  }

  let responseitensInventary = {
    id_inventary: id_inventary,
    SelectedProductsRows: SelectedProductsRows,
    type: 'createinventaryitens'
  }

  continueMessage("Deseja realizar inventario desses itens?", "Sim", "Não", async function () {
    try {

      let url = `${BASE_CONTROLLERS}registers.php`;

      const response = await fetch(url, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(responseitensInventary)
      });

      const responseBody = await response.text();
      let responseParse;

      try {
        responseParse = JSON.parse(responseBody);
      } catch (error) {
        showMessage('Erro ao fazer requisição: Resposta inválida do servidor', 'error');
        return;
      }

      if (responseParse || responseParse.success) {
        showMessage('Inventario realizado com sucesso', 'success')

        ClearLocalStorage();
        document.getElementById('AdjustInventary').style.display = 'none';
        document.getElementById('InventaryListProduct').style.display = 'none';


        setTimeout(() => {
          location.reload();
        }, 4000);

      } else {
        showMessage('Erro ao realizar Inventario' + responseParse.message, 'error');
      }

    } catch (error) {
      showMessage('Erro na requisição PHP' + error, 'error');
    }
  }, function () {
    showMessage('Operação cancelada', 'warning')
  })
}