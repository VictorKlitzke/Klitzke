let selectedRequest = [];

document.addEventListener('DOMContentLoaded', function() {

    let productSRequestearch = document.getElementById('product-request-search');
    let productResult = document.getElementById('product-result-request');
    let productID = document.getElementById('product-id');
    let productName = document.getElementById('product-name');
    let productsRequesttock_quantity = document.getElementById('product-stock_quantity');
    let value_product = document.getElementById('product-value');

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

    productResult.addEventListener('click', function(event) {
        if (event.target.tagName === 'LI') {
            let selectedProduct = event.target;
            let productId = selectedProduct.getAttribute('data-id');
            let productNames = selectedProduct.getAttribute('data-name');
            let productSRequesttock_quantity = selectedProduct.getAttribute('data-stock_quantity');
            let productValue_product = selectedProduct.getAttribute('data-value_product');

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

            console.log(productValue_product)

            productResult.innerHTML = '';
        }
    });
});

function updatePedido() {
    var requestID = document.getElementById('product-id').value;
    var requestName = document.getElementById('product-name').value;
    var requestQuantity = document.getElementById('product-stock_quantity').value;
    var requestValue = document.getElementById('product-value').value;

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
            var Actions = newRow.insertCell(4);

            Name.style.minWidth = "100%";

            ID_.textContent = requestID;
            Name.textContent = requestName;
            Quantity.textContent = requestQuantity;
            Value.textContent = requestValue;

            Quantity.classList.add('quantity-cell');

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
                stock_quantity: parseInt(requestQuantity)
            });
        }

        document.getElementById('product-id').value = "";
        document.getElementById('product-name').value = "";
        document.getElementById('product-stock_quantity').value = "";
        document.getElementById('product-value').value = "";
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

            var productIndex = selectedRequest.findIndex(product => product.id == productId);
            if (productIndex !== -1) {
                selectedRequest.splice(productIndex, 1);
            }
        }
    } else {
        console.error("Célula de quantidade não encontrada.");
    }
    calculateTotal();
}

function calculateTotal() {
    let total = 0;
    let productsRequest = document.querySelectorAll('.quantity-cell');

    productsRequest.forEach(product => {
        let quantity = parseInt(product.textContent) || 0;
        let valueElement = product.parentElement.nextElementSibling.querySelector('.value');

        if (valueElement) {
            let value = parseFloat(valueElement.textContent) || 0;
            total += quantity * value;
        } else {
            console.error('Elemento de valor não encontrado.');
        }
    });

    let totalAmountElementRequest = document.getElementById('totalizador-request');
    if (totalAmountElementRequest) {
        totalAmountElementRequest.textContent = 'R$ ' + total.toFixed(2);
    }

    return total.toFixed(2);
}

document.querySelector('.button-request').addEventListener('click', updatePedido);