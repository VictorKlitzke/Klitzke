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

  if (!valueCredit || !valueDebit || !valuePIX || !valueMoney || !closeDate) {
    console.log('Por favor, preencha todos os campos.');
    return;
  }

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
          console.log('Caixa fechado com sucesso.');
        } else {
          console.log('Erro ao fechar o caixa. Tente novamente.');
        }
      })
      .catch(error => {
        console.error('Erro ao fechar o caixa:', error);
        console.log('Erro ao fechar o caixa. Tente novamente.');
      });
  }
} 
