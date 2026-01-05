<x-layout>
    <main>
        <!-- Header -->
        <div class="flex items-center justify-between px-4 py-4 border-b lg:py-6 dark:border-primary-darker">
            <h1 class="text-2xl font-semibold text-gray-800 dark:text-light">Pencatatan Keuangan</h1>
            <div class="flex space-x-2">
                <button id="addIncomeBtn"
                    class="px-4 py-2 text-sm text-white rounded-md bg-green-600 hover:bg-green-700 focus:outline-none">
                    <i class="fas fa-plus-circle mr-1"></i> Tambah Pemasukan
                </button>
                <button id="addExpenseBtn"
                    class="px-4 py-2 text-sm text-white rounded-md bg-red-600 hover:bg-red-700 focus:outline-none">
                    <i class="fas fa-minus-circle mr-1"></i> Tambah Pengeluaran
                </button>
            </div>
        </div>

        <!-- Ringkasan, Chart, dan Tabel -->
        <div class="mt-6 space-y-8">
            @include('peternak.keuangan._cards-summary')
            @include('peternak.keuangan._charts')
            @include('peternak.keuangan._table')
        </div>

        <!-- Modals -->
        @include('peternak.keuangan._modals')
    </main>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function confirmDelete(id) {
        event.preventDefault();

        Swal.fire({
            title: "Hapus transaksi?",
            text: "Tindakan ini tidak bisa dibatalkan.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Ya, hapus",
            cancelButtonText: "Batal",
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById("deleteForm-" + id).submit();
            }
        });
    }
</script>

<script>
function formatRupiahInput(inputId) {
    const el = document.getElementById(inputId);

    el.addEventListener("input", function () {
        let value = this.value.replace(/\D/g, "");
        this.value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    });

    el.form?.addEventListener("submit", function () {
        el.value = el.value.replace(/\./g, "");
    });
}

formatRupiahInput("incomeAmount");
formatRupiahInput("expenseAmount");
formatRupiahInput("editAmount");


function formatRupiah(num) {
    return num.replace(/\D/g, "")
              .replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

document.addEventListener("DOMContentLoaded", () => {
    const editAmount = document.getElementById("editAmount");

    if (!editAmount) return;

    // Format saat halaman load
    if (editAmount.value.trim() !== "") {
        editAmount.value = formatRupiah(editAmount.value);
    }

    // Format saat mengetik
    editAmount.addEventListener("input", () => {
        editAmount.value = formatRupiah(editAmount.value);
    });

    // Bersihkan titik sebelum submit
    editAmount.form.addEventListener("submit", () => {
        editAmount.value = editAmount.value.replace(/\./g, "");
    });
});

</script>



        @include('peternak.keuangan._script')
    @endpush
</x-layout>
