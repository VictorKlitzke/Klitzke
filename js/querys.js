const editUsers = document.getElementById("edit-users");

window.onload = function () {
  NivelAccess();
}

async function NivelAccess() {
  try {

    let response = await fetch(`${BASE_CONTROLLERS}querys.php`);

    if (!response.ok) {
      showMessage('Erro na requisição: ' + response.statusText, 'warning');
    }

    let data = await response.json();

    if (data.access < 50) {
      editUsers.setAttribute('disabled', true); 
    } else {
      editUsers.removeAttribute('disabled');  
    }

  } catch (error) {
    console.log('Erro na requisição' + error);
    console.clear();
  }
}