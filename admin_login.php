<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ECUSTA — Admin & Teacher Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="css/theme.css">
    <script src="js/theme.js"></script>

    <?php if (@$_GET['w']) {
        echo '<script>window.addEventListener("DOMContentLoaded",function(){showAlert("' . htmlspecialchars(@$_GET['w'], ENT_QUOTES) . '","error");});</script>';
    } ?>

    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--admin-login-bg);
            background-attachment: fixed;
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background:
                radial-gradient(2px 2px at 15% 25%, rgba(255, 255, 255, 0.12), transparent),
                radial-gradient(2px 2px at 50% 60%, rgba(255, 255, 255, 0.08), transparent),
                radial-gradient(1px 1px at 85% 35%, rgba(255, 255, 255, 0.12), transparent),
                radial-gradient(1px 1px at 70% 85%, rgba(255, 255, 255, 0.1), transparent),
                radial-gradient(2px 2px at 30% 90%, rgba(255, 255, 255, 0.08), transparent);
            pointer-events: none;
            z-index: 0;
        }

        .container {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 460px;
            padding: 20px;
        }

        .logo-section {
            text-align: center;
            margin-bottom: 28px;
            animation: fadeInDown 0.8s ease-out;
        }

        .logo-section img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            border: 3px solid rgba(255, 255, 255, 0.15);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            margin-bottom: 16px;
            object-fit: cover;
            background: white;
        }

        .logo-section h1 {
            color: var(--text-primary);
            font-size: 20px;
            font-weight: 700;
            letter-spacing: 1px;
            margin-bottom: 4px;
        }

        .logo-section .badge {
            display: inline-block;
            padding: 4px 14px;
            background: var(--bg-input);
            border: 1px solid var(--border-primary);
            border-radius: 20px;
            color: var(--text-muted);
            font-size: 11px;
            font-weight: 500;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-top: 8px;
        }

        .card {
            background: var(--bg-card);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--border-primary);
            border-radius: 20px;
            padding: 36px 32px;
            box-shadow: 0 20px 60px var(--shadow-color), inset 0 1px 0 rgba(255, 255, 255, 0.08);
            animation: fadeInUp 0.8s ease-out 0.2s both;
        }

        .tabs {
            display: flex;
            background: var(--bg-input);
            border-radius: 12px;
            padding: 4px;
            margin-bottom: 28px;
        }

        .tab-btn {
            flex: 1;
            padding: 12px 0;
            border: none;
            background: transparent;
            color: var(--text-dimmed);
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            border-radius: 10px;
            transition: all 0.3s ease;
            letter-spacing: 0.5px;
        }

        .tab-btn.active {
            background: var(--bg-card-hover);
            color: var(--text-primary);
            box-shadow: 0 4px 12px var(--shadow-color);
        }

        .tab-btn:hover:not(.active) {
            color: var(--sidebar-text-hover);
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
            animation: fadeIn 0.4s ease;
        }

        .role-indicator {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 14px;
            background: var(--bg-input);
            border-radius: 10px;
            margin-bottom: 22px;
            border: 1px solid var(--border-primary);
        }

        .role-indicator .material-icons {
            font-size: 20px;
            color: var(--text-muted);
        }

        .role-indicator span {
            color: var(--text-muted);
            font-size: 13px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            font-size: 12px;
            font-weight: 500;
            color: var(--text-muted);
            margin-bottom: 8px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .form-group input {
            width: 100%;
            padding: 14px 16px;
            background: var(--bg-input);
            border: 1px solid var(--border-input);
            border-radius: 12px;
            color: var(--text-primary);
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            transition: all 0.3s ease;
            outline: none;
        }

        .form-group input::placeholder {
            color: var(--text-input-placeholder);
        }

        .form-group input:focus {
            border-color: var(--border-input-focus);
            background: var(--bg-input-focus);
            box-shadow: 0 0 0 3px rgba(168, 133, 255, 0.15);
        }

        .btn-admin {
            width: 100%;
            padding: 15px;
            border: none;
            border-radius: 12px;
            font-family: 'Inter', sans-serif;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            letter-spacing: 0.5px;
            margin-top: 6px;
            background: var(--admin-accent-gradient);
            color: var(--admin-accent-dark);
            box-shadow: 0 4px 15px var(--shadow-color);
        }

        .btn-admin:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px var(--shadow-color);
        }

        .btn-admin:active {
            transform: translateY(0);
        }

        .back-link {
            text-align: center;
            margin-top: 24px;
            animation: fadeInUp 0.8s ease-out 0.4s both;
        }

        .back-link a {
            color: var(--text-dimmed);
            font-size: 13px;
            text-decoration: none;
            transition: color 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .back-link a:hover {
            color: var(--sidebar-text-hover);
        }

        .back-link .material-icons {
            font-size: 16px;
        }

        .alert-toast {
            position: fixed;
            top: 24px;
            right: 24px;
            padding: 14px 24px;
            border-radius: 12px;
            color: #fff;
            font-size: 14px;
            font-weight: 500;
            z-index: 9999;
            animation: slideInRight 0.4s ease, fadeOut 0.4s ease 4s forwards;
            box-shadow: 0 8px 32px var(--shadow-color);
            max-width: 360px;
        }

        .alert-toast.error {
            background: linear-gradient(135deg, var(--danger), #ff4b2b);
        }

        .footer-text {
            text-align: center;
            margin-top: 20px;
            color: var(--text-faint);
            font-size: 11px;
            animation: fadeInUp 0.8s ease-out 0.6s both;
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(100px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes fadeOut {
            to {
                opacity: 0;
                transform: translateY(-10px);
            }
        }

        @media (max-width: 480px) {
            .container {
                padding: 16px;
            }

            .card {
                padding: 28px 22px;
            }

            .logo-section img {
                width: 80px;
                height: 80px;
            }

            .logo-section h1 {
                font-size: 17px;
            }
        }
    </style>
</head>

<body>
    <button class="theme-toggle theme-toggle-float" title="Toggle dark/light mode">
        <span class="material-icons">light_mode</span>
    </button>
    <div class="container">
        <!-- Logo -->
        <div class="logo-section">
            <img src="assets/img/ecusta_logo.png" alt="ECUSTA Logo">
            <h1>ECUSTA</h1>
            <div class="badge">Admin &amp; Teacher Portal</div>
        </div>

        <!-- Card -->
        <div class="card">
            <div class="tabs">
                <button class="tab-btn active" onclick="switchTab('admin')">Admin</button>
                <button class="tab-btn" onclick="switchTab('teacher')">Teacher</button>
            </div>

            <!-- Admin Login -->
            <div id="admin" class="tab-content active">
                <div class="role-indicator">
                    <span class="material-icons">shield</span>
                    <span>System Administrator Access</span>
                </div>
                <form method="post" action="includes/head.php?q=admin_login.php">
                    <div class="form-group">
                        <label for="admin-email">Admin Email</label>
                        <input id="admin-email" name="uname" type="text" placeholder="admin@ecusta.edu.et"
                            maxlength="20" required>
                    </div>
                    <div class="form-group">
                        <label for="admin-password">Password</label>
                        <input id="admin-password" name="password" type="password" placeholder="Enter admin password"
                            maxlength="15" required>
                    </div>
                    <button type="submit" name="login" class="btn-admin">Sign In as Admin</button>
                </form>
            </div>

            <!-- Teacher Login -->
            <div id="teacher" class="tab-content">
                <div class="role-indicator">
                    <span class="material-icons">school</span>
                    <span>Teacher / Instructor Access</span>
                </div>
                <form method="post" action="includes/admin.php?q=admin_login.php">
                    <div class="form-group">
                        <label for="teacher-email">Teacher Email</label>
                        <input id="teacher-email" name="uname" type="text" placeholder="teacher@ecusta.edu.et"
                            maxlength="20" required>
                    </div>
                    <div class="form-group">
                        <label for="teacher-password">Password</label>
                        <input id="teacher-password" name="password" type="password"
                            placeholder="Enter teacher password" maxlength="15" required>
                    </div>
                    <button type="submit" name="login2" class="btn-admin">Sign In as Teacher</button>
                </form>
            </div>
        </div>

        <!-- Back to student login -->
        <div class="back-link">
            <a href="index.php">
                <span class="material-icons">arrow_back</span>
                Back to Student Portal
            </a>
        </div>

        <p class="footer-text">&copy; 2026 ECUSTA — Ethiopian Catholic University St. Thomas Aquinas</p>
    </div>

    <script>
        function switchTab(tabId) {
            document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            document.getElementById(tabId).classList.add('active');
            event.target.classList.add('active');
        }

        function showAlert(msg, type) {
            const el = document.createElement('div');
            el.className = 'alert-toast ' + (type || 'error');
            el.textContent = msg;
            document.body.appendChild(el);
            setTimeout(() => el.remove(), 4500);
        }
    </script>
</body>

</html>