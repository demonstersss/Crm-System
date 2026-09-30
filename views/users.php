<?php /** @var array $usersList */ ?>

<?php if (!empty($error)): ?><div style="padding:15px; background:rgba(204,51,51,0.1); color:#ff6b6b; margin-bottom:20px; border-left: 4px solid #cc3333;"><?= htmlspecialchars($error) ?></div><?php endif; ?>
<?php if (!empty($success)): ?><div style="padding:15px; background:rgba(212,175,55,0.1); color:#d4af37; margin-bottom:20px; border-left: 4px solid #d4af37;"><?= htmlspecialchars($success) ?></div><?php endif; ?>

<div class="card">
    <h3>Регистрация нового сотрудника</h3>
    <form method="POST" action="index.php?action=users" class="form-row">
        <input type="text" name="name" class="form-control" placeholder="ФИО сотрудника" required style="margin: 0; width: 30%;">
        <input type="email" name="email" class="form-control" placeholder="Рабочий Email" required style="margin: 0; width: 25%;">
        <input type="password" name="password" class="form-control" placeholder="Пароль" required style="margin: 0; width: 20%;">
        
        <select name="role" class="form-control" style="margin: 0; width: 15%;">
            <option value="manager">Менеджер</option>
            <option value="admin">Администратор</option>
        </select>
        
        <button type="submit" name="add_user" class="btn" style="width: 10%;">Создать</button>
    </form>
</div>

<div class="card">
    <h3>Список сотрудников системы</h3>
    <table>
        <tr>
            <th>ФИО</th>
            <th>Email</th>
            <th>Статус</th>
            <th>Смена Роли</th>
            <th>Действие</th>
        </tr>
        <?php foreach ($usersList as $u): ?>
            <tr style="<?= $u['status'] === 'inactive' ? 'opacity: 0.5;' : '' ?>">
                <td><b><?= htmlspecialchars($u['name']) ?></b></td>
                <td><?= htmlspecialchars($u['email']) ?></td>
                <td>
                    <?php if ($u['status'] === 'active'): ?>
                        <span style="color: #4CAF50; font-size:12px; border:1px solid #4CAF50; padding:2px 5px; border-radius:3px;">Активен</span>
                    <?php else: ?>
                        <span style="color: #ff6b6b; font-size:12px; border:1px solid #ff6b6b; padding:2px 5px; border-radius:3px;">Отключен</span>
                    <?php endif; ?>
                </td>
                
                <td>
                    <form method="POST" action="index.php?action=users" style="display:flex; gap:5px;">
                        <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                        <select name="role" class="form-control" style="margin:0; padding:4px; font-size:12px;">
                            <option value="manager" <?= $u['role'] === 'manager' ? 'selected' : '' ?>>Менеджер</option>
                            <option value="admin" <?= $u['role'] === 'admin' ? 'selected' : '' ?>>Админ</option>
                        </select>
                        <button type="submit" name="change_role" class="btn" style="padding:4px 8px; font-size:12px;">ОК</button>
                    </form>
                </td>

                <td>
                    <form method="POST" action="index.php?action=users">
                        <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                        <?php if ($u['status'] === 'active'): ?>
                            <button type="submit" name="toggle_status" class="btn btn-danger" style="padding:4px 8px; font-size:12px;">Деактивировать</button>
                        <?php else: ?>
                            <button type="submit" name="toggle_status" class="btn" style="padding:4px 8px; font-size:12px; background:#4CAF50;">Включить</button>
                        <?php endif; ?>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>