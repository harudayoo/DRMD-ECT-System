<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cash Assistance Payroll</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.5;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .main-title {
            font-size: 24px;
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 5px;
        }
        .footer {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            margin-top: 20px;
        }
        .page-number:before {
            content: counter(page);
        }
        .page-break {
            page-break-after: always;
        }
        .beneficiary-table {
            width: 100%;
            border-collapse: collapse;
        }
        .beneficiary-table td {
            border: 1px solid black;
            padding: 30px 0;
            font-size: 24px;
            text-align: center;
            font-weight: bold;
            width: 25%;
            word-break: break-all;
        }
    </style>
</head>
<body>
    <div class="header">
        <p>Republic of the Philippines</p>
        <p>DEPARTMENT OF SOCIAL WELFARE AND DEVELOPMENT</p>
        <p>Field Office XI, Davao City</p>
        <p class="main-title">CASH ASSISTANCE PAYROLL</p>
    </div>

    <p>MAIN</p>
    <p>FOR PAYMENT OF CASH FOR EMERGENCY CASH TRANSFER (ECT) FOR THE FAMILIES AFFECTED BY TROUGH OF LPA IN THE PROVINCE OF {{ strtoupper($payroll->barangay->municipality->province->provinceName) }} THE PERIOD OF <u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u>, 2024</p>
    <p>Project Name: <u>{{ $payroll->barangay->barangayName }}, {{ $payroll->barangay->municipality->municipalityName }}</u></p>

    @foreach($beneficiaries as $pageIndex => $pageBeneficiaries)
        <table>
            <thead>
                <tr>
                    <th>Beneficiary Number</th>
                    <th>Last Name</th>
                    <th>First Name</th>
                    <th>Middle Name</th>
                    <th>Barangay</th>
                    <th>Amount</th>
                    <th>Signature</th>
                    <th>Date Paid</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pageBeneficiaries as $beneficiary)
                    <tr>
                        <td>{{ $beneficiary->beneficiaryNumber }}</td>
                        <td>{{ strtoupper($beneficiary->lastName) }}</td>
                        <td>{{ strtoupper($beneficiary->firstName) }}</td>
                        <td>{{ strtoupper($beneficiary->middleName) }}</td>
                        <td>{{ $payroll->barangay->barangayName }}</td>
                        <td>{{ number_format($beneficiary->amount, 2) }}</td>
                        <td></td>
                        <td></td>
                    </tr>
                @endforeach
                @for($i = count($pageBeneficiaries); $i < 10; $i++)
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                @endfor
            </tbody>
        </table>

        <p style="text-align: right;">Sub-total: {{ number_format($pageBeneficiaries->sum('amount'), 2) }}</p>

        <div class="footer">
            {{ $payroll->payrollNumber }}-{{ $pageIndex + 1 }}
        </div>

        <div style="text-align: right;">
            Page <span class="page-number"></span> of {{ ceil(count($payroll->beneficiaries) / 10) }}
        </div>

        @if(!$loop->last)
            <div class="page-break"></div>
        @endif
    @endforeach

      <!-- Add a page break before the new table -->
      <div class="page-break"></div>

      <!-- New page for beneficiary number table -->
      <table class="beneficiary-table">
          @foreach($payroll->beneficiaries->chunk(2) as $pair)
              <tr>
                  @foreach($pair as $beneficiary)
                      <td>{{ $beneficiary->beneficiaryNumber }}</td>
                      <td>{{ $beneficiary->beneficiaryNumber }}</td>
                  @endforeach
              </tr>
          @endforeach
      </table>
</body>
</html>
