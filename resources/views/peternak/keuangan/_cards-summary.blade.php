<div class="grid grid-cols-1 gap-6 p-4 sm:grid-cols-2 lg:grid-cols-4">

    {{-- 🟢 Total Pemasukan --}}
    <x-keuangan.card
        title="Total Pemasukan"
        value="{{ 'Rp ' . number_format($summary['pemasukan'], 0, ',', '.') }}"
        color="green"
        icon="cash"
    />

    {{-- 🔴 Total Pengeluaran --}}
    <x-keuangan.card
        title="Total Pengeluaran"
        value="{{ 'Rp ' . number_format($summary['pengeluaran'], 0, ',', '.') }}"
        color="red"
        icon="cart"
    />

    {{-- 🔵 Keuntungan Bersih --}}
    <x-keuangan.card
        title="Keuntungan Bersih"
        value="{{ 'Rp ' . number_format($summary['saldo'], 0, ',', '.') }}"
        color="blue"
        icon="arrow"
    />

    {{-- 🟣 Penjualan Anakan --}}
    <x-keuangan.card
        title="Penjualan Anakan"
        value="{{ $salesCount . ' Ekor' }}"
        color="purple"
        icon="users"
    />

</div>
