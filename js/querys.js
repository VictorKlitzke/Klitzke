const editButtons = document.querySelectorAll(".accessnivel");

window.onload = function () {
  NivelAccess();
  NoticeBoard();
}

async function NivelAccess() {
  try {
    let response = await fetch(`${BASE_CONTROLLERS}querys.php`);

    if (!response.ok) {
      showMessage('Erro na requisição: ' + response.statusText, 'warning');
      return;
    }

    let data = await response.json();
    const userAccess = data.access;

    editButtons.forEach(button => {
      if (userAccess < 50) {
        button.style.display = 'none';
      }
    });

  } catch (error) {
    console.log('Erro na requisição: ' + error);
  }
}

async function NoticeBoard() {
  try {
    let responseNoticeBoard = await fetch(`${BASE_CONTROLLERS}querys.php`);

    if (!responseNoticeBoard.ok) {
      showMessage('Erro na requisição: ' + responseNoticeBoard.statusText, 'warning');
      return;
    }

    let dataresponseNoticeBoard = await responseNoticeBoard.json();
    const query_warnings = dataresponseNoticeBoard.query_warnings;

    console.log(query_warnings);

    const noticeBoardContainer = document.querySelector('.notice-board');
    noticeBoardContainer.innerHTML = '';

    const today = new Date();

    query_warnings.forEach(warning => {
      const [year, month, day] = warning.transaction_date.split('-');
      const dateVenciment = new Date(`${year}-${month}-${day}`);

      if (isNaN(dateVenciment)) {
        console.log('Data inválida:', warning.transaction_date);
        return; 
      }

      let avisoTexto = '';
      let icone = '';
      let classeCor = '';

      if (dateVenciment < today) {
        icone = '<i class="fas fa-exclamation-circle"></i>'; 
        classeCor = 'text-danger';
        avisoTexto = `${icone} <strong>Conta:</strong> ${warning.description || 'Sem descrição'} - <strong>Valor:</strong> <span class="text-bold">R$ ${warning.value || 'N/A'}</span> - <strong>Vencida em:</strong> ${day}/${month}/${year}`;
      } else if (dateVenciment <= new Date(today.setDate(today.getDate() + 5))) {
        icone = '<i class="fas fa-hourglass-half"></i>'; 
        classeCor = 'text-warning';
        avisoTexto = `${icone} <strong>Conta:</strong> ${warning.description || 'Sem descrição'} - <strong>Valor:</strong> <span class="text-bold">R$ ${warning.value || 'N/A'}</span> - <strong>Vencimento em:</strong> ${day}/${month}/${year}`;
      } else {
        return; 
      }

      const avisoElement = document.createElement('p');
      avisoElement.className = `${classeCor} mb-2`; 
      avisoElement.innerHTML = avisoTexto;

      const separador = document.createElement('hr');
      separador.className = 'my-2';

      noticeBoardContainer.appendChild(avisoElement);
      noticeBoardContainer.appendChild(separador);
    });

  } catch (error) {
    console.log('Erro na requisição: ' + error);
  }
}


function formatDate(dateString) {
  if (!dateString) return 'Data inválida';
  const [year, month, day] = dateString.split('-');
  return `${day}/${month}/${year}`;
}
