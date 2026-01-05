<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // ================================================
    // 0. INJEK DATA DARI LARAVEL
    // ================================================
    window.allTransactions = @json($chartData); // data asli dari DB
    // Deep clone khusus chart, supaya tidak ikut keubah oleh filter / sort
    window.chartOriginalData = JSON.parse(JSON.stringify(window.allTransactions));
    window.filteredData = [...window.allTransactions]; // untuk tabel & export

    document.addEventListener('DOMContentLoaded', () => {
        console.log("🚀 Inisialisasi halaman keuangan...");

        // ================================================
        // 1. UTIL
        // ================================================
        const formatRupiah = num =>
            new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(num || 0).replace('IDR', 'Rp');

        const formatDate = d =>
            new Date(d).toLocaleDateString('id-ID', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric'
            });

        const getToday = () => new Date().toISOString().split('T')[0];

        const createGradient = (ctx, color) => {
            const gradient = ctx.createLinearGradient(0, 0, 0, 300);
            gradient.addColorStop(0, color + "99"); // 60% opacity
            gradient.addColorStop(1, color + "00"); // transparent
            return gradient;
        };

        // ================================================
        // 2. INISIALISASI CHARTS
        // ================================================
        const ctxLine = document.getElementById('financeChart')?.getContext('2d');
        const ctxPie = document.getElementById('expenseDistributionChart')?.getContext('2d');

        window.lineChart = null;
        window.pieChart = null;

        const initCharts = () => {
            // ---------- LINE CHART (Tren Keuangan) ----------
            if (ctxLine) {
                const greenGrad = createGradient(ctxLine, "#22c55e");
                const redGrad = createGradient(ctxLine, "#ef4444");

                window.lineChart = new Chart(ctxLine, {
                    type: "line",
                    data: {
                        labels: [],
                        datasets: [
                            {
                                label: "Pemasukan",
                                borderColor: "#22c55e",
                                backgroundColor: greenGrad,
                                pointBackgroundColor: "#22c55e",
                                pointRadius: 4,
                                pointHoverRadius: 6,
                                data: [],
                                tension: 0.4,
                                fill: true,
                                borderWidth: 2,
                            },
                            {
                                label: "Pengeluaran",
                                borderColor: "#ef4444",
                                backgroundColor: redGrad,
                                pointBackgroundColor: "#ef4444",
                                pointRadius: 4,
                                pointHoverRadius: 6,
                                data: [],
                                tension: 0.4,
                                fill: true,
                                borderWidth: 2,
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: {
                            intersect: false,
                            mode: 'index',
                        },
                        plugins: {
                            tooltip: {
                                backgroundColor: "rgba(30, 30, 30, 0.9)",
                                titleColor: "#fff",
                                bodyColor: "#eee",
                                padding: 10,
                                callbacks: {
                                    label: (ctx) =>
                                        ` ${ctx.dataset.label}: Rp ${ctx.raw.toLocaleString("id-ID")}`
                                }
                            },
                            legend: {
                                labels: {
                                    color: "#444",
                                    padding: 20
                                }
                            }
                        },
                        scales: {
                            x: {
                                ticks: { color: "#555" },
                                grid: { display: false }
                            },
                            y: {
                                ticks: {
                                    color: "#555",
                                    callback: v => "Rp " + v.toLocaleString("id-ID")
                                },
                                grid: {
                                    color: "#eee",
                                    drawBorder: false,
                                }
                            }
                        }
                    }
                });
            }

            // ---------- PIE CHART (Distribusi Pengeluaran) ----------
            if (ctxPie) {
                window.pieChart = new Chart(ctxPie, {
                    type: "doughnut",
                    data: {
                        // SESUAIKAN dengan enum kategori pengeluaran di DB-mu
                        labels: ['pakan', 'vitamin', 'pengeluaran_lainnya', 'obat', 'perawatan'],
                        datasets: [{
                            data: [0, 0, 0, 0, 0],
                            backgroundColor: [
                                "#3b82f6",
                                "#10b981",
                                "#f59e0b",
                                "#ef4444",
                                "#a855f7"
                            ],
                            borderWidth: 0,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: "65%",
                        plugins: {
                            legend: {
                                position: "bottom",
                                labels: {
                                    color: "#444"
                                }
                            },
                            tooltip: {
                                backgroundColor: "rgba(0,0,0,0.85)",
                                titleColor: "#fff",
                                bodyColor: "#eee",
                                callbacks: {
                                    label: (ctx) =>
                                        ` Rp ${ctx.raw.toLocaleString("id-ID")}`
                                }
                            }
                        }
                    }
                });
            }
        };

        // ================================================
        // 3. FUNGSI RENDER CHART (BULANAN / TAHUNAN)
        // ================================================
        window.renderCharts = function (mode = 'month') {
            if (!window.lineChart || !window.pieChart) return;

            // PENTING: pakai data khusus chart, bukan filteredData
            const tx = window.chartOriginalData;
            if (!tx || tx.length === 0) {
                // kalau tidak ada data, kosongkan chart
                window.lineChart.data.labels = [];
                window.lineChart.data.datasets[0].data = [];
                window.lineChart.data.datasets[1].data = [];
                window.lineChart.update();

                window.pieChart.data.datasets[0].data = [0, 0, 0, 0, 0];
                window.pieChart.update();
                return;
            }

            const monthNames = ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"];

            // ========== MODE BULANAN: 12 bulan di tahun terbaru ==========
            if (mode === 'month') {
                // asumsikan data dari backend sudah di-sort desc by tanggal
                const latestYear = new Date(tx[0].tanggal).getFullYear();

                // buat 12 bulan fix
                const fullMonths = Array.from({ length: 12 }, (_, i) => {
                    const m = String(i + 1).padStart(2, '0');
                    return `${latestYear}-${m}`;
                });

                const grouped = {};
                fullMonths.forEach(m => grouped[m] = { pemasukan: 0, pengeluaran: 0 });

                tx.forEach(t => {
                    const d = new Date(t.tanggal);
                    const y = d.getFullYear();
                    const m = String(d.getMonth() + 1).padStart(2, '0');
                    const key = `${y}-${m}`;

                    if (!grouped[key]) return; // hanya tahun terbaru

                    if (t.tipe === 'pemasukan') grouped[key].pemasukan += (t.jumlah || 0);
                    if (t.tipe === 'pengeluaran') grouped[key].pengeluaran += (t.jumlah || 0);
                });

                window.lineChart.data.labels = monthNames.map((label) => `${label} ${latestYear}`);
                window.lineChart.data.datasets[0].data = fullMonths.map(m => grouped[m].pemasukan);
                window.lineChart.data.datasets[1].data = fullMonths.map(m => grouped[m].pengeluaran);
            }

            // ========== MODE TAHUNAN: kumpulkan per tahun ==========
            if (mode === 'year') {
                const groupedYear = {};

                tx.forEach(t => {
                    const y = new Date(t.tanggal).getFullYear();
                    if (!groupedYear[y]) groupedYear[y] = { pemasukan: 0, pengeluaran: 0 };

                    if (t.tipe === 'pemasukan') groupedYear[y].pemasukan += (t.jumlah || 0);
                    if (t.tipe === 'pengeluaran') groupedYear[y].pengeluaran += (t.jumlah || 0);
                });

                const years = Object.keys(groupedYear).sort((a, b) => a - b);

                window.lineChart.data.labels = years;
                window.lineChart.data.datasets[0].data = years.map(y => groupedYear[y].pemasukan);
                window.lineChart.data.datasets[1].data = years.map(y => groupedYear[y].pengeluaran);
            }

            window.lineChart.update();

            // ---------- PIE CHART: selalu pakai tahun terbaru ----------
            const latestYearForPie = new Date(tx[0].tanggal).getFullYear();
            const kategoriList = window.pieChart.data.labels;

            window.pieChart.data.datasets[0].data = kategoriList.map(cat =>
                tx
                    .filter(t =>
                        t.tipe === 'pengeluaran' &&
                        t.kategori === cat &&
                        new Date(t.tanggal).getFullYear() === latestYearForPie
                    )
                    .reduce((sum, t) => sum + (t.jumlah || 0), 0)
            );
            window.pieChart.update();
        };

        // ========================================================
        // 4. STARTUP CHART
        // ========================================================
        initCharts();
        // pertama kali → render mode bulanan
        setTimeout(() => {
            window.renderCharts("month");
        }, 300);

        // ========================================================
        // 5. RINGKASAN KARTU ATAS (OPSIONAL)
        // ========================================================
        const renderSummary = () => {
            if (!window.allTransactions || window.allTransactions.length === 0) return;

            const income = window.allTransactions
                .filter(t => t.tipe === 'pemasukan')
                .reduce((s, t) => s + (t.jumlah || 0), 0);

            const expense = window.allTransactions
                .filter(t => t.tipe === 'pengeluaran')
                .reduce((s, t) => s + (t.jumlah || 0), 0);

            const profit = income - expense;

            // Misal penjualan anakan → kategori mengandung 'anakan'
            const sales = window.allTransactions
                .filter(t => t.kategori && t.kategori.toLowerCase().includes('anakan'))
                .length;

            const elIncome  = document.getElementById('totalIncome');
            const elExpense = document.getElementById('totalExpense');
            const elProfit  = document.getElementById('totalProfit');
            const elSales   = document.getElementById('totalSales');

            if (elIncome)  elIncome.textContent  = formatRupiah(income);
            if (elExpense) elExpense.textContent = formatRupiah(expense);
            if (elProfit)  elProfit.textContent  = formatRupiah(profit);
            if (elSales)   elSales.textContent   = sales + ' Ekor';
        };

        renderSummary();

        // ========================================================
        // 6. FORM TAMBAH TRANSAKSI (Modal)
        // ========================================================
        const addIncomeBtn  = document.getElementById('addIncomeBtn');
        const addExpenseBtn = document.getElementById('addExpenseBtn');
        const modalIncome   = document.getElementById('modalIncome');
        const modalExpense  = document.getElementById('modalExpense');
        const incomeForm    = document.getElementById('incomeForm');
        const expenseForm   = document.getElementById('expenseForm');
        const closeIncome   = document.getElementById('closeIncome');
        const closeExpense  = document.getElementById('closeExpense');
        const cancelIncome  = document.getElementById('cancelIncome');
        const cancelExpense = document.getElementById('cancelExpense');

        const openModal = (modal) => {
            if (!modal) return;
            modal.classList.remove('hidden');
            const dateInput = modal.querySelector('input[type="date"]');
            if (dateInput) dateInput.value = getToday();
        };

        const closeModal = (modal, form) => {
            if (!modal) return;
            modal.classList.add('hidden');
            if (form) form.reset();
        };

        addIncomeBtn?.addEventListener('click', () => openModal(modalIncome));
        addExpenseBtn?.addEventListener('click', () => openModal(modalExpense));

        [closeIncome, cancelIncome].forEach(btn =>
            btn?.addEventListener('click', () => closeModal(modalIncome, incomeForm))
        );
        [closeExpense, cancelExpense].forEach(btn =>
            btn?.addEventListener('click', () => closeModal(modalExpense, expenseForm))
        );

        // Hanya validasi sederhana, submit tetap ke backend (tanpa AJAX)
        incomeForm?.addEventListener('submit', (e) => {
            const formData = {
                category: document.getElementById('incomeCategory')?.value,
                description: document.getElementById('incomeDesc')?.value,
                date: document.getElementById('incomeDate')?.value,
                amount: document.getElementById('incomeAmount')?.value,
            };
            if (!formData.category || !formData.date || !formData.amount) {
                e.preventDefault();
                alert('Lengkapi semua kolom pemasukan!');
                return;
            }
        });

        expenseForm?.addEventListener('submit', (e) => {
            const formData = {
                category: document.getElementById('expenseCategory')?.value,
                description: document.getElementById('expenseDesc')?.value,
                date: document.getElementById('expenseDate')?.value,
                amount: document.getElementById('expenseAmount')?.value,
            };
            if (!formData.category || !formData.date || !formData.amount) {
                e.preventDefault();
                alert('Lengkapi semua kolom pengeluaran!');
                return;
            }
        });

        // ========================================================
        // 7. EDIT & DELETE TRANSAKSI (tombol di tabel)
        // ========================================================
        document.body.addEventListener('click', e => {
            if (e.target.closest('.delete-btn')) {
                const id = parseInt(e.target.closest('.delete-btn').dataset.id);
                // Hapus handling bisa ditambah di sini (konfirmasi, submit form hidden, dll.)
                console.log("Hapus transaksi id:", id);
            }
        });

        // Open modal edit (data diambil dari data-* di tombol .edit-transaction)
        document.addEventListener('click', function (e) {
            const btn = e.target.closest('.edit-transaction');
            if (!btn) return;

            const id = btn.dataset.id;

            document.getElementById('editTipe').value     = btn.dataset.tipe;
            document.getElementById('editType').value     = btn.dataset.tipe;
            document.getElementById('editCategory').value = btn.dataset.kategori;
            document.getElementById('editName').value     = btn.dataset.nama;
            document.getElementById('editDesc').value     = btn.dataset.deskripsi ?? '';
            document.getElementById('editDate').value     = btn.dataset.tanggal;
            document.getElementById('editAmount').value   = btn.dataset.jumlah;

            document.getElementById('editForm').action = `/peternak/keuangan/${id}`;
            document.getElementById('modalEdit').classList.remove('hidden');
        });

        document.getElementById('closeEdit')?.addEventListener('click', () =>
            document.getElementById('modalEdit')?.classList.add('hidden')
        );
        document.getElementById('cancelEdit')?.addEventListener('click', () =>
            document.getElementById('modalEdit')?.classList.add('hidden')
        );

        // ========================================================
        // 8. TOMBOL BULANAN / TAHUNAN
        // ========================================================
        const btnMonth = document.getElementById('btnMonth');
        const btnYear  = document.getElementById('btnYear');

        if (btnMonth && btnYear) {
            btnMonth.addEventListener('click', () => {
                btnMonth.classList.add('bg-primary', 'text-white');
                btnYear.classList.remove('bg-primary', 'text-white');
                window.renderCharts('month');
            });

            btnYear.addEventListener('click', () => {
                btnYear.classList.add('bg-primary', 'text-white');
                btnMonth.classList.remove('bg-primary', 'text-white');
                window.renderCharts('year');
            });
        }

        // ========================================================
        // 9. PAGINATION + FILTER + SORT TABEL
        // ========================================================
        let currentPage = 1;
        const perPage   = 10;

        const updateTable = () => {
            const tbody = document.getElementById("transactionTableBody");
            if (!tbody) return;

            tbody.innerHTML = "";

            const total = window.filteredData.length;
            const start = (currentPage - 1) * perPage;
            const paginated = window.filteredData.slice(start, start + perPage);

            paginated.forEach(t => {
                const tipeLabel = t.tipe === "pemasukan" ? "Pemasukan" : "Pengeluaran";
                const tipeClass = t.tipe === "pemasukan" ? "text-green-600" : "text-red-600";

                const kategoriLabel = t.kategori
                    ? t.kategori.replace(/_/g, " ").replace(/\b\w/g, c => c.toUpperCase())
                    : "-";

                const row = document.createElement("tr");
                row.innerHTML = `
                    <td class="px-4 py-2">${formatDate(t.tanggal)}</td>
                    <td class="px-4 py-2">${kategoriLabel}</td>
                    <td class="px-4 py-2">${t.nama_item ?? "-"}</td>
                    <td class="px-4 py-2">${t.deskripsi ?? "-"}</td>
                    <td class="px-4 py-2 ${tipeClass} font-semibold">${formatRupiah(t.jumlah)}</td>
                    <td class="px-4 py-2">${tipeLabel}</td>
                    <td class="px-4 py-2 text-center">
                        <button class="edit-btn text-blue-500 mx-1" data-id="${t.id}">
                            <i class="fas fa-pen"></i>
                        </button>
                        <button class="delete-btn text-red-500 mx-1" data-id="${t.id}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                `;
                tbody.appendChild(row);
            });

            const showCountEl  = document.getElementById("showCount");
            const totalCountEl = document.getElementById("totalCount");
            if (showCountEl)  showCountEl.textContent  = paginated.length;
            if (totalCountEl) totalCountEl.textContent = total;

            const btnPrev = document.getElementById("btnPrev");
            const btnNext = document.getElementById("btnNext");
            if (btnPrev) btnPrev.disabled = currentPage === 1;
            if (btnNext) btnNext.disabled = start + perPage >= total;
        };

        const applyFilters = () => {
            const search  = document.getElementById("searchTransaction")?.value.toLowerCase() || "";
            const type    = document.getElementById("filterType")?.value || "all";
            const sortBy  = document.getElementById("sortBy")?.value || "latest";

            // PENTING → gunakan allTransactions sebagai sumber tabel
            let filtered = [...window.allTransactions];

            // 🔍 FILTER SEARCH
            if (search) {
                filtered = filtered.filter(t => {
                    const key = [
                        t.nama_item ?? "",
                        t.deskripsi ?? "",
                        t.kategori ?? ""
                    ].join(" ").toLowerCase();

                    return key.includes(search);
                });
            }

            // 🔍 FILTER TYPE (income / expense → tipe pemasukan / pengeluaran)
            if (type !== "all") {
                filtered = filtered.filter(t => {
                    if (type === "income")  return t.tipe === "pemasukan";
                    if (type === "expense") return t.tipe === "pengeluaran";
                    return true;
                });
            }

            // 🔃 SORTING
            if (sortBy === "latest") {
                filtered.sort((a, b) => new Date(b.tanggal) - new Date(a.tanggal));
            } else if (sortBy === "oldest") {
                filtered.sort((a, b) => new Date(a.tanggal) - new Date(b.tanggal));
            } else if (sortBy === "amount_low") {
                filtered.sort((a, b) => (a.jumlah || 0) - (b.jumlah || 0));
            } else if (sortBy === "amount_high") {
                filtered.sort((a, b) => (b.jumlah || 0) - (a.jumlah || 0));
            }

            window.filteredData = filtered;
            currentPage = 1;
            updateTable();
        };

        // PREV / NEXT PAGE
        document.getElementById("btnPrev")?.addEventListener("click", () => {
            if (currentPage > 1) {
                currentPage--;
                updateTable();
            }
        });

        document.getElementById("btnNext")?.addEventListener("click", () => {
            const total = window.filteredData.length;
            if (currentPage * perPage < total) {
                currentPage++;
                updateTable();
            }
        });

        // APPLY FILTER BUTTON
        document.getElementById("applyFilter")?.addEventListener("click", applyFilters);

        // LOAD AWAL TABLE
        window.filteredData = [...window.allTransactions];
        updateTable();

        // ========================================================
        // 10. EXPORT CSV (PAKAI FILTERED DATA)
        // ========================================================
        const exportCSV = () => {
            if (!window.filteredData || window.filteredData.length === 0) {
                alert("Tidak ada data untuk diekspor!");
                return;
            }

            const rows = [
                ["Tanggal", "Kategori", "Nama Item", "Deskripsi", "Jumlah", "Status"],
                ...window.filteredData.map(t => [
                    formatDate(t.tanggal),
                    (t.kategori || "-").replace(/_/g, " ").replace(/\b\w/g, c => c.toUpperCase()),
                    t.nama_item ?? "-",
                    t.deskripsi ?? "-",
                    formatRupiah(t.jumlah),
                    t.tipe === "pemasukan" ? "Pemasukan" : "Pengeluaran"
                ]),
            ];

            const csvContent = "\uFEFF" + rows.map(r => r.join(",")).join("\n");
            const blob = new Blob([csvContent], { type: "text/csv;charset=utf-8;" });
            const url = URL.createObjectURL(blob);

            const today = new Date().toISOString().split("T")[0];
            const link = document.createElement("a");
            link.href = url;
            link.download = `keuangan_${today}.csv`;
            link.click();
        };

        document.getElementById("exportBtn")?.addEventListener("click", exportCSV);
    });
</script>
