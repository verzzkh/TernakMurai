{{-- resources/views/peternak/keuangan/_table.blade.php --}}

<div class="p-4">
    <div class="bg-white rounded-md shadow dark:bg-darker">

 <!-- FILTER BAR -->
<div class="flex flex-col md:flex-row md:items-center md:justify-between p-4 border-b dark:border-primary">

    <!-- FORM FILTER -->
    <form method="GET" class="flex flex-wrap items-center gap-2">

        <!-- Search -->
        <input name="search" type="text" placeholder="Cari transaksi..."
            value="{{ request('search') }}"
            class="px-3 py-2 text-sm border rounded-md dark:bg-darker dark:border-primary-light dark:text-light">

        <!-- Type Filter -->
        <select name="type"
            class="px-3 py-2 text-sm border rounded-md dark:bg-darker dark:border-primary-light dark:text-light">
            <option value="all"     {{ request('type')=='all' ? 'selected' : '' }}>Semua Tipe</option>
            <option value="income"  {{ request('type')=='income' ? 'selected' : '' }}>Pemasukan</option>
            <option value="expense" {{ request('type')=='expense' ? 'selected' : '' }}>Pengeluaran</option>
        </select>

        <!-- Sort By -->
        <select name="sort"
            class="px-3 py-2 text-sm border rounded-md dark:bg-darker dark:border-primary-light dark:text-light">
            <option value="latest"      {{ request('sort')=='latest' ? 'selected' : '' }}>Terbaru</option>
            <option value="oldest"      {{ request('sort')=='oldest' ? 'selected' : '' }}>Terlama</option>
            <option value="amount_low"  {{ request('sort')=='amount_low' ? 'selected' : '' }}>Jumlah Terkecil</option>
            <option value="amount_high" {{ request('sort')=='amount_high' ? 'selected' : '' }}>Jumlah Terbesar</option>
        </select>

        <button type="submit"
            class="px-3 py-2 text-sm  bg-blue-600 hover:bg-blue-700 text-white rounded-md ">
            Terapkan
        </button>

    </form>

   <button id="exportBtn"
    class="mt-3 md:mt-0 px-4 py-2 text-sm text-white
           bg-blue-600 hover:bg-blue-700
           rounded-md transition">
    Ekspor CSV
</button>


</div>



        <!-- TABLE -->
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-600 dark:text-light">
                <thead class="bg-gray-100 dark:bg-primary-dark">
                    <tr>
                        <th class="px-4 py-3">Tanggal</th>
                        <th class="px-4 py-3">Kategori</th>
                        <th class="px-4 py-3">Nama Item</th>
                        <th class="px-4 py-3">Jumlah</th>
                        <th class="px-4 py-3">Tipe</th>
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody>
    @forelse($transaksi as $t)
        <tr class="border-b">
            <td class="px-4 py-2">{{ $t->tanggal->format('d/m/Y') }}</td>

            <td class="px-4 py-2">
                {{ ucfirst(str_replace('_', ' ', $t->kategori)) }}
            </td>

            <td class="px-4 py-2">{{ $t->nama_item }}</td>

            <td class="px-4 py-2 {{ $t->tipe == 'pemasukan' ? 'text-green-600' : 'text-red-600' }} font-semibold">
                Rp {{ number_format($t->jumlah, 0, ',', '.') }}
            </td>

            <td class="px-4 py-2">
                {{ $t->tipe == 'pemasukan' ? 'Pemasukan' : 'Pengeluaran' }}
            </td>

            <td class="px-4 py-2 text-center">
    <div class="inline-flex gap-2">

        <!-- tombol edit -->
        <button 
            class="edit-transaction px-2 py-1 bg-blue-500 text-white rounded text-xs"
            data-id="{{ $t->id }}"
            data-tanggal="{{ $t->tanggal->format('Y-m-d') }}"
            data-kategori="{{ $t->kategori }}"
            data-nama="{{ $t->nama_item }}"
            data-deskripsi="{{ $t->deskripsi }}"
            data-jumlah="{{ $t->jumlah }}"
            data-tipe="{{ $t->tipe }}"
        >
            Edit
        </button>

        <!-- tombol hapus -->
       <form id="deleteForm-{{ $t->id }}" 
      action="{{ route('peternak.keuangan.destroy', $t->id) }}"
      method="POST">
    @csrf
    @method('DELETE')

    <button type="button"
            onclick="confirmDelete({{ $t->id }})"
            class="px-2 py-1 bg-red-500 text-white rounded text-xs">
        Hapus
    </button>
</form>



    </div>
</td>

        </tr>
    @empty
        <tr>
            <td colspan="6" class="text-center py-4 text-gray-500">
                Tidak ada data.
            </td>
        </tr>
    @endforelse
</tbody>

            </table>
        </div>

        <!-- PAGINATION -->
        <div class="p-4">
            {{ $transaksi->links() }}
        </div>

    </div>
</div>
