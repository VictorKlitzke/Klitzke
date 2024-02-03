const menuBtn = document.querySelector('.menu-btn');
const menu = document.querySelector('.menu');

document.addEventListener("DOMContentLoaded", function() {
    menuBtn.addEventListener("click", async(e) => {
        if ((CloseBoxpdv.style.display = "block")) {
            CloseBoxpdv.style.display = "none";
            CloseBoxpdv.style.transition = "transform 0.9s";
        }
    });

    console.log(menuBtn);

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

function loadPage(url) {
    var content = document.getElementById('content');
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            content.innerHTML = xhr.responseText;
        }
    };
    xhr.open('GET', url, true);
    xhr.send();
}