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

async function RegisterClients() {
    try {

    } catch (error) {
        window.alert("Erro ao fazer requisição" + error);
    }
}

async function RegisterCompany() {
    try {

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