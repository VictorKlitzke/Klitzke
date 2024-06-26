const FieldsUsers = () => {
    return {
        type: 'users',
        name: document.getElementById("name").value,
        email: document.getElementById("email").value,
        password: document.getElementById("password").value,
        phone: document.getElementById("phone").value,
        userFunction: document.getElementById("function").value,
        commission: document.getElementById("commission").value,
        targetCommission: document.getElementById("target_commission").value,
        access: document.getElementById("access").value
    };
}
async function RegisterUsers() {

    const Fields = await FieldsUsers();

    // if (Fields.name == "" || Fields.password == "" ||
    //         Fields.email == "" || Fields.phone || 
    //         Fields.userFunction || Fields.commission == "" ||
    //         Fields.targetCommission == "") {

    //     window.alert("Algum campo está vazio, por favor preencha o campo");
    //     return false;
    // }

    let responseFields = {
        type: Fields.type,
        name: Fields.name,
        email: Fields.email,
        password: Fields.password,
        phone: Fields.phone,
        userFunction: Fields.userFunction,
        commission: Fields.commission,
        targetCommission: Fields.targetCommission,
        access: Fields.access
    }

    const continueRegisterUsers = confirm("Deseja cadastrar realmente esse usuário?");

    if (continueRegisterUsers) {
        try {

            let url = `${BASE_CONTROLLERS}registers.php`;

            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(responseFields)
            })

            const responseBody = await response.text();

            if (responseBody.success) {
                showMessage("Usuário " + Fields.name + " cadastrado com sucesso!", 'success');
            } else {
                showMessage("Erro ao fazer cadastro " + Fields.name, 'error');
            }

        } catch (error) {
            showMessage("Erro ao fazer requisição" + error, 'error');
        }
    }
}

const getFieldsClients = () => {
    return {
        type: 'clients',
        name: document.getElementById('name').value,
        email: document.getElementById('email').value,
        social_reason: document.getElementById('social_reason').value,
        cpf: document.getElementById('cpf').value,
        phone: document.getElementById('phone').value,
        address: document.getElementById('address').value,
        city: document.getElementById('city').value,
        cep: document.getElementById('cep').value,
        neighborhood: document.getElementById('neighborhood').value,
    };
}
async function RegisterClients() {

    const FieldsClients = await getFieldsClients();

    if (FieldsClients.cpf == "" || FieldsClients.name == "" || FieldsClients.social_reason == "") {
        showMessage('Campos não podem ficar vazios, por favor preecha', 'warning');
        return;
    }

    if (FieldsClients.cpf < 11) {
        showMessage('CPF não pode ser menor que 11 digitos', 'warning');
        return;
    }

    if (FieldsClients.cpf != Number || FieldsClients.cep != Number ||
        FieldsClients.phone != Number) {
            showMessage('CPF ou CEP ou Telefone, não pode ser diferentes de numeros', 'warning');
            return;
        }

    let responseClients = {
        type: FieldsClients.type,
        name: FieldsClients.name,
        email: FieldsClients.email,
        social_reason: FieldsClients.social_reason,
        cpf: FieldsClients.cpf,
        phone: FieldsClients.phone,
        address: FieldsClients.address,
        city: FieldsClients.city,
        cep: FieldsClients.cep,
        neighborhood: FieldsClients.neighborhood,
    }

    try {

        let url = `${BASE_CONTROLLERS}registers.php`;

        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(responseClients)
        })

        const responseBody = await response.text();

        if (responseBody.success) {
            showMessage("Cliente " + Fields.name + " cadastrado com sucesso!", 'success');
        } else {
            showMessage("Erro ao fazer cadastro " + Fields.name, 'error');
        }

    } catch (error) {
        window.alert("Erro ao fazer requisição" + error);
    }
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
        type : 'table_request',
        name: document.getElementById("name").value,
    }
}
async function RegisterTableRequest() {

    const FieldsTable = await getFieldsTable();

    if (FieldsTable.name == "") {
        showMessage('Preencha todos os campos!', 'error');
        return;
    }

    let responseFieldsTable = {
        type: FieldsTable.type,
        name: FieldsTable.name
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
        } else {
            showMessage('Erro ao cadastrar mesa', 'error');
        }

    } catch (error) {
        showMessage("Erro ao fazer requisição" + error, 'error');
    }
}

const getFieldsAccount = () => {
    return {
        type: 'account',
        pix: document.getElementById('pix').value,
        name_holder: document.getElementById('name_holder').value,
        city: document.getElementById('city').value,
    }
}
async function RegisterAccount() {

    const FieldsAccount = await getFieldsAccount();

    if (FieldsAccount.pix == "" || FieldsAccount.city == "" || FieldsAccount.name_holder == "") {
        showMessage('Campos vazios, preencha os campos', 'warning');
        return;
    }

    let responseAccount = {
        pix: FieldsAccount.pix,
        name_holder: FieldsAccount.name_holder,
        city: FieldsAccount.city
    }

    try {

        let url = `${BASE_CONTROLLERS}registers.php`;

        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(responseAccount)
        })

        const responseBody = await response.json();

        if (responseBody.success) {
            showMessage('Conta cadastrada com sucesso', 'success');
        } else {
            showMessage('Erro ao cadastrar conta bancaria', 'error');
        }

    } catch (error) {
        showMessage("Erro ao fazer requisição" + error, 'error');
    }
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
        type: 'forn',
        name_company: document.getElementById('company').value,
        fantasy_name: document.getElementById('fantasy_name').value,
        email: document.getElementById('email').value,
        phone: document.getElementById('phone').value,
        address: document.getElementById('address').value,
        city: document.getElementById('city').value,
        state: document.getElementById('state').value,
        cnpj: document.getElementById('cnpj').value
    }
}
async function RegisterForn() {

    const FieldsForn = await getFieldsForn();

    if (FieldsForn.cnpj != Number) {
        showMessage('CNPJ não pode ser diferente de numero', 'warning');
        return;
    }

    if (FieldsForn.cnpj == ""|| FieldsForn.name_company == "" || FieldsForn.fantasy_name == "") {
        showMessage('Campos não podem ficar vazios', 'warning');
    }

    let responseForn = {
        name_company: FieldsForn.name_company,
        fantasy_name: FieldsForn.fantasy_name,
        email: FieldsForn.email,
        phone: FieldsForn.phone,
        address: FieldsForn.address,
        city: FieldsForn.city,
        state: FieldsForn.state,
        cnpj: FieldsForn.cnpj,
    }

    try {

        let url = "";

        const response = await fetch(url, {
            method: "POST",
            headers: {
                '': 'Content-Type',
            },
            body: JSON.stringify(responseForn)
        })

        const responseBody = await response.json();

        if (responseBody.success) {
            showMessage('', '');
        } else {
            showMessage('', '');
        }

    } catch (error) {
        showMessage('Erro na requisição' + error, 'error')
    }
}