
const wrapper = document.querySelector('.wrapper');
const registerLink = document.querySelector('.register-link');
const loginLink = document.querySelector('.login-link');

registerLink.onclick = () => {
    wrapper.classList.add('active');
}

loginLink.onclick = () => {
    wrapper.classList.remove('active');
}


const getFieldsLogin = () => {
    return {
        type: 'login',
        name: document.getElementById('name').value,
        password: document.getElementById('password').value,
    }
}
async function login() {

    const FieldsLogin = await getFieldsLogin();

    if (FieldsLogin.name == "" || FieldsLogin.password == "") {
        showMessage('Campos não podem ser vazios', 'warning');
        return;
    }

    try {

        let url = `${BASE_CONTROLLERS}login.php`;

        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(FieldsLogin)
        })

        if (!response.ok) {
            throw new Error('Erro ao fazer requisição: ' + response.statusText);
        }

        const responseBody = await response.json();

        if (responseBody.success) {
            showMessage('Login realizado por ' + FieldsLogin.name, 'success');
            window.location.href = `${BASE_PATH_HOME}`;
        } else {
            showMessage('Erro ao fazer login: ' + responseBody.message, 'error');
        }

    } catch (error) {
        showMessage('Erro ao fazer requisição' + error, 'error');
    }

}

async function logout() {
    try {
        let url = `${BASE_CONTROLLERS}logout.php`;

        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            }
        });

        if (!response.ok) {
            throw new Error('Erro ao fazer requisição: ' + response.statusText);
        }

        const responseBody = await response.json();

        if (responseBody.success) {
            showMessage('Logout realizado com sucesso', 'success');
        } else {
            showMessage('Erro ao fazer logout: ' + responseBody.message, 'error');
        }

    } catch (error) {
        showMessage('Erro ao fazer requisição: ' + error.message, 'error');
    }
}
