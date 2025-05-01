<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <style>
        body { font-family: sans-serif; margin: 0; padding: 0; font-size: 14px;}
       
        p{
            font-size: 14px;
        }
        .header {
        background-color:  rgb(1, 35, 46);
        color: white; 
        padding: 20px;}
        .title{
            margin-bottom: 20px;
        }
       .span{
        font-size: 12px;
        margin-top: 30px;
       }
       .details-table th{
        background-color:   rgb(1, 35, 46);
        color: white; 
       }
        .header .title { font-size: 24px; font-weight: bold; text-align: left; }

        .section { display: flex; justify-content: space-between; margin-bottom: 30px; }
        .section .left, .section .right { width: 48%; }

        .details-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .details-table th, .details-table td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        /* .details-table th { background-color: #f4f4f4; } */

        .created-at { font-size: 12px; color: #777; margin-bottom: 20px; }
        .row {
                display: flex;
                flex-wrap: wrap;
                margin-bottom: 30px;
            }

            .col-6 {
                width: 50%;
                box-sizing: border-box;
                padding: 0 15px;
            }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="title">Rental Pro</div>

            <div class="info-section">
                <h2>Invoice</h2>
                <p><strong>Booking ID:</strong> {{ $booking->booking_no }}</p>
                <p><strong>Payment Status:</strong> {{ ucfirst($booking->payment_status) }}</p>
                <p><strong>Issued On:</strong> {{ $booking->created_at->format('Y-m-d') }}</p>
            </div>
        </div>

    
        <table style="width: 100%; margin-bottom: 30px;margin-top: 30px;">
            <tr>
                <td style="width: 50%; vertical-align: top;">
                    <h4>Customer Details</h4>
                    <p><strong>Name:</strong> {{ $customer->name }}</p>
                    <p><strong>Email:</strong> {{ $customer->email }}</p>
                    <p><strong>Address:</strong> {{ $customer->address }}</p>
                </td>
                <td style="width: 50%; vertical-align: top;">
                    <h4>Fleet Provider Details</h4>
                    <p><strong>Provider:</strong> {{ $fp->name }}</p>
                    <p><strong>Vehicle:</strong> {{ $fleet->vehicle_name }}</p>
                    <p><strong>Address:</strong> {{ $fleet->address }}</p>
                </td>
            </tr>
        </table>

        <table class="details-table">
            <thead class="thead">
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
                    <td>{{ $booking->booking_no }}</td>
                    <td>{{ $booking->fleet->vehicle_name }}</td>
                    <td>{{ $booking->fleet->license_plate }}</td>
                    <td>{{ $booking->from_date }}</td>
                    <td>{{ $booking->to_date }}</td>
                    <td>£{{ $booking->total_price }}</td>
                    @if(Auth::user()->hasRole('Admin') || (Auth::user()->hasRole('FP')))  
                        <td>20</td>
                        <td>£{{ number_format($booking->total_price * 0.80, 2) }}</td>
                    @endif
                    @if(Auth::user()->hasRole('Admin'))
                        <td>£{{ number_format($booking->total_price * 0.20, 2) }}</td>
                    @endif
                </tr>
            </tbody>
        </table>

        <div class="details mt-4">
            <p>We appreciate you choosing to do business with Rental Pro.
                Thank you!
            </p>
        </div>
    </div>
</body>
</html>
