<?php

namespace App\Filament\Seller\Resources;

use App\Filament\Seller\Resources\TransaksiResource\Pages;
use App\Models\Transaksi; // Sesuaikan model
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\DateTimePicker; // Untuk tanggal_transaksi

class TransaksiResource extends Resource
{
    protected static ?string $model = Transaksi::class;

    protected static ?string $navigationIcon = 'heroicon-o-receipt-percent'; // Ubah ikon jika suka
    protected static ?string $navigationLabel = 'Transaksi'; // Sesuaikan label

    // Filter transaksi yang dimiliki oleh penjual yang sedang login
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('user_id', auth()->id());
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Hidden::make('user_id')
                    ->default(auth()->id())
                    ->required(),
                TextInput::make('total_harga') // Sesuaikan dengan nama kolom di DB
                    ->label('Jumlah Total')
                    ->numeric()
                    ->prefix('Rp')
                    ->required(),
                DateTimePicker::make('tanggal_transaksi') // Sesuaikan dengan nama kolom di DB
                    ->label('Tanggal Transaksi')
                    ->default(now())
                    ->required(),
                Select::make('payment_id') // Sesuaikan dengan nama kolom di DB
                    ->label('ID Pembayaran Terkait')
                    ->relationship('payment', 'payment_id') // Relasi ke model Payment, gunakan payment_id
                    ->nullable(), // Karena di DB juga nullable
                Select::make('status_transaksi') // Sesuaikan dengan nama kolom di DB
                    ->label('Status Transaksi')
                    ->options([
                        'menunggu pembayaran' => 'Menunggu Pembayaran',
                        'dikemas' => 'Dikemas',
                        'dikirim' => 'Dikirim',
                        'diterima' => 'Diterima',
                    ])
                    ->default('menunggu pembayaran')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID Transaksi')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('total_harga') // Sesuaikan dengan nama kolom di DB
                    ->label('Jumlah Total')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('tanggal_transaksi') // Sesuaikan dengan nama kolom di DB
                    ->label('Tanggal Transaksi')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('payment.payment_method') // Mengakses metode pembayaran melalui relasi
                    ->label('Metode Pembayaran')
                    ->sortable(),
                TextColumn::make('status_transaksi') // Sesuaikan dengan nama kolom di DB
                    ->label('Status')
                    ->badge()
                    ->colors([
                        'menunggu pembayaran' => 'warning',
                        'dikemas' => 'info',
                        'dikirim' => 'primary',
                        'diterima' => 'success',
                    ])
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status_transaksi')
                    ->label('Filter Status')
                    ->options([
                        'menunggu pembayaran' => 'Menunggu Pembayaran',
                        'dikemas' => 'Dikemas',
                        'dikirim' => 'Dikirim',
                        'diterima' => 'Diterima',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // Jika Anda ingin mengelola detail transaksi atau pembayaran dari sini,
            // Anda bisa membuat RelationManager di sini.
            // Sebagai contoh: PaymentsRelationManager::class
        ];
    }

    // public static function getPages(): array
    // {
    //     return [
    //         'index' => Pages\ListTransaksis::route('/'),
    //         'create' => Pages\CreateTransaksi::route('/create'),
    //         'edit' => Pages\EditTransaksi::route('/{record}/edit'),
    //     ];
    // }
}