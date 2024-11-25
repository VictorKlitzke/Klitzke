const { createApp, ref, onMounted } = Vue;
require('dotenv').config();

createApp({
  components: {
    'v-select': VueSelect,
  },
  setup() {
    const form = ref({
      ClientId: '',
      UserId: '',
      dateNow: '',
      dateReturn: '',
      discount: 0,
      obs: ''
    });

    const BASE_CONTROLLERS = process.env.BASE_CONTROLLERS;
    const clients = ref([]);
    const users = ref([]);
    const products = ref([]);
    const selectedProducts = ref([]);
    const selectedProduct = ref(null);
    const productQuantity = ref(1);
    const productPrice = ref(0);
    const subTotal = ref(0);
    const total = ref(0);

    const fetchClients = async () => {
      try {
        const response = await fetch(`${BASE_CONTROLLERS}searchs.php`, {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: new URLSearchParams({ client_list: 'true' }),
        });
        const data = await response.json();
        if (data.success && Array.isArray(data.clients)) {
          clients.value = data.clients;  // Ajuste caso a resposta contenha "clients"
        } else {
          console.log("Nenhum cliente encontrado.");
        }
      } catch (error) {
        console.log("Erro na requisição de Busca: " + error);
      }
    };

    const fetchUsers = async () => {
      try {
        const response = await fetch(`${BASE_CONTROLLERS}searchs.php`, {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: new URLSearchParams({ users_list: 'true' }),
        });
        const data = await response.json();
        if (data.success && Array.isArray(data.users)) {
          users.value = data.users;
        } else {
          console.log("Nenhum usuário encontrado.");
        }
      } catch (error) {
        console.log("Erro na requisição de Usuários: " + error);
      }
    };

    const fetchProducts = async () => {
      try {
        const response = await fetch(`${BASE_CONTROLLERS}searchs.php`, {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: new URLSearchParams({ product_list: 'true' }),
        });
        const data = await response.json();
        if (data.success && Array.isArray(data.products)) {
          products.value = data.products;
        } else {
          console.log("Nenhum produto encontrado.");
        }
      } catch (error) {
        console.log("Erro na requisição de Produtos: " + error);
      }
    };

    const addProductToConditional = () => {
      if (selectedProduct.value && productQuantity.value > 0 && productPrice.value > 0) {
        selectedProducts.value.push({
          name: selectedProduct.value.name,
          quantity: productQuantity.value,
          price: productPrice.value,
        });
        updateTotal();
      } else {
        alert("Produto ou quantidade inválida.");
      }
    };

    const removeProduct = (index) => {
      selectedProducts.value.splice(index, 1);
      updateTotal();
    };

    const updateTotal = () => {
      subTotal.value = selectedProducts.value.reduce((sum, product) => {
        return sum + (product.quantity * product.price);
      }, 0);

      total.value = (subTotal.value - form.value.discount).toFixed(2);
    };

    const registerConditional = () => {
      // Lógica para registrar o condicional
      console.log(form.value);
    };

    onMounted(async () => {
      await fetchClients();
      await fetchUsers();
      await fetchProducts();
    });

    return {
      form,
      clients,
      users,
      products,
      selectedProducts,
      selectedProduct,
      productQuantity,
      productPrice,
      subTotal,
      total,
      addProductToConditional,
      removeProduct,
      updateTotal,
      registerConditional,
    };
  },
}).mount('#app');
