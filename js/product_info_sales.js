
const ProductInfoSales = document.getElementById('product-info-sales');
const CloseModalProductSales = document.getElementById('product-info-modal');

const overlayHome = document.getElementById('overlay-home');

console.log(ProductInfoSales);

Details.addEventListener("click", async (e) => {

    if (ProductInfoSales.style.display === "none") {
        ProductInfoSales.style.display = "block";
        overlayHome.style.display = "block";
        ProductInfoSales.style.transition = "transform 0.9s";

    }
});

CloseModalProductSales.addEventListener("click", async (e) => {
    if ((ProductInfoSales.style.display = "block")) {
        ProductInfoSales.style.display = "none";
        overlayHome.style.display = "none";
        ProductInfoSales.style.transition = "transform 0.9s";
    }
});

function InfoSales(index, user, client, form_payment, product, quantity, value, value_total) {

    var Details = document.getElementById('details-' + index);
    let user = document.getElementById('user');
    let client = document.getElementById('client');
    let form_payment = document.getElementById('form_payment');
    let product = document.getElementById('product');
    let quantity = document.getElementById('quantity');
    let value = document.getElementById('value');
    let value_total = document.getElementById('value_total');

    if ((ProductInfoSales.style.display = "none")) {
        ProductInfoSales.style.display = "block";
        overlayHome.style.display = "block";
        ProductInfoSales.style.transition = "transform 0.9s";
        user.innerHTML = "'<span id='user'> '" + user + "' </span>'";
        client.innerHTML = "'<span id='client'> '" + client + "' </span>'";
        form_payment.innerHTML = "'<span id='form_payment'> '" + form_payment + "' </span>'";
        product.innerHTML = "'<span id='product'> '" + product + "' </span>'";
        quantity.innerHTML = "'<span id='quantity'> '" + quantity + "' </span>'";
        value.innerHTML = "'<span id='value'> '" + value + "' </span>'";
        value_total.innerHTML = "'<span id='value_total'> '" + value_total + "' </span>'";
    }
}
