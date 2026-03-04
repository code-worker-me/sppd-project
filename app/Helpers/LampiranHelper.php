<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class LampiranHelper
{
    private const IMAGE_EXTENSIONS = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'];

    public const SECTIONS = [
        [
            'key' => 'laporan_perjalanan',
            'label' => 'Laporan Perjalanan',
            'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
            'color' => 'blue',
        ],
        [
            'key' => 'foto_kegiatan',
            'label' => 'Foto Kegiatan',
            'icon' => 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z',
            'color' => 'green',
        ],
        [
            'key' => 'blanko_sppd',
            'label' => 'Blanko SPPD',
            'icon' => 'M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z',
            'color' => 'orange',
        ],
        [
            'key' => 'surat_tugas',
            'label' => 'Surat Tugas',
            'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2',
            'color' => 'purple',
        ],
    ];

    public const COLOR_MAP = [
        'blue' => ['bg' => 'bg-blue-50',   'icon' => 'text-blue-600',   'border' => 'border-blue-200',   'badge' => 'bg-blue-100 text-blue-700',   'btn' => 'bg-blue-600 hover:bg-blue-700'],
        'green' => ['bg' => 'bg-green-50',  'icon' => 'text-green-600',  'border' => 'border-green-200',  'badge' => 'bg-green-100 text-green-700',  'btn' => 'bg-green-600 hover:bg-green-700'],
        'orange' => ['bg' => 'bg-orange-50', 'icon' => 'text-orange-600', 'border' => 'border-orange-200', 'badge' => 'bg-orange-100 text-orange-700', 'btn' => 'bg-orange-600 hover:bg-orange-700'],
        'purple' => ['bg' => 'bg-purple-50', 'icon' => 'text-purple-600', 'border' => 'border-purple-200', 'badge' => 'bg-purple-100 text-purple-700', 'btn' => 'bg-purple-600 hover:bg-purple-700'],
    ];

    public static function parseFiles(mixed $raw): array
    {
        $files = match (true) {
            is_array($raw) => $raw,
            is_string($raw) => json_decode($raw, true) ?? [],
            default => [],
        };

        return array_values(array_filter($files));
    }

    public static function isImage(string $path): bool
    {
        return in_array(
            strtolower(pathinfo($path, PATHINFO_EXTENSION)),
            self::IMAGE_EXTENSIONS
        );
    }

    public static function isPdf(string $path): bool
    {
        return strtolower(pathinfo($path, PATHINFO_EXTENSION)) === 'pdf';
    }

    public static function images(array $files): array
    {
        return array_values(array_filter($files, fn ($f) => self::isImage($f)));
    }

    public static function pdfs(array $files): array
    {
        return array_values(array_filter($files, fn ($f) => self::isPdf($f)));
    }

    public static function url(string $path, string $disk = 'public'): string
    {
        return Storage::disk($disk)->url($path);
    }

    public static function filename(string $path): string
    {
        return basename($path);
    }

    public static function buildSections(?object $lampiran): array
    {
        return collect(self::SECTIONS)
            ->map(function (array $section) use ($lampiran) {
                $raw = $lampiran->{$section['key']} ?? null;
                $files = self::parseFiles($raw);

                return [
                    ...$section,
                    'color' => self::COLOR_MAP[$section['color']],
                    'files' => $files,
                    'count' => count($files),
                    'images' => self::images($files),
                    'pdfs' => self::pdfs($files),
                ];
            })
            ->toArray();
    }
}
