<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Devis {{$quotation->client->name}}</title>
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
        <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('/images/logo_pdf.png'))) }}" width="500px" alt="AutoBody Logo">
    </div>
    <?php $totalHT = 0;$totalTTC = 0; ?>
    <table class="info-table">
        <tr>
            <th>Devis N° :</th>
            <td>{{$quotation->id}}</td>
            <th>Date :</th>
            <td>
            @php
                $date = substr($quotation->created_at, 0, 10);
            @endphp
                {{$date}}
            </td>

        </tr>
        <tr>
            <th>Vehicule :</th>
            <td>{{$quotation->vehicle->label}}</td>
            <th>Client :</th>
            <td>{{($quotation->client->user ? (($quotation->client->user->sexe == 'H' ? 'Mr' : 'Mme') . ' ') : '') . $quotation->client->name}}</td>
        </tr>
        <tr>
            <th>Chassis No :</th>
            <td>{{$quotation->vehicle->chassis_number}}</td>
            <th>Immatricule :</th>
            <td>{{$quotation->vehicle->registration}}</td>
        </tr>
        <tr>
            <th></th>
            <td></td>
            <th>Assurance :</th>
            <td>{{$quotation->vehicle->insurance}}</td>
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
        @foreach($quotation->quotationLines as $ql)
            @if($ql->type != 'MOD')
            <tr>
                <td><b>{{$ql->reference ?? $ql->state}}</b></td>
                <td><b>{{$ql->description}}</b></td>
                <td><b>{{$ql->quantity}}</b></td>
                <td><b>{{number_format($ql->price, 2)}}</b></td>
                <td><b>{{number_format($ql->price * $ql->quantity, 2)}}</b></td>
                <td><b>{{$ql->TVA ? $ql->TVA . '%' : '' }}</b></td>
                <td><b>{{$ql->TVA ?  number_format($ql->price * $ql->quantity * (1 + ($ql->TVA/100)), 2) : '' }}</b></td>
            </tr>
            <?php
                $totalHT += $ql->price * $ql->quantity;
                $totalTTC += $ql->price * $ql->quantity * (1 + ($ql->TVA/100));
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
        <tr style="margin-top: 40px !important;">
            <td></td>
            <td><u>{{strtoupper($quotation->title)}}  :</u></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        @foreach($quotation->quotationLines as $ql)
            @if($ql->type == 'MOD')
                <?php
                $price_ttc = $ql->price * (1 + ($ql->TVA/100));
                ?>
                <tr>
                    <td><b>{{$ql->state}}</b></td>
                    <td><b>{{$ql->description}}</b></td>
                    <td><b>{{$ql->quantity}}</b></td>
                    <td><b></b></td>
                    <td><b>{{number_format($ql->price, 2)}}</b></td>
                    <td><b>{{$ql->TVA}}%</b></td>
                    <td><b>{{ number_format($price_ttc, 2)}}</b></td>
                </tr>
                    <?php
                    $totalHT += $ql->price ;
                    $totalTTC += $ql->price *  (1 + ($ql->TVA/100));
                    ?>
            @endif
        @endforeach
    </table>

    <div class="totals" style="display: flex; justify-content: end">
        <table class="totals-table" style="width: 100% !important;" >
            <tr>
                <th style="width: 80% !important;">Total HT :</th>
                <td><b>{{$totalHT}} DH</b></td>
            </tr>
            <tr>
                <th>Total TTC :</th>
                <td><b>{{$totalTTC}} DH</b></td>
            </tr>
        </table>
    </div>

    <div>
        Arrêté le présent devis à la somme de :
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
