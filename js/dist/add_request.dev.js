"use strict";

var selectedRequest = [];
var numbersTableRequest = [];
var newListProducts = [];
var tableSelected = [];
document.addEventListener('DOMContentLoaded', function () {
  var productSRequestearch = document.getElementById('product-request-search');
  var SearchTable = document.getElementById('search-table');
  var productResult = document.getElementById('product-result-request');
  var searchResultTable = document.getElementById('result-table');
  var productID = document.getElementById('product-id');
  var productName = document.getElementById('product-name');
  var productsRequesttock_quantity = document.getElementById('product-stock_quantity');
  var value_product = document.getElementById('product-value');
  var numberTable = document.getElementById('number-table');
  var selectedRequestList = [];
  SearchTable.addEventListener('input', function _callee() {
    var searchQueryTable, _response, responseData;

    return regeneratorRuntime.async(function _callee$(_context) {
      while (1) {
        switch (_context.prev = _context.next) {
          case 0:
            searchQueryTable = SearchTable.value;
            _context.prev = 1;
            _context.next = 4;
            return regeneratorRuntime.awrap(fetch("http://localhost/Klitzke/ajax/search_table_request.php", {
              method: 'POST',
              headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
              },
              body: 'searchQueryTable=' + encodeURIComponent(searchQueryTable)
            }));

          case 4:
            _response = _context.sent;

            if (!(_response.status === 200)) {
              _context.next = 12;
              break;
            }

            _context.next = 8;
            return regeneratorRuntime.awrap(_response.text());

          case 8:
            responseData = _context.sent;
            searchResultTable.innerHTML = responseData;
            _context.next = 13;
            break;

          case 12:
            window.alert("Erro na busca" + _response.status);

          case 13:
            _context.next = 18;
            break;

          case 15:
            _context.prev = 15;
            _context.t0 = _context["catch"](1);
            window.alert("Erro ao buscar comanda. Por favor contante o suporte", response.error.message);

          case 18:
            ;

          case 19:
          case "end":
            return _context.stop();
        }
      }
    }, null, null, [[1, 15]]);
  });
  productSRequestearch.addEventListener('input', function _callee2() {
    var searchQuery, _response2, responseData;

    return regeneratorRuntime.async(function _callee2$(_context2) {
      while (1) {
        switch (_context2.prev = _context2.next) {
          case 0:
            searchQuery = productSRequestearch.value;
            _context2.prev = 1;
            _context2.next = 4;
            return regeneratorRuntime.awrap(fetch('http://localhost/Klitzke/ajax/search_request.php', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
              },
              body: 'searchQuery=' + encodeURIComponent(searchQuery)
            }));

          case 4:
            _response2 = _context2.sent;

            if (!_response2.ok) {
              _context2.next = 12;
              break;
            }

            _context2.next = 8;
            return regeneratorRuntime.awrap(_response2.text());

          case 8:
            responseData = _context2.sent;
            productResult.innerHTML = responseData;
            _context2.next = 13;
            break;

          case 12:
            window.alert('Erro na requisição: ' + _response2.status);

          case 13:
            _context2.next = 18;
            break;

          case 15:
            _context2.prev = 15;
            _context2.t0 = _context2["catch"](1);
            console.error('Erro ao realizar requisição:', _context2.t0);

          case 18:
          case "end":
            return _context2.stop();
        }
      }
    }, null, null, [[1, 15]]);
  });

  function updateTotal(totalAddRequest) {
    var calculateRequest = document.getElementById('product-value-total');

    if (calculateRequest) {
      calculateRequest.textContent = 'R$ ' + totalAddRequest.toFixed(2);
    }
  }

  function calculateRequestAdd() {
    var totalAddRequest = 0;
    selectedRequestList.forEach(function (requestProduct) {
      var stockQuantity = requestProduct.productSRequesttock_quantity;
      var valueProduct = parseFloat(value_product.value) || 0;
      totalAddRequest += stockQuantity * valueProduct;
    });
    var calculateRequest = document.getElementById('product-value-total');

    if (calculateRequest) {
      calculateRequest.textContent = 'R$ ' + totalAddRequest.toFixed(2);
    }

    updateTotal(totalAddRequest);
    return totalAddRequest.toFixed(2);
  }

  searchResultTable.addEventListener('click', function (event) {
    if (event.target.tagName === 'LI') {
      var _numbersTableRequest = event.target;

      var TableNumber = _numbersTableRequest.getAttribute('data-number');

      numberTable.value = TableNumber;
      searchResultTable.innerHTML = '';
      SearchTable.innerHTML = '';
    }
  });
  productResult.addEventListener('click', function (event) {
    if (event.target.tagName === 'LI') {
      var _selectedRequest = event.target;

      var productId = _selectedRequest.getAttribute('data-id');

      var productNames = _selectedRequest.getAttribute('data-name');

      var productSRequesttock_quantity = _selectedRequest.getAttribute('data-stock_quantity');

      var productValue_product = _selectedRequest.getAttribute('data-value_product');

      if (productID) {
        productID.value = productId;
      }

      if (productName) {
        productName.value = productNames;
      }

      if (productSRequesttock_quantity) {
        productsRequesttock_quantity.value = 1;
      }

      if (value_product) {
        value_product.value = productValue_product;
      }

      var selectProductArray = {
        id: productID,
        stock_quantity: parseInt(productSRequesttock_quantity) || 0,
        value: productValue_product
      };
      selectedRequestList.push(selectProductArray);
      productResult.innerHTML = '';
    }

    calculateRequestAdd();
  });
});

function updatePedido() {
  var requestID = document.getElementById('product-id').value;
  var requestName = document.getElementById('product-name').value;
  var requestQuantity = document.getElementById('product-stock_quantity').value;
  var requestValue = document.getElementById('product-value').value;
  var numberTableRequest = document.getElementById('number-table').value;

  if (!isNaN(requestValue) && requestID && requestQuantity && requestName) {
    var table = document.querySelector('.tbody-request');
    var existingRow = findExistingRow(requestID);

    if (numberTableRequest === '') {
      window.alert("Comanda nao foi selecionada");
      return true;
    }

    if (existingRow) {
      var quantityCell = existingRow.querySelector('.quantity-cell');
      var currentQuantity = parseInt(quantityCell.textContent);
      quantityCell.textContent = currentQuantity + parseInt(requestQuantity);
    } else {
      var newRow = table.insertRow();
      var ID_ = newRow.insertCell(0);
      var Name = newRow.insertCell(1);
      var Quantity = newRow.insertCell(2);
      var Value = newRow.insertCell(3);
      var Command = newRow.insertCell(4);
      var Actions = newRow.insertCell(5);
      Name.style.minWidth = "100%";
      ID_.textContent = requestID;
      Name.textContent = requestName;
      Quantity.textContent = requestQuantity;
      Value.textContent = requestValue;
      Command.textContent = numberTableRequest;
      Quantity.classList.add('quantity-cell');
      Value.classList.add('value-cell');
      newRow.addEventListener('click', function () {
        selectRow(newRow);
      });
      var deleteButton = document.createElement('button');
      deleteButton.textContent = 'Deletar';
      deleteButton.style.backgroundColor = 'red';
      deleteButton.style.borderRadius = '5px';
      deleteButton.style.border = 'none';
      deleteButton.style.color = 'white';
      deleteButton.style.padding = '4px';
      deleteButton.style.display = 'none';
      deleteButton.classList.add('delete-button');
      deleteButton.addEventListener('click', function () {
        deleteSelectedRow(newRow, Quantity);
      });
      Actions.appendChild(deleteButton);
      selectedRequest.push({
        id: requestID,
        stock_quantity: parseInt(requestQuantity),
        value: requestValue
      });
    }

    document.getElementById('product-id').value = "";
    document.getElementById('product-name').value = "";
    document.getElementById('product-stock_quantity').value = "";
    document.getElementById('product-value').value = "";
    document.getElementById('product-request-search').value = "";
  } else {
    alert("Preencha todos os campos corretamente antes de adicionar o pedido.");
  }

  calculateTotal();
}

function findExistingRow(requestID) {
  var table = document.querySelector('.tbody-request');
  var rows = table.getElementsByTagName('tr');

  for (var i = 0; i < rows.length; i++) {
    var cells = rows[i].getElementsByTagName('td');

    if (cells.length > 1 && cells[0].textContent === requestID) {
      return rows[i];
    }
  }

  return null;
}

function selectRow(row) {
  var deleteButton = row.querySelector('.delete-button');

  if (deleteButton) {
    var selectedRows = document.querySelectorAll('.selected-row');
    selectedRows.forEach(function (selectedRow) {
      selectedRow.classList.remove('selected-row');
      var deleteBtn = selectedRow.querySelector('.delete-button');

      if (deleteBtn) {
        deleteBtn.style.display = 'none';
      }
    });
    row.classList.add('selected-row');
    deleteButton.style.display = 'inline';
    row.style.backgroundColor = '#202020';
  } else {
    console.error("Botão de exclusão não encontrado.");
  }
}

function deleteSelectedRow(row, quantityCell) {
  var productId, number, productIndex;
  return regeneratorRuntime.async(function deleteSelectedRow$(_context3) {
    while (1) {
      switch (_context3.prev = _context3.next) {
        case 0:
          productId = row.querySelector('.quantity-cell').textContent;

          if (quantityCell) {
            number = parseInt(quantityCell.textContent) - 1;

            if (number >= 1) {
              quantityCell.textContent = number;
            } else {
              row.remove();
              productIndex = selectedRequest.findIndex(function (requestProducts) {
                return requestProducts.id == productId;
              });

              if (productIndex !== -1) {
                selectedRequest.splice(productIndex, 1);
              }
            }
          } else {
            console.error("Célula de quantidade não encontrada.");
          }

          calculateTotal();

        case 3:
        case "end":
          return _context3.stop();
      }
    }
  });
}

function showErrorMessageRequest(message) {
  var errorContainerRequest = document.getElementById('erro-global-h2');
  var errorMessageElementRequest = document.getElementById('erro-global-h2');
  errorMessageElementRequest.textContent = message;
  errorContainerRequest.style.display = 'flex';
  setTimeout(function () {
    errorMessageElementRequest.textContent = '';
    errorContainerRequest.style.display = 'none';
  }, 3000);
}

function showSuccessMessageRequest(message) {
  var successContainerRequest = document.querySelector('sucess-global');
  var successMessageElementRequest = document.getElementById('sucess-global-h2');
  successMessageElementRequest.textContent = message;
  successContainerRequest.style.display = 'flex';
  setTimeout(function () {
    successMessageElementRequest.textContent = '';
    successContainerRequest.style.display = 'none';
  }, 3000);
}

function requestValidateStock(stock_quantity, currentQuantity) {
  if (stock_quantity > currentQuantity) {
    window.alert('Estoque insuficiente para adicionar mais deste produto.');
    return false;
  }

  return true;
}

function updateTotalAmountRequest(totalRequest) {
  var totalElementOrderRequestRequest = document.getElementById('totalizador-request');

  if (totalElementOrderRequestRequest) {
    totalElementOrderRequestRequest.textContent = 'R$ ' + totalRequest.toFixed(2);
  }
}

function calculateTotal() {
  var totalRequest = 0;
  selectedRequest.forEach(function (requestProducts) {
    var quantityElementOrderRequest = document.querySelector('.quantity-cell');
    var valueElementOrderRequest = document.querySelector('.value-cell');

    if (quantityElementOrderRequest && valueElementOrderRequest) {
      var quantityElementOrderTotalRequest = parseInt(quantityElementOrderRequest.textContent) || 0;
      var valueRequest = parseFloat(valueElementOrderRequest.textContent) || 0;
      totalRequest += quantityElementOrderTotalRequest * valueRequest;
    } else {
      console.error('Elementos não encontrados para o produto ID:', requestProducts.id);
    }
  });
  var totalElementOrderRequestRequest = document.getElementById('totalizador-request');

  if (totalElementOrderRequestRequest) {
    totalElementOrderRequestRequest.textContent = 'R$ ' + totalRequest.toFixed(2);
  }

  updateTotalAmountRequest(totalRequest);
  return totalRequest.toFixed(2);
}

function AddProductOrder(id, name, stock_quantity, value_product) {
  var tbody = document.querySelector('#items-list-order');
  var existingRow = document.querySelector("product-".concat(id));

  if (existingRow) {
    var quantityCell = existingRow.querySelector('.product-quantity-order');
    var valueCell = existingRow.querySelector('.product-value-order');

    if (quantityCell && valueCell) {
      var currentQuantity = parseInt(quantityCell.textContent);
      var currentValue = parseFloat(valueCell.textContent.replace('R$', '').trim());

      if (currentQuantity < stock_quantity) {
        window.alert("Estoque insuficiente para adicionar mais deste produto.");
        return false;
      }

      quantityCell.textContent = currentQuantity + 1;
      var newValue = currentValue + parseFloat(value_product);
      valueCell.textContent = "R$ ".concat(newValue.toFixed(2));
      var productIndex = newListProducts.findIndex(function (product) {
        return product.id === id;
      });

      if (productIndex !== -1) {
        newListProducts[productIndex].stock_quantity++;
        newListProducts[productIndex].value_product += parseFloat(value_product);
      }
    } else {
      console.error('Elementos de quantidade ou valor não encontrados na linha existente.');
    }
  } else {
    var newRow = document.createElement('tr');
    newRow.className = 'tr-order';
    newRow.id = "product-".concat(id);
    newRow.innerHTML = "\n            <td class='product-id-order'>".concat(id, "</td>\n            <td class='product-name-order'>").concat(name, "</td>\n            <td class='product-quantity-order'>1</td>\n            <td class='product-value-order'>R$ ").concat(value_product, "</td>\n            <td style='margin: 6px; padding: 6px; cursor: pointer;'>\n                <button onclick='deleteItemFromOrder(").concat(id, ")' class='btn-delete' type='button'>Deletar</button>\n            </td>\n        ");
    tbody.appendChild(newRow);
    calculateTotalRequestOrder();
    var newProduct = {
      name: name,
      stock_quantity: 1,
      value_product: parseFloat(value_product)
    };
    newListProducts.push(newProduct);
  } // console.log(newListProducts);

}

function deleteItemFromOrder(id) {
  var rowToDelete = document.getElementById("product-".concat(id));

  if (rowToDelete) {
    var quantityCell = rowToDelete.querySelector('.product-quantity-order');
    var currentQuantity = parseInt(quantityCell.textContent);

    if (currentQuantity > 1) {
      quantityCell.textContent = currentQuantity - 1;
      var productIndex = newListProducts.findIndex(function (product) {
        return product.name === name;
      });

      if (productIndex !== -1) {
        newListProducts[productIndex].stock_quantity--;
        newListProducts[productIndex].value_product -= parseFloat(newListProducts[productIndex].value_product);
      }
    } else {
      rowToDelete.remove();
      newListProducts = newListProducts.filter(function (product) {
        return product.id !== id;
      });
    }
  } else {
    window.alert("Produto não encontrado na comanda.");
  }

  calculateTotalRequestOrder(); // console.log(newListProducts);
}

function AddProductItems() {
  var totalizadorOrder, valueTotalizadorOrder, responseDataNewList, urlOrder, _response3, OrderResponse, responseDataOrder;

  return regeneratorRuntime.async(function AddProductItems$(_context4) {
    while (1) {
      switch (_context4.prev = _context4.next) {
        case 0:
          totalizadorOrder = document.getElementById('total-order-value').textContent;
          valueTotalizadorOrder = 0;

          if (totalizadorOrder) {
            valueTotalizadorOrder = parseFloat(totalizadorOrder.textContent.replace('R$ ', '')) || 0;
          }

          responseDataNewList = {
            newProduct: newListProducts,
            valueTotalizadorOrder: valueTotalizadorOrder
          };
          console.log(responseDataNewList);

          if (!(newListProducts.length <= 0)) {
            _context4.next = 9;
            break;
          }

          showErrorMessageRequest("Nenhum produto selecionado");
          _context4.next = 24;
          break;

        case 9:
          _context4.prev = 9;
          urlOrder = '';
          _context4.next = 13;
          return regeneratorRuntime.awrap(fetch(urlOrder, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify(requestData)
          }));

        case 13:
          _response3 = _context4.sent;
          _context4.next = 16;
          return regeneratorRuntime.awrap(_response3.text());

        case 16:
          OrderResponse = _context4.sent;
          responseDataOrder = JSON.parse(OrderResponse);

          if (responseDataOrder && responseDataOrder.success) {
            showSuccessMessage('Venda finalizada com sucesso!');
          } else {
            console.error('Erro ao registrar venda:', responseDataOrder ? responseDataOrder.error : 'Resposta vazia');
          }

          _context4.next = 24;
          break;

        case 21:
          _context4.prev = 21;
          _context4.t0 = _context4["catch"](9);
          showErrorMessageRequest("Erro ao enviar pedido");

        case 24:
        case "end":
          return _context4.stop();
      }
    }
  }, null, null, [[9, 21]]);
}

function updateTotalAmountRequestOrder(valueRequestOrder) {
  var totalElementOrderRequestRequestOrder = document.getElementById('total-order-value');

  if (totalElementOrderRequestRequestOrder) {
    totalElementOrderRequestRequestOrder.textContent = 'R$ ' + valueRequestOrder.toFixed(2);
  }
}

function calculateTotalRequestOrder() {
  var totalRequestOrder = 0;
  newListProducts.forEach(function (newProduct) {
    var quantityElementOrderRequest = 1;
    var valueElementOrderRequest = document.getElementById('order-total-request');

    if (quantityElementOrderRequest && valueElementOrderRequest) {
      var quantityElementOrderTotalRequestOrder = parseInt(quantityElementOrderRequest.textContent) || 0;
      var valueRequestOrder = parseFloat(valueElementOrderRequest.textContent) || 0;
      totalRequestOrder += quantityElementOrderTotalRequestOrder * valueRequestOrder;
    } else {
      console.error('Elementos não encontrados para o produto ID:', newProduct.id);
    }
  });
  var totalElementOrderRequestRequestOrder = document.getElementById('total-order-value');

  if (totalElementOrderRequestRequestOrder) {
    totalElementOrderRequestRequestOrder.textContent = 'R$ ' + totalRequestOrder.toFixed(2);
  }

  updateTotalAmountRequestOrder(totalRequestOrder);
  return totalRequestOrder.toFixed(2);
}

function addGathersArray(index, id, table_request, total_request) {
  var ResulttableGathers, ExistingRowOrder, newTableGathers, newTableInsert;
  return regeneratorRuntime.async(function addGathersArray$(_context5) {
    while (1) {
      switch (_context5.prev = _context5.next) {
        case 0:
          ResulttableGathers = document.getElementById('table-gathers-selected');
          ExistingRowOrder = document.getElementById("row-" + id);

          if (!ExistingRowOrder) {
            _context5.next = 5;
            break;
          }

          window.alert("Comanda ja selecionada");
          return _context5.abrupt("return");

        case 5:
          newTableGathers = {
            id: id,
            table_request: table_request,
            total_request: parseFloat(total_request.replace('', ''))
          };
          tableSelected.push(newTableGathers);
          newTableInsert = ResulttableGathers.insertRow();
          newTableInsert.id = "row-" + id;
          newTableInsert.innerHTML = "<td id='order-id'>" + id + "</td>" + "<td id='order-table-request'>" + table_request + "</td>" + "<td id='order-total-request" + id + "'>" + total_request + "</td>" + "<td style='margin: 6px; padding: 6px;'>" + "<div>" + "<button onclick='removertableSelected(" + id + ")' id='button-delete-" + id + "' class='btn-delete' type='button'>Deletar</button>" + "</div>" + "</td>";
          updateTotalizador();

        case 11:
        case "end":
          return _context5.stop();
      }
    }
  });
}

function removertableSelected(id) {
  var rowToRemoveOrder = document.getElementById("row-" + id);

  if (tableSelected.length > 0) {
    var productIndexOrder = tableSelected.id = id;

    if (productIndexOrder !== -1) {
      var tableOrderRow = tableSelected.id = id;
      var productQuantityCellOrder = 1;

      if (productQuantityCellOrder) {
        var number = tableSelected.table_request - 1;

        if (number >= 1) {
          tableOrderRow.table_request = number;
          productQuantityCellOrder = number;
        } else {
          tableSelected.splice(productIndexOrder, 1);
          rowToRemoveOrder.remove();
        }
      }
    } else {
      console.error("Produto não encontrado no array.");
    }
  } else {
    console.error("Array de produtos está vazio");
  }

  updateTotalizador();
}

function updateAmountOrder(totalOrderRequest) {
  var totalElementOrderRequest = document.getElementById('totalizador');

  if (totalElementOrderRequest) {
    totalElementOrderRequest.textContent = 'R$ ' + totalOrderRequest.toFixed(2);
  }
}

function updateTotalizador() {
  var totalOrderRequest = 0;
  tableSelected.forEach(function (tableSelected) {
    var quantityElementOrder = 1;
    var valueElementOrder = document.getElementById('order-total-request' + tableSelected.id).textContent;

    if (quantityElementOrder && valueElementOrder) {
      var quantityElementOrderTotal = 1 || 0;
      var valueOrders = parseFloat(document.getElementById('order-total-request' + tableSelected.id).textContent) || 0;
      totalOrderRequest += quantityElementOrderTotal * valueOrders;
    } else {
      console.error('Elementos não encontrados para comanda de ID:', tableSelected.id);
    }
  });
  var totalElementOrderRequest = document.getElementById('totalizador');

  if (totalElementOrderRequest) {
    totalElementOrderRequest.textContent = 'R$ ' + totalOrderRequest.toFixed(2);
  }

  updateAmountOrder(totalOrderRequest);
  return totalOrderRequest.toFixed(2);
}

function GathersTables() {
  var valueGathersTotal, valueTotalizadorOrderGathres, RequestDataGathers, RequestTables, responseTablesBody, responseTables;
  return regeneratorRuntime.async(function GathersTables$(_context6) {
    while (1) {
      switch (_context6.prev = _context6.next) {
        case 0:
          valueGathersTotal = document.getElementById('totalizador').textContent;
          valueTotalizadorOrderGathres = 0;

          if (!(valueGathersTotal === 0)) {
            _context6.next = 7;
            break;
          }

          window.alert("Valor total zerado, por favror contante o suporte");
          return _context6.abrupt("return", false);

        case 7:
          valueTotalizadorOrderGathres = parseFloat(valueGathersTotal.trim('R$ ', ''));

        case 8:
          RequestDataGathers = {
            tables: tableSelected,
            valueTotalizadorOrderGathres: valueTotalizadorOrderGathres
          };

          if (!(tableSelected.length === 0)) {
            _context6.next = 13;
            break;
          }

          window.alert("Nenhuma comanda selecionada");
          _context6.next = 29;
          break;

        case 13:
          console.log(RequestDataGathers);
          _context6.prev = 14;
          _context6.next = 17;
          return regeneratorRuntime.awrap(fetch('http://localhost/Klitzke/ajax/gathers_tables.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify(RequestDataGathers)
          }));

        case 17:
          RequestTables = _context6.sent;
          _context6.next = 20;
          return regeneratorRuntime.awrap(RequestTables.text());

        case 20:
          responseTablesBody = _context6.sent;
          console.log('Response from server:', responseTablesBody);
          responseTables = JSON.parse(responseTablesBody);

          if (responseTables && responseTables.success) {
            showSuccessMessage('Comandas ajuntada com sucesso');
          } else {
            console.error('Erro ao registrar venda:', responseTables ? responseTables.error : 'Resposta vazia');
          }

          _context6.next = 29;
          break;

        case 26:
          _context6.prev = 26;
          _context6.t0 = _context6["catch"](14);
          console.error('Erro ao enviar dados para o PHP:', _context6.t0);

        case 29:
        case "end":
          return _context6.stop();
      }
    }
  }, null, null, [[14, 26]]);
}

function generetorRequest() {
  var totalElementOrderRequestRequest, TotalValueRequest, numberTableRequest, RequestData, urlRequest, responseRequest, responseBodyRequest, responseDataRequest;
  return regeneratorRuntime.async(function generetorRequest$(_context7) {
    while (1) {
      switch (_context7.prev = _context7.next) {
        case 0:
          totalElementOrderRequestRequest = document.getElementById('totalizador-request');
          TotalValueRequest = 0;

          if (totalElementOrderRequestRequest) {
            TotalValueRequest = parseFloat(totalElementOrderRequestRequest.textContent.replace('R$ ', '')) || 0;
          }

          numberTableRequest = document.getElementById('number-table').value;
          RequestData = {
            TotalValueRequest: TotalValueRequest,
            requestProducts: selectedRequest,
            numberTableRequest: numberTableRequest
          };
          console.log(RequestData);

          if (!(requestProducts.length === 0)) {
            _context7.next = 11;
            break;
          }

          showErrorMessageRequest('Nenhum produto selecionado!!');
          return _context7.abrupt("return", true);

        case 11:
          _context7.prev = 11;
          urlRequest = 'http://localhost/Klitzke/ajax/add_request.php';
          _context7.next = 15;
          return regeneratorRuntime.awrap(fetch(urlRequest, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify(RequestData)
          }));

        case 15:
          responseRequest = _context7.sent;
          _context7.next = 18;
          return regeneratorRuntime.awrap(responseRequest.text());

        case 18:
          responseBodyRequest = _context7.sent;
          responseDataRequest = JSON.parse(responseBodyRequest);

          if (responseDataRequest && responseDataRequest.success) {
            showSuccessMessageRequest('Pedido gerado com sucesso!');
          } else {
            console.error('Erro ao registrar venda:', responseDataRequest ? responseDataRequest.error : 'Resposta vazia');
          }

          _context7.next = 26;
          break;

        case 23:
          _context7.prev = 23;
          _context7.t0 = _context7["catch"](11);
          console.error('Erro ao enviar dados para o PHP:', _context7.t0);

        case 26:
        case "end":
          return _context7.stop();
      }
    }
  }, null, null, [[11, 23]]);
}

document.querySelector('.button-request').addEventListener('click', updatePedido, calculateTotal());
document.querySelector('.invoice-request').addEventListener('click', generetorRequest);
document.querySelector('.button-order').addEventListener('click', AddProductOrder());