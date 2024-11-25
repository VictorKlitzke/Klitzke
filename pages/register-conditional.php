<!-- Container Principal -->
<div id="app" class="container-fluid p-4 shadow-lg border rounded-4">
    <div class="card mb-3">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Pedido Condicional</h5>
        </div>
        <div class="card-body">
            <form>
                <!-- Linha 1: Cliente e Datas -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Cliente <span class="text-danger">*</span></label>
                        <select id="clients" class="form-select" v-model="form.ClientId" required>
                            <option value="" disabled selected>Selecione o cliente</option>
                            <option v-for="client in clients" :key="client.id" :value="client.id">{{ client.name }}</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Data <span class="text-danger">*</span></label>
                        <input type="date" v-model="form.dateNow" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Prev. de Devolução <span class="text-danger">*</span></label>
                        <input type="date" v-model="form.dateReturn" class="form-control" required>
                    </div>
                </div>

                <!-- Linha 2: Vendedor -->
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="cliente" class="form-label">Vendedor/Responsável <span class="text-danger">*</span></label>
                        <select id="users" class="form-select" v-model="form.UserId" required>
                            <option disabled selected>Selecione o Responsável</option>
                            <option v-for="user in users" :key="user.id" :value="user.id">{{ user.name }}</option>
                        </select>
                    </div>
                </div>

                <!-- Linha 3: Valores -->
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="subtotal" class="form-label">R$ Sub:</label>
                        <input id="sub-total" v-model="subTotal" class="form-control" disabled>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">R$ Desconto:</label>
                        <input type="text" v-model="form.discount" class="form-control" value="0,00" @input="updateTotal">
                    </div>
                    <div class="col-md-4">
                        <label for="total" class="form-label">R$ Total:</label>
                        <input id="total" v-model="total" class="form-control" disabled placeholder="0,00"/>
                    </div>
                </div>

                <!-- Linha 4: Observações -->
                <div class="mb-3">
                    <label for="observacao" class="form-label">Observação</label>
                    <textarea id="obs" v-model="form.obs" class="form-control" rows="3" placeholder="Digite alguma observação"></textarea>
                </div>

                <!-- Linha 5: Produtos -->
                <div class="mb-3">
                    <label class="form-label">Produtos/Serviço do Condicional</label>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                            <tr>
                                <th>Produto</th>
                                <th>Quantidade</th>
                                <th>Preço Unitário</th>
                                <th>Total</th>
                                <th>Ações</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(product, index) in selectedProducts" :key="index">
                                <td>{{ product.name }}</td>
                                <td><input type="number" v-model="product.quantity" @input="updateTotal" class="form-control quantity-input" /></td>
                                <td><input type="number" v-model="product.price" @input="updateTotal" class="form-control price-input" /></td>
                                <td>{{ (product.quantity * product.price).toFixed(2).replace('.', ',') }}</td>
                                <td><button type="button" @click="removeProduct(index)" class="btn btn-sm">🗑️</button></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Botões -->
                <div class="d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">
                        <i class="bi bi-plus-lg"></i> Adicionar Produto
                    </button>
                    <button type="button" @click="registerConditional" class="btn btn-success"><i class="bi bi-save"></i> Salvar</button>
                    <button type="reset" class="btn btn-secondary"><i class="bi bi-x-circle"></i> Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addProductModalLabel">Adicionar Produto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="productSelect" class="form-label">Selecione o Produto</label>
                    <v-select id="productSelect" v-model="selectedProduct" :options="products.map(p => p.name)" label="name" />
                </div>
                <div class="mb-3">
                    <label for="productQuantity" class="form-label">Quantidade</label>
                    <input id="productQuantity" v-model="productQuantity" type="number" class="form-control" min="1" />
                </div>
                <div class="mb-3">
                    <label for="productPrice" class="form-label">Preço Unitário</label>
                    <input id="productPrice" v-model="productPrice" type="number" class="form-control" min="0.01" />
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary" @click="addProductToConditional">Adicionar</button>
            </div>
        </div>
    </div>
</div>
