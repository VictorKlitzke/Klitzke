const editButtons = document.querySelectorAll(".accessnivel"); // Adicione um ponto para selecionar pela classe

window.onload = function () {
  NivelAccess();
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
