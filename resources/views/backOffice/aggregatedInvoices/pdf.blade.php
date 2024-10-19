<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Facture {{$invoice->client}}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }
        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 10px;
            border: 1px solid #eee;
        }
        .logo {
            text-align: center;
            margin-bottom: 20px;
        }
        .company-details {
            text-align: center;
            margin-bottom: 20px;
        }
        .info-table, .items-table, .totals-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px
        }
        .info-table th, .info-table td,  .totals-table th, .totals-table td {
            border: none;
            /*border: 1px solid #ddd;*/
            padding: 5px;
        }
        .items-table th, .items-table td {
            border-right: 1px solid #818181;
            border-left: 1px solid #818181;
            padding: 5px;
        }
        .info-table th {
            /*background-color: #d1d1d1;*/
            text-align: left;
        }
        .info-table td {
            text-align: left;
        }
        .items-table th {
            background-color: #d1d1d1;
            text-align: left;
        }
        .totals-table {
            margin-top: -17px;
        }
        .totals-table th {
            border: none;
            /*background-color: #d1d1d1;*/
            text-align: right;
        }
        .totals-table td {
            border: none;
            text-align: center;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 10px;
        }
    </style>
</head>
<body>
<div class="invoice-box">
    <div class="logo">
        <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('/images/logo_pdf.png'))) }}" width="300px" alt="AutoBody Logo">
    </div>
    <?php $totalHT = 0;$totalTTC = 0; ?>
    <table class="info-table">
        <tr>
            <th>Facture N°</th>
            <td>{{$invoice->number}}</td>
            <th>Date</th>
            <td>
                @php
                    $date = substr($invoice->created_at, 0, 10);
                @endphp
                {{$date}}
            </td>
        </tr>
        <tr>
            <th></th>
            <td></td>
            <th>Client</th>
            <td>{{$invoice->client}}</td>

        </tr>
        <!-- Additional rows as needed -->
    </table>

    <table class="items-table">
        <tr>
            <th>Nature</th>
            <th>Description</th>
            <th>QTE</th>
            <th>P.U</th>
            <th>Total (HT)</th>
            <th>TVA</th>
            <th>Montant (TTC)</th>
        </tr>
        @foreach($invoice->invoiceLines as $line)
            @if($line->type != 'MOD')
                <tr>
                    <td><b>{{$line->reference ?? 'Occasion'}}</b></td>
                    <td><b>{{$line->description}}</b></td>
                    <td><b>{{$line->quantity}}</b></td>
                    <td><b>{{number_format($line->price, 2)}}</b></td>
                    <td><b>{{number_format($line->price * $line->quantity, 2)}}</b></td>
                    <td><b>{{$line->TVA ? $line->TVA . '%' : '' }}</b></td>
                    <td><b>{{$line->TVA ?  number_format($line->price * $line->quantity * (1 + ($line->TVA/100)), 2) : '' }}</b></td>
                </tr>
                    <?php
                    $totalHT += $line->price * $line->quantity;
                    $totalTTC += $line->price * $line->quantity * (1 + ($line->TVA/100));
                    ?>
            @else
                <?php
                    $MOD_exist = 'true';
                ?>
            @endif
        @endforeach
        <tr style="margin-top: 40px !important;">
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr style="margin-top: 40px !important;">
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        @if(isset($MOD_exist))
        <tr style="margin-top: 40px !important;">
            <td></td>
            <td><u>{{strtoupper($invoice->title)}}  :</u></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        @endif
        @foreach($invoice->invoiceLines as $line)
            @if($line->type == 'MOD')
                    <?php
                    $price_ttc = $line->price * (1 + ($line->TVA/100));
                    ?>
                <tr>
                    <td><b>{{$line->state}}</b></td>
                    <td><b>{{$line->description}}</b></td>
                    <td><b>{{$line->quantity}}</b></td>
                    <td><b></b></td>
                    <td><b>{{number_format($line->price, 2)}}</b></td>
                    <td><b>{{$line->TVA}}%</b></td>
                    <td><b>{{ number_format($price_ttc, 2)}}</b></td>
                </tr>
                    <?php
                    $totalHT += $line->price ;
                    $totalTTC += $line->price *  (1 + ($line->TVA/100));
                    ?>
            @endif
        @endforeach
    </table>

    <div class="totals" style="display: flex; justify-content: end">
        <table class="totals-table" style="width: 100% !important;" >
            <tr>
                <th style="width: 80% !important;">Total HT :</th>
                <td><b>{{number_format($totalHT, 2)}} DH</b></td>
            </tr>
            <tr>
                <th>Total TTC :</th>
                <td><b>{{number_format($totalTTC, 2)}} DH</b></td>
            </tr>
        </table>
    </div>

    <div>
        Arrêtée à la somme de :
    </div>
    <div>
        @php
            $nombreEntier = intval($totalTTC);
        @endphp
        <b>@numToWords($nombreEntier)</b>
    </div>
    <div class="footer">
        <div class="company-details">
            <p>AutoBody Repair s.a.l. Address: N 322, Lot Ennamae QI, Bensouda FES</p>
            <p>N° de tel: 0535607454, ICE: 002300070600003, Email: autobody.repair@gmail.com</p>
        </div>
    </div>
</div>
</body>
</html>
