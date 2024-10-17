const getEditUsers = () => {
    return {
        type: {
            type: 'edituser',
        },
        values: {
            id: document.getElementById('id_user').value,
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
        id_user: values.id,
        name: values.name,
        email: values.email,
        login: values.login,
        phone: values.phone,
        function: values.function,
        commission: values.commission,
        target_commission: values.target_commission
    }

    continueMessage("Deseja continuar com a edição?", "Sim", "Não", async function () {
        try {

            let url = `${BASE_CONTROLLERS}edits.php`;

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
                location.reload();
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
            type: 'editclient'
        },
        values: {
            id_client: document.getElementById('id_client').value,
            name: document.getElementById('name').value,
            email: document.getElementById('email').value,
            social_reason: document.getElementById('social_reason').value,
            cpf: document.getElementById('cpf').value,
            phone: document.getElementById('phone').value,
            address: document.getElementById('address').value,
            city: document.getElementById('city').value,
            cep: document.getElementById('cep').value,
            neighborhood: document.getElementById('neighborhood').value,
        },
        inputs: {
            name: document.getElementById('name'),
            email: document.getElementById('email'),
            social_reason: document.getElementById('social_reason'),
            cpf: document.getElementById('cpf'),
            phone: document.getElementById('phone'),
            address: document.getElementById('address'),
            city: document.getElementById('city'),
            cep: document.getElementById('cep'),
            neighborhood: document.getElementById('neighborhood'),
        }
    }
}
async function EditClients() {
    const { type, values, inputs} = await getEditClients();

    if (values.cpf == "" || values.name == "" || values.social_reason == "") {
        showMessage('Campos não podem ficar vazios, por favor preecha', 'warning');

        if (values.cpf == "") inputs.cpf.classList.add('error');
        if (values.name == "") inputs.name.classList.add('error');
        if (values.social_reason == "") inputs.social_reason.classList.add('error');
        setTimeout(() => {
            inputs.cpf.classList.remove('error');
            inputs.name.classList.remove('error');
            inputs.social_reason.classList.remove('error');
        }, 3000);

        return;
    }
    if (values.cpf < 11) {
        showMessage('CPF não pode ser menor que 11 digitos', 'warning');

        if (values.cpf < 11) inputs.cpf.classList.add('error');
        setTimeout(() => {
            inputs.cpf.classList.remove('error');
        }, 3000);

        return;
    } else if (values.cep < 8) {
        showMessage('CEP não pode ser menor que 8 digitos', 'warning');

        if (values.cep < 8) inputs.cep.classList.add('error');
        setTimeout(() => {
            inputs.cep.classList.remove('error');
        }, 3000);

        return;
    } else if (values.phone < 8) {
        showMessage('Telefone não pode ser menor que 8 digitos', 'warning');

        if (values.phone < 8) inputs.phone.classList.add('error');
        setTimeout(() => {
            inputs.phone.classList.remove('error');
        }, 3000);

        return;
    }

    if (isNaN(values.cpf) || isNaN(values.cep) || isNaN(values.phone)) {
        showMessage('CPF, CEP ou Telefone devem conter apenas números', 'warning');

        if (isNaN(values.cpf)) inputs.cpf.classList.add('error');
        if (isNaN(values.cep)) inputs.cep.classList.add('error');
        if (isNaN(values.phone)) inputs.phone.classList.add('error');

        setTimeout(() => {
            inputs.cpf.classList.remove('error');
            inputs.cep.classList.remove('error');
            inputs.phone.classList.remove('error');
        }, 3000);

        return;
    }

    let responseEditClient = {
        type: type.type,
        id_client: values.id_client,
        name: values.name,
        email: values.email,
        social_reason: values.social_reason,
        cpf: values.cpf,
        phone: values.phone,
        address: values.address,
        city: values.city,
        cep: values.cep,
        neighborhood: values.neighborhood,
    }

    continueMessage("Deseja continuar com a edição?", "Sim", "Não", async function () {
        try {

            let url = `${BASE_CONTROLLERS}edits.php`;

            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(responseEditClient)
            })

            const responseBody = await response.json();

            if (responseBody.success) {
                showMessage("Cliente " + values.name + " editado com sucesso!", 'success');
                location.reload();
            } else {
                showMessage(responseBody.message || "Erro ao tentar editar cliente " + values.name, 'error');
            }

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
            id_company: document.getElementById('id_company').value,
            name: document.getElementById('name').value,
            email: document.getElementById('email').value,
            cnpj: document.getElementById('cnpj').value,
            state_registration: document.getElementById('state_registration').value,
            phone: document.getElementById('phone').value,
            address: document.getElementById('address').value,
            city: document.getElementById('city').value,
            state: document.getElementById('state').value,
        },
        inputs: {
            name: document.getElementById('name'),
            email: document.getElementById('email'),
            cnpj: document.getElementById('cnpj'),
            state_registration: document.getElementById('state_registration'),
            phone: document.getElementById('phone'),
            address: document.getElementById('address'),
            city: document.getElementById('city'),
            state: document.getElementById('state'),
        }
    }
}
async function EditCompany() {
    const { type, values, inputs} = await getEditCompany();

    if (values.name == "" || values.state_registration == "" || values.cnpj == "") {
        showMessage('Campo não podem ser vazios', 'warning');

        if (values.name === "") inputs.name.classList.add('error');
        if (values.state_registration === "") inputs.state_registration.classList.add('error');
        if (values.cnpj === "") inputs.cnpj.classList.add('error');
        setTimeout(() => {
            inputs.name.classList.remove('error');
            inputs.state_registration.classList.remove('error');
            inputs.cnpj.classList.remove('error');
        }, 3000);

        return;
    }

    if (values.cnpj < 14) {
        showMessage('CNPJ não pode ser conter menos que 14 digitos', 'warning');

        if (values.cnpj < 14) inputs.cnpj.classList.add('error');
        setTimeout(() => {
            inputs.name.classList.remove('error');
        }, 3000);

        return;
    }

    if (values.state_registration < 9) {
        showMessage('Inscrição estadual não pode ser conter menos que 9 digitos', 'warning');

        if (values.state_registration < 9) inputs.state_registration.classList.add('error');
        setTimeout(() => {
            inputs.state_registration.classList.remove('error');
        }, 3000);

        return;
    }

    let InputCnpj = values.cnpj.replace(/\D/g, "");

    if (isNaN(InputCnpj) || isNaN(values.state_registration) || isNaN(values.phone)) {
        showMessage('CNPJ ou Inscrição estadual ou Telefone, não pode ser diferentes de numeros', 'warning');

        if (isNaN(InputCnpj)) inputs.cnpj.classList.add('error');
        if (isNaN(values.state_registration)) inputs.state_registration.classList.add('error');
        if (isNaN(values.phone)) inputs.phone.classList.add('error');
        setTimeout(() => {
            inputs.cnpj.classList.remove('error');
            inputs.state_registration.classList.remove('error');
            inputs.phone.classList.remove('error');
        }, 3000);

        return;
    }

    let responseEditCompany = {
        type: type.type,
        id_company: values.id_company,
        name: values.name,
        email: values.email,
        cnpj: values.cnpj,
        state_registration: values.state_registration,
        phone: values.phone,
        address: values.address,
        city: values.city,
        state: values.state,
    }

    continueMessage("Deseja continuar com a edição?", "Sim", "Não", async function () {
        try {

            let url = `${BASE_CONTROLLERS}edits.php`;

            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(responseEditCompany)
            })
    
            const responseBody = await response.json();
    
            if (responseBody.success) {
                showMessage("Empresa " + values.name + " editada com sucesso!", 'success');
                location.reload();
            } else {
                showMessage(responseBody.message || "Erro ao tentar editar Empresa " + values.name, 'error');
            }

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
            id_forn: document.getElementById('id_forn').value,
            name_company: document.getElementById('name_company').value,
            fantasy_name: document.getElementById('fantasy_name').value,
            email: document.getElementById('email').value,
            phone: document.getElementById('phone').value,
            address: document.getElementById('address').value,
            city: document.getElementById('city').value,
            state: document.getElementById('state').value,
            cnpj: document.getElementById('cnpjcpf').value,
        },
        inputs: {
            name_company: document.getElementById('company'),
            fantasy_name: document.getElementById('fantasy_name'),
            email: document.getElementById('email'),
            phone: document.getElementById('phone'),
            address: document.getElementById('address'),
            city: document.getElementById('city'),
            state: document.getElementById('state'),
            cnpj: document.getElementById('cnpj'),
        }
    }
}
async function EditForn() {

    const { type, values, inputs } = await getEditSuppliers();

    if (values.cnpj == "" || values.name_company == "" || values.fantasy_name == "") {
        showMessage('Campos não podem ficar vazios', 'warning');

        if (values.fantasy_name === "") inputs.fantasy_name.classList.add('error');
        if (values.name_company === "") inputs.name_company.classList.add('error');
        if (values.cnpj === "") inputs.cnpj.classList.add('error');
        setTimeout(() => {
            inputs.fantasy_name.classList.remove('error');
            inputs.name_company.classList.remove('error');
            inputs.cnpj.classList.remove('error');
        }, 3000);

        return;
    }

    if (values.cnpj < 14) {
        showMessage('CNPJ não pode ser menor que 14 numeros', 'warning');

        if (values.cnpj === "") inputs.cnpj.classList.add('error');
        setTimeout(() => {
            inputs.cnpj.classList.remove('error');
        }, 3000);

        return;
    }

    if (values.phone < 8) {
        showMessage('Telefone não pode ser menor que 8 numeros', 'warning');

        if (values.phone === "") inputs.phone.classList.add('error');
        setTimeout(() => {
            inputs.phone.classList.remove('error');
        }, 3000);

        return;
    }

    let InputCnpj = values.cnpj.replace(/\D/g, "");

    if (isNaN(values.phone) || isNaN(InputCnpj)) {
        showMessage('Telefone ou CNPJ tem que ser numeros', 'warning');

        if (isNaN(values.phone)) inputs.phone.classList.add('error');
        if (isNaN(InputCnpj)) inputs.cnpj.classList.add('error');
        setTimeout(() => {
            inputs.phone.classList.remove('error');
            inputs.cnpj.classList.remove('error');
        }, 3000);

        return;
    }


    let responseEditForn = {
        type: type.type,
        id_forn: values.id_forn,
        name_company: values.name_company,
        fantasy_name: values.fantasy_name,
        email: values.email,
        phone: values.phone,
        address: values.address,
        city: values.city,
        state: values.state,
        cnpj: values.cnpj,
    }

    continueMessage("Deseja continuar com a edição?", "Sim", "Não", async function () {
        try {

            let url = `${BASE_CONTROLLERS}edits.php`;

            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(responseEditForn)
            })
    
            const responseBody = await response.json();
    
            if (responseBody.success) {
                showMessage("Fornecedor " + values.name + " editada com sucesso!", 'success');
                location.reload();
            } else {
                showMessage(responseBody.message || "Erro ao tentar editar Fornecedor " + values.name, 'error');
            }

        } catch (error) {
            showMessage('Erro ao fazer requisição' + error ,'error')
        }
    }, function () {
        showMessage('Operação cancelada', 'warning')
    })

}

const getInventaryQuantity = () => {
    return {
        type: {
            type: 'inventaryquantity',
        },
        values: {
            quantityProduct: document.getElementById('productQuantity').value,
            productID: document.getElementById('productID').value
        },
        inputs: {
            quantityProduct: document.getElementById('productQuantity'),
            productID: document.getElementById('productID')
        }
    }
}
async function Inventaryquantity() {
    const { type, values, inputs} = await getInventaryQuantity();

    if (isNaN(values.quantityProduct)) {
        showMessage('Campo quantidade tem que ser numerico');
        return;
    }

    if (values.quantityProduct < 0) {
        showMessage('Quantidade não pode ser nagativo');
        return;
    }

    let responseInventary = {
        quantityProduct: values.quantityProduct,
        valueProduct: values.valueProduct,
        type: type.type
    }

    continueMessage("Deseja continuar com a edição?", "Sim", "Não", async function () {
        try {

            let url = `${BASE_CONTROLLERS}edits.php`;
            let response = await fech(url, {
                method: "POST",
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(responseInventary)
            })

        } catch (error) {
            showMessage('Erro ao fazer requisição' + error ,'error')
        }
    },function () {
        showMessage('Operação cancelada', 'warning');
    });
}