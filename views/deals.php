<?php 
if (!empty($success)): ?>
    <div style="padding:15px; background:rgba(212,175,55,0.1); color:#d4af37; margin-bottom:20px; border-left: 4px solid #d4af37;"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<div class="card">
    <h3>Создать сделку</h3>
    <form method="POST" action="index.php?action=deals" class="form-row">
        <select name="contact_id" class="form-control" required style="margin: 0; width: 30%;">
            <option value="">Выберите клиента...</option>
            <?php foreach($allContacts as $c): ?>
                <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['first_name'] . ' ' . $c['last_name']) ?></option>
            <?php endforeach; ?>
        </select>
        <input type="text" name="title" class="form-control" placeholder="Название сделки (напр. Разработка сайта)" required style="margin: 0; width: 40%;">
        <input type="number" step="0.01" name="amount" class="form-control" placeholder="Сумма (₽)" style="margin: 0; width: 20%;">
        <button type="submit" name="add_deal" class="btn" style="width: 10%;">Создать</button>
    </form>
</div>

<?php foreach ($deals as $deal): ?>
    <div class="card" style="border-left: 4px solid <?= $deal['status'] === 'closed' ? '#4CAF50' : ($deal['status'] === 'in_progress' ? '#d4af37' : '#007bff') ?>;">
        <div style="display: flex; justify-content: space-between;">
            <div>
                <h3 style="margin-bottom: 5px;"><?= htmlspecialchars($deal['title']) ?> - <?= number_format($deal['amount'], 2, ',', ' ') ?> ₽</h3>
                <p style="margin: 0 0 15px; font-size: 14px; color: #888;">
                    Клиент: 
                    <?php if ($deal['first_name']): ?>
                        <?= htmlspecialchars($deal['first_name'] . ' ' . $deal['last_name']) ?>
                    <?php else: ?>
                        <span style="color: #ff6b6b; font-style: italic;">Пользователь удален</span>
                    <?php endif; ?> 
                    | Менеджер: <?= htmlspecialchars($deal['manager_name']) ?>
                </p>            
            </div>
            
            <div style="display: flex; gap: 10px; align-items: flex-start;">
                <form method="POST" action="index.php?action=deals" style="display:flex; gap:5px;">
                    <input type="hidden" name="deal_id" value="<?= $deal['id'] ?>">
                    <select name="status" class="form-control" style="margin:0; padding:6px; font-size:13px;">
                        <option value="new" <?= $deal['status'] == 'new' ? 'selected' : '' ?>>Новая</option>
                        <option value="in_progress" <?= $deal['status'] == 'in_progress' ? 'selected' : '' ?>>В работе</option>
                        <option value="closed" <?= $deal['status'] == 'closed' ? 'selected' : '' ?>>Закрыта</option>
                    </select>
                    <button type="submit" name="change_status" class="btn" style="padding: 6px 12px; font-size: 12px;">ОК</button>
                </form>
                
                <?php if (User::isAdmin()): ?>
                    <form method="POST" action="index.php?action=deals" onsubmit="return confirm('Точно удалить сделку?');">
                        <input type="hidden" name="deal_id" value="<?= $deal['id'] ?>">
                        <button type="submit" name="delete_deal" class="btn btn-danger" style="padding: 6px 12px; font-size: 12px;">Удалить</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>

        <div style="background: #111; padding: 15px; border-radius: 4px; margin-bottom: 15px; max-height: 200px; overflow-y: auto;">
            <?php foreach ($deal['notes'] as $note): ?>
                <div style="margin-bottom: 10px; font-size: 14px;">
                    <span style="color: <?= $note['type'] === 'history_log' ? '#888' : '#d4af37' ?>;">
                        <b><?= htmlspecialchars($note['user_name']) ?></b> (<?= date('d.m.Y H:i', strtotime($note['created_at'])) ?>): 
                    </span>
                    <span style="color: <?= $note['type'] === 'history_log' ? '#aaa' : '#fff' ?>; <?= $note['type'] === 'history_log' ? 'font-style: italic;' : '' ?>">
                        <?= htmlspecialchars($note['content']) ?>
                    </span>
                </div>
            <?php endforeach; ?>
        </div>

        <form method="POST" action="index.php?action=deals" style="display: flex; gap: 10px;">
            <input type="hidden" name="deal_id" value="<?= $deal['id'] ?>">
            <input type="text" name="content" class="form-control" placeholder="Написать комментарий..." required style="margin: 0; width: 85%;">
            <button type="submit" name="add_comment" class="btn" style="width: 15%;">Отправить</button>
        </form>
    </div>
<?php endforeach; ?>