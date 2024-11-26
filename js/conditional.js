let SelectedProducts = [];

window.onload = function () {
    getProduct();
    getClients();
    getUsers();
};

const getUsers = async () => {
    try {

        let url = `${BASE_CONTROLLERS}searchs.php`;

        const response = await fetch(url, {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: new URLSearchParams({ users_list: "true" }),
        })

        if (!response.ok) {
            showMessage('Erro ao buscar vendedor', 'error');
            return;
        }

        const users = await response.json();

        const dropdownUsersMenu = document.getElementById('usersDropdownMenu');
        dropdownUsersMenu.querySelectorAll("li:not(:first-child)").forEach(li => li.remove());

        users.forEach(({ id, name }) => {
            const item = document.createElement("li");
            const button = document.createElement("button");

            button.classList.add("dropdown-item");
            button.textContent = name;
            button.dataset.user = JSON.stringify({ id, name });

            button.addEventListener("click", () => {
                const dropdownButton = document.getElementById("usersDropdown");
                dropdownButton.textContent = name;
                dropdownButton.dataset.user = JSON.stringify({ id, name });
            });

            item.appendChild(button);
            dropdownUsersMenu.appendChild(item);
        });

        const searchInput = document.getElementById("userSearch");
        searchInput.addEventListener("input", () => {
            const filter = searchInput.value.toLowerCase();
            const items = dropdownUsersMenu.querySelectorAll(".dropdown-item");

            items.forEach(item => {
                const text = item.textContent.toLowerCase();
                item.style.display = text.includes(filter) ? "" : "none";
            });
        });
    } catch (error) {
        console.log('Erro ao fazer requisição: ' + error.message);
    }
}
const getClients = async () => {
    try {

        let url = `${BASE_CONTROLLERS}searchs.php`;

        const response = await fetch(url, {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: new URLSearchParams({ client_list: "true" }),
        })

        if (!response.ok) {
            showMessage('Erro ao buscar cliente', 'error');
            return;
        }

        const clients = await response.json();

        const dropdownClientMenu = document.getElementById('clientDropdownMenu');
        dropdownClientMenu.querySelectorAll("li:not(:first-child)").forEach(li => li.remove());

        clients.forEach(({ id, name }) => {
            const item = document.createElement("li");
            const button = document.createElement("button");

            button.classList.add("dropdown-item");
            button.textContent = name;
            button.dataset.client = JSON.stringify({ id, name });

            button.addEventListener("click", () => {
                const dropdownButton = document.getElementById("clientDropdown");
                dropdownButton.textContent = name;
                dropdownButton.dataset.client = JSON.stringify({ id, name });
            });

            item.appendChild(button);
            dropdownClientMenu.appendChild(item);
        });

        const searchInput = document.getElementById("clientSearch");
        searchInput.addEventListener("input", () => {
            const filter = searchInput.value.toLowerCase();
            const items = dropdownClientMenu.querySelectorAll(".dropdown-item");

            items.forEach(item => {
                const text = item.textContent.toLowerCase();
                item.style.display = text.includes(filter) ? "" : "none";
            });
        });

    } catch (error) {
        console.log('Erro ao fazer requisição: ' + error.message);
    }
}
const getProduct = async () => {
    try {

        let url = `${BASE_CONTROLLERS}searchs.php`;
        const response = await fetch(url, {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: new URLSearchParams({ product_list: "true" }),
        });

        if (!response.ok) showMessage('Erro ao fazer a requisição', 'error');

        const products = await response.json();

        if (products.error) {
            console.error(products.error);
            return;
        }

        const dropdownMenu = document.getElementById("productDropdownMenu");

        dropdownMenu.querySelectorAll("li:not(:first-child)").forEach(li => li.remove());

        products.forEach(({ id, name, value_product, stock_quantity }) => {
            const item = document.createElement("li");
            const button = document.createElement("button");

            button.classList.add("dropdown-item");
            button.textContent = name;
            button.dataset.product = JSON.stringify({ id, name, value_product, stock_quantity });

            button.addEventListener("click", () => {
                const selectedProduct = JSON.parse(button.dataset.product);
                const dropdownButton = document.getElementById("productDropdown");

                dropdownButton.textContent = selectedProduct.name;
                dropdownButton.dataset.product = JSON.stringify(selectedProduct);

                document.getElementById("quantity").value = 1;
                document.getElementById("price-unit").value = selectedProduct.value_product;
            });


            item.appendChild(button);
            dropdownMenu.appendChild(item);
        });

        const searchInput = document.getElementById("productSearch");
        searchInput.addEventListener("input", () => {
            const filter = searchInput.value.toLowerCase();
            const items = dropdownMenu.querySelectorAll(".dropdown-item");

            items.forEach(item => {
                const text = item.textContent.toLowerCase();
                item.style.display = text.includes(filter) ? "" : "none";
            });
        });
    } catch (error) {
        console.error("Erro ao buscar produtos: " + error.message);
    }
};
const addProductTable = () => {
    const dropdownButton = document.getElementById('productDropdown');
    const selectedProductData = dropdownButton.dataset.product;

    if (!selectedProductData) {
        showMessage('Selecione um produto antes de adicionar!', 'warning');
        return;
    }

    const selectedProduct = JSON.parse(selectedProductData);
    const quantity = parseInt(document.getElementById('quantity').value, 10);
    const priceUnit = parseFloat(document.getElementById('price-unit').value);

    if (selectedProduct && quantity > 0 && priceUnit > 0) {
        const tableBody = document.getElementById('productTableBody');
        const newRow = document.createElement('tr');
        const total = quantity * priceUnit;

        newRow.innerHTML = `
            <td>${selectedProduct.name}</td>
            <td>
                <input type="number" value="${quantity}" onchange="UpdateQuantity(this, '${selectedProduct.id}')" class="form-control quantity-input" />
            </td>
            <td>
                <input type="number" value="${priceUnit}" class="form-control price-input" />
            </td>
            <td class="total-cell">${numberFormat(total).replace('.', ',')}</td>
            <td>
                <button type="button" onclick="RemoveProduct(this)" class="btn btn-danger btn-sm">Excluir</button>
            </td>
        `;

        SelectedProducts.push({
            ProductId: selectedProduct.id,
            ProductName: selectedProduct.name,
            ProductQuantity: quantity,
            ProductPrice: priceUnit
        });

        tableBody.appendChild(newRow);
        addInputEventListeners(newRow);
        document.getElementById('addProductForm').reset();
        updateTotal();
        updateSubTotal();

        const modal = bootstrap.Modal.getInstance(document.getElementById('addProductModal'));
        modal.hide();
    } else {
        showMessage('Preencha todos os campos corretamente!', 'warning');
    }
};

const RemoveProduct = (button) => {
    const row = button.closest('tr');
    if (row) {
        row.remove();
    }
}

function UpdateQuantity(element, id) {
    const newQuantity = parseInt(element.value, 10);
    const productIndex = SelectedProducts.findIndex(product => product.ProductId === id);

    if (productIndex !== -1) {
        SelectedProducts[productIndex].ProductQuantity = newQuantity;

        const priceUnit = SelectedProducts[productIndex].ProductPrice;
        const total = newQuantity * priceUnit;

        const row = element.closest('tr');
        const totalCell = row.querySelector('.total-cell');
        totalCell.textContent = numberFormat(total).replace('.', ',');

        updateTotal();
        updateSubTotal();
    } else {
        console.log('Produto não encontrado no array selecionado.');
    }
}
const addInputEventListeners = (row) => {
    const quantityInput = row.querySelector('.quantity-input');
    const priceInput = row.querySelector('.price-input');
    const totalCell = row.querySelector('.total-cell');

    const updateRowTotal = () => {
        const quantity = parseInt(quantityInput.value, 10) || 0;
        const priceUnit = parseFloat(priceInput.value.replace(',', '.')) || 0;
        const total = quantity * priceUnit;

        totalCell.textContent = total.toFixed(2);
        updateTotal();
    };

    quantityInput.addEventListener('input', updateRowTotal);
    priceInput.addEventListener('input', updateRowTotal);
};

const updateSubTotal = () => {
    const rows = document.querySelectorAll('#productTableBody tr');
    let total = 0;

    rows.forEach(row => {
        const totalCell = row.querySelector('.total-cell');
        const totalValue = parseFloat(totalCell.textContent.replace(',', '.'));
        total += totalValue;
    });
    document.getElementById('sub-total').value = total.toFixed(2).replace('.', ',');
}
const updateTotal = () => {
    const rows = document.querySelectorAll('#productTableBody tr');
    const discountInput = document.getElementById('discount');

    let discount = parseFloat(discountInput.value.replace(',', '.'));
    let total = 0;

    rows.forEach(row => {
        const totalCell = row.querySelector('.total-cell');
        const totalValue = parseFloat(totalCell.textContent.replace(',', '.'));
        total += totalValue;
    });

    if (isNaN(discount)) {
        discount = 0;
    }

    total -= discount;

    document.getElementById('total').value = total.toFixed(2).replace('.', ',');
};

const getFields = () => {
    const dropdownButtonUser = document.getElementById('usersDropdown');
    const selectedUserDataUser = JSON.parse(dropdownButtonUser.dataset.user).id;
    const clientNameData = JSON.parse(dropdownButtonUser.dataset.user).name

    const dropdownButtonClient = document.getElementById('clientDropdown');
    const selectedUserDataClient = JSON.parse(dropdownButtonClient.dataset.client).id;
    const userNameData = JSON.parse(dropdownButtonClient.dataset.client).name;

    return {
        type: {
            type: 'registerconditional'
        },
        values: {
            dateReturn: document.getElementById('date-return').value,
            dateNow: document.getElementById('date-now').value,

            subTotal: document.getElementById('sub-total').value.replace(',', '.'),
            total: document.getElementById('total').value.replace(',', '.'),
            discount: document.getElementById('discount').value.replace(',', '.'),

            obs: document.getElementById('obs').value,

            ClientName: clientNameData,
            UserName: userNameData,
            UserId: selectedUserDataUser,
            ClientId: selectedUserDataClient,
        },
    }
}
const printReceipt = (data) => {
    const receiptWindow = window.open('', '_blank', 'width=300,height=600');
    if (receiptWindow) {
        const receiptHTML = `
            <html>
                <head>
                    <title>Comprovante da Condicional</title>
                    <style>
                        @media print {
                            body, html {
                                margin: 0;
                                padding: 0;
                                width: 58mm;
                                font-size: 12px;
                                font-family: 'Courier New', monospace;
                            }

                            .header {
                                text-align: center;
                                margin-bottom: 15px;
                                font-weight: bold;
                            }

                            .details {
                                margin-bottom: 15px;
                                font-size: 14px;
                            }

                            .products {
                                margin-bottom: 20px;
                            }

                            table {
                                width: 100%;
                                border-collapse: collapse;
                                margin-bottom: 10px;
                                font-size: 12px;
                                margin-top: 10px;
                            }

                            table, th, td {
                                border: 1px solid #000;
                            }

                            th, td {
                                padding: 5px;
                                text-align: left;
                            }

                            th {
                                background-color: #f0f0f0;
                                font-weight: bold;
                            }

                            td {
                                padding-left: 5px;
                                padding-right: 5px;
                            }

                            .total {
                                font-weight: bold;
                                text-align: right;
                                margin-top: 15px;
                                font-size: 14px;
                            }

                            .signature {
                                margin-top: 20px;
                                border-top: 1px solid #000;
                                padding-top: 10px;
                                text-align: center;
                                margin-bottom: 10px;
                            }

                            .signature p {
                                margin-top: 10px;
                            }
                        }
                    </style>
                </head>
                <body>
                    <div class="header">
                        <h3>Comprovante</h3>
                        <p>Data: ${data.date}</p>
                    </div>
                    <div class="details">
                        <p><strong>Cliente:</strong> ${data.clientName}</p>
                        <p><strong>Atendente:</strong> ${data.userName}</p>
                        <p><strong>Total:</strong> R$ ${numberFormat(data.totalValue)}</p>
                    </div>
                    <div class="products">
                        <h4>Produtos:</h4>
                        <table>
                            <thead>
                                <tr>
                                    <th>Produto</th>
                                    <th>Quantidade</th>
                                    <th>Preço Unitário</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${Array.isArray(data.SelectedProducts) && data.SelectedProducts.length > 0 ?
                                    data.SelectedProducts.map(product => `
                                                                <tr>
                                                                    <td>${product.productName}</td>
                                                                    <td>${product.productQuantity}</td>
                                                                    <td>R$ ${numberFormat(product.productPrice)}</td>
                                                                    <td>R$ ${(product.productQuantity * product.productPrice).toFixed(2)}</td>
                                                                </tr>
                                                            `).join('') :
                                    '<tr><td colspan="4">Nenhum produto selecionado</td></tr>'
                                }
                            </tbody>
                        </table>
                    </div>
                    <div class="total">
                        <p><strong>Total Geral:</strong> R$ ${numberFormat(data.totalValue)}</p>
                    </div>
                    <div class="signature">
                        <p><strong>Assinatura do Cliente:</strong></p>
                        <p>_________________________________________</p>
                    </div>
                </body>
            </html>
        `;

        receiptWindow.document.write(receiptHTML);
        receiptWindow.document.close();

        // Espera o conteúdo ser carregado completamente antes de chamar a impressão
        receiptWindow.onload = () => {
            setTimeout(() => {
                receiptWindow.print();
                receiptWindow.close();
            }, 500); // Espera 500ms para garantir que tudo esteja renderizado
        };
    } else {
        showMessage('Erro ao abrir a janela para impressão', 'error');
    }
};
const RegisterConditional = async () => {
    const { values, type } = await getFields();

    if (!values.ClientId || values.ClientId === "") {
        showMessage('Cliente não foi selecionado', 'warning');
        return;
    }
    if (!values.UserId || values.UserId === "") {
        showMessage('Usuário não foi selecionado', 'warning');
        return;
    }

    if (SelectedProducts.length === 0) {
        showMessage('Nenhum Produto selecionado', 'warning');
        return;
    }

    let responseCond = {
        type: type.type,
        dateNow: values.dateNow,
        dateReturn: values.dateReturn,
        subTotal: values.subTotal,
        discount: values.discount,
        total: values.total,
        obs: values.obs,
        UserId: values.UserId,
        ClientId: values.ClientId,
        SelectedProducts: SelectedProducts
    };

    continueMessage("Deseja realmente registrar essa condicional?", "Sim", "Não", async function () {
        try {
            let url = `${BASE_CONTROLLERS}registers.php`;

            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(responseCond)
            });

            if (!response.ok) {
                showMessage('Erro ao enviar dados, contate o suporte', 'error');
                return;
            }

            const responseBody = await response.json();

            if (responseBody && responseBody.success) {
                showMessage('Condicional aberta com sucesso', 'success');

                const printSales = {
                    date: new Date().toLocaleString(),
                    clientName: values.ClientName,
                    userName: values.UserName,
                    totalValue: values.total,
                    SelectedProducts: SelectedProducts.map(product => ({
                        productName: product.ProductName,
                        productQuantity: product.ProductQuantity,
                        productPrice: parseFloat(product.ProductPrice),
                    }))
                };

                setTimeout(() => {
                    continueMessage("Deseja imprimir comprovamente", "Sim", "Não", async function () {
                        printReceipt(printSales);
                    }, function () {
                        showMessage('Não imprimiu o comprovante', 'info')
                    })
                }, 4000)

                setTimeout(() => {
                    location.reload();
                }, 7000);

            } else {
                showMessage('Erro ao finalizar condicional ' + responseBody.message, 'error');
            }

        } catch (error) {
            showMessage('Erro ao fazer requisição ' + error, 'error');
        }
    });
};
