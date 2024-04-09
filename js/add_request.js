let selectedRequest = [];
let numbersTableRequest = [];

document.addEventListener('DOMContentLoaded', function() {

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

    SearchTable.addEventListener('input', function() {

        let searchQueryTable = SearchTable.value;
        let http = new XMLHttpRequest();

        http.onreadystatechange = function() {
            if (http.readyState === 4 && http.status === 200) {
                searchResultTable.innerHTML = http.responseText;
            }
        }
        http.open('POST', 'http://localhost/Klitzke/ajax/search_table_request.php', true);
        http.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        http.send('searchQueryTable=' + encodeURIComponent(searchQueryTable));
    });

    productSRequestearch.addEventListener('input', function() {

        let searchQuery = productSRequestearch.value;
        let xhr = new XMLHttpRequest();

        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                productResult.innerHTML = xhr.responseText;
            }
        }
        xhr.open('POST', 'http://localhost/Klitzke/ajax/search_request.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.send('searchQuery=' + encodeURIComponent(searchQuery));
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

    searchResultTable.addEventListener('click', function(event) {
        if (event.target.tagName === 'LI') {
            let numbersTableRequest = event.target;
            let TableNumber = numbersTableRequest.getAttribute('data-number');

            if (TableNumber) {
                numberTable.value = TableNumber;
            }

            searchResultTable.innerHTML = '';
            SearchTable.innerHTML = '';
        }
    })

    productResult.addEventListener('click', function(event) {
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

            newRow.addEventListener('click', function() {
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
            deleteButton.addEventListener('click', function() {
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
        selectedRows.forEach(function(selectedRow) {
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

function updateTotalAmountRequest(totalRequest) {

    let totalAmountElementRequest = document.getElementById('totalizador-request');

    if (totalAmountElementRequest) {
        totalAmountElementRequest.textContent = 'R$ ' + totalRequest.toFixed(2);
    }
}

function calculateTotal() {

    let totalRequest = 0;

    selectedRequest.forEach(requestProducts => {

        let quantityElementRequest = document.querySelector('.quantity-cell');
        let valueElementRequest = document.querySelector('.value-cell');

        if (quantityElementRequest && valueElementRequest) {
            let quantityElementTotalRequest = parseInt(quantityElementRequest.textContent) || 0;
            let valueRequest = parseFloat(valueElementRequest.textContent) || 0;

            totalRequest += quantityElementTotalRequest * valueRequest;
        } else {
            console.error('Elementos não encontrados para o produto ID:', requestProducts.id);
        }
    });

    let totalAmountElementRequest = document.getElementById('totalizador-request');
    if (totalAmountElementRequest) {
        totalAmountElementRequest.textContent = 'R$ ' + totalRequest.toFixed(2);
    }

    updateTotalAmountRequest(totalRequest);

    return totalRequest.toFixed(2);

}

async function generetorRequest() {

    let totalAmountElementRequest = document.getElementById('totalizador-request');
    let TotalValueRequest = 0;
    if (totalAmountElementRequest) {
        TotalValueRequest = parseFloat(totalAmountElementRequest.textContent.replace('R$ ', '')) || 0;
    }
    let numberTableRequest = document.getElementById('number-table').value;

    let RequestData = {
        TotalValueRequest: TotalValueRequest,
        requestProducts: selectedRequest,
        numberTableRequest: numberTableRequest
    };

    console.log(RequestData);

    if (requestProducts.length === 0) {
        showErrorMessageRequest('Nenhum produto selecionado!!');
        return;
    } else {

        try {
            let urlRequest = 'http://localhost/Klitzke/ajax/add_request.php';

            const responseRequest = await fetch(urlRequest, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(RequestData),
            });

            const responseBodyRequest = await responseRequest.text();
            const responseDataRequest = JSON.parse(responseBodyRequest);

            if (responseDataRequest && responseDataRequest.success) {
                showSuccessMessageRequest('Venda finalizada com sucesso!');
            } else {
                console.error('Erro ao registrar venda:', responseDataRequest ? responseDataRequest.error : 'Resposta vazia');
            }

        } catch (error) {
            console.error('Erro ao enviar dados para o PHP:', error);
        }

    }

}

function showErrorMessageRequest(message) {
    const errorContainer = document.getElementById('error-container-request');
    const errorMessageElement = document.getElementById('error-message-request');
    errorMessageElement.textContent = message;
    errorContainer.style.display = 'flex';
    setTimeout(() => {
        errorMessageElement.textContent = '';
        errorContainer.style.display = 'none';
    }, 3000);
}

function showSuccessMessageRequest(message) {
    const successContainer = document.getElementById('success-container-request');
    const successMessageElement = document.getElementById('success-message-request');
    successMessageElement.textContent = message;
    successContainer.style.display = 'flex';
    setTimeout(() => {
        successMessageElement.textContent = '';
        successContainer.style.display = 'none';
    }, 3000);
}

function AddProductOrder(index, id, name, stock_quantity, value_product) {

    var tbody = document.querySelector('#items-list-order');
    var existingRow = document.getElementById('product-id-order');

    console.log(existingRow);

    if (existingRow) {
        let quantityOrderCell = document.querySelector('#product-quantity-order');
        let currentQuantity = parseFloat(quantityOrderCell.textContent);
        console.log(quantityOrderCell, currentQuantity);
        quantityOrderCell.textContent = currentQuantity + 1;
    } else {

        var newRow = document.createElement('tr');
        newRow.id = `product-${id}`;
        newRow.className = 'tr-order';

        newRow.innerHTML = "<td id='product-name'>" + name + "</td>" +
            "<td id='product-quantity-" + id + "'>" + 1 + "</td>" +
            "<td id='value" + id + "'>" + "R$" + value_product + " </td>";

        tbody.appendChild(newRow);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const tableSelected = [];
    let totalValorSeletected = 0;

    const totalizador = document.getElementById('totalizador');

    document.querySelectorAll('.table-gathers').forEach(table => {
        table.addEventListener('click', function() {
            const tableIndex = table.dataset.index;
            const ValueTable = parseFloat(table.dataset.valor);

            if (!tableSelected.includes(tableIndex)) {
                tableSelected.push(tableIndex);
                exibirtableSelecteds(table);
                totalValorSeletected += ValueTable;
            } else {
                tableSelected.splice(tableSelected.indexOf(tableIndex), 1);
                removertableSelected(tableIndex);
                totalValorSeletected -= ValueTable;
            }

            updateTotalizador();
            console.log(updateTotalizador());
        });
    });

    function exibirtableSelecteds(table) {
        table.classList.add('selecionada');
        const tableSelecionada = document.createElement('div');
        tableSelected.textContent = table.textContent;
        tableSelecionada.dataset.id = table.dataset.id;
        document.querySelector('.table-gathers-selected').appendChild(tableSelecionada);
    }

    function removertableSelected(tableIndex) {
        const tableSelecionada = document.querySelector('.table-gathers-selected [data-index="' + tableIndex + '"]');
        tableSelecionada.remove();
    }

    function updateTotalizador() {
        if (totalizador) {
            totalValorSeletected = Math.max(totalValorSeletected, 0);
            totalizador.textContent = totalValorSeletected.toFixed(2);
        }
    }

    async function GathersTables() {
        const RequestDataGathers = {
            tables: tableSelected
        }

        console.log(RequestDataGathers);

        try {

            const RequestTables = await fetch('http://localhost/Klitzke/ajax/gathers_tables.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(RequestDataGathers),
            });
            const responseTablesBody = await RequestTables.text();
            console.log('Response from server:', responseTablesBody);
            const responseTables = JSON.parse(responseTablesBody);

            if (responseTables && responseTables.success) {
                showSuccessMessage('Venda finalizada com sucesso!');
            } else {
                console.error('Erro ao registrar venda:', responseTables ? responseTables.error : 'Resposta vazia');
            }
        } catch (error) {
            console.error('Erro ao enviar dados para o PHP:', error);
        }
    }

    document.querySelector('.button-gathers').addEventListener('click', GathersTables);
});

document.querySelector('.button-request').addEventListener('click', updatePedido, calculateTotal);
document.querySelector('.invoice-request').addEventListener('click', generetorRequest);