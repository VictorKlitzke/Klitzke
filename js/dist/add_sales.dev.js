"use strict";

var selectedProducts = [];
var selectedPortion = [];
var selectedClientId;
var Portion;
var OverlayPortion;
var portionValues;
var trProduct = document.getElementById("product-result");
var tdButton = document.getElementById("button-product");
var saveButton = document.getElementById('button-portion');
var descPortionTbody = document.getElementById('desc-portion');
var totalPortionElement = document.getElementById('total-portion-sales');
var totalAmountElement = document.getElementById('totalAmount');
var portionTotalInput = document.getElementById('portion-total');
document.addEventListener('DOMContentLoaded', function () {
  Portion = document.querySelector('.portion-sales');
  OverlayPortion = document.querySelector('.overlay-portion');
});

function AddSelectProducts(index, id, name, stock_quantity, value) {
  var productAlreadyExists = false;

  for (var i = 0; i < selectedProducts.length; i++) {
    if (selectedProducts[i].id === id) {
      var number = selectedProducts[i].stock_quantity;
      number++;

      if (validateStock(stock_quantity, number)) {
        selectedProducts[i].stock_quantity = number;
        var productQuantityCell = document.getElementById("product-quantity-" + id);

        if (productQuantityCell) {
          productQuantityCell.textContent = number;
        }
      }

      updateProductQuantity(id, number);
      productAlreadyExists = true;
    }
  }

  if (!productAlreadyExists) {
    var newProduct = {
      id: id,
      name: name,
      stock_quantity: 1,
      value: parseFloat(value.replace(',', '.'))
    };
    validateStock(stock_quantity, 1);
    selectedProducts.push(newProduct);
    var newRow = trProduct.insertRow();
    newRow.id = "row-" + id;
    newRow.innerHTML = "<td id='product-id'>" + id + "</td>" + "<td id='product-name'>" + name + "</td>" + "<td id='product-quantity-" + id + "'>" + 1 + "</td>" + "<td id='product-value' class='content-form'>" + "<input type='text' id='value" + id + "' value='" + value + "' />" + "</td>" + "<td style='margin: 6px; padding: 6px;'>" + "<div>" + "<div>" + "<button onclick='removeProduct(" + id + ")' id='button-delete-" + id + "' class='btn-delete' type='button'>Deletar</button>" + "<button onclick='editProductValue(" + id + ")' class='btn-edit' style='margin-left: 5px;' type='button'>Editar Valor</button>" + "</div>" + "</div>" + "</td>";
  }

  calculateTotal();
}

function editProductValue(id) {
  var valueInput = document.getElementById("value" + id);

  if (!valueInput) {
    console.error('Elemento valueInput não encontrado.');
    return;
  }

  var promptResult = prompt("Digite o novo valor do produto:", valueInput.value);

  if (promptResult !== null && !isNaN(promptResult.trim())) {
    var editedValue = parseFloat(promptResult.replace(',', '.'));

    if (!isNaN(editedValue)) {
      valueInput.value = editedValue;
      var productIndex = selectedProducts.findIndex(function (product) {
        return product.id === String(id);
      });

      if (productIndex !== -1) {
        selectedProducts[productIndex].value = editedValue;
      } else {
        console.error('Produto não encontrado no array selectedProducts.');
      }
    } else {
      console.error('O valor do produto não é um número válido.');
    }
  }

  calculateTotal();
}

function openCreditModal() {
  if (Portion && OverlayPortion) {
    Portion.style.display = 'block';
    OverlayPortion.style.display = 'block';
  }
}

function closeCreditModal() {
  if (Portion && OverlayPortion) {
    Portion.style.display = 'none';
    OverlayPortion.style.display = 'none';
  }
}

document.addEventListener('DOMContentLoaded', function () {
  function checkPaymentMethod() {
    var selectedPaymentMethod = document.getElementById('id_payment_method').value;

    if (selectedPaymentMethod === '3') {
      openCreditModal();
    } else {
      closeCreditModal();
    }
  }

  document.getElementById('id_payment_method').addEventListener('change', checkPaymentMethod);
  var finishButtonPortion = document.getElementById('finish-portion');

  if (saveButton) {
    saveButton.addEventListener('click', function () {
      portionValues = calculateAndDisplayPortions();
    });
  } // if (finishButtonPortion) {
  //     finishButtonPortion.addEventListener('click', function() {
  //         finalizeSalePortion();
  //     });
  // }


  function calculateAndDisplayPortions() {
    var portionTotal = parseInt(portionTotalInput.value) || 1;

    if (portionTotal <= 0) {
      alert('Por favor, insira um número válido de parcelas.');
      return;
    }

    var totalAmount = parseFloat(totalAmountElement.textContent.replace('R$ ', '')) || 0;
    var portionValue = totalAmount / portionTotal;
    descPortionTbody.innerHTML = '';

    for (var i = 1; i <= portionTotal; i++) {
      var newRow = descPortionTbody.insertRow();
      var cellNumber = newRow.insertCell(0);
      var cellPortion = newRow.insertCell(1);
      var cellValue = newRow.insertCell(2);
      cellNumber.textContent = i;
      cellPortion.textContent = i;
      cellValue.textContent = 'R$ ' + portionValue.toFixed(2);
      selectedPortion.push({
        portionValue: portionValue,
        portionTotal: i
      });
    }

    totalPortionElement.textContent = 'R$ ' + portionValue.toFixed(2);
    return {
      selectedPortion: selectedPortion
    };
  }
});

function finalizeSalePortion() {
  var totalAmountElement, totalValuezPortion, selectedPaymentMethodPortion, idSalesClientPortion, requestDataPortion, responsePortion, responseBodyPortion, responseDataPortion;
  return regeneratorRuntime.async(function finalizeSalePortion$(_context) {
    while (1) {
      switch (_context.prev = _context.next) {
        case 0:
          totalAmountElement = document.getElementById('totalAmount');
          totalValuezPortion = 0;

          if (totalAmountElement) {
            totalValuezPortion = parseFloat(totalAmountElement.textContent.replace('R$ ', '')) || 0;
          }

          selectedPaymentMethodPortion = document.getElementById('id_payment_method').value;
          idSalesClientPortion = selectedClientId || '';

          if (!(selectedPaymentMethodPortion === '3')) {
            _context.next = 29;
            break;
          }

          openCreditModal();
          requestDataPortion = {
            idPaymentMethod: selectedPaymentMethodPortion,
            salesIdClient: idSalesClientPortion,
            totalValue: totalValuezPortion,
            selectedPortion: selectedPortion,
            products: selectedProducts
          };

          if (!(selectedProducts.length === 0)) {
            _context.next = 12;
            break;
          }

          showErrorMessage('Erro ao registrar venda, nenhum produto selecionado');
          _context.next = 27;
          break;

        case 12:
          _context.prev = 12;
          _context.next = 15;
          return regeneratorRuntime.awrap(fetch('http://localhost/Klitzke/ajax/add_sales_portion.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify(requestDataPortion)
          }));

        case 15:
          responsePortion = _context.sent;
          _context.next = 18;
          return regeneratorRuntime.awrap(responsePortion.text());

        case 18:
          responseBodyPortion = _context.sent;
          console.log('Response from server:', responseBodyPortion);
          responseDataPortion = JSON.parse(responseBodyPortion);

          if (responseDataPortion && responseDataPortion.success) {
            showSuccessMessage('Venda finalizada com sucesso!'); // const saleId = responseData.id;
            // window.location.href = 'pages/proof.php?sale_id=' + saleId;
          } else {
            console.error('Erro ao registrar venda:', responseDataPortion ? responseDataPortion.error : 'Resposta vazia');
          }

          _context.next = 27;
          break;

        case 24:
          _context.prev = 24;
          _context.t0 = _context["catch"](12);
          console.error('Erro ao enviar dados para o PHP:', _context.t0);

        case 27:
          _context.next = 30;
          break;

        case 29:
          return _context.abrupt("return", false);

        case 30:
        case "end":
          return _context.stop();
      }
    }
  }, null, null, [[12, 24]]);
}

function finalizeSale() {
  var totalAmountElement, totalValue, selectedPaymentMethod, idSalesClient, requestData, url, response, responseBody, responseData;
  return regeneratorRuntime.async(function finalizeSale$(_context2) {
    while (1) {
      switch (_context2.prev = _context2.next) {
        case 0:
          totalAmountElement = document.getElementById('totalAmount');
          totalValue = 0;

          if (totalAmountElement) {
            totalValue = parseFloat(totalAmountElement.textContent.replace('R$ ', '')) || 0;
          }

          selectedPaymentMethod = document.getElementById('id_payment_method').value;
          idSalesClient = selectedClientId || '';
          requestData = {
            idPaymentMethod: selectedPaymentMethod,
            salesIdClient: idSalesClient,
            totalValue: totalValue,
            products: selectedProducts
          };

          if (!(selectedProducts.length === 0)) {
            _context2.next = 10;
            break;
          }

          showErrorMessage('Erro ao registrar venda, nenhum produto selecionado');
          _context2.next = 25;
          break;

        case 10:
          _context2.prev = 10;
          url = 'http://localhost/Klitzke/ajax/add_sales.php';
          _context2.next = 14;
          return regeneratorRuntime.awrap(fetch(url, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify(requestData)
          }));

        case 14:
          response = _context2.sent;
          _context2.next = 17;
          return regeneratorRuntime.awrap(response.text());

        case 17:
          responseBody = _context2.sent;
          responseData = JSON.parse(responseBody);

          if (responseData && responseData.success) {
            showSuccessMessage('Venda finalizada com sucesso!');
          } else {
            console.error('Erro ao registrar venda:', responseData ? responseData.error : 'Resposta vazia');
          }

          _context2.next = 25;
          break;

        case 22:
          _context2.prev = 22;
          _context2.t0 = _context2["catch"](10);
          console.error('Erro ao enviar dados para o PHP:', _context2.t0);

        case 25:
        case "end":
          return _context2.stop();
      }
    }
  }, null, null, [[10, 22]]);
}

function updateTotalAmount(total) {
  var totalAmountElement = document.getElementById('totalAmount');

  if (totalAmountElement) {
    totalAmountElement.textContent = 'R$ ' + total.toFixed(2);
  }
}

function calculateTotal() {
  var total = 0;
  selectedProducts.forEach(function (product) {
    var quantityElement = document.getElementById('product-quantity-' + product.id);
    var valueElement = document.getElementById('value' + product.id);

    if (quantityElement && valueElement) {
      var quantityElementTotal = parseInt(quantityElement.textContent) || 0;
      var value = parseFloat(valueElement.value) || 0;
      total += quantityElementTotal * value;
    } else {
      console.error('Elementos não encontrados para o produto ID:', product.id);
    }
  });
  var totalAmountElement = document.getElementById('totalAmount');

  if (totalAmountElement) {
    totalAmountElement.textContent = 'R$ ' + total.toFixed(2);
  }

  updateTotalAmount(total);
  return total.toFixed(2);
}

function removeProduct(id) {
  var rowToRemove = document.getElementById("row-" + id);

  if (selectedProducts.length > 0) {
    var productIndex = selectedProducts.findIndex(function (product) {
      return product.id = id;
    });

    if (productIndex !== -1) {
      var product = selectedProducts[productIndex];
      var productQuantityCell = document.getElementById("product-quantity-" + id);

      if (productQuantityCell) {
        var number = product.stock_quantity - 1;

        if (number >= 1) {
          product.stock_quantity = number;
          productQuantityCell.textContent = number;
        } else {
          selectedProducts.splice(productIndex, 1);
          rowToRemove.remove();
        }
      }
    } else {
      console.error("Produto não encontrado no array.");
    }
  } else {
    console.error("Array de produtos está vazio");
  }

  calculateTotal();
} // if (process.env.NODE_ENV !== 'production') {
//     console.log('Valor sensível:', valorSensivel);
// }
// if (process.env.NODE_ENV === 'development') {
//     console.log('Somente exibido em ambiente de desenvolvimento');
// }


document.getElementById("sales-search-form").addEventListener("submit", function (event) {
  event.preventDefault();
  var searchInput = document.getElementById("clientSelectedSales").value;
  var tableRows = document.querySelectorAll(".tbody-selected tr");
  tableRows.forEach(function (row) {
    var clientName = row.querySelector("td:nth-child(2)").textContent.toLowerCase();

    if (clientName.includes(searchInput.toLowerCase())) {
      row.style.display = "";
    } else {
      row.style.display = "none";
    }
  });
});
document.addEventListener("DOMContentLoaded", function () {
  var tableRows = document.querySelectorAll(".tbody-selected");
  tableRows.forEach(function (row) {
    row.addEventListener("dblclick", function () {
      var clientName = row.querySelector("td:nth-child(2)").textContent;
      var salesPageElement = document.getElementById("sales-page");
      selectedClientId = row.querySelector("td:first-child").textContent;

      if (salesPageElement) {
        salesPageElement.innerHTML = "Codigo do cliente: " + selectedClientId + " Nome do cliente: " + clientName;
      }
    });
  });
});

function showErrorMessage(message) {
  var errorContainer = document.getElementById('error-container');
  var errorMessageElement = document.getElementById('error-message');
  errorMessageElement.textContent = message;
  errorContainer.style.display = 'flex';
  setTimeout(function () {
    errorMessageElement.textContent = '';
    errorContainer.style.display = 'none';
  }, 3000);
}

function showSuccessMessage(message) {
  var successContainer = document.getElementById('success-container');
  var successMessageElement = document.getElementById('success-message');
  successMessageElement.textContent = message;
  successContainer.style.display = 'flex';
  setTimeout(function () {
    successMessageElement.textContent = '';
    successContainer.style.display = 'none';
  }, 3000);
}

var finishButton = document.getElementById('finish-sales');

if (finishButton) {
  finishButton.onclick = finalizeSale;
}

function updateProductQuantity(id, stock_quantity) {
  for (var i = 0; i < selectedProducts.length; i++) {
    if (selectedProducts[i].id === id) {
      selectedProducts[i].stock_quantity = stock_quantity;
      break;
    }
  }
}

function validateStock(stock_quantity, qnt) {
  if (stock_quantity < qnt) {
    window.alert("Você não possui estoque suficiente!");
    return false;
  }

  return true;
}