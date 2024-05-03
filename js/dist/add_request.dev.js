"use strict";

var selectedRequest = [];
var numbersTableRequest = [];
var newListProducts = [];
var tableSelected = [];
var addButtonCard = document.getElementById('add-card-item');
var sourceTable = document.querySelector('.card-request-finallize .tbody-request');
var destinationTable = document.querySelector('destination-table');
var existingCardOrder = document.getElementById('card-order');
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

      if (SearchTable) {
        SearchTable.innerHTML = '';
      } else {
        console.error('Elemento SearchTable não encontrado.');
      }
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
/* CODIGO PARA ADICIONAR ITEM EM ITENS DO PEDIDO */

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
      Quantity.id = 'quantity-cell';
      Value.classList.add('value-cell');
      Value.id = 'value-cell';
      Command.id = 'command-cell';
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
        Name: requestName,
        stock_quantity: parseInt(requestQuantity),
        value: requestValue,
        Command: numberTableRequest
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
/***/

/* CODIGO PARA ADICONAR ITENS NO CARD */


function addItemCard() {
  var sourceTable, commandIdCell, numberIdTable, totalizadorCard, existingCardOrder, quantityCell, valueCell, totalcard, rows, _destinationTable;

  return regeneratorRuntime.async(function addItemCard$(_context4) {
    while (1) {
      switch (_context4.prev = _context4.next) {
        case 0:
          sourceTable = document.querySelector('.tbody-request');
          commandIdCell = document.getElementById('command-cell').textContent.trim();
          numberIdTable = document.getElementById('number-table');
          totalizadorCard = document.getElementById('totalizador-request');
          existingCardOrder = document.getElementById('card-order');
          quantityCell = document.getElementById('quantity-cell');
          valueCell = document.getElementById('value-cell');
          totalcard = 0;

          if (sourceTable) {
            _context4.next = 11;
            break;
          }

          console.error('Elemento sourceTable não encontrado');
          return _context4.abrupt("return");

        case 11:
          rows = sourceTable.querySelectorAll('tr');

          if (!(rows.length === 0)) {
            _context4.next = 15;
            break;
          }

          window.alert('Não há nenhum item na comanda');
          return _context4.abrupt("return");

        case 15:
          rows.forEach(function (row) {
            if (quantityCell && valueCell) {
              var quantityText = quantityCell.textContent.trim();
              var valueText = valueCell.textContent.trim().replace('R$ ', '');
              var quantity = parseInt(quantityText, 10);
              var value = parseFloat(valueText);

              if (!isNaN(quantity) && !isNaN(value)) {
                var lineTotal = quantity * value;
                totalcard += lineTotal;
              } else {
                console.error('Erro ao converter quantidade ou valor para números.');
              }
            } else {
              console.error('Elementos .quantity-cell ou .value-cell não encontrados na linha.');
            }
          });

          if (existingCardOrder.style.display = 'none' || existingCardOrder.dataset.commandId !== commandIdCell) {
            existingCardOrder.style.display = 'flex';
            existingCardOrder = document.createElement('div');
            existingCardOrder.id = 'card-order';
            existingCardOrder.classList.add('card-order', 'right');
            existingCardOrder.innerHTML = "\n\t\t\t\t\t<div class=\"card-order-content\">\n\t\t\t\t\t\t\t<div class=\"card-list\">\n\t\t\t\t\t\t\t\t\t<h2>Itens na comanda</h2>\n\t\t\t\t\t\t\t\t\t<button type=\"button\" id=\"add-more-items\" class=\"btn-add-more-items right\">Adicionar mais itens</button>\n\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t<table>\n\t\t\t\t\t\t\t\t\t<thead>\n\t\t\t\t\t\t\t\t\t\t\t<tr>\n\t\t\t\t\t\t\t\t\t\t\t\t\t<td>#</td>\n\t\t\t\t\t\t\t\t\t\t\t\t\t<td>Nome</td>\n\t\t\t\t\t\t\t\t\t\t\t\t\t<td>Qtd.</td>\n\t\t\t\t\t\t\t\t\t\t\t\t\t<td>Valor</td>\n\t\t\t\t\t\t\t\t\t\t\t\t\t<td>Comanda</td>\n\t\t\t\t\t\t\t\t\t\t\t</tr>\n\t\t\t\t\t\t\t\t\t</thead>\n\t\t\t\t\t\t\t\t\t<tbody id=\"destination-table\">\n\t\t\t\t\t\t\t\t\t\t\t<tr>\n\t\t\t\t\t\t\t\t\t\t\t</tr>\n\t\t\t\t\t\t\t\t\t</tbody>\n\t\t\t\t\t\t\t</table>\n\t\t\t\t\t\t\t<div class=\"card-footer right\">\n\t\t\t\t\t\t\t\t\t<button type=\"button\" id=\"invoice-request\" class=\"invoice-request\">Gerar Pedido</button>\n\t\t\t\t\t\t\t\t\t<h2 class=\"left total-card\" id=\"totalizador-card\">R$ ".concat(totalcard.toFixed(2), "</h2>\n\t\t\t\t\t\t\t</div>\n\t\t\t\t\t</div>\n\t\t\t");
            document.body.appendChild(existingCardOrder);
            _destinationTable = document.getElementById('destination-table');
            _destinationTable.innerHTML = '';
            rows.forEach(function (row) {
              var clonedRow = row.cloneNode(true);

              _destinationTable.appendChild(clonedRow);
            });
          } else {
            console.log('O número da comanda é o mesmo. Atualizando o card existente.');
          }

          sourceTable.innerHTML = '';
          numberIdTable.value = '';
          totalizadorCard.innerHTML = '';

        case 20:
        case "end":
          return _context4.stop();
      }
    }
  });
}
/***/


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
} // function AddProductOrder(id, name, stock_quantity, value_product) {
//     let tbody = document.querySelector('#items-list-order');
//     let existingRow = document.querySelector(`product-${id}`);
//     if (existingRow) {
//         let quantityCell = existingRow.querySelector('.product-quantity-order');
//         let valueCell = existingRow.querySelector('.product-value-order');
//         if (quantityCell && valueCell) {
//             let currentQuantity = parseInt(quantityCell.textContent);
//             let currentValue = parseFloat(valueCell.textContent.replace('R$', '').trim());
//             if (currentQuantity < stock_quantity) {
//                 window.alert("Estoque insuficiente para adicionar mais deste produto.");
//                 return false;
//             }
//             quantityCell.textContent = currentQuantity + 1;
//             let newValue = currentValue + parseFloat(value_product);
//             valueCell.textContent = `R$ ${newValue.toFixed(2)}`;
//             let productIndex = newListProducts.findIndex(product => product.id === id);
//             if (productIndex !== -1) {
//                 newListProducts[productIndex].stock_quantity++;
//                 newListProducts[productIndex].value_product += parseFloat(value_product);
//             }
//         } else {
//             console.error('Elementos de quantidade ou valor não encontrados na linha existente.');
//         }
//     } else {
//         let newRow = document.createElement('tr');
//         newRow.className = 'tr-order';
//         newRow.id = `product-${id}`;
//         newRow.innerHTML = `
//             <td class='product-id-order'>${id}</td>
//             <td class='product-name-order'>${name}</td>
//             <td class='product-quantity-order'>1</td>
//             <td class='product-value-order'>R$ ${value_product}</td>
//             <td style='margin: 6px; padding: 6px; cursor: pointer;'>
//                 <button onclick='deleteItemFromOrder(${id})' class='btn-delete' type='button'>Deletar</button>
//             </td>
//         `;
//         tbody.appendChild(newRow);
//         calculateTotalRequestOrder();
//         let newProduct = {
//             name: name,
//             stock_quantity: 1,
//             value_product: parseFloat(value_product)
//         };
//         newListProducts.push(newProduct);
//     }
//     // console.log(newListProducts);
// }
// function deleteItemFromOrder(id) {
//     let rowToDelete = document.getElementById(`product-${id}`);
//     if (rowToDelete) {
//         let quantityCell = rowToDelete.querySelector('.product-quantity-order');
//         let currentQuantity = parseInt(quantityCell.textContent);
//         if (currentQuantity > 1) {
//             quantityCell.textContent = currentQuantity - 1;
//             let productIndex = newListProducts.findIndex(product => product.name === name);
//             if (productIndex !== -1) {
//                 newListProducts[productIndex].stock_quantity--;
//                 newListProducts[productIndex].value_product -= parseFloat(newListProducts[productIndex].value_product);
//             }
//         } else {
//             rowToDelete.remove();
//             newListProducts = newListProducts.filter(product => product.id !== id);
//         }
//     } else {
//         window.alert("Produto não encontrado na comanda.");
//     }
//     calculateTotalRequestOrder();
// }
// async function AddProductItems() {
//     let totalizadorOrder = document.getElementById('total-order-value').textContent
//     let valueTotalizadorOrder = 0;
//     if (totalizadorOrder) {
//         valueTotalizadorOrder = parseFloat(totalizadorOrder.textContent.replace('R$ ', '')) || 0;
//     }
//     const responseDataNewList = {
//         newProduct: newListProducts,
//         valueTotalizadorOrder: valueTotalizadorOrder,
//     }
//     console.log(responseDataNewList);
//     if (newListProducts.length <= 0) {
//         showErrorMessageRequest("Nenhum produto selecionado")
//     } else {
//         try {
//             let urlOrder = '';
//             const response = await fetch(urlOrder, {
//                 method: 'POST',
//                 headers: {
//                     'Content-Type': 'application/json',
//                 },
//                 body: JSON.stringify(requestData),
//             });
//             const OrderResponse = await response.text();
//             const responseDataOrder = JSON.parse(OrderResponse);
//             if (responseDataOrder && responseDataOrder.success) {
//                 showSuccessMessage('Venda finalizada com sucesso!');
//             } else {
//                 console.error('Erro ao registrar venda:', responseDataOrder ? responseDataOrder.error : 'Resposta vazia');
//             }
//         } catch (error) {
//             showErrorMessageRequest("Erro ao enviar pedido")
//         }
//     }
// }
// function updateTotalAmountRequestOrder(valueRequestOrder) {
//     let totalElementOrderRequestRequestOrder = document.getElementById('total-order-value');
//     if (totalElementOrderRequestRequestOrder) {
//         totalElementOrderRequestRequestOrder.textContent = 'R$ ' + valueRequestOrder.toFixed(2);
//     }
// }
// function calculateTotalRequestOrder() {
//     let totalRequestOrder = 0;
//     newListProducts.forEach(newProduct => {
//         let quantityElementOrderRequest = 1;
//         let valueElementOrderRequest = document.getElementById('order-total-request');
//         if (quantityElementOrderRequest && valueElementOrderRequest) {
//             let quantityElementOrderTotalRequestOrder = parseInt(quantityElementOrderRequest.textContent) || 0;
//             let valueRequestOrder = parseFloat(valueElementOrderRequest.textContent) || 0;
//             totalRequestOrder += quantityElementOrderTotalRequestOrder * valueRequestOrder;
//         } else {
//             console.error('Elementos não encontrados para o produto ID:', newProduct.id);
//         }
//     });
//     let totalElementOrderRequestRequestOrder = document.getElementById('total-order-value');
//     if (totalElementOrderRequestRequestOrder) {
//         totalElementOrderRequestRequestOrder.textContent = 'R$ ' + totalRequestOrder.toFixed(2);
//     }
//     updateTotalAmountRequestOrder(totalRequestOrder);
//     return totalRequestOrder.toFixed(2);
// }

/* CODIGO DE AGRUPAMENTO DE COMANDAS */


function addGathersArray(index, id, table_request, total_request) {
  var ResulttableGathers, ExistingRowOrder, newTableGathers, numericValueTotal, FormmatedTotalValue, newTableInsert;
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
          numericValueTotal = parseFloat(total_request);
          FormmatedTotalValue = numericValueTotal.toFixed(2);
          newTableInsert = ResulttableGathers.insertRow();
          newTableInsert.id = "row-" + id;
          newTableInsert.innerHTML = "<td id='order-id'>" + id + "</td>" + "<td id='order-table-request'>" + table_request + "</td>" + "<td id='order-total-request" + id + "'>" + FormmatedTotalValue + "</td>" + "<td style='margin: 6px; padding: 6px;'>" + "<div>" + "<button onclick='removertableSelected(" + id + ")' id='button-delete-" + id + "' class='btn-delete' type='button'>Deletar</button>" + "</div>" + "</td>";
          updateTotalizador();

        case 13:
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
    var valueElementOrder = document.getElementById('order-total-request' + tableSelected.id);

    if (quantityElementOrder && valueElementOrder) {
      var quantityElementOrderTotal = 1 || 0;
      var valueOrders = parseFloat(valueElementOrder.textContent) || 0;
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
  var valueGathersTotal, valueTotalizadorOrderGathres, RequestDataGathers, urlOrderGathres, RequestTables, responseTablesBody, responseTables;
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
          valueTotalizadorOrderGathres = parseFloat(valueGathersTotal.replace(/R\$\s/g, ''));

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
          _context6.next = 31;
          break;

        case 13:
          _context6.prev = 13;
          urlOrderGathres = 'http://localhost/Klitzke/ajax/gathers_tables.php';
          _context6.next = 17;
          return regeneratorRuntime.awrap(fetch(urlOrderGathres, {
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

          if (!responseTablesBody.startsWith('<')) {
            _context6.next = 24;
            break;
          }

          console.error('Erro ao enviar dados para o PHP:', responseTablesBody);
          return _context6.abrupt("return");

        case 24:
          responseTables = JSON.parse(responseTablesBody);

          if (responseTables && responseTables.success) {
            window.alert('Comandas ajuntada com sucesso');
          } else {
            console.error('Erro ao tentar agrupar comandas:', responseTables ? responseTables.error : 'Resposta vazia');
          }

          _context6.next = 31;
          break;

        case 28:
          _context6.prev = 28;
          _context6.t0 = _context6["catch"](13);
          console.error('Erro ao enviar dados para o PHP:', _context6.t0);

        case 31:
        case "end":
          return _context6.stop();
      }
    }
  }, null, null, [[13, 28]]);
}
/***/
// async function generetorRequest() {
//     let totalElementOrderRequestRequest = document.getElementById('totalizador-request');
//     let TotalValueRequest = 0;
//     if (totalElementOrderRequestRequest) {
//         TotalValueRequest = parseFloat(totalElementOrderRequestRequest.textContent.replace('R$ ', '')) || 0;
//     }
//     let numberTableRequest = document.getElementById('number-table').value;
//     let RequestData = {
//         TotalValueRequest: TotalValueRequest,
//         requestProducts: selectedRequest,
//         numberTableRequest: numberTableRequest
//     };
//     console.log(RequestData);
//     if (requestProducts.length === 0) {
//         showErrorMessageRequest('Nenhum produto selecionado!!');
//         return true;
//     } else {
//         try {
//             let urlRequest = 'http://localhost/Klitzke/ajax/add_request.php';
//             const responseRequest = await fetch(urlRequest, {
//                 method: 'POST',
//                 headers: {
//                     'Content-Type': 'application/json'
//                 }, body: JSON.stringify(RequestData),
//             });
//             const responseBodyRequest = await responseRequest.text();
//             const responseDataRequest = JSON.parse(responseBodyRequest);
//             if (responseDataRequest && responseDataRequest.success) {
//                 showSuccessMessageRequest('Pedido gerado com sucesso!');
//             } else {
//                 console.error('Erro ao registrar venda:', responseDataRequest ? responseDataRequest.error : 'Resposta vazia');
//             }
//         } catch (error) {
//             console.error('Erro ao enviar dados para o PHP:', error);
//         }
//     }
// }

/* CARDS DE MENSAGENS */


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
/***/


document.querySelector('.button-request').addEventListener('click', updatePedido, calculateTotal()); // document.querySelector('.invoice-request').addEventListener('click', generetorRequest);
// document.querySelector('.button-order').addEventListener('click', AddProductOrder());

document.getElementById('add-card-item').addEventListener('click', addItemCard);