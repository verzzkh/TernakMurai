// Format currency untuk tampilan nominal
const formatCurrency = (value) => {
    return 'Rp ' + parseFloat(value).toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ".");
  };
  
  // Format currency untuk input (saat mengetik)
  const formatCurrencyInput = (input) => {
    // Hapus karakter non-numerik
    let value = input.value.replace(/\D/g, '');
    
    // Format dengan separator ribuan
    if (value) {
      value = parseInt(value).toLocaleString('id-ID');
    }
    
    // Update input value
    input.value = value;
  };
  
  document.addEventListener('DOMContentLoaded', function() {
    // Inisialisasi format currency pada input nominal
    const currencyInputs = document.querySelectorAll('input[type="text"][placeholder*="Contoh:"]');
    currencyInputs.forEach(input => {
      input.addEventListener('input', () => formatCurrencyInput(input));
    });
  
    // Data untuk tabel transaksi
    const transactionData = [
      {
        id: 1,
        date: '2025-03-14',
        category: 'Penjualan Anakan',
        description: 'Penjualan Anakan MB-A-042',
        amount: 850000,
        type: 'income',
        status: 'active'
      },
      {
        id: 2,
        date: '2025-03-13',
        category: 'Pakan Burung',
        description: 'Pembelian Pakan Murai Premium 5kg',
        amount: 145000,
        type: 'expense',
        status: 'active'
      },
      {
        id: 3,
        date: '2025-03-12',
        category: 'Vitamin',
        description: 'Pembelian Vitamin Multi Burung',
        amount: 85000,
        type: 'expense',
        status: 'active'
      },
      {
        id: 4,
        date: '2025-03-10',
        category: 'Penjualan Anakan',
        description: 'Penjualan Anakan MB-A-038',
        amount: 1200000,
        type: 'income',
        status: 'active'
      },
      {
        id: 5,
        date: '2025-03-08',
        category: 'Peralatan',
        description: 'Pembelian Mangkok Pakan 10 pcs',
        amount: 120000,
        type: 'expense',
        status: 'active'
      }
    ];
  
    // Fungsi untuk filter transaksi
    const filterTransactions = () => {
      const startDate = document.querySelector('input[type="date"]:first-of-type').value;
      const endDate = document.querySelector('input[type="date"]:last-of-type').value;
      const category = document.querySelector('select').value;
      const type = document.querySelector('input[name="tipe_transaksi"]:checked').value;
      const minAmount = document.querySelector('input[placeholder="Min"]').value.replace(/\D/g, '');
      const maxAmount = document.querySelector('input[placeholder="Max"]').value.replace(/\D/g, '');
      
      // Filter data berdasarkan kriteria
      let filteredData = [...transactionData];
      
      if (startDate) {
        filteredData = filteredData.filter(item => item.date >= startDate);
      }
      
      if (endDate) {
        filteredData = filteredData.filter(item => item.date <= endDate);
      }
      
      if (category && category !== 'all') {
        filteredData = filteredData.filter(item => {
          const categoryMapping = {
            'penjualan_burung': 'Penjualan Burung',
            'penjualan_anakan': 'Penjualan Anakan',
            'pakan': 'Pakan Burung',
            'vitamin': 'Vitamin',
            'peralatan': 'Peralatan',
            'obat': 'Obat-obatan',
            'lainnya': 'Lainnya'
          };
          return item.category === categoryMapping[category];
        });
      }
      
      if (type && type !== 'all') {
        filteredData = filteredData.filter(item => item.type === (type === 'pemasukan' ? 'income' : 'expense'));
      }
      
      if (minAmount) {
        filteredData = filteredData.filter(item => item.amount >= parseInt(minAmount));
      }
      
      if (maxAmount) {
        filteredData = filteredData.filter(item => item.amount <= parseInt(maxAmount));
      }
      
      return filteredData;
    };
  
    // Fungsi untuk menampilkan transaksi dalam tabel
    const renderTransactionTable = (data) => {
      const tableBody = document.querySelector('table tbody');
      
      // Clear existing rows
      tableBody.innerHTML = '';
      
      // Render new rows
      data.forEach(item => {
        const row = document.createElement('tr');
        row.className = 'bg-white border-b dark:bg-darker dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600';
        
        const formattedDate = new Date(item.date).toLocaleDateString('id-ID');
        
        row.innerHTML = `
          <td class="px-4 py-3">${formattedDate}</td>
          <td class="px-4 py-3">${item.category}</td>
          <td class="px-4 py-3">${item.description}</td>
          <td class="px-4 py-3 ${item.type === 'income' ? 'text-green-500' : 'text-red-500'} font-medium">
            ${item.type === 'income' ? '' : '-'}${formatCurrency(item.amount)}
          </td>
          <td class="px-4 py-3">
            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
              ${item.status === 'active' ? 'Aktif' : 'Dihapus'}
            </span>
          </td>
          <td class="px-4 py-3 text-right">
            <button class="text-blue-500 hover:text-blue-700 mr-2">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
              </svg>
            </button>
            <button class="text-red-500 hover:text-red-700">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
              </svg>
            </button>
          </td>
        `;
        
        tableBody.appendChild(row);
      });
    };
  
    // Fungsi untuk menghitung summary keuangan
    const calculateFinanceSummary = (data) => {
      const currentDate = new Date();
      const currentMonth = currentDate.getMonth();
      const currentYear = currentDate.getFullYear();
      
      // Filter transaksi bulan ini
      const thisMonthData = data.filter(item => {
        const itemDate = new Date(item.date);
        return itemDate.getMonth() === currentMonth && itemDate.getFullYear() === currentYear;
      });
      
      // Hitung total pendapatan bulan ini
      const totalIncome = thisMonthData
        .filter(item => item.type === 'income')
        .reduce((sum, item) => sum + item.amount, 0);
      
      // Hitung total pengeluaran bulan ini
      const totalExpense = thisMonthData
        .filter(item => item.type === 'expense')
        .reduce((sum, item) => sum + item.amount, 0);
      
      // Hitung net profit/loss
      const netProfit = totalIncome - totalExpense;
      
      // Update card totals
      document.querySelector('h6:contains("Total Pendapatan Bulan Ini") + span').textContent = formatCurrency(totalIncome);
      document.querySelector('h6:contains("Total Pengeluaran Bulan Ini") + span').textContent = formatCurrency(totalExpense);
      document.querySelector('h6:contains("Net Profit/Loss") + span').textContent = formatCurrency(netProfit);
      
      // Update chart data
      // Note: This would need to be implemented based on the specific chart library being used
    };
  
    // Attach filter event listeners
    const filterElements = document.querySelectorAll('input[type="date"], select, input[name="tipe_transaksi"], input[placeholder="Min"], input[placeholder="Max"]');
    
    filterElements.forEach(element => {
      element.addEventListener('change', () => {
        const filteredData = filterTransactions();
        renderTransactionTable(filteredData);
      });
    });
  
    // Inisialisasi tampilan awal
    renderTransactionTable(transactionData);
  });