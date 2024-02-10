document.addEventListener('DOMContentLoaded', function() {

    let productSearch = document.getElementById('product-search');
    let productResult = document.getElementById('product-result');
    let productID = document.getElementById('product-id');
    let productName = document.getElementById('product-name');
    let productstock_quantity = document.getElementById('product-stock_quantity');

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
            let productstock_quantitys = selectedProduct.getAttribute('data-stock_quantity');

            if (productID) {
                productID.value = productId;
            }

            if (productName) {
                productName.value = productNames;
            }

            if (productstock_quantity) {
                productstock_quantity.value = productstock_quantitys;
            }

            productResult.innerHTML = '';
            productResult.style.display = 'none';
        }
    });
});