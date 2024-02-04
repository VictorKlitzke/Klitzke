const ProductInfoSales = document.getElementById('product-info-sales');
const CloseModalProductSales = document.getElementById('product-info-modal');

const overlayHome = document.getElementById('overlay-home');

Details.addEventListener("click", async(e) => {

    if (ProductInfoSales.style.display === "none") {
        ProductInfoSales.style.display = "block";
        overlayHome.style.display = "block";
        ProductInfoSales.style.transition = "transform 0.9s";

    }
});

CloseModalProductSales.addEventListener("click", async(e) => {
    if ((ProductInfoSales.style.display = "block")) {
        ProductInfoSales.style.display = "none";
        overlayHome.style.display = "none";
        ProductInfoSales.style.transition = "transform 0.9s";
    }
});

function InfoSales(index, id, users, clients, form_payment, products, quantity, value, value_total) {

    var Details = document.getElementById('details-' + index);
    let id_sales = document.getElementById('id');
    let user_sales = document.getElementById('user');
    let client_sales = document.getElementById('client');
    let form_payment_sales = document.getElementById('form_payment');
    let product_sales = document.getElementById('product');
    let quantity_sales = document.getElementById('quantity');
    let value_sales = document.getElementById('value');
    let value_total_sales = document.getElementById('value_total');

    if ((ProductInfoSales.style.display = "none")) {
        ProductInfoSales.style.display = "block";
        overlayHome.style.display = "block";
        ProductInfoSales.style.transition = "transform 0.9s";
        id_sales.innerHTML = "<input type='hidden' name='id_process' value='" + id + "'>";
        user_sales.innerHTML = "'<span id='user'> '" + users + "' </span>'";
        client_sales.innerHTML = "'<span id='client'> '" + clients + "' </span>'";
        form_payment_sales.innerHTML = "'<span id='form_payment'> '" + form_payment + "' </span>'";
        product_sales.innerHTML = "'<span id='product'> '" + products + "' </span>'";
        quantity_sales.innerHTML = "'<span id='quantity'> '" + quantity + "' </span>'";
        value_sales.innerHTML = "'<span id='value'> '" + value + "' </span>'";
        value_total_sales.innerHTML = "'<span id='value_total'> '" + value_total + "' </span>'";

        console.log(id, users);
    }
}