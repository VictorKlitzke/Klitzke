<?php

if (!isset($_SESSION['id'])) {
  header("Location: login.php");
  exit();
}
$page_permission = 'register-users';
if (!isset($_SESSION['user_permissions'][$page_permission]) || $_SESSION['user_permissions'][$page_permission] !== 1) {
  header("Location: " . INCLUDE_PATH . "access-denied.php");
  exit();
}

?>

<div class="container-fluid p-4 shadow-lg border rounded-4 bg-light">
  <h2 class="text-dark mb-4">Cadastrar Usuário</h2>
  <div class="row g-3">
    <div class="col-md-6">
      <label class="text-dark" for="name">Nome</label>
      <div class="input-group">
        <div class="input-group-text">@</div>
        <input type="text" id="name" class="form-control border border-dark" placeholder="Digite o nome">
      </div>
      <span id="name-error" class="error-message text-danger">Campo está inválido, ajuste se possível.</span>
    </div>
    <div class="col-md-6">
      <label class="text-dark" for="email">Email</label>
      <input type="email" id="email" class="form-control border border-dark" placeholder="Digite o email">
      <span id="email-error" class="error-message text-danger">Campo está inválido, ajuste se possível.</span>
    </div>
    <div class="col-12">
      <label class="text-dark" for="password">Senha</label>
      <input type="password" id="password" class="form-control border border-dark" placeholder="Digite a senha">
      <span id="password-error" class="error-message text-danger">Campo está inválido, ajuste se possível.</span>
    </div>
    <div class="col-md-6">
      <label class="text-dark" for="function">Função</label>
      <input type="text" id="function" class="form-control border border-dark" placeholder="Digite a função">
      <span id="function-error" class="error-message text-danger">Campo está inválido, ajuste se possível.</span>
    </div>
    <div class="col-md-6">
      <label class="text-dark" for="phone">Telefone</label>
      <input type="text" id="phone" class="form-control border border-dark" placeholder="Digite o telefone">
      <span id="phone-error" class="error-message text-danger">Campo está inválido, ajuste se possível.</span>
    </div>
    <div class="col-md-4">
      <label class="text-dark" for="commission">Comissão</label>
      <input type="number" id="commission" class="form-control border border-dark" placeholder="Digite a comissão">
      <span id="commission-error" class="error-message text-danger">Campo está inválido, ajuste se possível.</span>
    </div>
    <div class="col-md-4">
      <label class="text-dark" for="target_commission">Comissão por Meta</label>
      <input type="number" id="target_commission" class="form-control border border-dark" placeholder="Digite a comissão por meta">
      <span id="target_commission-error" class="error-message text-danger">Campo está inválido, ajuste se possível.</span>
    </div>
    <div class="col-md-4">
      <label class="text-dark" for="access">Nível de Acesso</label>
      <select id="access" class="form-select form-select-sm border border-dark">
        <option value="100">Administrador</option>
        <option value="50">Moderado</option>
        <option value="10">Padrão</option>
      </select>
      <span id="access-error" class="error-message text-danger">Campo está inválido, ajuste se possível.</span>
    </div>
    <div class="col-md-12">
      <label class="text-dark" for="user-type">Selecione o Tipo de Usuário</label>
      <select id="user-type" name="user-type" class="form-select border border-dark">
        <option value="vendedor">Vendedor</option>
        <option value="financeiro">Financeiro</option>
        <option value="estoquista">Estoquista</option>
        <option value="comprador">Comprador</option>
        <option value="suporte">Usuário de Suporte</option>
      </select>
      <span id="error" class="error-message text-danger">Campo está inválido, ajuste se possível.</span>
    </div>
  </div>
</div>

<br>

<div class="container-fluid card shadow-lg p-4 border rounded-4 bg-light">
  <h2 class="text-black mt-4">Menus de Acesso</h2>
  <hr>
  <div class="row g-3">
    <div class="col-md-4 mb-4">
      <div class="card h-100 text-center shadow">
        <div class="card-body">
          <h5 class="card-title">
            Cadastros Gerais
            <input type="checkbox" class="form-check-input" id="check-registers">
          </h5>
          <p class="card-text">Gerencie todos os cadastros de forma eficiente.</p>
          <div class="col">
            <label for="cadastros-submenu-usuario" class="mt-2">Usuário</label>
            <select id="cadastros-submenu-usuario" class="form-select">
              <option value="sim">Sim</option>
              <option value="nao" selected>Não</option>
            </select>
          </div>
          <div class="col">
            <label for="cadastros-submenu-clientes" class="mt-2">Clientes:</label>
            <select id="cadastros-submenu-clientes" class="form-select">
              <option value="sim">Sim</option>
              <option value="nao" selected>Não</option>
            </select>
          </div>
          <div class="col">
            <label for="cadastros-submenu-fornecedores" class="mt-2">Fornecedores</label>
            <select id="cadastros-submenu-fornecedores" class="form-select">
              <option value="sim">Sim</option>
              <option value="nao" selected>Não</option>
            </select>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4 mb-4">
      <div class="card h-100 text-center shadow">
        <div class="card-body">
          <h5 class="card-title">
            Faturamento
            <input type="checkbox" class="form-check-input" id="check-sales">
          </h5>
          <p class="card-text">Controle e emita faturas de forma simples.</p>
          <div class="col">
            <label for="faturamento-submenu-vendas" class="mt-2">Vendas:</label>
            <select id="faturamento-submenu-vendas" class="form-select">
              <option value="sim">Sim</option>
              <option value="nao" selected>Não</option>
            </select>
          </div>
          <div class="col">
            <label for="faturamento-submenu-lista-vendas" class="mt-2">Lista de Vendas:</label>
            <select id="faturamento-submenu-lista-vendas" class="form-select">
              <option value="sim">Sim</option>
              <option value="nao" selected>Não</option>
            </select>
          </div><div class="col">
            <label for="faturamento-submenu-condicional" class="mt-2">Condicionais:</label>
            <select id="faturamento-submenu-condicional" class="form-select">
              <option value="sim">Sim</option>
              <option value="nao" selected>Não</option>
            </select>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4 mb-4">
      <div class="card h-100 text-center shadow">
        <div class="card-body">
          <h5 class="card-title">
            Food
            <input type="checkbox" class="form-check-input" id="check-food">
          </h5>
          <p class="card-text">Gerencie informações relacionadas à alimentação.</p>
          <div class="col">
            <label for="food-submenu-pedidos" class="mt-2">Pedidos</label>
            <select id="food-submenu-pedidos" class="form-select">
              <option value="sim">Sim</option>
              <option value="nao" selected>Não</option>
            </select>
          </div>
          <div class="col">
            <label for="food-submenu-lista-pedidos" class="mt-2">Lista de Pedidos</label>
            <select id="food-submenu-lista-pedidos" class="form-select">
              <option value="sim">Sim</option>
              <option value="nao" selected>Não</option>
            </select>
          </div>
          <div class="col">
            <label for="food-submenu-cadastro-mesa" class="mt-2">Cadastro de Mesa</label>
            <select id="food-submenu-cadastro-mesa" class="form-select">
              <option value="sim">Sim</option>
              <option value="nao" selected>Não</option>
            </select>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4 mb-4">
      <div class="card h-100 text-center shadow">
        <div class="card-body">
          <h5 class="card-title">
            Fluxo de Caixa
            <input type="checkbox" class="form-check-input" id="check-boxpdv">
          </h5>
          <p class="card-text">Acompanhe seu fluxo de caixa de forma precisa.</p>
          <div class="col">
            <label for="fluxo-caixa-submenu-abertura" class="mt-2">Abrir Caixa</label>
            <select id="fluxo-caixa-submenu-abertura" class="form-select">
              <option value="sim">Sim</option>
              <option value="nao" selected>Não</option>
            </select>
          </div>
          <div class="col">
            <label for="fluxo-caixa-submenu-lista" class="mt-2">Lista de Caixa</label>
            <select id="fluxo-caixa-submenu-lista" class="form-select">
              <option value="sim">Sim</option>
              <option value="nao" selected>Não</option>
            </select>
          </div>
          <div class="col">
            <label for="fluxo-caixa-submenu-relatorio" class="mt-2">Relatório Fluxo de Caixa</label>
            <select id="fluxo-caixa-submenu-relatorio" class="form-select">
              <option value="sim">Sim</option>
              <option value="nao" selected>Não</option>
            </select>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4 mb-4">
      <div class="card h-100 text-center shadow">
        <div class="card-body">
          <h5 class="card-title">
            Suprimentos
            <input type="checkbox" class="form-check-input" id="check-supplies">
          </h5>
          <p class="card-text">Controle e gerencie seus suprimentos.</p>
          <div class="col">
            <label for="suprimentos-submenu-solicitacao" class="mt-2">Solicitação de Compras</label>
            <select id="suprimentos-submenu-solicitacao" class="form-select">
              <option value="sim">Sim</option>
              <option value="nao" selected>Não</option>
            </select>
          </div>
          <div class="col">
            <label for="suprimentos-submenu-lista" class="mt-2">Lista das Solicitações de Compras</label>
            <select id="suprimentos-submenu-lista" class="form-select">
              <option value="sim">Sim</option>
              <option value="nao" selected>Não</option>
            </select>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4 mb-4">
      <div class="card h-100 text-center shadow">
        <div class="card-body">
          <h5 class="card-title">
            Controle Financeiro
            <input type="checkbox" class="form-check-input" id="check-financial-control">
          </h5>
          <p class="card-text">Gerencie suas finanças com eficiência.</p>
          <div class="col">
            <label for="controle-financeiro-submenu-pagamentos" class="mt-2">Visualizar Pagamentos</label>
            <select id="controle-financeiro-submenu-pagamentos" class="form-select">
              <option value="sim">Sim</option>
              <option value="nao" selected>Não</option>
            </select>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4 mb-4">
      <div class="card h-100 text-center shadow">
        <div class="card-body">
          <h5 class="card-title">
            Controle Estoque
            <input type="checkbox" class="form-check-input m-2" id="check-product-stock">
          </h5>
          <p class="card-text">Mantenha o controle do seu estoque facilmente.</p>
          <div class="col">
            <label for="controle-estoque-submenu-lista" class="mt-2">Lista de Produtos</label>
            <select id="controle-estoque-submenu-lista" class="form-select">
              <option value="sim">Sim</option>
              <option value="nao" selected>Não</option>
            </select>
          </div>
          <div class="col">
            <label for="controle-estoque-submenu-produtos" class="mt-2">Produtos</label>
            <div class="col">
              <select id="controle-estoque-submenu-produtos" class="form-select">
                <option value="sim">Sim</option>
                <option value="nao" selected>Não</option>
              </select>
            </div>
          </div>
          <div class="col">
            <label for="controle-estoque-submenu-inventory" class="mt-2">Inventario</label>
            <div class="col">
              <select id="controle-estoque-submenu-inventory" class="form-select">
                <option value="sim">Sim</option>
                <option value="nao" selected>Não</option>
              </select>
            </div>
          </div>
          <div class="col">
            <label for="controle-estoque-submenu-portion" class="mt-2">Criar Porções</label>
            <div class="col">
              <select id="controle-estoque-submenu-open-portion" class="form-select">
                <option value="sim">Sim</option>
                <option value="nao" selected>Não</option>
              </select>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4 mb-4">
      <div class="card h-100 text-center shadow">
        <div class="card-body">
          <h5 class="card-title">
            Administrativo
            <input type="checkbox" class="form-check-input" id="check-adm">
          </h5>
          <p class="card-text">Gerencie funções administrativas da empresa.</p>
          <div class="col">
          <label for="administrativo-submenu-dashboards" class="mt-2">Dashboards</label>
            <select id="administrativo-submenu-dashboards" class="form-select">
              <option value="sim">Sim</option>
              <option value="nao" selected>Não</option>
            </select>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4 mb-4">
      <div class="card h-100 text-center shadow">
        <div class="card-body">
          <h5 class="card-title">
            Minha Empresa
            <input type="checkbox" class="form-check-input" id="check-mycompany">
          </h5>
          <p class="card-text">Gerencie informações sobre sua empresa.</p>
          <div class="col">
          <label for="minha-empresa-submenu" class="mt-2">Empresa</label>
            <select id="minha-empresa-submenu" class="form-select">
              <option value="sim">Sim</option>
              <option value="nao" selected>Não</option>
            </select>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<br>

<div class="container-fluid card">
  <div class="col-12 p-2">
    <button onclick="RegisterUsers()" class="btn btn-primary" type="button">Cadastrar</button>
  </div>
</div>


<script>
  document.addEventListener('DOMContentLoaded', function () {
    var form = document.querySelector('.form');

    form.addEventListener('keydown', function (event) {
      if (event.key === 'Enter') {
        event.preventDefault();

        var currentInput = event.target;
        var formElements = form.elements;
        var currentIndex = Array.from(formElements).indexOf(currentInput);

        if (currentIndex < formElements.length - 1) {
          formElements[currentIndex + 1].focus();
        }
      }
    });
  });
</script>