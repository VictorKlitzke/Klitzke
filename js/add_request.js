let selectedRequest = [];
let tableSelected = [];
let SelectedInvos = [];
let SelectedFatPed = [];
let ButtonSelected = [];

let addButtonCard = document.getElementById('add-card-item');
let sourceTable = document.getElementById('tbody-request');
let destinationTable = document.querySelector('destination-table');
let existingCardOrder = document.getElementById('card-order');

let OpenModalInvoicing = document.getElementById('modal-invo');
let overlayModalInvoicing = document.getElementById('overlay-invo');
let closeModalInvo = document.getElementById('modal-invo-close');
let ButtonFatInvo = document.querySelectorAll('.Invo-forms');
let buttonCardPed = document.getElementById('add-card-item');
let closeInvoButton = document.getElementById('Invo-Fat');

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

			const response = await fetch(`${BASE_SEARCH}searchs.php`, {
				method: 'POST',
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded'
				},
				body: 'searchQueryTable=' + encodeURIComponent(searchQueryTable)
			})

			if (response.status === 200) {
				const responseDataTable = await response.text();
				searchResultTable.innerHTML = responseDataTable;
			} else {
				showMessage('Erro na busca' + response.status, 'error');
			}

		} catch (error) {
			showMessage("Erro ao buscar comanda. Por favor contante o suporte" + error, 'error');
		};
	});

	productSRequestearch.addEventListener('input', async function () {

		let search_query_request = productSRequestearch.value;

		try {
			const response = await fetch(`${BASE_SEARCH}searchs.php`, {
				method: 'POST',
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded'
				},
				body: 'search_query_request=' + encodeURIComponent(search_query_request)
			});

			if (response.status == 200) {
				const responseData = await response.text();
				productResult.innerHTML = responseData;
			} else {
				showMessage('Erro na requisição: ' + response.status, 'error');
			}

		} catch (error) {
			showMessage('Erro ao realizar requisição:' + error, 'error');
		}
	});

	function updateTotal(total) {
		const calculateRequest = document.getElementById('product-value-total');

		if (calculateRequest) {
			calculateRequest.textContent = 'R$ ' + total.toFixed(2);
		}
	}

	function calculateTotalForRequests() {
		let total = 0;

		selectedRequestList.forEach(requestProduct => {
			const stockQuantity = parseFloat(requestProduct.productSRequesttock_quantity) || 0;
			const valueProduct = parseFloat(requestProduct.value_product) || 0;

			total += stockQuantity * valueProduct;
		});

		return total;
	}

	function calculateRequestAdd() {
		const totalAddRequest = calculateTotalForRequests();
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

		var table = document.getElementById('tbody-request');
		var existingRow = findExistingRow(requestID);

		if (numberTableRequest === '') {
			showMessage('Comanda nao foi selecionada', 'warning');
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
			Quantity.id = 'quantity-cell-';
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
		showMessage('Preencha todos os campos corretamente antes de adicionar o pedido.', 'warning');
	}
	calculateTotal();
}

function findExistingRow(requestID) {
	var table = document.getElementById('tbody-request');
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
		console.clear();
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
		showMessage('Itens do pedido não encontrados, itens pedidos vazio', 'warning');
		return false;
	}

	if (!sourceTable || sourceTable.innerHTML.trim() === '') {
		showMessage('Itens do pedido não encontrados, entre em contato com o suporte!', 'warning');
		return;
	}

	const rowsCardP = sourceTable.querySelectorAll('tr');

	const currentCommandId = numberIdTable.value.trim();
	if (!currentCommandId) {
		showMessage('Número da comanda não pode ser vazio.', 'warning');
		return;
	}

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
		showMessage('Elemento .destination-table não encontrado no card, entre em contato com o suporte!', 'warning');
		return;
	}

	rowsCardP.forEach((row) => {
		const cells = Array.from(row.children);

		if (cells.length < 4) {
			console.warn('A linha não contém células suficientes:', row);
			console.clear()
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
			totalcard: totalcard,
			currentCommandId: currentCommandId
		}
		SelectedFatPed.push(PedFat);
	})

	const totalizadorElement = existingCardOrder.querySelector('.total-card');
	if (totalizadorElement) {
		totalizadorElement.textContent = `R$ ${totalcard.toFixed(2)}`;
		totalizadorElement.id = `totalizador-card${currentCommandId}`;
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
		<div class="card" id="card-order">
    <div class="card-header bg-primary text-white">
        <h4 class="mb-0">Itens na comanda ${commandId}</h4>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nome</th>
                        <th scope="col">Qtd.</th>
                        <th scope="col">Valor</th>
                    </tr>
                </thead>
                <tbody class="destination-table" id="destination-table">
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer d-flex justify-content-between align-items-center">
        <h5 class="total-card mb-0" id="totalizador-card${commandId}">R$ 0.00</h5>
        <button type="button" id="invoice-request-${commandId}" class="btn btn-success invoice-request">Gerar Pedido</button>
    </div>
</div>

    `;

	const invoiceButton = newCard.querySelector(`#invoice-request-${commandId}`);
	invoiceButton.addEventListener('click', function () {
		ModalFaturamento(commandId);
	});

	return newCard;
}

function requestValidateStock(stock_quantity, currentQuantity) {
	if (stock_quantity > currentQuantity) {
		showMessage('Estoque insuficiente para adicionar mais deste produto.', 'warning');
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
		let quantityElement = document.getElementById('quantity-cell-' + requestProducts.id);
		let valueElement = document.getElementById('value-cell-' + requestProducts.id);

		if (quantityElement && valueElement) {
			let quantityText = quantityElement.textContent.trim();
			let valueText = valueElement.textContent.trim();

			console.log('Texto de quantidade:', quantityText);
			console.log('Texto de valor:', valueText);

			let quantityValue = parseInt(quantityText, 10) || 0;
			let valueAmount = parseFloat(valueText.replace(',', '.')) || 0;

			totalRequest += quantityValue * valueAmount;
		} else {
			console.error('Elementos não encontrados para o produto ID:', requestProducts.id);
		}

	});

	updateTotalAmountRequest(totalRequest);

	return totalRequest.toFixed(2);
}


async function addGathersArray(index, id, table_request, total_request) {

	const ResulttableGathers = document.getElementById('table-gathers-selected');
	let ExistingRowOrder = document.getElementById("row-" + id);

	if (ExistingRowOrder) {
		showMessage('Comanda ja selecionada', 'warning');
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
		showMessage('Valor total zerado, por favror contante o suporte', 'warning')
		return false;
	} else {
		valueTotalizadorOrderGathres = parseFloat(valueGathersTotal.replace(/R\$\s/g, ''));
	}

	const RequestDataGathers = {
		tables: tableSelected,
		valueTotalizadorOrderGathres: valueTotalizadorOrderGathres
	}

	if (tableSelected.length === 0) {
		showMessage('Nenhuma comanda selecionada', 'warning')
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
				showMessage('Erro ao enviar dados para o PHP:' + responseTablesBody, 'error');
				return;
			}

			const responseTables = JSON.parse(responseTablesBody);

			if (responseTables && responseTables.success) {
				showMessage('Comandas ajuntada com sucesso', 'success');
			} else {
				showMessage('Erro ao tentar agrupar comandas:' + responseTables ? responseTables.error : 'Resposta vazia', 'error');
			}
		} catch (error) {
			showMessage('Erro ao enviar dados para o PHP:' + error, 'error');
		}
	}
}

/* CARD DE FATURAMENTO */

closeModalInvo.addEventListener("click", function () {
	OpenModalInvoicing.style.display = "none";
	overlayModalInvoicing.style.display = "none";
});

function fieldsTotalForms(button) {
	const buttonId = button.dataset.paymentId;
	const existingInput = document.querySelector(`.input-total-card[data-payment-id="${buttonId}"]`);

	if (existingInput) {
		existingInput.style.display = 'block';
		return;
	}

	const newInput = document.createElement('div');
	newInput.classList.add('input-total-card-container');
	newInput.dataset.paymentId = buttonId;
	newInput.innerHTML = `
		<div>
			<strong>${button.textContent}</strong><input id="payment-final-fat-${buttonId}" class="form-control text-black input-total-card" data-payment-id="${buttonId}" type="text" placeholder="Valor a ser pago"/>
		</div>
		<br/>
	`;

	const orderDetails = document.getElementById('orderDetails');
	orderDetails.appendChild(newInput);

	// Adiciona o evento de cálculo da diferença ao novo input
	const paymentInput = newInput.querySelector('.input-total-card');
	paymentInput.addEventListener('input', function () {
		const totalcardValue = parseFloat(document.getElementById(`total-card-final`).value);
		calculateDifference(totalcardValue);
	});
}

function ModalFaturamento(commandId) {
	const totalcardElement = document.getElementById(`totalizador-card${commandId}`);
	const totalcardValue = totalcardElement ? parseFloat(totalcardElement.innerText.replace('R$', '').trim()) : 0.00;

	const OpenModalInvoicing = document.getElementById('modal-invo');
	const overlayModalInvoicing = document.getElementById('overlay-invo');
	const orderDetails = document.getElementById('orderDetails');

	orderDetails.innerHTML = '';

	const itemElement = document.createElement('div');
	itemElement.style.alignItems = 'center';

	itemElement.innerHTML = `
		<span>Status: Em Atendimento</span>
		<br/>
		<span class="text-black d-flex"><strong> Diferença: </strong> <p id="difference-amount">R$ 0.00</p></span>
		<br/>
		<div>
			<strong>Total</strong><input class="form-control text-black" id="total-card-final" type="text" value="${totalcardValue}" readonly/>
		</div>
		<br>
	`;
	orderDetails.appendChild(itemElement);

	OpenModalInvoicing.style.display = 'block';
	overlayModalInvoicing.style.display = 'block';

	const ButtonFatInvo = document.querySelectorAll('.Invo-forms');
	ButtonFatInvo.forEach(function (button) {
		button.addEventListener("click", function () {
			fieldsTotalForms(button);
			button.style.background = "rgb(58, 204, 82)";
			let buttonPed = {
				buttonId: button.dataset.paymentId,
				buttonText: button.textContent
			}
			ButtonSelected.push(buttonPed);

			calculateDifference(totalcardValue);
			console.log(totalcardValue);
		});

		button.addEventListener("dblclick", function () {
			const paymentId = button.dataset.paymentId;
			const inputContainer = document.querySelector(`.input-total-card-container[data-payment-id="${paymentId}"]`);
			if (inputContainer) {
				inputContainer.style.display = 'none';
			}
			button.style.background = "";
			const index = ButtonSelected.findIndex(item => item.buttonId === paymentId);
			if (index > -1) {
				ButtonSelected.splice(index, 1);
			}
		});
	});

	document.getElementById('modal-invo-close').onclick = function () {
		OpenModalInvoicing.style.display = 'none';
		overlayModalInvoicing.style.display = 'none';
	};

	window.onclick = function (event) {
		if (event.target == overlayModalInvoicing) {
			OpenModalInvoicing.style.display = 'none';
			overlayModalInvoicing.style.display = 'none';
		}
	};

	if (closeInvoButton) {
		closeInvoButton.dataset.commandId = commandId;
	} else {
		console.error("Invo-Fat not found in the DOM.");
	}
}

function calculateDifference(totalValue) {
	const paymentInputs = document.querySelectorAll('.input-total-card');
	let sumPayments = 0;

	paymentInputs.forEach(function (input) {
		const paymentValue = parseFloat(input.value) || 0;
		sumPayments += paymentValue;
	});

	const difference = totalValue - sumPayments;

	const differenceElement = document.getElementById('difference-amount');
	if (differenceElement) {
		differenceElement.innerText = `R$ ${difference.toFixed(2)}`;
	}
}
async function CloseInvo() {

	const cardOrderFat = document.getElementById('card-order');
	const CloseButtonInvo = document.getElementById('Invo-Fat');
	const commandId = CloseButtonInvo ? CloseButtonInvo.dataset.commandId : null;

	let totalCardFinal = document.getElementById('total-card-final').value
	totalCardFinal.replace('R$', '').trim();

	let PedFat = SelectedFatPed.filter(item => item.currentCommandId === commandId);

	if (!commandId) {
		showMessage('Numero da comanda não encontrada!', 'warning');
		return;
	}

	let paymentFormsValor = ButtonSelected.map(button => {
		const input = document.getElementById(`payment-final-fat-${button.buttonId}`);
		return {
			paymentId: button.buttonId,
			paymentValue: input ? input.value.trim() : ''
		};
	}).filter(item => item.paymentValue !== '');

	let firstPagament = paymentFormsValor[0];
	let FormValuesPagament = parseFloat(firstPagament.paymentValue);

	if (isNaN(FormValuesPagament)) {
		showMessage('Proibido letras, por favor insira numeros', 'warning');
		return;
	}

	if (paymentFormsValor == null || paymentFormsValor == '') {
		showMessage('Nenhum valor nas formas de pagemento', 'warning');
		return;
	}

	if (totalCardFinal == '') {
		showMessage('Total esta vazio!', 'warning');
		return;
	}

	if (FormValuesPagament < totalCardFinal) {
		showMessage('Valores menores que o total', 'warning');
		return;
	}

	let responseInvo = {
		SelectedFatPed: PedFat,
		totalCardFinal: totalCardFinal,
		ButtonSelected: paymentFormsValor
	}

	if (responseInvo.SelectedFatPed == null) {
		showMessage('Erro ao buscar itens de pedido para faturamento!', 'warning');
		return;
	}

	try {
		const responseserver = await fetch(`${BASE_URL}add_request.php`, {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json',
			},
			body: JSON.stringify(responseInvo),
		})

		const responseText = await responseserver.text();

		let responseDataInvo;

		try {
			responseDataInvo = JSON.parse(responseText);
		} catch (error) {
			showMessage('Erro inesperado ao processar a faturamento de pedido. Entre em contato com o suporte.', 'error');
			return;
		}

		if (responseDataInvo && responseDataInvo.success) {
			showMessage('Pedido finalizada com sucesso!', 'success');
			cardOrderFat.style.display = 'none';
			OpenModalInvoicing.style.display = 'none';
			overlayModalInvoicing.style.display = 'none';
		} else {
			showMessage('Erro ao faturar pedido, por favor entre em contato com o suporte', 'error');
		}
	} catch (error) {
		showMessage('Erro ao faturar Pedido' + error, 'error');
		console.clear();
	}

}

document.getElementById('button-request').addEventListener('click', updatePedido, calculateTotal());
// document.querySelector('.invoice-request').addEventListener('click', generetorRequest);
// document.querySelector('.button-order').addEventListener('click', AddProductOrder());
document.getElementById('add-card-item').addEventListener('click', addItemCard);