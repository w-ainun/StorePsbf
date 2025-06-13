<?php

namespace App\Filament\Pages;

use Filament\Pages\Auth\Login as BaseLoginPage; // Import kelas Login dasar Filament

class CustomLoginPage extends BaseLoginPage
{
    // Ini akan menjadi halaman login universal.
    // Kita tidak perlu navigationIcon atau shouldRegisterNavigation karena akan diakses via rute web.php
    protected static ?string $navigationIcon = 'heroicon-o-arrow-right-on-rectangle';
    protected static bool $shouldRegisterNavigation = false;

    // Atur view yang akan digunakan. Filament secara default akan mencari di
    // resources/views/filament/pages/custom-login-page.blade.php
    // Anda bisa salin konten dari vendor/filament/filament/resources/views/pages/auth/login.blade.php
    // ke view ini jika ingin tampilan default.
    protected static string $view = 'filament.pages.custom-login-page';

    // Anda bisa meng-override method getCredentials() jika diperlukan kustomisasi
    // otentikasi lebih lanjut, tapi untuk role-based redirect, ini tidak selalu perlu.
}