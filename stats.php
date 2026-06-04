<?php
/**
 * Statistics Dashboard
 * Animated counters, charts, and development metrics
 */
require_once 'config/database.php';
$pageTitle = 'Statistics';
$pageDescription = 'Development statistics and metrics for RobiCodes portfolio - projects, contributions, experience, and more.';
include 'includes/header.php';

$stats = dbGetAll("SELECT * FROM statistics ORDER BY sort_order ASC");
?>

<section class="section hero" style="min-height:auto;padding-top:8rem" aria-label="Statistics">
    <div class="container">
        <div class="section__header reveal">
            <h2 class="section__title">Development Statistics</h2>
            <p class="section__subtitle">Numbers that reflect my journey and growth as a developer</p>
        </div>

        <!-- Stats Grid -->
        <div class="stats__grid" style="margin-bottom:var(--spacing-3xl)">
            <?php foreach ($stats as $stat): ?>
            <div class="stat-card glass-card reveal">
                <div class="stat-card__icon">
                    <i class="fas fa-chart-bar"></i>
                </div>
                <div class="stat-card__value" 
                     data-target="<?php echo (int)$stat['value']; ?>" 
                     data-suffix="<?php echo sanitizeOutput($stat['suffix']); ?>">
                    0<?php echo sanitizeOutput($stat['suffix']); ?>
                </div>
                <p class="stat-card__label"><?php echo sanitizeOutput($stat['label']); ?></p>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Charts Row -->
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:var(--spacing-lg);margin-bottom:var(--spacing-3xl)">
            <div class="glass-card reveal">
                <h3 style="margin-bottom:var(--spacing-lg);font-size:var(--font-size-lg)">Technology Distribution</h3>
                <canvas id="techChart" height="250" aria-label="Technology distribution chart"></canvas>
            </div>
            <div class="glass-card reveal reveal-delay-1">
                <h3 style="margin-bottom:var(--spacing-lg);font-size:var(--font-size-lg)">Project Categories</h3>
                <canvas id="categoryChart" height="250" aria-label="Project categories chart"></canvas>
            </div>
        </div>

        <!-- Additional Stats -->
        <div class="glass-card reveal">
            <h3 style="margin-bottom:var(--spacing-lg);font-size:var(--font-size-lg)">Development Activity</h3>
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:var(--spacing-lg)">
                <div style="text-align:center;padding:var(--spacing-md)">
                    <div style="font-size:var(--font-size-3xl);font-weight:800;background:var(--gradient-primary);-webkit-background-clip:text;background-clip:text;-webkit-text-fill-color:transparent">12</div>
                    <p style="font-size:var(--font-size-sm);color:var(--text-muted);margin:0">PHP Projects</p>
                </div>
                <div style="text-align:center;padding:var(--spacing-md)">
                    <div style="font-size:var(--font-size-3xl);font-weight:800;background:var(--gradient-primary);-webkit-background-clip:text;background-clip:text;-webkit-text-fill-color:transparent">5</div>
                    <p style="font-size:var(--font-size-sm);color:var(--text-muted);margin:0">JavaScript Projects</p>
                </div>
                <div style="text-align:center;padding:var(--spacing-md)">
                    <div style="font-size:var(--font-size-3xl);font-weight:800;background:var(--gradient-primary);-webkit-background-clip:text;background-clip:text;-webkit-text-fill-color:transparent">3</div>
                    <p style="font-size:var(--font-size-sm);color:var(--text-muted);margin:0">Android Apps</p>
                </div>
                <div style="text-align:center;padding:var(--spacing-md)">
                    <div style="font-size:var(--font-size-3xl);font-weight:800;background:var(--gradient-primary);-webkit-background-clip:text;background-clip:text;-webkit-text-fill-color:transparent">5</div>
                    <p style="font-size:var(--font-size-sm);color:var(--text-muted);margin:0">API Services</p>
                </div>
                <div style="text-align:center;padding:var(--spacing-md)">
                    <div style="font-size:var(--font-size-3xl);font-weight:800;background:var(--gradient-primary);-webkit-background-clip:text;background-clip:text;-webkit-text-fill-color:transparent">8</div>
                    <p style="font-size:var(--font-size-sm);color:var(--text-muted);margin:0">Open Source Contributions</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js" defer></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof Chart !== 'undefined') {
        // Technology Distribution Chart
        const techCtx = document.getElementById('techChart');
        if (techCtx) {
            new Chart(techCtx, {
                type: 'doughnut',
                data: {
                    labels: ['PHP', 'JavaScript', 'MySQL', 'HTML/CSS', 'Java', 'Python', 'Other'],
                    datasets: [{
                        data: [35, 20, 15, 12, 8, 5, 5],
                        backgroundColor: [
                            '#6c5ce7', '#fdcb6e', '#00cec9', '#e17055', '#00b894', '#0984e3', '#636e72'
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { color: '#b0b0d0', padding: 12, usePointStyle: true }
                        }
                    }
                }
            });
        }

        // Category Chart
        const catCtx = document.getElementById('categoryChart');
        if (catCtx) {
            new Chart(catCtx, {
                type: 'bar',
                data: {
                    labels: ['Web Apps', 'APIs', 'Mobile', 'Tools', 'Automation'],
                    datasets: [{
                        label: 'Projects',
                        data: [8, 4, 3, 6, 4],
                        backgroundColor: ['#6c5ce7', '#00cec9', '#fd79a8', '#fdcb6e', '#55efc4'],
                        borderRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        x: {
                            ticks: { color: '#b0b0d0' },
                            grid: { display: false }
                        },
                        y: {
                            ticks: { color: '#b0b0d0' },
                            grid: { color: 'rgba(255,255,255,0.05)' }
                        }
                    }
                }
            });
        }
    }
});
</script>

<?php include 'includes/footer.php'; ?>
