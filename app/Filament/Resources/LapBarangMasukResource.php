<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LapBarangMasukResource\Pages;
use App\Filament\Resources\LapBarangMasukResource\RelationManagers;
use App\Models\LapBarangMasuk;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LapBarangMasukResource extends Resource
{
    protected static ?int $navigationSort = 3;

    protected static ?string $navigationGroup = 'Laporan';
    
    protected static ?string $model = LapBarangMasuk::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLapBarangMasuks::route('/'),
            'create' => Pages\CreateLapBarangMasuk::route('/create'),
            'edit' => Pages\EditLapBarangMasuk::route('/{record}/edit'),
        ];
    }
}