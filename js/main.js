const OpenBoxpdv = document.getElementById('open-boxpdv');
const CloseBoxpdv = document.getElementById('close-boxpdv');
const CloseModalBoxPdv = document.getElementById('close-boxpdv-modal');
const overlay = document.getElementById("overlay");

document.addEventListener('DOMContentLoaded', () => {
  calculateMoneySystem();
  calculateTotalizadoAll();
});

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

function printBoxReport(content) {
  let printWindow = window.open('', '', 'height=600,width=800');
  printWindow.document.write('<html><head><title>Fechamento de Caixa</title>');
  printWindow.document.write('</head><body>');
  printWindow.document.write(content);
  printWindow.document.write('</body></html>');
  printWindow.document.close();
  printWindow.print();
}

async function closeBox() {

  let valueDebit = parseFloat(document.getElementById('value_debit').value.replace('R$', '').replace(',', '.')) || 0;
  let valueCredit = parseFloat(document.getElementById('value_credit').value.replace('R$', '').replace(',', '.')) || 0;
  let valuePIX = parseFloat(document.getElementById('value_pix').value.replace('R$', '').replace(',', '.')) || 0;
  let valueMoney = parseFloat(document.getElementById('value_money').value.replace('R$', '').replace(',', '.')) || 0;
  let value_aprazo = parseFloat(document.getElementById('value_aprazo').value.replace('R$', '').replace(',', '.')) || 0;
  let value_difference = parseFloat(document.getElementById('value_difference').value.replace('R$', '').replace(',', '.')) || 0;
  let value_fisico = parseFloat(document.getElementById('value_fisico').value.replace('R$', '').replace(',', '.')) || 0;
  let value_system = parseFloat(document.getElementById('value_system').value.replace('R$', '').replace(',', '.')) || 0;
  let close_date = parseFloat(document.getElementById('date_close').value.replace('R$', '').replace(',', '.')) || 0;
  let soma = parseFloat(document.getElementById('soma').value.replace('R$', '').replace(',', '.')) || 0;
  let TotalizadorBox = parseFloat(document.getElementById('TotalizadorBox').value.replace('R$', '').replace(',', '.')) || 0;
  let Change_sales = parseFloat(document.getElementById('change_sales').value.replace('R$', '').replace(',', '.').trim()) || 0;

  if (value_system == "" || value_fisico == "") {
    showMessage('Valor sistema ou valor fisico sem valor', 'warning');
    return;
  }

  let respondeBoxPdv = {
    value_debit: valueDebit,
    value_credit: valueCredit,
    value_pix: valuePIX,
    value_money: valueMoney,
    value_aprazo: value_aprazo,
    value_system: value_system,
    value_fisico: value_fisico,
    value_difference: value_difference,
    close_date: close_date,
    soma: soma,
    TotalizadorBox: TotalizadorBox,
    Change_sales: Change_sales
  }

  continueMessage("Deseja realmente fechar o caixa?", "Sim", "Não", async function () {

    try {
      let url = `${BASE_URL}close_boxpdv.php`;

      const response = await fetch(url, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(respondeBoxPdv)
      });

      const responseText = await response.text();

      console.log(responseText);

      try {
        const data = JSON.parse(responseText);

        if (data.success) {
          showMessage('Caixa fechado com sucesso', 'success');

          const printContent = `
            <h3>Resumo do Fechamento de Caixa</h3>
            <p><strong>Débito:</strong> R$ ${valueDebit}</p>
            <p><strong>Crédito:</strong> R$ ${valueCredit}</p>
            <p><strong>PIX:</strong> R$ ${valuePIX}</p>
            <p><strong>Dinheiro:</strong> R$ ${valueMoney}</p>
            <p><strong>A Prazo:</strong> R$ ${value_aprazo}</p>
            <p><strong>Total do Sistema:</strong> R$ ${value_system}</p>
            <p><strong>Total Físico:</strong> R$ ${value_fisico}</p>
            <p><strong>Diferença:</strong> R$ ${value_difference}</p>
            <p><strong>Diferença:</strong> R$ ${TotalizadorBox}</p>
          `;

          CloseBoxpdv.style.display = 'none';
          overlay.style.display = 'none';

          continueMessage("Deseja imprimir o relatório?", "Sim", "Não",
            async function () {
              printBoxReport(printContent);
              setTimeout(() => {
                window.location.reload();
              }, 1000);
            },
            function () {
              showMessage('Operação cancelada', 'warning');
              setTimeout(() => {
                window.location.reload();
              }, 3000);
            }
          );
        } else {
          showMessage('Erro ao fechar o caixa. Tente novamente.', 'error');
        }
      } catch (error) {
        showMessage('Erro inesperado no servidor.' + error.message, 'error');
      }

    } catch (error) {
      showMessage('Erro ao fechar o caixa. Tente novamente.', 'error');
    }
  }), function () {
    showMessage('Operação cancelada', 'error');
  }
}

function calculateDifference() {
  let systemValue = parseFloat(document.getElementById('value_system').value.replace('R$', '').replace(',', '.').trim()) || 0;
  let fisicoValue = parseFloat(document.getElementById('value_fisico').value.replace('R$', '').replace(',', '.').trim()) || 0;
  let difference = systemValue - fisicoValue;

  document.getElementById('value_difference').value = numberFormat(difference);
}

function calculateMoneySystem() {
  let value_system = parseFloat(document.getElementById('value_system').value.replace('R$', '').replace(',', '.').trim()) || 0;
  let value_money = parseFloat(document.getElementById('value_money').value.replace('R$', '').replace(',', '.').trim()) || 0;

  let soma = value_system + value_money;

  const somaField = document.getElementById('soma');
  if (somaField) {
    somaField.value = numberFormat(soma);
  }
}

function calculateTotalizadoAll() {
  const values = [
    parseFloat(document.getElementById('value_debit').value.replace('R$', '').replace(',', '.').trim()) || 0,
    parseFloat(document.getElementById('value_credit').value.replace('R$', '').replace(',', '.').trim()) || 0,
    parseFloat(document.getElementById('value_pix').value.replace('R$', '').replace(',', '.').trim()) || 0,
    parseFloat(document.getElementById('value_money').value.replace('R$', '').replace(',', '.').trim()) || 0,
    parseFloat(document.getElementById('value_aprazo').value.replace('R$', '').replace(',', '.').trim()) || 0,
    parseFloat(document.getElementById('value_system').value.replace('R$', '').replace(',', '.').trim()) || 0,
  ];

  const totalAll = values.reduce((accumulator, currentValue) => accumulator + currentValue, 0);

  const TotalizadoBox = document.getElementById('TotalizadorBox');
  if (TotalizadoBox) {
    TotalizadoBox.value = numberFormat(totalAll);
  }
}

function numberFormat(value) {
  return 'R$ ' + value.toFixed(2).replace('.', ',');
}
