<?= $this->extend('pages/admin/layout/template') ?>
<?= $this->section('content') ?>

<?php
// ===== Prepare raw chart data (year => month => total)
$rawChartData = [];

foreach ($chartData as $row) {
    $year  = (int) $row['year'];
    $month = (int) $row['month'];

    if (!isset($rawChartData[$year])) {
        $rawChartData[$year] = array_fill(1, 12, 0);
    }
    $rawChartData[$year][$month] = (int) $row['total'];
}

$currentYear = (int) date('Y');
?>

<?php if (session()->getFlashdata('errors')): ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php foreach (session()->getFlashdata('errors') as $field => $error): ?>
                <li><?= esc($field) ?>: <?= esc(is_array($error) ? json_encode($error) : $error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<div class="container py-4">

    <!-- ===== HEADER ===== -->
    <div class="row g-4 mb-4">

      <!-- Active Members -->
      <div class="col-xl-3 col-md-6">
          <div class="card kpi-card kpi-green">
              <div class="card-body">
                  <div class="kpi-icon">
                      <i class="bi bi-person-check-fill"></i>
                  </div>
                  <small>Active Members</small>
                  <h3 data-testid="active-members"><?= $activeMembers ?></h3>
                  <small>Active today</small>
              </div>
          </div>
      </div>

      <!-- New Members -->
      <div class="col-xl-3 col-md-6">
          <div class="card kpi-card kpi-blue">
              <div class="card-body">
                  <div class="kpi-icon">
                      <i class="bi bi-person-plus-fill"></i>
                  </div>
                  <small>New Members</small>
                  <h3 data-testid="new-members"><?= $newMembers ?></h3>
                  <small>This month</small>
              </div>
          </div>
      </div>

      <!-- Inactive Members -->
      <div class="col-xl-3 col-md-6">
          <div class="card kpi-card kpi-orange">
              <div class="card-body">
                  <div class="kpi-icon">
                      <i class="bi bi-person-dash-fill"></i>
                  </div>
                  <small>Inactive Members</small>
                  <h3 data-testid="inactive-members"><?= $inactiveMembersThisMonth ?></h3>
                  <small>Expired this month</small>
              </div>
          </div>
      </div>

      <!-- Total Members -->
      <div class="col-xl-3 col-md-6">
          <div class="card kpi-card kpi-red">
              <div class="card-body">
                  <div class="kpi-icon">
                      <i class="bi bi-people-fill"></i>
                  </div>
                  <small>Total Members</small>
                  <h3 data-testid="total-members"><?= $totalMembers ?></h3>
                  <small>All time</small>
              </div>
          </div>
      </div>

  </div>

    <!-- ===== CHART ===== -->
    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h6 class="mb-0">
                <i class="bi bi-graph-up me-1"></i>
                New Members Growth
            </h6>

            <select id="yearFilter" class="form-select form-select-sm" style="width:180px">
                <option value="all">All the time</option>
                <option value="current">Current Year</option>
            </select>
        </div>

        <div class="card-body">
            <p class="text-muted small mb-3">
                Number of new memberships based on start date
            </p>

            <div style="height:300px">
                <canvas id="membersChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- ===== PASS DATA TO JS ===== -->
<script>
window.RAW_CHART_DATA = <?= json_encode($rawChartData) ?>;
window.CURRENT_YEAR  = <?= $currentYear ?>;
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const canvas = document.getElementById('membersChart');
    if (!canvas || typeof Chart === 'undefined') return;

    const ctx = canvas.getContext('2d');
    const yearFilter = document.getElementById('yearFilter');

    const MONTHS = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
    const RAW = window.RAW_CHART_DATA;
    const CURRENT_YEAR = window.CURRENT_YEAR;

    // ===== Populate dropdown: previous years only (exclude current year)
    Object.keys(RAW)
        .map(y => parseInt(y))
        .filter(y => y < CURRENT_YEAR)
        .sort((a,b) => b - a)
        .slice(0,5)
        .forEach(year => {
            const opt = document.createElement('option');
            opt.value = year;
            opt.textContent = year;
            yearFilter.appendChild(opt);
        });

    function buildData(filter) {

        // All time → aggregate per year
        if (filter === 'all') {
            const years = Object.keys(RAW).sort();
            return {
                labels: years,
                values: years.map(y =>
                    Object.values(RAW[y]).reduce((a,b) => a+b, 0)
                )
            };
        }

        // Current year OR selected year
        const year = (filter === 'current') ? CURRENT_YEAR : filter;

        return {
            labels: MONTHS,
            values: RAW[year] ? Object.values(RAW[year]) : Array(12).fill(0)
        };
    }

    const chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
                data: [],
                borderColor: '#0d6efd',
                backgroundColor: 'rgba(13,110,253,.25)',
                fill: true,
                tension: 0.35,
                pointRadius: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { precision: 0 }
                }
            }
        }
    });

    function updateChart(filter) {
        const { labels, values } = buildData(filter);
        chart.data.labels = labels;
        chart.data.datasets[0].data = values;
        chart.options.scales.y.suggestedMax = Math.max(...values, 3);
        chart.update();
    }

    // Default
    updateChart('all');

    yearFilter.addEventListener('change', e => {
        updateChart(e.target.value);
    });
});
</script>

<?= $this->endSection() ?>