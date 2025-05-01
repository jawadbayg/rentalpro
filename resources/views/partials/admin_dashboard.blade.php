<div class="admin-dashboard-container py-4">
    <div class="row">
        <div class="col-md-3">
            <div class="dashboard-card">
                <h5>Total Users</h5>
                <h3>{{ $totalUsers ?? '0' }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="dashboard-card">
                <h5>Total Customers</h5>
                <h3>{{ $totalCustomers ?? '0' }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="dashboard-card">
                <h5>Total Fleet Providers</h5>
                <h3>{{ $totalFleetProviders ?? '0' }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="dashboard-card">
                <h5>Total Active Fleets</h5>
                <h3>{{ $totalFleets ?? '0' }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="dashboard-card">
                <h5>Total Bookings</h5>
                <h3>{{ $totalBookings ?? '0' }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="dashboard-card">
                <h5>Total Invoices</h5>
                <h3>{{ $totalInvoices ?? '0' }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="dashboard-card">
                <h5>To be Paid Invoices</h5>
                <h3>{{ $ToBePaidInvoices ?? '0' }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="dashboard-card">
                <h5>Verification Requests</h5>
                <h3>{{ $verification_requests ?? '0' }}</h3>
            </div>
        </div>
    </div>
    <div class="graph-container py-4">
        <div class="row">
            <div class="col-md-6">
                <div class="graph-card">
                    <h5>Invoices Total (Paid amount & Pending Amounts)</h5>
                        <div class="chart-div">
                            <canvas id="pendingAmountChart" class="chart-smaller" height="120"></canvas>
                        </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="graph-card">
                    <h5>Revenue Total</h5>
                        <div class="chart-div">
                            <canvas id="" class="chart-smaller" height="120"></canvas>
                        </div>
                </div>
            </div>
        </div>
    </div>

</div>

<style>
    .admin-dashboard-container{
        font-family: 'Poppins',sans-serif;
    }
    .dashboard-card {
        background-color:white;
        padding: 20px;
        margin-bottom: 20px;
        border-radius: 12px;
        text-align: center;
        box-shadow: 0 0 10px rgba(0,0,0,0.06);
        transition: transform 0.2s ease;
    }
    .dashboard-card h5 {
        font-size: 16px;
        color: #333;
        margin-bottom: 10px;
        font-weight: 500;
    }
    .dashboard-card h3 {
        font-size: 28px;
        font-weight: 700;
        color: #111;
    }
    .dashboard-card:hover {
        transform: translateY(-3px);
    }
    .graph-container {
    font-family: 'Poppins', sans-serif;
    }
    
    .graph-card {
        background-color: #fff;
        padding: 20px;
        margin-bottom: 20px;
        border-radius: 12px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.06);
        transition: transform 0.2s ease;
    }

    .graph-card h5 {
        font-size: 16px;
        color: #333;
        margin-bottom: 15px;
        font-weight: 500;
    }

    .graph-placeholder {
        height: 200px;
        background-color: #f2f2f2;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #999;
        font-size: 14px;
    }
    .chart-smaller {
        width: 300px !important;  
        height: 300px !important;
    }
    .chart-div{
        display: flex;
        justify-content: center;
        align-items: center;
    }
</style>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    function renderPendingVsPaidChart(pendingAmount, paidAmount) {
        const ctx = document.getElementById("pendingAmountChart").getContext('2d');

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Pending Amount', 'Paid Amount'],
                datasets: [{
                    data: [pendingAmount, paidAmount],
                    backgroundColor: ['rgb(12, 93, 123)', '#36A2EB'],
                    borderColor: ['#fff', '#fff'],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                aspectRatio: 1, 
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.label + ': £ ' + context.parsed;
                            }
                        }
                    }
                }
            }
        });
    }

    document.addEventListener("DOMContentLoaded", function() {
        const totalPendingAmount = {{ $totalPendingAmount ?? 0 }};
        const totalPaidAmount = {{ $totalPaidAmount ?? 0 }};
        renderPendingVsPaidChart(totalPendingAmount, totalPaidAmount);
    });
</script>
