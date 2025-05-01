@extends('layouts.app')

@section('content')
<div class="container invoice-index-container">
    <h2 class="mb-4">Invoices</h2>

    <div class="table-responsive">
        <table id="invoiceTable" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Booking No</th>
                    <th>Customer</th>
                    <th>Vehicle</th>
                    <th>Payment Status</th>
                    <th>Due Date</th>
                    <th>Created At</th>
                    <th>Action</th>

                </tr>
            </thead>
            <tbody>
                @foreach($invoices as $invoice)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $invoice->booking_no }}</td>
                        <td>{{ $invoice->customer->name ?? 'N/A' }}</td>
                        <td>{{ $invoice->fleet->vehicle_name ?? 'N/A' }}</td>
                        <td>{{ ucfirst($invoice->payment_status) }}</td>
                        <td>{{ $invoice->due_date }}</td>
                        <td>{{ $invoice->created_at->format('Y-m-d') }}</td>
                        <td>
                            <a href="{{ route('invoices.download', $invoice->id) }}" class="btn-download" title="Download">
                            <i class="fas fa-download"></i>
                            </a>
                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function () {
        $('#invoiceTable').DataTable();
    });
</script>
@endsection
