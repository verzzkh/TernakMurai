<x-layout>
  <main>
      <!-- Content header -->
      <div class="flex items-center justify-between px-4 py-4 border-b lg:py-6 dark:border-primary-darker">
          <h1 class="text-2xl font-semibold">Manajemen Anakan</h1>
      </div>

      <!-- Tab Navigation -->
      <div class="px-4 border-b dark:border-primary-darker">
          <div class="flex space-x-8">
              <button id="tab-jantan" class="py-4 text-primary border-b-2 border-primary dark:text-primary-light dark:border-primary-light font-medium">
                  Anakan Jantan
              </button>
              <button id="tab-betina" class="py-4 text-gray-500 hover:text-primary dark:text-gray-400 dark:hover:text-primary-light font-medium">
                  Anakan Betina
              </button>
          </div>
      </div>

      <!-- Content -->
      <div class="mt-2">
          <!-- Search and filter section -->
          <div class="flex flex-col md:flex-row items-center justify-between p-4 space-y-3 md:space-y-0">
              <div class="w-full md:w-1/3">
                  <div class="relative">
                      <input type="text" id="searchAnakan" placeholder="Cari anakan..." class="w-full pl-10 pr-4 py-2 rounded-md border dark:border-primary-darker dark:bg-darker focus:outline-none focus:ring focus:ring-primary-light">
                      <span class="absolute left-3 top-2 text-gray-400">
                          <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                          </svg>
                      </span>
                  </div>
              </div>
              <div class="flex space-x-3">
                  <select id="filterStatus" class="px-4 py-2 rounded-md border dark:border-primary-darker dark:bg-darker focus:outline-none focus:ring focus:ring-primary-light">
                      <option value="all">Semua Status</option>
                      <option value="trotol">Trotol</option>
                      <option value="pastol">Pastol</option>
                      <option id="option-lomba" value="lomba">Lomba</option>
                      <option id="option-dewasa" value="dewasa" class="hidden">Dewasa</option>
                  </select>
                  <select id="filterAktif" class="px-4 py-2 rounded-md border dark:border-primary-darker dark:bg-darker focus:outline-none focus:ring focus:ring-primary-light">
                      <option value="all">Semua</option>
                      <option value="aktif">Aktif</option>
                      <option value="terjual">Terjual</option>
                  </select>
              </div>
          </div>

          <!-- Anakan cards grid - Jantan -->
          <div id="anakanJantanGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 p-4">
          </div>

          <!-- Anakan cards grid - Betina (hidden by default) -->
          <div id="anakanBetinaGrid" class="hidden grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 p-4">
          </div>
      </div>
  </main>

  <!-- Script untuk pencarian, filter, dan tab navigation -->
  <script>
      document.addEventListener('DOMContentLoaded', function() {
          // Data dummy untuk anakan jantan
          const anakanJantanData = [
              {
                  id: 1,
                  ring: "MB-AJ-001",
                  status: "Trotol",
                  statusClass: "bg-blue-500",
                  umur: "25 hari",
                  harga: 450000,
                  aktif: true,
                  jantan: "MB-J-001",
                  img: "/images/anakan/anak 1.jpeg"
              },
              {
                  id: 2,
                  ring: "MB-AJ-002",
                  status: "Pastol",
                  statusClass: "bg-purple-500",
                  umur: "45 hari",
                  harga: 750000,
                  aktif: true,
                  jantan: "MB-J-002",
                  img: "/images/anakan/anak 2.jpeg"
              },
              {
                  id: 3,
                  ring: "MB-AJ-003",
                  status: "Lomba",
                  statusClass: "bg-green-500",
                  umur: "90 hari",
                  harga: 1500000,
                  aktif: true,
                  jantan: "MB-J-001",
                  img: "/images/anakan/anak 3.jpeg"
              },
              {
                  id: 4,
                  ring: "MB-AJ-004",
                  status: "Pastol",
                  statusClass: "bg-purple-500",
                  umur: "50 hari",
                  harga: 800000,
                  aktif: false,
                  jantan: "MB-J-003",
                  img: "/images/anakan/anak 4.jpeg"
              }
          ];

          // Data dummy untuk anakan betina
          const anakanBetinaData = [
              {
                  id: 5,
                  ring: "MB-AB-001",
                  status: "Trotol",
                  statusClass: "bg-blue-500",
                  umur: "30 hari",
                  harga: 350000,
                  aktif: true,
                  jantan: "MB-J-002",
                  img: "/img/anakan5.jpg"
              },
              {
                  id: 6,
                  ring: "MB-AB-002",
                  status: "Pastol",
                  statusClass: "bg-purple-500",
                  umur: "55 hari",
                  harga: 650000,
                  aktif: true,
                  jantan: "MB-J-001",
                  img: "/img/anakan6.jpg"
              },
              {
                  id: 7,
                  ring: "MB-AB-003",
                  status: "Dewasa",
                  statusClass: "bg-teal-500",
                  umur: "85 hari",
                  harga: 1200000,
                  aktif: false,
                  jantan: "MB-J-001",
                  img: "/img/anakan7.jpg"
              }
          ];

          // Elemen input dan filter
          const searchInput = document.getElementById('searchAnakan');
          const filterStatus = document.getElementById('filterStatus');
          const filterAktif = document.getElementById('filterAktif');
          const anakanJantanGrid = document.getElementById('anakanJantanGrid');
          const anakanBetinaGrid = document.getElementById('anakanBetinaGrid');
          
          // Tab buttons
          const tabJantan = document.getElementById('tab-jantan');
          const tabBetina = document.getElementById('tab-betina');
          
          // Current active tab
          let activeTab = 'jantan';
          
          // Format currency
          function formatRupiah(angka) {
              return new Intl.NumberFormat('id-ID', {
                  style: 'currency',
                  currency: 'IDR',
                  minimumFractionDigits: 0
              }).format(angka);
          }
          
          // Fungsi untuk render anakan cards berdasarkan gender
          function renderAnakanCards(gender) {
              const gridElement = gender === 'jantan' ? anakanJantanGrid : anakanBetinaGrid;
              const dataSource = gender === 'jantan' ? anakanJantanData : anakanBetinaData;
              
              // Hapus semua card yang ada
              gridElement.innerHTML = '';
              
              // Filter data berdasarkan pencarian dan status
              const searchTerm = searchInput.value.toLowerCase();
              const statusFilter = filterStatus.value;
              const aktifFilter = filterAktif.value;
              
              const filteredData = dataSource.filter(anakan => {
                  // Filter pencarian
                  const matchesSearch = 
                      anakan.ring.toLowerCase().includes(searchTerm) ||
                      anakan.jantan.toLowerCase().includes(searchTerm);
                  
                  // Filter status pertumbuhan
                  let matchesStatus = (statusFilter === 'all');
                  
                  if (gender === 'jantan') {
                      if (statusFilter === 'trotol' && anakan.status === 'Trotol') {
                          matchesStatus = true;
                      } else if (statusFilter === 'pastol' && anakan.status === 'Pastol') {
                          matchesStatus = true;
                      } else if (statusFilter === 'lomba' && anakan.status === 'Lomba') {
                          matchesStatus = true;
                      }
                  } else { // betina
                      if (statusFilter === 'trotol' && anakan.status === 'Trotol') {
                          matchesStatus = true;
                      } else if (statusFilter === 'pastol' && anakan.status === 'Pastol') {
                          matchesStatus = true;
                      } else if (statusFilter === 'dewasa' && anakan.status === 'Dewasa') {
                          matchesStatus = true;
                      }
                  }
                  
                  // Filter status aktif/terjual
                  let matchesAktif = (aktifFilter === 'all');
                  if (aktifFilter === 'aktif' && anakan.aktif) {
                      matchesAktif = true;
                  } else if (aktifFilter === 'terjual' && !anakan.aktif) {
                      matchesAktif = true;
                  }
                  
                  return matchesSearch && matchesStatus && matchesAktif;
              });
              
              // Render filtered anakan cards
              filteredData.forEach(anakan => {
                  const cardHTML = `
                      <div class="bg-white rounded-md shadow-md overflow-hidden dark:bg-darker cursor-pointer hover:shadow-lg transition duration-300" onclick="window.location.href = '/anakan/detail/${anakan.id}'">
                          <div class="h-40 bg-gray-200 dark:bg-gray-700 relative overflow-hidden">
                              <img src="${anakan.img}" alt="Anakan" class="w-full h-full object-cover">
                              <div class="absolute top-2 right-2 ${anakan.statusClass} text-white px-2 py-1 rounded-md text-xs">
                                  ${anakan.status}
                              </div>
                              ${!anakan.aktif ? `
                              <div class="absolute bottom-0 left-0 right-0 bg-gray-800 bg-opacity-75 text-white text-center py-1 text-sm">
                                  Terjual
                              </div>` : ''}
                          </div>
                          <div class="p-4">
                              <h3 class="text-lg font-semibold">${anakan.ring}</h3>
                              <div class="flex justify-between mt-2">
                                  <p class="text-gray-600 dark:text-gray-300">
                                      <span class="font-medium">Umur:</span> ${anakan.umur}
                                  </p>
                                  <p class="text-gray-600 dark:text-gray-300">
                                      <span class="font-medium">Harga:</span> ${formatRupiah(anakan.harga)}
                                  </p>
                              </div>
                              <p class="text-gray-600 dark:text-gray-300 mt-1">
                                  <span class="font-medium">Jantan:</span> ${anakan.jantan}
                              </p>
                              
                              <div class="mt-3 flex justify-between">
                                  ${anakan.aktif ? `
                                  <div class="flex space-x-2">
                                      <button class="px-2 py-1 text-xs bg-gray-200 text-gray-700 rounded hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                                          Edit Harga
                                      </button>
                                      <button class="px-2 py-1 text-xs bg-primary-100 text-primary-dark rounded hover:bg-primary-200 dark:bg-primary dark:text-primary-100 dark:hover:bg-primary-dark">
                                          Update Status
                                      </button>
                                  </div>
                                  <button class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded hover:bg-green-200 dark:bg-green-800 dark:text-green-100 dark:hover:bg-green-700">
                                      Jual
                                  </button>` : `
                                  <span class="text-xs text-gray-500 dark:text-gray-400">
                                      Terjual pada 15/08/2023
                                  </span>`}
                              </div>
                          </div>
                      </div>
                  `;
                  gridElement.innerHTML += cardHTML;
              });
              
              // Tambahkan card "Tambah Anakan"
              const createText = gender === 'jantan' ? 'Tambah Anakan Jantan' : 'Tambah Anakan Betina';
              const createPath = gender === 'jantan' ? '/anakan/create?gender=jantan' : '/anakan/create?gender=betina';
              
              const addCardHTML = `
                  <div class="bg-white rounded-md shadow-md overflow-hidden dark:bg-darker cursor-pointer hover:shadow-lg transition duration-300 border-2 border-dashed border-gray-300 dark:border-gray-600" onclick="window.location.href = '${createPath}'">
                      <div class="h-full flex flex-col items-center justify-center p-8">
                          <div class="w-16 h-16 rounded-full bg-primary-100 dark:bg-primary flex items-center justify-center mb-4">
                              <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-primary-dark dark:text-primary-100" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                              </svg>
                          </div>
                          <h3 class="text-lg font-semibold text-primary-dark dark:text-primary-100">${createText}</h3>
                          <p class="text-gray-500 dark:text-gray-400 text-center mt-2">Klik untuk menambahkan anakan baru</p>
                      </div>
                  </div>
              `;
              gridElement.innerHTML += addCardHTML;
          }
          
          // Toggle active tab
          function switchTab(gender) {
              if (gender === 'jantan') {
                  // Update tab styles
                  tabJantan.classList.add('text-primary', 'border-b-2', 'border-primary', 'dark:text-primary-light', 'dark:border-primary-light');
                  tabJantan.classList.remove('text-gray-500', 'hover:text-primary', 'dark:text-gray-400', 'dark:hover:text-primary-light');
                  
                  tabBetina.classList.remove('text-primary', 'border-b-2', 'border-primary', 'dark:text-primary-light', 'dark:border-primary-light');
                  tabBetina.classList.add('text-gray-500', 'hover:text-primary', 'dark:text-gray-400', 'dark:hover:text-primary-light');
                  
                  // Show/hide grid
                  anakanJantanGrid.classList.remove('hidden');
                  anakanBetinaGrid.classList.add('hidden');
                  
                  // Update filter options for jantan
                  document.getElementById('option-lomba').classList.remove('hidden');
                  document.getElementById('option-dewasa').classList.add('hidden');
              } else {
                  // Update tab styles
                  tabBetina.classList.add('text-primary', 'border-b-2', 'border-primary', 'dark:text-primary-light', 'dark:border-primary-light');
                  tabBetina.classList.remove('text-gray-500', 'hover:text-primary', 'dark:text-gray-400', 'dark:hover:text-primary-light');
                  
                  tabJantan.classList.remove('text-primary', 'border-b-2', 'border-primary', 'dark:text-primary-light', 'dark:border-primary-light');
                  tabJantan.classList.add('text-gray-500', 'hover:text-primary', 'dark:text-gray-400', 'dark:hover:text-primary-light');
                  
                  // Show/hide grid
                  anakanBetinaGrid.classList.remove('hidden');
                  anakanJantanGrid.classList.add('hidden');
                  
                  // Update filter options for betina
                  document.getElementById('option-lomba').classList.add('hidden');
                  document.getElementById('option-dewasa').classList.remove('hidden');
              }
              
              // Reset filter to 'all' when switching tabs
              filterStatus.value = 'all';
              
              activeTab = gender;
          }
          
          // Tab click event handlers
          tabJantan.addEventListener('click', function() {
              switchTab('jantan');
              renderAnakanCards('jantan');
          });
          
          tabBetina.addEventListener('click', function() {
              switchTab('betina');
              renderAnakanCards('betina');
          });
          
          // Render initial view
          renderAnakanCards('jantan');
          
          // Search and filter event listeners
          searchInput.addEventListener('input', function() {
              renderAnakanCards(activeTab);
          });
          filterStatus.addEventListener('change', function() {
              renderAnakanCards(activeTab);
          });
          filterAktif.addEventListener('change', function() {
              renderAnakanCards(activeTab);
          });
      });
  </script>
</x-layout>