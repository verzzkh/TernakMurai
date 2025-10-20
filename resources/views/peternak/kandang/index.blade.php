<x-layout>
    <main>
        <!-- Content header -->
        <div class="flex items-center justify-between px-4 py-4 border-b lg:py-6 dark:border-primary-darker">
            <h1 class="text-2xl font-semibold">Manajemen Kandang</h1>
        </div>

        <!-- Content -->
        <div class="mt-2">
            <!-- Search and filter section -->
            <div class="flex flex-col md:flex-row items-center justify-between p-4 space-y-3 md:space-y-0">
                <div class="w-full md:w-1/3">
                    <div class="relative">
                        <input type="text" id="searchKandang" placeholder="Cari kandang..." class="w-full pl-10 pr-4 py-2 rounded-md border dark:border-primary-darker dark:bg-darker focus:outline-none focus:ring focus:ring-primary-light">
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
                        <option value="belum_bertelur">Belum Bertelur</option>
                        <option value="bertelur">Bertelur</option>
                        <option value="mengeram">Mengeram</option>
                        <option value="menetas">Menetas</option>
                    </select>
                </div>
            </div>

            <!-- Kandang cards grid -->
            <div id="kandangGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 p-4">
            </div>
        </div>
    </main>

    <!-- Script untuk pencarian dan filter -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Data dummy untuk kandang
            const kandangData = [
                {
                    id: 1,
                    nomor: "001",
                    status: "Belum Bertelur",
                    statusClass: "bg-blue-500",
                    jantan: "MB-J-001",
                    betina: "MB-B-001",
                    telur: 0,
                    anakan: 0,
                    img: "/images/kandang/murai 1.jpeg"
                },
                {
                    id: 2,
                    nomor: "002",
                    status: "Bertelur",
                    statusClass: "bg-yellow-500",
                    jantan: "MB-J-002",
                    betina: "MB-B-002",
                    telur: 2,
                    anakan: 0,
                   img: "/images/kandang/murai 2.jpeg"

                },
             
                {
                    id: 3,
                    nomor: "003",
                    status: "Mengeram",
                    statusClass: "bg-orange-500",
                    jantan: "MB-J-003",
                    betina: "MB-B-003",
                    telur: 3,
                    anakan: 0,
                    img: "/images/kandang/murai 3.jpeg"
                },
                {
                    id: 4,
                    nomor: "004",
                    status: "Menetas",
                    statusClass: "bg-green-500",
                    jantan: "MB-J-004",
                    betina: "MB-B-004",
                    telur: 0,
                    anakan: 3,
                    img: "/images/kandang/murai 4.jpeg"
                },
                {
                    id: 5,
                    nomor: "005",
                    status: "Bertelur",
                    statusClass: "bg-yellow-500",
                    jantan: "MB-J-005",
                    betina: "MB-B-005",
                    telur: 0,
                    anakan: 3,
                    img: "/images/kandang/murai 5.jpeg"
                }
            ];

            // Elemen input dan filter
            const searchInput = document.getElementById('searchKandang');
            const filterStatus = document.getElementById('filterStatus');
            const kandangGrid = document.getElementById('kandangGrid');
            
            // Fungsi untuk render kandang cards
            function renderKandangCards() {
                // Hapus semua card yang ada
                kandangGrid.innerHTML = '';
                
                // Filter data berdasarkan pencarian dan status
                const searchTerm = searchInput.value.toLowerCase();
                const statusFilter = filterStatus.value;
                
                const filteredData = kandangData.filter(kandang => {
                    // Filter pencarian
                    const matchesSearch = 
                        kandang.nomor.toLowerCase().includes(searchTerm) ||
                        kandang.jantan.toLowerCase().includes(searchTerm) ||
                        kandang.betina.toLowerCase().includes(searchTerm);
                    
                    // Filter status
                    let matchesStatus = (statusFilter === 'all');
                    if (statusFilter === 'belum_bertelur' && kandang.status === 'Belum Bertelur') {
                        matchesStatus = true;
                    } else if (statusFilter === 'bertelur' && kandang.status === 'Bertelur') {
                        matchesStatus = true;
                    } else if (statusFilter === 'mengeram' && kandang.status === 'Mengeram') {
                        matchesStatus = true;
                    } else if (statusFilter === 'menetas' && kandang.status === 'Menetas') {
                        matchesStatus = true;
                    }
                    
                    return matchesSearch && matchesStatus;
                });
                
                // Render filtered kandang cards
                filteredData.forEach(kandang => {
                    const cardHTML = `
                        <div class="bg-white rounded-md shadow-md overflow-hidden dark:bg-darker cursor-pointer hover:shadow-lg transition duration-300" onclick="window.location.href = '/kandang/detail/${kandang.id}'">
                            <div class="h-40 bg-gray-200 dark:bg-gray-700 relative overflow-hidden">
                                <img src="${kandang.img}" alt="Kandang" class="w-full h-full object-cover">
                                <div class="absolute top-2 right-2 ${kandang.statusClass} text-white px-2 py-1 rounded-md text-xs">
                                    ${kandang.status}
                                </div>
                            </div>
                            <div class="p-4">
                                <h3 class="text-lg font-semibold">Kandang #${kandang.nomor}</h3>
                                <p class="text-gray-600 dark:text-gray-300 mt-2">
                                    <span class="font-medium">Jantan:</span> ${kandang.jantan}
                                </p>
                                <p class="text-gray-600 dark:text-gray-300">
                                    <span class="font-medium">Betina:</span> ${kandang.betina}
                                </p>
                                <p class="text-gray-600 dark:text-gray-300 mt-1">
                                    <span class="font-medium">Telur:</span> ${kandang.telur} butir
                                </p>
                                ${kandang.anakan > 0 ? `
                                <p class="text-gray-600 dark:text-gray-300 mt-1">
                                    <span class="font-medium">Anakan:</span> ${kandang.anakan} ekor
                                </p>` : ''}
                            </div>
                        </div>
                    `;
                    kandangGrid.innerHTML += cardHTML;
                });
                
                // Tambahkan card "Tambah Kandang"
                const addCardHTML = `
                    <div class="bg-white rounded-md shadow-md overflow-hidden dark:bg-darker cursor-pointer hover:shadow-lg transition duration-300 border-2 border-dashed border-gray-300 dark:border-gray-600" onclick="window.location.href = '/kandang/create'">
                        <div class="h-full flex flex-col items-center justify-center p-8">
                            <div class="w-16 h-16 rounded-full bg-primary-100 dark:bg-primary flex items-center justify-center mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-primary-dark dark:text-primary-100" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-primary-dark dark:text-primary-100">Tambah Kandang</h3>
                            <p class="text-gray-500 dark:text-gray-400 text-center mt-2">Klik untuk menambahkan kandang baru</p>
                        </div>
                    </div>
                `;
                kandangGrid.innerHTML += addCardHTML;
            }
            
            // Render cards awal
            renderKandangCards();
            
            // Pasang event listener
            searchInput.addEventListener('input', renderKandangCards);
            filterStatus.addEventListener('change', renderKandangCards);
        });
    </script>
</x-layout>