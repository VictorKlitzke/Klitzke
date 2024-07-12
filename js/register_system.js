const FieldsUsers = () => {
    return {
        type: {
            type: 'users',
        },
        values: {
            name: document.getElementById("name").value,
            email: document.getElementById("email").value,
            password: document.getElementById("password").value,
            phone: document.getElementById("phone").value,
            userFunction: document.getElementById("function").value,
            commission: document.getElementById("commission").value,
            targetCommission: document.getElementById("target_commission").value,
            access: document.getElementById("access").value
        },
        inputs: {
            name: document.getElementById("name"),
            email: document.getElementById("email"),
            password: document.getElementById("password"),
            phone: document.getElementById("phone"),
            userFunction: document.getElementById("function"),
            commission: document.getElementById("commission"),
            targetCommission: document.getElementById("target_commission"),
            access: document.getElementById("access")
        }
    };
}
async function RegisterUsers() {

    const {values, type, inputs} = await FieldsUsers();

    if (values.name == "" || values.password == "" || values.email == "" || values.phone || values.userFunction || values.access == "") {
        showMessage('Campos estão vazios, por favor preencha', 'warning',);

        if (values.name == "") inputs.name.classList.add('error');
        if (values.password == "") inputs.password.classList.add('error');
        if (values.email == "") inputs.email.classList.add('error');
        if (values.userFunction == "") inputs.userFunction.classList.add('error');
        if (values.phone == "") inputs.phone.classList.add('error');
        if (values.access == "") inputs.access.classList.add('error');
        setTimeout(() => {
            inputs.name.classList.remove('error');
            inputs.password.classList.remove('error');
            inputs.email.classList.remove('error');
            inputs.userFunction.classList.remove('error');
            inputs.phone.classList.remove('error');
            inputs.access.classList.remove('error');
        }, 3000);

        return;
    }

    if (values.password < 6) {
        showMessage('Senha tem que ser maior que 6 digitos', 'warning',);

        if (values.password < 6) inputs.password.classList.add('error');
        setTimeout(() => {
            inputs.password.classList.remove('error');
        }, 3000);

        return;
    }

    let responseFields = {
        type: values.type,
        name: values.name,
        email: values.email,
        password: values.password,
        phone: values.phone,
        userFunction: values.userFunction,
        commission: values.commission,
        targetCommission: values.targetCommission,
        access: values.access
    }

    continueMessage(
        "Deseja realmente cadastrar esse usuário?", "Sim", "Não", async function () {
            try {
                let url = `${BASE_CONTROLLERS}registers.php`;

                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(responseFields)
                });

                const responseBody = await response.json();

                if (responseBody.success) {
                    showMessage("Usuário " + values.name + " cadastrado com sucesso!", 'success');
                } else {
                    showMessage("Erro ao fazer cadastro: " + responseBody.message, 'error');
                }

            } catch (error) {
                showMessage("Erro ao fazer requisição: " + error, 'error');
            }
        },
        function () {
            showMessage("Cadastro de usuário cancelado.", 'warning');
        }
    );
}

const getFieldsClients = () => {
    return {
        type: {
            type: 'clients',
        },
        values: {
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

    };
}
async function RegisterClients() {

    const { values, inputs, type } = await getFieldsClients();

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


    let responseClients = {
        type: type.type,
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

    continueMessage("Deseja realmente fazer cadastro de cliente?", "Sim", "Não", async function () {
        try {

            let url = `${BASE_CONTROLLERS}registers.php`;

            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(responseClients)
            })

            const responseBody = await response.json();

            if (responseBody.success) {
                showMessage("Cliente " + values.name + " cadastrado com sucesso!", 'success');
            } else {
                showMessage(responseBody.message || "Erro ao fazer cadastro " + values.name, 'error');
            }

        } catch (error) {
            showMessage('Erro ao fazer requisição' + error, 'error');
        }
    },
        function () {
            showMessage('Cadastro de cliente cancelado', 'warning');
        })
}

const getFieldsCompany = () => {
    return {
        type: 'company',
        name: document.getElementById('name').value,
        email: document.getElementById('email').value,
        cnpj: document.getElementById('cnpj').value,
        state_registration: document.getElementById('state_registration').value,
        phone: document.getElementById('phone').value,
        address: document.getElementById('address').value,
        city: document.getElementById('city').value,
        state: document.getElementById('state').value,
    };
}
async function RegisterCompany() {

    const FieldsCompany = await getFieldsCompany();

    if (FieldsCompany.cnpj < 14) {
        showMessage('CNPJ não pode ser menor que 14 digitos', 'warning');
        return;
    }

    if (FieldsCompany.cnpj != Number || FieldsCompany.state_registration != Number || FieldsCompany.phone != Number) {
        showMessage('CNPJ ou Inscrição estadual ou Telefone, não pode ser diferentes de numeros', 'warning');
        return;
    }

    let responseCompany = {
        type: FieldsCompany.type,
        name: FieldsCompany.name,
        email: FieldsCompany.email,
        cnpj: FieldsCompany.cnpj,
        state_registration: FieldsCompany.state_registration,
        phone: FieldsCompany.phone,
        address: FieldsCompany.address,
        city: FieldsCompany.city,
        state: FieldsCompany.state,
    }

    try {

        let url = `${BASE_CONTROLLERS}registers.php`;

        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(responseCompany)
        })

        const responseBody = await response.text();

        if (responseBody.success) {
            showMessage("Empresa " + Fields.name + " cadastrado com sucesso!", 'success');
        } else {
            showMessage("Erro ao fazer cadastro " + Fields.name, 'error');
        }
    } catch (error) {
        window.alert("Erro ao fazer requisição" + error);
    }
}

const getFieldsTable = () => {
    return {
        type: {
            type: 'table_request',
        },
        values: {
            name: document.getElementById("name_table").value,
        },
        inputs: {
            name: document.getElementById("name_table"),
        }
    };
}
async function RegisterTableRequest() {

    const { values, inputs, type } = await getFieldsTable();

    if (values.name == "") {
        showMessage('Preencha todos os campos!', 'warning');

        if (values.name === "") inputs.name.classList.add('error');
        setTimeout(() => {
            inputs.name.classList.remove('error');
        }, 3000);

        return;
    }

    if (values.name != Number && values.name == String) {
        showMessage('Campo invalido, só aceita numeros', 'warning');

        if (values.name === "") inputs.name.classList.add('error');
        setTimeout(() => {
            inputs.name.classList.remove('error');
        }, 3000);

        return
    }

    let responseFieldsTable = {
        type: type.type,
        name: values.name
    }

    try {

        let url = `${BASE_CONTROLLERS}registers.php`;

        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(responseFieldsTable)
        })

        const responseBody = await response.json();

        if (responseBody.success) {
            showMessage('Mesa cadastrada com sucesso', 'success');
            values.name == "";
        } else {
            showMessage(responseBody.message || 'Erro ao cadastrar mesa', 'error');
        }

    } catch (error) {
        showMessage("Erro ao fazer requisição" + error, 'error');
    }
}

const getFieldsAccount = () => {
    return {
        type: {
            type: 'account',
        },
        values: {
            pix: document.getElementById('pix').value.trim(),
            name_holder: document.getElementById('name_holder').value.trim(),
            city: document.getElementById('city').value.trim(),
        },
        inputs: {
            pix: document.getElementById('pix'),
            name_holder: document.getElementById('name_holder'),
            city: document.getElementById('city')
        }
    };
}
async function RegisterAccount() {
    const { values, inputs, type } = await getFieldsAccount();
    const lettersRegex = /^[A-Za-z\s]+$/;

    if (values.pix === "" || values.city === "" || values.name_holder === "") {
        showMessage('Campos vazios, preencha os campos', 'warning');

        if (values.pix === "") inputs.pix.classList.add('error');
        if (values.city === "") inputs.city.classList.add('error');
        if (values.name_holder === "") inputs.name_holder.classList.add('error');
        setTimeout(() => {
            inputs.pix.classList.remove('error');
            inputs.city.classList.remove('error');
            inputs.name_holder.classList.remove('error');
        }, 3000);

        return;
    }

    if (values.pix != Number && values.pix == String) {
        showMessage('Campo invalido, só numeros', 'warning');

        if (values.pix != Number) inputs.pix.classList.add('error');
        setTimeout(() => {
            inputs.pix.classList.remove('error');
        }, 3000);

        return;
    }

    if (!lettersRegex.test(values.name_holder) || !lettersRegex.test(values.city)) {
        showMessage('Campos invalidos, não aceita números', 'warning');

        if (!lettersRegex.test(values.name_holder)) inputs.name_holder.classList.add('error');
        if (!lettersRegex.test(values.city)) inputs.city.classList.add('error');
        setTimeout(() => {
            inputs.name_holder.classList.remove('error');
            inputs.city.classList.remove('error');
        }, 3000);

        return;
    }

    let responseAccount = {
        type: type.type,
        pix: values.pix,
        name_holder: values.name_holder,
        city: values.city
    };

    continueMessage("Deseja realmente cadastrar conta para o PIX?", "Sim", "Não", async function () {
        try {
            let url = `${BASE_CONTROLLERS}registers.php`;

            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(responseAccount)
            });
            const textResponse = await response.text();
            const responseBody = JSON.parse(textResponse);

            if (responseBody.success) {
                showMessage('Conta cadastrada com sucesso', 'success');
            } else {
                showMessage('Erro ao cadastrar conta bancaria: ' + (responseBody.error || 'Erro desconhecido'), 'error');
            }
        } catch (error) {
            showMessage("Erro ao fazer requisição: " + error, 'error');
        }
    }, function () {
        showMessage('Cadastro cancelado', 'warning');
    });
}

const getFieldsProducts = () => {
    return {
        type: 'products',
        name: document.getElementById('name').value,
        quantity: document.getElementById('quantity').value,
        stock_quantity: document.getElementById('stock_quantity').value,
        barcode: document.getElementById('barcode').value,
        value_product: document.getElementById('value_product').value,
        cost_value: document.getElementById('cost_value').value,
        reference: document.getElementById('reference').value,
        model: document.getElementById('model').value,
        brand: document.getElementById('brand').value,
        flow: document.getElementById('flow').value,
        register_date: document.getElementById('register_date').value,

    }
}
async function RegisterProducts() {

    const DateActual = new Date();

    FieldsProduct.register_date = DateActual;

    const FieldsProduct = await getFieldsProduct();

    if (FieldsProduct.quantity != Number || FieldsProduct.stock_quantity != Nmber ||
        FieldsProduct.barcode != Number || FieldsProduct.value_product != Number || FieldsProduct.cost_value != Number
    ) {
        showMessage('Quantidade e Quantidade estoque não podem ser diferente de numero', 'warning');
        return;
    }

    let responseProduct = {
        type: FieldsProduct.type,
        name: FieldsProduct.name,
        quantity: FieldsProduct.quantity,
        stock_quantity: FieldsProduct.stock_quantity,
        barcode: FieldsProduct.barcode,
        value_product: FieldsProduct.value_product,
        cost_value: FieldsProduct.cost_value,
        reference: FieldsProduct.reference,
        model: FieldsProduct.model,
        brand: FieldsProduct.brand,
        flow: FieldsProduct.flow,
        register_date: FieldsProduct.register_date,
    }

    try {

        let url = `${BASE_CONTROLLERS}registers.php`;

        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(responseProduct)
        })

        const responseBody = await response.json();

        if (responseBody.success) {
            showMessage('Produto' + FieldsProduct.name + 'cadastrado com sucesso', 'success');
        } else {
            showMessage('Erro ao cadastrar produto', 'error');
        }

    } catch (error) {
        showMessage("Erro ao fazer requisição" + error, 'error');
    }
}

const getFieldsForn = () => {
    return {
        type: {
            type: 'forn'
        },
        values: {
            name_company: document.getElementById('name_company').value,
            fantasy_name: document.getElementById('fantasy_name').value,
            email: document.getElementById('email').value,
            phone: document.getElementById('phone').value,
            address: document.getElementById('address').value,
            city: document.getElementById('city').value,
            state: document.getElementById('state').value,
            cnpj: document.getElementById('cnpj').value,
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
async function RegisterForn() {

    const {type, values, inputs} = await getFieldsForn();

    console.log(values.name_company);

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

    let responseForn = {
        type: type.type,
        name_company: values.name_company,
        fantasy_name: values.fantasy_name,
        email: values.email,
        phone: values.phone,
        address: values.address,
        city: values.city,
        state: values.state,
        cnpj: values.cnpj,
    }

    console.log(responseForn);

    continueMessage("Deseja continuar com o cadastro?", "Sim", "Não", async function () {
        try {

            let url = `${BASE_CONTROLLERS}registers.php`;

            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(responseForn)
            });

            console.log(await response.json());

            const responseBody = await response.json();

            console.log(responseBody);

            if (responseBody.success) {
                showMessage('Fornecedor ' + values.name_company + ' cadastrado com sucesso', 'success');
            } else {
                showMessage(responseBody.message || 'Erro ao fazer cadastro do fornecedor ' + responseBody.error, 'error');
            }

        } catch (error) {
            showMessage('Erro na requisição ' + error, 'error')
        }
    }, function () {
        showMessage('Cadastro de Fornecedor cancelado', 'error');
    }
    )
}