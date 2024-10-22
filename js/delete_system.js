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
async function deleteClients(button) {
    const { type, values } = await getDeleteClients(button);

    if (!values.id_clients_delete) {
        showMessage('ID do cliente inválido', 'warning');
        return;
    }

    let responseDeletarClients = {
        type: type.type,
        id_clients_delete: values.id_clients_delete,
    }

    continueMessage("Deseja realmente deletar esse cliente?", "Sim", "Não", async function () {

        try {

            let url = `${BASE_CONTROLLERS}deletes.php`;

            const response = await fetch(url, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(responseDeletarClients)
            });
            const responseBody = await response.json();

            if (responseBody.success) {
                showMessage('Cliente deletado com sucesso', 'success');

                setTimeout(() => {
                    location.reload();
                }, 2000);

            } else {
                showMessage(responseBody.message || 'Erro ao deletar cliente', 'error');
            }

        } catch (error) {
            showMessage('Error interno no servidor, contante o suporte ' + error, 'error');
        }

    }, function () {
        showMessage('Exclusão de cliente cancelada', 'warning');
    })
}

const getDeleteForn = (button) => {
    return {
        type: {
            type: 'deleteForn',
        },
        values: {
            id_forn_delete: button.getAttribute('data-id')
        }
    };
}
async function DeleteForn(button) {
    const { type, values } = await getDeleteForn(button);

    if (!values.id_forn_delete) {
        showMessage('ID do fornecedor inválido', 'warning');
        return;
    }

    let responseDeleteForn = {
        type: type.type,
        id_forn_delete: values.id_forn_delete,
    }

    continueMessage("Deseja realmente deletar esse fornecedor?", "Sim", "Não", async function () {

        try {

            let url = `${BASE_CONTROLLERS}deletes.php`;

            const response = await fetch(url, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(responseDeleteForn)
            });
            const responseBody = await response.json();

            if (responseBody.success) {
                showMessage('Forecedor deletado com sucesso', 'success');

                setTimeout(() => {
                    location.reload();
                }, 2000);

            } else {
                showMessage(responseBody.message || 'Erro ao deletar fornecedor', 'error');
            }

        } catch (error) {
            showMessage('Error interno no servidor, contante o suporte ' + error, 'error');
        }

    }, function () {
        showMessage('Exclusão de fornecedor cancelada', 'warning');
    })
}

async function DeleteMenuAccess(menuName, UserIDMenu) {

    if (!UserIDMenu) {
        showMessage('ID do Usuário não encontrado', 'warning');
        return;
    }

    let responseDeleteMenu = {
        type: 'deleteMenuAccess',
        menu: menuName,
        UserIDMenu: UserIDMenu
    }

    console.log(responseDeleteMenu);

    continueMessage("Deseja realmente deletar menu de acceso?", "Sim", "Não", async function() {
        try {

            let url = `${BASE_CONTROLLERS}deletes.php`;
    
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(responseDeleteMenu)
            })
    
            const result = await response.json();

            console.log(result);

            if (result.success) {
                showMessage('Menu deletado com sucesso', 'success');
            } else {
                showMessage('Erro ao remover menu de acesso' + result.message, 'error');
            }
    
        } catch (error) {
            showMessage('Erro ao tentar remover Menu do usuário', 'error')
        }
    }, function () {
        showMessage('Operação cancelada', 'warning')
    })
}