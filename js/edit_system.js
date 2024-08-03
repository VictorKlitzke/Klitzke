const getEditUsers = () => {
    return {
        type: {
            type: 'edituser',
        },
        values: {
            name: document.getElementById('name').value,
            email: document.getElementById('email').value,
            login: document.getElementById('login').value,
            phone: document.getElementById('phone').value,
            function: document.getElementById('function').value,
            commission: document.getElementById('commission').value,
            target_commission: document.getElementById('target_commission').value,
        },
        inputs: {
            name: document.getElementById('name'),
            email: document.getElementById('email'),
            login: document.getElementById('login'),
            phone: document.getElementById('phone'),
            function: document.getElementById('function'),
            commission: document.getElementById('commission'),
            target_commission: document.getElementById('target_commission'),
        }
    }
}
async function EditUsers() {
    const {type, values, inputs} = await getEditUsers();

    if (values.name == "" || values.password == "" || values.email == "" || values.phone == "" || values.function == "") {
        showMessage('Preencha todos os campos!', 'warning',);

        if (values.name == "") inputs.name.classList.add('error');
        if (values.password == "") inputs.password.classList.add('error');
        if (values.email == "") inputs.email.classList.add('error');
        if (values.function == "") inputs.function.classList.add('error');
        if (values.phone == "") inputs.phone.classList.add('error');
        setTimeout(() => {
            inputs.name.classList.remove('error');
            inputs.password.classList.remove('error');
            inputs.email.classList.remove('error');
            inputs.function.classList.remove('error');
            inputs.phone.classList.remove('error');
        }, 3000);

        return;
    }

    if (isNaN(values.target_commission) || isNaN(values.commission) || isNaN(values.phone)){
        showMessage('Campos só aceita numeros', 'warning',);

        if (!isNaN(values.target_commission)) inputs.targetCommission.classList.add('error');
        if (!isNaN(values.commission)) inputs.commission.classList.add('error');
        if (!isNaN(values.phone)) inputs.phone.classList.add('error');
        setTimeout(() => {
            inputs.commission.classList.remove('error');
            inputs.commission.target_commission.remove('error');
            inputs.commission.phone.remove('error');
        }, 3000);

        return;
    }

    let responseEditUsers = {
        type: type.type,
        name: values.name,
        email: values.email,
        login: values.login,
        phone: values.phone,
        function: values.function,
        commission: values.commission,
        target_commission: values.target_commission
    }

    console.log(responseEditUsers);

    continueMessage("Deseja continuar com a edição?", "Sim", "Não", async function () {
        try {

            let url = `${BASE_CONTROLLERS}registers.php`;

            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(responseEditUsers)
            });

            const responseBody = await response.json();

            if (responseBody.success) {
                showMessage("Usuário " + values.name + " editado com sucesso!", 'success');
            } else {
                showMessage("Erro ao tentar editar usuário: " + responseBody.message, 'error');
            }


        } catch (error) {
            showMessage('Erro ao fazer requisição' + error ,'error')
        }
    }, function () {
        showMessage('Operação cancelada', 'warning')
    })
}

const getEditClients = () => {
    return {
        type: {
            type: 'editclients'
        },
        values: {

        },
        inputs: {

        }
    }
}
async function EditClients() {
    const { type, values, inputs} = await getEditClients();

    continueMessage("Deseja continuar com a edição?", "", "", async function () {
        try {

        } catch (error) {
            showMessage('Erro ao fazer requisição' + error ,'error')
        }
    }, function () {
        showMessage('Operação cancelada', 'warning')
    })
}

const getEditCompany = () => {
    return {
        type : {
            type: 'editcompany',
        },
        values: {

        },
        inputs: {

        }
    }
}
async function EditCompany() {
    const { type, values, inputs} = await getEditCompany();

    continueMessage("Deseja continuar com a edição?", "", "", async function () {
        try {

        } catch (error) {
            showMessage('Erro ao fazer requisição' + error ,'error')
        }
    }, function () {
        showMessage('Operação cancelada', 'warning')
    })
}

const getEditSuppliers = () => {
    return {
        type: {
            type: 'editsuppliers',
        },
         values: {

         }
    }
}