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
                        <h2 class="text-2xl font-bold text-gray-900">Informasi Perjalanan Dinas</h2>
                        <p class="text-sm text-gray-600 mt-1">Detail surat tugas dan perjalanan dinas</p>
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

            <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6">
                    <!-- Section Header -->
                    <div class="mb-6 pb-4 border-b-2 border-gray-100">
                        <h2 class="text-xl font-bold text-gray-900 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-green-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
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
        </div>
    </div>
</x-app-layout>
