async function fetchSales() {
  let responseType = {
    type: 'sumUsersSales'
  }

  try {
    let url = `${BASE_CONTROLLERS}lists.php`;

    const response = await fetch(url, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(responseType)
    });

    const data = await response.json()

    if (data.success) {
      return data.data;

    } else {
      console.error(data.message);
      return [];
    }
  } catch (error) {
    console.error('Erro ao buscar dados:', error);
    return [];
  }
}
async function createChart() {
  const salesData = await fetchSales();
  const labels = salesData.map(item => item.users_name);
  const data = salesData.map(item => parseInt(item.total_sales, 10));

  const ctx = document.getElementById('sales-users').getContext('2d');

  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: labels,
      datasets: [{
        label: 'Vendas de Usuários',
        data: data,
        borderWidth: 1
      }]
    },
    options: {
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  });
}

async function fetchSalesPerMonth() {
  let responseType = {
    type: 'sumUsersSales'
  }
  try {
    let url = `${BASE_CONTROLLERS}lists.php`;

    const response = await fetch(url, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(responseType)
    });
    const data = await response.json();

    if (data.success) {
      return data.date_sales;
    } else {
      console.error(data.message);
      return [];
    }
  } catch (error) {
    console.error('Erro ao buscar dados:', error);
    return [];
  }
}
async function createChartSalesDate() {
  const salesData = await fetchSalesPerMonth();

  function parseDate(dateStr) {
    const [year, month] = dateStr.split('-');
    return new Date(year, month - 1); // `month - 1` porque meses são indexados a partir de 0 em JavaScript
  }

  // Obtém o ano atual para criar a lista de todos os meses
  const currentYear = new Date().getFullYear();

  // Cria uma lista de todos os meses do ano
  const months = Array.from({ length: 12 }, (_, i) => {
    const month = i + 1; // Mês de 1 a 12
    return `${currentYear}-${month.toString().padStart(2, '0')}`;
  });

  // Cria um mapa para armazenar vendas e valores totais por mês
  const salesMap = new Map(months.map(month => [month, { total_sales: 0, total_value: 0 }]));

  // Preenche o mapa com os dados recebidos
  salesData.forEach(item => {
    const monthKey = item.month;
    if (salesMap.has(monthKey)) {
      salesMap.set(monthKey, {
        total_sales: parseInt(item.total_sales, 10),
        total_value: parseFloat(item.total_value)
      });
    }
  });

  // Ordena os meses e extrai os dados para o gráfico
  const sortedMonths = [...salesMap.keys()].sort();
  const labels = sortedMonths.map(month => {
    const date = parseDate(month);
    return `${date.toLocaleString('default', { month: 'long' })} ${date.getFullYear()}`;
  });
  const totalSales = sortedMonths.map(month => salesMap.get(month).total_sales);
  const totalAmount = sortedMonths.map(month => salesMap.get(month).total_value);

  // Cria o gráfico
  const ctx = document.getElementById('total-sales').getContext('2d');

  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: labels,
      datasets: [
        {
          label: 'Total de Vendas',
          data: totalSales,
          backgroundColor: 'rgba(75, 192, 192, 0.2)',
          borderColor: 'rgba(75, 192, 192, 1)',
          borderWidth: 1
        },
        {
          label: 'Valor Total das Vendas',
          data: totalAmount,
          backgroundColor: 'rgba(153, 102, 255, 0.2)',
          borderColor: 'rgba(153, 102, 255, 1)',
          borderWidth: 1
        }
      ]
    },
    options: {
      scales: {
        x: {
          stacked: true,
          title: {
            display: true,
            text: 'Meses'
          }
        },
        y: {
          stacked: true,
          beginAtZero: true,
          title: {
            display: true,
            text: 'Valores'
          }
        }
      }
    }
  });
}
async function createChartsDateMaisSales() {

  const salesData = await fetchSalesPerMonth();
  const labels = salesData.map(item => item.month);
  const totalSales = salesData.map(item => parseInt(item.total_sales, 10));
  const totalAmount = salesData.map(item => parseFloat(item.total_value));
  const maxSales = Math.max(...totalSales);
  const maxSalesIndex = totalSales.indexOf(maxSales);
  const bestMonth = labels[maxSalesIndex];

  document.getElementById('best-month').innerText = `O mês com mais vendas é: ${bestMonth}, com um total de ${maxSales} vendas.`;
  const ctx = document.getElementById('total-date-sales').getContext('2d');

  new Chart(ctx, {
    type: 'line',
    data: {
      labels: labels,
      datasets: [
        {
          label: 'Total de Vendas',
          data: totalSales,
          borderColor: 'rgba(75, 192, 192, 1)',
          borderWidth: 2,
          fill: false
        },
        {
          label: 'Valor Total Vendido',
          data: totalAmount,
          borderColor: 'rgba(153, 102, 255, 1)',
          borderWidth: 2,
          fill: false
        }
      ]
    },
    options: {
      scales: {
        x: {
          title: {
            display: true,
            text: 'Meses'
          }
        },
        y: {
          beginAtZero: true,
          title: {
            display: true,
            text: 'Valores'
          }
        }
      }
    }
  });
}

async function fetchBoxClosing() {
  let responseClosing = {
    type: 'sumclosingbox'
  }

  try {

    let url = `${BASE_CONTROLLERS}lists.php`;

    const response = await fetch(url, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(responseClosing)
    });

    if (!response.ok) {
      console.error('Erro na resposta do servidor:', response.status, response.statusText);
      return;
    }

    const data = await response.json();

    if (data.success) {
      return data.result_box_closing
    } else {
      console.error(data.message);
      return [];
    }

  } catch (error) {
    console.error('Erro ao buscar dados:', error);
    return [];
  }
}
async function createChartsBoxClosing() {
  const data = await fetchBoxClosing();
  if (data.length === 0) return;

  const labels = data.map(item => item.date_close);
  const valueDebit = data.map(item => item.value_debit);
  const valueCredit = data.map(item => item.value_credit);
  const valuePix = data.map(item => item.value_pix);
  const valueMoney = data.map(item => item.value_money);
  const valueSystem = data.map(item => item.value_system);
  const valueFisico = data.map(item => item.value_fisico);
  const boxDifference = data.map(item => item.boxpdv_difference);
  const somaMoneySystem = data.map(item => item.soma_money_system);

  console.log(valueCredit, valueDebit, valuePix, valueMoney);

  new Chart(document.getElementById('chartBar').getContext('2d'), {
    type: 'bar',
    data: {
      labels: labels,
      datasets: [
        { label: 'Débito', data: valueDebit, backgroundColor: 'rgba(75, 192, 192, 0.5)' },
        { label: 'Crédito', data: valueCredit, backgroundColor: 'rgba(153, 102, 255, 0.5)' },
        { label: 'PIX', data: valuePix, backgroundColor: 'rgba(255, 159, 64, 0.5)' },
        { label: 'Dinheiro', data: valueMoney, backgroundColor: 'rgba(255, 99, 132, 0.5)' }
      ]
    },
    options: {
      responsive: true,
      scales: {
        y: { beginAtZero: true }
      }
    }
  });

  new Chart(document.getElementById('chartLine').getContext('2d'), {
    type: 'line',
    data: {
      labels: labels,
      datasets: [
        { label: 'Sistema', data: valueSystem, borderColor: 'rgba(75, 192, 192, 1)', fill: false },
        { label: 'Físico', data: valueFisico, borderColor: 'rgba(255, 99, 132, 1)', fill: false }
      ]
    },
    options: {
      responsive: true,
      scales: {
        y: { beginAtZero: true }
      }
    }
  });

  new Chart(document.getElementById('chartPie').getContext('2d'), {
    type: 'pie',
    data: {
      labels: ['Débito', 'Crédito', 'PIX', 'Dinheiro'],
      datasets: [{
        data: [
          valueDebit.reduce((sum, val) => sum + val, 0),
          valueCredit.reduce((sum, val) => sum + val, 0),
          valuePix.reduce((sum, val) => sum + val, 0),
          valueMoney.reduce((sum, val) => sum + val, 0)
        ],
        backgroundColor: ['rgba(75, 192, 192, 0.7)', 'rgba(153, 102, 255, 0.7)', 'rgba(255, 159, 64, 0.7)', 'rgba(255, 99, 132, 0.7)']
      }]
    },
    options: {
      responsive: true
    }
  });

  new Chart(document.getElementById('chartStacked').getContext('2d'), {
    type: 'bar',
    data: {
      labels: labels,
      datasets: [
        { label: 'Soma Dinheiro + Sistema', data: somaMoneySystem, backgroundColor: 'rgba(54, 162, 235, 0.5)' },
        { label: 'Diferença', data: boxDifference, backgroundColor: 'rgba(255, 206, 86, 0.5)' }
      ]
    },
    options: {
      responsive: true,
      scales: {
        x: { stacked: true },
        y: { stacked: true, beginAtZero: true }
      }
    }
  });
}


async function fetchFinacialControl() {
  let responseType = {
    type: 'sumcontrolfinancial'
  }
  try {
    let url = `${BASE_CONTROLLERS}lists.php`;

    const response = await fetch(url, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(responseType)
    });
    const data = await response.json();

    if (data.success) {
      return data.sumfinancial;
    } else {
      console.error(data.message);
      return [];
    }
  } catch (error) {
    console.error('Erro ao buscar dados:', error);
    return [];
  }
}
async function createChartsFinacialControl() {
  const DataFinancialControl = await fetchFinacialControl();

  const TotalDes = DataFinancialControl
    .filter(item => item.pay === null && item.status_aprazo === 'Despesa')
    .map(item => parseFloat(item.value));

  const TotalReceb = DataFinancialControl
    .filter(item => item.status_aprazo === 'Receita' && item.type === 'Receita')
    .map(item => parseFloat(item.value));

  const totalDespesas = TotalDes.reduce((a, b) => a + b, 0);
  const totalReceitas = TotalReceb.reduce((a, b) => a + b, 0);

  const ctx = document.getElementById('total-financial-control').getContext('2d');

  new Chart(ctx, {
    type: 'doughnut',
    data: {
      labels: ['Despesas', 'Receitas'],
      datasets: [{
        label: 'Distribuição Financeira',
        data: [totalDespesas, totalReceitas],
        backgroundColor: [
          'rgba(255, 99, 132, 0.7)',
          'rgba(75, 192, 192, 0.7)'
        ],
        hoverBackgroundColor: [
          'rgba(255, 99, 132, 1)',
          'rgba(75, 192, 192, 1)'
        ],
        hoverOffset: 4
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          position: 'top',
          labels: {
            boxWidth: 100
          }
        },
        title: {
          display: true,
          text: 'Distribuição Financeira: Despesas e Receitas',
          font: {
            size: 16,
            weight: 'bold'
          }
        }
      },
      layout: {
        padding: {
          top: 20,
          bottom: 20
        }
      }
    }
  });
}

document.addEventListener('DOMContentLoaded', function () {
  try {
    createChart();
    createChartSalesDate();
    createChartsDateMaisSales();
    createChartsFinacialControl();
    createChartsBoxClosing();
  } catch (error) {
    console.error(error);
  }
});
