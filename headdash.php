<?php
include_once 'config/dbConnection.php';
session_start();
if (!(isset($_SESSION['email'])) || !(isset($_SESSION['key']))) {
  header("location:admin_login.php?w=Session expired. Please login again.");
  exit;
}
$email = $_SESSION['email'];
$name = $_SESSION['name'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ECUSTA — Admin Dashboard</title>
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
      background: var(--bg-primary);
      color: var(--text-secondary);
      min-height: 100vh;
      display: flex;
    }

    .sidebar {
      width: 260px;
      background: var(--admin-sidebar-bg);
      border-right: 1px solid var(--border-primary);
      display: flex;
      flex-direction: column;
      position: fixed;
      top: 0;
      left: 0;
      height: 100vh;
      z-index: 100;
      overflow-y: auto;
    }

    .sidebar-brand {
      padding: 24px 20px;
      display: flex;
      align-items: center;
      gap: 12px;
      border-bottom: 1px solid var(--border-primary);
    }

    .sidebar-brand img {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      object-fit: cover;
      background: #fff;
    }

    .sidebar-brand span {
      font-size: 16px;
      font-weight: 700;
      color: var(--text-primary);
    }

    .sidebar-brand .admin-badge {
      font-size: 9px;
      background: var(--admin-sidebar-active-bg);
      color: var(--admin-accent);
      padding: 2px 8px;
      border-radius: 6px;
      font-weight: 600;
      letter-spacing: 1px;
      text-transform: uppercase;
      margin-left: 4px;
    }

    .sidebar-label {
      padding: 20px 28px 8px;
      font-size: 10px;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 2px;
      color: var(--text-faint);
    }

    .sidebar-nav {
      flex: 1;
      padding: 12px;
      display: flex;
      flex-direction: column;
      gap: 2px;
    }

    .nav-item {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 11px 16px;
      border-radius: 10px;
      color: var(--sidebar-text);
      text-decoration: none;
      font-size: 13px;
      font-weight: 500;
      transition: all 0.2s ease;
    }

    .nav-item:hover {
      background: var(--bg-card-hover);
      color: var(--sidebar-text-hover);
    }

    .nav-item.active {
      background: var(--admin-sidebar-active-bg);
      color: var(--admin-sidebar-active-text);
    }

    .nav-item .material-icons {
      font-size: 19px;
    }

    .sidebar-footer {
      padding: 14px 12px;
      border-top: 1px solid var(--border-primary);
    }

    .sidebar-footer .user-info {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 10px;
    }

    .sidebar-footer .user-info .avatar {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      background: var(--admin-accent-gradient);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 14px;
      font-weight: 700;
      color: var(--admin-accent-dark);
      flex-shrink: 0;
    }

    .sidebar-footer .user-name {
      font-size: 13px;
      font-weight: 600;
      color: var(--text-primary);
    }

    .sidebar-footer .user-email {
      font-size: 11px;
      color: var(--text-dimmed);
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      max-width: 140px;
    }

    .btn-signout {
      display: flex;
      align-items: center;
      gap: 8px;
      padding: 10px 16px;
      border-radius: 10px;
      color: var(--text-dimmed);
      text-decoration: none;
      font-size: 13px;
      transition: all 0.2s ease;
      margin-top: 6px;
    }

    .btn-signout:hover {
      background: var(--danger-bg);
      color: var(--danger);
    }

    .main {
      margin-left: 260px;
      flex: 1;
      padding: 32px;
      min-height: 100vh;
    }

    .page-header {
      margin-bottom: 28px;
    }

    .page-header h1 {
      font-size: 24px;
      font-weight: 700;
      color: var(--text-primary);
      margin-bottom: 4px;
    }

    .page-header p {
      font-size: 13px;
      color: var(--text-dimmed);
    }

    .card {
      background: var(--bg-card);
      border: 1px solid var(--border-primary);
      border-radius: 16px;
      padding: 24px;
      margin-bottom: 24px;
    }

    .data-table {
      width: 100%;
      border-collapse: collapse;
    }

    .data-table th {
      text-align: left;
      padding: 12px 16px;
      font-size: 11px;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 1px;
      color: var(--table-header-color);
      border-bottom: 1px solid var(--border-primary);
    }

    .data-table td {
      padding: 14px 16px;
      font-size: 14px;
      border-bottom: 1px solid var(--table-border);
      color: var(--text-muted);
    }

    .data-table tbody tr:hover {
      background: var(--table-row-hover);
    }

    .btn-action {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 8px 16px;
      border-radius: 8px;
      font-size: 13px;
      font-weight: 600;
      text-decoration: none;
      transition: all 0.2s ease;
      border: none;
      cursor: pointer;
    }

    .btn-primary {
      background: var(--admin-accent-gradient);
      color: var(--admin-accent-dark);
    }

    .btn-primary:hover {
      transform: translateY(-1px);
      box-shadow: 0 4px 15px var(--shadow-color);
    }

    .btn-danger {
      background: linear-gradient(135deg, var(--danger), #ff4b2b);
      color: #fff;
    }

    .btn-danger:hover {
      transform: translateY(-1px);
      box-shadow: 0 4px 15px var(--shadow-color);
    }

    .btn-submit {
      width: 100%;
      padding: 14px;
      border-radius: 12px;
      font-family: 'Inter', sans-serif;
      font-size: 15px;
      font-weight: 600;
      cursor: pointer;
      border: none;
      background: var(--admin-accent-gradient);
      color: var(--admin-accent-dark);
      transition: all 0.2s ease;
      margin-top: 8px;
    }

    .btn-submit:hover {
      transform: translateY(-1px);
      box-shadow: 0 6px 20px var(--shadow-color);
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
    .form-group textarea,
    .form-group select {
      width: 100%;
      padding: 13px 16px;
      background: var(--bg-input);
      border: 1px solid var(--border-input);
      border-radius: 10px;
      color: var(--text-primary);
      font-family: 'Inter', sans-serif;
      font-size: 14px;
      outline: none;
      transition: all 0.2s ease;
    }

    .form-group input::placeholder,
    .form-group textarea::placeholder {
      color: var(--text-input-placeholder);
    }

    .form-group input:focus,
    .form-group textarea:focus,
    .form-group select:focus {
      border-color: var(--border-input-focus);
      background: var(--bg-input-focus);
    }

    .form-group textarea {
      resize: vertical;
      min-height: 100px;
    }

    .form-group select option {
      background: var(--select-option-bg);
    }

    .form-row {
      display: flex;
      gap: 12px;
    }

    .form-row .form-group {
      flex: 1;
    }

    .question-block {
      border: 1px solid var(--border-primary);
      border-radius: 12px;
      padding: 20px;
      margin-bottom: 20px;
      background: var(--bg-card);
    }

    .question-block h3 {
      font-size: 14px;
      color: var(--admin-accent);
      margin-bottom: 16px;
    }

    .stat-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
      gap: 16px;
      margin-bottom: 28px;
    }

    .stat-card {
      padding: 20px;
      border-radius: 14px;
      background: var(--bg-card);
      border: 1px solid var(--border-primary);
    }

    .stat-card .label {
      font-size: 11px;
      text-transform: uppercase;
      letter-spacing: 1px;
      color: var(--text-dimmed);
      margin-bottom: 8px;
    }

    .stat-card .value {
      font-size: 28px;
      font-weight: 700;
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
      animation: slideIn 0.4s ease, fadeOut 0.4s ease 4s forwards;
      box-shadow: 0 8px 32px var(--shadow-color);
    }

    .alert-toast.error {
      background: linear-gradient(135deg, var(--danger), #ff4b2b);
    }

    .alert-toast.success {
      background: linear-gradient(135deg, #11998e, var(--success));
      color: var(--bg-primary);
    }

    @keyframes slideIn {
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
      }
    }

    .menu-toggle {
      display: none;
      position: fixed;
      top: 16px;
      left: 16px;
      z-index: 200;
      background: var(--bg-card);
      border: 1px solid var(--border-primary);
      border-radius: 10px;
      padding: 8px;
      color: var(--text-primary);
      cursor: pointer;
    }

    @media (max-width: 768px) {
      .sidebar {
        transform: translateX(-100%);
        transition: transform 0.3s ease;
      }

      .sidebar.open {
        transform: translateX(0);
      }

      .main {
        margin-left: 0;
        padding: 24px 16px;
        padding-top: 64px;
      }

      .menu-toggle {
        display: flex;
      }

      .form-row {
        flex-direction: column;
        gap: 0;
      }

      .stat-grid {
        grid-template-columns: repeat(2, 1fr);
      }
    }
  </style>
</head>

<body>
  <button class="menu-toggle" onclick="document.querySelector('.sidebar').classList.toggle('open')">
    <span class="material-icons">menu</span>
  </button>

  <nav class="sidebar">
    <div class="sidebar-brand">
      <img src="assets/img/ecusta_logo.png" alt="Logo">
      <span>ECUSTA</span>
      <span class="admin-badge">Admin</span>
    </div>
    <div class="sidebar-nav">
      <div class="sidebar-label">Overview</div>
      <a href="headdash.php?q=0" class="nav-item <?php if (@$_GET['q'] == 0)
        echo 'active'; ?>">
        <span class="material-icons">dashboard</span> Dashboard
      </a>

      <div class="sidebar-label">Exam Management</div>
      <a href="headdash.php?q=6" class="nav-item <?php if (@$_GET['q'] == 6 && !@$_GET['step'])
        echo 'active'; ?>">
        <span class="material-icons">add_circle</span> Add Exam
      </a>
      <a href="headdash.php?q=7" class="nav-item <?php if (@$_GET['q'] == 7)
        echo 'active'; ?>">
        <span class="material-icons">delete_sweep</span> Remove Exam
      </a>

      <div class="sidebar-label">Users</div>
      <a href="headdash.php?q=1" class="nav-item <?php if (@$_GET['q'] == 1)
        echo 'active'; ?>">
        <span class="material-icons">people</span> All Users
      </a>
      <a href="headdash.php?q=8" class="nav-item <?php if (@$_GET['q'] == 8)
        echo 'active'; ?>">
        <span class="material-icons">person_add</span> Add User
      </a>
      <a href="headdash.php?q=2" class="nav-item <?php if (@$_GET['q'] == 2)
        echo 'active'; ?>">
        <span class="material-icons">assessment</span> Scores
      </a>

      <div class="sidebar-label">Administration</div>
      <a href="headdash.php?q=3" class="nav-item <?php if (@$_GET['q'] == 3)
        echo 'active'; ?>">
        <span class="material-icons">feedback</span> Feedback
      </a>
      <a href="headdash.php?q=4" class="nav-item <?php if (@$_GET['q'] == 4)
        echo 'active'; ?>">
        <span class="material-icons">admin_panel_settings</span> Add Teacher
      </a>
      <a href="headdash.php?q=5" class="nav-item <?php if (@$_GET['q'] == 5 || @$_GET['q'] == 'edit_admin')
        echo 'active'; ?>">
        <span class="material-icons">manage_accounts</span> Manage Teachers
      </a>
      <a href="headdash.php?q=manage_dept" class="nav-item <?php if (@$_GET['q'] == 'manage_dept')
        echo 'active'; ?>">
        <span class="material-icons">domain</span> Departments
      </a>
    </div>
    <div class="sidebar-footer">
      <div style="display:flex;align-items:center;justify-content:space-between;padding:0 10px 8px">
        <button class="theme-toggle" title="Toggle dark/light mode">
          <span class="material-icons">light_mode</span>
        </button>
      </div>
      <div class="user-info">
        <div class="avatar"><span class="material-icons" style="font-size:18px">shield</span></div>
        <div style="flex:1">
          <div class="user-name">System Admin</div>
          <div class="user-email"><?php echo htmlspecialchars($email); ?></div>
        </div>
        <a href="headdash.php?q=edit_admin&email=<?php echo urlencode($email); ?>" title="Edit my account" style="color:var(--text-dimmed);display:flex;align-items:center;padding:4px;border-radius:6px;transition:color 0.2s">
          <span class="material-icons" style="font-size:18px">edit</span>
        </a>
      </div>
      <a href="logout.php?q=admin_login.php" class="btn-signout">
        <span class="material-icons" style="font-size:18px">logout</span> Sign Out
      </a>
    </div>
  </nav>

  <main class="main">

    <!-- DASHBOARD HOME -->
    <?php if (@$_GET['q'] == 0) { ?>
      <div class="page-header">
        <h1>Admin Dashboard</h1>
        <p>System overview</p>
      </div>
      <?php
      $ucount = mysqli_num_rows(mysqli_query($con, "SELECT * FROM user"));
      $qcount = mysqli_num_rows(mysqli_query($con, "SELECT * FROM quiz"));
      $acount = mysqli_num_rows(mysqli_query($con, "SELECT * FROM admin WHERE role='admin'"));
      $fcount = mysqli_num_rows(mysqli_query($con, "SELECT * FROM feedback"));
      ?>
      <div class="stat-grid">
        <div class="stat-card">
          <div class="label">Total Students</div>
          <div class="value" style="color:#4facfe"><?php echo $ucount; ?></div>
        </div>
        <div class="stat-card">
          <div class="label">Total Exams</div>
          <div class="value" style="color:#38ef7d"><?php echo $qcount; ?></div>
        </div>
        <div class="stat-card">
          <div class="label">Teachers</div>
          <div class="value" style="color:#a885ff"><?php echo $acount; ?></div>
        </div>
        <div class="stat-card">
          <div class="label">Feedback</div>
          <div class="value" style="color:#ffd700"><?php echo $fcount; ?></div>
        </div>
      </div>
      <!-- All quizzes -->
      <div class="card">
        <h2 style="color:#fff;font-size:18px;margin-bottom:16px">All Exams</h2>
        <table class="data-table">
          <thead>
            <tr>
              <th>#</th>
              <th>Exam Name</th>
              <th>Access Code</th>
              <th>Questions</th>
              <th>Marks</th>
              <th>+</th>
              <th>−</th>
              <th>Time</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $result = mysqli_query($con, "SELECT * FROM quiz ORDER BY date DESC") or die('Error');
            $c = 1;
            while ($row = mysqli_fetch_array($result)) {
              $code = htmlspecialchars(@$row['access_code'] ?: '—');
              echo '<tr><td>' . $c++ . '</td><td>' . $row['title'] . '</td><td><code style="background:var(--bg-input);padding:3px 8px;border-radius:6px;font-size:13px;letter-spacing:1px;font-weight:600;color:var(--text-primary)">' . $code . '</code></td><td>' . $row['total'] . '</td><td>' . ($row['sahi'] * $row['total']) . '</td><td>' . $row['sahi'] . '</td><td>' . $row['wrong'] . '</td><td>' . $row['time'] . ' min</td></tr>';
            }
            ?>
          </tbody>
        </table>
      </div>
    <?php } ?>

    <!-- USERS LIST -->
    <?php if (@$_GET['q'] == 1) { ?>
      <div class="page-header">
        <h1>All Students</h1>
        <p>Manage registered students</p>
      </div>
      <div class="card">
        <table class="data-table">
          <thead>
            <tr>
              <th>#</th>
              <th>Name</th>
              <th>Gender</th>
              <th>Department</th>
              <th>Year</th>
              <th>Email</th>
              <th>Mobile</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $result = mysqli_query($con, "SELECT * FROM user") or die('Error');
            $c = 1;
            while ($row = mysqli_fetch_array($result)) {
              echo '<tr><td>' . $c++ . '</td><td>' . htmlspecialchars($row['name']) . '</td><td>' . $row['gender'] . '</td><td>' . htmlspecialchars($row['college']) . '</td><td>' . htmlspecialchars(@$row['year']) . '</td><td>' . htmlspecialchars($row['email']) . '</td><td>' . $row['mob'] . '</td>';
              echo '<td style="display:flex;gap:6px">';
              echo '<a href="headdash.php?q=edit_user&email=' . urlencode($row['email']) . '" class="btn-action btn-primary" title="Edit user"><span class="material-icons" style="font-size:16px">edit</span></a>';
              echo '<a href="update.php?demail=' . urlencode($row['email']) . '" class="btn-action btn-danger" title="Delete user" onclick="return confirm(\'Delete this student and all their data?\')" ><span class="material-icons" style="font-size:16px">delete</span></a>';
              echo '</td></tr>';
            }
            ?>
          </tbody>
        </table>
      </div>
    <?php } ?>

    <!-- EDIT USER -->
    <?php if (@$_GET['q'] == 'edit_user') {
      $uemail = @$_GET['email'];
      $uq = mysqli_query($con, "SELECT * FROM user WHERE email='$uemail'");
      $urow = mysqli_fetch_array($uq);
      if ($urow) {
        ?>
        <div class="page-header">
          <h1>Edit Student</h1>
          <p>Update information for <?php echo htmlspecialchars($urow['name']); ?></p>
        </div>
        <div class="card" style="max-width:550px">
          <form action="update.php?q=update_user" method="POST">
            <input type="hidden" name="email" value="<?php echo htmlspecialchars($urow['email']); ?>">
            <div class="form-row">
              <div class="form-group">
                <label>Full Name</label>
                <input name="name" type="text" value="<?php echo htmlspecialchars($urow['name']); ?>" required>
              </div>
              <div class="form-group">
                <label>Gender</label>
                <select name="gender" style="appearance:auto" required>
                  <option value="M" <?php if ($urow['gender'] == 'M')
                    echo 'selected'; ?>>Male</option>
                  <option value="F" <?php if ($urow['gender'] == 'F')
                    echo 'selected'; ?>>Female</option>
                </select>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label>Department</label>
                <select id="edit-user-dept" name="college" style="appearance:auto" required
                  onchange="updateEditUserYears()">
                  <option value="" disabled>Select Department</option>
                  <?php
                  $dq = mysqli_query($con, "SELECT * FROM departments ORDER BY dept_name ASC");
                  while ($dr = mysqli_fetch_array($dq)) {
                    $sel = ($dr['dept_name'] == $urow['college']) ? 'selected' : '';
                    echo '<option value="' . htmlspecialchars($dr['dept_name']) . '" data-years="' . htmlspecialchars($dr['year_labels']) . '" ' . $sel . '>' . htmlspecialchars($dr['dept_name']) . '</option>';
                  }
                  ?>
                </select>
              </div>
              <div class="form-group">
                <label>Year</label>
                <select id="edit-user-year" name="year" style="appearance:auto">
                  <option value="">Select Year</option>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label>Email (Read Only)</label>
              <input type="email" value="<?php echo htmlspecialchars($urow['email']); ?>" disabled
                style="opacity:0.6;cursor:not-allowed">
            </div>
            <div class="form-group">
              <label>Mobile Number</label>
              <input name="mob" type="number" value="<?php echo $urow['mob']; ?>" required>
            </div>
            <div class="form-group">
              <label>New Password <span style="color:var(--text-dimmed);font-weight:400">(leave blank to keep
                  current)</span></label>
              <input name="new_password" type="password" placeholder="Enter new password" minlength="5">
            </div>
            <div style="display:flex;gap:12px;margin-top:8px">
              <button type="submit" class="btn-submit" style="flex:1">Save Changes</button>
              <a href="headdash.php?q=1" class="btn-action btn-danger"
                style="display:flex;align-items:center;justify-content:center;padding:14px 24px;border-radius:12px;text-decoration:none;font-weight:600">Cancel</a>
            </div>
          </form>
        </div>
        <script>
          (function () {
            const currentYear = <?php echo json_encode(@$urow['year'] ?: ''); ?>;
            function updateEditUserYears() {
              const select = document.getElementById('edit-user-dept');
              const opt = select.selectedOptions[0];
              const years = opt ? opt.getAttribute('data-years') : '';
              const yearSelect = document.getElementById('edit-user-year');
              yearSelect.innerHTML = '<option value="">Select Year</option>';
              if (years) {
                years.split(',').forEach(y => {
                  const o = document.createElement('option');
                  o.value = y.trim();
                  o.textContent = y.trim();
                  if (y.trim() === currentYear) o.selected = true;
                  yearSelect.appendChild(o);
                });
              }
            }
            window.updateEditUserYears = updateEditUserYears;
            updateEditUserYears();
          })();
        </script>
      <?php } else { ?>
        <div class="card" style="text-align:center;padding:32px">
          <p style="color:var(--text-dimmed)">User not found.</p>
          <a href="headdash.php?q=1" style="color:#a885ff">Back to Users</a>
        </div>
      <?php }
    } ?>

    <!-- SCORES -->
    <?php if (@$_GET['q'] == 2) {
      $sel_eid = @$_GET['eid'];
      $sel_dept = @$_GET['dept'];
      ?>
      <div class="page-header">
        <h1>Exam Scores</h1>
        <p>View student scores per exam with department filtering</p>
      </div>

      <!-- Filter Bar -->
      <div class="card" style="padding:20px">
        <form method="GET" action="headdash.php" style="display:flex;gap:16px;align-items:flex-end;flex-wrap:wrap">
          <input type="hidden" name="q" value="2">
          <div class="form-group" style="flex:1;min-width:220px;margin-bottom:0">
            <label>Select Exam</label>
            <select name="eid" onchange="this.form.submit()"
              style="appearance:auto;width:100%;padding:12px 16px;border-radius:10px;border:1px solid var(--border-input);background:var(--bg-input);color:var(--text-primary);font-size:14px;cursor:pointer">
              <option value="">— Choose an Exam —</option>
              <?php
              $eq = mysqli_query($con, "SELECT eid, title FROM quiz ORDER BY date DESC");
              while ($er = mysqli_fetch_array($eq)) {
                $selected = ($er['eid'] == $sel_eid) ? 'selected' : '';
                echo '<option value="' . $er['eid'] . '" ' . $selected . '>' . htmlspecialchars($er['title']) . '</option>';
              }
              ?>
            </select>
          </div>
          <div class="form-group" style="flex:1;min-width:220px;margin-bottom:0">
            <label>Department</label>
            <select name="dept" onchange="this.form.submit()"
              style="appearance:auto;width:100%;padding:12px 16px;border-radius:10px;border:1px solid var(--border-input);background:var(--bg-input);color:var(--text-primary);font-size:14px;cursor:pointer">
              <option value="">All Departments</option>
              <?php
              $dq = mysqli_query($con, "SELECT * FROM departments ORDER BY dept_name ASC");
              while ($dr = mysqli_fetch_array($dq)) {
                $selected = ($dr['dept_name'] == $sel_dept) ? 'selected' : '';
                echo '<option value="' . htmlspecialchars($dr['dept_name']) . '" ' . $selected . '>' . htmlspecialchars($dr['dept_name']) . '</option>';
              }
              ?>
            </select>
          </div>
        </form>
      </div>

      <?php if ($sel_eid) {
        // Get exam info
        $quiz_q = mysqli_query($con, "SELECT * FROM quiz WHERE eid='$sel_eid'");
        $quiz_row = mysqli_fetch_array($quiz_q);
        $exam_title = $quiz_row['title'];
        $total_marks = $quiz_row['sahi'] * $quiz_row['total'];
        $total_questions = $quiz_row['total'];
        $marks_per_correct = $quiz_row['sahi'];
        $marks_per_wrong = $quiz_row['wrong'];

        // Build query with optional department filter
        $sql = "SELECT h.email, h.score, h.sahi as correct, h.wrong, h.date, u.name, u.gender, u.college"
          . " FROM history h JOIN user u ON h.email = u.email"
          . " WHERE h.eid = '$sel_eid'";
        if ($sel_dept) {
          $sel_dept_esc = mysqli_real_escape_string($con, $sel_dept);
          $sql .= " AND u.college = '$sel_dept_esc'";
        }
        $sql .= " ORDER BY h.score DESC";
        $scores_q = mysqli_query($con, $sql);
        $num_students = mysqli_num_rows($scores_q);
        ?>

        <!-- Exam Summary -->
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:12px;margin-bottom:20px">
          <div class="card" style="text-align:center;padding:16px;margin-bottom:0">
            <div
              style="font-size:11px;text-transform:uppercase;letter-spacing:1px;color:var(--text-dimmed);margin-bottom:4px">
              Students</div>
            <div style="font-size:24px;font-weight:700;color:var(--text-primary)"><?php echo $num_students; ?></div>
          </div>
          <div class="card" style="text-align:center;padding:16px;margin-bottom:0">
            <div
              style="font-size:11px;text-transform:uppercase;letter-spacing:1px;color:var(--text-dimmed);margin-bottom:4px">
              Total Marks</div>
            <div style="font-size:24px;font-weight:700;color:var(--text-primary)"><?php echo $total_marks; ?></div>
          </div>
          <div class="card" style="text-align:center;padding:16px;margin-bottom:0">
            <div
              style="font-size:11px;text-transform:uppercase;letter-spacing:1px;color:var(--text-dimmed);margin-bottom:4px">
              Questions</div>
            <div style="font-size:24px;font-weight:700;color:var(--text-primary)"><?php echo $total_questions; ?></div>
          </div>
          <div class="card" style="text-align:center;padding:16px;margin-bottom:0">
            <div
              style="font-size:11px;text-transform:uppercase;letter-spacing:1px;color:var(--text-dimmed);margin-bottom:4px">
              +/− per Q</div>
            <div style="font-size:24px;font-weight:700;color:var(--text-primary)">+<?php echo $marks_per_correct; ?> /
              −<?php echo $marks_per_wrong; ?></div>
          </div>
        </div>

        <!-- Scores Table -->
        <div class="card">
          <h3 style="margin-bottom:16px"><?php echo htmlspecialchars($exam_title); ?> —
            Scores<?php echo $sel_dept ? ' (' . htmlspecialchars($sel_dept) . ')' : ''; ?></h3>
          <table class="data-table" id="scores-table">
            <thead>
              <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Department</th>
                <th>Score</th>
                <th>Correct</th>
                <th>Wrong</th>
                <th>Date</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $c = 0;
              while ($sr = mysqli_fetch_array($scores_q)) {
                $c++;
                $pct = $total_marks > 0 ? round(($sr['score'] / $total_marks) * 100) : 0;
                $pct_color = $pct >= 70 ? '#2ecc71' : ($pct >= 50 ? '#f39c12' : '#e74c3c');
                $date_fmt = date('d M Y, H:i', strtotime($sr['date']));
                echo '<tr>';
                echo '<td>' . $c . '</td>';
                echo '<td>' . htmlspecialchars($sr['name']) . '</td>';
                echo '<td style="color:var(--text-dimmed);font-size:13px">' . htmlspecialchars($sr['email']) . '</td>';
                echo '<td>' . htmlspecialchars($sr['college']) . '</td>';
                echo '<td><span style="font-weight:700">' . $sr['score'] . '</span><span style="color:var(--text-dimmed)"> / ' . $total_marks . '</span> <span style="color:' . $pct_color . ';font-size:12px;font-weight:600">(' . $pct . '%)</span></td>';
                echo '<td style="color:#2ecc71;font-weight:600">' . $sr['correct'] . '</td>';
                echo '<td style="color:#e74c3c;font-weight:600">' . $sr['wrong'] . '</td>';
                echo '<td style="font-size:13px;color:var(--text-dimmed)">' . $date_fmt . '</td>';
                echo '</tr>';
              }
              if ($c == 0) {
                echo '<tr><td colspan="8" style="text-align:center;padding:32px;color:var(--text-dimmed)">No students have taken this exam yet.</td></tr>';
              }
              ?>
            </tbody>
          </table>
        </div>

        <?php if ($c > 0) { ?>
          <!-- Export Bar -->
          <div class="card" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px">
            <div style="font-size:14px;color:var(--text-dimmed)">
              <span class="material-icons" style="font-size:18px;vertical-align:middle">download</span>
              Export <?php echo $c; ?> record<?php echo $c > 1 ? 's' : ''; ?>
            </div>
            <div style="display:flex;gap:10px">
              <button onclick="exportPDF()" class="btn-action btn-primary"
                style="display:inline-flex;align-items:center;gap:6px;padding:10px 20px;border:none;cursor:pointer;font-family:inherit">
                <span class="material-icons" style="font-size:18px">picture_as_pdf</span> Export PDF
              </button>
              <button onclick="exportExcel()" class="btn-action"
                style="display:inline-flex;align-items:center;gap:6px;padding:10px 20px;background:#217346;color:#fff;border-radius:8px;border:none;cursor:pointer;font-family:inherit;font-weight:600">
                <span class="material-icons" style="font-size:18px">table_chart</span> Export Excel
              </button>
            </div>
          </div>

          <!-- Export Libraries -->
          <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
          <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.2/jspdf.plugin.autotable.min.js"></script>
          <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
          <script>
            function getTableData() {
              const table = document.getElementById('scores-table');
              const headers = [];
              const rows = [];
              table.querySelectorAll('thead th').forEach(th => headers.push(th.textContent.trim()));
              table.querySelectorAll('tbody tr').forEach(tr => {
                const row = [];
                tr.querySelectorAll('td').forEach(td => row.push(td.textContent.trim()));
                if (row.length === headers.length) rows.push(row);
              });
              return { headers, rows };
            }

            function exportPDF() {
              const { jsPDF } = window.jspdf;
              const doc = new jsPDF('l', 'mm', 'a4');
              const examTitle = <?php echo json_encode($exam_title); ?>;
              const dept = <?php echo json_encode($sel_dept ?: 'All Departments'); ?>;
              const date = new Date().toLocaleDateString();

              doc.setFontSize(18);
              doc.text('ECUSTA — Exam Scores Report', 14, 18);
              doc.setFontSize(11);
              doc.setTextColor(100);
              doc.text('Exam: ' + examTitle + '  |  Department: ' + dept + '  |  Generated: ' + date, 14, 26);

              const { headers, rows } = getTableData();
              doc.autoTable({
                head: [headers],
                body: rows,
                startY: 32,
                theme: 'grid',
                styles: { fontSize: 9, cellPadding: 3 },
                headStyles: { fillColor: [127, 0, 255], textColor: 255, fontStyle: 'bold' },
                alternateRowStyles: { fillColor: [245, 245, 255] }
              });

              doc.save(examTitle.replace(/[^a-zA-Z0-9]/g, '_') + '_Scores.pdf');
            }

            function exportExcel() {
              const examTitle = <?php echo json_encode($exam_title); ?>;
              const { headers, rows } = getTableData();
              const data = [headers, ...rows];
              const ws = XLSX.utils.aoa_to_sheet(data);

              // Style column widths
              ws['!cols'] = headers.map((h, i) => ({ wch: Math.max(h.length, ...rows.map(r => (r[i] || '').length)) + 4 }));

              const wb = XLSX.utils.book_new();
              XLSX.utils.book_append_sheet(wb, ws, 'Scores');
              XLSX.writeFile(wb, examTitle.replace(/[^a-zA-Z0-9]/g, '_') + '_Scores.xlsx');
            }
          </script>
        <?php } ?>

      <?php } else { ?>
        <!-- No exam selected -->
        <div class="card" style="text-align:center;padding:48px">
          <span class="material-icons" style="font-size:56px;color:var(--text-faint);margin-bottom:12px">quiz</span>
          <h3 style="color:var(--text-primary);margin-bottom:8px">Select an Exam</h3>
          <p style="color:var(--text-dimmed);font-size:14px">Choose an exam from the dropdown above to view student scores.
          </p>
        </div>
      <?php } ?>
    <?php } ?>

    <!-- FEEDBACK -->
    <?php if (@$_GET['q'] == 3 && !@$_GET['fid']) { ?>
      <div class="page-header">
        <h1>Feedback</h1>
        <p>User feedback and messages</p>
      </div>
      <div class="card">
        <table class="data-table">
          <thead>
            <tr>
              <th>#</th>
              <th>Subject</th>
              <th>Email</th>
              <th>Date</th>
              <th>Time</th>
              <th>By</th>
              <th></th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <?php
            $result = mysqli_query($con, "SELECT * FROM feedback ORDER BY date DESC") or die('Error');
            $c = 1;
            while ($row = mysqli_fetch_array($result)) {
              $date = date("d-m-Y", strtotime($row['date']));
              echo '<tr><td>' . $c++ . '</td><td><a href="headdash.php?q=3&fid=' . $row['id'] . '" style="color:#a885ff;text-decoration:none">' . $row['subject'] . '</a></td><td>' . $row['email'] . '</td><td>' . $date . '</td><td>' . $row['time'] . '</td><td>' . $row['name'] . '</td>';
              echo '<td><a href="headdash.php?q=3&fid=' . $row['id'] . '" style="color:#4facfe"><span class="material-icons" style="font-size:18px">open_in_new</span></a></td>';
              echo '<td><a href="update.php?fdid=' . $row['id'] . '" class="btn-action btn-danger" title="Delete"><span class="material-icons" style="font-size:16px">delete</span></a></td></tr>';
            }
            ?>
          </tbody>
        </table>
      </div>
    <?php } ?>

    <!-- Feedback detail -->
    <?php if (@$_GET['fid']) {
      $id = @$_GET['fid'];
      $result = mysqli_query($con, "SELECT * FROM feedback WHERE id='$id'") or die('Error');
      while ($row = mysqli_fetch_array($result)) {
        echo '<div class="page-header"><h1>' . $row['subject'] . '</h1><p>' . date("d-m-Y", strtotime($row['date'])) . ' &bull; ' . $row['time'] . ' &bull; By ' . $row['name'] . '</p></div>';
        echo '<div class="card" style="line-height:1.8;color:rgba(255,255,255,0.75)">' . $row['feedback'] . '</div>';
        echo '<a href="headdash.php?q=3" style="color:#a885ff;text-decoration:none;font-size:14px"><span class="material-icons" style="font-size:16px;vertical-align:middle">arrow_back</span> Back to Feedback</a>';
      }
    } ?>

    <!-- ADD TEACHER -->
    <?php if (@$_GET['q'] == 4) { ?>
      <div class="page-header">
        <h1>Add Teacher</h1>
        <p>Register a new teacher account</p>
      </div>
      <div class="card" style="max-width:500px">
        <form action="includes/signadmin.php?q=headdash.php?q=4" method="POST">
          <div class="form-group">
            <label>Teacher Email</label>
            <input name="email" type="email" placeholder="teacher@ecusta.edu.et" required>
          </div>
          <div class="form-group">
            <label>Password</label>
            <input name="password" type="password" placeholder="Enter password" required>
          </div>
          <button type="submit" class="btn-submit">Add Teacher</button>
        </form>
      </div>
    <?php } ?>

    <!-- MANAGE TEACHERS -->
    <?php if (@$_GET['q'] == 5) { ?>
      <div class="page-header">
        <h1>Manage Teachers</h1>
        <p>Edit or remove teacher accounts</p>
      </div>
      <div class="card">
        <table class="data-table">
          <thead>
            <tr>
              <th>#</th>
              <th>Email</th>
              <th>Role</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $result = mysqli_query($con, "SELECT * FROM admin WHERE role='admin'") or die('Error');
            $c = 1;
            while ($row = mysqli_fetch_array($result)) {
              echo '<tr><td>' . $c++ . '</td><td>' . htmlspecialchars($row['email']) . '</td><td><span style="background:var(--bg-input);padding:3px 10px;border-radius:6px;font-size:12px;font-weight:600;letter-spacing:0.5px;text-transform:uppercase;color:var(--text-primary)">' . htmlspecialchars($row['role']) . '</span></td>';
              echo '<td style="display:flex;gap:6px">';
              echo '<a href="headdash.php?q=edit_admin&email=' . urlencode($row['email']) . '" class="btn-action btn-primary" title="Edit teacher"><span class="material-icons" style="font-size:16px">edit</span></a>';
              echo '<a href="update.php?demail1=' . urlencode($row['email']) . '" class="btn-action btn-danger" title="Delete teacher" onclick="return confirm(\'Delete this teacher account?\')" ><span class="material-icons" style="font-size:16px">delete</span></a>';
              echo '</td></tr>';
            }
            ?>
          </tbody>
        </table>
      </div>
    <?php } ?>

    <!-- EDIT ADMIN/TEACHER -->
    <?php if (@$_GET['q'] == 'edit_admin') {
      $aemail = @$_GET['email'];
      $aq = mysqli_query($con, "SELECT * FROM admin WHERE email='" . mysqli_real_escape_string($con, $aemail) . "'");
      $arow = mysqli_fetch_array($aq);
      if ($arow) {
        $is_head = ($arow['role'] === 'head');
        $page_title = $is_head ? 'Edit System Admin Account' : 'Edit Teacher Account';
        $back_link = $is_head ? 'headdash.php?q=0' : 'headdash.php?q=5';
        $redirect_val = $is_head ? 'headdash.php?q=0' : 'headdash.php?q=5';
        ?>
        <div class="page-header">
          <h1><?php echo $page_title; ?></h1>
          <p>Update credentials for <?php echo htmlspecialchars($arow['email']); ?></p>
        </div>
        <div class="card" style="max-width:550px">
          <form action="update.php?q=update_admin" method="POST">
            <input type="hidden" name="old_email" value="<?php echo htmlspecialchars($arow['email']); ?>">
            <input type="hidden" name="redirect" value="<?php echo $redirect_val; ?>">
            <div class="form-group">
              <label>Email (Username)</label>
              <input name="new_email" type="email" value="<?php echo htmlspecialchars($arow['email']); ?>" required>
            </div>
            <div class="form-group">
              <label>New Password <span style="color:var(--text-dimmed);font-weight:400">(leave blank to keep
                  current)</span></label>
              <input name="new_password" type="password" placeholder="Enter new password" minlength="5">
            </div>
            <div style="display:flex;gap:12px;margin-top:8px">
              <button type="submit" class="btn-submit" style="flex:1">Save Changes</button>
              <a href="<?php echo $back_link; ?>" class="btn-action btn-danger"
                style="display:flex;align-items:center;justify-content:center;padding:14px 24px;border-radius:12px;text-decoration:none;font-weight:600">Cancel</a>
            </div>
          </form>
        </div>
      <?php } else { ?>
        <div class="card" style="text-align:center;padding:32px">
          <p style="color:var(--text-dimmed)">Account not found.</p>
          <a href="headdash.php?q=5" style="color:#a885ff">Back to Teachers</a>
        </div>
      <?php }
    } ?>

    <!-- ADD EXAM (Admin) -->
    <?php if (@$_GET['q'] == 6 && !@$_GET['step']) { ?>
      <div class="page-header">
        <h1>Create New Exam</h1>
        <p>Enter exam details — you'll add questions next</p>
      </div>
      <div class="card" style="max-width:600px">
        <form action="update.php?q=addquiz&from=admin" method="POST">
          <div class="form-group">
            <label>Exam Title</label>
            <input name="name" type="text" placeholder="e.g. Data Structures Final" required>
          </div>
          <div class="form-group">
            <label>Access Code (Mandatory)</label>
            <input name="access_code" type="text" placeholder="e.g. SECRET123" maxlength="20" required>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label>Total Questions</label>
              <input name="total" type="number" min="1" placeholder="e.g. 10" required>
            </div>
            <div class="form-group">
              <label>Time Limit (min)</label>
              <input name="time" type="number" min="1" placeholder="e.g. 30" required>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label>Marks per Correct</label>
              <input name="right" type="number" min="0" placeholder="e.g. 2" required>
            </div>
            <div class="form-group">
              <label>Minus per Wrong</label>
              <input name="wrong" type="number" min="0" placeholder="e.g. 1" required>
            </div>
          </div>
          <div class="form-group">
            <label>Tag</label>
            <input name="tag" type="text" placeholder="e.g. #final #cs">
          </div>
          <div class="form-row">
            <div class="form-group">
              <label>Target Department (Optional)</label>
              <select name="target_dept"
                style="appearance:auto;padding:11px 16px;border-radius:10px;border:1px solid var(--border-input);background:var(--bg-input);color:var(--text-primary);width:100%">
                <option value="">All Departments</option>
                <?php
                $d_query = mysqli_query($con, "SELECT * FROM departments ORDER BY dept_name ASC");
                while ($d_row = mysqli_fetch_array($d_query)) {
                  echo "<option value='" . $d_row['dept_name'] . "'>" . $d_row['dept_name'] . "</option>";
                }
                ?>
              </select>
            </div>
            <div class="form-group">
              <label>Target Year (Optional)</label>
              <select name="target_year" style="appearance:auto;padding:13px 16px;">
                <option value="">All Years</option>
                <option value="1st Year">1st Year</option>
                <option value="2nd Year">2nd Year</option>
                <option value="3rd Year">3rd Year</option>
                <option value="4th Year">4th Year</option>
                <option value="5th Year">5th Year</option>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label>Description</label>
            <textarea name="desc" placeholder="Write quiz description..."></textarea>
          </div>
          <button type="submit" class="btn-submit">Create Exam &amp; Add Questions</button>
        </form>
      </div>
    <?php } ?>

    <!-- ADD QUESTIONS (Admin) -->
    <?php if (@$_GET['q'] == 6 && @$_GET['step'] == 2) { ?>
      <div class="page-header">
        <h1>Add Questions</h1>
        <p>Enter questions and options for the quiz</p>
      </div>
      <div class="card" style="max-width:700px">
        <form action="update.php?q=addqns&n=<?php echo @$_GET['n']; ?>&eid=<?php echo @$_GET['eid']; ?>&ch=4&from=admin"
          method="POST">
          <?php for ($i = 1; $i <= @$_GET['n']; $i++) { ?>
            <div class="question-block" id="q-block-<?php echo $i; ?>">
              <div class="form-row">
                <h3 style="flex:1">Question <?php echo $i; ?></h3>
                <div class="form-group" style="max-width:200px">
                  <select name="type<?php echo $i; ?>" onchange="toggleQuestionType(<?php echo $i; ?>, this.value)"
                    style="padding:8px;font-size:12px">
                    <option value="mcq">Multiple Choice</option>
                    <option value="short">Short Answer</option>
                    <option value="code">Code Snippet</option>
                    <option value="match">Matching</option>
                  </select>
                </div>
              </div>

              <div class="form-group">
                <label>Question Text</label>
                <textarea name="qns<?php echo $i; ?>" placeholder="Enter question..." required></textarea>
              </div>

              <!-- MCQ Inputs -->
              <div id="mcq-<?php echo $i; ?>" class="type-input">
                <div class="form-row">
                  <div class="form-group">
                    <label>Option A</label>
                    <input name="<?php echo $i; ?>1" type="text" placeholder="Option A">
                  </div>
                  <div class="form-group">
                    <label>Option B</label>
                    <input name="<?php echo $i; ?>2" type="text" placeholder="Option B">
                  </div>
                </div>
                <div class="form-row">
                  <div class="form-group">
                    <label>Option C</label>
                    <input name="<?php echo $i; ?>3" type="text" placeholder="Option C">
                  </div>
                  <div class="form-group">
                    <label>Option D</label>
                    <input name="<?php echo $i; ?>4" type="text" placeholder="Option D">
                  </div>
                </div>
                <div class="form-group">
                  <label>Correct Answer</label>
                  <select name="ans<?php echo $i; ?>" style="appearance:auto">
                    <option value="a">Option A</option>
                    <option value="b">Option B</option>
                    <option value="c">Option C</option>
                    <option value="d">Option D</option>
                  </select>
                </div>
              </div>

              <!-- Short Answer & Code Inputs -->
              <div id="short-<?php echo $i; ?>" class="type-input" style="display:none">
                <div class="form-group">
                  <label>Correct Answer (Exact Match)</label>
                  <textarea name="ans_text<?php echo $i; ?>" placeholder="Enter the correct answer text..."></textarea>
                </div>
              </div>

              <!-- Matching Inputs -->
              <div id="match-<?php echo $i; ?>" class="type-input" style="display:none">
                <p style="font-size:12px;color:rgba(255,255,255,0.5);margin-bottom:10px">Enter matching pairs. They will be
                  shuffled for students.</p>
                <?php for ($k = 1; $k <= 4; $k++) { ?>
                  <div class="form-row" style="margin-bottom:8px">
                    <input name="match_left_<?php echo $i; ?>_<?php echo $k; ?>" type="text"
                      placeholder="Item <?php echo $k; ?> Left" style="flex:1">
                    <span style="padding-top:10px">=</span>
                    <input name="match_right_<?php echo $i; ?>_<?php echo $k; ?>" type="text"
                      placeholder="Item <?php echo $k; ?> Right" style="flex:1">
                  </div>
                <?php } ?>
              </div>

            </div>
          <?php } ?>
          <button type="submit" class="btn-submit">Save All Questions</button>
        </form>
      </div>
      <script>
        function toggleQuestionType(id, type) {
          // Hide all inputs for this question
          document.getElementById('mcq-' + id).style.display = 'none';
          document.getElementById('short-' + id).style.display = 'none';
          document.getElementById('match-' + id).style.display = 'none';

          // Show selected
          if (type === 'mcq' || type === 'code') {
            document.getElementById('mcq-' + id).style.display = 'block';
          } else if (type === 'short') {
            document.getElementById('short-' + id).style.display = 'block';
          } else if (type === 'match') {
            document.getElementById('match-' + id).style.display = 'block';
          }
        }
      </script>
    <?php } ?>

    <!-- MANAGE EXAMS (Admin) -->
    <?php if (@$_GET['q'] == 7) { ?>
      <div class="page-header">
        <h1>Manage Exams</h1>
        <p>Edit, release, or remove exams</p>
      </div>
      <div class="card">
        <table class="data-table">
          <thead>
            <tr>
              <th>#</th>
              <th>Topic</th>
              <th>Access Code</th>
              <th>Status</th>
              <th>Questions</th>
              <th>Marks</th>
              <th>Time</th>
              <th>Created By</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $result = mysqli_query($con, "SELECT * FROM quiz ORDER BY date DESC") or die('Error');
            $c = 1;
            while ($row = mysqli_fetch_array($result)) {
              $status = @$row['status'] ?: 'active';
              $status_color = ($status == 'active' ? '#2ecc71' : '#e74c3c');
              $code = htmlspecialchars(@$row['access_code'] ?: '—');
              echo '<tr><td>' . $c++ . '</td><td>' . $row['title'] . '</td><td><code style="background:var(--bg-input);padding:3px 8px;border-radius:6px;font-size:13px;letter-spacing:1px;font-weight:600;color:var(--text-primary)">' . $code . '</code></td><td><span class="badge" style="background:' . $status_color . ';color:#fff;padding:2px 6px;border-radius:4px;font-size:10px;text-transform:uppercase">' . $status . '</span></td><td>' . $row['total'] . '</td><td>' . ($row['sahi'] * $row['total']) . '</td><td>' . $row['time'] . ' min</td><td style="color:rgba(255,255,255,0.4)">' . $row['email'] . '</td>';
              echo '<td>
                <a href="headdash.php?q=manage_quiz&eid=' . $row['eid'] . '" class="btn-action btn-primary" style="margin-right:5px"><span class="material-icons" style="font-size:16px">edit</span> Manage</a>
                <a href="update.php?q=rmquiz&eid=' . $row['eid'] . '&from=admin" class="btn-action btn-danger"><span class="material-icons" style="font-size:16px">delete</span> Remove</a>
              </td></tr>';
            }
            ?>
          </tbody>
        </table>
      </div>
    <?php } ?>

    <!-- ADD USER -->
    <?php if (@$_GET['q'] == 8) { ?>
      <div class="page-header">
        <h1>Add Student</h1>
        <p>Register a new student account</p>
      </div>
      <div class="card" style="max-width:550px">
        <form action="update.php?q=adduser" method="POST">
          <div class="form-row">
            <div class="form-group">
              <label>Full Name</label>
              <input name="name" type="text" placeholder="Student name" required>
            </div>
            <div class="form-group">
              <label>Gender</label>
              <select name="gender" style="appearance:auto" required>
                <option value="" disabled selected>Select</option>
                <option value="M">Male</option>
                <option value="F">Female</option>
              </select>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label>College / Department</label>
              <select id="user-dept" name="college" style="appearance:auto" required onchange="updateUserYears()">
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
              <label>Year</label>
              <select id="user-year" name="year" style="appearance:auto" required>
                <option value="" disabled selected>Select Department First</option>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label>Email Address</label>
            <input name="email" type="email" placeholder="student@ecusta.edu.et" required>
          </div>
          <div class="form-group">
            <label>Mobile Number</label>
            <input name="mob" type="number" placeholder="e.g. 0912345678" required>
          </div>
          <div class="form-group">
            <label>Password</label>
            <input name="password" type="password" placeholder="Min 5 characters" minlength="5" required>
          </div>
          <button type="submit" class="btn-submit">Create Student Account</button>
        </form>
      </div>
    <?php } ?>

    <!-- MANAGE QUIZ (Admin) -->
    <?php if (@$_GET['q'] == 'manage_quiz') {
      $eid = @$_GET['eid'];
      $q = mysqli_query($con, "SELECT * FROM quiz WHERE eid='$eid' ");
      while ($row = mysqli_fetch_array($q)) {
        $title = $row['title'];
        $total = $row['total'];
        $sahi = $row['sahi'];
        $wrong = $row['wrong'];
        $time = $row['time'];
        $tag = $row['tag'];
        $intro = $row['intro'];
        $target_dept = $row['target_dept'];
        $target_year = $row['target_year'];
        $status = @$row['status'];
        $access_code = @$row['access_code'];
      }
      ?>
      <div class="page-header">
        <h1>Manage Exam: <?php echo $title; ?></h1>
        <p>Edit details, questions, and manage student attempts</p>
      </div>

      <!-- Access Code Display -->
      <div class="card" style="display:flex;align-items:center;gap:16px;padding:20px;border-left:4px solid #7F00FF">
        <span class="material-icons" style="font-size:28px;color:#a885ff">vpn_key</span>
        <div>
          <div
            style="font-size:11px;text-transform:uppercase;letter-spacing:1px;color:var(--text-dimmed);margin-bottom:4px">
            Access Code</div>
          <div style="font-size:22px;font-weight:700;letter-spacing:3px;color:var(--text-primary);font-family:monospace">
            <?php echo htmlspecialchars($access_code ?: '—'); ?>
          </div>
        </div>
      </div>

      <!-- Admin Controls -->
      <div class="card" style="border-left: 4px solid #7F00FF;">
        <h3>Admin Controls</h3>
        <div class="form-row" style="align-items:center">
          <p style="margin:0;margin-right:16px;white-space:nowrap">Extend exam time for all students by:</p>
          <form action="update.php?q=extendtime" method="POST" style="display:flex;align-items:center;gap:10px;flex:1">
            <input type="hidden" name="eid" value="<?php echo $eid; ?>">
            <select name="time_add"
              style="padding:10px;border-radius:8px;border:1px solid var(--border-input);background:var(--bg-input);color:var(--text-primary);cursor:pointer">
              <option value="5">+5 Mins</option>
              <option value="10" selected>+10 Mins</option>
              <option value="15">+15 Mins</option>
              <option value="30">+30 Mins</option>
              <option value="60">+1 Hour</option>
            </select>
            <button type="submit" class="btn-action btn-primary">Extend Time</button>
          </form>
        </div>
        <hr style="margin:16px 0;border-top:1px solid var(--border-primary)">
        <h4>Reset Attempt for User</h4>
        <form action="update.php?q=reset_user_exam" method="POST" class="form-row" style="align-items:flex-end">
          <input type="hidden" name="eid" value="<?php echo $eid; ?>">
          <div class="form-group" style="flex:1">
            <label>Student Email</label>
            <input name="email" type="email" placeholder="student@example.com" required>
          </div>
          <button type="submit" class="btn-action btn-danger" style="margin-bottom:2px">Reset Attempt</button>
        </form>

        <hr style="margin:16px 0;border-top:1px solid var(--border-primary)">
        <h4 style="color:var(--danger)">Danger Zone</h4>
        <form action="update.php?q=reset_all_exam" method="POST"
          onsubmit="return confirm('WARNING: This will delete ALL student attempts for this quiz. This cannot be undone. Are you sure?')">
          <input type="hidden" name="eid" value="<?php echo $eid; ?>">
          <button type="submit" class="btn-action btn-danger"
            style="width:100%;background:var(--danger);border-color:var(--danger)">Reset All Attempts (Re-open for
            Everyone)</button>
        </form>
      </div>

      <!-- Exam Details Form -->
      <div class="card">
        <h3>Exam Details</h3>
        <form action="update.php?q=editquiz&eid=<?php echo $eid; ?>&from=admin" method="POST">
          <div class="form-row">
            <div class="form-group">
              <label>Exam Title</label>
              <input name="name" type="text" value="<?php echo $title; ?>" required>
            </div>
            <div class="form-group">
              <label>Time Limit (min)</label>
              <input name="time" type="number" value="<?php echo $time; ?>" required>
            </div>
          </div>
      </div>

      <!-- Exam Status Toggle -->
      <div class="form-group"
        style="margin:20px 0;padding:16px;background:var(--bg-card-hover);border-radius:12px;border:1px solid var(--border-primary)">
        <div style="display:flex;justify-content:space-between;align-items:center">
          <div>
            <label style="margin-bottom:4px;display:block;font-weight:600">Exam Release Status</label>
            <div style="font-size:14px;color:var(--text-secondary)">
              Current Status: <span class="badge"
                style="background:<?php echo ($status == 'active' ? '#2ecc71' : '#e74c3c'); ?>;color:#fff;padding:4px 10px;border-radius:6px;text-transform:uppercase;font-size:11px;font-weight:700">
                <?php echo ucfirst($status ? $status : 'active'); ?></span>
            </div>
          </div>
          <a href="update.php?q=toggle_exam_status&eid=<?php echo $eid; ?>&status=<?php echo ($status == 'active' ? 'disabled' : 'active'); ?>&from=admin"
            class="btn-action <?php echo ($status == 'active' ? 'btn-danger' : 'btn-success'); ?>"
            style="padding:10px 20px;border-radius:8px;text-decoration:none;font-weight:600">
            <?php echo ($status == 'active' ? 'Disable Exam' : 'Release Exam (Make Active)'); ?>
          </a>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label>Target Dept</label>
          <select name="target_dept" style="appearance:auto;padding:13px 16px;">
            <option value="" <?php if ($target_dept == '')
              echo 'selected'; ?>>All Departments</option>
            <?php
            $d_query = mysqli_query($con, "SELECT * FROM departments ORDER BY dept_name ASC");
            while ($d_row = mysqli_fetch_array($d_query)) {
              $dept = $d_row['dept_name'];
              $sel = ($dept == $target_dept) ? 'selected' : '';
              echo "<option value='$dept' $sel>$dept</option>";
            }
            ?>
          </select>
        </div>
        <div class="form-group">
          <label>Target Year</label>
          <select name="target_year" style="appearance:auto;padding:13px 16px;">
            <option value="" <?php if ($target_year == '')
              echo 'selected'; ?>>All Years</option>
            <option value="1st Year" <?php if ($target_year == '1st Year')
              echo 'selected'; ?>>1st Year</option>
            <option value="2nd Year" <?php if ($target_year == '2nd Year')
              echo 'selected'; ?>>2nd Year</option>
            <option value="3rd Year" <?php if ($target_year == '3rd Year')
              echo 'selected'; ?>>3rd Year</option>
            <option value="4th Year" <?php if ($target_year == '4th Year')
              echo 'selected'; ?>>4th Year</option>
            <option value="5th Year" <?php if ($target_year == '5th Year')
              echo 'selected'; ?>>5th Year</option>
          </select>
        </div>
      </div>
      <button type="submit" class="btn-submit" style="width:auto;padding:10px 24px">Update Details</button>
      </form>
      </div>

      <!-- Questions List -->
      <div class="card">
        <h3>Questions</h3>
        <table class="data-table">
          <thead>
            <tr>
              <th>#</th>
              <th>Question</th>
              <th>Type</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $q = mysqli_query($con, "SELECT * FROM questions WHERE eid='$eid' ORDER BY sn ASC");
            while ($row = mysqli_fetch_array($q)) {
              $qid = $row['qid'];
              $qns = $row['qns'];
              $sn = $row['sn'];
              $type = $row['question_type'];
              if (empty($type))
                $type = 'mcq';
              echo '<tr><td>' . $sn . '</td><td>' . htmlspecialchars($qns) . '</td><td>' . strtoupper($type) . '</td>
              <td style="display:flex;gap:6px">
                <a href="headdash.php?q=edit_question&qid=' . $qid . '" class="btn-action btn-primary"><span class="material-icons" style="font-size:16px">edit</span> Edit</a>
                <a href="update.php?q=delete_question&qid=' . $qid . '&eid=' . $eid . '&from=admin" class="btn-action btn-danger" onclick="return confirm(\'Delete this question? This cannot be undone.\')"><span class="material-icons" style="font-size:16px">delete</span> Remove</a>
              </td></tr>';
            }
            ?>
          </tbody>
        </table>
        <div style="margin-top:16px">
          <a href="headdash.php?q=6&step=2&eid=<?php echo $eid; ?>&n=1" class="btn-action btn-primary"
            style="display:inline-block">+ Add Question</a>
        </div>
      </div>
    <?php } ?>

    <!-- EDIT QUESTION (Admin) -->
    <?php if (@$_GET['q'] == 'edit_question') {
      $qid = @$_GET['qid'];
      $q = mysqli_query($con, "SELECT * FROM questions WHERE qid='$qid'");
      while ($row = mysqli_fetch_array($q)) {
        $qns = $row['qns'];
        $eid = $row['eid'];
        $type = $row['question_type'];
        if (empty($type))
          $type = 'mcq';
      }
      ?>
      <div class="page-header">
        <h1>Edit Question</h1>
      </div>
      <div class="card" style="max-width:700px">
        <form action="update.php?q=editqns&qid=<?php echo $qid; ?>&from=admin" method="POST">
          <input type="hidden" name="eid" value="<?php echo $eid; ?>">
          <div class="form-group">
            <label>Question Text</label>
            <textarea name="qns" required><?php echo htmlspecialchars(stripslashes($qns)); ?></textarea>
          </div>

          <?php if ($type == 'mcq') {
            $options = [];
            $q2 = mysqli_query($con, "SELECT * FROM options WHERE qid='$qid'");
            while ($row2 = mysqli_fetch_array($q2)) {
              $options[$row2['optionid']] = $row2['option'];
            }
            $q3 = mysqli_query($con, "SELECT * FROM answer WHERE qid='$qid'");
            $ansid = '';
            while ($row3 = mysqli_fetch_array($q3)) {
              $ansid = $row3['ansid'];
            }
            $i = 1;
            foreach ($options as $oid => $opt) {
              echo '<div class="form-group"><label>Option ' . $i . '</label>';
              echo '<input name="option[]" type="text" value="' . htmlspecialchars(stripslashes($opt)) . '" required>';
              echo '<input name="oid[]" type="hidden" value="' . $oid . '">';
              echo '<div style="margin-top:4px"><input type="radio" name="ans" value="' . $oid . '" ' . ($ansid == $oid ? 'checked' : '') . '> Correct Answer</div>';
              echo '</div>';
              $i++;
            }
          } else if ($type == 'short' || $type == 'code') {
            $q3 = mysqli_query($con, "SELECT * FROM answer WHERE qid='$qid'");
            $ans_text = '';
            while ($row3 = mysqli_fetch_array($q3)) {
              $ans_text = $row3['ansid'];
            }
            echo '<div class="form-group"><label>Correct Answer</label>';
            echo '<textarea name="ans_text" required>' . htmlspecialchars(stripslashes($ans_text)) . '</textarea></div>';
          }
          ?>
          <button type="submit" class="btn-submit">Update Question</button>
        </form>
      </div>
    <?php } ?>

    <!-- MANAGE DEPARTMENTS (Admin) -->
    <?php if (@$_GET['q'] == 'manage_dept') { ?>
      <div class="page-header">
        <h1>Manage Departments</h1>
        <p>Add, remove, and configure departments</p>
      </div>

      <!-- Add Dept -->
      <div class="card">
        <h3>Add New Department</h3>
        <form action="update.php?q=add_dept" method="POST">
          <div class="form-row">
            <div class="form-group">
              <label>Department Name</label>
              <input name="dept_name" type="text" placeholder="e.g. Electrical Engineering" required>
            </div>
            <div class="form-group">
              <label>Year Labels (Comma Separated)</label>
              <input name="year_labels" type="text" placeholder="e.g. 1st Year, 2nd Year, 3rd Year" required>
              <small style="color:var(--text-dimmed);margin-top:4px;display:block">
                Enter year names separated by commas. The number of years will be calculated automatically.
              </small>
            </div>
          </div>
          <button type="submit" class="btn-submit" style="width:auto">Add Department</button>
        </form>
      </div>

      <!-- Dept List -->
      <div class="card">
        <h3>Existing Departments</h3>
        <table class="data-table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Years</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $q = mysqli_query($con, "SELECT * FROM departments ORDER BY dept_name ASC");
            $c = 1;
            while ($row = mysqli_fetch_array($q)) {
              $years = explode(',', $row['year_labels']);
              $count = count($years);
              echo '<tr><td>' . $c++ . '</td><td>' . htmlspecialchars($row['dept_name']) . '</td><td>' . $count . ' (' . htmlspecialchars(implode(', ', $years)) . ')</td>
              <td><a href="update.php?q=delete_dept&id=' . $row['dept_id'] . '" class="btn-action btn-danger" onclick="return confirm(\'Are you sure? This may affect users linked to this department.\')"><span class="material-icons" style="font-size:16px">delete</span> Remove</a></td></tr>';
            }
            ?>
          </tbody>
        </table>
      </div>
    <?php } ?>

  </main>
  <script>
    function showAlert(msg, type) {
      const el = document.createElement('div');
      el.className = 'alert-toast ' + (type || 'error');
      el.textContent = msg;
      document.body.appendChild(el);
      setTimeout(() => el.remove(), 4500);
    }
  </script>
  <script>
    function updateUserYears() {
      const select = document.getElementById('user-dept');
      const years = select.selectedOptions[0].getAttribute('data-years');
      const yearSelect = document.getElementById('user-year');
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

</html>