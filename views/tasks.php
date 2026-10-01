<?php if (!empty($success)): ?><div style="padding:15px; background:rgba(212,175,55,0.1); color:#d4af37; margin-bottom:20px; border-left: 4px solid #d4af37;"><?= htmlspecialchars($success) ?></div><?php endif; ?>

<div class="card">
    <h3>Новая задача</h3>
    <form method="POST" action="index.php?action=tasks" class="form-row">
        <input type="text" name="title" class="form-control" placeholder="Что нужно сделать?" required style="margin: 0; width: 30%;">
        <input type="text" name="description" class="form-control" placeholder="Детали..." style="margin: 0; width: 30%;">
        <input type="datetime-local" name="due_date" class="form-control" required style="margin: 0; width: 20%;">
        
        <?php if (User::isAdmin()): ?>
            <select name="assigned_to" class="form-control" style="margin: 0; width: 10%;">
                <?php foreach($usersForAssign as $u): ?>
                    <option value="<?= $u['id'] ?>"><?= htmlspecialchars($u['name']) ?></option>
                <?php endforeach; ?>
            </select>
        <?php else: ?>
            <input type="hidden" name="assigned_to" value="<?= $_SESSION['user_id'] ?>">
        <?php endif; ?>
        
        <button type="submit" name="add_task" class="btn" style="width: 10%;">Добавить</button>
    </form>
</div>

<div class="card" style="margin-bottom: 40px;">
    <h3>Список задач</h3>
    <table>
        <tr><th>Дедлайн</th><th>Задача</th><th>Ответственный</th><th>Статус</th><th>Действие</th></tr>
        <?php foreach ($tasks as $task): ?>
            <tr style="<?= $task['status'] === 'completed' ? 'opacity: 0.5; text-decoration: line-through;' : '' ?>">
                <td style="color: <?= (strtotime($task['due_date']) < time() && $task['status'] !== 'completed') ? '#ff6b6b' : '#fff' ?>;">
                    <?= date('d.m.Y H:i', strtotime($task['due_date'])) ?>
                </td>
                <td>
                    <b><?= htmlspecialchars($task['title']) ?></b><br>
                    <span style="font-size: 12px; color: #888;"><?= htmlspecialchars($task['description']) ?></span>
                </td>
                <td><?= htmlspecialchars($task['assigned_name']) ?></td>
                <td><?= $task['status'] === 'completed' ? 'Выполнена' : 'Ожидает' ?></td>
                <td>
                    <?php if ($task['status'] !== 'completed'): ?>
                        <form method="POST" action="index.php?action=tasks">
                            <input type="hidden" name="task_id" value="<?= $task['id'] ?>">
                            <button type="submit" name="complete_task" class="btn" style="padding: 5px 10px; font-size: 11px;">Готово</button>
                        </form>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>

<div class="card" style="border-top: 3px solid #007bff;">
    <h3>Назначить встречу с клиентом</h3>
    <form method="POST" action="index.php?action=tasks" class="form-row">
        <input type="text" name="title" class="form-control" placeholder="Тема встречи (напр. Презентация)" required style="margin: 0; width: 25%;">
        
        <select name="contact_id" class="form-control" required style="margin: 0; width: 30%;">
            <option value="">Выберите клиента...</option>
            <?php foreach($allContacts as $c): ?>
                <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['first_name'] . ' ' . $c['last_name']) ?></option>
            <?php endforeach; ?>
        </select>

        <input type="datetime-local" name="meeting_date" class="form-control" required style="margin: 0; width: 20%;">
        
        
        
        <button type="submit" name="add_meeting" class="btn" style="width: 10%; background: #007bff;">Назначить</button>
    </form>
</div>

<div class="card">
    <h3>Календарь встреч</h3>
    <table>
        <tr><th>Дата и время</th><th>Тема</th><th>Клиент</th><th>Ответственный менеджер</th><th>Действие</th></tr>
        <?php foreach ($meetings as $meeting): ?>
            <tr>
                <td style="color: <?= (strtotime($meeting['meeting_date']) < time()) ? '#ff6b6b' : '#fff' ?>;">
                    <?= date('d.m.Y H:i', strtotime($meeting['meeting_date'])) ?>
                </td>
                <td><b><?= htmlspecialchars($meeting['title']) ?></b></td>
                <td>
                    <?php if ($meeting['first_name']): ?>
                        <?= htmlspecialchars($meeting['first_name'] . ' ' . $meeting['last_name']) ?>
                    <?php else: ?>
                        <span style="color: #ff6b6b; font-style: italic;">Клиент удален</span>
                    <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($meeting['user_name']) ?></td>
                <td>
                    <?php if (strtotime($meeting['meeting_date']) < time()): ?>
                        <span style="font-size: 11px; color: #888; font-style: italic;">Завершена</span>
                    <?php elseif (User::isAdmin() || $meeting['user_id'] == $_SESSION['user_id']): ?>
                        <form method="POST" action="index.php?action=tasks" onsubmit="return confirm('Отменить запланированную встречу?');" style="margin: 0;">
                            <input type="hidden" name="meeting_id" value="<?= $meeting['id'] ?>">
                            <button type="submit" name="delete_meeting" class="btn btn-danger" style="padding: 5px 10px; font-size: 11px;">Отменить</button>
                        </form>
                    <?php else: ?>
                        <span style="font-size: 11px; color: #888;">Нет прав</span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>