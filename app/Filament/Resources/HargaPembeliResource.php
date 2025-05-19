<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HargaPembeliResource\Pages;
use App\Filament\Resources\HargaPembeliResource\RelationManagers;
use App\Models\HargaPembeli;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Support\RawJs;

class HargaPembeliResource extends Resource
{
    protected static ?int $navigationSort = 1;

    protected static ?string $navigationGroup = 'Master Data';

    protected static ?string $model = HargaPembeli::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make([
                    Forms\Components\Select::make('barang_id')
                        ->label('Barang')
                        ->options(function () {
                            return \App\Models\DataBarang::with('jenisBarang')->get()->mapWithKeys(function ($barang) {
                                return [
                                    $barang->id => $barang->nama . ' — ' . $barang->jenisBarang->nama,
                                ];
                            })->toArray();
                        })
                        ->required(),
                    Forms\Components\TextInput::make('harga_jual')
                        ->label('Harga Jual')
                        ->mask(RawJs::make('$money($input)'))
                        ->stripCharacters(',')
                        ->numeric()
                        ->required(),
                    Forms\Components\Select::make('pembeli_id')
                        ->label('Pembeli')
                        ->relationship('pembeli', 'nama')
                        ->required(),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('no')
                    ->label('No')
                    ->rowIndex(),
                Tables\Columns\TextColumn::make('barang.nama')
                    ->label('Barang')
                    ->formatStateUsing(fn($state, $record) => $record->barang->nama . ' — ' . $record->barang->jenisBarang->nama)
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('harga_jual')
                    ->label('Harga Jual')
                    ->formatStateUsing(fn($state) => 'Rp ' . number_format($state, 0, ',', '.'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('pembeli.nama')
                    ->label('Pembeli')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->recordUrl(null)
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
            'index' => Pages\ListHargaPembelis::route('/'),
            'create' => Pages\CreateHargaPembeli::route('/create'),
            'edit' => Pages\EditHargaPembeli::route('/{record}/edit'),
        ];
    }
}