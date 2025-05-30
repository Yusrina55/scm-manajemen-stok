<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReceivingLogResource\Pages;
use App\Filament\Resources\ReceivingLogResource\RelationManagers;
use App\Models\ReceivingLog;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;

class ReceivingLogResource extends Resource
{
    protected static ?int $navigationSort = 1;

    protected static ?string $model = ReceivingLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-arrow-down';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(3)->schema([
                    TextInput::make('quantity')
                        ->label('Qty')
                        ->required()
                        ->numeric()
                        ->minValue(1),
                    TextInput::make('supplier_price')
                        ->label('Harga Supplier')
                        ->numeric()
                        ->minValue(0)
                        ->required()
                        ->helperText('Masukkan harga dalam Rupiah'),
                    DatePicker::make('entry_date')
                        ->label('Tanggal Masuk')
                        ->required()
                        ->placeholder('Pilih tanggal')
                        ->displayFormat('d M Y')
                        ->minDate(now()->subYears(5))
                        ->maxDate(now()->addYears(5))
                ]),
                Grid::make(2)->schema([
                    Select::make('product_id')
                        ->label('Product')
                        ->relationship('product', 'name')
                        ->searchable()
                        ->required(),
                    Select::make('supplier_id')
                        ->label('Supplier')
                        ->relationship('supplier', 'name')
                        ->searchable()
                        ->required(),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable()->label('ID'),
                TextColumn::make('product.name')->searchable()->label('Nama Produk'),
                TextColumn::make('supplier.name')->searchable()->label('Nama Supplier'),
                TextColumn::make('quantity')->sortable()->label('Qty'),
                TextColumn::make('supplier_price')
                    ->label('Harga Supplier')
                    ->money('idr', true)
                    ->sortable(),
                TextColumn::make('entry_date')
                    ->label('Tanggal Masuk')
                    ->date('d M Y'),
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Filter::make('created_today')
                    ->label('Dibuat Hari Ini')
                    ->query(fn($query) => $query->whereDate('created_at', now()->toDateString())),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReceivingLogs::route('/'),
            'create' => Pages\CreateReceivingLog::route('/create'),
            'edit' => Pages\EditReceivingLog::route('/{record}/edit'),
        ];
    }

    public static function getModelLabel(): string
    {
        return 'Log Masuk';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Daftar Log Masuk';
    }

}
