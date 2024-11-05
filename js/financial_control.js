let selectedFinacialControl = [];
let selectedPagamentalControl = [];

const AddVariationForn = document.getElementById('add-variation-forn');

async function finalizeAPrazoControl() {
  document.querySelectorAll('#table-financial-control-details input[type="checkbox"]:checked').forEach(checkbox => {
    selectedFinacialControl.push(checkbox.value);

    const row = checkbox.closest('tr');
    const dateVenciment = row.querySelector('td:nth-child(3)').textContent;
    const value_aprazo = row.querySelector('td:nth-child(4)').textContent;

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
      numberdoc: document.getElementById('numberdoc').value,
      transactionType: document.getElementById('transactionType').value,
      incomeExpense: document.getElementById('incomeExpense').value
    },
    inputs: {
      dateTransaction: document.getElementById('dateTransaction'),
      valueTransaction: document.getElementById('valueTransaction'),
      nameExterno: document.getElementById('nameExterno'),
      descriptionTransaction: document.getElementById('descriptionTransaction'),
      numberdoc: document.getElementById('numberdoc'),
      numberdoc: document.getElementById('transactionType'),
      incomeExpense: document.getElementById('incomeExpense')
    }
  }
}
async function RegisterAccountsPayable() {
  const { type, values, inputs } = await getFieldsAccountsPayable();

  if (values.dateTransaction == null || values.valueTransaction == "" ||
    values.descriptionTransaction == "" || values.transactionType == null ||
    values.incomeExpense == "") {
    showMessage('Campo não podem ser vazios', 'warning');

    if (values.dateTransaction === "") inputs.valueTransaction.classList.add('error');
    if (values.valueTransaction === "") inputs.valueTransaction.classList.add('error');
    if (values.descriptionTransaction === "") inputs.descriptionTransaction.classList.add('error');
    if (values.transactionType === null) inputs.descriptionTransaction.classList.add('error');
    if (values.incomeExpense === "") inputs.descriptionTransaction.classList.add('error');
    setTimeout(() => {
      inputs.dateTransaction.classList.remove('error');
      inputs.valueTransaction.classList.remove('error');
      inputs.descriptionTransaction.classList.remove('error');
      inputs.transactionType.classList.remove('error');
      inputs.incomeExpense.classList.remove('error');
    }, 3000);

    return;
  }


  let responseAccountPayable = {
    type: type.type,
    dateTransaction: values.dateTransaction,
    valueTransaction: values.valueTransaction,
    descriptionTransaction: values.descriptionTransaction,
    nameExterno: values.nameExterno,
    numberdoc: values.numberdoc,
    transactionType: values.transactionType,
    incomeExpense: values.incomeExpense
  }

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
function ShowModalAddVariation() {
  if (AddVariationForn.style.display === 'block') {
    AddVariationForn.style.display = 'none';
  } else {
    AddVariationForn.style.display = 'block';
  }
}
function SelectFinancial() {
  const categorys = document.getElementById('categorys').value;
  const StatusCategory = document.getElementById('status-category');

  console.log(categorys);

  if (categorys === 'contas a pagar') {
    StatusCategory.value = 'Despesa';
  } else if (categorys === 'contas a receber') {
    StatusCategory.value = 'Receita';
  }
}
function searchSales() {
  const input = document.getElementById('searchInput');
  const filter = input.value.toLowerCase();
  const table = document.getElementById('table-sales');
  const rows = table.getElementsByTagName('tr');

  for (let i = 1; i < rows.length; i++) {
    const cells = rows[i].getElementsByTagName('td');
    let match = false;

    for (let j = 0; j < cells.length; j++) {
      if (cells[j]) {
        const cellValue = cells[j].textContent || cells[j].innerText;
        if (cellValue.toLowerCase().indexOf(filter) > -1) {
          match = true;
          break;
        }
      }
    }
    rows[i].style.display = match ? "" : "none";
  }
}
function searchcontrol() {
  const input = document.getElementById('searchInputControl');
  const filter = input.value.toLowerCase();
  const table = document.getElementById('result-financial-control');
  const rows = table.getElementsByTagName('tr');

  for (let i = 1; i < rows.length; i++) {
    const cells = rows[i].getElementsByTagName('td');
    let match = false;

    for (let j = 0; j < cells.length; j++) {
      if (cells[j]) {
        const cellValue = cells[j].textContent || cells[j].innerText;
        if (cellValue.toLowerCase().indexOf(filter) > -1) {
          match = true;
          break;
        }
      }
    }
    rows[i].style.display = match ? "" : "none";
  }
}
function searchcontrolEntry() {
  const input = document.getElementById('searchcontrolEntry');
  const filter = input.value.toLowerCase();
  const table = document.getElementById('result-entry');
  const rows = table.getElementsByTagName('tr');

  for (let i = 1; i < rows.length; i++) {
    const cells = rows[i].getElementsByTagName('td');
    let match = false;

    for (let j = 0; j < cells.length; j++) {
      if (cells[j]) {
        const cellValue = cells[j].textContent || cells[j].innerText;
        if (cellValue.toLowerCase().indexOf(filter) > -1) {
          match = true;
          break;
        }
      }
    }
    rows[i].style.display = match ? "" : "none";
  }
}
function searchSalesAll() {
  const input = document.getElementById('searchInputSalesAll');
  const filter = input.value.toLowerCase();
  const table = document.getElementById('allsales-result');
  const rows = table.getElementsByTagName('tr');

  for (let i = 1; i < rows.length; i++) {
    const cells = rows[i].getElementsByTagName('td');
    let match = false;

    for (let j = 0; j < cells.length; j++) {
      if (cells[j]) {
        const cellValue = cells[j].textContent || cells[j].innerText;
        if (cellValue.toLowerCase().indexOf(filter) > -1) {
          match = true;
          break;
        }
      }
    }
    rows[i].style.display = match ? "" : "none";
  }
}
