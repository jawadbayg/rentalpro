<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice</title>
    <style>
        body {
            font-family: sans-serif;
            margin: 0;
           
            color: #333;
        }

        .header {
            background-color: rgb(1, 35, 46);
            color: white;
            padding: 20px;
        }

        .title {
            font-size: 24px;
            font-weight: bold;
        }

        .info-section {
            margin-top: 30px;
        }

        .info-section p {
            font-size: 14px;
            margin: 5px 0;
        }

        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }

        .details-table th,
        .details-table td {
            border: 1px solid #ddd;
            padding: 10px;
            font-size: 13px;
        }

        .details-table th {
            background-color: rgb(1, 35, 46);
            color: white;
        }

        .footer {
            margin-top: 40px;
            font-size: 13px;
        }
    </style>
</head>
<body>

    <div class="header">
        <div class="title">Rental Pro</div>

        <div class="info-section">
            <h2>Invoice</h2>
            <p><strong>Booking ID:</strong> {{ $invoice->booking_no }}</p>
            <p><strong>Payment Status:</strong> {{ ucfirst($invoice->payment_status) }}</p>
            <p><strong>Due Date:</strong> {{ $invoice->due_date }}</p>
            <p><strong>Issued On:</strong> {{ $invoice->created_at->format('Y-m-d') }}</p>
        </div>
    </div>

    <table style="width: 100%; margin-bottom: 30px;margin-top: 30px;">
            <tr>
                <td style="width: 50%; vertical-align: top;">
                    <h4>Customer Details</h4>
                    <p><strong>Name:</strong> {{ $invoice->customer->name }}</p>
                    <p><strong>Email:</strong> {{ $invoice->customer->email }}</p>
                    <p><strong>Address:</strong> {{ $invoice->customer->address }}</p>
                </td>
                <td style="width: 50%; vertical-align: top;">
                    <h4>Fleet Provider Details</h4>
                    <p><strong>Provider:</strong> {{ $invoice->fp->name }}</p>
                    <p><strong>Email:</strong> {{ $invoice->fp->email }}</p>
                    <p><strong>Vehicle:</strong> {{ $invoice->fleet->vehicle_name }}</p>
                    <p><strong>Address:</strong> {{ $invoice->fp->address }}</p>        
                </td>
            </tr>
        </table>
    <table class="details-table">
        <thead>
            <tr>
                <th>Booking ID</th>
                <th>Vehicle</th>
                <th>License Plate</th>
                <th>From</th>
                <th>To</th>
                <th>Total Amount</th>
                @if(Auth::user()->hasRole('Admin') || (Auth::user()->hasRole('FP')))  
                    <th>Fee %</th>
                    <th>Final Amount</th>
                @endif
                @if(Auth::user()->hasRole('Admin'))
                    <th>Rental Pro Amount</th>
                @endif
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $invoice->booking->booking_no }}</td>
                <td>{{ $invoice->fleet->vehicle_name }}</td>
                <td>{{ $invoice->fleet->license_plate }}</td>
                <td>{{ $invoice->booking->from_date }}</td>
                <td>{{ $invoice->booking->to_date }}</td>
                <td>£{{ $invoice->booking->total_price }}</td>
                @if(Auth::user()->hasRole('Admin') || (Auth::user()->hasRole('FP')))  
                    <td>20</td>
                    <td>£{{ number_format($invoice->booking->total_price * 0.80, 2) }}</td>
                @endif
                @if(Auth::user()->hasRole('Admin'))
                    <td>£{{ number_format($invoice->booking->total_price * 0.20, 2) }}</td>
                @endif
            </tr>
        </tbody>
    </table> 

    <div class="footer">
        <p>We appreciate your business with Rental Pro.</p>
        <p>Thank you!</p>
    </div>

</body>
</html>
