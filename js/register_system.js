function FieldsUsers() {
    return {
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
        name: Fields.name,
        email: Fields.email,
        password: Fields.password,
        phone: Fields.phone,
        userFunction: Fields.userFunction,
        commission: Fields.commission,
        targetCommission: Fields.targetCommission,
        access: Fields.access
    }

    console.log(responseFields);

    const continueRegisterUsers = confirm("Deseja cadastrar realmente esse usuário?");

    if (continueRegisterUsers) {
        try {

            let url = `${BASE_CLASS}registers.php`;

            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ responseFields })
            })

            const responseBody = await response.json();

            if (responseBody.success) {
                window.alert("Usuário " + Fields.name + " cadastrado com sucesso!");
            }

        } catch (error) {
            window.alert("Erro ao fazer requisição" + error);
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