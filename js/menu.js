const menuBtn = document.querySelector('.menu-btn');
const menu = document.querySelector('.menu');
const header = document.getElementById('header');
const content = document.getElementById('content');

var open = true;
var windowSize = window.innerWidth;
var targetSizeMenu = (windowSize <= 400) ? 200 : 250;

document.addEventListener('DOMContentLoaded', function() {

if (windowSize <= 768) {
    menu.style.width = '0';
    menu.style.left = '0';
    content.style.width = '0';
    content.style.left = '0';
    open = false;
}

});

async function OpenMenu() {
    if (open) {
        menu.style.width = '0';
        menu.style.padding = '0';
        open = false;
        content.style.width = '100%';
        header.style.width = '100%';
        content.style.left = '0';
        header.style.left = '0';
    } else {
        menu.style.display = 'block';
        menu.style.width = targetSizeMenu + 'px';
        menu.style.padding = '10px 0';
        open = true;

        if (windowSize > 768) {
            content.style.width = 'calc(100% - 250px)';
            header.style.width = 'calc(100% - 250px)';
        }
        content.style.left = targetSizeMenu + 'px';
        header.style.left = targetSizeMenu + 'px';
    }
}

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

function ToggleNoteFiscal() {
    let subMenuFiscal = document.getElementById('fiscal');
    subMenuFiscal.style.display = subMenuFiscal.style.display === 'none' ? 'block' : 'none';
    subMenuFiscal.style.padding = '5px';
}