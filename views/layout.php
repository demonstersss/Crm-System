<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'CRM System' ?></title>
    <style>
        body { margin: 0; font-family: 'Segoe UI', Tahoma, sans-serif; background: #0f0f0f; color: #e0e0e0; }
        .navbar { background: #1c1c1c; padding: 15px 30px; border-bottom: 2px solid #d4af37; display: flex; justify-content: space-between; align-items: center; }
        .navbar-brand { color: #d4af37; font-size: 20px; font-weight: bold; text-decoration: none; text-transform: uppercase; letter-spacing: 1px; }
        .navbar-menu a { color: #fff; text-decoration: none; margin-left: 20px; font-size: 15px; transition: color 0.3s; }
        .navbar-menu a:hover { color: #d4af37; }
        .user-info { color: #888; font-size: 14px; margin-right: 20px; border-right: 1px solid #444; padding-right: 20px; }
        
        .container { padding: 30px; max-width: 1200px; margin: 0 auto; }
        .card { background: #1c1c1c; border: 1px solid #2a2a2a; border-radius: 6px; padding: 25px; margin-bottom: 20px; box-shadow: 0 5px 15px rgba(0,0,0,0.5); }
        .card h3 { color: #d4af37; margin-top: 0; font-weight: 500; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #333; }
        th { color: #d4af37; font-weight: normal; text-transform: uppercase; font-size: 12px; letter-spacing: 1px;}
        
        .btn { background: linear-gradient(135deg, #d4af37 0%, #aa8c2c 100%); color: #111; padding: 9px 18px; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; text-decoration: none; display: inline-block; transition: 0.2s; }
        .btn:hover { box-shadow: 0 4px 15px rgba(212, 175, 55, 0.4); transform: translateY(-1px); }
        .btn-danger { background: #cc3333; color: #fff; }
        .btn-danger:hover { box-shadow: 0 4px 15px rgba(204, 51, 51, 0.4); }
        
        .form-control { width: 100%; padding: 10px; margin: 5px 0 15px; background: #2a2a2a; border: 1px solid #444; color: #fff; border-radius: 4px; box-sizing: border-box; transition: 0.3s; }
        .form-control:focus { border-color: #d4af37; outline: none; }
        .form-row { display: flex; gap: 15px; align-items: flex-start; }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="index.php?action=dashboard" class="navbar-brand">CRM System</a>
        <div class="navbar-menu">
            <span class="user-info"><?= htmlspecialchars($_SESSION['user_name']) ?> (<?= htmlspecialchars($_SESSION['role']) ?>)</span>
            
            <?php if (User::isAdmin()): ?>
                <a href="index.php?action=users">Сотрудники</a>
            <?php endif; ?>
            
            <a href="index.php?action=companies">Компании и Контакты</a>
            <a href="index.php?action=deals">Сделки</a>
            <a href="index.php?action=tasks">Задачи</a>
            <a href="index.php?action=logout" style="color: #cc3333;">Выйти</a>
        </div>
    </div>
    <div class="container">
        <?php require_once $content; ?>
    </div>
</body>
</html>