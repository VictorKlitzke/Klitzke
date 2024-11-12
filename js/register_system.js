const filesContent = document.getElementById('file-pdf');
const checkboxes = document.querySelectorAll('.form-check-input');
const selects = document.querySelectorAll('.form-select');

const InventarYScreen = document.getElementById('inventory-screen');
const idInventary = document.getElementById('idInventary');

localStorage.setItem('modalAberto', 'true');

document.addEventListener("DOMContentLoaded", () => {
    idInventary.textContent = "Código do Inventário";
});

function ClearLocalStorage() {
    localStorage.removeItem('idInventary');
    idInventary.textContent = "Código do Inventário";
}

checkboxes.forEach(checkbox => {
    checkbox.addEventListener('change', function () {
        const cardBody = this.closest('.card-body');
        const selectsInCard = cardBody.querySelectorAll('.form-select');

        selectsInCard.forEach(select => {
            select.value = this.checked ? 'sim' : 'nao';
        });
    });
});

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
            access: document.getElementById("access").value,
            typeUsers: document.getElementById("user-type").value
        },
        inputs: {
            name: document.getElementById("name"),
            email: document.getElementById("email"),
            password: document.getElementById("password"),
            phone: document.getElementById("phone"),
            userFunction: document.getElementById("function"),
            commission: document.getElementById("commission"),
            targetCommission: document.getElementById("target_commission"),
            access: document.getElementById("access"),
            typeUsers: document.getElementById("user-type")
        },
        menuaccess: {
            registeruser: document.getElementById("cadastros-submenu-usuario").value,
            registerclients: document.getElementById("cadastros-submenu-clientes").value,
            registerforn: document.getElementById("cadastros-submenu-fornecedores").value,

            sales: document.getElementById("faturamento-submenu-vendas").value,
            listSales: document.getElementById("faturamento-submenu-lista-vendas").value,

            orders: document.getElementById("food-submenu-pedidos").value,
            listOrders: document.getElementById("food-submenu-lista-pedidos").value,
            registerTables: document.getElementById("food-submenu-cadastro-mesa").value,

            registerBoxPdv: document.getElementById("fluxo-caixa-submenu-abertura").value,
            listBoxPdv: document.getElementById("fluxo-caixa-submenu-lista").value,
            reportsBoxPdv: document.getElementById("fluxo-caixa-submenu-relatorio").value,

            requestPurchase: document.getElementById("suprimentos-submenu-solicitacao").value,
            listrequestPurchase: document.getElementById("suprimentos-submenu-lista").value,

            listProducts: document.getElementById("controle-estoque-submenu-lista").value,
            registerProducts: document.getElementById("controle-estoque-submenu-produtos").value,
            registerInventory: document.getElementById("controle-estoque-submenu-inventory").value,
            registerPortion: document.getElementById("controle-estoque-submenu-open-portion"),

            dashboardADM: document.getElementById("administrativo-submenu-dashboards").value,

            financialControl: document.getElementById("controle-financeiro-submenu-pagamentos").value,

            myCompany: document.getElementById("minha-empresa-submenu").value

        }
    };
}
async function RegisterUsers() {

    const { values, type, inputs, menuaccess } = await FieldsUsers();

    if (values.name === "" || values.password === "" || values.email === "" || values.phone === "" || values.userFunction === "") {
        showMessage('Preencha todos os campos!', 'warning',);

        if (values.name === "") inputs.name.classList.add('error');
        if (values.password === "") inputs.password.classList.add('error');
        if (values.email === "") inputs.email.classList.add('error');
        if (values.userFunction === "") inputs.userFunction.classList.add('error');
        if (values.phone === "") inputs.phone.classList.add('error');
        setTimeout(() => {
            inputs.name.classList.remove('error');
            inputs.password.classList.remove('error');
            inputs.email.classList.remove('error');
            inputs.userFunction.classList.remove('error');
            inputs.phone.classList.remove('error');
        }, 3000);

        return;
    }

    if (isNaN(values.targetCommission) || isNaN(values.commission) || isNaN(values.phone)) {
        showMessage('Campos só aceita numeros', 'warning');

        if (!isNaN(values.targetCommission)) inputs.targetCommission.classList.add('error');
        if (!isNaN(values.commission)) inputs.commission.classList.add('error');
        if (!isNaN(values.phone)) inputs.phone.classList.add('error');
        setTimeout(() => {
            inputs.commission.classList.remove('error');
            inputs.targetCommission.classList.remove('error');
            inputs.phone.classList.remove('error');
        }, 3000);

        return;
    }

    if (values.password.length < 6) {
        showMessage('Senha tem que ser maior que 6 digitos', 'warning');

        if (values.password.length < 6) inputs.password.classList.add('error');
        setTimeout(() => {
            inputs.password.classList.remove('error');
        }, 3000);

        return;
    }

    let responseFields = {
        type: type.type,
        name: values.name,
        email: values.email,
        password: values.password,
        phone: values.phone,
        userFunction: values.userFunction,
        commission: values.commission,
        targetCommission: values.targetCommission,
        access: values.access,
        registerusers: menuaccess.registeruser,
        registerclients: menuaccess.registerclients,
        registerforn: menuaccess.registerforn,
        sales: menuaccess.sales,
        listSales: menuaccess.listSales,
        orders: menuaccess.orders,
        listOrders: menuaccess.listOrders,
        registerTables: menuaccess.registerTables,
        typeUsers: values.typeUsers,
        registerBoxPdv: menuaccess.registerBoxPdv,
        listBoxPdv: menuaccess.listBoxPdv,
        reportsBoxPdv: menuaccess.reportsBoxPdv,
        requestPurchase: menuaccess.requestPurchase,
        listrequestPurchase: menuaccess.listrequestPurchase,
        listProducts: menuaccess.listProducts,
        registerProducts: menuaccess.registerProducts,
        registerInventory: menuaccess.registerInventory,
        registerPortion: menuaccess.registerPortion,
        dashboardADM: menuaccess.dashboardADM,
        financialControl: menuaccess.financialControl,
        myCompany: menuaccess.myCompany
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

                const responseBody = await response.text();
                try {
                    const ResponseParseUser = JSON.parse(responseBody);

                    if (ResponseParseUser.success && ResponseParseUser) {
                        showMessage("Usuário " + values.name + " cadastrado com sucesso!", 'success');

                        setTimeout(() => {
                            location.reload();
                        }, 2000);

                    } else {
                        showMessage("Erro ao fazer cadastro: " + responseBody.message, 'error');
                    }
                } catch (error) {
                    showMessage("Erro ao fazer requisição: " + error, 'error');
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

                setTimeout(() => {
                    location.reload();
                }, 2000);

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
        type: {
            type: 'company',
        },
        values: {
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
    };
}
async function RegisterCompany() {

    const { values, type, inputs } = await getFieldsCompany();

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

    let responseCompany = {
        type: type.type,
        name: values.name,
        email: values.email,
        cnpj: values.cnpj,
        state_registration: values.state_registration,
        phone: values.phone,
        address: values.address,
        city: values.city,
        state: values.state,
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

        const responseBody = await response.json();

        if (responseBody.success) {
            showMessage("Empresa " + values.name + " cadastrado com sucesso!", 'success');

            setTimeout(() => {
                location.reload();
            }, 2000);

        } else {
            showMessage(responseBody.message || "Erro ao fazer cadastro " + values.name, 'error');
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

    continueMessage("Continuar com o cadastro?", "Sim", "Não", async function () {
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

                setTimeout(() => {
                    location.reload();
                }, 2000);

            } else {
                showMessage(responseBody.message || 'Erro ao cadastrar mesa' + responseBody.message, 'error');
            }

        } catch (error) {
            showMessage("Erro ao fazer requisição" + error, 'error');
        }
    }, function () {
        showMessage('Erro ao fazer requisição' + error, 'error')
    })
}

const getFieldsAccount = () => {
    return {
        type: {
            type: 'account',
        },
        values: {
            pix: document.getElementById('pix').value.trim(),
            name_holder: document.getElementById('name_holder').value.trim(),
            account_number: document.getElementById('account_number').value.trim(),
            bank: document.getElementById('bank').value.trim(),
            agency: document.getElementById('agency').value.trim(),
            typeAccount: document.getElementById('type-account').value.trim(),
        },
        inputs: {
            pix: document.getElementById('pix'),
            name_holder: document.getElementById('name_holder'),
            account_number: document.getElementById('account_number'),
            bank: document.getElementById('bank'),
            agency: document.getElementById('agency'),
            typeAccount: document.getElementById('type-account'),
        }
    };
}
async function RegisterAccount() {
    const { values, inputs, type } = await getFieldsAccount();
    const lettersRegex = /^[A-Za-z\s]+$/;

    if (values.pix === "" || values.name_holder === "" || values.bank === "" || values.agency === "" || values.typeAccount === "") {
        showMessage('Campos vazios, preencha os campos', 'warning');

        if (values.pix === "") inputs.pix.classList.add('error');
        if (values.name_holder === "") inputs.name_holder.classList.add('error');
        if (values.bank === "") inputs.bank.classList.add('error');
        if (values.agency === "") inputs.agency.classList.add('error');
        if (values.typeAccount === "") inputs.typeAccount.classList.add('error');

        setTimeout(() => {
            inputs.pix.classList.remove('error');
            inputs.name_holder.classList.remove('error');
            inputs.bank.classList.remove('error');
            inputs.agency.classList.remove('error');
            inputs.typeAccount.classList.remove('error');
        }, 3000);

        return;
    }

    if (isNaN(values.pix) || isNaN(values.account_number)) {
        showMessage('Campo PIX inválido ou Numero da Conta, apenas números são aceitos', 'warning');
        inputs.pix.classList.add('error');
        inputs.account_number.classList.add('error');

        setTimeout(() => {
            inputs.pix.classList.remove('error');
            inputs.account_number.classList.remove('error');
        }, 3000);

        return;
    }

    if (!lettersRegex.test(values.name_holder)) {
        showMessage('Nome do titular inválido, não aceita números', 'warning');
        inputs.name_holder.classList.add('error');

        setTimeout(() => {
            inputs.name_holder.classList.remove('error');
        }, 3000);

        return;
    }

    let responseAccount = {
        type: type.type,
        pix: values.pix,
        name_holder: values.name_holder,
        bank: values.bank,
        agency: values.agency,
        typeAccount: values.typeAccount,
        account_number: values.account_number
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
            const responseBody = await response.json();

            if (responseBody.success) {
                showMessage('Conta cadastrada com sucesso', 'success');
                setTimeout(() => {
                    location.reload();
                }, 2000);
            } else {
                showMessage('Erro ao cadastrar conta bancária: ' + responseBody.message, 'error');
            }
        } catch (error) {
            showMessage("Erro ao fazer requisição: " + error, 'error');
        }
    }, function () {
        showMessage('Cadastro cancelado', 'warning');
    });
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

    const { type, values, inputs } = await getFieldsForn();

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

            const responseBody = await response.json();

            if (responseBody.success) {
                showMessage('Fornecedor ' + values.name_company + ' cadastrado com sucesso', 'success');

                setTimeout(() => {
                    location.reload();
                }, 2000);

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

const getFieldsProducts = () => {
    const flowElement = document.getElementById('flow');
    const flowFile = flowElement.files.length > 0 ? flowElement.files[0] : null;

    return {
        type: {
            type: 'products',
        },
        values: {
            name: document.getElementById('name').value,
            quantity: document.getElementById('quantity').value,
            stock_quantity: document.getElementById('stock_quantity').value,
            barcode: document.getElementById('barcode').value,
            value_product: document.getElementById('value_product').value,
            cost_value: document.getElementById('cost_value').value,
            reference: document.getElementById('reference').value,
            model: document.getElementById('model').value,
            brand: document.getElementById('brand').value,
            size: document.getElementById('size').value,
            flow: flowFile,
        },
        inputs: {
            name: document.getElementById('name'),
            quantity: document.getElementById('quantity'),
            stock_quantity: document.getElementById('stock_quantity'),
            barcode: document.getElementById('barcode'),
            value_product: document.getElementById('value_product'),
            cost_value: document.getElementById('cost_value'),
            reference: document.getElementById('reference'),
            model: document.getElementById('model'),
            brand: document.getElementById('brand'),
            size: document.getElementById('size'),
            flow: flowElement,
        }
    }
}
async function RegisterProducts() {
    const { type, values, inputs } = await getFieldsProducts();
    let InputValueCost = values.cost_value.replace(/^R\$\s?/, "");
    let InputValueProduct = values.value_product.replace(/^R\$\s?/, "");

    if (values.barcode == "" || values.name == "" || values.reference == "" || values.quantity == ""
        || values.stock_quantity == "" || values.cost_value == "" || values.value_product == ""
    ) {
        showMessage('Quantidade e Quantidade estoque não podem ser diferente de número', 'warning');

        if (values.barcode === "") inputs.barcode.classList.add('error');
        if (values.name === "") inputs.name.classList.add('error');
        if (values.reference === "") inputs.reference.classList.add('error');
        if (values.quantity === "") inputs.quantity.classList.add('error');
        if (values.stock_quantity === "") inputs.stock_quantity.classList.add('error');
        if (values.cost_value === "") inputs.cost_value.classList.add('error');
        if (values.value_product === "") inputs.value_product.classList.add('error');
        setTimeout(() => {
            inputs.barcode.classList.remove('error');
            inputs.name.classList.remove('error');
            inputs.reference.classList.remove('error');
            inputs.quantity.classList.remove('error');
            inputs.stock_quantity.classList.remove('error');
            inputs.cost_value.classList.remove('error');
            inputs.value_product.classList.remove('error');
        }, 3000);

        return;
    }

    if (values.cost_value.length > values.value_product.length) {
        showMessage('Valor de custo não pode ser maior que valor do produto', 'warning');

        if (values.cost_value.length > values.value_product.length) inputs.cost_value.classList.add('error');
        setTimeout(() => {
            inputs.cost_value.classList.remove('error');
        }, 3000);

        return;
    }

    const quantity = Number(values.quantity);
    const stock_quantity = Number(values.stock_quantity);
    const barcode = Number(values.barcode);
    const cost_value = parseCurrency(InputValueCost);
    const value_product = parseCurrency(InputValueProduct);

    if (isNaN(quantity) || isNaN(stock_quantity) ||
        isNaN(barcode) || isNaN(value_product) || isNaN(cost_value)
    ) {
        showMessage('Campos não podem ser Strings', 'warning');

        if (isNaN(values.barcode)) inputs.barcode.classList.add('error');
        if (isNaN(values.quantity)) inputs.quantity.classList.add('error');
        if (isNaN(values.stock_quantity)) inputs.stock_quantity.classList.add('error');
        if (isNaN(InputValueCost)) inputs.cost_value.classList.add('error');
        if (isNaN(InputValueProduct)) inputs.value_product.classList.add('error');
        setTimeout(() => {
            inputs.barcode.classList.remove('error');
            inputs.quantity.classList.remove('error');
            inputs.stock_quantity.classList.remove('error');
            inputs.cost_value.classList.remove('error');
            inputs.value_product.classList.remove('error');
        }, 3000);

        return;
    }

    let imageBase64 = "";
    if (values.flow instanceof File) {
        const reader = new FileReader();
        reader.onload = async () => {
            imageBase64 = reader.result.split(',')[1];
            await sendProductData(type, values, imageBase64, quantity, stock_quantity, barcode, cost_value, value_product);
        };
        reader.onerror = (error) => {
            showMessage("Erro ao ler o arquivo" + error, 'error');
        };
        reader.readAsDataURL(values.flow);
    } else {
        await sendProductData(type, values, imageBase64, quantity, stock_quantity, barcode, cost_value, value_product);
    }

    function parseCurrency(value) {
        return parseFloat(value.replace(',', '.'));
    }

    async function sendProductData(type, values, imageBase64, quantity, stock_quantity, barcode, cost_value, value_product) {
        let responseProduct = {
            type: type.type,
            name: values.name,
            quantity: quantity,
            stock_quantity: stock_quantity,
            barcode: barcode,
            value_product: value_product,
            cost_value: cost_value,
            reference: values.reference,
            model: values.model,
            brand: values.brand,
            size: values.size,
            flow: imageBase64
        };

        continueMessage("Deseja realmente continuar com o cadastro?", "Sim", "Não", async function () {
            try {
                let url = `${BASE_CONTROLLERS}registers.php`;

                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(responseProduct)
                });

                const responseBody = await response.json();

                if (responseBody.success) {
                    showMessage('Produto ' + values.name + ' cadastrado com sucesso', 'success');

                    setTimeout(() => {
                        location.reload();
                    }, 2000);

                } else {
                    showMessage('Erro ao cadastrar produto' + responseBody.message, 'error');
                }

            } catch (error) {
                showMessage("Erro ao fazer requisição: " + error, 'error');
            }
        }, function () {
            ShowMessage('Operação cancelada', 'warning');
        })
    }
}

const getFieldsBoxPdv = () => {
    return {
        type: {
            type: 'boxpdv',
        },
        values: {
            value: document.getElementById('value').value,
            observation: document.getElementById('observation').value,
        },
        inputs: {
            value: document.getElementById('value'),
            observation: document.getElementById('observation'),
        }
    }
}
async function RegisterBoxPdv() {
    const { type, values, inputs } = await getFieldsBoxPdv();

    if (values.value == "" || values.observation == "") {
        showMessage('Campos não podem ficar vazios', 'warning');

        if (values.value === "") inputs.value.classList.add('error');
        if (values.observation === "") inputs.observation.classList.add('error');
        setTimeout(() => {
            inputs.value.classList.remove('error');
            inputs.observation.classList.remove('error');
        }, 3000);

        return;

    }

    function parseCurrency(value) {
        value = value.replace(/[^0-9,.]/g, '');
        value = value.replace(',', '.');
        return parseFloat(value);
    }
    const valueBoxPdv = parseCurrency(values.value);

    let responseBoxPdv = {
        type: type.type,
        value: valueBoxPdv,
        observation: values.observation,
    }

    continueMessage("Deseja realmente abrir o caixa?", "Sim", "Não", async function () {

        try {
            let url = `${BASE_CONTROLLERS}registers.php`;

            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(responseBoxPdv)
            });

            const responseBody = await response.json();

            if (responseBody.success) {
                showMessage('Caixa aberto no valor de ' + values.value, 'success');

                setTimeout(() => {
                    location.reload();
                }, 2000);

            } else {
                showMessage(responseBody.message || 'Erro ao abrir caixa ' + responseBody.error, 'error');
            }

        } catch (error) {
            showMessage('Erro na requisição' + error.message, 'error')
        }

    }, function () {
        showMessage('Abertura de caixa cancelada', 'warning')
    })
}

const getFieldsSangria = () => {
    return {
        type: {
            type: 'sangriapdv',
        },
        values: {
            value: document.getElementById('value').value,
            observation: document.getElementById('observation').value,
        },
        inputs: {
            value: document.getElementById('value'),
            observation: document.getElementById('observation'),
        }
    }
}
async function RegisterSangria() {
    const { type, values, inputs } = await getFieldsSangria();
    let InputValueSangria = values.value.replace(/\D/g, "");

    if (values.value == "" || values.observation == "") {
        showMessage('Campos vazios', 'warning');

        if (values.value === "") inputs.value.classList.add('error');
        if (values.observation === "") inputs.observation.classList.add('error');
        setTimeout(() => {
            inputs.value.classList.remove('error');
            inputs.observation.classList.remove('error');
        }, 3000);

        return;
    }

    let responseSangria = {
        type: type.type,
        value: values.value,
        observation: values.observation,
    }

    continueMessage("Deseja realmente fazer essa retirada?", "Sim", "Não", async function () {

        try {

            let url = `${BASE_CONTROLLERS}registers.php`;

            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(responseSangria)
            });

            const responseBody = await response.json();

            if (responseBody.success) {
                showMessage('Retirada realizada no valor de ' + values.value, 'success');

                setTimeout(() => {
                    location.reload();
                }, 2000);

            } else {
                showMessage(responseBody.message || 'Erro ao fazer retirada de valor ' + responseBody.error, 'error');
            }

        } catch (error) {
            showMessage('Erro na requisição' + error.message, 'error');
        }

    }, function () {
        showMessage('Retirada de caixa cancelada', 'warning');
    })
}

const getFieldsMultiply = () => {
    return {
        type: {
            type: 'multiply',
        },
        values: {
            multiply: document.getElementById('multiply').value,
        },
        inputs: {
            multiply: document.getElementById('multiply')
        }
    }
}
async function RegisterMultiply() {
    const { type, values, inputs } = await getFieldsMultiply();


    if (values.multiply == "") {
        showMessage('Campo vazio', 'warning')

        if (values.multiply === "") inputs.multiply.classList.add('error');
        setTimeout(() => {
            inputs.value.multiply.remove('error');
        }, 3000);


        return;
    }

    let responseMultiply = {
        type: type.type,
        multiply: values.multiply
    }

    continueMessage("Deseja relamente cadastrar um multiplicador?", "Sim", "Não", async function () {

        try {

            let url = `${BASE_CONTROLLERS}registers.php`;

            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(responseMultiply)
            });

            const responseBody = await response.json();

            if (responseBody.success) {
                showMessage('Multiplicador cadatrado na quantidade de ' + values.multiply, 'success');

                setTimeout(() => {
                    location.reload();
                }, 2000);

            } else {
                showMessage(responseBody.message || 'Erro ao cadastrar multiplicador ' + responseBody.error, 'error');
            }

        } catch (error) {
            showMessage('Erro na requisição' + error.message, 'error')
        }

    }, function () {
        showMessage('Registro cancelado', 'warning')
    })
}

const getFieldCreateInventary = () => {
    return {
        type: {
            type: 'createinventary',
        },
        values: {
            inventaryDate: document.getElementById('inventaryDate').value,
            inventaryStatus: document.getElementById('inventaryStatus').value,
            inventaryObs: document.getElementById('inventaryObs').value
        },
        inputs: {
            inventaryDate: document.getElementById('inventaryDate'),
            inventaryStatus: document.getElementById('inventaryStatus'),
            inventaryObs: document.getElementById('inventaryObs')
        }
    }
}
async function Inventaryquantity() {
    const { type, values, inputs } = await getFieldCreateInventary();

    if (values.inventaryStatus == null || values.inventaryDate == null) {
        showMessage('Campos não podem ser vazio', 'warning');

        inputs.inventaryStatus.classList.add('error');
        inputs.inventaryDate.classList.add('error');
        setTimeout(() => {
            inputs.inventaryStatus.classList.remove('error');
            inputs.inventaryDate.classList.remove('error');
        }, 3000);

        return;
    }

    let responseInventary = {
        inventaryStatus: values.inventaryStatus,
        inventaryDate: values.inventaryDate,
        inventaryObs: values.inventaryObs,
        type: type.type
    }

    continueMessage("Deseja realmente criar um novo inventario?", "Sim", "Não", async function () {
        try {

            let url = `${BASE_CONTROLLERS}registers.php`;

            let response = await fetch(url, {
                method: "POST",
                headers: {
                    'Content-Type': 'multipart/form-data',
                },
                body: JSON.stringify(responseInventary)
            });

            if (!response.ok) {
                showMessage('Erro ao conectar com servidor, contate o suporte' + response.status, 'error');
            }

            const responseInve = await response.text();
            let responseParse;
            try {
                responseParse = JSON.parse(responseInve);

                console.log(responseParse);

            } catch (error) {
                showMessage('Erro ao fazer requisição: Resposta inválida do servidor', 'error');
                return;
            }

            if (responseParse && responseParse.success) {
                showMessage('Inventário realizado com sucesso', 'success');

                Inventaryid = responseParse.data.id;
                localStorage.setItem('Inventaryid', Inventaryid)

                idInventary.textContent = Inventaryid;
                document.getElementById('AdjustInventary').style.display = 'block';
                document.getElementById('InventaryListProduct').style.display = 'block';
            } else {
                showMessage('Erro ao tentar fazer inventário: ' + (responseParse ? responseParse.message : 'Resposta vazia'), 'error');
            }
        } catch (error) {
            showMessage('Erro ao fazer requisição' + error, 'error')
        }
    }, function () {
        showMessage('Operação cancelada', 'warning');
    });
}

const getDisplayInvoice = () => {
    const products = [];
    const rows = document.querySelectorAll('table tbody tr');

    rows.forEach(row => {
        const cod_product = row.querySelector('#cod_product').textContent.trim();
        const name_product = row.querySelector('#name_product').value.trim();
        const unit_product = row.querySelector('#unit').value.trim();
        const quantity_product = row.querySelector('#quantity').value.trim();
        const value_product1 = row.querySelector('#value_product').value.trim();

        function parseCurrency(value) {
            value = value.replace(/[^0-9,.]/g, '');
            value = value.replace(',', '.');
            return parseFloat(value);
        }
        const value_product = parseCurrency(value_product1);

        products.push({
            cod_product,
            name_product,
            unit_product,
            quantity_product,
            value_product
        });
    });

    return {
        type: {
            type: 'invoice',
        },
        values: products
    };
};

async function RegisterDisplayInvoice() {
    const { type, values } = await getDisplayInvoice();

    if (values.length === 0 || values.some(product => product.name_product === "" || product.value_product === "")) {
        alert('Campos vazios!');
        return;
    }

    let responseDisplayInvoice = {
        type: type.type,
        products: values
    };

    console.log(responseDisplayInvoice);

    try {
        let url = `http://localhost:3000/klitzke/controllers/registers.php`;

        let response = await fetch(url, {
            method: "POST",
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(responseDisplayInvoice)
        });

        if (!response.ok) {
            showMessage('Erro ao conectar com servidor, contate o suporte' + response.status, 'error');
            return;
        }

        const responseBody = await response.text();
        console.log(responseBody);

        if (responseBody.success) {
            alert('Produtos cadastrados com sucesso, volte à tela inicial');
        } else {
            alert(responseBody.message);
        }

    } catch (error) {
        console.error(error);
    }
}
