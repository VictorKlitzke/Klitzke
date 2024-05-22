let selectedRequest = [];
let tableSelected = [];
let SelectedInvos = [];
let SelectedFatPed = [];
let ButtonSelected = [];

let addButtonCard = document.getElementById('add-card-item');
let sourceTable = document.querySelector('.card-request-finallize .tbody-request');
let destinationTable = document.querySelector('destination-table');
let existingCardOrder = document.getElementById('card-order');

let OpenModalInvoicing = document.getElementById('modal-invo');
let overlayModalInvoicing = document.getElementById('overlay-invo');
let closeModalInvo = document.getElementById('modal-invo-close');
let ButtonFatInvo = document.querySelectorAll('.Invo-forms');
let buttonCardPed = document.getElementById('add-card-item');

document.addEventListener('DOMContentLoaded', function () {

	let productSRequestearch = document.getElementById('product-request-search');
	let SearchTable = document.getElementById('search-table');
	let productResult = document.getElementById('product-result-request');
	let searchResultTable = document.getElementById('result-table');

	let productID = document.getElementById('product-id');
	let productName = document.getElementById('product-name');
	let productsRequesttock_quantity = document.getElementById('product-stock_quantity');
	let value_product = document.getElementById('product-value');

	let numberTable = document.getElementById('number-table');

	let selectedRequestList = [];

	SearchTable.addEventListener('input', async function () {

		let searchQueryTable = SearchTable.value;

		try {

			const response = await fetch("http://localhost/Klitzke/ajax/search_table_request.php", {
				method: 'POST',
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded'
				},
				body: 'searchQueryTable=' + encodeURIComponent(searchQueryTable)
			})

			if (response.status === 200) {
				const responseData = await response.text();
				searchResultTable.innerHTML = responseData;
			} else {
				window.alert("Erro na busca" + response.status);
			}

		} catch (error) {
			window.alert("Erro ao buscar comanda. Por favor contante o suporte", response.error.message);
		};
	});

	productSRequestearch.addEventListener('input', async function () {

		let searchQuery = productSRequestearch.value;

		try {
			const response = await fetch('http://localhost/Klitzke/ajax/search_request.php', {
				method: 'POST',
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded'
				},
				body: 'searchQuery=' + encodeURIComponent(searchQuery)
			});

			if (response.ok) {
				const responseData = await response.text();
				productResult.innerHTML = responseData;
			} else {
				window.alert('Erro na requisição: ' + response.status);
			}

		} catch (error) {
			console.error('Erro ao realizar requisição:', error);
		}
	});

	function updateTotal(totalAddRequest) {
		let calculateRequest = document.getElementById('product-value-total');

		if (calculateRequest) {
			calculateRequest.textContent = 'R$ ' + totalAddRequest.toFixed(2);
		}
	}

	function calculateRequestAdd() {
		let totalAddRequest = 0;

		selectedRequestList.forEach(requestProduct => {
			let stockQuantity = requestProduct.productSRequesttock_quantity;
			let valueProduct = parseFloat(value_product.value) || 0;

			totalAddRequest += stockQuantity * valueProduct;
		});

		let calculateRequest = document.getElementById('product-value-total');
		if (calculateRequest) {
			calculateRequest.textContent = 'R$ ' + totalAddRequest.toFixed(2);
		}

		updateTotal(totalAddRequest);

		return totalAddRequest.toFixed(2);
	}

	searchResultTable.addEventListener('click', function (event) {
		if (event.target.tagName === 'LI') {
			let numbersTableRequest = event.target;
			let TableNumber = numbersTableRequest.getAttribute('data-number');

			numberTable.value = TableNumber;

			searchResultTable.innerHTML = '';
			if (SearchTable) {
				SearchTable.innerHTML = '';
			} else {
				console.error('Elemento SearchTable não encontrado.');
			}

		}
	})

	productResult.addEventListener('click', function (event) {
		if (event.target.tagName === 'LI') {
			let selectedRequest = event.target;
			let productId = selectedRequest.getAttribute('data-id');
			let productNames = selectedRequest.getAttribute('data-name');
			let productSRequesttock_quantity = selectedRequest.getAttribute('data-stock_quantity');
			let productValue_product = selectedRequest.getAttribute('data-value_product');

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

			let selectProductArray = {
				id: productID, stock_quantity: parseInt(productSRequesttock_quantity) || 0, value: productValue_product
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
			deleteButton.type = 'button';
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
		row.style.backgroundColor = '#ccc';
		row.style.color = '#000';
	} else {
		console.error("Botão de exclusão não encontrado.");
	}
}

async function deleteSelectedRow(row, quantityCell) {
	var productId = row.querySelector('.quantity-cell').textContent;

	if (quantityCell) {
		var number = parseInt(quantityCell.textContent) - 1;

		if (number >= 1) {
			quantityCell.textContent = number;
		} else {
			row.remove();

			var productIndex = selectedRequest.findIndex(requestProducts => requestProducts.id == productId);
			if (productIndex !== -1) {
				selectedRequest.splice(productIndex, 1);
			}
		}
	} else {
		console.error("Célula de quantidade não encontrada.");
	}
	calculateTotal();
}

/***/

/* CODIGO PARA ADICONAR ITENS NO CARD */

async function addItemCard() {

	const numberIdTable = document.getElementById('number-table');
	const rowstableProducts = document.getElementById('command-cell');

	let totalcard = 0;
	let existingCardOrder = null;
	let existingRow = null;

	if (rowstableProducts == '' || rowstableProducts == null) {
		window.alert('Itens do pedido não encontrados, itens pedidos vazio');
		return false;
	}

	if (!sourceTable || sourceTable.innerHTML.trim() === '') {
		window.alert('Itens do pedido não encontrados, entre em contato com o suporte!');
		return;
	}

	const rowsCardP = sourceTable.querySelectorAll('tr');

	const currentCommandId = numberIdTable.value.trim();
	if (!currentCommandId) {
		window.alert('Número da comanda não pode ser vazio.');
		return;
	}

	// Verifica se já existe um card com o currentCommandId
	document.querySelectorAll('.card-order').forEach(card => {
		if (card.dataset.commandId === currentCommandId) {
			existingCardOrder = card;
		}
	});

	if (!existingCardOrder) {
		existingCardOrder = createNewCard(currentCommandId);
		document.body.appendChild(existingCardOrder);
	} else if (existingCardOrder.dataset.commandId === currentCommandId) {
		console.log("functionando!!")
	}

	const destinationTable = existingCardOrder.querySelector('.destination-table');
	if (!destinationTable) {
		window.alert('Elemento .destination-table não encontrado no card, entre em contato com o suporte!');
		return;
	}

	rowsCardP.forEach((row) => {
		const cells = Array.from(row.children);

		if (cells.length < 4) {
			console.warn('A linha não contém células suficientes:', row);
			return;
		}

		const productID = cells[0].textContent.trim();
		const productName = cells[1].textContent.trim();
		const quantityText = cells[2].textContent.trim();
		const unitPriceText = cells[3].textContent.trim().replace('R$ ', '');
		const quantity = parseInt(quantityText, 10);
		const unitPrice = parseFloat(unitPriceText);

		// Verifica se já existe uma linha com o mesmo produto na tabela de destino
		Array.from(destinationTable.querySelectorAll('tr')).forEach(destRow => {
			const destCells = Array.from(destRow.children);
			if (destCells.length > 0 && destCells[0].textContent.trim() === productID) {
				existingRow = destRow;
			}
		});

		if (existingRow) {
			// Atualiza a quantidade e o valor total se a linha já existir
			const existingQuantity = parseInt(existingRow.children[2].textContent, 10);
			const newQuantity = existingQuantity + quantity;
			existingRow.children[2].textContent = newQuantity;
			const newLineTotal = newQuantity * unitPrice;
			totalcard += quantity * unitPrice; // Adiciona o total da nova quantidade
			existingRow.children[3].textContent = `R$ ${newLineTotal.toFixed(2)}`;
		} else {
			// Adiciona uma nova linha se não existir
			const newRow = document.createElement('tr');
			newRow.innerHTML = `
                <td>${productID}</td>
                <td>${productName}</td>
                <td>${quantity}</td>
                <td>R$ ${(quantity * unitPrice).toFixed(2)}</td>
            `;
			destinationTable.appendChild(newRow);
			totalcard += quantity * unitPrice;
		}

		let PedFat = {
			productID: productID,
			productName: productName,
			quantity: quantity,
			totalcard: totalcard
		}
		SelectedFatPed.push(PedFat);
	})

	const totalizadorElement = existingCardOrder.querySelector('.total-card');
	if (totalizadorElement) {
		totalizadorElement.textContent = `R$ ${totalcard.toFixed(2)}`;
	}

	sourceTable.innerHTML = '';
	numberIdTable.value = '';
	const totalizadorCard = document.getElementById('totalizador-request');
	totalizadorCard.textContent = '';
}

function createNewCard(commandId) {
	const newCard = document.createElement('div');
	newCard.classList.add('content');
	newCard.dataset.commandId = commandId;

	newCard.innerHTML = `
		<div class="card-order left">
			<div class="card-list">
				<h2>Itens na comanda ${commandId}</h2>
			</div>
			<div class="row-table-request">
				<table>
					<thead>
						<tr>
							<td>#</td>
							<td>Nome</td>
							<td>Qtd.</td>
							<td>Valor</td>
						</tr>
					</thead>
					<tbody class="destination-table" id="destination-table">
					</tbody>
				</table>
			</div>
			<div class="card-footer right">
				<button type="button" id="invoice-request" class="invoice-request" onclick="ModalFaturamento()">Gerar Pedido</button>
			</div>
			<h2 class="left total-card" id="totalizador-card">R$ 0.00</h2>
		</div>
    `;

	return newCard;
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

	let totalElementOrderRequestRequest = document.getElementById('totalizador-request');

	if (totalElementOrderRequestRequest) {
		totalElementOrderRequestRequest.textContent = 'R$ ' + totalRequest.toFixed(2);
	}
}

function calculateTotal() {

	let totalRequest = 0;

	selectedRequest.forEach(requestProducts => {

		let quantityElementOrderRequest = document.querySelector('.quantity-cell');
		let valueElementOrderRequest = document.querySelector('.value-cell');

		if (quantityElementOrderRequest && valueElementOrderRequest) {
			let quantityElementOrderTotalRequest = parseInt(quantityElementOrderRequest.textContent) || 0;
			let valueRequest = parseFloat(valueElementOrderRequest.textContent) || 0;

			totalRequest += quantityElementOrderTotalRequest * valueRequest;
		} else {
			console.error('Elementos não encontrados para o produto ID:', requestProducts.id);
		}
	});

	let totalElementOrderRequestRequest = document.getElementById('totalizador-request');
	if (totalElementOrderRequestRequest) {
		totalElementOrderRequestRequest.textContent = 'R$ ' + totalRequest.toFixed(2);
	}

	updateTotalAmountRequest(totalRequest);

	return totalRequest.toFixed(2);

}

// function AddProductOrder(id, name, stock_quantity, value_product) {
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

/* MODAL DE FATURAMENTO DE PEDIDO */

// function closemodalData() {
//
// 	if (OpenModalInvoicingFat.style.display == 'block' && overlayModalInvoicingFat.style.display == "block") {
// 		OpenModalInvoicingFat.style.display = "none";
// 		overlayModalInvoicingFat.style.display = "none";
// 	}
// };
//
// function ModalFaturamento(id_table, date_request, total_request, STATUS_REQUEST) {
// 	if (STATUS_REQUEST === "AGRUPADOS") {
// 		window.alert("Esse pedido esta agrupados");
// 		return;
// 	} else {
// 		OpenModalInvoicingFat.style.display = 'block';
// 		overlayModalInvoicingFat.style.display = 'block';
//
// 		const id_table_invoFat = document.getElementById('id-table');
// 		const value_request_totalFat = document.getElementById('total-request');
// 		const date_request_invoFat = document.getElementById('date-request');
// 		const status_request_invoFat = document.getElementById('status-request');
//
// 		id_table_invoFat.textContent = "Comanda: " + id_table;
// 		value_request_totalFat.innerHTML = "Total pedido " + '<input type="text" id="total_request" value="' + total_request + '" />';
// 		date_request_invoFat.textContent = "Data do pedido: " + date_request;
// 		status_request_invoFat.textContent = "Status: " + STATUS_REQUEST;
//
// 		ButtonFat.forEach(function (button) {
// 			button.addEventListener("click", function () {
// 				button.style.background = "rgb(58, 204, 82)";
// 			})
// 			button.addEventListener("dblclick", function () {
// 				button.style.background = "";
// 			})
// 		});
// 	}
//
// 	let orderPedidosFat = {
// 		id_table: id_table,
// 		date_request: date_request,
// 		total_request: total_request,
// 		status_request: STATUS_REQUEST,
// 		Button: ButtonFatInvo
// 	}
//
// 	SelectedInvosFat.push(orderPedidosFat);
// }
//
// async function CloseInvo() {
//
// 	let responseInvo = {
// 		SelectedInvosFat: orderPedidosFat,
// 	}
//
// 	try {
// 		const responseserver = await fetch("", {
// 			method: 'POST',
// 			headers: {
// 				'Content-Type': 'application/json',
// 			},
// 			body: JSON.stringify(responseInvo),
// 		})
//
// 		const response = await responseserver.text();
// 		console.log('Response from server:', response);
// 		const responseDataInvo = JSON.parse(response);
//
// 		if (responseDataInvo && responseDataInvo.success) {
// 			window.alert('Pedido finalizada com sucesso!');
// 			// const saleId = responseData.id;
// 			// window.location.href = 'pages/proof.php?sale_id=' + saleId;
// 		} else {
// 			console.error('Erro ao faturar pedido:', responseDataInvo ? responseDataInvo.error : 'Resposta vazia');
// 		}
// 	} catch (error) {
// 		console.log("Erro ao faturar Pedido" + SelectedInvos.id_table + error);
// 	}
//
// }

/**/

/* CODIGO DE AGRUPAMENTO DE COMANDAS */

async function addGathersArray(index, id, table_request, total_request) {

	const ResulttableGathers = document.getElementById('table-gathers-selected');
	let ExistingRowOrder = document.getElementById("row-" + id);

	if (ExistingRowOrder) {
		window.alert("Comanda ja selecionada");
		return;
	}

	let newTableGathers = {
		id: id,
		table_request: table_request,
		total_request: parseFloat(total_request.replace('', ''))
	}

	tableSelected.push(newTableGathers);

	let numericValueTotal = parseFloat(total_request);
	let FormmatedTotalValue = numericValueTotal.toFixed(2);

	let newTableInsert = ResulttableGathers.insertRow();
	newTableInsert.id = "row-" + id;
	newTableInsert.innerHTML = "<td id='order-id'>" + id + "</td>" +
		"<td id='order-table-request'>" + table_request + "</td>" +
		"<td id='order-total-request" + id + "'>" + FormmatedTotalValue + "</td>" +
		"<td style='margin: 6px; padding: 6px;'>" +
		"<div>" +
		"<button onclick='removertableSelected(" + id + ")' id='button-delete-" + id + "' class='btn-delete' type='button'>Deletar</button>" +
		"</div>" +
		"</td>";
	updateTotalizador();
}

function removertableSelected(id) {

	let rowToRemoveOrder = document.getElementById("row-" + id);

	if (tableSelected.length > 0) {

		let productIndexOrder = tableSelected.id = id;

		if (productIndexOrder !== -1) {

			let tableOrderRow = tableSelected.id = id;
			let productQuantityCellOrder = 1;

			if (productQuantityCellOrder) {
				let number = tableSelected.table_request - 1;

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
	let totalElementOrderRequest = document.getElementById('totalizador');
	if (totalElementOrderRequest) {
		totalElementOrderRequest.textContent = 'R$ ' + totalOrderRequest.toFixed(2);
	}
}

function updateTotalizador() {

	let totalOrderRequest = 0;

	tableSelected.forEach(tableSelected => {

		let quantityElementOrder = 1;
		let valueElementOrder = document.getElementById('order-total-request' + tableSelected.id);

		if (quantityElementOrder && valueElementOrder) {
			let quantityElementOrderTotal = 1 || 0;
			let valueOrders = parseFloat(valueElementOrder.textContent) || 0;

			totalOrderRequest += quantityElementOrderTotal * valueOrders;
		} else {
			console.error('Elementos não encontrados para comanda de ID:', tableSelected.id);
		}
	});

	let totalElementOrderRequest = document.getElementById('totalizador');
	if (totalElementOrderRequest) {
		totalElementOrderRequest.textContent = 'R$ ' + totalOrderRequest.toFixed(2);
	}

	updateAmountOrder(totalOrderRequest);

	return totalOrderRequest.toFixed(2);
}

async function GathersTables() {

	let valueGathersTotal = document.getElementById('totalizador').textContent;
	let valueTotalizadorOrderGathres = 0;

	if (valueGathersTotal === 0) {
		window.alert("Valor total zerado, por favror contante o suporte")
		return false;
	} else {
		valueTotalizadorOrderGathres = parseFloat(valueGathersTotal.replace(/R\$\s/g, ''));
	}

	const RequestDataGathers = {
		tables: tableSelected,
		valueTotalizadorOrderGathres: valueTotalizadorOrderGathres
	}

	if (tableSelected.length === 0) {
		window.alert("Nenhuma comanda selecionada")
	} else {
		try {

			let urlOrderGathres = 'http://localhost/Klitzke/ajax/gathers_tables.php';

			const RequestTables = await fetch(urlOrderGathres, {
				method: 'POST', headers: {
					'Content-Type': 'application/json',
				}, body: JSON.stringify(RequestDataGathers)
			});

			const responseTablesBody = await RequestTables.text();

			if (responseTablesBody.startsWith('<')) {
				console.error('Erro ao enviar dados para o PHP:', responseTablesBody);
				return;
			}

			const responseTables = JSON.parse(responseTablesBody);

			if (responseTables && responseTables.success) {
				window.alert('Comandas ajuntada com sucesso');
			} else {
				console.error('Erro ao tentar agrupar comandas:', responseTables ? responseTables.error : 'Resposta vazia');
			}
		} catch (error) {
			console.error('Erro ao enviar dados para o PHP:', error);
		}
	}
}

/***/

/* CARDS DE MENSAGENS */

function showErrorMessageRequest(message) {
	const errorContainerRequest = document.getElementById('erro-global-h2');
	const errorMessageElementRequest = document.getElementById('erro-global-h2');
	errorMessageElementRequest.textContent = message;
	errorContainerRequest.style.display = 'flex';
	setTimeout(() => {
		errorMessageElementRequest.textContent = '';
		errorContainerRequest.style.display = 'none';
	}, 3000);
}

function showSuccessMessageRequest(message) {
	const successContainerRequest = document.querySelector('sucess-global');
	const successMessageElementRequest = document.getElementById('sucess-global-h2');
	successMessageElementRequest.textContent = message;
	successContainerRequest.style.display = 'flex';
	setTimeout(() => {
		successMessageElementRequest.textContent = '';
		successContainerRequest.style.display = 'none';
	}, 3000);
}

/***/

/* CARD DE FATURAMENTO */

closeModalInvo.addEventListener("click", function () {
	OpenModalInvoicing.style.display = "none";
	overlayModalInvoicing.style.display = "none";
});

function ModalFaturamento() {

	const totalcardElement = document.getElementById('totalizador-card');
	const totalcardValue = totalcardElement ? totalcardElement.innerText.replace('R$', '').trim() : '0.00';

	const OpenModalInvoicing = document.getElementById('modal-invo');
	const overlayModalInvoicing = document.getElementById('overlay-invo');
	const orderDetails = document.getElementById('orderDetails');

	orderDetails.innerHTML = '';

	const itemElement = document.createElement('div');
	itemElement.style.alignItems = 'center';

	itemElement.innerHTML = `
        <span>Status: Em Atendimento</span>
        <br/>
        <br/>
        <div style="display: flex; align-items: center;">
        	<strong>Total</strong><input id="total-card-final" type="text" value="${totalcardValue}"/>
		</div>
        <br>
    `;
	orderDetails.appendChild(itemElement);

	OpenModalInvoicing.style.display = 'block';
	overlayModalInvoicing.style.display = 'block';

	const ButtonFatInvo = document.querySelectorAll('.Invo-forms');
	ButtonFatInvo.forEach(function (button) {
		button.addEventListener("click", function () {
			button.style.background = "rgb(58, 204, 82)";
		});
		console.log(button)
		button.addEventListener("dblclick", function () {
			button.style.background = "";
		});

		let buttonPed = {
			button: button
		}
		ButtonSelected.push(buttonPed);
	});

	document.getElementById('modal-invo-close').onclick = function() {
		OpenModalInvoicing.style.display = 'none';
		overlayModalInvoicing.style.display = 'none';
	};

	window.onclick = function(event) {
		if (event.target == overlayModalInvoicing) {
			OpenModalInvoicing.style.display = 'none';
			overlayModalInvoicing.style.display = 'none';
		}
	};
}

async function CloseInvo() {

	let totalCardFinal = document.getElementById('total-card-final').value
	totalCardFinal.replace('R$', '').trim();

	let PedFat = SelectedFatPed || '';

	if (totalCardFinal == '') {
		window.alert("Total esta vazio!");
		return;
	}

	let responseInvo = {
		SelectedFatPed: PedFat,
		totalCardFinal: totalCardFinal
	}

	console.log(responseInvo)

	if (responseInvo.SelectedFatPed == null) {
		window.alert("Erro ao buscar itens de pedido para faturamento!")
		return;
	}

	try {
		const responseserver = await fetch("", {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json',
			},
			body: JSON.stringify(responseInvo),
		})

		const response = await responseserver.text();
		console.log('Response from server:', response);
		const responseDataInvo = JSON.parse(response);

		if (responseDataInvo && responseDataInvo.success) {
			window.alert('Pedido finalizada com sucesso!');
		} else {
			window.alert("Erro ao faturar pedido, por favor entre em contato com o suporte")
			console.error('Erro ao faturar pedido:', responseDataInvo ? responseDataInvo.error : 'Resposta vazia');
		}
	} catch (error) {
		console.log("Erro ao faturar Pedido" + SelectedInvos.id_table + error);
	}

}

/**/

document.querySelector('.button-request').addEventListener('click', updatePedido, calculateTotal());
// document.querySelector('.invoice-request').addEventListener('click', generetorRequest);
// document.querySelector('.button-order').addEventListener('click', AddProductOrder());
document.getElementById('add-card-item').addEventListener('click', addItemCard);