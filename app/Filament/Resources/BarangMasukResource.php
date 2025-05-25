<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BarangMasukResource\Pages;
use App\Filament\Resources\BarangMasukResource\RelationManagers;
use App\Models\BarangMasuk;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Carbon\Carbon;

class BarangMasukResource extends Resource
{
    protected static ?int $navigationSort = 2;

    protected static ?string $navigationGroup = 'Transaksi';

    protected static ?string $model = BarangMasuk::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScope(SoftDeletingScope::class);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make([
                    Forms\Components\TextInput::make('id_transaksi')
                        ->label('ID Transaksi')
                        ->disabled()
                        ->default(fn() => self::generateIdTransaksi()),
                    Forms\Components\DatePicker::make('tanggal_masuk')
                        ->label('Tanggal Masuk')
                        ->required()
                        ->displayFormat('d/m/Y')
                        ->format('Y-m-d'),
                    Forms\Components\Select::make('barang_id')
                        ->label('Barang')
                        ->relationship('barang', 'nama')
                        ->reactive()
                        ->required()
                        ->options(function () {
                            return \App\Models\DataBarang::with('jenisBarang')->get()->mapWithKeys(function ($barang) {
                                return [
                                    $barang->id => $barang->nama . ' — ' . $barang->jenisBarang->nama,
                                ];
                            })->toArray();
                        })
                        ->afterStateUpdated(fn(callable $set, $state) => self::setRelatedFields($set, $state)),
                    Forms\Components\TextInput::make('jenis_barang')
                        ->label('Jenis Barang')
                        ->disabled()
                        ->dehydrated(false),
                    Forms\Components\TextInput::make('jumlah')
                        ->label('Jumlah')
                        ->numeric()
                        ->required(),
                    Forms\Components\TextInput::make('satuan')
                        ->label('Satuan')
                        ->disabled()
                        ->dehydrated(false),
                ]),
            ]);
    }

    protected static function setRelatedFields(callable $set, $barangId): void
    {
        $barang = \App\Models\DataBarang::with('jenisBarang', 'satuan')->find($barangId);
        if ($barang) {
            $set('jenis_barang', $barang->jenisBarang->nama ?? '');
            $set('satuan', $barang->satuan->nama ?? '');
        } else {
            $set('jenis_barang', '');
            $set('satuan', '');
        }
    }

    protected static function generateIdTransaksi(): string
    {
        $last = \App\Models\BarangMasuk::latest('id')->first();
        $number = $last ? ((int) substr($last->id_transaksi, 3)) + 1 : 1;
        return 'TM-' . str_pad($number, 7, '0', STR_PAD_LEFT);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('no')
                    ->label('No')
                    ->rowIndex(),
                Tables\Columns\TextColumn::make('id_transaksi')
                    ->label('ID Transaksi')
                    ->sortable(),
                Tables\Columns\TextColumn::make('tanggal_masuk')
                    ->label('Tanggal Masuk')
                    ->date()
                    ->formatStateUsing(fn($state) => self::formatTanggalIndonesia($state))
                    ->sortable(),
                Tables\Columns\TextColumn::make('barang.nama')
                    ->label('Barang')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('barang.jenisBarang.nama')
                    ->label('Jenis Barang')
                    ->sortable(),
                Tables\Columns\TextColumn::make('jumlah')
                    ->label('Jumlah')
                    ->sortable(),
                Tables\Columns\TextColumn::make('barang.satuan.nama')
                    ->label('Satuan')
                    ->sortable(),
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

    public static function formatTanggalIndonesia($tanggal)
    {
        if (!$tanggal) {
            return null;
        }

        $bulan = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];

        $dt = Carbon::parse($tanggal);
        $tgl = $dt->day;
        $bln = $bulan[$dt->month] ?? '';
        $thn = $dt->year;

        return "$tgl $bln $thn";
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
            'index' => Pages\ListBarangMasuks::route('/'),
            'create' => Pages\CreateBarangMasuk::route('/create'),
            'edit' => Pages\EditBarangMasuk::route('/{record}/edit'),
        ];
    }
}
