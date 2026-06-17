<div class="dashboard-shell dashboard-shell--admin">
    <header class="admin-dash-header">
        <div>
            <p class="admin-dash-header__eyebrow">Admin overview</p>
            <h1 class="admin-dash-header__title">Welcome back, {{ Auth::user()->name }}</h1>
            <p class="admin-dash-header__subtitle">Monitor users, fleet, bookings, and revenue at a glance.</p>
        </div>
    </header>

    <div class="row g-3 g-xl-4 mb-2">
        <div class="col-6 col-xl-3">
            <div class="admin-stat-card">
                <span class="admin-stat-card__icon admin-stat-card__icon--teal"><i class="fas fa-users"></i></span>
                <p class="admin-stat-card__label">Total users</p>
                <p class="admin-stat-card__value">{{ $totalUsers ?? '0' }}</p>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="admin-stat-card">
                <span class="admin-stat-card__icon admin-stat-card__icon--gold"><i class="fas fa-user-group"></i></span>
                <p class="admin-stat-card__label">Customers</p>
                <p class="admin-stat-card__value">{{ $totalCustomers ?? '0' }}</p>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="admin-stat-card">
                <span class="admin-stat-card__icon admin-stat-card__icon--teal"><i class="fas fa-handshake"></i></span>
                <p class="admin-stat-card__label">Fleet providers</p>
                <p class="admin-stat-card__value">{{ $totalFleetProviders ?? '0' }}</p>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="admin-stat-card">
                <span class="admin-stat-card__icon admin-stat-card__icon--gold"><i class="fas fa-car"></i></span>
                <p class="admin-stat-card__label">Active fleets</p>
                <p class="admin-stat-card__value">{{ $totalFleets ?? '0' }}</p>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="admin-stat-card">
                <span class="admin-stat-card__icon admin-stat-card__icon--teal"><i class="fas fa-calendar-check"></i></span>
                <p class="admin-stat-card__label">Bookings</p>
                <p class="admin-stat-card__value">{{ $totalBookings ?? '0' }}</p>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="admin-stat-card">
                <span class="admin-stat-card__icon admin-stat-card__icon--gold"><i class="fas fa-file-invoice"></i></span>
                <p class="admin-stat-card__label">Invoices</p>
                <p class="admin-stat-card__value">{{ $totalInvoices ?? '0' }}</p>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="admin-stat-card">
                <span class="admin-stat-card__icon admin-stat-card__icon--warn"><i class="fas fa-hourglass-half"></i></span>
                <p class="admin-stat-card__label">Unpaid invoices</p>
                <p class="admin-stat-card__value">{{ $ToBePaidInvoices ?? '0' }}</p>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="admin-stat-card">
                <span class="admin-stat-card__icon admin-stat-card__icon--teal"><i class="fas fa-id-card"></i></span>
                <p class="admin-stat-card__label">Verification queue</p>
                <p class="admin-stat-card__value">{{ $verification_requests ?? '0' }}</p>
            </div>
        </div>
    </div>

    <section class="admin-quick-links mb-4">
        <h2 class="admin-section-title">Quick links</h2>
        <div class="row g-2 g-md-3">
            <div class="col-6 col-md-4 col-lg">
                <a href="{{ route('users.index') }}" class="admin-quick-link"><i class="fas fa-users"></i><span>Users</span></a>
            </div>
            <div class="col-6 col-md-4 col-lg">
                <a href="{{ route('roles.index') }}" class="admin-quick-link"><i class="fas fa-user-shield"></i><span>Roles</span></a>
            </div>
            <div class="col-6 col-md-4 col-lg">
                <a href="{{ route('fleet.index') }}" class="admin-quick-link"><i class="fas fa-car"></i><span>Fleet</span></a>
            </div>
            <div class="col-6 col-md-4 col-lg">
                <a href="{{ route('customer.bookings.index') }}" class="admin-quick-link"><i class="fas fa-calendar-days"></i><span>Bookings</span></a>
            </div>
            <div class="col-6 col-md-4 col-lg">
                <a href="{{ route('invoices.index') }}" class="admin-quick-link"><i class="fas fa-file-invoice-dollar"></i><span>Invoices</span></a>
            </div>
            <div class="col-6 col-md-4 col-lg">
                <a href="{{ route('payments.index') }}" class="admin-quick-link"><i class="fas fa-credit-card"></i><span>Payments</span></a>
            </div>
            <div class="col-6 col-md-4 col-lg">
                <a href="{{ route('verification_requests.index') }}" class="admin-quick-link"><i class="fas fa-clipboard-check"></i><span>Verification</span></a>
            </div>
        </div>
    </section>

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="admin-chart-card">
                <div class="admin-chart-card__head">
                    <h3 class="admin-chart-card__title">Invoice amounts</h3>
                    <p class="admin-chart-card__hint">Paid vs pending (platform fees)</p>
                </div>
                <div class="admin-chart-card__body admin-chart-card__body--doughnut">
                    <canvas id="pendingAmountChart" height="260"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="admin-chart-card">
                <div class="admin-chart-card__head">
                    <h3 class="admin-chart-card__title">Revenue by month</h3>
                    <p class="admin-chart-card__hint">Fee revenue (current year data)</p>
                </div>
                <div class="admin-chart-card__body admin-chart-card__body--line">
                    <canvas id="revenueChartAdmin"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
(function () {
    const brandTeal = '#01232e';
    const brandGold = '#d4a056';
    const pending = {{ $totalPendingAmount ?? 0 }};
    const paid = {{ $totalPaidAmount ?? 0 }};

    function renderPendingVsPaidChart() {
        const el = document.getElementById('pendingAmountChart');
        if (!el || typeof Chart === 'undefined') return;
        new Chart(el.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['Pending amount', 'Paid amount'],
                datasets: [{
                    data: [pending, paid],
                    backgroundColor: [brandGold, brandTeal],
                    borderColor: '#fff',
                    borderWidth: 3,
                    hoverOffset: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '58%',
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom',
                        labels: { padding: 16, font: { family: "'DM Sans', sans-serif", size: 12 } }
                    },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                const v = context.raw !== undefined ? context.raw : context.parsed;
                                return ' ' + context.label + ': Rs. ' + Number(v).toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    }

    function renderRevenueLine() {
        const el = document.getElementById('revenueChartAdmin');
        if (!el || typeof Chart === 'undefined') return;
        new Chart(el.getContext('2d'), {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Revenue (Rs.)',
                    data: @json($revenueByMonth ?? array_fill(0, 12, 0)),
                    fill: true,
                    backgroundColor: 'rgba(1, 35, 46, 0.12)',
                    borderColor: brandTeal,
                    borderWidth: 2,
                    tension: 0.35,
                    pointBackgroundColor: brandGold,
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(1, 35, 46, 0.06)' },
                        ticks: { font: { family: "'DM Sans', sans-serif" } }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { family: "'DM Sans', sans-serif" }, maxRotation: 0 }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        labels: { font: { family: "'DM Sans', sans-serif" } }
                    }
                }
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        renderPendingVsPaidChart();
        renderRevenueLine();
    });
})();
</script>
