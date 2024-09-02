let selectedFinacialControl = [];
let selectedPagamentalControl = [];

async function FinalizeAPrazo() {
  document.querySelectorAll('#table-financial-control-detals input[type="checkbox"]:checked').forEach(checkbox => {
    selectedFinacialControl.push(checkbox.value);

    const row = checkbox.closest('tr');
    const value_aprazo = row.querySelector('th:nth-child(4)').textContent;
    const dateVenciment = row.querySelector('td:nth-child(3)').textContent;

    selectedPagamentalControl.push({
      dateVenciment: dateVenciment,
      value_aprazo: value_aprazo,
      id_aprazo: checkbox.value
    });
  });

  if (selectedFinacialControl.length === 0) {
    showMessage('Nenhuma parcela selecionada', 'warning');
    return;
  }

  let responseControl = {
    type: 'registerAccountsReceivable',
    selectedFinacialControl: selectedFinacialControl,
    selectedPagamentalControl: selectedPagamentalControl
  }

  continueMessage("Deseja relamente baixar as parcelas?", "Sim", "Não", async function () {

    try {

      let url = `${BASE_CONTROLLERS}registers.php`;

      const response = await fetch(url, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(responseControl)
      });

      const responseBody = await response.json();

      if (responseBody.success) {
        showMessage('Baixa no contas a receber com sucesso ', 'success');

        setTimeout(() => {
          location.reload();
        }, 2000);

      } else {
        showMessage(responseBody.message || 'Erro ao dar baixa no contas a receber ' + responseBody.error, 'error');
      }

    } catch (error) {
      showMessage('Erro na requisição' + error.message, 'error')
    }

  }, function () {
    showMessage('Registro cancelado', 'warning')
  })
}

const getFieldsAccountsPayable = () => {
  return {
    type: {
      type: 'AccountsPayable',
    },
    values: {
      dateTransaction: document.getElementById('dateTransaction').value,
      valueTransaction: document.getElementById('valueTransaction').value,
      nameExterno: document.getElementById('nameExterno').value,
      descriptionTransaction: document.getElementById('descriptionTransaction').value,
    },
    inputs: {
      dateTransaction: document.getElementById('dateTransaction'),
      valueTransaction: document.getElementById('valueTransaction'),
      nameExterno: document.getElementById('nameExterno'),
      descriptionTransaction: document.getElementById('descriptionTransaction'),
    }
  }
}
async function RegisterAccountsPayable() {
  const { type, values, inputs } = await getFieldsAccountsPayable();

  if (values.dateTransaction == null || values.valueTransaction == "" || values.descriptionTransaction == "") {
    showMessage('Campo não podem ser vazios', 'warning');

    if (values.dateTransaction === "") inputs.valueTransaction.classList.add('error');
    if (values.valueTransaction === "") inputs.valueTransaction.classList.add('error');
    if (values.descriptionTransaction === "") inputs.descriptionTransaction.classList.add('error');
    setTimeout(() => {
      inputs.dateTransaction.classList.remove('error');
      inputs.valueTransaction.classList.remove('error');
      inputs.descriptionTransaction.classList.remove('error');
    }, 3000);

    return;
  }


  let responseAccountPayable = {
    type: type.type,
    dateTransaction: values.dateTransaction,
    valueTransaction: values.valueTransaction,
    descriptionTransaction: values.descriptionTransaction,
    nameExterno: values.nameExterno
  }

  console.log(responseAccountPayable);

  continueMessage("Deseja realmente cadastrar contas a pagar?", "Sim", "Não", async function () {

    try {

      let url = `${BASE_CONTROLLERS}registers.php`;

      const response = await fetch(url, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(responseAccountPayable)
      });

      const responseBody = await response.json();

      if (responseBody.success) {
        showMessage('Contas a pagar cadastrada com sucesso', 'success');

        setTimeout(() => {
          location.reload();
        }, 2000);

      } else {
        showMessage(responseBody.message || 'Erro ao cadastrar contas a pagar ' + responseBody.error, 'error');
      }

    } catch (error) {
      showMessage('Erro na requisição' + error.message, 'error');
    }

  }, function () {
    showMessage('Operação cancelada', 'warning');
  })

}