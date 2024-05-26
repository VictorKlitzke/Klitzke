const menuBtn = document.querySelector('.menu-btn');
const menu = document.querySelector('.menu');

document.addEventListener("DOMContentLoaded", function() {
    menuBtn.addEventListener("click", async(e) => {
        if ((CloseBoxpdv.style.display = "block")) {
            CloseBoxpdv.style.display = "none";
            CloseBoxpdv.style.transition = "transform 0.9s";
        }
    });

    menuBtn.addEventListener('click', function() {
        menu.classList.toggle('show-menu');
    });

    const menuItems = document.querySelectorAll('.items-menu a');
    menuItems.forEach(function(item) {
        item.addEventListener('click', function() {
            menu.classList.remove('menu-btn');
        });
    });

    const overlay = document.querySelector('.overlay');
    overlay.addEventListener('click', function() {
        menu.classList.remove('menu-btn');
    });

    const closeBoxpdv = document.querySelector('#close-boxpdv-modal');
    closeBoxpdv.addEventListener('click', function() {
        menu.classList.remove('show-menu');
    });
});

function ToggleRegister() {
    let subMenuRegister = document.getElementById('registers');
    subMenuRegister.style.display = subMenuRegister.style.display === 'none' ? 'block' : 'none';
    subMenuRegister.style.padding = '5px';
}

function ToggleDelivery() {
    let subMenuDelivery = document.getElementById('delivery');
    subMenuDelivery.style.display = subMenuDelivery.style.display === 'none' ? 'block' : 'none';
    subMenuDelivery.style.padding = '5px';
}

function ToggleLists() {
    let subMenuList = document.getElementById('list-registers');
    subMenuList.style.display = subMenuList.style.display === 'none' ? 'block' : 'none';
    subMenuList.style.padding = '5px';
}

function ToggleInvoicing() {
    let subMenu = document.getElementById('invoicing');
    subMenu.style.display = subMenu.style.display === 'none' ? 'block' : 'none';
    subMenu.style.padding = '5px';
}

function ToggleReport() {
    let subMenuReport = document.getElementById('report');
    subMenuReport.style.display = subMenuReport.style.display === 'none' ? 'block' : 'none';
    subMenuReport.style.padding = '5px';
}

function ToggleCompany() {
    let subMenuCompany = document.getElementById('company');
    subMenuCompany.style.display = subMenuCompany.style.display === 'none' ? 'block' : 'none';
    subMenuCompany.style.padding = '5px';
}