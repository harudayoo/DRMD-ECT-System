<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Cash Disbursement Record</title>
    <style>
        @page {
            margin: 0.25in;
            size: 8.5in 13in;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 9pt;
            line-height: 1.2;
            margin: 0;
            padding: 0;
        }

        .container {
            padding: 12px;
        }

        .header {
            position: relative;
            margin-bottom: 15px;
        }

        .title {
            font-weight: bold;
            font-size: 12pt;
            text-align: center;
            margin: 12px 0 24px 0;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .entity-info {
            margin-bottom: 12px;
            font-size: 9pt;
        }

        .entity-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 4px;
            padding: 2px 0;
        }

        .entity-label {
            width: 120px;
            display: inline-block;
            font-weight: normal;
        }

        .entity-value {
            text-decoration: underline;
            font-weight: bold;
            padding-left: 4px;
        }

        .sheet-no {
            text-align: right;
            padding-right: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8.5pt;
            margin-top: 10px;
        }

        th, td {
            border: 1px solid black;
            padding: 4px 6px;
            text-align: center;
            vertical-align: middle;
            height: 42px;
            line-height: 1.2;
        }

        .header-row th {
            text-align: center;
            font-weight: normal;
            padding: 6px 4px;
            height: 30px;
        }

        .amount {
            font-family: 'Courier New', monospace;
            text-align: right;
            padding-right: 6px;
        }

        .page-number {
            text-align: right;
            font-size: 8pt;
            position: fixed;
            bottom: 15px;
            right: 15px;
        }

        .date { width: 8%; }
        .reference { width: 15%; }
        .payee { width: 22%; }
        .uacs { width: 10%; }
        .nature { width: 15%; }
        .cash-advanced { width: 10%; }
        .disbursements { width: 10%; }
        .balance { width: 10%; }

        .officer-title {
            display: block;
            font-size: 7.5pt;
            margin-top: 3px;
            font-style: italic;
        }

        .multiline-header {
            font-size: 8pt;
            line-height: 1.2;
            padding: 4px 3px;
        }

        .reference-text {
            font-size: 8pt;
            line-height: 1.3;
        }

        .payee-info {
            text-align: left;
            font-size: 8pt;
            padding: 4px 6px;
            line-height: 1.3;
        }

        tr {
            page-break-inside: avoid;
        }

        .page-break {
            page-break-after: always;
        }

        tr:nth-child(even) {
            background-color: #fafafa;
        }
    </style>
</head>
<body>
    @php
        $totalPages = ceil(count($beneficiaries) / 12);
        $currentPage = 1;
        $counter = 0;
        $balance = $cashAdvanceReceived;
    @endphp

    @for($page = 1; $page <= $totalPages; $page++)
        <div class="container">
            <div class="header">
                <div class="title">CASH DISBURSEMENT RECORD</div>
            </div>

            <div class="entity-info">
                <div class="entity-row">
                    <div>
                        <span class="entity-label">Entity Name:</span>
                        <span class="entity-value">{{ $entityName }}</span>
                    </div>
                    <div class="sheet-no">Sheet No.: {{ $sheetNumber }}</div>
                </div>
                <div class="entity-row">
                    <div>
                        <span class="entity-label">Fund Cluster:</span>
                        <span class="entity-value">{{ $fundCluster }}</span>
                    </div>
                </div>
            </div>

            <table>
                <tr class="header-row">
                    <th colspan="3">
                        {{ $accountableOfficer }}
                        <span class="officer-title">Accountable Officer</span>
                    </th>
                    <th colspan="3">
                        {{ $officialDesignation }}
                        <span class="officer-title">Official Designation</span>
                    </th>
                    <th colspan="2">
                        {{ $station }}
                        <span class="officer-title">Station</span>
                    </th>
                </tr>

                <tr>
                    <th class="date multiline-header">Date</th>
                    <th class="reference multiline-header">ADA/Check/DV/<br>Payroll/Reference<br>Number</th>
                    <th class="payee multiline-header">Payee</th>
                    <th class="uacs multiline-header">UACS<br>Object<br>Code</th>
                    <th class="nature multiline-header">Nature of<br>Payment</th>
                    <th class="cash-advanced multiline-header">Cash Advanced<br>Received<br>(Refunded)</th>
                    <th class="disbursements multiline-header">Disbursements</th>
                    <th class="balance multiline-header">Cash Advance<br>Balance</th>
                </tr>

                @if($page === 1)
                    <tr>
                        <td>{{ $cdr_date }}</td>
                        <td class="reference-text">
                            Check No. {{ $checkNumber }}<br>
                            DV. No. {{ $referenceNumber }}
                        </td>
                        <td class="payee-info">{{ $accountableOfficer }}</td>
                        <td>{{ $uacsObjectCode }}</td>
                        <td>{{ $natureOfPayment }}</td>
                        <td class="amount">PHP {{ number_format($cashAdvanceReceived, 2) }}</td>
                        <td></td>
                        <td class="amount">PHP {{ number_format($cashAdvanceReceived, 2) }}</td>
                    </tr>
                @endif

                @foreach($beneficiaries as $index => $beneficiary)
                    @if(floor($index / 12) + 1 == $page)
                        @php
                            $amount = (float)$beneficiary['amount'];
                            $balance -= $amount;

                            // Format names with proper capitalization
                            $formattedLastName = ucfirst(strtolower($beneficiary['lastName']));
                            $formattedFirstName = ucfirst(strtolower($beneficiary['firstName']));
                            $formattedMiddleName = $beneficiary['middleName'];
                        @endphp
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($beneficiary['updated_at'])->format('m/d/Y') }}</td>
                            <td class="reference-text">
                                DV. No. {{ $referenceNumber }}<br>
                                Payroll No. {{ $beneficiary['payrollNo'] }}
                            </td>
                            <td class="payee-info">
                                {{ strtoupper($beneficiary['barangayName']) }}, {{ strtoupper($beneficiary['municipalityName']) }}<br>
                                ({{ $formattedLastName }}, {{ $formattedFirstName }})
                            </td>
                            <td>{{ $uacsObjectCode }}</td>
                            <td>{{ $natureOfPayment }}</td>
                            <td></td>
                            <td class="amount">PHP {{ number_format($amount, 2) }}</td>
                            <td class="amount">PHP {{ number_format($balance, 2) }}</td>
                        </tr>
                    @endif
                @endforeach
            </table>

            <div class="page-number">Page {{ $page }} of {{ $totalPages }}</div>

            @if($page < $totalPages)
                <div class="page-break"></div>
            @endif
        </div>
    @endfor
</body>
</html>
