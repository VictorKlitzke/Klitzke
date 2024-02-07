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

function InfoSales(index, users, clients, form_payment, status_sales, quantity, products, total_value, date_sales) {

    var user_sales = document.getElementById("users-sales");
    var client_sales = document.getElementById("clients-sales");
    var form_payment_sales = document.getElementById("form-payment-sales");
    var status_sales = document.getElementById("status-sales");
    var products_sales = document.getElementById("products-sales");
    var quantity_sales = document.getElementById("quantity-sales");
    var total_sales = document.getElementById("total-sales");
    var date_sales = document.getElementById("date-sales");

    if (ProductInfoSales && overlayHome && user_sales && client_sales && form_payment_sales && products_sales && quantity_sales && date_sales && total_sales) {
        if ((ProductInfoSales.style.display = "none")) {
            ProductInfoSales.style.display = "block";
            overlayHome.style.display = "block";
            ProductInfoSales.style.transition = "transform 0.9s";

            user_sales.innerHTML = "<p>" + users + "</p>";
            client_sales.innerHTML = "<p>" + clients + "</p>";
            form_payment_sales.innerHTML = "<p>" + form_payment + "</p>";
            status_sales.innerHTML = "<p>" + status_sales + "</p>";
            quantity_sales.innerHTML = "<p>" + quantity + "</p>";
            products_sales.innerHTML = "<p>" + products + "</p>";
            total_sales.innerHTML = "<p>" + total_value + "</p>";
            date_sales.innerHTML = "<p>" + date_sales + "</p>";

            console.log(users, clients);
        }
    }
}