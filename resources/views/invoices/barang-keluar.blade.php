<!DOCTYPE html>
<html>

<head>
    <title>Invoice {{ $barangKeluar->id_transaksi }}</title>
    <style>
    /* Tambahkan styling sesuai kebutuhan, agar mirip invoice */
    body {
        font-family: Arial, sans-serif;
        font-size: 12px;
    }

    table {
        border-collapse: collapse;
        width: 100%;
    }

    th,
    td {
        border: 1px solid black;
        padding: 6px;
    }

    .header {
        background-color: #0055a5;
        color: white;
        padding: 10px;
    }

    .right {
        text-align: right;
    }

    .center {
        text-align: center;
    }

    /* Tambah style lain seperti pada invoice */
    </style>
</head>

<body>

    <div class="header">
        <table style="border: 0;">
            <tr>
                <td style="border: 0;">
                    <h2>MandalaArtaSinergi</h2>
                </td>
                <td class="right" style="border: 0;">
                    <p>Jl. Almuhajirin raya ruko no.1 <br>
                        kampung bendungan tambun, <br>
                        bekasi, jawa barat
                    </p>
                </td>
            </tr>
        </table>


    </div>

    <h3>INVOICE</h3>

    <table>
        <tr>
            <td>Nama Perusahaan</td>
            <td>: {{ $barangKeluar->pembeli->nama }}</td>
            <td>Invoice No.</td>
            <td>: {{ $barangKeluar->id_transaksi }}</td>
        </tr>
        <tr>
            <td rowspan="3">Alamat</td>
            <td rowspan="3">: {{ $barangKeluar->pembeli->alamat ?? '-' }}</td>
            <td>Tanggal</td>
            <td>: {{ \Carbon\Carbon::parse($barangKeluar->tanggal_po)->format('d M Y') }}</td>
        </tr>
        <tr>
            <td>No PO</td>
            <td>: {{ $barangKeluar->no_po }}</td>
        </tr>
        <tr>
            <td>Tanggal PO</td>
            <td>: {{ \Carbon\Carbon::parse($barangKeluar->tanggal_po)->format('d M Y') }}</td>
        </tr>
    </table>

    <br>

    <table>
        <thead>
            <tr>
                <th>Alamat Pengiriman Barang</th>
                <th>Tanggal Pengiriman Barang</th>
                <th>Cara Pembayaran</th>
                <th>Bayar ke Nomor Rekening</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="center">Jasa Marga Tollroad ...</td>
                <td class="center">{{ \Carbon\Carbon::parse($barangKeluar->tanggal_kirim)->format('d M Y') }}</td>
                <td class="center">Transfer</td>
                <td class="center">PT. MANDALA ARTA SINERGI<br>Bank BCA<br>KCP Arundina Cibubur<br>No. Rekening
                    6282451740</td>
            </tr>
        </tbody>
    </table>

    <br>

    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Nama Barang</th>
                <th>Jumlah</th>
                <th>Harga Per Liter</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>{{ $barangKeluar->barang->nama }} ({{ $barangKeluar->barang->jenisBarang->nama }})</td>
                <td>{{ number_format($barangKeluar->jumlah) }} Ltr</td>
                <td>Rp. {{ number_format($hargaSatuan, 0, ',', '.') }}</td>
                <td>Rp. {{ number_format($barangKeluar->harga_total, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <br>

    <table>
        <tr>
            <td>Sub Total</td>
            <td>Rp {{ number_format($barangKeluar->harga_total, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>PPn 11%</td>
            <td>Rp {{ number_format($barangKeluar->harga_total * 0.11, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <th>TOTAL</th>
            <th>Rp {{ number_format($barangKeluar->harga_total * 1.11, 0, ',', '.') }}</th>
        </tr>
    </table>

    <br>
    <p><em>Terbilang : #
            {{ ucwords(\NumberFormatter::create('id_ID', \NumberFormatter::SPELLOUT)->format($barangKeluar->harga_total * 0.11)) }}
            Rupiah #</em></p>

    <br>

    <table>
        <tr>
            <td class="center" style="border: 0; width:40%;">
                <p>Mohon dibayarkan dengan jumlah penuh <br>
                    Pembeli wajib membayar seluruh biaya yang <br>
                    dikenakan oleh pihak Bank
                </p>
            </td>
            <td style="border: 0;"></td>
        </tr>
    </table>


    <hr>
    <table style="width:100%;">
        <tr>
            <td style="font-size: 9px; font-style: italic;">
                Payment should be made by crossed cheque paybank<br>
                PT. MANDALA ARTA SINERGI Any Others from pay<br>
                Sole Responsibility of payers.<br>
                Payment are only valid after clearance from our Bank
            </td>
            <td style="font-size: 9px; font-style: italic;">
                Pembayaran dilakukan dengan cek silang atas nama<br>
                PT. MANDALA ARTA SINERGI. Pembayaran dengan cara lain<br>
                menjadi tanggung jawab pembeli.<br>
                Pembayaran dinyatakan sah/lunas setelah kliring pada bank kami
            </td>
        </tr>
    </table>

</body>

</html>