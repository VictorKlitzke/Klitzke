const selectedProducts = [];

const trProduct = document.getElementById("product-result");
const tdButton = document.getElementById("button-product");

function AddSelectProducts(index, id, name, stock_quantity, value) {
    let productAlreadyExists = false;

    for (let i = 0; i < selectedProducts.length; i++) {
        if (selectedProducts[i].id === id) {
            let number = selectedProducts[i].stock_quantity;
            number++;

            if (validateStock(stock_quantity, number)) {
                selectedProducts[i].stock_quantity = number;
                let productQuantityCell = document.getElementById("product-quantity-" + id);
                if (productQuantityCell) {
                    productQuantityCell.textContent = number;
                }
            }

            updateProductQuantity(id, number);

            productAlreadyExists = true;
        }
    }

    if (!productAlreadyExists) {
        let newProduct = {
            id: id,
            name: name,
            stock_quantity: 1,
            value: value
        };

        validateStock(stock_quantity, 1);
        selectedProducts.push(newProduct);

        let newRow = trProduct.insertRow();
        newRow.id = "row-" + id;
        newRow.innerHTML = "<td id='product-id'>" + id + "</td>" +
            "<td id='product-name'>" + name + "</td>" +
            "<td id='product-quantity-" + id + "'>" + 1 + "</td>" +
            "<td id='product-value' class='content-form'>" +
            "<input type='text' id='value" + id + "' value='" + value + "' />" +
            "</td>" +
            "<td id='total-item'>" + id + "</td>" +
            "<td style='margin: 6px; padding: 6px;'>" +
            "<div>" +
            "<a class='btn-delete'>" +
            "<button onclick='removeProduct(" + id +")' id='button-delete-" + id + "' class='btn-delete' type='button'>Deletar</button></a>" +
            "</div>" +
            "</td>";
    }
}

async function removeProduct(id) {
    
    let rowToRemove = document.getElementById("row-" + id);
    if (rowToRemove) {
        rowToRemove.remove();
    }
    // selectedProducts = selectedProducts.filter(product => product.id !== id);

    console.log("Quantidade removida:");
}

async function finalizeSale() {
    let selectedPaymentMethod = document.getElementById('id_payment_method').value;
    let idSalesClient = document.getElementById("sales_id_client").value;

    let requestData = {
        id_payment_method: selectedPaymentMethod,
        sales_id_client: idSalesClient,
        products: selectedProducts
    };

    console.log(requestData);

    if (selectedProducts.length === 0) {
        showErrorMessage('Erro ao registrar venda, nenhum produto selecionado');
        return;
    } else {
        try {
            const response = await fetch('http://localhost/Klitzke/ajax/add_sales.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(requestData)
            });

        } catch (error) {
            console.error('Erro ao enviar dados para o PHP:', error);
        }
        showSuccessMessage('Venda finalizada com sucesso!')
    }
}


function showErrorMessage(message) {
    const errorContainer = document.getElementById('error-container');
    const errorMessageElement = document.getElementById('error-message');

    errorMessageElement.textContent = message;
    errorContainer.style.display = 'none';

    setTimeout(() => {
        errorMessageElement.textContent = '';
        errorContainer.style.display = 'flex';
    }, 3000);
}

function showSuccessMessage(message) {
    const sucesscontainer = document.getElementById('success-container');
    const successMessageElement = document.getElementById('success-message');

    successMessageElement.textContent = message;

    if (sucesscontainer.style.display === 'none') {

        sucesscontainer.style.display = 'flex';

    }setTimeout(() => {
        successMessageElement.textContent = '';

    }, 3000);
}

const finishButton = document.getElementById('finish-sales');

if (finishButton) {
    finishButton.onclick = finalizeSale;
}

function updateProductQuantity(id, stock_quantity) {

    for (let i = 0; i < selectedProducts.length; i++) {
        if (selectedProducts[i].id === id) {
            selectedProducts[i].stock_quantity = stock_quantity;
            break;
        }
    }
}

function addProductToArray(id, name, stock_quantity, value) {
    selectedProducts.push({ id, name, stock_quantity, value });
}

function validateStock(stock_quantity, qnt) {

    if (stock_quantity < qnt) {
        window.alert("Você não possui estoque suficiente!");
        return false;
    }
    return true;
}
