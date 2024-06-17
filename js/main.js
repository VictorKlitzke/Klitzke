const OpenBoxpdv = document.getElementById('open-boxpdv');
const CloseBoxpdv = document.getElementById('close-boxpdv');
const CloseModalBoxPdv = document.getElementById('close-boxpdv-modal');
const overlay = document.getElementById("overlay");

OpenBoxpdv.addEventListener("click", async (e) => {
  if ((CloseBoxpdv.style.display = "none")) {
    CloseBoxpdv.style.display = "block";
    overlay.style.display = "block";
    CloseBoxpdv.style.transition = "transform 0.9s";
  }
});

CloseModalBoxPdv.addEventListener("click", async (e) => {
  if ((CloseBoxpdv.style.display = "block")) {
    CloseBoxpdv.style.display = "none";
    overlay.style.display = "none";
    CloseBoxpdv.style.transition = "transform 0.9s";
  }
});

document.getElementById("cnpj").addEventListener("input", function (e) {
  let input = e.target;
  let value = input.value.replace(/\D/g, "");

  if (value.length > 14) {
    value = value.slice(0, 14);
  }

  input.value = formmatecnpj(value);
});

document.getElementById("cpf").addEventListener("input", function (e) {
  let input = e.target;
  let value = input.value.replace(/\D/g, "");

  if (value.length > 11) {
    value = value.slice(0, 11);
  }

  input.value = formmatecpf(value);
});

function formmatecnpj(value) {
  return value.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/, "$1.$2.$3/$4-$5");
}

function formmatecpf(value) {
  return value.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, "$1.$2.$3-$4");
}

async function closeBox() {

  let valueDebit = parseFloat(document.getElementById('value_debit').value);
  let valueCredit = parseFloat(document.getElementById('value_credit').value);
  let valuePIX = parseFloat(document.getElementById('value_pix').value);
  let valueMoney = parseFloat(document.getElementById('value_money').value);
  let closeDate = document.getElementById('date_close').value;

  let confirmClose = confirm('Deseja realmente fechar o caixa?');

  if (confirmClose) {
    fetch('http://localhost/Klitzke/ajax/close_boxpdv.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        value_debit: valueDebit,
        value_credit: valueCredit,
        value_pix: valuePIX,
        value_money: valueMoney,
        close_date: closeDate
      }),
    })
      .then(response => response.json())
      .then(data => {
        if (data && data.success) {
          window.alert('Caixa fechado com sucesso.');
          CloseBoxpdv.style.display = 'none';
          overlay.style.display = 'none';
          window.location.reload();
        } else {
          window.alert('Erro ao fechar o caixa. Tente novamente.');
        }
      })
      .catch(error => {
        console.error('Erro ao fechar o caixa:', error);
        console.log('Erro ao fechar o caixa. Tente novamente.');
      });
  }
}

function UploadXML() {
  const InputXML = document.getElementById("xmlFile");
  const file = InputXML.files[0];

  if (!file) {
    window.alert("Por favor, adicione um arquivo xml");
    return;
  }

  const reader = new FileReader();
  reader.onload = function(event) {
    const xmlContent = event.target.result;

    const url = `${BASE_CLASS}XMLFile.php`;

    const response = fetch(url, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({ xmlData: xmlContent }),
    })
        .then(response => response.text())
            console.log(response)
        .then(data => {
          if (data.success) {
            ViewProducts(data.products);
          } else {
            window.alert("Erro ao mostrar os itens da NF-e: " + data.message);
          }
        })
        .catch(error => {
          window.alert("Erro ao ler XML, contate o suporte: " + error);
        });
  };

  reader.readAsText(file);
}

const xmlData = `
<?xml version="1.0" encoding="UTF-8"?>
<nfeProc xmlns="http://www.portalfiscal.inf.br/nfe" versao="4.00">
    <NFe>
        <infNFe>
            <det nItem="1">
                <prod>
                    <cProd>123456</cProd>
                    <cEAN>7891234567895</cEAN>
                    <xProd>Produto A</xProd>
                    <NCM>12345678</NCM>
                    <CFOP>5102</CFOP>
                    <uCom>UN</uCom>
                    <qCom>10.0000</qCom>
                    <vUnCom>5.00</vUnCom>
                    <vProd>50.00</vProd>
                </prod>
            </det>
            <det nItem="2">
                <prod>
                    <cProd>789012</cProd>
                    <cEAN>7891234567896</cEAN>
                    <xProd>Produto B</xProd>
                    <NCM>87654321</NCM>
                    <CFOP>5102</CFOP>
                    <uCom>UN</uCom>
                    <qCom>5.0000</uCom>
                    <vUnCom>10.00</vUnCom>
                    <vProd>50.00</vProd>
                </prod>
            </det>
        </infNFe>
    </NFe>
</nfeProc>
`;

function ViewProducts(products) {
  const productsContainer = document.getElementById('xml-product');
  productsContainer.innerHTML = '';

  products.forEach(product => {
    const productDiv = document.createElement('div');
    productDiv.className = 'product';
    productDiv.innerHTML = `
                <strong>Nome:</strong> ${product.name}<br>
                <strong>Quantidade:</strong> ${product.quantity}<br>
                <strong>Preço:</strong> R$ ${product.price}
            `;
    productsContainer.appendChild(productDiv);
  });
}
