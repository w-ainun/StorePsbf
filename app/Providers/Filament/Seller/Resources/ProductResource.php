<?php

namespace App\Filament\Seller\Resources;

use App\Filament\Seller\Resources\ProductResource\Pages;
use App\Models\Product; // Sesuaikan model
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\FileUpload; // Untuk gambar

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube'; // Ubah ikon jika suka
    protected static ?string $navigationLabel = 'Produk'; // Ubah label jika ingin lebih umum

    // Menggunakan metode ini untuk memfilter produk berdasarkan user yang sedang login
    public static function getEloquentQuery(): Builder
    {
        // Asumsi kolom 'user_id' ada di tabel 'products' untuk mengidentifikasi penjual.
        // Jika tidak ada, Anda perlu menambahkannya atau menyesuaikan filter.
        return parent::getEloquentQuery()->whereHas('user', function (Builder $query) {
            $query->where('id', auth()->id());
        });
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Karena kita menggunakan model Product yang tidak langsung memiliki user_id
                // Tapi user_id seharusnya ada untuk relasi ke penjual.
                // Jika ingin produk hanya bisa ditambahkan oleh penjual,
                // tambahkan Hidden::make('user_id') atau set di observer/event listener
                // Hidden::make('user_id')
                //    ->default(auth()->id())
                //    ->required(),
                TextInput::make('nama_barang') // Sesuaikan dengan nama kolom di DB
                    ->label('Nama Produk')
                    ->required()
                    ->maxLength(255),
                Textarea::make('deskripsi')
                    ->label('Deskripsi')
                    ->nullable()
                    ->columnSpanFull(),
                TextInput::make('harga') // Sesuaikan dengan nama kolom di DB
                    ->label('Harga')
                    ->numeric()
                    ->prefix('Rp')
                    ->required(),
                TextInput::make('stok') // Sesuaikan dengan nama kolom di DB
                    ->label('Stok')
                    ->numeric()
                    ->required(),
                Select::make('kategori') // Sesuaikan dengan nama kolom di DB
                    ->label('Kategori')
                    ->options([
                        'hp' => 'HP',
                        'laptop' => 'Laptop',
                        'printer' => 'Printer',
                        'kamera' => 'Kamera',
                    ])
                    ->default('hp') // Set default jika perlu
                    ->required(),
                FileUpload::make('gambar') // Sesuaikan jika Anda mengelola gambar
                    ->label('Gambar Produk')
                    ->nullable()
                    ->image()
                    ->disk('public') // Pastikan disk 'public' dikonfigurasi dengan benar
                    ->directory('product-images'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_barang')
                    ->label('Nama Produk')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('kategori')
                    ->label('Kategori')
                    ->badge()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('harga')
                    ->label('Harga')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('stok')
                    ->label('Stok')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Ditambahkan Pada')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('kategori')
                    ->label('Filter Kategori')
                    ->options([
                        'hp' => 'HP',
                        'laptop' => 'Laptop',
                        'printer' => 'Printer',
                        'kamera' => 'Kamera',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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

    // public static function getPages(): array
    // {
    //     return [
    //         'index' => Pages\ListProducts::route('/'),
    //         'create' => Pages\CreateProduct::route('/create'),
    //         'edit' => Pages\EditProduct::route('/{record}/edit'),
    //     ];
    // }
}