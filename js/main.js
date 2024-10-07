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

async function closeBox() {

  let valueDebit = parseFloat(document.getElementById('value_debit').value);
  let valueCredit = parseFloat(document.getElementById('value_credit').value);
  let valuePIX = parseFloat(document.getElementById('value_pix').value);
  let valueMoney = parseFloat(document.getElementById('value_money').value);
  let value_aprazo = parseFloat(document.getElementById('value_aprazo').value);
  let closeDate = document.getElementById('date_close').value;

  let respondeBoxPdv = {
    value_debit: valueDebit,
    value_credit: valueCredit,
    value_pix: valuePIX,
    value_money: valueMoney,
    value_aprazo: value_aprazo,
    close_date: closeDate
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

      try {
        const data = JSON.parse(responseText);

        if (data.success) {
          showMessage('Caixa fechado com sucesso', 'success');
          CloseBoxpdv.style.display = 'none';
          overlay.style.display = 'none';

          setTimeout(() => {
            window.location.reload();
          }, 3000);
        } else {
          showMessage('Erro ao fechar o caixa. Tente novamente.', 'error');
        }
      } catch (jsonError) {
        console.error("Erro ao converter resposta para JSON:", jsonError);
        console.log("Resposta recebida:", responseText);
        showMessage('Erro inesperado no servidor. Verifique o console para detalhes.', 'error');
      }

    } catch (error) {
      console.error('Erro ao fechar o caixa:', error);
      showMessage('Erro ao fechar o caixa. Tente novamente.', 'error');
    }
  }), function () {
    showMessage('Operação cancelada', 'error');
  }
}
