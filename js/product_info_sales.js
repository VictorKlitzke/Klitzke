const ProductInfoSales = document.getElementById('product-info-sales');
const CloseModalProductSales = document.getElementById('product-info-modal');

const overlayHome = document.getElementById('overlay-home');

// Details.addEventListener("click", async(e) => {

//     if (ProductInfoSales.style.display === "none") {
//         ProductInfoSales.style.display = "block";
//         overlayHome.style.display = "block";
//         ProductInfoSales.style.transition = "transform 0.9s";

//     }
// });

// CloseModalProductSales.addEventListener("click", async(e) => {
//     if ((ProductInfoSales.style.display = "block")) {
//         ProductInfoSales.style.display = "none";
//         overlayHome.style.display = "none";
//         ProductInfoSales.style.transition = "transform 0.9s";
//     }
// });

function InfoSales(index, users, clients, form_payment, status_sales, total_value, date_sales) {

    var user_sales = document.getElementById("users-sales");
    var client_sales = document.getElementById("clients-sales");
    var form_payment_sales = document.getElementById("form-payment-sales");
    var product_sales = document.getElementById("status-sales");
    var quantity_sales = document.getElementById("quantity-sales");
    var value_sales = document.getElementById("value-sales");
    var value_total_sales = document.getElementById("total-value-sales");

    if (ProductInfoSales && overlayHome && user_sales && client_sales && form_payment_sales && product_sales && quantity_sales && value_sales && value_total_sales) {
        if ((ProductInfoSales.style.display = "none")) {
            ProductInfoSales.style.display = "block";
            overlayHome.style.display = "block";
            ProductInfoSales.style.transition = "transform 0.9s";

            user_sales.innerHTML = "<span>" + users + "</span>";
            client_sales.innerHTML = "<span>" + clients + "</span>";
            form_payment_sales.innerHTML = "<span>" + form_payment + "</span>";
            product_sales.innerHTML = "<span>" + status_sales + "</span>";
            quantity_sales.innerHTML = "<span>" + date_sales + "</span>";
            value_sales.innerHTML = "<span>" + total_value + "</span>";
            value_total_sales.innerHTML = "<span id='total_value'>" + date_sales + "</span>";

            console.log(users, clients);
        }
    }
}