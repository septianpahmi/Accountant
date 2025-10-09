<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Invoice #{{ $record->invoice_number ?? 'INV-0001' }}</title>
    <link rel="stylesheet" href="/assets/css/invoice.css">
    <style>
        .signature {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
        }

        .signature div {
            width: 45%;
            text-align: center;
        }

        .signature-line {
            margin-top: 60px;
            border-top: 1px solid #666;
            display: inline-block;
            width: 200px;
        }
    </style>
</head>

<body>
    <div id="invoiceholder">

        <div id="headerimage"></div>
        <div id="invoice" class="effect2">

            <div id="invoice-top">
                <div class="logo">
                    <img src="/assets/img/logo1.png" alt="">
                </div>
                <div class="info">
                    <h1>PT. Sinar Terang Abadi</h1>
                    <p>Jl. Raya Cianjur No. 88, Cianjur, Jawa Barat</p>
                    <p>Email: info@sinarterangabadi.com | Telp: (0263) 555-1234</p>
                </div><!--End Info-->

            </div><!--End InvoiceTop-->

            <div id="invoice-mid">

                <div class="clientlogo">
                    <img src="/assets/img/client.jpg" alt="">
                </div>
                <div class="infouser">
                    <h2>{{ $record->customer->name ?? 'Nama Pelanggan' }}</h2>
                    <p>{{ $record->customer->email ?? '' }}</br>
                        {{ $record->customer->phone ?? '' }}</br>
                        {{ $record->customer->address ?? '' }}</p>
                </div>
                <div class="project"></div>
                <div class="title">
                    <h1>Invoice # {{ $record->invoice_number ?? 'INV-0001' }}</h1>
                    <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($record->date)->format('d M Y') }}</p>
                    <p><strong>Status:</strong> {{ ucfirst($record->status ?? 'Draft') }}</p>
                </div><!--End Title-->

            </div><!--End Invoice Mid-->

            <div id="invoice-bot">

                <div id="table">
                    <table>
                        <tr class="tabletitle">
                            <td class="item">
                                <h2>Keterangan</h2>
                            </td>
                            <td class="Hours">
                                <h2>Harga</h2>
                            </td>
                            <td class="Rate">
                                <h2>Qty</h2>
                            </td>
                            <td class="subtotal">
                                <h2>Sub Total</h2>
                            </td>
                        </tr>

                        <tr class="service">
                            <td class="tableitem">
                                <p class="itemtext">{{ $record->ket ?? 'Tidak ada keterangan' }}</p>
                            </td>
                            <td class="tableitem">
                                <p class="itemtext">Rp {{ number_format($record->price, 0, ',', '.') }}</p>
                            </td>
                            <td class="tableitem">
                                <p class="itemtext">{{ $record->qty }}</p>
                            </td>
                            <td class="tableitem">
                                <p class="itemtext">Rp {{ number_format($record->total, 0, ',', '.') }}</p>
                            </td>
                        </tr>

                        <tr class="tabletitle">
                            <td></td>
                            <td></td>
                            <td class="Rate">
                                <h2>Total</h2>
                            </td>
                            <td class="payment">
                                <h2>Rp {{ number_format($record->total, 0, ',', '.') }}</h2>
                            </td>
                        </tr>

                    </table>
                </div><!--End Table-->
                <div class="signature">
                    <div>
                        <p>Penerima,</p>
                        <div class="signature-line"></div>
                    </div>
                    <div>
                        <p>Hormat Kami,</p>
                        <div class="signature-line"></div>
                    </div>
                </div>

                <div id="legalcopy">
                    <p class="legal"><strong>Terima kasih telah melakukan transaksi dengan kami.</strong></p>
                    <p class="legal">Invoice ini dibuat secara otomatis dan sah tanpa tanda tangan basah.</p>
                </div>

            </div><!--End InvoiceBot-->
        </div><!--End Invoice-->
    </div><!-- End Invoice Holder-->
    <script>
        window.addEventListener("load", function() {
            window.print();
        });
    </script>
</body>

</html>
