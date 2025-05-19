<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LapBarangKeluarResource\Pages;
use App\Filament\Resources\LapBarangKeluarResource\RelationManagers;
use App\Models\LapBarangKeluar;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LapBarangKeluarResource extends Resource
{
    protected static ?int $navigationSort = 3;

    protected static ?string $navigationGroup = 'Laporan';
    
    protected static ?string $model = LapBarangKeluar::class;

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
            'index' => Pages\ListLapBarangKeluars::route('/'),
            'create' => Pages\CreateLapBarangKeluar::route('/create'),
            'edit' => Pages\EditLapBarangKeluar::route('/{record}/edit'),
        ];
    }
}