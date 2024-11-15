<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        @page {
            size: A4;
            margin: 1.27cm;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 11pt;
            line-height: 1.2;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .header {
            position: relative;
            width: 100%;
            margin-bottom: 20px;
            font-size: 10pt;
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
            font-size: 14pt;
            margin: 40px 0 20px;
        }

        .period {
            text-align: center;
            margin-bottom: 15px;
            font-size: 11pt;
        }

        .fund-cluster {
            margin-bottom: 15px;
            font-size: 11pt;
        }

        .report-info {
            text-align: right;
            margin-bottom: 20px;
            font-size: 11pt;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
            font-size: 10pt;
        }

        th, td {
            border: 0.5px solid black;
            padding: 4px;
            text-align: center;
            vertical-align: middle;
            height: 20px;
        }

        th {
            font-weight: bold;
            background-color: #f8f8f8;
        }

        .amount-cell {
            text-align: right;
        }

        .total-row td {
            font-weight: bold;
            background-color: #f8f8f8;
        }

        .certification {
            text-align: center;
            margin: 30px 0;
            font-size: 10pt;
            line-height: 1.5;
        }


        .signature-section {
            text-align: center;
            margin: 40px 0;
        }

        .signature-name {
            text-align: center;
            margin-bottom: 30px;
        }

        .signature-line {
            border-top: 1px solid black;
            display: block;
            width: 200px;
            margin: 0 auto;
        }

        .signature-details {
            font-size: 10pt;
            text-align: center;
            margin-top: 3px;
        }

        .footer {
            margin-top: 40px;
            display: flex;
            justify-content: space-around;
            font-size: 10pt;
        }

        .footer > div {
            text-align: center;
            width: 200px;
        }

        .footer .signature-name {
            margin-bottom: 30px;
        }

        .footer .signature-details {
            margin-top: 3px;
        }

        .col-date { width: 8%; }
        .col-number { width: 12%; }
        .col-payee { width: 24%; }
        .col-code { width: 8%; }
        .col-nature { width: 12%; }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    @php
        $dataArray = $groupedData->toArray();
        $chunks = array_chunk($dataArray, 8);
        $totalPages = count($chunks);
    @endphp

    @foreach($chunks as $pageIndex => $pageData)
    <div class="header">
        <div class="page-number">Page {{ $pageIndex + 1 }} of {{ $totalPages }}</div>
        <div class="appendix-number">Appendix {{ $appendixNumber }}</div>
    </div>

    <div class="title">REPORT OF CASH DISBURSEMENTS</div>

    <div class="period">
        Period Covered: {{ $period }}
    </div>

    <div class="fund-cluster">
        Fund Cluster: {{ $rcd->cdr->entity->fundCluster }}
    </div>

    <div class="report-info">
        Report Number: {{ $reportNumber }}<br>
        Sheet No.: {{ $pageIndex + 1 }}
    </div>

    <table>
        <thead>
            <tr>
                <th class="col-date">Date</th>
                <th class="col-number">DV/Payroll Number</th>
                <th class="col-number">ORS/BURS Number</th>
                <th class="col-number">Responsibility Center Code</th>
                <th class="col-payee">Payee</th>
                <th class="col-code">UACS Object Code</th>
                <th class="col-nature">Nature of Payment</th>
                <th class="col-number">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pageData as $group)
                <tr>
                    <td>{{ $group['date'] }}</td>
                    <td>{{ $group['dvNumber'] }}</td>
                    <td>{{ $group['orsNumber'] }}</td>
                    <td>{{ $group['responCode'] }}</td>
                    <td>{{ $group['payee'] }}</td>
                    <td>{{ $group['uacsCode'] }}</td>
                    <td>{{ $group['paymentNature'] }}</td>
                    <td class="amount-cell">{{ number_format($group['amount'], 2) }}</td>
                </tr>
            @endforeach

            @if($pageIndex === $totalPages - 1)
            <tr class="total-row">
                <td colspan="7" style="text-align: right">Total</td>
                <td class="amount-cell">{{ number_format($totalAmount, 2) }}</td>
            </tr>
            @endif
        </tbody>
    </table>

    @if($pageIndex === $totalPages - 1)
    <div class="certification">
        <strong>CERTIFICATION</strong><br>
        I hereby certify on my official oath that this Report of Cash Disbursements in sheet(s) is a full, true,<br>
        and correct statement of all cash disbursements during the period stated above actually made by me in payment<br>
        for obligations shown in pertinent disbursement vouchers/payroll.
    </div>

    <div class="signature-section">
        <div class="signature-name">
            {{ $rcd->cdr->designation->accountableOfficer }}
        </div>
        <div class="signature-line"></div>
        <div class="signature-details">
            Name and Signature of Disbursing Officer/Cashier
        </div>
    </div>

    <div class="footer">
        <div>
            <div class="signature-name">
                {{ $rcd->cdr->designation->officialDesignation }}
            </div>
            <div class="signature-line"></div>
            <div class="signature-details">
                Official Designation
            </div>
        </div>
        <div>
            <div class="signature-name">
                {{ Carbon\Carbon::parse($rcd->updated_at)->format('m/d/Y') }}
            </div>
            <div class="signature-line"></div>
            <div class="signature-details">
                Date
            </div>
        </div>
    </div>
    @endif

    @if($pageIndex < $totalPages - 1)
    <div class="page-break"></div>
    @endif
    @endforeach
</body>
</html>
