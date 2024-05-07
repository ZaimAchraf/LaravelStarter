<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Devis {{$quotation->client->name}}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #333; }
        .invoice-box { max-width: 800px; margin: auto; padding: 10px; border: 1px solid #eee; }
        .logo { text-align: center; margin-bottom: 20px; }
        .logo img { max-width: 180px; }
        .company-details { text-align: center; margin-bottom: 20px; }
        .info-table, .items-table, .totals-table { width: 100%; border-collapse: collapse; margin-bottom: 30px}
        .info-table th, .info-table td, .items-table th, .items-table td, .totals-table th, .totals-table td { border: 1px solid #ddd; padding: 5px; }
        .info-table th { background-color: #f2f2f2; text-align: left; }
        .info-table td { text-align: left; }
        .items-table th { background-color: #f2f2f2; text-align: left; }
        .totals-table { margin-top: 20px; }
        .totals-table th { background-color: #f2f2f2; text-align: right; }
        .totals-table td { text-align: right; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .footer { text-align: center; margin-top: 20px; font-size: 10px; }
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
            <th>Date</th>
            <td>{{$quotation->created_at}}</td>
            <th>Client</th>
            <td>{{($quotation->client->user ? (($quotation->client->user->sexe == 'H' ? 'Mr' : 'Mme') . ' ') : '') . $quotation->client->name}}</td>
        </tr>
        <tr>
            <th>Vehicule</th>
            <td>{{$quotation->vehicle->label}}</td>
            <th>Assurance</th>
            <td>{{$quotation->vehicle->insurance}}</td>
        </tr>
        <tr>
            <th>Chassis No</th>
            <td>{{$quotation->vehicle->chassis_number}}</td>
            <th>Immatricule</th>
            <td>{{$quotation->vehicle->registration}}</td>
        </tr>
        <!-- Additional rows as needed -->
    </table>

    <table class="items-table">
        <tr>
            <th>Description</th>
            <th>QTE</th>
            <th>P.U</th>
            <th>Total (HT)</th>
            <th>TVA</th>
            <th>Montant (TTC)</th>
        </tr>
        @foreach($quotation->quotationLines as $ql)
            <tr>
                <td>{{$ql->description}}</td>
                <td>{{$ql->quantity}}</td>
                <td>{{$ql->price}}</td>
                <td>{{$ql->price * $ql->quantity}}</td>
                <td>{{$ql->TVA}}%</td>
                <td>{{$ql->price * $ql->quantity * (1 + ($ql->TVA/100))}}</td>
            </tr>
            <?php
                $totalHT += $ql->price * $ql->quantity;
                $totalTTC += $ql->price * $ql->quantity * (1 + ($ql->TVA/100));
            ?>
        @endforeach
    </table>

    <div class="totals" style="display: flex; justify-content: space-between">
        <table class="totals-table" style="width: 50% !important;" >
            <tr>
                <th>Total HT</th>
                <td>{{$totalHT}}</td>
            </tr>
            <tr>
                <th>Total TTC</th>
                <td>{{$totalTTC}}</td>
            </tr>
        </table>
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
