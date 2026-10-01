<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px; }
    .stat-card { background: #1c1c1c; padding: 25px; border-radius: 6px; border-top: 3px solid #d4af37; box-shadow: 0 5px 15px rgba(0,0,0,0.5); text-align: center; }
    .stat-card h4 { margin: 0; color: #888; text-transform: uppercase; font-size: 13px; font-weight: normal; }
    .stat-card .value { font-size: 32px; color: #d4af37; font-weight: bold; margin: 10px 0; }
    .charts-grid { display: grid; grid-template-columns: 1fr 2fr; gap: 20px; }
    .chart-container { background: #1c1c1c; padding: 20px; border-radius: 6px; border: 1px solid #2a2a2a; }
    .dash-table { width: 100%; border-collapse: collapse; font-size: 13px; margin-top: 10px; }
    .dash-table th { text-align: left; padding: 8px; border-bottom: 1px solid #333; color: #888; }
    .dash-table td { padding: 8px; border-bottom: 1px solid #2a2a2a; color: #fff; }
</style>

<div class="stats-grid">
    <div class="stat-card"><h4>Всего сделок</h4><div class="value"><?= htmlspecialchars($stats['total_deals'] ?? 0) ?></div></div>
    <div class="stat-card"><h4>Конверсия в успех</h4><div class="value"><?= $conversion ?>%</div></div>
    <div class="stat-card"><h4>Общая выручка</h4><div class="value"><?= number_format($stats['total_revenue'] ?? 0, 0, ',', ' ') ?> ₽</div></div>
</div>

<div class="charts-grid">
    <div class="chart-container">
        <h3 style="color: #d4af37; margin-top:0; text-align:center;">Статусы сделок</h3>
        <canvas id="statusChart"></canvas>
    </div>
    
    <div class="chart-container" style="overflow-y: auto; max-height: 400px; display: flex; flex-direction: column; gap: 20px;">
        
        <div>
            <h3 style="color: #d4af37; margin:0 0 10px 0;">Текущие сделки (В работе)</h3>
            <?php if (empty($activeDeals)): ?>
                <p style="color:#888; font-size:13px;">Нет активных сделок.</p>
            <?php else: ?>
                <table class="dash-table">
                    <tr><th>Название</th><th>Клиент</th><th>Сумма</th></tr>
                    <?php foreach($activeDeals as $ad): ?>
                        <tr>
                            <td><b><?= htmlspecialchars($ad['title']) ?></b></td>
                            <td><?= htmlspecialchars($ad['first_name'] . ' ' . $ad['last_name']) ?></td>
                            <td style="color: #4CAF50;"><?= number_format($ad['amount'], 0, ',', ' ') ?> ₽</td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php endif; ?>
        </div>

        <div>
            <h3 style="color: #d4af37; margin:0 0 10px 0;">Предстоящие встречи</h3>
            <?php if (empty($upcomingMeetings)): ?>
                <p style="color:#888; font-size:13px;">Встреч не запланировано.</p>
            <?php else: ?>
                <table class="dash-table">
                    <tr><th>Дата</th><th>Тема</th><th>Клиент</th><th>Менеджер</th></tr>
                    <?php foreach($upcomingMeetings as $um): ?>
                        <tr>
                            <td style="color: #007bff;"><?= date('d.m.Y H:i', strtotime($um['meeting_date'])) ?></td>
                            <td><b><?= htmlspecialchars($um['title']) ?></b></td>
                            <td>
                                <?php if ($um['first_name']): ?>
                                    <?= htmlspecialchars($um['first_name'] . ' ' . $um['last_name']) ?>
                                <?php else: ?>
                                    <span style="color: #ff6b6b; font-style: italic;">Клиент удален</span>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($um['user_name']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    const dataNew = <?= $stats['new_deals'] ?? 0 ?>;
    const dataProgress = <?= $stats['in_progress_deals'] ?? 0 ?>;
    const dataClosed = <?= $stats['closed_deals'] ?? 0 ?>;

    new Chart(document.getElementById('statusChart'), {
        type: 'doughnut',
        data: {
            labels: ['Новые', 'В работе', 'Успешные'],
            datasets: [{
                data: [dataNew, dataProgress, dataClosed],
                backgroundColor: ['#007bff', '#d4af37', '#4CAF50'],
                borderWidth: 0
            }]
        },
        options: { plugins: { legend: { labels: { color: '#e0e0e0' } } } }
    });
</script>