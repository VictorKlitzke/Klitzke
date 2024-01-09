const menuBtn = document.querySelector('.menu-btn');
const menu = document.querySelector('.menu');

document.addEventListener("DOMContentLoaded", function () {
    menuBtn.addEventListener("click", async (e) => {
        if ((CloseBoxpdv.style.display = "block")) {
            CloseBoxpdv.style.display = "none";
            CloseBoxpdv.style.transition = "transform 0.9s";
        }
    });

    console.log(menuBtn);

    menuBtn.addEventListener('click', function () {
        menu.classList.toggle('show-menu');
    });

    const menuItems = document.querySelectorAll('.items-menu a');
    menuItems.forEach(function (item) {
        item.addEventListener('click', function () {
            menu.classList.remove('menu-btn');
        });
    });

    const overlay = document.querySelector('.overlay');
    overlay.addEventListener('click', function () {
        menu.classList.remove('menu-btn');
    });

    const closeBoxpdv = document.querySelector('#close-boxpdv-modal');
    closeBoxpdv.addEventListener('click', function () {
        menu.classList.remove('show-menu');
    });
});