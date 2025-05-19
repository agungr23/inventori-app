<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DataBarangResource\Pages;
use App\Filament\Resources\DataBarangResource\RelationManagers;
use App\Models\DataBarang;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DataBarangResource extends Resource
{

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationGroup = 'Master Data';

    protected static ?string $model = DataBarang::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';

    public static function form(Form $form): Form
    {
        return $form
            ->columns([
                'sm' => 1,   // layar kecil (<640px) 1 kolom full-width
                'md' => 2,   // layar medium (≥768px) 2 kolom
                'lg' => 3,   // layar besar (≥1024px) 3 kolom
            ])
            ->schema([
                Forms\Components\Card::make([
                    Forms\Components\TextInput::make('nama')
                        ->label('Barang')
                        ->required()
                        ->maxLength(255)
                        ->columnSpan([
                            'sm' => 'full',
                            'md' => 1,
                            'lg' => 1,
                        ]),
                    Forms\Components\Select::make('jenis_barang_id')
                        ->label('Jenis')
                        ->relationship('jenisBarang', 'nama')
                        ->required()
                        ->columnSpan([
                            'sm' => 'full',
                            'md' => 1,
                            'lg' => 1,
                        ]),
                    Forms\Components\TextInput::make('stok')
                        ->label('Stok')
                        ->numeric()
                        ->required()
                        ->columnSpan([
                            'sm' => 'full',
                            'md' => 1,
                            'lg' => 1,
                        ]),
                    Forms\Components\Select::make('satuan_id')
                        ->label('Satuan')
                        ->relationship('satuan', 'nama')
                        ->required()
                        ->columnSpan([
                            'sm' => 'full',
                            'md' => 1,
                            'lg' => 1,
                        ]),
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
                Tables\Columns\TextColumn::make('nama')
                    ->label('Barang')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('jenisBarang.nama')
                    ->label('Jenis')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('stok')
                    ->label('Stok')
                    ->sortable()
                    ->color(fn($state) => $state <= 30000 ? 'danger' : null)
                    ->formatStateUsing(fn($state) => $state <= 30000 ? "$state ⚠️" : $state)
                    ->tooltip(fn($state) => $state <= 30000 ? 'Stok rendah! Harap lakukan pengisian.' : null), // warna merah jika stok ≤ 30000
                Tables\Columns\TextColumn::make('satuan.nama')
                    ->label('Satuan')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(), // filter data yang di soft delete
            ])
            ->recordUrl(null)
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(fn($record) => is_null($record->deleted_at)), // hanya tampil kalau bukan soft deleted
                Tables\Actions\DeleteAction::make(), // Soft delete
                Tables\Actions\RestoreAction::make(), // Restore data soft deleted
                Tables\Actions\ForceDeleteAction::make(), // Hapus permanen
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
            'index' => Pages\ListDataBarangs::route('/'),
            'create' => Pages\CreateDataBarang::route('/create'),
            'edit' => Pages\EditDataBarang::route('/{record}/edit'),
        ];
    }
}