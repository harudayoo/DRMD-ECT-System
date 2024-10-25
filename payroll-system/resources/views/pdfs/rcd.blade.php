{{-- resources/views/exports/rcd.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: arial;
            font-size: 12px;
            line-height: 1.5;
        }
        .header {
            position: relative;
            width: 100%;
            margin-bottom: 20px;
        }
        .page-number {
            position: absolute;
            left: 0;
            top: 0;
        }
        .appendix-number {
            position: absolute;
            right: 0;
            top: 0;
        }
        .title {
            text-align: center;
            font-weight: bold;
            margin: 20px 0;
        }
        .period {
            text-align: center;
            margin-bottom: 20px;
        }
        .fund-center {
            margin-bottom: 10px;
        }
        .report-info {
            text-align: right;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid black;
            padding: 5px;
            font-size: 11px;
        }
        .total-row {
            text-align: right;
            font-weight: bold;
        }
        .certification {
            text-align: center;
            margin: 30px 0;
        }
        .signature-line {
            text-align: center;
            margin: 20px 0;
        }
        .underline {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="page-number">Page {{ $PAGE_NUM }}</div>
        <div class="appendix-number">Appendix {{ $appendixNumber }}</div>
    </div>

    <div class="title">
        REPORT OF CASH DISBURSEMENTS
    </div>
    <div class="period">
        Period Covered: {{ $period }}
    </div>

    <div class="fund-center">
    Fund Center: {{ $fundCenter ?? '________________' }}
</div>
    <div class="report-info">
        Report Number: {{ $reportNumber }}<br>
        Sheet No.: _________________
    </div>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>DV/Payroll Number</th>
                <th>ORS/BURS Number</th>
                <th>Responsibility Center Code</th>
                <th>Payee</th>
                <th>UACS Object Code</th>
                <th>Nature of Payment</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($groupedData as $data)
                <tr>
                    <td>{{ $data['date'] }}</td>
                    <td>{{ $data['dvNumber'] }}</td>
                    <td>{{ $data['orsNumber'] }}</td>
                    <td>{{ $data['responCode'] }}</td>
                    <td>{{ $data['payee'] }}</td>
                    <td>{{ $data['uacsCode'] }}</td>
                    <td>{{ $data['paymentNature'] }}</td>
                    <td style="text-align: right">{{ $data['amount'] }}</td>
                </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="7">Total</td>
                <td>{{ number_format($totalAmount, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <div class="certification">
        <strong>CERTIFICATION</strong><br><br>
        I hereby certify on my official oath that this Report of Cash Disbursements in sheet(s) is a full, true,<br>
        and correct statement of all cash disbursements during the period stated above actually made by me in payment<br>
        for obligations shown in pertinent disbursement vouchers/payroll.
    </div>

    <div class="signature-line">
        <div class="underline">{{ $rcd->dvPayroll->dvPName ?? '' }}</div>
        Name and Signature of Disbursing Officer/Cashier
    </div>

    <div class="signature-line">
        <div class="underline">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
        Official Designation
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <span class="underline">{{ Carbon\Carbon::parse($rcd->updated_at)->format('m/d/Y') }}</span>
        <br>Date
    </div>
</body>
</html>
