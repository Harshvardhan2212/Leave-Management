<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payslip - WebCodeGenie</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .container {
            width: 80%;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            color: #0047ab; /* Dark blue from WebCodeGenie's theme */
        }
        .header p {
            margin: 5px 0;
            color: #555;
        }
        .logo {
            width: 150px;
            margin-bottom: 20px;
        }
        .section {
            margin-bottom: 30px;
        }
        .section h2 {
            color: #0047ab;
            border-bottom: 2px solid #0047ab;
            padding-bottom: 5px;
        }
        .section p {
            margin: 5px 0;
            font-size: 1rem;
            color: #333;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            color: #777;
            font-size: 0.9rem;
        }
        .badge {
            display: inline-block;
            padding: 5px 15px;
            font-size: 0.9rem;
            border-radius: 15px;
            color: #fff;
            background-color: #ff5722; /* Accent color */
        }
        .details, .salary {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
        }
        .details strong, .salary strong {
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <p><b>WebCodeGenie Technology Pvt. Ltd.</b></p>
            <p>Ahmedabad, Gujarat, India</p>
            <h1>Payslip</h1>
        </div>

        <div class="section">
            <h2>Employee Details</h2>
            <div class="details">
                <p><strong>Name:</strong> {{ $firstName }}</p>
                <p><strong>Designation:</strong> {{ $designation }}</p>
                <p><strong>Department:</strong> {{ $department }}</p>
            </div>
        </div>

        <div class="section">
            <h2>Salary Details</h2>
            <div class="salary">
                <p><strong>Basic Salary:</strong> ${{ $basicSalary }}</p>
                <p><strong>Allowances:</strong> ${{ $allowances }}</p>
            </div>
            <div class="salary">
                <p><strong>Deductions:</strong> ${{ $deductions }}</p>
                <p><strong>Net Salary:</strong> ${{ $netSalary }}</p>
            </div>
            <div class="salary">
                <p><strong>Payment Date:</strong> {{ $paymentDate }}</p>
                <p><strong>Payment Status:</strong> <span>{{ $paymentStatus }}</span></p>
            </div>
        </div>

        <div class="footer">
            <p>Generated on {{ \Carbon\Carbon::now()->format('F d, Y') }}</p>
        </div>
    </div>
</body>
</html>
