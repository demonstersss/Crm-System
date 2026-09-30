<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px; }
    .stat-card { background: #1c1c1c; padding: 25px; border-radius: 6px; border-top: 3px solid #d4af37; box-shadow: 0 5px 15px rgba(0,0,0,0.5); text-align: center; }
    .stat-card h4 { margin: 0; color: #888; text-transform: uppercase; font-size: 13px; font-weight: normal; }
    .stat-card .value { font-size: 32px; color: #d4af37; font-weight: bold; margin: 10px 0; }
    .charts-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    .chart-container { background: #1c1c1c; padding: 20px; border-radius: 6px; border: 1px solid #2a2a2a; }
</style>

<div class="stats-grid">
    <div class="stat-card">
        <h4>Всего сделок</h4>
        <div class="value"><?= htmlspecialchars($stats['total_deals'] ?? 0) ?></div>
    </div>
    <div class="stat-card">
        <h4>Конверсия в успех</h4>
        <div class="value"><?= $conversion ?>%</div>
    </div>
    <div class="stat-card">
        <h4>Общая выручка</h4>
        <div class="value"><?= number_format($stats['total_revenue'] ?? 0, 0, ',', ' ') ?> ₽</div>
    </div>
</div>

<div class="charts-grid">
    <div class="chart-container">
        <h3 style="color: #d4af37; margin-top:0; text-align:center;">Статусы сделок</h3>
        <canvas id="statusChart"></canvas>
    </div>
    
    <div class="chart-container">
        <h3 style="color: #d4af37; margin-top:0; text-align:center;">Воронка продаж</h3>
        <canvas id="funnelChart"></canvas>
    </div>
</div>

<script>
    const dataNew = <?= $stats['new_deals'] ?? 0 ?>;
    const dataProgress = <?= $stats['in_progress_deals'] ?? 0 ?>;
    const dataClosed = <?= $stats['closed_deals'] ?? 0 ?>;

    new Chart(document.getElementById('statusChart'), {
        type: 'doughnut',
        data: {
            labels: ['Новые', 'В работе', 'Закрытые (Успех)'],
            datasets: [{
                data: [dataNew, dataProgress, dataClosed],
                backgroundColor: ['#007bff', '#d4af37', '#4CAF50'],
                borderWidth: 0
            }]
        },
        options: {
            plugins: { legend: { labels: { color: '#e0e0e0' } } }
        }
    });

    new Chart(document.getElementById('funnelChart'), {
        type: 'bar',
        data: {
            labels: ['Новые (Лиды)', 'В переговорах', 'Закрытые'],
            datasets: [{
                label: 'Количество сделок',
                data: [dataNew, dataProgress, dataClosed],
                backgroundColor: 'rgba(212, 175, 55, 0.7)',
                borderColor: '#d4af37',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1, color: '#888' }, grid: { color: '#333' } },
                x: { ticks: { color: '#e0e0e0' }, grid: { display: false } }
            },
            plugins: { legend: { display: false } }
        }
    });
</script>