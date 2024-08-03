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