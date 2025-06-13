<?php

namespace App\Filament\Seller\Resources;

use App\Filament\Seller\Resources\PaymentResource\Pages;
use App\Models\Payment; // Sesuaikan model
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card'; // Ubah ikon jika suka
    protected static ?string $navigationLabel = 'Pembayaran'; // Sesuaikan label

    // Filter pembayaran yang terkait dengan transaksi penjual yang sedang login
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereHas('transaksi', function (Builder $query) {
                $query->where('user_id', auth()->id());
            });
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // ID Transaksi
                Select::make('transaksi_id') // Sesuaikan dengan nama kolom di DB
                    ->label('ID Transaksi')
                    ->relationship('transaksi', 'id', function (Builder $query) { // Relasi ke model Transaksi
                        // Hanya tampilkan transaksi milik penjual yang sedang login
                        $query->where('user_id', auth()->id());
                    })
                    ->required(),
                TextInput::make('amount') // Jika Anda punya kolom amount di payments, jika tidak, hapus
                    ->label('Jumlah Pembayaran')
                    ->numeric()
                    ->prefix('Rp')
                    ->nullable(), // Nullable karena skema tidak punya amount
                Select::make('payment_method') // Sesuaikan dengan nama kolom di DB
                    ->label('Metode Pembayaran')
                    ->options([
                        'Transfer Bank' => 'Transfer Bank',
                        'Kartu Kredit' => 'Kartu Kredit',
                        'E-wallet' => 'E-wallet',
                        'COD' => 'COD',
                    ])
                    ->required(),
                Select::make('status_payment') // Sesuaikan dengan nama kolom di DB
                    ->label('Status Pembayaran')
                    ->options([
                        'pending' => 'Pending',
                        'paid' => 'Paid',
                    ])
                    ->default('pending')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('transaksi.id')
                    ->label('ID Transaksi')
                    ->searchable()
                    ->sortable(),
                // TextColumn::make('amount') // Hapus jika tidak ada kolom amount di payments
                //    ->label('Jumlah')
                //    ->money('IDR')
                //    ->sortable(),
                TextColumn::make('payment_method') // Sesuaikan dengan nama kolom di DB
                    ->label('Metode')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status_payment') // Sesuaikan dengan nama kolom di DB
                    ->label('Status')
                    ->badge()
                    ->colors([
                        'pending' => 'warning',
                        'paid' => 'success',
                    ])
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Tanggal Pembayaran')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status_payment')
                    ->label('Filter Status')
                    ->options([
                        'pending' => 'Pending',
                        'paid' => 'Paid',
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
            //
        ];
    }

//     public static function getPages(): array
//     {
//         return [
//             'index' => Pages\ListPayments::route('/'),
//             'create' => Pages\CreatePayment::route('/create'),
//             'edit' => Pages\EditPayment::route('/{record}/edit'),
//         ];
//     }
}