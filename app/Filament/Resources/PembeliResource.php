<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PembeliResource\Pages;
use App\Filament\Resources\PembeliResource\RelationManagers;
use App\Models\Pembeli;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PembeliResource extends Resource
{
    protected static ?int $navigationSort = 1;

    protected static ?string $navigationGroup = 'Master Data';

    protected static ?string $model = Pembeli::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

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
                        ->label('Nama Pembeli')
                        ->required()
                        ->columnSpan([
                            'sm' => 'full',
                            'md' => 1,
                            'lg' => 1,
                        ]),

                    Forms\Components\TextInput::make('nomor_telepon')
                        ->label('No Telp')
                        ->tel()
                        ->nullable()
                        ->columnSpan([
                            'sm' => 'full',
                            'md' => 1,
                            'lg' => 1,
                        ]),

                    Forms\Components\TextInput::make('email')
                        ->label('Email')
                        ->email()
                        ->nullable()
                        ->columnSpan([
                            'sm' => 'full',
                            'md' => 1,
                            'lg' => 1,
                        ]),

                    Forms\Components\Textarea::make('alamat')
                        ->label('Alamat')
                        ->nullable()
                        ->columnSpan([
                            'sm' => 'full',
                            'md' => 3,
                            'lg' => 3,
                        ]),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('no_urut')
                    ->label('No')
                    ->rowIndex(), // <-- nomor urut otomatis
                Tables\Columns\TextColumn::make('nama')
                    ->searchable()
                    ->label('Nama Pembeli')->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('alamat')
                    ->label('Alamat'),
                Tables\Columns\TextColumn::make('nomor_telepon')
                    ->label('No Telp'),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email'),
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
            'index' => Pages\ListPembelis::route('/'),
            'create' => Pages\CreatePembeli::route('/create'),
            'edit' => Pages\EditPembeli::route('/{record}/edit'),
        ];
    }
}