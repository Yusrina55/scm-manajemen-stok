<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Filament\Resources\TransactionResource\RelationManagers;
use App\Models\Product;
use App\Models\Transaction;
use Filament\Tables\Actions\ActionGroup;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Number;

class TransactionResource extends Resource
{
    protected static ?int $navigationSort = 4;
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()->schema([
                    Section::make('Informasi Transaksi')->schema([
                        Repeater::make('transactionDetails')
                            ->label('Detail Transaksi')
                            ->relationship()
                            ->schema([
                                Select::make('product_id')
                                    ->label('Nama Produk')
                                    ->relationship('product', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->distinct()
                                    ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                    ->reactive() //change in this field will trigger change in another field (client-side)
                                    ->afterStateUpdated(fn($state, \Filament\Forms\Set $set) => $set('price', Product::find($state)?->price ?? 0))
                                    ->afterStateUpdated(fn($state, \Filament\Forms\Set $set) => $set('sub_total', Product::find($state)?->price ?? 0))
                                    ->columnSpan(4),
                                TextInput::make('quantity')
                                    ->label('Qty')
                                    ->numeric()
                                    ->required()
                                    ->default(1)
                                    ->minValue(1)
                                    ->reactive()
                                    ->afterStateUpdated(fn($state, \Filament\Forms\Set $set, \Filament\Forms\Get $get) => $set('sub_total', $state * $get('price')))
                                    ->columnSpan(2),
                                TextInput::make('price')
                                    ->label('Harga')
                                    ->numeric()
                                    ->required()
                                    ->dehydrated()
                                    ->readOnly()
                                    ->columnSpan(3),
                                TextInput::make('sub_total')
                                    ->label('Sub-Total')
                                    ->numeric()
                                    ->required()
                                    ->dehydrated()
                                    ->columnSpan(3),
                            ])
                            ->columns(12),
                        Fieldset::make('placeholder')
                            ->label('Ringkasan Transaksi')
                            ->schema([
                                Grid::make(2)->schema([
                                    Placeholder::make('total_placeholder')
                                        ->label('Total Harga')
                                        ->content(function (\Filament\Forms\Get $get, \Filament\Forms\Set $set) {
                                            $total = 0;
                                            if (!$repeaters = $get('transactionDetails')) {
                                                return $total;
                                            }
                                            foreach ($repeaters as $key => $repeater) {
                                                $total += $get("transactionDetails.{$key}.sub_total");
                                            }
                                            $set('total', $total);
                                            return Number::currency($total, 'IDR');
                                        }),
                                    DatePicker::make('transaction_date')
                                        ->label('Tanggal Transaksi')
                                        ->required()
                                        ->placeholder('Pilih tanggal')
                                        ->displayFormat('d M Y')
                                        ->minDate(now()->subYears(5))
                                        ->maxDate(now()->addYears(5))
                                ])
                            ]),
                        Hidden::make('total')
                            ->default(0),
                        Hidden::make('quantity')
                            ->default(0)
                    ])
                ])->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('total')
                    ->label('Total Harga')
                    ->money('idr', true)
                    ->sortable(),
                TextColumn::make('transaction_date')
                    ->label('Tanggal Transaksi')
                    ->dateTime('d M Y')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Waktu Dicatat')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Filter::make('created_today')
                    ->label('Dibuat Hari Ini')
                    ->query(fn($query) => $query->whereDate('created_at', now()->toDateString())),
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])
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
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }
    public static function getModelLabel(): string
    {
        return 'Transaksi';
    }

}
