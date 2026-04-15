@use(App\Helpers\LampiranHelper)

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Informasi Perjalanan Terbaru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-4">
                <div class="p-6">
                    <!-- Header Section -->
                    <div class="mb-6 border-b border-gray-200 pb-4">
                        <h2 class="text-2xl font-bold text-gray-900">Informasi Pegawai</h2>
                        <p class="text-sm text-gray-600 mt-1">Detail lengkap data pegawai dan akun</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Name -->
                        <div class="space-y-1">
                            <label class="text-xs font-medium text-gray-500 uppercase tracking-wider flex items-center">
                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Nama Lengkap
                            </label>
                            <p class="text-base ml-2 mt-1 font-semibold text-gray-900">{{ $user->name ?? '-' }}</p>
                        </div>

                        <!-- Email -->
                        <div class="space-y-1">
                            <label class="text-xs font-medium text-gray-500 uppercase tracking-wider flex items-center">
                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                Email
                            </label>
                            <p class="text-base ml-2 mt-1 text-gray-900 break-all">{{ $user->email ?? '-' }}</p>
                        </div>

                        <!-- Role/Hak Akses -->
                        <div class="space-y-1">
                            <label class="text-xs font-medium text-gray-500 uppercase tracking-wider flex items-center">
                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                                Role/Hak Akses
                            </label>

                            <div>
                                @php
                                    $roleColors = [
                                        'admin' => 'bg-red-100 text-red-800 border-red-200',
                                        'staff' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                        'user' => 'bg-green-100 text-green-800 border-green-200',
                                    ];
                                    $role = $user->role ?? 'user';
                                    $colorClass = $roleColors[$role] ?? 'bg-gray-100 text-gray-800 border-gray-200';
                                @endphp
                                <span
                                    class="inline-flex items-center ml-2 mt-1 px-3 py-1 rounded-full text-sm font-medium border {{ $colorClass }}">
                                    {{ ucfirst($role) }}
                                </span>
                            </div>
                        </div>

                        <!-- NIP -->
                        @if ($user->dataDiri->nip ?? null)
                            <div class="space-y-1">
                                <label
                                    class="text-xs font-medium text-gray-500 uppercase tracking-wider flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                                    </svg>
                                    NIP
                                </label>
                                <p class="text-base font-mono text-gray-900">{{ $user->dataDiri->nip ?? '-' }}</p>
                            </div>
                        @endif

                        <!-- Pangkat/Golongan -->
                        @if ($user->dataDiri->pangkat ?? null)
                            <div class="space-y-1">
                                <label
                                    class="text-xs font-medium text-gray-500 uppercase tracking-wider flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                    </svg>
                                    Pangkat/Golongan
                                </label>
                                <p class="text-base text-gray-900">{{ $user->dataDiri->pangkat ?? '-' }}</p>
                            </div>
                        @endif

                        <!-- Unit Kerja -->
                        @if ($user->dataDiri->unit_kerja ?? null)
                            <div class="space-y-1">
                                <label
                                    class="text-xs font-medium text-gray-500 uppercase tracking-wider flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                    Unit Kerja
                                </label>
                                <div>
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-lg text-sm font-medium bg-blue-50 text-blue-800 border border-blue-200">
                                        {{ ucfirst($user->dataDiri->unit_kerja ?? '-') }}
                                    </span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-4">
                <div class="p-6">
                    <!-- Header Section 2 -->
                    <div class="mb-6 border-b border-gray-200 pb-4">
                        <h2 class="text-2xl font-bold text-gray-900">Informasi SPPD</h2>
                        <p class="text-sm text-gray-600 mt-1">Detail SPPD</p>
                    </div>

                    @if (!$sppd)
                        <div class="bg-white overflow-hidden">
                            <div class="p-12 text-center mb-4">
                                <h3 class="mt-4 text-lg font-medium text-gray-900">Belum Ada Data SPPD</h3>
                                <p class="mt-2 text-sm text-gray-500">
                                    Silakan hubungi administrator untuk menambahkan data.
                                </p>
                            </div>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Surat Tugas -->
                            <div class="space-y-1">
                                <label
                                    class="text-xs font-medium text-gray-500 uppercase tracking-wider flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Nomor Surat Tugas
                                </label>
                                <p class="text-base font-semibold text-gray-900">{{ $sppd->st ?? '-' }}</p>
                            </div>

                            <!-- Kota -->
                            <div class="space-y-1">
                                <label
                                    class="text-xs font-medium text-gray-500 uppercase tracking-wider flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    Kota Tujuan
                                </label>
                                <div>
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-lg text-sm font-medium bg-green-50 text-green-800 border border-green-200">
                                        {{ $sppd->kota ?? '-' }}
                                    </span>
                                </div>
                            </div>

                            <!-- Tanggal Berangkat -->
                            <div class="space-y-1">
                                <label
                                    class="text-xs font-medium text-gray-500 uppercase tracking-wider flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    Tanggal Berangkat
                                </label>
                                <p class="text-base text-gray-900">
                                    {{ $sppd->tg_berangkat ? $sppd->tg_berangkat->format('d F Y') : '-' }}</p>
                            </div>

                            <!-- Tanggal Pulang -->
                            <div class="space-y-1">
                                <label
                                    class="text-xs font-medium text-gray-500 uppercase tracking-wider flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    Tanggal Pulang
                                </label>
                                <p class="text-base text-gray-900">
                                    {{ $sppd->tg_pulang ? $sppd->tg_pulang->format('d F Y') : '-' }}</p>
                            </div>

                            <!-- Angkutan -->
                            <div class="space-y-1">
                                <label
                                    class="text-xs font-medium text-gray-500 uppercase tracking-wider flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                    </svg>
                                    Jenis Angkutan
                                </label>
                                <div>
                                    @php
                                        $angkutanColors = [
                                            'darat' => 'bg-gray-100 text-gray-800 border-gray-200',
                                            'laut' => 'bg-blue-100 text-blue-800 border-blue-200',
                                            'udara' => 'bg-sky-100 text-sky-800 border-sky-200',
                                        ];
                                        $angkutan = $sppd->angkutan ?? 'darat';
                                        $colorClass =
                                            $angkutanColors[$angkutan] ?? 'bg-gray-100 text-gray-800 border-gray-200';
                                    @endphp
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium border {{ $colorClass }}">
                                        {{ ucfirst($angkutan) }}
                                    </span>
                                </div>
                            </div>

                            <!-- Uraian Perjalanan -->
                            <div class="space-y-1 md:col-span-2">
                                <label
                                    class="text-xs font-medium text-gray-500 uppercase tracking-wider flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 6h16M4 12h16M4 18h7" />
                                    </svg>
                                    Uraian Perjalanan
                                </label>
                                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                    <p class="text-base text-gray-900">{{ $sppd->deskripsi }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg mb-4">
                <div class="p-6">
                    <!-- Section Header -->
                    <div class="mb-6 pb-4 border-b-2 border-gray-100">
                        <h2 class="text-xl font-bold text-gray-900 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960"
                                width="24px" fill="#e3e3e3">
                                <path
                                    d="M600-320h120q17 0 28.5-11.5T760-360v-240q0-17-11.5-28.5T720-640H600q-17 0-28.5 11.5T560-600v240q0 17 11.5 28.5T600-320Zm40-80v-160h40v160h-40Zm-280 80h120q17 0 28.5-11.5T520-360v-240q0-17-11.5-28.5T480-640H360q-17 0-28.5 11.5T320-600v240q0 17 11.5 28.5T360-320Zm40-80v-160h40v160h-40Zm-200 80h80v-320h-80v320ZM80-160v-640h800v640H80Zm80-560v480-480Zm0 480h640v-480H160v480Z" />
                            </svg>
                            Detail Biaya Perjalanan Dinas
                        </h2>
                        <p class="text-sm text-gray-500 mt-1 ml-8">Rincian biaya yang dikeluarkan selama perjalanan
                            dinas</p>
                    </div>

                    @if (!$perjalanan || !$sppd)
                        <div class="bg-white overflow-hidden">
                            <div class="p-12 text-center mb-4">
                                <h3 class="mt-4 text-lg font-medium text-gray-900">Belum Ada Data</h3>
                                <p class="mt-2 text-sm text-gray-500">
                                    Anda belum memiliki data perjalanan dinas. Silakan hubungi administrator untuk
                                    menambahkan data.
                                </p>
                            </div>
                        </div>
                    @else
                        <!-- Table -->
                        <div class="overflow-hidden border border-gray-200 rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Jenis Biaya
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Nominal
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <!-- Tiket Pergi -->
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <span class="text-2xl mr-3">✈️</span>
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">Tiket Pergi</div>
                                                    <div class="text-xs text-gray-500">Transportasi menuju lokasi</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            <div class="text-sm font-semibold text-gray-900">
                                                Rp {{ number_format($perjalanan->tiket_pergi ?? 0, 0, ',', '.') }}
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Tiket Pulang -->
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <span class="text-2xl mr-3">🏠</span>
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">Tiket Pulang</div>
                                                    <div class="text-xs text-gray-500">Transportasi kembali ke asal
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            <div class="text-sm font-semibold text-gray-900">
                                                Rp {{ number_format($perjalanan->tiket_pulang ?? 0, 0, ',', '.') }}
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Hotel -->
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <span class="text-2xl mr-3">🏨</span>
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">Hotel/Penginapan
                                                    </div>
                                                    <div class="text-xs text-gray-500">Biaya akomodasi</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            <div class="text-sm font-semibold text-gray-900">
                                                Rp {{ number_format($perjalanan->hotel ?? 0, 0, ',', '.') }}
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Uang Harian -->
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <span class="text-2xl mr-3">💵</span>
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">Uang Harian</div>
                                                    <div class="text-xs text-gray-500">Biaya konsumsi harian</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            <div class="text-sm font-semibold text-gray-900">
                                                Rp {{ number_format($perjalanan->uang_harian ?? 0, 0, ',', '.') }}
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Uang Representasi -->
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <span class="text-2xl mr-3">💼</span>
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">Uang Representasi
                                                    </div>
                                                    <div class="text-xs text-gray-500">Biaya representasi</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            <div class="text-sm font-semibold text-gray-900">
                                                Rp
                                                {{ number_format($perjalanan->uang_representasi ?? 0, 0, ',', '.') }}
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Transport Lokal Pergi -->
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <span class="text-2xl mr-3">🚖</span>
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">Transport Lokal
                                                        (Pergi)
                                                    </div>
                                                    <div class="text-xs text-gray-500">Transportasi lokal di tujuan
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            <div class="text-sm font-semibold text-gray-900">
                                                Rp
                                                {{ number_format($perjalanan->transport_lokal_pergi ?? 0, 0, ',', '.') }}
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Transport Lokal Pulang -->
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <span class="text-2xl mr-3">🚕</span>
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">Transport Lokal
                                                        (Pulang)
                                                    </div>
                                                    <div class="text-xs text-gray-500">Transportasi lokal kepulangan
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            <div class="text-sm font-semibold text-gray-900">
                                                Rp
                                                {{ number_format($perjalanan->transport_lokal_pulang ?? 0, 0, ',', '.') }}
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- BBM + Toll -->
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <span class="text-2xl mr-3">⛽</span>
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">BBM + Toll</div>
                                                    <div class="text-xs text-gray-500">Bahan bakar dan tol</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            <div class="text-sm font-semibold text-gray-900">
                                                Rp {{ number_format($perjalanan->bbm_tol ?? 0, 0, ',', '.') }}
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Total -->
                                    <tr class="bg-green-50 border-t-2 border-green-200">
                                        <td class="px-6 py-5 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <span class="text-3xl mr-3">💰</span>
                                                <div>
                                                    <div class="text-base font-bold text-green-900 uppercase">Total
                                                        Biaya
                                                        SPPD</div>
                                                    <div class="text-xs text-green-700">Jumlah keseluruhan biaya</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-5 whitespace-nowrap text-right">
                                            <div class="text-2xl font-bold text-green-600">
                                                Rp {{ number_format($perjalanan->jumlah_sppd ?? 0, 0, ',', '.') }}
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6">

                    {{-- Section Header --}}
                    <div class="mb-6 pb-4 border-b-2 border-gray-100">
                        <h2 class="text-xl font-bold text-gray-900 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-green-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            Lampiran Perjalanan Dinas
                        </h2>
                        <p class="text-sm text-gray-500 mt-1 ml-8">Lampiran foto dan dokumen perjalanan dinas.</p>
                    </div>

                    @if (!$sppd)
                        <div class="p-12 text-center">
                            <div
                                class="w-16 h-16 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900">Belum Ada Data</h3>
                            <p class="mt-2 text-sm text-gray-500">
                                Anda belum memiliki data perjalanan dinas.<br>
                                Silakan hubungi administrator untuk menambahkan data.
                            </p>
                        </div>
                    @else
                        <div class="space-y-6">
                            @foreach ($sections as $section)
                                <div class="border border-gray-200 rounded-xl overflow-hidden">

                                    {{-- Card Header --}}
                                    <div
                                        class="flex items-center justify-between px-5 py-3.5
                                    {{ $section['color']['bg'] }} border-b {{ $section['color']['border'] }}">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-8 h-8 rounded-lg bg-white/70 flex items-center justify-center shadow-sm">
                                                <svg class="w-4 h-4 {{ $section['color']['icon'] }}" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="{{ $section['icon'] }}" />
                                                </svg>
                                            </div>
                                            <span
                                                class="font-semibold text-gray-800 text-sm">{{ $section['label'] }}</span>
                                        </div>
                                        <span
                                            class="text-xs font-bold px-2.5 py-1 rounded-full {{ $section['color']['badge'] }}">
                                            {{ $section['count'] }} {{ $section['count'] === 1 ? 'file' : 'files' }}
                                        </span>
                                    </div>

                                    {{-- Card Body --}}
                                    <div class="p-4">
                                        @if ($section['count'] === 0)
                                            <div
                                                class="flex items-center gap-3 py-3 px-4 bg-gray-50 rounded-lg text-gray-400">
                                                <svg class="w-5 h-5 flex-shrink-0" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                                </svg>
                                                <span class="text-sm">Tidak ada lampiran.</span>
                                            </div>
                                        @else
                                            {{-- ── IMAGES ─────────────────────────────────── --}}
                                            @if (count($section['images']) > 0)
                                                <div
                                                    class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3
                                                {{ count($section['pdfs']) > 0 ? 'mb-4' : '' }}">
                                                    @foreach ($section['images'] as $img)
                                                        <a href="{{ LampiranHelper::url($img) }}" target="_blank"
                                                            class="group relative block aspect-square rounded-xl overflow-hidden
                                                      border-2 border-gray-100 hover:border-gray-300
                                                      transition-all shadow-sm hover:shadow-md">

                                                            <img src="{{ LampiranHelper::url($img) }}"
                                                                alt="{{ LampiranHelper::filename($img) }}"
                                                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">

                                                            {{-- Hover overlay --}}
                                                            <div
                                                                class="absolute inset-0 bg-black/0 group-hover:bg-black/30
                                                            transition-all duration-200 flex items-center justify-center">
                                                                <div
                                                                    class="opacity-0 group-hover:opacity-100 transition-opacity">
                                                                    <div class="bg-white/90 rounded-full p-2 shadow">
                                                                        <svg class="w-5 h-5 text-gray-800"
                                                                            fill="none" stroke="currentColor"
                                                                            viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                stroke-width="2"
                                                                                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                                                        </svg>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            {{-- Filename --}}
                                                            <div
                                                                class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/60 to-transparent
                                                            px-2 py-1.5 opacity-0 group-hover:opacity-100 transition-opacity">
                                                                <p class="text-white text-[10px] truncate">
                                                                    {{ LampiranHelper::filename($img) }}
                                                                </p>
                                                            </div>
                                                        </a>
                                                    @endforeach
                                                </div>
                                            @endif

                                            {{-- ── PDFs ────────────────────────────────────── --}}
                                            @if (count($section['pdfs']) > 0)
                                                <div class="space-y-4">
                                                    @foreach ($section['pdfs'] as $pdf)
                                                        <div class="border border-gray-200 rounded-xl overflow-hidden">

                                                            {{-- PDF Toolbar --}}
                                                            <div
                                                                class="flex items-center justify-between px-4 py-2.5 bg-gray-50 border-b border-gray-200">
                                                                <div class="flex items-center gap-2.5">
                                                                    <div
                                                                        class="w-7 h-7 rounded-md bg-red-100 flex items-center justify-center flex-shrink-0">
                                                                        <svg class="w-4 h-4 text-red-600"
                                                                            fill="currentColor" viewBox="0 0 24 24">
                                                                            <path
                                                                                d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zm-1 1.5L18.5 9H13V3.5z" />
                                                                        </svg>
                                                                    </div>
                                                                    <div>
                                                                        <p
                                                                            class="text-sm font-medium text-gray-700 truncate max-w-xs">
                                                                            {{ LampiranHelper::filename($pdf) }}
                                                                        </p>
                                                                        <p class="text-xs text-gray-400">PDF Document
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                                <div class="flex items-center gap-2">
                                                                    <a href="{{ LampiranHelper::url($pdf) }}"
                                                                        target="_blank"
                                                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium
                                                                  text-gray-600 bg-white border border-gray-300 rounded-lg
                                                                  hover:bg-gray-50 transition-all">
                                                                        <svg class="w-3.5 h-3.5" fill="none"
                                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                stroke-width="2"
                                                                                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                                                        </svg>
                                                                        Buka Tab Baru
                                                                    </a>
                                                                    <a href="{{ LampiranHelper::url($pdf) }}"
                                                                        download="{{ LampiranHelper::filename($pdf) }}"
                                                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium
                                                                  text-white {{ $section['color']['btn'] }} rounded-lg transition-all">
                                                                        <svg class="w-3.5 h-3.5" fill="none"
                                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                stroke-width="2"
                                                                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                                        </svg>
                                                                        Unduh
                                                                    </a>
                                                                </div>
                                                            </div>

                                                            {{-- iFrame --}}
                                                            <iframe src="{{ LampiranHelper::url($pdf) }}"
                                                                class="w-full" style="height: 500px; border: none;"
                                                                title="{{ LampiranHelper::filename($pdf) }}"
                                                                loading="lazy">
                                                                <p class="p-4 text-sm text-gray-500 text-center">
                                                                    Browser Anda tidak mendukung tampilan PDF.
                                                                    <a href="{{ LampiranHelper::url($pdf) }}"
                                                                        class="text-blue-600 underline">
                                                                        Klik di sini untuk membuka.
                                                                    </a>
                                                                </p>
                                                            </iframe>

                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
