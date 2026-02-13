<?php
include_once 'dbConnection.php';
session_start();
if (!(isset($_SESSION['email']))) {
  header("location:admin_login.php");
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
      background: #0e1a2b;
      color: #e0e6ed;
      min-height: 100vh;
      display: flex;
    }

    .sidebar {
      width: 260px;
      background: linear-gradient(180deg, #1a0a2e 0%, #150826 100%);
      border-right: 1px solid rgba(255, 255, 255, 0.06);
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
      border-bottom: 1px solid rgba(255, 255, 255, 0.06);
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
      color: #fff;
    }

    .sidebar-brand .admin-badge {
      font-size: 9px;
      background: rgba(168, 133, 255, 0.2);
      color: #a885ff;
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
      color: rgba(255, 255, 255, 0.2);
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
      color: rgba(255, 255, 255, 0.45);
      text-decoration: none;
      font-size: 13px;
      font-weight: 500;
      transition: all 0.2s ease;
    }

    .nav-item:hover {
      background: rgba(255, 255, 255, 0.05);
      color: rgba(255, 255, 255, 0.8);
    }

    .nav-item.active {
      background: rgba(168, 133, 255, 0.12);
      color: #a885ff;
    }

    .nav-item .material-icons {
      font-size: 19px;
    }

    .sidebar-footer {
      padding: 14px 12px;
      border-top: 1px solid rgba(255, 255, 255, 0.06);
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
      background: linear-gradient(135deg, #a885ff, #e572ff);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 14px;
      font-weight: 700;
      color: #1a0a2e;
      flex-shrink: 0;
    }

    .sidebar-footer .user-name {
      font-size: 13px;
      font-weight: 600;
      color: #fff;
    }

    .sidebar-footer .user-email {
      font-size: 11px;
      color: rgba(255, 255, 255, 0.35);
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
      color: rgba(255, 255, 255, 0.35);
      text-decoration: none;
      font-size: 13px;
      transition: all 0.2s ease;
      margin-top: 6px;
    }

    .btn-signout:hover {
      background: rgba(255, 65, 108, 0.12);
      color: #ff416c;
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
      color: #fff;
      margin-bottom: 4px;
    }

    .page-header p {
      font-size: 13px;
      color: rgba(255, 255, 255, 0.4);
    }

    .card {
      background: rgba(255, 255, 255, 0.04);
      border: 1px solid rgba(255, 255, 255, 0.06);
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
      color: rgba(255, 255, 255, 0.3);
      border-bottom: 1px solid rgba(255, 255, 255, 0.06);
    }

    .data-table td {
      padding: 14px 16px;
      font-size: 14px;
      border-bottom: 1px solid rgba(255, 255, 255, 0.04);
      color: rgba(255, 255, 255, 0.7);
    }

    .data-table tbody tr:hover {
      background: rgba(255, 255, 255, 0.03);
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
      background: linear-gradient(135deg, #a885ff, #e572ff);
      color: #1a0a2e;
    }

    .btn-primary:hover {
      transform: translateY(-1px);
      box-shadow: 0 4px 15px rgba(168, 133, 255, 0.3);
    }

    .btn-danger {
      background: linear-gradient(135deg, #ff416c, #ff4b2b);
      color: #fff;
    }

    .btn-danger:hover {
      transform: translateY(-1px);
      box-shadow: 0 4px 15px rgba(255, 65, 108, 0.3);
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
      background: linear-gradient(135deg, #a885ff, #e572ff);
      color: #1a0a2e;
      transition: all 0.2s ease;
      margin-top: 8px;
    }

    .btn-submit:hover {
      transform: translateY(-1px);
      box-shadow: 0 6px 20px rgba(168, 133, 255, 0.3);
    }

    .form-group {
      margin-bottom: 18px;
    }

    .form-group label {
      display: block;
      font-size: 12px;
      font-weight: 500;
      color: rgba(255, 255, 255, 0.55);
      margin-bottom: 8px;
      letter-spacing: 0.5px;
      text-transform: uppercase;
    }

    .form-group input,
    .form-group textarea,
    .form-group select {
      width: 100%;
      padding: 13px 16px;
      background: rgba(255, 255, 255, 0.05);
      border: 1px solid rgba(255, 255, 255, 0.08);
      border-radius: 10px;
      color: #fff;
      font-family: 'Inter', sans-serif;
      font-size: 14px;
      outline: none;
      transition: all 0.2s ease;
    }

    .form-group input::placeholder,
    .form-group textarea::placeholder {
      color: rgba(255, 255, 255, 0.2);
    }

    .form-group input:focus,
    .form-group textarea:focus,
    .form-group select:focus {
      border-color: rgba(168, 133, 255, 0.5);
      background: rgba(255, 255, 255, 0.07);
    }

    .form-group textarea {
      resize: vertical;
      min-height: 100px;
    }

    .form-group select option {
      background: #1a0a2e;
    }

    .form-row {
      display: flex;
      gap: 12px;
    }

    .form-row .form-group {
      flex: 1;
    }

    .question-block {
      border: 1px solid rgba(255, 255, 255, 0.06);
      border-radius: 12px;
      padding: 20px;
      margin-bottom: 20px;
      background: rgba(255, 255, 255, 0.02);
    }

    .question-block h3 {
      font-size: 14px;
      color: #a885ff;
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
      background: rgba(255, 255, 255, 0.04);
      border: 1px solid rgba(255, 255, 255, 0.06);
    }

    .stat-card .label {
      font-size: 11px;
      text-transform: uppercase;
      letter-spacing: 1px;
      color: rgba(255, 255, 255, 0.35);
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
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
    }

    .alert-toast.error {
      background: linear-gradient(135deg, #ff416c, #ff4b2b);
    }

    .alert-toast.success {
      background: linear-gradient(135deg, #11998e, #38ef7d);
      color: #0e1a2b;
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
      background: rgba(255, 255, 255, 0.08);
      border: 1px solid rgba(255, 255, 255, 0.1);
      border-radius: 10px;
      padding: 8px;
      color: #fff;
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
      <img src="ecusta_logo.png" alt="Logo">
      <span>ECUSTA</span>
      <span class="admin-badge">Admin</span>
    </div>
    <div class="sidebar-nav">
      <div class="sidebar-label">Overview</div>
      <a href="headdash.php?q=0" class="nav-item <?php if (@$_GET['q'] == 0)
        echo 'active'; ?>">
        <span class="material-icons">dashboard</span> Dashboard
      </a>

      <div class="sidebar-label">Quiz Management</div>
      <a href="headdash.php?q=6" class="nav-item <?php if (@$_GET['q'] == 6 && !@$_GET['step'])
        echo 'active'; ?>">
        <span class="material-icons">add_circle</span> Add Quiz
      </a>
      <a href="headdash.php?q=7" class="nav-item <?php if (@$_GET['q'] == 7)
        echo 'active'; ?>">
        <span class="material-icons">delete_sweep</span> Remove Quiz
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
        <span class="material-icons">leaderboard</span> Rankings
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
      <a href="headdash.php?q=5" class="nav-item <?php if (@$_GET['q'] == 5)
        echo 'active'; ?>">
        <span class="material-icons">person_remove</span> Remove Teacher
      </a>
    </div>
    <div class="sidebar-footer">
      <div class="user-info">
        <div class="avatar"><span class="material-icons" style="font-size:18px">shield</span></div>
        <div>
          <div class="user-name">System Admin</div>
          <div class="user-email"><?php echo htmlspecialchars($email); ?></div>
        </div>
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
          <div class="label">Total Quizzes</div>
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
        <h2 style="color:#fff;font-size:18px;margin-bottom:16px">All Quizzes</h2>
        <table class="data-table">
          <thead>
            <tr>
              <th>#</th>
              <th>Topic</th>
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
              echo '<tr><td>' . $c++ . '</td><td>' . $row['title'] . '</td><td>' . $row['total'] . '</td><td>' . ($row['sahi'] * $row['total']) . '</td><td>' . $row['sahi'] . '</td><td>' . $row['wrong'] . '</td><td>' . $row['time'] . ' min</td></tr>';
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
              <th>College</th>
              <th>Email</th>
              <th>Mobile</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <?php
            $result = mysqli_query($con, "SELECT * FROM user") or die('Error');
            $c = 1;
            while ($row = mysqli_fetch_array($result)) {
              echo '<tr><td>' . $c++ . '</td><td>' . $row['name'] . '</td><td>' . $row['gender'] . '</td><td>' . $row['college'] . '</td><td>' . $row['email'] . '</td><td>' . $row['mob'] . '</td>';
              echo '<td><a href="update.php?demail=' . $row['email'] . '" class="btn-action btn-danger" title="Delete user"><span class="material-icons" style="font-size:16px">delete</span></a></td></tr>';
            }
            ?>
          </tbody>
        </table>
      </div>
    <?php } ?>

    <!-- RANKING -->
    <?php if (@$_GET['q'] == 2) { ?>
      <div class="page-header">
        <h1>Student Rankings</h1>
        <p>Overall leaderboard</p>
      </div>
      <div class="card">
        <table class="data-table">
          <thead>
            <tr>
              <th>Rank</th>
              <th>Name</th>
              <th>Gender</th>
              <th>College</th>
              <th>Score</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $q = mysqli_query($con, "SELECT * FROM rank ORDER BY score DESC") or die('Error');
            $c = 0;
            while ($row = mysqli_fetch_array($q)) {
              $e = $row['email'];
              $s = $row['score'];
              $q12 = mysqli_query($con, "SELECT * FROM user WHERE email='$e'") or die('Error');
              while ($r2 = mysqli_fetch_array($q12)) {
                $uname = $r2['name'];
                $gender = $r2['gender'];
                $college = $r2['college'];
              }
              $c++;
              $rankStyle = $c <= 3 ? 'color:#ffd700;font-weight:700' : 'color:#a885ff;font-weight:600';
              echo '<tr><td style="' . $rankStyle . '">' . $c . '</td><td>' . $uname . '</td><td>' . $gender . '</td><td>' . $college . '</td><td style="font-weight:700">' . $s . '</td></tr>';
            }
            ?>
          </tbody>
        </table>
      </div>
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
        <form action="signadmin.php?q=headdash.php?q=4" method="POST">
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

    <!-- REMOVE TEACHER -->
    <?php if (@$_GET['q'] == 5) { ?>
      <div class="page-header">
        <h1>Manage Teachers</h1>
        <p>Remove teacher accounts</p>
      </div>
      <div class="card">
        <table class="data-table">
          <thead>
            <tr>
              <th>Email</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <?php
            $result = mysqli_query($con, "SELECT * FROM admin WHERE role='admin'") or die('Error');
            while ($row = mysqli_fetch_array($result)) {
              echo '<tr><td>' . $row['email'] . '</td>';
              echo '<td><a href="update.php?demail1=' . $row['email'] . '" class="btn-action btn-danger"><span class="material-icons" style="font-size:16px">delete</span> Remove</a></td></tr>';
            }
            ?>
          </tbody>
        </table>
      </div>
    <?php } ?>

    <!-- ADD QUIZ (Admin) -->
    <?php if (@$_GET['q'] == 6 && !@$_GET['step']) { ?>
      <div class="page-header">
        <h1>Create New Quiz</h1>
        <p>Enter quiz details — you'll add questions next</p>
      </div>
      <div class="card" style="max-width:600px">
        <form action="update.php?q=addquiz&from=admin" method="POST">
          <div class="form-group">
            <label>Quiz Title</label>
            <input name="name" type="text" placeholder="e.g. Data Structures Final" required>
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
          <div class="form-group">
            <label>Description</label>
            <textarea name="desc" placeholder="Write quiz description..."></textarea>
          </div>
          <button type="submit" class="btn-submit">Create Quiz &amp; Add Questions</button>
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
            <div class="question-block">
              <h3>Question <?php echo $i; ?></h3>
              <div class="form-group">
                <label>Question Text</label>
                <textarea name="qns<?php echo $i; ?>" placeholder="Enter question..." required></textarea>
              </div>
              <div class="form-row">
                <div class="form-group">
                  <label>Option A</label>
                  <input name="<?php echo $i; ?>1" type="text" placeholder="Option A" required>
                </div>
                <div class="form-group">
                  <label>Option B</label>
                  <input name="<?php echo $i; ?>2" type="text" placeholder="Option B" required>
                </div>
              </div>
              <div class="form-row">
                <div class="form-group">
                  <label>Option C</label>
                  <input name="<?php echo $i; ?>3" type="text" placeholder="Option C" required>
                </div>
                <div class="form-group">
                  <label>Option D</label>
                  <input name="<?php echo $i; ?>4" type="text" placeholder="Option D" required>
                </div>
              </div>
              <div class="form-group">
                <label>Correct Answer</label>
                <select name="ans<?php echo $i; ?>" style="appearance:auto" required>
                  <option value="a">Option A</option>
                  <option value="b">Option B</option>
                  <option value="c">Option C</option>
                  <option value="d">Option D</option>
                </select>
              </div>
            </div>
          <?php } ?>
          <button type="submit" class="btn-submit">Save All Questions</button>
        </form>
      </div>
    <?php } ?>

    <!-- REMOVE QUIZ (Admin) -->
    <?php if (@$_GET['q'] == 7) { ?>
      <div class="page-header">
        <h1>Remove Quiz</h1>
        <p>Delete any quiz from the system</p>
      </div>
      <div class="card">
        <table class="data-table">
          <thead>
            <tr>
              <th>#</th>
              <th>Topic</th>
              <th>Questions</th>
              <th>Marks</th>
              <th>Time</th>
              <th>Created By</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <?php
            $result = mysqli_query($con, "SELECT * FROM quiz ORDER BY date DESC") or die('Error');
            $c = 1;
            while ($row = mysqli_fetch_array($result)) {
              echo '<tr><td>' . $c++ . '</td><td>' . $row['title'] . '</td><td>' . $row['total'] . '</td><td>' . ($row['sahi'] * $row['total']) . '</td><td>' . $row['time'] . ' min</td><td style="color:rgba(255,255,255,0.4)">' . $row['email'] . '</td>';
              echo '<td><a href="update.php?q=rmquiz&eid=' . $row['eid'] . '&from=admin" class="btn-action btn-danger"><span class="material-icons" style="font-size:16px">delete</span> Remove</a></td></tr>';
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
          <div class="form-group">
            <label>College / Department</label>
            <input name="college" type="text" placeholder="e.g. Computer Science" required>
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
</body>

</html>