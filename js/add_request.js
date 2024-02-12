document.addEventListener('DOMContentLoaded', function() {

    let productSearch = document.getElementById('product-search');
    let productResult = document.getElementById('product-result');
    let productID = document.getElementById('product-id');
    let productName = document.getElementById('product-name');
    let productstock_quantity = document.getElementById('product-stock_quantity');
    let value_product = document.getElementById('product-value');

    productSearch.addEventListener('input', function() {

        let searchQuery = productSearch.value;
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
            let productStock_quantitys = selectedProduct.getAttribute('data-stock_quantity');
            let productValue_product = selectedProduct.getAttribute('data-value_product');

            if (productID) {
                productID.value = productId;
            }

            if (productName) {
                productName.value = productNames;
            }

            if (productstock_quantity) {
                productstock_quantity.value = productStock_quantitys;
            }
            if (value_product) {
                value_product.value = productValue_product;
            }

            console.log(productValue_product)

            productResult.innerHTML = '';
            // productResult.style.display = 'none';
        }
    });
});

document.addEventListener('DOMContentLoaded', function() {

    let productListTableBody = document.querySelector('.list tbody');

    addButton.addEventListener('click', function() {

        let productID = productID.value;
        let productName = productName.value;
        let productStockQuantity = productstock_quantity.value;

        let newRow = document.createElement('tr');
        newRow.innerHTML = `
        <td>${productID}</td>
        <td>${productName}</td>
        <td>${productStockQuantity}</td>
    `;

        productListTableBody.appendChild(newRow);

        productID.value = '';
        productName.value = '';
        productstock_quantity.value = '';
        updateTotalValue();
    });

    function updateTotalValue() {
        let totalValue = 10.00;
        productValueP.textContent = `R$ ${totalValue.toFixed(2)}`;
    }
});