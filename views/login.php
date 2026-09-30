<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход в CRM</title>
    <style>
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            height: 100vh; 
            background: #0f0f0f; 
            color: #e0e0e0;
            margin: 0;
        }
        .login-box { 
            background: #1c1c1c; 
            padding: 40px 30px; 
            border-radius: 8px; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.8); 
            border-top: 3px solid #d4af37; 
            width: 100%; 
            max-width: 340px; 
        }
        .login-box h2 { 
            margin-top: 0; 
            margin-bottom: 25px;
            text-align: center; 
            color: #d4af37; 
            text-transform: uppercase;
            letter-spacing: 1.5px;
            font-size: 22px;
        }
        .login-box input { 
            width: 100%; 
            padding: 12px; 
            margin: 10px 0 20px 0; 
            background: #2a2a2a; 
            border: 1px solid #444; 
            color: #fff;
            border-radius: 4px; 
            box-sizing: border-box; 
            transition: border-color 0.3s;
        }
        .login-box input:focus {
            border-color: #d4af37;
            outline: none;
        }
        .login-box button { 
            width: 100%; 
            padding: 12px; 
            background: linear-gradient(135deg, #d4af37 0%, #aa8c2c 100%); 
            color: #111; 
            font-weight: bold;
            font-size: 16px;
            text-transform: uppercase;
            border: none; 
            border-radius: 4px; 
            cursor: pointer; 
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .login-box button:hover { 
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(212, 175, 55, 0.4); 
        }
        .error { 
            color: #ff6b6b; 
            font-size: 14px; 
            text-align: center; 
            margin-bottom: 15px;
            background: rgba(255, 107, 107, 0.1);
            padding: 10px;
            border-radius: 4px;
            border: 1px solid #ff6b6b;
        }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>CRM System</h2>
        <?php if (!empty($error)): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="POST" action="index.php?action=login">
            <input type="email" name="email" placeholder="Email" required autocomplete="email">
            <input type="password" name="password" placeholder="Пароль" required autocomplete="current-password">
            <button type="submit">Войти</button>
        </form>
    </div>
</body>
</html>