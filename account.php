<?php
include_once 'config/dbConnection.php';
session_start();
if (!(isset($_SESSION['email']))) {
  header("location:index.php");
  exit;
}
$name = $_SESSION['name'];
$email = $_SESSION['email'];

// Fetch user details for targeting
$q_user = mysqli_query($con, "SELECT * FROM user WHERE email='$email'");
$user_college = '';
$user_year = '';
while ($row_u = mysqli_fetch_array($q_user)) {
  $user_college = $row_u['college'];
  $user_year = $row_u['year'];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ECUSTA — Student Dashboard</title>
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

    /* Sidebar */
    .sidebar {
      width: 250px;
      background: var(--sidebar-bg);
      border-right: 1px solid var(--border-primary);
      display: flex;
      flex-direction: column;
      position: fixed;
      top: 0;
      left: 0;
      height: 100vh;
      z-index: 100;
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
      letter-spacing: 0.5px;
    }

    .sidebar-nav {
      flex: 1;
      padding: 16px 12px;
      display: flex;
      flex-direction: column;
      gap: 4px;
    }

    .nav-item {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 12px 16px;
      border-radius: 10px;
      color: var(--sidebar-text);
      text-decoration: none;
      font-size: 14px;
      font-weight: 500;
      transition: all 0.2s ease;
    }

    .nav-item:hover {
      background: var(--bg-card-hover);
      color: var(--sidebar-text-hover);
    }

    .nav-item.active {
      background: var(--sidebar-active-bg);
      color: var(--sidebar-active-text);
    }

    .nav-item .material-icons {
      font-size: 20px;
    }

    .sidebar-footer {
      padding: 16px 12px;
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
      background: var(--accent-gradient);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 14px;
      font-weight: 700;
      color: var(--bg-primary);
      flex-shrink: 0;
    }

    .sidebar-footer .user-info div {
      overflow: hidden;
    }

    .sidebar-footer .user-name {
      font-size: 13px;
      font-weight: 600;
      color: var(--text-primary);
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      max-width: 130px;
    }

    .sidebar-footer .user-email {
      font-size: 11px;
      color: var(--text-dimmed);
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      max-width: 130px;
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
      margin-top: 8px;
    }

    .btn-signout:hover {
      background: var(--danger-bg);
      color: var(--danger);
    }

    /* Main content */
    .main {
      margin-left: 250px;
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

    /* Cards / panels */
    .card {
      background: var(--bg-card);
      backdrop-filter: blur(10px);
      border: 1px solid var(--border-primary);
      border-radius: 16px;
      padding: 24px;
      margin-bottom: 24px;
    }

    /* Tables */
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

    .data-table .badge-done {
      display: inline-flex;
      align-items: center;
      gap: 4px;
      padding: 3px 10px;
      border-radius: 20px;
      font-size: 11px;
      font-weight: 600;
      background: var(--success-bg);
      color: var(--success);
    }

    /* Buttons */
    .btn-start {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 8px 18px;
      border-radius: 8px;
      font-size: 13px;
      font-weight: 600;
      text-decoration: none;
      background: var(--accent-gradient);
      color: var(--bg-primary);
      transition: all 0.2s ease;
      border: none;
      cursor: pointer;
    }

    .btn-start:hover {
      transform: translateY(-1px);
      box-shadow: 0 4px 15px var(--shadow-color);
    }

    /* Quiz description panel */
    .quiz-desc {
      padding: 24px;
    }

    .quiz-desc h2 {
      font-size: 20px;
      font-weight: 700;
      color: var(--text-primary);
      text-align: center;
      margin-bottom: 16px;
    }

    .quiz-desc .meta {
      color: var(--text-muted);
      font-size: 13px;
      margin-bottom: 12px;
    }

    .quiz-desc .intro {
      color: var(--text-secondary);
      line-height: 1.7;
    }

    /* Quiz taking */
    .quiz-question {
      font-size: 16px;
      font-weight: 600;
      color: var(--text-primary);
      margin-bottom: 20px;
      line-height: 1.6;
    }

    .quiz-option {
      display: block;
      padding: 14px 18px;
      border: 1px solid var(--border-input);
      border-radius: 10px;
      margin-bottom: 10px;
      cursor: pointer;
      transition: all 0.2s ease;
      color: var(--text-secondary);
      font-size: 14px;
    }

    .quiz-option:hover {
      background: var(--sidebar-active-bg);
      border-color: var(--accent);
    }

    .quiz-option input[type="radio"] {
      margin-right: 10px;
      accent-color: var(--accent);
    }

    /* Result */
    .result-card {
      text-align: center;
      padding: 32px;
    }

    .result-card h2 {
      font-size: 22px;
      color: var(--text-primary);
      margin-bottom: 24px;
    }

    .result-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
      gap: 16px;
      margin-top: 20px;
    }

    .result-stat {
      padding: 18px;
      border-radius: 12px;
      background: var(--bg-card);
      border: 1px solid var(--border-primary);
    }

    .result-stat .label {
      font-size: 11px;
      text-transform: uppercase;
      letter-spacing: 1px;
      color: var(--text-dimmed);
      margin-bottom: 6px;
    }

    .result-stat .value {
      font-size: 24px;
      font-weight: 700;
    }

    .result-stat .value.correct {
      color: var(--success);
    }

    .result-stat .value.wrong {
      color: var(--danger);
    }

    .result-stat .value.score {
      color: var(--accent);
    }

    .result-stat .value.total {
      color: var(--purple);
    }

    /* Alert */
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

    /* Responsive */
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
    }
  </style>
</head>

<body>
  <button class="menu-toggle" onclick="document.querySelector('.sidebar').classList.toggle('open')">
    <span class="material-icons">menu</span>
  </button>

  <!-- Sidebar -->
  <nav class="sidebar">
    <div class="sidebar-brand">
      <img src="assets/img/ecusta_logo.png" alt="Logo">
      <span>ECUSTA</span>
    </div>
    <div class="sidebar-nav">
      <a href="account.php?q=1" class="nav-item <?php if (@$_GET['q'] == 1)
        echo 'active'; ?>">
        <span class="material-icons">home</span> Home
      </a>
      <a href="account.php?q=2" class="nav-item <?php if (@$_GET['q'] == 2)
        echo 'active'; ?>">
        <span class="material-icons">history</span> History
      </a>

    </div>
    <div class="sidebar-footer">
      <div style="display:flex;align-items:center;justify-content:space-between;padding:0 10px 8px">
        <button class="theme-toggle" title="Toggle dark/light mode">
          <span class="material-icons">light_mode</span>
        </button>
      </div>
      <div class="user-info">
        <div class="avatar"><?php echo strtoupper(substr($name, 0, 1)); ?></div>
        <div>
          <div class="user-name"><?php echo htmlspecialchars($name); ?></div>
          <div class="user-email"><?php echo htmlspecialchars($email); ?></div>
        </div>
      </div>
      <a href="logout.php?q=index.php" class="btn-signout">
        <span class="material-icons" style="font-size:18px">logout</span> Sign Out
      </a>
    </div>
  </nav>

  <!-- Main Content -->
  <main class="main">

    <!-- HOME: Available Quizzes -->
    <?php if (@$_GET['q'] == 1) { ?>
      <div class="page-header">
        <h1>Available Exams</h1>
        <p>Select an exam to begin. Completed exams are shown in green.</p>
      </div>
      <div class="card">
        <table class="data-table">
          <thead>
            <tr>
              <th>#</th>
              <th>Exam Name</th>
              <th>Questions</th>
              <th>Marks</th>
              <th>+</th>
              <th>−</th>
              <th>Time</th>
              <th></th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <?php
            $result = mysqli_query($con, "SELECT * FROM quiz WHERE status='active' ORDER BY date DESC") or die('Error');
            $c = 1;
            while ($row = mysqli_fetch_array($result)) {
              $target_dept = $row['target_dept'];
              $target_year = $row['target_year'];

              // Check if user matches target
              $dept_match = empty($target_dept) || (stripos($user_college, $target_dept) !== false);
              $year_match = empty($target_year) || ($user_year == $target_year);

              if (!$dept_match || !$year_match) {
                continue;
              }
              $title = $row['title'];
              $total = $row['total'];
              $sahi = $row['sahi'];
              $wrong = $row['wrong'];
              $time = $row['time'];
              $eid = $row['eid'];
              $q12 = mysqli_query($con, "SELECT score FROM history WHERE eid='$eid' AND email='$email'") or die('Error');
              $rowcount = mysqli_num_rows($q12);
              if ($rowcount == 0) {
                echo '<tr><td>' . $c++ . '</td><td>' . $title . '</td><td>' . $total . '</td><td>' . ($sahi * $total) . '</td><td>' . $sahi . '</td><td>' . $wrong . '</td><td>' . $time . ' min</td>';
                echo '<td><a href="account.php?q=1&fid=' . $eid . '" title="View description" style="color:#4facfe"><span class="material-icons" style="font-size:18px">description</span></a></td>';
                echo '<td><a href="account.php?q=access&eid=' . $eid . '" class="btn-start"><span class="material-icons" style="font-size:16px">play_arrow</span> Start</a></td></tr>';
              } else {
                echo '<tr><td>' . $c++ . '</td><td>' . $title . ' <span class="badge-done"><span class="material-icons" style="font-size:12px">check</span> Done</span></td><td>' . $total . '</td><td>' . ($sahi * $total) . '</td><td>' . $sahi . '</td><td>' . $wrong . '</td><td>' . $time . ' min</td><td></td><td></td></tr>';
              }
            }
            ?>
          </tbody>
        </table>
      </div>
    <?php } ?>

    <!-- Exam Description -->
    <?php if (@$_GET['fid']) {
      $eid = @$_GET['fid'];
      $result = mysqli_query($con, "SELECT * FROM quiz WHERE eid='$eid'") or die('Error');
      while ($row = mysqli_fetch_array($result)) {
        $title = $row['title'];
        $date = date("d-m-Y", strtotime($row['date']));
        $intro = $row['intro'];
        echo '<div class="card quiz-desc"><h2>' . $title . '</h2><div class="meta">Date: ' . $date . '</div><div class="intro">' . $intro . '</div></div>';
      }
    } ?>

    <!-- MOODLE-STYLE EXAM INSTRUCTIONS PAGE -->
    <?php if (@$_GET['q'] == 'access' && @$_GET['eid']) {
      $eid = @$_GET['eid'];
      $q = mysqli_query($con, "SELECT * FROM quiz WHERE eid='$eid'");
      $row = mysqli_fetch_array($q);
      $title = $row['title'];
      $total = $row['total'];
      $sahi = $row['sahi'];
      $wrong = $row['wrong'];
      $time = $row['time'];
      $intro = $row['intro'];
      $date = date("d-m-Y", strtotime($row['date']));
      $access_code = $row['access_code'];
      ?>
      <style>
        .exam-intro {
          max-width: 700px;
          margin: 0 auto;
        }

        .exam-intro-header {
          text-align: center;
          padding: 32px 0 24px;
        }

        .exam-intro-header h1 {
          font-size: 28px;
          color: var(--text-primary);
          margin-bottom: 8px;
        }

        .exam-intro-header .exam-badge {
          display: inline-block;
          padding: 4px 16px;
          background: var(--sidebar-active-bg);
          color: var(--accent);
          border-radius: 20px;
          font-size: 12px;
          font-weight: 600;
          letter-spacing: 1px;
          text-transform: uppercase;
        }

        .exam-details-grid {
          display: grid;
          grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
          gap: 12px;
          margin-bottom: 24px;
        }

        .exam-detail-card {
          padding: 16px;
          border-radius: 12px;
          background: var(--bg-card);
          border: 1px solid var(--border-primary);
          text-align: center;
        }

        .exam-detail-card .detail-icon {
          font-size: 28px;
          margin-bottom: 6px;
        }

        .exam-detail-card .detail-label {
          font-size: 11px;
          text-transform: uppercase;
          letter-spacing: 1px;
          color: var(--text-dimmed);
          margin-bottom: 4px;
        }

        .exam-detail-card .detail-value {
          font-size: 18px;
          font-weight: 700;
          color: var(--text-primary);
        }

        .exam-rules {
          padding: 24px;
          margin-bottom: 24px;
        }

        .exam-rules h3 {
          font-size: 16px;
          color: var(--text-primary);
          margin-bottom: 16px;
          display: flex;
          align-items: center;
          gap: 8px;
        }

        .exam-rules ul {
          list-style: none;
          padding: 0;
        }

        .exam-rules li {
          padding: 10px 0;
          border-bottom: 1px solid var(--table-border);
          color: var(--text-secondary);
          font-size: 14px;
          display: flex;
          align-items: flex-start;
          gap: 10px;
          line-height: 1.5;
        }

        .exam-rules li:last-child {
          border-bottom: none;
        }

        .exam-rules li .material-icons {
          font-size: 18px;
          color: var(--warning);
          margin-top: 2px;
          flex-shrink: 0;
        }

        .exam-confirm {
          display: flex;
          align-items: center;
          gap: 10px;
          padding: 16px;
          background: var(--bg-card);
          border: 1px solid var(--border-primary);
          border-radius: 10px;
          margin-bottom: 20px;
          cursor: pointer;
        }

        .exam-confirm input[type="checkbox"] {
          width: 18px;
          height: 18px;
          accent-color: var(--accent);
        }

        .exam-confirm label {
          color: var(--text-secondary);
          font-size: 14px;
          cursor: pointer;
        }

        .exam-start-btn {
          width: 100%;
          padding: 16px;
          border-radius: 12px;
          border: none;
          font-family: 'Inter', sans-serif;
          font-size: 16px;
          font-weight: 700;
          cursor: pointer;
          background: var(--accent-gradient);
          color: var(--bg-primary);
          transition: all 0.3s ease;
          letter-spacing: 0.5px;
        }

        .exam-start-btn:hover:not(:disabled) {
          transform: translateY(-2px);
          box-shadow: 0 8px 25px var(--shadow-color);
        }

        .exam-start-btn:disabled {
          opacity: 0.4;
          cursor: not-allowed;
          transform: none;
        }

        .access-code-input {
          width: 100%;
          padding: 14px;
          border-radius: 10px;
          border: 1px solid var(--border-input);
          background: var(--bg-input);
          color: var(--text-primary);
          text-align: center;
          font-size: 20px;
          letter-spacing: 4px;
          font-weight: 600;
          font-family: 'Inter', sans-serif;
          outline: none;
          transition: all 0.3s;
          margin-bottom: 20px;
        }

        .access-code-input:focus {
          border-color: var(--border-input-focus);
          background: var(--bg-input-focus);
        }

        .access-code-input::placeholder {
          color: var(--text-input-placeholder);
          letter-spacing: 1px;
          font-size: 14px;
          font-weight: 400;
        }
      </style>
      <div class="exam-intro">
        <div class="exam-intro-header">
          <span class="exam-badge">Examination</span>
          <h1><?php echo $title; ?></h1>
          <p style="color:var(--text-muted);font-size:13px">Created on <?php echo $date; ?></p>
        </div>

        <?php if (!empty($intro)) { ?>
          <div class="card" style="margin-bottom:20px">
            <h3 style="color:var(--text-primary);font-size:15px;margin-bottom:10px;display:flex;align-items:center;gap:8px">
              <span class="material-icons" style="font-size:20px;color:var(--accent)">info</span> Description
            </h3>
            <p style="color:var(--text-secondary);line-height:1.7;font-size:14px"><?php echo $intro; ?></p>
          </div>
        <?php } ?>

        <div class="exam-details-grid">
          <div class="exam-detail-card">
            <div class="detail-icon" style="color:var(--accent)"><span class="material-icons"
                style="font-size:28px">assignment</span></div>
            <div class="detail-label">Questions</div>
            <div class="detail-value"><?php echo $total; ?></div>
          </div>
          <div class="exam-detail-card">
            <div class="detail-icon" style="color:var(--success)"><span class="material-icons"
                style="font-size:28px">timer</span></div>
            <div class="detail-label">Duration</div>
            <div class="detail-value"><?php echo $time; ?> min</div>
          </div>
          <div class="exam-detail-card">
            <div class="detail-icon" style="color:var(--success)"><span class="material-icons"
                style="font-size:28px">add_circle</span></div>
            <div class="detail-label">Per Correct</div>
            <div class="detail-value">+<?php echo $sahi; ?></div>
          </div>
          <div class="exam-detail-card">
            <div class="detail-icon" style="color:var(--danger)"><span class="material-icons"
                style="font-size:28px">remove_circle</span></div>
            <div class="detail-label">Per Wrong</div>
            <div class="detail-value">-<?php echo $wrong; ?></div>
          </div>
        </div>

        <div class="card exam-rules">
          <h3><span class="material-icons">gavel</span> Exam Rules & Instructions</h3>
          <ul>
            <li><span class="material-icons">fullscreen</span> The exam will enter <strong>fullscreen mode</strong>. Do
              not exit fullscreen during the exam.</li>
            <li><span class="material-icons">tab</span> <strong>Do not switch tabs</strong> or windows. Tab switching will
              be detected and counted as a violation.</li>
            <li><span class="material-icons">warning</span> After <strong>3 violations</strong>, your exam will be
              <strong>automatically submitted</strong>.
            </li>
            <li><span class="material-icons">timer</span> The exam has a strict time limit of <strong><?php echo $time; ?>
                minutes</strong>. When time runs out, your answers will be submitted automatically.</li>
            <li><span class="material-icons">flag</span> You can <strong>flag questions</strong> for review and navigate
              between questions freely.</li>
            <li><span class="material-icons">block</span> Right-click and keyboard shortcuts are <strong>disabled</strong>
              during the exam.</li>
          </ul>
        </div>

        <form
          action="<?php echo empty($access_code) ? 'account.php?q=exam&eid=' . $eid : 'update.php?q=checkcode&eid=' . $eid; ?>"
          method="POST" id="examStartForm">
          <?php if (!empty($access_code)) { ?>
            <input name="access_code" type="text" class="access-code-input" placeholder="Enter Access Code" required
              autocomplete="off">
          <?php } ?>
          <div class="exam-confirm" onclick="document.getElementById('confirmCheck').click()">
            <input type="checkbox" id="confirmCheck"
              onchange="document.getElementById('startExamBtn').disabled = !this.checked">
            <label for="confirmCheck">I have read and agree to the exam rules. I understand that violations may result in
              automatic submission.</label>
          </div>
          <button type="submit" class="exam-start-btn" id="startExamBtn" disabled>
            <span class="material-icons" style="vertical-align:middle;margin-right:6px">play_circle</span>
            Begin Examination
          </button>
        </form>
        <a href="account.php?q=1"
          style="display:block;text-align:center;margin-top:16px;color:var(--text-muted);font-size:13px;text-decoration:none">
          <span class="material-icons" style="font-size:14px;vertical-align:middle">arrow_back</span> Back to Exams
        </a>
      </div>
    <?php } ?>

    <!-- FULL EXAM UI -->
    <?php if (@$_GET['q'] == 'exam' && @$_GET['eid']) {
      $eid = @$_GET['eid'];
      $q = mysqli_query($con, "SELECT * FROM quiz WHERE eid='$eid'");
      $quiz = mysqli_fetch_assoc($q);
      $duration = $quiz['time'] * 60; // in seconds
    
      // Fetch all questions and options
      $questions = [];
      $q_sql = mysqli_query($con, "SELECT * FROM questions WHERE eid='$eid' ORDER BY RAND()");
      while ($q_row = mysqli_fetch_assoc($q_sql)) {
        $qid = $q_row['qid'];
        $opts_sql = mysqli_query($con, "SELECT * FROM options WHERE qid='$qid'");
        $options = [];
        while ($opt_row = mysqli_fetch_assoc($opts_sql)) {
          $options[] = $opt_row;
        }
        $q_row['options'] = $options;
        // Ensure question_type is available
        if (!isset($q_row['question_type']))
          $q_row['question_type'] = 'mcq';
        $questions[] = $q_row;
      }
      ?>
      <style>
        .sidebar {
          display: none !important;
        }

        .menu-toggle {
          display: none !important;
        }

        .main {
          margin-left: 0 !important;
          width: 100%;
          max-width: 1200px;
          margin: 0 auto;
          padding-top: 20px;
        }

        .exam-container {
          display: flex;
          gap: 24px;
          height: calc(100vh - 40px);
        }

        .question-panel {
          flex: 1;
          display: flex;
          flex-direction: column;
        }

        .status-panel {
          width: 300px;
          background: var(--bg-card);
          border: 1px solid var(--border-primary);
          border-radius: 16px;
          padding: 20px;
          display: flex;
          flex-direction: column;
        }

        .timer-box {
          font-size: 32px;
          font-weight: 700;
          text-align: center;
          margin-bottom: 16px;
          color: var(--accent);
          font-feature-settings: "tnum";
          font-variant-numeric: tabular-nums;
          padding: 12px;
          border-radius: 12px;
          background: var(--sidebar-active-bg);
        }

        .timer-box.warning {
          color: var(--danger);
          background: var(--danger-bg);
          animation: pulse 1s infinite;
        }

        @keyframes pulse {

          0%,
          100% {
            opacity: 1;
          }

          50% {
            opacity: 0.7;
          }
        }

        .violation-counter {
          display: flex;
          align-items: center;
          justify-content: center;
          gap: 6px;
          padding: 8px 12px;
          border-radius: 8px;
          margin-bottom: 16px;
          background: var(--danger-bg);
          color: var(--danger);
          font-size: 12px;
          font-weight: 600;
          text-transform: uppercase;
          letter-spacing: 0.5px;
        }

        .violation-counter.safe {
          background: var(--success-bg);
          color: var(--success);
        }

        .question-legend {
          display: flex;
          flex-wrap: wrap;
          gap: 8px;
          margin-bottom: 16px;
          padding: 12px;
          border-radius: 10px;
          background: var(--bg-card);
          border: 1px solid var(--border-primary);
        }

        .legend-item {
          display: flex;
          align-items: center;
          gap: 4px;
          font-size: 11px;
          color: var(--text-muted);
        }

        .legend-dot {
          width: 12px;
          height: 12px;
          border-radius: 4px;
        }

        .legend-dot.not-visited {
          background: var(--bg-input);
          border: 1px solid var(--border-input);
        }

        .legend-dot.current {
          border: 2px solid var(--accent);
          background: transparent;
        }

        .legend-dot.answered {
          background: var(--success-bg);
          border: 1px solid var(--success);
        }

        .legend-dot.flagged {
          background: rgba(255, 215, 0, 0.2);
          border: 1px solid var(--warning);
        }

        .grid-questions {
          display: grid;
          grid-template-columns: repeat(5, 1fr);
          gap: 8px;
          overflow-y: auto;
          padding-right: 4px;
          flex: 1;
          align-content: start;
        }

        .q-btn {
          aspect-ratio: 1;
          border-radius: 8px;
          border: 1px solid var(--border-input);
          background: var(--bg-input);
          color: var(--text-muted);
          display: flex;
          align-items: center;
          justify-content: center;
          cursor: pointer;
          font-weight: 600;
          transition: all 0.2s;
          font-size: 13px;
        }

        .q-btn:hover {
          background: var(--bg-card-hover);
        }

        .q-btn.active {
          border-color: var(--accent);
          color: var(--text-primary);
          box-shadow: 0 0 0 2px var(--sidebar-active-bg);
        }

        .q-btn.answered {
          background: var(--success-bg);
          color: var(--success);
          border-color: transparent;
        }

        .q-btn.flagged {
          background: rgba(255, 215, 0, 0.2);
          color: var(--warning);
          border-color: transparent;
        }

        .q-card {
          background: var(--bg-card);
          border: 1px solid var(--border-primary);
          border-radius: 16px;
          padding: 40px;
          flex: 1;
          display: flex;
          flex-direction: column;
          overflow-y: auto;
          position: relative;
        }

        .q-text {
          font-size: 20px;
          font-weight: 600;
          line-height: 1.6;
          margin-bottom: 30px;
          color: var(--text-primary);
        }

        .opt-label {
          display: flex;
          align-items: center;
          padding: 16px 20px;
          background: var(--bg-input);
          border: 1px solid var(--border-primary);
          border-radius: 10px;
          margin-bottom: 12px;
          cursor: pointer;
          transition: all 0.2s;
          color: var(--text-secondary);
        }

        .opt-label:hover {
          background: var(--bg-card-hover);
        }

        .opt-label input {
          margin-right: 16px;
          transform: scale(1.2);
          accent-color: var(--accent);
        }

        .opt-label.selected {
          background: var(--sidebar-active-bg);
          border-color: var(--accent);
        }

        .nav-btns {
          display: flex;
          justify-content: space-between;
          margin-top: 24px;
        }

        .btn-nav {
          padding: 12px 24px;
          border-radius: 10px;
          border: none;
          font-weight: 600;
          cursor: pointer;
          display: flex;
          align-items: center;
          gap: 8px;
          font-size: 15px;
          transition: all 0.2s;
        }

        .btn-prev {
          background: var(--bg-input);
          color: var(--text-primary);
        }

        .btn-prev:hover {
          background: var(--bg-card-hover);
        }

        .btn-next {
          background: var(--accent);
          color: var(--bg-primary);
        }

        .btn-next:hover {
          opacity: 0.9;
        }

        .btn-flag {
          position: absolute;
          top: 20px;
          right: 20px;
          background: transparent;
          border: none;
          color: var(--text-dimmed);
          cursor: pointer;
          font-size: 0;
        }

        .btn-flag .material-icons {
          font-size: 24px;
        }

        .btn-flag.active {
          color: var(--warning);
        }

        .blur-overlay {
          position: fixed;
          top: 0;
          left: 0;
          width: 100%;
          height: 100%;
          background: rgba(14, 26, 43, 0.97);
          backdrop-filter: blur(20px);
          z-index: 9999;
          display: none;
          flex-direction: column;
          align-items: center;
          justify-content: center;
        }

        .blur-overlay h2 {
          color: #fff;
        }

        .blur-overlay p {
          color: rgba(255, 255, 255, 0.7);
        }

        .exam-progress {
          width: 100%;
          height: 4px;
          background: var(--bg-input);
          border-radius: 2px;
          margin-bottom: 16px;
        }

        .exam-progress-fill {
          height: 100%;
          background: var(--accent-gradient);
          border-radius: 2px;
          transition: width 0.3s ease;
        }

        @media (max-width: 768px) {
          .exam-container {
            flex-direction: column-reverse;
            height: auto;
          }

          .status-panel {
            width: 100%;
          }

          .q-card {
            padding: 20px;
          }
        }

        /* CODE SNIPPET QUESTION STYLES */
        .q-text pre {
          background: #0d1117;
          color: #e6edf3;
          padding: 20px 24px;
          border-radius: 10px;
          overflow-x: auto;
          font-family: 'JetBrains Mono', 'Fira Code', 'Consolas', 'Monaco', monospace;
          font-size: 14px;
          line-height: 1.7;
          margin: 16px 0;
          border: 1px solid #30363d;
          tab-size: 4;
          white-space: pre;
          position: relative;
        }

        .q-text code {
          background: rgba(255, 255, 255, 0.08);
          padding: 2px 6px;
          border-radius: 4px;
          font-family: 'JetBrains Mono', 'Fira Code', monospace;
          font-size: 0.9em;
          color: var(--accent);
        }

        .q-text pre code {
          background: none;
          padding: 0;
          border-radius: 0;
          font-size: inherit;
          color: inherit;
        }

        /* Syntax highlighting colors */
        .syn-keyword {
          color: #ff7b72;
          font-weight: 600;
        }

        .syn-type {
          color: #79c0ff;
        }

        .syn-string {
          color: #a5d6ff;
        }

        .syn-comment {
          color: #8b949e;
          font-style: italic;
        }

        .syn-number {
          color: #79c0ff;
        }

        .syn-method {
          color: #d2a8ff;
        }

        .syn-paren {
          color: #e6edf3;
        }

        .syn-operator {
          color: #ff7b72;
        }

        /* Code-style MCQ option cards for code questions */
        .code-option-card {
          display: flex;
          align-items: flex-start;
          gap: 0;
          padding: 0;
          background: #0d1117;
          border: 1px solid #30363d;
          border-radius: 10px;
          margin-bottom: 12px;
          cursor: pointer;
          transition: all 0.2s ease;
          position: relative;
          overflow: hidden;
        }

        .code-option-card:hover {
          border-color: var(--accent);
          box-shadow: 0 0 0 1px rgba(79, 172, 254, 0.2);
        }

        .code-option-card.selected {
          border-color: var(--accent);
          box-shadow: 0 0 0 2px rgba(79, 172, 254, 0.3);
        }

        .code-option-indicator {
          display: flex;
          align-items: center;
          justify-content: center;
          width: 44px;
          min-height: 100%;
          background: rgba(255, 255, 255, 0.04);
          border-right: 1px solid #30363d;
          color: #8b949e;
          font-size: 14px;
          font-weight: 600;
          flex-shrink: 0;
          transition: all 0.2s;
          align-self: stretch;
        }

        .code-option-card:hover .code-option-indicator {
          color: var(--accent);
          background: rgba(79, 172, 254, 0.08);
        }

        .code-option-card.selected .code-option-indicator {
          background: var(--accent);
          color: #fff;
          border-right-color: var(--accent);
        }

        .code-option-content {
          color: #e6edf3;
          font-family: 'JetBrains Mono', 'Fira Code', 'Consolas', monospace;
          font-size: 14px;
          line-height: 1.6;
          padding: 14px 18px;
          flex: 1;
          white-space: pre;
          overflow-x: auto;
        }

        /* NEW MCQ OPTION CARD STYLES */
        .mcq-option-card {
          display: flex;
          align-items: center;
          gap: 16px;
          padding: 16px 20px;
          background: transparent;
          border: 1px solid var(--border-primary);
          border-radius: 12px;
          margin-bottom: 12px;
          cursor: pointer;
          transition: all 0.2s ease;
          position: relative;
        }

        .mcq-option-card:hover {
          border-color: var(--accent);
          background: rgba(255, 255, 255, 0.02);
        }

        .mcq-option-card.selected {
          border-color: var(--accent);
          background: rgba(79, 172, 254, 0.1);
          box-shadow: 0 0 0 1px var(--accent);
        }

        .option-indicator {
          display: flex;
          align-items: center;
          justify-content: center;
          width: 32px;
          height: 32px;
          border-radius: 50%;
          border: 1px solid var(--text-muted);
          color: var(--text-muted);
          font-size: 14px;
          font-weight: 600;
          flex-shrink: 0;
          transition: all 0.2s;
        }

        .mcq-option-card:hover .option-indicator {
          border-color: var(--accent);
          color: var(--accent);
        }

        .mcq-option-card.selected .option-indicator {
          background: var(--accent);
          border-color: var(--accent);
          color: #fff;
        }

        .option-content {
          color: var(--text-secondary);
          font-size: 15px;
          line-height: 1.5;
          flex: 1;
        }

        .mcq-option-card.selected .option-content {
          color: var(--text-primary);
        }
      </style>

      <div id="lockdown-warning" class="blur-overlay">
        <span class="material-icons" style="font-size:64px;color:var(--danger);margin-bottom:20px">gpp_bad</span>
        <h2 style="margin-bottom:10px">⚠ Exam Violation Detected</h2>
        <p style="margin-bottom:8px">You switched away from the exam tab.</p>
        <p id="violationMsg" style="color:var(--danger);font-weight:700;font-size:18px;margin-bottom:20px">Violation 1 of
          3</p>
        <p style="font-size:13px;color:rgba(255,255,255,0.5);margin-bottom:20px">After 3 violations, your exam will be
          automatically submitted.</p>
        <button onclick="resumeExam()" class="btn-start" style="margin-top:10px;padding:14px 40px;font-size:16px">
          <span class="material-icons" style="vertical-align:middle;margin-right:6px">play_arrow</span> Resume Exam
        </button>
      </div>

      <form id="examForm" action="update.php?q=submitexam&eid=<?php echo $eid; ?>" method="POST" class="exam-container">
        <div class="status-panel">
          <div class="timer-box" id="timer">00:00</div>
          <div class="exam-progress">
            <div class="exam-progress-fill" id="progressBar" style="width:0%"></div>
          </div>
          <div class="violation-counter safe" id="violationDisplay">
            <span class="material-icons" style="font-size:14px">verified_user</span> No Violations
          </div>
          <div class="question-legend">
            <div class="legend-item">
              <div class="legend-dot not-visited"></div> Not visited
            </div>
            <div class="legend-item">
              <div class="legend-dot current"></div> Current
            </div>
            <div class="legend-item">
              <div class="legend-dot answered"></div> Answered
            </div>
            <div class="legend-item">
              <div class="legend-dot flagged"></div> Flagged
            </div>
          </div>
          <div
            style="font-size:11px;text-transform:uppercase;letter-spacing:1px;color:var(--text-dimmed);margin-bottom:10px">
            Questions</div>
          <div class="grid-questions" id="qGrid"></div>
          <button type="button" onclick="confirmSubmit()" class="btn-start"
            style="width:100%;margin-top:20px;background:linear-gradient(135deg, var(--success), #11998e);color:white">
            <span class="material-icons" style="vertical-align:middle;margin-right:4px;font-size:18px">send</span>
            Submit Exam
          </button>
        </div>

        <div class="question-panel">
          <div id="qContainer" class="q-card"></div>
          <div class="nav-btns">
            <button type="button" id="btnPrev" onclick="prevQ()" class="btn-nav btn-prev">
              <span class="material-icons">west</span> Previous
            </button>
            <button type="button" id="btnNext" onclick="nextQ()" class="btn-nav btn-next">
              Next <span class="material-icons">east</span>
            </button>
          </div>
        </div>
      </form>

      <script>
        const questions = <?php echo json_encode($questions); ?>;
        const duration = <?php echo $duration; ?>;
        let timeLeft = duration;
        let currentIdx = 0;
        let answers = {};
        let flags = {};
        let violationCount = 0;
        const MAX_VIOLATIONS = 3;

        // Time Sync Logic
        let currentQuizDuration = duration;
        setInterval(() => {
          fetch('update.php?q=check_time&eid=<?php echo $eid; ?>')
            .then(res => res.text())
            .then(newTime => {
              if (newTime) {
                const newDuration = parseInt(newTime) * 60;
                if (newDuration > currentQuizDuration) {
                  const diff = newDuration - currentQuizDuration;
                  timeLeft += diff;
                  currentQuizDuration = newDuration;
                  showAlert("Time extended by " + (diff / 60) + " minutes!", "success");
                }
              }
            })
            .catch(err => console.error(err));
        }, 15000);

        function init() {
          renderGrid();
          showQ(0);
          startTimer();
          updateProgress();

          // Enter fullscreen
          document.documentElement.requestFullscreen().catch(e => console.log(e));

          // Anti-cheat: disable context menu
          document.addEventListener('contextmenu', event => event.preventDefault());

          // Anti-cheat: visibility change detection with violation counting
          document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
              violationCount++;
              updateViolationDisplay();
              if (violationCount >= MAX_VIOLATIONS) {
                submitExam();
                return;
              }
              document.getElementById('lockdown-warning').style.display = 'flex';
              document.getElementById('violationMsg').textContent = `Violation ${violationCount} of ${MAX_VIOLATIONS}`;
            }
          });

          // Anti-cheat: fullscreen change detection
          document.addEventListener('fullscreenchange', () => {
            if (!document.fullscreenElement) {
              // User exited fullscreen — re-request after a short delay
              setTimeout(() => {
                document.documentElement.requestFullscreen().catch(e => console.log(e));
              }, 300);
            }
          });

          // Anti-cheat: block some keyboard shortcuts
          document.addEventListener('keydown', (e) => {
            // Block F11, Ctrl+W, Ctrl+T, Ctrl+N, Ctrl+Tab
            if (e.key === 'F11' || e.key === 'Escape') {
              e.preventDefault();
              e.stopPropagation();
              return false;
            }
            if (e.ctrlKey && ['w', 't', 'n', 'Tab'].includes(e.key)) {
              e.preventDefault();
              return false;
            }
            // Block Alt+Tab, Alt+F4
            if (e.altKey && (e.key === 'Tab' || e.key === 'F4')) {
              e.preventDefault();
              return false;
            }
          });

          // Anti-cheat: prevent navigation
          window.onbeforeunload = function () {
            return "Your exam is still in progress. Leaving will NOT save your answers.";
          };

          // Block browser back button
          history.pushState(null, null, location.href);
          window.addEventListener('popstate', () => {
            history.pushState(null, null, location.href);
          });
        }

        function updateViolationDisplay() {
          const el = document.getElementById('violationDisplay');
          if (violationCount === 0) {
            el.className = 'violation-counter safe';
            el.innerHTML = '<span class="material-icons" style="font-size:14px">verified_user</span> No Violations';
          } else {
            el.className = 'violation-counter';
            el.innerHTML = `<span class="material-icons" style="font-size:14px">warning</span> ${violationCount}/${MAX_VIOLATIONS} Violations`;
          }
        }

        function updateProgress() {
          const answered = Object.keys(answers).length;
          const total = questions.length;
          const pct = total > 0 ? (answered / total * 100) : 0;
          document.getElementById('progressBar').style.width = pct + '%';
        }

        function startTimer() {
          const timerEl = document.getElementById('timer');
          const interval = setInterval(() => {
            timeLeft--;
            const m = Math.floor(timeLeft / 60).toString().padStart(2, '0');
            const s = (timeLeft % 60).toString().padStart(2, '0');
            timerEl.textContent = `${m}:${s}`;
            // Warning when less than 2 minutes
            if (timeLeft <= 120) {
              timerEl.classList.add('warning');
            }
            if (timeLeft <= 0) {
              clearInterval(interval);
              submitExam();
            }
          }, 1000);
        }

        function renderGrid() {
          const grid = document.getElementById('qGrid');
          grid.innerHTML = '';
          questions.forEach((q, idx) => {
            const btn = document.createElement('div');
            btn.className = `q-btn ${idx === currentIdx ? 'active' : ''} ${answers[q.qid] ? 'answered' : ''} ${flags[q.qid] ? 'flagged' : ''}`;
            btn.textContent = idx + 1;
            btn.onclick = () => showQ(idx);
            grid.appendChild(btn);
          });
        }

        // Simple syntax highlighter for code blocks
        function highlightSyntax(code) {
          // Escape HTML first
          let escaped = code
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;');

          // Apply syntax highlighting with spans
          // Comments (// and /* */)
          escaped = escaped.replace(/(\/\/.*$)/gm, '<span class="syn-comment">$1</span>');
          escaped = escaped.replace(/(\/\*[\s\S]*?\*\/)/g, '<span class="syn-comment">$1</span>');
          // Strings
          escaped = escaped.replace(/("(?:[^"\\]|\\.)*")/g, '<span class="syn-string">$1</span>');
          escaped = escaped.replace(/('(?:[^'\\]|\\.)*')/g, '<span class="syn-string">$1</span>');
          // Keywords (Java, Python, C, C++)
          const keywords = ['abstract', 'assert', 'boolean', 'break', 'byte', 'case', 'catch', 'char', 'class', 'const', 'continue', 'def', 'default', 'do', 'double', 'elif', 'else', 'enum', 'extends', 'final', 'finally', 'float', 'for', 'from', 'goto', 'if', 'implements', 'import', 'in', 'instanceof', 'int', 'interface', 'is', 'lambda', 'long', 'native', 'new', 'not', 'or', 'and', 'package', 'pass', 'private', 'print', 'protected', 'public', 'raise', 'return', 'short', 'static', 'strictfp', 'super', 'switch', 'synchronized', 'this', 'throw', 'throws', 'transient', 'try', 'void', 'volatile', 'while', 'with', 'yield', 'String', 'System', 'Math', 'null', 'true', 'false', 'True', 'False', 'None', 'self', 'def', 'class', 'elif', 'except', 'exec', 'lambda', 'range', 'len', 'input', 'output', 'cout', 'cin', 'endl', 'printf', 'scanf', 'include', 'define', 'using', 'namespace', 'std', 'main', 'println'];
          const keywordRegex = new RegExp('\\b(' + keywords.join('|') + ')\\b', 'g');
          escaped = escaped.replace(keywordRegex, (match) => {
            // Don't highlight inside already-highlighted spans
            return '<span class="syn-keyword">' + match + '</span>';
          });
          // Types
          const types = ['int', 'float', 'double', 'char', 'boolean', 'byte', 'short', 'long', 'void', 'String', 'Integer', 'Boolean', 'Double', 'Float', 'List', 'Map', 'Set', 'Array', 'Object'];
          const typeRegex = new RegExp('(?<![\\w])(' + types.join('|') + ')(?![\\w])', 'g');
          // Numbers
          escaped = escaped.replace(/\b(\d+\.?\d*)\b/g, '<span class="syn-number">$1</span>');
          // Method calls like .print( or .println(
          escaped = escaped.replace(/\.([a-zA-Z_]\w*)\s*\(/g, '.<span class="syn-method">$1</span>(');

          return escaped;
        }

        // Format question text - detect code blocks and apply highlighting
        function formatQuestionText(text, isCodeType) {
          if (!text) return '';

          // If text contains <pre> or ``` markers, parse them
        let formatted = text;

        // Handle ``` code blocks (markdown style)
          formatted = formatted.replace(/```(\w*)\n?([\s\S]*?)```/g, (match, lang, code) => {
            return '<pre><code>' + highlightSyntax(code.trim()) + '</code></pre>';
          });

          // Handle <pre> tags already in text
          formatted = formatted.replace(/<pre>([\s\S]*?)<\/pre>/g, (match, code) => {
            return '<pre><code>' + highlightSyntax(code.trim()) + '</code></pre>';
          });

          // For code-type questions: if no code block was detected, check for multi-line 
          // content that looks like code (contains brackets, semicolons, indentation)
          if (isCodeType && !formatted.includes('<pre>')) {
            const lines = formatted.split('\n');
            let textPart = [];
            let codePart = [];
            let inCode = false;

            for (let line of lines) {
              const looksLikeCode = /[{}();=<>]/.test(line) || /^\s{2,}/.test(line) || /^(int|for|if|while|def|class|public|private|void|return|import|from|print|System)/.test(line.trim());
              if (looksLikeCode && !inCode) {
                inCode = true;
              }
              if (inCode) {
                codePart.push(line);
              } else {
                textPart.push(line);
              }
            }

            if (codePart.length > 0) {
              formatted = textPart.join('\n');
              if (formatted.trim()) formatted += '\n';
              formatted += '<pre><code>' + highlightSyntax(codePart.join('\n')) + '</code></pre>';
            }
          }

          // Convert remaining newlines to <br> outside of <pre> blocks
          formatted = formatted.replace(/([^>])\n/g, '$1<br>');

          return formatted;
        }

        function showQ(idx) {
          currentIdx = idx;
          const q = questions[idx];
          const container = document.getElementById('qContainer');
          const selected = answers[q.qid];
          const isCodeType = q.question_type === 'code';

          let questionHtml = isCodeType ? formatQuestionText(q.qns, true) : q.qns;

          let html = `
          <button type="button" class="btn-flag ${flags[q.qid] ? 'active' : ''}" onclick="toggleFlag('${q.qid}')">
            <span class="material-icons">${flags[q.qid] ? 'flag' : 'outlined_flag'}</span>
          </button>
          <h3 style="color:var(--text-muted);font-size:14px;margin-bottom:10px">Question ${idx + 1} of ${questions.length}</h3>
          <div class="q-text">${questionHtml}</div>
        `;

          if (q.question_type === 'mcq' || !q.question_type) {
            const letters = ['A', 'B', 'C', 'D', 'E', 'F'];
            q.options.forEach((opt, index) => {
              const letter = letters[index] || '?';
              const isSelected = selected === opt.optionid;

              html += `
                <div class="mcq-option-card ${isSelected ? 'selected' : ''}" onclick="selectAns('${q.qid}', '${opt.optionid}')">
                   <div class="option-indicator">${letter}</div>
                   <div class="option-content">${opt.option}</div>
                </div>
              `;
            });
          } else if (q.question_type === 'code' && q.options && q.options.length > 0) {
            // Code type with MCQ options - render as code-styled cards
            const letters = ['A', 'B', 'C', 'D', 'E', 'F'];
            q.options.forEach((opt, index) => {
              const letter = letters[index] || '?';
              const isSelected = selected === opt.optionid;

              html += `
                <div class="code-option-card ${isSelected ? 'selected' : ''}" onclick="selectAns('${q.qid}', '${opt.optionid}')">
                   <div class="code-option-indicator">${letter}</div>
                   <div class="code-option-content">${opt.option}</div>
                </div>
              `;
            });
          } else if (q.question_type === 'short' || q.question_type === 'code') {
            html += `
                <textarea class="form-control" 
                    onchange="selectAns('${q.qid}', this.value, true)" 
                    oninput="selectAns('${q.qid}', this.value, true)"
                    style="width:100%;height:150px;background:#0d1117;color:#e6edf3;border:1px solid #30363d;border-radius:10px;padding:18px;font-family:'JetBrains Mono','Fira Code',monospace;font-size:14px;line-height:1.6"
                    placeholder="Type your answer here...">${selected || ''}</textarea>
            `;
          } else if (q.question_type === 'match') {
            if (!q.shuffledRight) {
              q.shuffledRight = q.options.map(o => o.optionid).sort(() => Math.random() - 0.5);
            }
            html += '<div style="display:flex;flex-direction:column;gap:10px">';
            q.options.forEach(opt => {
              const currentVal = selected ? selected[opt.option] : '';
              html += `
                <div style="display:flex;align-items:center;padding:10px;background:var(--bg-input);border-radius:8px">
                    <span style="flex:1;font-weight:600;color:var(--text-primary)">${opt.option}</span>
                    <span style="margin:0 15px;color:var(--text-dimmed)">=</span>
                    <select style="flex:1;padding:8px;border-radius:4px;background:var(--bg-primary);color:var(--text-primary);border:1px solid var(--border-input)"
                        onchange="selectMatch('${q.qid}', '${opt.option}', this.value)">
                        <option value="">Select...</option>
                        ${q.shuffledRight.map(r => `<option value="${r}" ${currentVal === r ? 'selected' : ''}>${r}</option>`).join('')}
                    </select>
                </div>`;
            });
            html += '</div>';
          }

          container.innerHTML = html;
          renderGrid();

          document.getElementById('btnPrev').style.visibility = idx === 0 ? 'hidden' : 'visible';
          document.getElementById('btnNext').style.visibility = idx === questions.length - 1 ? 'hidden' : 'visible';
        }

        function selectAns(qid, val, isText = false) {
          answers[qid] = val;
          renderGrid();
          updateProgress();
          if (!isText) {
            // Re-render to update selection visually
            showQ(currentIdx);
          }
        }

        function selectMatch(qid, left, right) {
          if (!answers[qid] || typeof answers[qid] !== 'object') answers[qid] = {};
          answers[qid][left] = right;
          renderGrid();
          updateProgress();
        }

        function toggleFlag(qid) {
          flags[qid] = !flags[qid];
          renderGrid();
          showQ(currentIdx);
        }

        function prevQ() { if (currentIdx > 0) showQ(currentIdx - 1); }
        function nextQ() { if (currentIdx < questions.length - 1) showQ(currentIdx + 1); }

        function resumeExam() {
          document.getElementById('lockdown-warning').style.display = 'none';
          document.documentElement.requestFullscreen().catch(e => console.log(e));
        }

        function confirmSubmit() {
          const answered = Object.keys(answers).length;
          const total = questions.length;
          const msg = answered < total
            ? `You have answered ${answered} of ${total} questions. ${total - answered} questions are unanswered.\n\nAre you sure you want to submit?`
            : 'Are you sure you want to submit the exam?';
          if (confirm(msg)) {
            submitExam();
          }
        }

        function submitExam() {
          window.onbeforeunload = null;
          if (document.fullscreenElement) {
            document.exitFullscreen().catch(e => { });
          }
          const form = document.getElementById('examForm');

          for (const [qid, val] of Object.entries(answers)) {
            if (typeof val === 'object') {
              for (const [left, right] of Object.entries(val)) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = `ans_${qid}[${left}]`;
                input.value = right;
                form.appendChild(input);
              }
            } else {
              const input = document.createElement('input');
              input.type = 'hidden';
              input.name = `ans_${qid}`;
              input.value = val;
              form.appendChild(input);
            }
          }

          form.submit();
        }

        window.addEventListener('load', init);
      </script>
    <?php } ?>

    <!-- RESULT -->
    <?php if (@$_GET['q'] == 'result' && @$_GET['eid']) {
      $eid = @$_GET['eid'];
      $q = mysqli_query($con, "SELECT * FROM history WHERE eid='$eid' AND email='$email'") or die('Error');
      echo '<div class="card result-card"><h2>Exam Results</h2><div class="result-grid">';
      while ($row = mysqli_fetch_array($q)) {
        echo '<div class="result-stat"><div class="label">Total Questions</div><div class="value total">' . $row['level'] . '</div></div>';
        echo '<div class="result-stat"><div class="label">Correct</div><div class="value correct">' . $row['sahi'] . '</div></div>';
        echo '<div class="result-stat"><div class="label">Wrong</div><div class="value wrong">' . $row['wrong'] . '</div></div>';
        $q_quiz = mysqli_query($con, "SELECT sahi, total FROM quiz WHERE eid='$eid'");
        $row_quiz = mysqli_fetch_array($q_quiz);
        $max_score = $row_quiz['sahi'] * $row_quiz['total'];
        echo '<div class="result-stat"><div class="label">Score</div><div class="value score">' . $row['score'] . ' / ' . $max_score . '</div></div>';
      }
      $q = mysqli_query($con, "SELECT * FROM rank WHERE email='$email'") or die('Error');
      while ($row = mysqli_fetch_array($q)) {
        echo '<div class="result-stat"><div class="label">Overall Score</div><div class="value score">' . $row['score'] . '</div></div>';
      }
      echo '</div></div>';
    } ?>

    <!-- HISTORY -->
    <?php if (@$_GET['q'] == 2) {
      $q = mysqli_query($con, "SELECT * FROM history WHERE email='$email' ORDER BY date DESC") or die('Error');
      echo '<div class="page-header"><h1>Exam History</h1><p>Your previously completed exams</p></div><div class="card"><table class="data-table"><thead><tr><th>#</th><th>Exam Name</th><th>Questions</th><th>Correct</th><th>Wrong</th><th>Score</th></tr></thead><tbody>';
      $c = 0;
      while ($row = mysqli_fetch_array($q)) {
        $eid = $row['eid'];
        $q23 = mysqli_query($con, "SELECT title, sahi, total FROM quiz WHERE eid='$eid'") or die('Error');
        $title = '';
        $max_score = 0;
        while ($r2 = mysqli_fetch_array($q23)) {
          $title = $r2['title'];
          $max_score = $r2['sahi'] * $r2['total'];
        }
        $c++;
        echo '<tr><td>' . $c . '</td><td>' . $title . '</td><td>' . $row['level'] . '</td><td style="color:#38ef7d">' . $row['sahi'] . '</td><td style="color:#ff416c">' . $row['wrong'] . '</td><td style="color:#4facfe;font-weight:700">' . $row['score'] . ' / ' . $max_score . '</td></tr>';
      }
      echo '</tbody></table></div>';
    } ?>



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