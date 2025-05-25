<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BarangKeluarResource\Pages;
use App\Models\BarangKeluar;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Carbon\Carbon;

class BarangKeluarResource extends Resource
{
    protected static ?string $model = BarangKeluar::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    protected static ?string $navigationGroup = 'Transaksi';

    protected static ?int $navigationSort = 2;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScope(SoftDeletingScope::class);
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Card::make([
                Forms\Components\TextInput::make('id_transaksi')
                    ->label('ID Transaksi')
                    ->disabled()
                    ->default(fn() => self::generateIdTransaksi()),

                Forms\Components\TextInput::make('no_po')
                    ->label('No PO')
                    ->disabled()
                    ->default(fn() => self::generateNoPO()),

                Forms\Components\DatePicker::make('tanggal_po')
                    ->label('Tanggal PO')
                    ->required(),

                Forms\Components\DatePicker::make('tanggal_kirim')
                    ->label('Tanggal Kirim')
                    ->required(),

                Forms\Components\Select::make('barang_id')
                    ->label('Barang')
                    ->relationship('barang', 'nama')
                    ->required()
                    ->reactive()
                    ->options(function () {
                        return \App\Models\DataBarang::with('jenisBarang')->get()->mapWithKeys(function ($barang) {
                            return [
                                $barang->id => $barang->nama . ' — ' . $barang->jenisBarang->nama,
                            ];
                        })->toArray();
                    })
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        $barangId = $state;
                        $pembeliId = $get('pembeli_id');
                        $jumlah = $get('jumlah') ?? 0;

                        if ($barangId && $pembeliId) {
                            $hargaPembeli = \App\Models\HargaPembeli::where('barang_id', $barangId)
                                ->where('pembeli_id', $pembeliId)
                                ->first();

                            $hargaSatuan = $hargaPembeli ? $hargaPembeli->harga_jual : 0;
                            $set('harga_satuan', $hargaSatuan);
                            $set('harga_total', $hargaSatuan * $jumlah);
                        } else {
                            $set('harga_satuan', 0);
                            $set('harga_total', 0);
                        }
                    }),

                Forms\Components\Select::make('pembeli_id')
                    ->label('Pembeli')
                    ->relationship('pembeli', 'nama')
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        $pembeliId = $state;
                        $barangId = $get('barang_id');
                        $jumlah = $get('jumlah') ?? 0;

                        if ($barangId && $pembeliId) {
                            $hargaPembeli = \App\Models\HargaPembeli::where('barang_id', $barangId)
                                ->where('pembeli_id', $pembeliId)
                                ->first();

                            $hargaSatuan = $hargaPembeli ? $hargaPembeli->harga_jual : 0;
                            $set('harga_satuan', $hargaSatuan);
                            $set('harga_total', $hargaSatuan * $jumlah);
                        } else {
                            $set('harga_satuan', 0);
                            $set('harga_total', 0);
                        }
                    }),

                Forms\Components\TextInput::make('jumlah')
                    ->label('Jumlah')
                    ->numeric()
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        $hargaSatuan = $get('harga_satuan') ?? 0;
                        $set('harga_total', $hargaSatuan * $state);
                    }),

                Forms\Components\TextInput::make('harga_satuan')
                    ->label('Harga Satuan')
                    ->required()
                    ->reactive()
                    ->disabled()
                    ->dehydrated(),

                Forms\Components\TextInput::make('harga_total')
                    ->label('Harga Total')
                    ->disabled()
                    ->dehydrated()
                    ->numeric(),
            ]),
        ]);
    }



    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('no')
                ->label('No')
                ->rowIndex(),
            Tables\Columns\TextColumn::make('id_transaksi')
                ->label('ID Transaksi')
                ->sortable(),
            Tables\Columns\TextColumn::make('no_po')
                ->label('No PO')
                ->sortable(),
            Tables\Columns\TextColumn::make('tanggal_po')
                ->label('Tanggal PO')
                ->date()
                ->formatStateUsing(fn($state) => self::formatTanggalIndonesia($state))
                ->sortable(),
            Tables\Columns\TextColumn::make('tanggal_kirim')
                ->label('Tanggal Kirim')
                ->date()
                ->formatStateUsing(fn($state) => self::formatTanggalIndonesia($state))
                ->sortable(),
            Tables\Columns\TextColumn::make('barang.nama')
                ->label('Barang')
                ->sortable()
                ->searchable(),
            Tables\Columns\TextColumn::make('barang.jenisBarang.nama')
                ->label('Jenis')
                ->sortable(),
            Tables\Columns\TextColumn::make('pembeli.nama')
                ->label('Pembeli')
                ->sortable(),
            Tables\Columns\TextColumn::make('harga_total')
                ->label('Harga Total')
                ->formatStateUsing(fn($state) => 'Rp ' . number_format($state, 0, ',', '.'))
                ->sortable(),
            Tables\Columns\TextColumn::make('jumlah')->label('Jumlah')
                ->sortable(),
            Tables\Columns\TextColumn::make('barang.satuan.nama')
                ->label('Satuan')
                ->sortable(),
        ])->actions([
            Tables\Actions\Action::make('cetakInvoice')
                ->label('Cetak Invoice')
                ->icon('heroicon-o-printer')
                ->url(fn($record) => route('barang-keluar.invoice', $record->id))
                ->openUrlInNewTab(),
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
        ])->bulkActions([
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
            12 => 'Desember',
        ];

        $dt = Carbon::parse($tanggal);
        $tgl = $dt->day;
        $bln = $bulan[$dt->month] ?? '';
        $thn = $dt->year;

        return "$tgl $bln $thn";
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBarangKeluars::route('/'),
            'create' => Pages\CreateBarangKeluar::route('/create'),
            'edit' => Pages\EditBarangKeluar::route('/{record}/edit'),
        ];
    }

    public static function generateIdTransaksi(): string
    {
        $last = \App\Models\BarangKeluar::latest('id')->first();
        $number = $last ? ((int) substr($last->id_transaksi, 3)) + 1 : 1;
        return 'TK-' . str_pad($number, 7, '0', STR_PAD_LEFT);
    }

    public static function generateNoPO(): string
    {
        $last = \App\Models\BarangKeluar::latest('id')->first();
        $number = $last ? $last->id + 1 : 1;
        return 'PO-' . str_pad($number, 7, '0', STR_PAD_LEFT);
    }
}
