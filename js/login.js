const wrapper = document.querySelector('.wrapper');
const registerLink = document.querySelector('.register-link');
const loginLink = document.querySelector('.login-link');

registerLink.onclick = () => {
    wrapper.classList.add('active');
}

loginLink.onclick = () => {
    wrapper.classList.remove('active');
}

document.addEventListener('DOMContentLoaded', function() {
    const token = getCookie('jwt');

    if (token) {
        // Use o token conforme necessário
        console.log('Token encontrado:', token);

        console.log(token)

        // Exemplo de como enviar o token em uma requisição AJAX
        fetch('/some-protected-endpoint', {
            method: 'GET',
            headers: {
                'Authorization': `Bearer ${token}`
            }
        }).then(response => {
            return response.json();
        }).then(data => {
            console.log(data);
        }).catch(error => {
            console.error('Erro:', error);
        });
    } else {
        console.log('Token não encontrado.');
    }
});

function getCookie(name) {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) return parts.pop().split(';').shift();
}
