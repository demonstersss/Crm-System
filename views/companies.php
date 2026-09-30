<?php if (!empty($error)): ?>
    <div style="padding:15px; background:rgba(204,51,51,0.1); color:#ff6b6b; margin-bottom:20px; border-left: 4px solid #cc3333;">
        <?= htmlspecialchars($error) ?>
    </div>
<?php endif; ?>

<?php if (!empty($success)): ?>
    <div style="padding:15px; background:rgba(212,175,55,0.1); color:#d4af37; margin-bottom:20px; border-left: 4px solid #d4af37;">
        <?= htmlspecialchars($success) ?>
    </div>
<?php endif; ?>

<div class="card">
    <h3>Новая компания</h3>
    <form method="POST" action="index.php?action=companies" class="form-row">
        <input type="text" name="name" class="form-control" placeholder="Название компании" required style="margin: 0; width: 40%;">
        <input type="text" name="address" class="form-control" placeholder="Физический адрес" style="margin: 0; width: 40%;">
        <button type="submit" name="add_company" class="btn" style="width: 20%;">Добавить</button>
    </form>
</div>

<?php foreach ($companies as $company): ?>
    <div class="card">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h3 style="margin: 0;">
                <?= htmlspecialchars($company['name']) ?> 
                <span style="font-size:14px; color:#777; font-weight:normal;">(<?= htmlspecialchars($company['address']) ?>)</span>
            </h3>
            
            <?php if ($_SESSION['role'] === 'admin'): ?>
                <form method="POST" action="index.php?action=companies" onsubmit="return confirm('Удалить компанию и все связанные данные?');">
                    <input type="hidden" name="company_id" value="<?= $company['id'] ?>">
                    <button type="submit" name="delete_company" class="btn btn-danger" style="padding: 6px 12px; font-size: 12px;">Удалить</button>
                </form>
            <?php endif; ?>
        </div>
        
        <?php if (empty($company['contacts'])): ?>
            <p style="color: #666; font-size: 14px; margin-top: 15px;">У этой компании пока нет представителей.</p>
        <?php else: ?>
            <table>
                <tr>
                    <th>ФИО</th>
                    <th>Телефон</th>
                    <th>Email</th>
                    <?php if (User::isAdmin()): ?><th>Действие</th><?php endif; ?>
                </tr>
                <?php foreach ($company['contacts'] as $contact): ?>
                    <tr>
                        <td><?= htmlspecialchars($contact['first_name'] . ' ' . $contact['last_name']) ?></td>
                        <td><?= htmlspecialchars($contact['phone']) ?></td>
                        <td><?= htmlspecialchars($contact['email']) ?></td>
                        
                        <?php if (User::isAdmin()): ?>
                        <td>
                            <form method="POST" action="index.php?action=companies" onsubmit="return confirm('Удалить контакт? Привязанные сделки останутся в системе.');" style="margin:0;">
                                <input type="hidden" name="contact_id" value="<?= $contact['id'] ?>">
                                <button type="submit" name="delete_contact" class="btn btn-danger" style="padding: 4px 8px; font-size: 11px;">Удалить</button>
                            </form>
                        </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>

        <div style="margin-top: 20px; padding-top: 20px; border-top: 1px dashed #333;">
            <form method="POST" action="index.php?action=companies" class="form-row">
                <input type="hidden" name="company_id" value="<?= $company['id'] ?>">
                <input type="text" name="first_name" class="form-control" placeholder="Имя" required style="margin:0;">
                <input type="text" name="last_name" class="form-control" placeholder="Фамилия" style="margin:0;">
                <input type="text" name="phone" class="form-control" placeholder="Телефон" style="margin:0;">
                <input type="email" name="email" class="form-control" placeholder="Email" style="margin:0;">
                <button type="submit" name="add_contact" class="btn" style="white-space: nowrap;">+ Контакт</button>
            </form>
        </div>
    </div>
<?php endforeach; ?>