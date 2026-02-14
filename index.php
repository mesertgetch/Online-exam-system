<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ECUSTA — Online Examination System</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link rel="stylesheet" href="css/theme.css">
  <script src="js/theme.js"></script>

  <?php if (@$_GET['w']) {
    echo '<script>window.addEventListener("DOMContentLoaded",function(){showAlert("' . htmlspecialchars(@$_GET['w'], ENT_QUOTES) . '","error");});</script>';
  } ?>
  <?php if (@$_GET['q7']) {
    echo '<script>window.addEventListener("DOMContentLoaded",function(){showAlert("' . htmlspecialchars(@$_GET['q7'], ENT_QUOTES) . '","error");});</script>';
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
      background: var(--login-bg);
      background-attachment: fixed;
      overflow-x: hidden;
    }

    /* Animated background particles */
    body::before {
      content: '';
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background:
        radial-gradient(2px 2px at 20% 30%, rgba(255, 255, 255, 0.15), transparent),
        radial-gradient(2px 2px at 40% 70%, rgba(255, 255, 255, 0.1), transparent),
        radial-gradient(1px 1px at 90% 40%, rgba(255, 255, 255, 0.15), transparent),
        radial-gradient(1px 1px at 60% 80%, rgba(255, 255, 255, 0.12), transparent),
        radial-gradient(2px 2px at 80% 10%, rgba(255, 255, 255, 0.1), transparent);
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

    /* Logo section */
    .logo-section {
      text-align: center;
      margin-bottom: 28px;
      animation: fadeInDown 0.8s ease-out;
    }

    .logo-section img {
      width: 110px;
      height: 110px;
      border-radius: 50%;
      border: 3px solid rgba(255, 255, 255, 0.2);
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
      margin-bottom: 16px;
      object-fit: cover;
      background: white;
    }

    .logo-section h1 {
      color: var(--text-primary);
      font-size: 22px;
      font-weight: 700;
      letter-spacing: 1px;
      margin-bottom: 4px;
    }

    .logo-section p {
      color: var(--text-muted);
      font-size: 13px;
      font-weight: 400;
      letter-spacing: 2px;
      text-transform: uppercase;
    }

    /* Card */
    .card {
      background: var(--bg-card);
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
      border: 1px solid var(--border-primary);
      border-radius: 20px;
      padding: 36px 32px;
      box-shadow: 0 20px 60px var(--shadow-color), inset 0 1px 0 rgba(255, 255, 255, 0.1);
      animation: fadeInUp 0.8s ease-out 0.2s both;
    }

    /* Tab navigation */
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
      color: var(--text-muted);
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

    /* Forms */
    .tab-content {
      display: none;
    }

    .tab-content.active {
      display: block;
      animation: fadeIn 0.4s ease;
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

    .form-group input,
    .form-group select {
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

    .form-group input:focus,
    .form-group select:focus {
      border-color: var(--border-input-focus);
      background: var(--bg-input-focus);
      box-shadow: 0 0 0 3px rgba(79, 172, 254, 0.15);
    }

    .form-group select {
      appearance: none;
      -webkit-appearance: none;
      background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%23ffffff50' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
      background-position: right 12px center;
      background-repeat: no-repeat;
      background-size: 20px;
      cursor: pointer;
    }

    .form-group select option {
      background: var(--select-option-bg);
      color: var(--text-primary);
    }

    .form-row {
      display: flex;
      gap: 12px;
    }

    .form-row .form-group {
      flex: 1;
    }

    .btn-primary {
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
      background: var(--accent-gradient);
      color: var(--bg-primary);
      box-shadow: 0 4px 15px var(--shadow-color);
    }

    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px var(--shadow-color);
    }

    .btn-primary:active {
      transform: translateY(0);
    }

    /* Admin link */
    .admin-link {
      text-align: center;
      margin-top: 24px;
      animation: fadeInUp 0.8s ease-out 0.4s both;
    }

    .admin-link a {
      color: var(--text-dimmed);
      font-size: 13px;
      text-decoration: none;
      transition: color 0.3s ease;
      display: inline-flex;
      align-items: center;
      gap: 6px;
    }

    .admin-link a:hover {
      color: var(--sidebar-text-hover);
    }

    .admin-link .material-icons {
      font-size: 16px;
    }

    /* Alert toast */
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

    .alert-toast.success {
      background: linear-gradient(135deg, #11998e, var(--success));
      color: var(--bg-primary);
    }

    .footer-text {
      text-align: center;
      margin-top: 20px;
      color: var(--text-faint);
      font-size: 11px;
      animation: fadeInUp 0.8s ease-out 0.6s both;
    }

    /* Animations */
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

    /* Responsive */
    @media (max-width: 480px) {
      .container {
        padding: 16px;
      }

      .card {
        padding: 28px 22px;
      }

      .logo-section img {
        width: 90px;
        height: 90px;
      }

      .logo-section h1 {
        font-size: 18px;
      }

      .form-row {
        flex-direction: column;
        gap: 0;
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
      <img src="ecusta_logo.png" alt="ECUSTA Logo">
      <h1>ECUSTA</h1>
      <p>Online Examination System</p>
    </div>

    <!-- Card -->
    <div class="card">
      <!-- Tabs -->
      <div class="tabs">
        <button class="tab-btn active" onclick="switchTab('signin')">Sign In</button>
        <button class="tab-btn" onclick="switchTab('signup')">Sign Up</button>
      </div>

      <!-- Sign In Form -->
      <div id="signin" class="tab-content active">
        <form action="login.php?q=index.php" method="POST">
          <div class="form-group">
            <label for="login-email">Email Address</label>
            <input id="login-email" name="email" type="email" placeholder="you@example.com" required>
          </div>
          <div class="form-group">
            <label for="login-password">Password</label>
            <input id="login-password" name="password" type="password" placeholder="Enter your password" required>
          </div>
          <button type="submit" class="btn-primary">Sign In</button>
        </form>
      </div>

      <!-- Sign Up Form -->
      <div id="signup" class="tab-content">
        <form name="form" action="sign.php?q=account.php" method="POST" onsubmit="return validateSignUp()">
          <div class="form-row">
            <div class="form-group">
              <label for="reg-name">Full Name</label>
              <input id="reg-name" name="name" type="text" placeholder="Your name" required>
            </div>
            <div class="form-group">
              <label for="reg-gender">Gender</label>
              <select id="reg-gender" name="gender" required>
                <option value="" disabled selected>Select</option>
                <option value="M">Male</option>
                <option value="F">Female</option>
              </select>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="reg-college">College / Department</label>
              <select id="reg-college" name="college" required onchange="updateRegYears()"
                style="width:100%;padding:10px;border:1px solid #ddd;border-radius:5px">
                <option value="" disabled selected>Select Department</option>
                <?php
                $q = mysqli_query($con, "SELECT * FROM departments ORDER BY dept_name ASC");
                while ($row = mysqli_fetch_array($q)) {
                  echo '<option value="' . htmlspecialchars($row['dept_name']) . '" data-years="' . htmlspecialchars($row['year_labels']) . '">' . htmlspecialchars($row['dept_name']) . '</option>';
                }
                ?>
              </select>
            </div>
            <div class="form-group">
              <label for="reg-year">Year</label>
              <select id="reg-year" name="year" required
                style="width:100%;padding:10px;border:1px solid #ddd;border-radius:5px">
                <option value="" disabled selected>Select Department First</option>
              </select>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="reg-email">Email Address</label>
              <input id="reg-email" name="email" type="email" placeholder="you@example.com" required>
            </div>
            <div class="form-group">
              <label for="reg-mob">Mobile Number</label>
              <input id="reg-mob" name="mob" type="number" placeholder="e.g. 0912345678" required>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="reg-password">Password</label>
              <input id="reg-password" name="password" type="password" placeholder="Min 5 chars" required>
            </div>
            <div class="form-group">
              <label for="reg-cpassword">Confirm</label>
              <input id="reg-cpassword" name="cpassword" type="password" placeholder="Repeat" required>
            </div>
          </div>

          <button type="submit" class="btn-primary">Create Account</button>
        </form>
      </div>
    </div>

    <!-- Admin / Teacher link -->
    <div class="admin-link">
      <a href="admin_login.php">
        <span class="material-icons">admin_panel_settings</span>
        Admin &amp; Teacher Portal
      </a>
    </div>

    <p class="footer-text">&copy; 2026 ECUSTA — Ethiopian Catholic University St. Thomas Aquinas</p>
  </div>

  <script>
    // Tab switching
    function switchTab(tabId) {
      document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
      document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
      document.getElementById(tabId).classList.add('active');
      event.target.classList.add('active');
    }

    // Form validation
    function validateSignUp() {
      const name = document.getElementById('reg-name').value.trim();
      const college = document.getElementById('reg-college').value.trim();
      const email = document.getElementById('reg-email').value.trim();
      const password = document.getElementById('reg-password').value;
      const cpassword = document.getElementById('reg-cpassword').value;

      if (!name) { showAlert('Name is required.', 'error'); return false; }
      if (!college) { showAlert('College is required.', 'error'); return false; }

      const atpos = email.indexOf("@");
      const dotpos = email.lastIndexOf(".");
      if (atpos < 1 || dotpos < atpos + 2 || dotpos + 2 >= email.length) {
        showAlert('Please enter a valid email address.', 'error');
        return false;
      }

      if (password.length < 5 || password.length > 25) {
        showAlert('Password must be 5–25 characters.', 'error');
        return false;
      }

      if (password !== cpassword) {
        showAlert('Passwords do not match.', 'error');
        return false;
      }

      return true;
    }

    // Alert toast
    function showAlert(msg, type) {
      const el = document.createElement('div');
      el.className = 'alert-toast ' + (type || 'error');
      el.textContent = msg;
      document.body.appendChild(el);
      setTimeout(() => el.remove(), 4500);
    }
  </script>
  <script>
    function updateRegYears() {
      const select = document.getElementById('reg-college');
      const years = select.selectedOptions[0].getAttribute('data-years');
      const yearSelect = document.getElementById('reg-year');
      yearSelect.innerHTML = '<option value="" disabled selected>Select Year</option>';
      if (years) {
        const yearList = years.split(',');
        yearList.forEach(y => {
          const opt = document.createElement('option');
          opt.value = y.trim();
          opt.textContent = y.trim();
          yearSelect.appendChild(opt);
        });
      }
    }
  </script>
</body>

</html>l