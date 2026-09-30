

<?php if (!empty($success)): ?><div style="padding:15px; background:rgba(212,175,55,0.1); color:#d4af37; margin-bottom:20px; border-left: 4px solid #d4af37;"><?= htmlspecialchars($success) ?></div><?php endif; ?>

<div class="card">
    <h3>Новая задача / Встреча</h3>
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

<div class="card">
    <h3>Список задач</h3>
    <table>
        <tr>
            <th>Дедлайн</th>
            <th>Задача</th>
            <th>Ответственный</th>
            <th>Статус</th>
            <th>Действие</th>
        </tr>
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
