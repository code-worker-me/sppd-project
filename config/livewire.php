<?php

return [
    'temporary_file_upload' => [
        // Disk used to store temporary Livewire uploads before finalizing.
        // Set to 'private' to keep files in storage/app/private/livewire-tmp
        'disk' => env('LIVEWIRE_TMP_DISK', 'private'),

        // Max upload size rules can be defined here (Laravel validation rules),
        // Filament/FileUpload also sets client-side limits.
        'rules' => null,
    ],
];
