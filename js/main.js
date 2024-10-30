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

  let valueDebit = parseFloat(document.getElementById('value_debit').value);
  let valueCredit = parseFloat(document.getElementById('value_credit').value);
  let valuePIX = parseFloat(document.getElementById('value_pix').value);
  let valueMoney = parseFloat(document.getElementById('value_money').value);
  let value_aprazo = parseFloat(document.getElementById('value_aprazo').value);
  let value_difference = parseFloat(document.getElementById('value_difference').value);
  let value_fisico = parseFloat(document.getElementById('value_fisico').value);
  let value_system = parseFloat(document.getElementById('value_system').value);
  let close_date = document.getElementById('date_close').value;
  let soma = document.getElementById('soma').value;
  let TotalizadorBox = document.getElementById('TotalizadorBox').value;

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
    TotalizadorBox: TotalizadorBox
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
            <p><strong>Débito:</strong> R$ ${valueDebit.toFixed(2)}</p>
            <p><strong>Crédito:</strong> R$ ${valueCredit.toFixed(2)}</p>
            <p><strong>PIX:</strong> R$ ${valuePIX.toFixed(2)}</p>
            <p><strong>Dinheiro:</strong> R$ ${valueMoney.toFixed(2)}</p>
            <p><strong>A Prazo:</strong> R$ ${value_aprazo.toFixed(2)}</p>
            <p><strong>Total do Sistema:</strong> R$ ${value_system.toFixed(2)}</p>
            <p><strong>Total Físico:</strong> R$ ${value_fisico.toFixed(2)}</p>
            <p><strong>Diferença:</strong> R$ ${value_difference.toFixed(2)}</p>
            <p><strong>Diferença:</strong> R$ ${TotalizadorBox.toFixed(2)}</p>
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
  let systemValue = parseFloat(document.getElementById('value_system').value) || 0;
  let fisicoValue = parseFloat(document.getElementById('value_fisico').value) || 0;
  let difference = systemValue - fisicoValue;

  document.getElementById('value_difference').value = difference.toFixed(2);
}

function calculateMoneySystem() {
  let value_system = parseFloat(document.getElementById('value_system').value) || 0;
  let value_money = parseFloat(document.getElementById('value_money').value) || 0;

  let soma = value_system + value_money;

  const somaField = document.getElementById('soma');
  if (somaField) {
    somaField.value = soma.toFixed(2);
  }
}

function calculateTotalizadoAll() {
  const values = [
    parseFloat(document.getElementById('value_debit').value) || 0,
    parseFloat(document.getElementById('value_credit').value) || 0,
    parseFloat(document.getElementById('value_pix').value) || 0,
    parseFloat(document.getElementById('value_money').value) || 0,
    parseFloat(document.getElementById('value_aprazo').value) || 0,
    parseFloat(document.getElementById('value_system').value) || 0,
  ];

  const totalAll = values.reduce((accumulator, currentValue) => accumulator + currentValue, 0);

  const TotalizadoBox = document.getElementById('TotalizadorBox');
  if (TotalizadoBox) {
    TotalizadoBox.value = numberFormat(totalAll); 
  }
}
