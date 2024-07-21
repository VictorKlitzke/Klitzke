const getDeleteUsers = (button) => {
    return {
        type: {
            type: 'deleteUser',
        },
        values: {
            id_user_delete: button.getAttribute('data-id')
        }
    };
}

async function DeleteUsers(button) {
    const { type, values } = await getDeleteUsers(button);

    if (!values.id_user_delete) {
        showMessage('ID do usuário inválido', 'warning');
        return;
    }

    let responseDeleteUser = {
        type: 'deleteUser',
        id_user_delete: values.id_user_delete
    }

    continueMessage("Deseja realmente deletar esse usuário?", "Sim", "Não", async function () {

        try {

            let url = `${BASE_CONTROLLERS}deletes.php`;

            const response = await fetch(url, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(responseDeleteUser)
            });
            const responseBody = await response.json();

            if (responseBody.success) {
                showMessage('Usuário deletado com sucesso', 'success');

                setTimeout(() => {
                    location.reload();
                }, 2000);

            } else {
                showMessage(responseBody.message || 'Erro ao deletar usuário', 'error');
            }

        } catch (error) {
            showMessage('Error interno no servidor, contante o suporte ' + error, 'error');
        }

    }, function () {
        showMessage('Exclusão de usuário cancelada', 'warning');
    })

}

const getDeleteClients = (button) => {
    return {
        type: {
            type: 'deleteClients'
        },
        values: {
            id_clients_delete: button.getAttribute('data-id')
        },
    }
}