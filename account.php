<?php
include_once 'dbConnection.php';
session_start();
if (!(isset($_SESSION['email']))) {
  header("location:index.php");
  exit;
}
$name = $_SESSION['name'];
$email = $_SESSION['email'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ECUSTA — Student Dashboard</title>
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

    /* Sidebar */
    .sidebar {
      width: 250px;
      background: linear-gradient(180deg, #132238 0%, #0d1929 100%);
      border-right: 1px solid rgba(255, 255, 255, 0.06);
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
      color: rgba(255, 255, 255, 0.5);
      text-decoration: none;
      font-size: 14px;
      font-weight: 500;
      transition: all 0.2s ease;
    }

    .nav-item:hover {
      background: rgba(255, 255, 255, 0.06);
      color: rgba(255, 255, 255, 0.85);
    }

    .nav-item.active {
      background: rgba(79, 172, 254, 0.12);
      color: #4facfe;
    }

    .nav-item .material-icons {
      font-size: 20px;
    }

    .sidebar-footer {
      padding: 16px 12px;
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
      background: linear-gradient(135deg, #4facfe, #00f2fe);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 14px;
      font-weight: 700;
      color: #0e1a2b;
      flex-shrink: 0;
    }

    .sidebar-footer .user-info div {
      overflow: hidden;
    }

    .sidebar-footer .user-name {
      font-size: 13px;
      font-weight: 600;
      color: #fff;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      max-width: 130px;
    }

    .sidebar-footer .user-email {
      font-size: 11px;
      color: rgba(255, 255, 255, 0.4);
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
      color: rgba(255, 255, 255, 0.4);
      text-decoration: none;
      font-size: 13px;
      transition: all 0.2s ease;
      margin-top: 8px;
    }

    .btn-signout:hover {
      background: rgba(255, 65, 108, 0.12);
      color: #ff416c;
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
      color: #fff;
      margin-bottom: 4px;
    }

    .page-header p {
      font-size: 13px;
      color: rgba(255, 255, 255, 0.4);
    }

    /* Cards / panels */
    .card {
      background: rgba(255, 255, 255, 0.04);
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.06);
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
      color: rgba(255, 255, 255, 0.35);
      border-bottom: 1px solid rgba(255, 255, 255, 0.06);
    }

    .data-table td {
      padding: 14px 16px;
      font-size: 14px;
      border-bottom: 1px solid rgba(255, 255, 255, 0.04);
      color: rgba(255, 255, 255, 0.75);
    }

    .data-table tbody tr:hover {
      background: rgba(255, 255, 255, 0.03);
    }

    .data-table .badge-done {
      display: inline-flex;
      align-items: center;
      gap: 4px;
      padding: 3px 10px;
      border-radius: 20px;
      font-size: 11px;
      font-weight: 600;
      background: rgba(56, 239, 125, 0.12);
      color: #38ef7d;
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
      background: linear-gradient(135deg, #4facfe, #00f2fe);
      color: #0e1a2b;
      transition: all 0.2s ease;
      border: none;
      cursor: pointer;
    }

    .btn-start:hover {
      transform: translateY(-1px);
      box-shadow: 0 4px 15px rgba(79, 172, 254, 0.3);
    }

    /* Quiz description panel */
    .quiz-desc {
      padding: 24px;
    }

    .quiz-desc h2 {
      font-size: 20px;
      font-weight: 700;
      color: #fff;
      text-align: center;
      margin-bottom: 16px;
    }

    .quiz-desc .meta {
      color: rgba(255, 255, 255, 0.5);
      font-size: 13px;
      margin-bottom: 12px;
    }

    .quiz-desc .intro {
      color: rgba(255, 255, 255, 0.7);
      line-height: 1.7;
    }

    /* Quiz taking */
    .quiz-question {
      font-size: 16px;
      font-weight: 600;
      color: #fff;
      margin-bottom: 20px;
      line-height: 1.6;
    }

    .quiz-option {
      display: block;
      padding: 14px 18px;
      border: 1px solid rgba(255, 255, 255, 0.08);
      border-radius: 10px;
      margin-bottom: 10px;
      cursor: pointer;
      transition: all 0.2s ease;
      color: rgba(255, 255, 255, 0.7);
      font-size: 14px;
    }

    .quiz-option:hover {
      background: rgba(79, 172, 254, 0.08);
      border-color: rgba(79, 172, 254, 0.3);
    }

    .quiz-option input[type="radio"] {
      margin-right: 10px;
      accent-color: #4facfe;
    }

    /* Result */
    .result-card {
      text-align: center;
      padding: 32px;
    }

    .result-card h2 {
      font-size: 22px;
      color: #fff;
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
      background: rgba(255, 255, 255, 0.04);
      border: 1px solid rgba(255, 255, 255, 0.06);
    }

    .result-stat .label {
      font-size: 11px;
      text-transform: uppercase;
      letter-spacing: 1px;
      color: rgba(255, 255, 255, 0.4);
      margin-bottom: 6px;
    }

    .result-stat .value {
      font-size: 24px;
      font-weight: 700;
    }

    .result-stat .value.correct {
      color: #38ef7d;
    }

    .result-stat .value.wrong {
      color: #ff416c;
    }

    .result-stat .value.score {
      color: #4facfe;
    }

    .result-stat .value.total {
      color: #a885ff;
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
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
    }

    .alert-toast.error {
      background: linear-gradient(135deg, #ff416c, #ff4b2b);
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
      <img src="ecusta_logo.png" alt="Logo">
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
      <a href="account.php?q=3" class="nav-item <?php if (@$_GET['q'] == 3)
        echo 'active'; ?>">
        <span class="material-icons">leaderboard</span> Ranking
      </a>
    </div>
    <div class="sidebar-footer">
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
              <th>Topic</th>
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
            $result = mysqli_query($con, "SELECT * FROM quiz ORDER BY date DESC") or die('Error');
            $c = 1;
            while ($row = mysqli_fetch_array($result)) {
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
                echo '<td><a href="account.php?q=quiz&step=2&eid=' . $eid . '&n=1&t=' . $total . '" class="btn-start"><span class="material-icons" style="font-size:16px">play_arrow</span> Start</a></td></tr>';
              } else {
                echo '<tr><td>' . $c++ . '</td><td>' . $title . ' <span class="badge-done"><span class="material-icons" style="font-size:12px">check</span> Done</span></td><td>' . $total . '</td><td>' . ($sahi * $total) . '</td><td>' . $sahi . '</td><td>' . $wrong . '</td><td>' . $time . ' min</td><td></td><td></td></tr>';
              }
            }
            ?>
          </tbody>
        </table>
      </div>
    <?php } ?>

    <!-- Quiz description -->
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

    <!-- QUIZ TAKING -->
    <?php if (@$_GET['q'] == 'quiz' && @$_GET['step'] == 2) {
      $eid = @$_GET['eid'];
      $sn = @$_GET['n'];
      $total = @$_GET['t'];
      $q = mysqli_query($con, "SELECT * FROM questions WHERE eid='$eid' AND sn='$sn'");
      echo '<div class="page-header"><h1>Exam in Progress</h1><p>Question ' . $sn . ' of ' . $total . '</p></div><div class="card">';
      while ($row = mysqli_fetch_array($q)) {
        $qns = $row['qns'];
        $qid = $row['qid'];
        echo '<div class="quiz-question">Q' . $sn . '. ' . $qns . '</div>';
      }
      $q = mysqli_query($con, "SELECT * FROM options WHERE qid='$qid'");
      echo '<form action="update.php?q=quiz&step=2&eid=' . $eid . '&n=' . $sn . '&t=' . $total . '&qid=' . $qid . '" method="POST">';
      while ($row = mysqli_fetch_array($q)) {
        echo '<label class="quiz-option"><input type="radio" name="ans" value="' . $row['optionid'] . '">' . $row['option'] . '</label>';
      }
      echo '<br><button type="submit" class="btn-start" style="margin-top:10px"><span class="material-icons" style="font-size:16px">send</span> Submit Answer</button></form></div>';
    } ?>

    <!-- RESULT -->
    <?php if (@$_GET['q'] == 'result' && @$_GET['eid']) {
      $eid = @$_GET['eid'];
      $q = mysqli_query($con, "SELECT * FROM history WHERE eid='$eid' AND email='$email'") or die('Error');
      echo '<div class="card result-card"><h2>Exam Results</h2><div class="result-grid">';
      while ($row = mysqli_fetch_array($q)) {
        echo '<div class="result-stat"><div class="label">Total Questions</div><div class="value total">' . $row['level'] . '</div></div>';
        echo '<div class="result-stat"><div class="label">Correct</div><div class="value correct">' . $row['sahi'] . '</div></div>';
        echo '<div class="result-stat"><div class="label">Wrong</div><div class="value wrong">' . $row['wrong'] . '</div></div>';
        echo '<div class="result-stat"><div class="label">Score</div><div class="value score">' . $row['score'] . '</div></div>';
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
      echo '<div class="page-header"><h1>Exam History</h1><p>Your previously completed exams</p></div><div class="card"><table class="data-table"><thead><tr><th>#</th><th>Quiz</th><th>Questions</th><th>Correct</th><th>Wrong</th><th>Score</th></tr></thead><tbody>';
      $c = 0;
      while ($row = mysqli_fetch_array($q)) {
        $eid = $row['eid'];
        $q23 = mysqli_query($con, "SELECT title FROM quiz WHERE eid='$eid'") or die('Error');
        $title = '';
        while ($r2 = mysqli_fetch_array($q23)) {
          $title = $r2['title'];
        }
        $c++;
        echo '<tr><td>' . $c . '</td><td>' . $title . '</td><td>' . $row['level'] . '</td><td style="color:#38ef7d">' . $row['sahi'] . '</td><td style="color:#ff416c">' . $row['wrong'] . '</td><td style="color:#4facfe;font-weight:700">' . $row['score'] . '</td></tr>';
      }
      echo '</tbody></table></div>';
    } ?>

    <!-- RANKING -->
    <?php if (@$_GET['q'] == 3) {
      $q = mysqli_query($con, "SELECT * FROM rank ORDER BY score DESC") or die('Error');
      echo '<div class="page-header"><h1>Student Rankings</h1><p>Overall scores leaderboard</p></div><div class="card"><table class="data-table"><thead><tr><th>Rank</th><th>Name</th><th>Gender</th><th>College</th><th>Score</th></tr></thead><tbody>';
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
        $rankStyle = $c <= 3 ? 'color:#ffd700;font-weight:700' : 'color:#4facfe;font-weight:600';
        echo '<tr><td style="' . $rankStyle . '">' . $c . '</td><td>' . $uname . '</td><td>' . $gender . '</td><td>' . $college . '</td><td style="font-weight:700">' . $s . '</td></tr>';
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