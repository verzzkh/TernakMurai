<!-- ============================================================
     MODAL PEMASUKAN
============================================================ -->
<div id="modalIncome" class="fixed inset-0 hidden bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white dark:bg-darker rounded-lg shadow-lg w-full max-w-lg p-6 relative">

        <h2 class="text-xl font-semibold mb-4">Tambah Pemasukan</h2>

        <form id="incomeForm" method="POST" action="{{ route('peternak.keuangan.store') }}">
            @csrf

            <!-- Tipe Transaksi -->
            <input type="hidden" name="tipe" value="pemasukan">

            <!-- Kategori -->
            <label class="block mb-2 text-sm font-medium text-gray-700">Kategori Pemasukan</label>
            <select name="kategori" id="incomeCategory" class="w-full border rounded p-2 mb-3" required>
                <option value="">-- Pilih Kategori --</option>
                <option value="penjualan_anakan">Penjualan Anakan</option>
                <option value="penjualan_indukan">Penjualan Indukan</option>
                <option value="pemasukan_lainnya">Pemasukan Lainnya</option>
            </select>

            <!-- Nama item -->
            <label class="block mb-2 text-sm font-medium text-gray-700">Nama Item / Sumber Pemasukan</label>
            <input name="nama_item" id="incomeItem" type="text" class="w-full border rounded p-2 mb-3"
                placeholder="Contoh: Penjualan burung ring 123" required>

            <!-- Deskripsi -->
            <label class="block mb-2 text-sm font-medium text-gray-700">Deskripsi (opsional)</label>
            <input name="deskripsi" id="incomeDesc" type="text" class="w-full border rounded p-2 mb-3"
                placeholder="Catatan tambahan">

            <!-- Tanggal -->
            <label class="block mb-2 text-sm font-medium text-gray-700">Tanggal</label>
            <input name="tanggal" id="incomeDate" type="date" class="w-full border rounded p-2 mb-3" required>

            <!-- Jumlah -->
            <label class="block mb-2 text-sm font-medium text-gray-700">Jumlah (Rp)</label>
            <input name="jumlah" id="incomeAmount" type="text" step="0.01" min="0"
                class="w-full border rounded p-2 mb-3" placeholder="0" required>

            <!-- Tombol -->
            <div class="flex justify-end gap-2 mt-4">
                <button type="button" id="cancelIncome" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                    Simpan
                </button>
            </div>
        </form>

        <button id="closeIncome" class="absolute top-3 right-4 text-gray-500 hover:text-gray-700">✕</button>
    </div>
</div>


<!-- ============================================================
     MODAL PENGELUARAN
============================================================ -->
<div id="modalExpense" class="fixed inset-0 hidden bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white dark:bg-darker rounded-lg shadow-lg w-full max-w-lg p-6 relative">

        <h2 class="text-xl font-semibold mb-4">Tambah Pengeluaran</h2>

        <form id="expenseForm" method="POST" action="{{ route('peternak.keuangan.store') }}">
            @csrf

            <!-- Tipe Transaksi -->
            <input type="hidden" name="tipe" value="pengeluaran">

            <!-- Kategori -->
            <label class="block mb-2 text-sm font-medium text-gray-700">Kategori Pengeluaran</label>
            <select name="kategori" id="expenseCategory" class="w-full border rounded p-2 mb-3" required>
                <option value="">-- Pilih Kategori --</option>
                <option value="pakan">Pakan</option>
                <option value="vitamin">Vitamin</option>
                <option value="perawatan">Perawatan</option>
                <option value="pengeluaran_lainnya">Pengeluaran Lainnya</option>
            </select>

            <!-- Nama Item -->
            <label class="block mb-2 text-sm font-medium text-gray-700">Nama Item / Jenis Pengeluaran</label>
            <input name="nama_item" id="expenseItem" type="text" class="w-full border rounded p-2 mb-3"
                placeholder="Contoh: Pakan Voer, obat cacing" required>

            <!-- Deskripsi -->
            <label class="block mb-2 text-sm font-medium text-gray-700">Deskripsi (opsional)</label>
            <input name="deskripsi" id="expenseDesc" type="text" class="w-full border rounded p-2 mb-3"
                placeholder="Catatan tambahan">

            <!-- Tanggal -->
            <label class="block mb-2 text-sm font-medium text-gray-700">Tanggal</label>
            <input name="tanggal" id="expenseDate" type="date" class="w-full border rounded p-2 mb-3" required>

            <!-- Jumlah -->
            <label class="block mb-2 text-sm font-medium text-gray-700">Jumlah (Rp)</label>
            <input name="jumlah" id="expenseAmount" type="text" step="0.01" min="0"
                class="w-full border rounded p-2 mb-3" placeholder="0" required>

            <!-- Tombol -->
            <div class="flex justify-end gap-2 mt-4">
                <button type="button" id="cancelExpense" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                    Simpan
                </button>
            </div>
        </form>

        <button id="closeExpense" class="absolute top-3 right-4 text-gray-500 hover:text-gray-700">✕</button>
    </div>

</div>

<!-- Modal Edit Transaksi -->
<div id="modalEdit" class="fixed inset-0 hidden bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white dark:bg-darker rounded-lg shadow-lg w-full max-w-lg p-6 relative">

        <h2 class="text-xl font-semibold mb-4">Edit Transaksi</h2>

        <form id="editForm" method="POST">
            @csrf
            @method('PUT')

            <!-- ❗ HARUS ADA: Hidden tipe untuk dikirim ke backend -->
            <input type="hidden" name="tipe" id="editTipe">

            <!-- Tipe (readonly tampilan saja) -->
            <label class="block mb-2 text-sm font-medium">Tipe</label>
            <input id="editType" type="text" class="w-full border rounded p-2 mb-3 bg-gray-100 text-gray-700"
                readonly>

            <!-- Kategori -->
            <label class="block mb-2 text-sm font-medium">Kategori</label>
            <input id="editCategory" name="kategori" type="text" class="w-full border rounded p-2 mb-3">

            <!-- Nama item -->
            <label class="block mb-2 text-sm font-medium">Nama Item</label>
            <input id="editName" name="nama_item" type="text" class="w-full border rounded p-2 mb-3">

            <!-- Deskripsi -->
            <label class="block mb-2 text-sm font-medium">Deskripsi</label>
            <input id="editDesc" name="deskripsi" type="text" class="w-full border rounded p-2 mb-3">

            <!-- Tanggal -->
            <label class="block mb-2 text-sm font-medium">Tanggal</label>
            <input id="editDate" name="tanggal" type="date" class="w-full border rounded p-2 mb-3">

            <!-- Jumlah -->
            <label class="block mb-2 text-sm font-medium">Jumlah (Rp)</label>
            <input id="editAmount" name="jumlah" type="text" class="w-full border rounded p-2 mb-3">

            <div class="flex justify-end gap-2 mt-4">
                <button type="button" id="cancelEdit" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Simpan
                </button>
            </div>
        </form>

        <button id="closeEdit" class="absolute top-3 right-4 text-gray-500 hover:text-gray-700">✕</button>
    </div>
</div>
