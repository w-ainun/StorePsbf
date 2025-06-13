{{-- resources/views/filament/pages/custom-login-page.blade.php --}}

{{-- Menggunakan komponen card khusus untuk autentikasi dari Filament Panels --}}
<x-filament-panels::auth.card>
    {{-- Ini akan me-render form login yang sudah berfungsi dari Filament --}}
    @livewire(\Filament\Pages\Auth\Login::class)
</x-filament-panels::auth.card>