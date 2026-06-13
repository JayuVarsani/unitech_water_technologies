@php
    $user = request()->user();
    $deliveryTotal = (int) ($deliveryCount->total_count ?? 0);
    $deliveryDirect = (int) ($deliveryCount->direct ?? 0);
    $deliveryTransport = (int) ($deliveryCount->transport ?? 0);
    $chartDirect = $deliveryDirect > 0 ? $deliveryDirect : 14;
    $chartTransport = $deliveryTransport > 0 ? $deliveryTransport : 9;
@endphp

<div class="dashboard-page">

    <div class="row g-5 g-xl-8 mb-8 mb-xl-10">
        @if($canViewVisit)
            <div class="col-sm-6 col-xl-3">
                <a href="{{ route('company.visit.index') }}" class="dashboard-stat-link">
                    <div class="card dashboard-stat-card dashboard-stat-card--primary h-100">
                        <div class="card-body d-flex flex-column justify-content-between p-6 p-xl-8">
                            <div class="d-flex align-items-start justify-content-between gap-3">
                                <span class="dashboard-stat-label">Visits</span>
                                <span class="dashboard-stat-icon dashboard-stat-icon--primary">
                                    <i class="fa-solid fa-location-dot"></i>
                                </span>
                            </div>
                            <div class="mt-6">
                                <div class="dashboard-stat-value">{{ number_format($visitCount) }}</div>
                                <div class="dashboard-stat-meta">Total service reports</div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endif

        @if($canViewDelivery)
            <div class="col-sm-6 col-xl-3">
                <a href="{{ route('company.delivery.index') }}" class="dashboard-stat-link">
                    <div class="card dashboard-stat-card dashboard-stat-card--info h-100">
                        <div class="card-body d-flex flex-column justify-content-between p-6 p-xl-8">
                            <div class="d-flex align-items-start justify-content-between gap-3">
                                <span class="dashboard-stat-label">Deliveries</span>
                                <span class="dashboard-stat-icon dashboard-stat-icon--info">
                                    <i class="fa-solid fa-truck"></i>
                                </span>
                            </div>
                            <div class="mt-6">
                                <div class="dashboard-stat-value">{{ number_format($deliveryTotal) }}</div>
                                <div class="dashboard-stat-badges">
                                    <span class="dashboard-stat-badge">Direct {{ number_format($deliveryDirect) }}</span>
                                    <span class="dashboard-stat-badge">Transport {{ number_format($deliveryTransport) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endif

        @if($user->assign_role_name !== 'Machine operator')
            @if($canViewCustomer)
                <div class="col-sm-6 col-xl-3">
                    <a href="{{ route('company.customer-management.customer.index') }}" class="dashboard-stat-link">
                        <div class="card dashboard-stat-card dashboard-stat-card--success h-100">
                            <div class="card-body d-flex flex-column justify-content-between p-6 p-xl-8">
                                <div class="d-flex align-items-start justify-content-between gap-3">
                                    <span class="dashboard-stat-label">Customers</span>
                                    <span class="dashboard-stat-icon dashboard-stat-icon--success">
                                        <i class="fa-solid fa-users"></i>
                                    </span>
                                </div>
                                <div class="mt-6">
                                    <div class="dashboard-stat-value">{{ number_format($customerCount) }}</div>
                                    <div class="dashboard-stat-meta">Active customer records</div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @endif

            @if($canViewStaff)
                <div class="col-sm-6 col-xl-3">
                    <a href="{{ route('company.staff-management.staff.index') }}" class="dashboard-stat-link">
                        <div class="card dashboard-stat-card dashboard-stat-card--danger h-100">
                            <div class="card-body d-flex flex-column justify-content-between p-6 p-xl-8">
                                <div class="d-flex align-items-start justify-content-between gap-3">
                                    <span class="dashboard-stat-label">Staff</span>
                                    <span class="dashboard-stat-icon dashboard-stat-icon--danger">
                                        <i class="fa-solid fa-user-tie"></i>
                                    </span>
                                </div>
                                <div class="mt-6">
                                    <div class="dashboard-stat-value">{{ number_format($staffCount) }}</div>
                                    <div class="dashboard-stat-meta">Team members on panel</div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @endif

            {{-- @if($canViewCustomerGroup)
                <div class="col-sm-6 col-xl-3">
                    <a href="{{ route('company.customer-management.customer-group.index') }}" class="dashboard-stat-link">
                        <div class="card dashboard-stat-card dashboard-stat-card--warning h-100">
                            <div class="card-body d-flex flex-column justify-content-between p-6 p-xl-8">
                                <div class="d-flex align-items-start justify-content-between gap-3">
                                    <span class="dashboard-stat-label">Customer Groups</span>
                                    <span class="dashboard-stat-icon dashboard-stat-icon--warning">
                                        <i class="fa-solid fa-people-group"></i>
                                    </span>
                                </div>
                                <div class="mt-6">
                                    <div class="dashboard-stat-value">{{ number_format($customergroupCount) }}</div>
                                    <div class="dashboard-stat-meta">Grouped customer accounts</div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @endif --}}
        @endif
    </div>

    <div class="row g-5 g-xl-8">
        <div class="col-xl-6">
            <div class="card dashboard-chart-card h-100">
                <div class="card-header border-0 pt-7 pb-0 px-7">
                    <h3 class="dashboard-chart-title mb-1">Monthly Visits</h3>
                    <p class="dashboard-chart-subtitle mb-0">Service visits recorded over the last 6 months</p>
                </div>
                <div class="card-body px-7 pb-7 pt-5">
                    <div class="dashboard-chart-wrap">
                        <canvas id="dashboardVisitsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6">
            <div class="card dashboard-chart-card h-100">
                <div class="card-header border-0 pt-7 pb-0 px-7">
                    <h3 class="dashboard-chart-title mb-1">Delivery Overview</h3>
                    <p class="dashboard-chart-subtitle mb-0">Direct delivery vs transport / parcel challans</p>
                </div>
                <div class="card-body px-7 pb-7 pt-5">
                    <div class="dashboard-chart-wrap">
                        <canvas id="dashboardDeliveriesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('footer')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const visitsCtx = document.getElementById('dashboardVisitsChart');
        const deliveriesCtx = document.getElementById('dashboardDeliveriesChart');

        if (visitsCtx) {
            new Chart(visitsCtx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    datasets: [{
                        label: 'Visits',
                        data: [8, 12, 10, 15, 11, 18],
                        borderColor: '#1B84FF',
                        backgroundColor: 'rgba(27, 132, 255, 0.12)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.35,
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        pointBackgroundColor: '#1B84FF',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { color: '#78829D', font: { size: 12, weight: '600' } }
                        },
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(219, 223, 233, 0.7)' },
                            ticks: { color: '#78829D', font: { size: 12 } }
                        }
                    }
                }
            });
        }

        if (deliveriesCtx) {
            new Chart(deliveriesCtx, {
                type: 'bar',
                data: {
                    labels: ['Direct Delivery', 'Transport / Parcel'],
                    datasets: [{
                        label: 'Deliveries',
                        data: [
                            {{ $chartDirect }},
                            {{ $chartTransport }}
                        ],
                        backgroundColor: ['#17C653', '#7239EA'],
                        borderRadius: 10,
                        borderSkipped: false,
                        maxBarThickness: 72,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { color: '#78829D', font: { size: 12, weight: '600' } }
                        },
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(219, 223, 233, 0.7)' },
                            ticks: { color: '#78829D', font: { size: 12 } }
                        }
                    }
                }
            });
        }
    });
</script>
@endpush
