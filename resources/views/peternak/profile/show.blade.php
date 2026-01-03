<x-layout>
    <main>
        <div class="px-4 py-6 max-w-4xl mx-auto">
            <div class="bg-white dark:bg-darker rounded-lg shadow p-6">
                <h1 class="text-2xl font-semibold mb-4">Your Profile</h1>

                @if (session('success'))
                    <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
                @endif

                <form action="{{ route('peternak.profile.update') }}" method="POST" enctype="multipart/form-data"
                    class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm">Name</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                class="block w-full px-3 py-2 border rounded-md">
                            @error('name')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="text-sm">Email</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                class="block w-full px-3 py-2 border rounded-md">
                            @error('email')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm">Password (kosongkan untuk tidak berubah)</label>
                            <input type="password" name="password" class="block w-full px-3 py-2 border rounded-md">
                            @error('password')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="text-sm">Confirm Password</label>
                            <input type="password" name="password_confirmation"
                                class="block w-full px-3 py-2 border rounded-md">
                        </div>
                    </div>

                    <hr class="my-4">

                    <h2 class="text-lg font-medium">Peternak Info</h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm">Nama Peternakan</label>
                            <input type="text" name="nama_peternakan"
                                value="{{ old('nama_peternakan', $peternak->nama_peternakan ?? '') }}" required
                                class="block w-full px-3 py-2 border rounded-md">
                            @error('nama_peternakan')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="text-sm">Nomor Handphone</label>
                            <input type="text" name="nomor_handphone"
                                value="{{ old('nomor_handphone', $peternak->nomor_handphone ?? '') }}"
                                class="block w-full px-3 py-2 border rounded-md">
                            @error('nomor_handphone')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="text-sm">Alamat</label>
                        <textarea name="alamat" rows="3" class="block w-full px-3 py-2 border rounded-md">{{ old('alamat', $peternak->alamat ?? '') }}</textarea>
                        @error('alamat')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-start space-x-4">
                        <div>
                            <label class="text-sm block">Foto Profil</label>
                            <input type="file" name="foto_profil" accept="image/*" class="mt-2">
                            @error('foto_profil')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            @if (!empty($peternak?->foto_profil))
                                <img src="{{ asset('storage/' . $peternak->foto_profil) }}"
                                    class="w-32 h-32 rounded-md object-cover shadow">
                            @else
                                <div
                                    class="w-32 h-32 rounded-md bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
                                    No Image</div>
                            @endif
                        </div>
                    </div>
                    <div class="flex justify-end space-x-3 pt-4">
                        <button type="submit"
                            class="
            px-4 py-2
            text-white
            bg-cyan-600
            hover:bg-cyan-700
            dark:bg-cyan-500 dark:hover:bg-cyan-600
            rounded-md
            focus:outline-none
            focus:ring focus:ring-cyan-400
            focus:ring-offset-1
            transition
        ">
                            Simpan
                        </button>
                    </div>



                </form>
            </div>
        </div>
    </main>
</x-layout>
