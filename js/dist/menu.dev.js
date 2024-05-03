"use strict";

var menuBtn = document.querySelector('.menu-btn');
var menu = document.querySelector('.menu');
document.addEventListener("DOMContentLoaded", function () {
  menuBtn.addEventListener("click", function _callee(e) {
    return regeneratorRuntime.async(function _callee$(_context) {
      while (1) {
        switch (_context.prev = _context.next) {
          case 0:
            if (CloseBoxpdv.style.display = "block") {
              CloseBoxpdv.style.display = "none";
              CloseBoxpdv.style.transition = "transform 0.9s";
            }

          case 1:
          case "end":
            return _context.stop();
        }
      }
    });
  });
  menuBtn.addEventListener('click', function () {
    menu.classList.toggle('show-menu');
  });
  var menuItems = document.querySelectorAll('.items-menu a');
  menuItems.forEach(function (item) {
    item.addEventListener('click', function () {
      menu.classList.remove('menu-btn');
    });
  });
  var overlay = document.querySelector('.overlay');
  overlay.addEventListener('click', function () {
    menu.classList.remove('menu-btn');
  });
  var closeBoxpdv = document.querySelector('#close-boxpdv-modal');
  closeBoxpdv.addEventListener('click', function () {
    menu.classList.remove('show-menu');
  });
});

function ToggleRegister() {
  var subMenuRegister = document.getElementById('registers');
  subMenuRegister.style.display = subMenuRegister.style.display === 'none' ? 'block' : 'none';
  subMenuRegister.style.padding = '5px';
}

function ToggleDelivery() {
  var subMenuDelivery = document.getElementById('delivery');
  subMenuDelivery.style.display = subMenuDelivery.style.display === 'none' ? 'block' : 'none';
  subMenuDelivery.style.padding = '5px';
}

function ToggleLists() {
  var subMenuList = document.getElementById('list-registers');
  subMenuList.style.display = subMenuList.style.display === 'none' ? 'block' : 'none';
  subMenuList.style.padding = '5px';
}

function ToggleInvoicing() {
  var subMenu = document.getElementById('invoicing');
  subMenu.style.display = subMenu.style.display === 'none' ? 'block' : 'none';
  subMenu.style.padding = '5px';
}

function ToggleReport() {
  var subMenuReport = document.getElementById('report');
  subMenuReport.style.display = subMenuReport.style.display === 'none' ? 'block' : 'none';
  subMenuReport.style.padding = '5px';
}

function loadPage(url) {
  var content = document.getElementById('content');
  var xhr = new XMLHttpRequest();

  xhr.onreadystatechange = function () {
    if (xhr.readyState == 4 && xhr.status == 200) {
      content.innerHTML = xhr.responseText;
    }
  };

  xhr.open('GET', url, true);
  xhr.send();
}