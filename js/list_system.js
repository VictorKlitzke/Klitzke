async function InativarInvo(button) {

    const id_request_inativar = button.getAttribute('data-id');

    if (!id_request_inativar) {
        window.alert("ID indentificado");
        return;
    }

    const continueInativar = confirm("Desseja realmente inativar pedido?");

    if (continueInativar) {
        try {

            let url = "http://localhost/Klitzke/ajax/inativar_request.php";

            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({id_inativar: id_request_inativar})
            })

            const responseText = await response.text();

            let result;
            try {
                result = JSON.parse(responseText);
            } catch (e) {
                window.alert("Erro inesperado ao processar a inativação do pedido. Entre em contato com o suporte.");
                return;
            }

            if (result.success) {
                window.alert("Pedido inativado com suceesso");
            } else {
                window.alert("Erro ao inativar pedido: " + result.message);
            }

        } catch (error) {
            window.alert(" Erro ao fazer requisiçao, entre em contato com o suporte! " + error);
        }
    }
}

async function ShowOnPage(button) {

    const id_product_page = button.getAttribute('data-id');

    if (!id_product_page) {
        window.alert("Impossivel continuar sem o ID do produto");
        return;
    }

    const continuePage = confirm("Deseja realmente mostrar o produto na pagina?")

    if (continuePage) {
        try {

            let url = "http://localhost/Klitzke/ajax/show_on_page.php";

            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({id_product: id_product_page})
            })

            const responseText = response.text();

            let result;
            try {
                result = JSON.parse(responseText);
            } catch (e) {
                window.alert("Erro inesperado ao processar a inativação do pedido. Entre em contato com o suporte.");
                return;
            }

            if (result.success) {
                window.alert("Produto está mostrando na pagina com sucesso")
            } else {
                window.alert("Erro ao mostrar produto na pagina" + result.message)
            }

        } catch (error) {
            window.alert(" Erro ao fazer requisiçao, entre em contato com o suporte! " + error);
        }
    }

}

async function CancelSales(button) {
    const id_sales_cancel = button.getAttribute('data-id');;

    if (!id_sales_cancel) {
        window.alert("ID da venda nao identificado");
        return;
    }

    const constinueCancel = confirm("Deseja realmente cancelar essa venda?");

    if (constinueCancel) {
       try {
           let url = "http://localhost/Klitzke/ajax/cancel_sales.php";

           const response = await fetch(url,{
               method: 'POST',
               headers: {
                   'Content-Type': 'application/json',
               },
               body: JSON.stringify({id_sales_cancel: id_sales_cancel})
           })

           console.log(JSON.stringify({id_sales_cancel: id_sales_cancel}))

           const responseText = await response.text();
           let result;

           try {
               result = JSON.parse(responseText)
           } catch (error) {
               window.alert("Erro interno, entre em contato com o suporte" + error)
           }

           if (result.success) {
               window.alert("Venda cancelada com sucesso!");
           } else {
               window.alert("Erro ao tentar cancelar a venda" + result.getMessage());
           }

       } catch (error) {
           window.alert("Erro interno, entre em contato com o suporte" + error)
           return;
       }
    }
}

async function ReopenSales(button) {
    const id_sales_reopen = button.getAttribute("data-id");

    if (!id_sales_reopen) {
        window.alert("ID da venda nao encontrado");
    }

    const continueReopen = confirm("Deseja realmente reabrir venda?");

    if (continueReopen) {
        try {
            let = "";

            const response = await fetch(url,{
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({id_sales_cancel: id_sales_cancel})
            })

            const responseText = response.text();

            let result;

            try {
                result = JSON.parse(responseText);
            } catch (error) {
                throw new error;
            }

            if (result.sucess) {
                window.alert("Venda reaberta com sucesso");
            } else {
                window.alert("Erro ao reabrir venda" + result.getMessage());
            }

        } catch (error) {
            window.alert("Erro interno, entre em contato com o suporte");
            throw new error;
            return;
        }
    }
}