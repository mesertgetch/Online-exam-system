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
  <title>ECUSTA — Teacher Dashboard</title>
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
      padding: 16px 12px;
      display: flex;
      flex-direction: column;
      gap: 4px;
      overflow-y: auto;
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

    .card {
      background: var(--bg-card);
      backdrop-filter: blur(10px);
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

    .badge-done {
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

    .btn-action {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 8px 18px;
      border-radius: 8px;
      font-size: 13px;
      font-weight: 600;
      text-decoration: none;
      transition: all 0.2s ease;
      border: none;
      cursor: pointer;
    }

    .btn-primary {
      background: var(--accent-gradient);
      color: var(--bg-primary);
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
      background: var(--accent-gradient);
      color: var(--bg-primary);
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
      color: var(--accent);
      margin-bottom: 16px;
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
    </div>
    <div class="sidebar-nav">
      <div class="sidebar-label">Dashboard</div>
      <a href="dash.php?q=0" class="nav-item <?php if (@$_GET['q'] == 0 && !@$_GET['step'])
        echo 'active'; ?>">
        <span class="material-icons">home</span> My Exams
      </a>
      <a href="dash.php?q=1" class="nav-item <?php if (@$_GET['q'] == 1)
        echo 'active'; ?>">
        <span class="material-icons">assessment</span> Scores
      </a>
      <a href="dash.php?q=2" class="nav-item <?php if (@$_GET['q'] == 2)
        echo 'active'; ?>">
        <span class="material-icons">leaderboard</span> Ranking
      </a>
      <div class="sidebar-label">Exam Management</div>
      <a href="dash.php?q=4" class="nav-item <?php if (@$_GET['q'] == 4)
        echo 'active'; ?>">
        <span class="material-icons">add_circle</span> Add Exam
      </a>
      <a href="dash.php?q=5" class="nav-item <?php if (@$_GET['q'] == 5)
        echo 'active'; ?>">
        <span class="material-icons">delete_sweep</span> Remove Exam
      </a>
    </div>
    <div class="sidebar-footer">
      <div style="display:flex;align-items:center;justify-content:space-between;padding:0 10px 8px">
        <button class="theme-toggle" title="Toggle dark/light mode">
          <span class="material-icons">light_mode</span>
        </button>
      </div>
      <div class="user-info">
        <div class="avatar"><?php echo strtoupper(substr($email, 0, 1)); ?></div>
        <div>
          <div class="user-name">Teacher</div>
          <div class="user-email"><?php echo htmlspecialchars($email); ?></div>
        </div>
      </div>
      <a href="logout.php?q=admin_login.php" class="btn-signout">
        <span class="material-icons" style="font-size:18px">logout</span> Sign Out
      </a>
    </div>
  </nav>

  <main class="main">

    <!-- HOME: My Exams -->
    <?php if (@$_GET['q'] == 0) { ?>
      <div class="page-header">
        <h1>My Exams</h1>
        <p>Exams you have created</p>
      </div>
      <div class="card">
        <table class="data-table">
          <thead>
            <tr>
              <th>#</th>
              <th>Exam Name</th>
              <th>Access Code</th>
              <th>Status</th>
              <th>Questions</th>
              <th>Marks</th>
              <th>+</th>
              <th>−</th>
              <th>Time</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $result = mysqli_query($con, "SELECT * FROM quiz WHERE email='$email' ORDER BY date DESC") or die('Error');
            $c = 1;
            while ($row = mysqli_fetch_array($result)) {
              $status = @$row['status'] ?: 'active';
              $status_color = ($status == 'active' ? '#2ecc71' : '#e74c3c');
              $code = htmlspecialchars(@$row['access_code'] ?: '—');
              echo '<tr><td>' . $c++ . '</td><td>' . $row['title'] . '</td><td><code style="background:var(--bg-input);padding:3px 8px;border-radius:6px;font-size:13px;letter-spacing:1px;font-weight:600;color:var(--text-primary)">' . $code . '</code></td><td><span class="badge" style="background:' . $status_color . ';color:#fff;padding:2px 6px;border-radius:4px;font-size:10px;text-transform:uppercase">' . $status . '</span></td><td>' . $row['total'] . '</td><td>' . ($row['sahi'] * $row['total']) . '</td><td>' . $row['sahi'] . '</td><td>' . $row['wrong'] . '</td><td>' . $row['time'] . ' min</td>
              <td><a href="dash.php?q=manage_quiz&eid=' . $row['eid'] . '" class="btn-action btn-primary" style="margin-right:5px"><span class="material-icons" style="font-size:16px">edit</span> Manage</a></td></tr>';
            }
            ?>
          </tbody>
        </table>
      </div>
    <?php } ?>

    <!-- SCORES -->
    <?php if (@$_GET['q'] == 1) { ?>
      <div class="page-header">
        <h1>Student Scores</h1>
        <p>Scores for your exams</p>
      </div>
      <div class="card">
        <table class="data-table">
          <thead>
            <tr>
              <th>#</th>
              <th>Exam Name</th>
              <th>Student</th>
              <th>College</th>
              <th>Score</th>
              <th>Date</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $q = mysqli_query($con, "SELECT distinct q.title,u.name,u.college,h.score,h.date from user u,history h,quiz q where q.email='$email' and q.eid=h.eid and h.email=u.email order by q.eid DESC") or die('Error');
            $c = 0;
            while ($row = mysqli_fetch_array($q)) {
              $c++;
              echo '<tr><td>' . $c . '</td><td>' . $row['title'] . '</td><td>' . $row['name'] . '</td><td>' . $row['college'] . '</td><td style="color:#4facfe;font-weight:700">' . $row['score'] . '</td><td>' . $row['date'] . '</td></tr>';
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
        <p>Overall scores leaderboard</p>
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
              $rankStyle = $c <= 3 ? 'color:#ffd700;font-weight:700' : 'color:#4facfe;font-weight:600';
              echo '<tr><td style="' . $rankStyle . '">' . $c . '</td><td>' . $uname . '</td><td>' . $gender . '</td><td>' . $college . '</td><td style="font-weight:700">' . $s . '</td></tr>';
            }
            ?>
          </tbody>
        </table>
      </div>
    <?php } ?>

    <!-- ADD QUIZ STEP 1 -->
    <?php if (@$_GET['q'] == 4 && !@$_GET['step']) { ?>
      <div class="page-header">
        <h1>Create New Exam</h1>
        <p>Enter the exam details below</p>
      </div>
      <div class="card" style="max-width:600px">
        <form action="update.php?q=addquiz" method="POST">
          <div class="form-group">
            <label>Exam Title</label>
            <input name="name" type="text" placeholder="e.g. Data Structures Midterm" required>
          </div>
          <div class="form-group">
            <label>Access Code (Mandatory)</label>
            <input name="access_code" type="text" placeholder="e.g. A1B2C3" maxlength="20" required>
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
            <input name="tag" type="text" placeholder="e.g. #midterm #cs">
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
            <label>Exam Description</label>
            <textarea name="desc" placeholder="Write exam description..."></textarea>
          </div>
          <button type="submit" class="btn-submit">Create Exam &amp; Add Questions</button>
        </form>
      </div>
    <?php } ?>

    <!-- ADD QUIZ STEP 2: Questions -->
    <?php if (@$_GET['q'] == 4 && @$_GET['step'] == 2) { ?>
      <div class="page-header">
        <h1>Add Questions</h1>
        <p>Enter questions and their options</p>
      </div>
      <div class="card" style="max-width:700px">
        <form action="update.php?q=addqns&n=<?php echo @$_GET['n']; ?>&eid=<?php echo @$_GET['eid']; ?>&ch=4"
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
          <button type="submit" class="btn-submit">Submit Questions</button>
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

    <!-- REMOVE EXAM -->
    <?php if (@$_GET['q'] == 5) { ?>
      <div class="page-header">
        <h1>Remove Exam</h1>
        <p>Delete exams you've created</p>
      </div>
      <div class="card">
        <table class="data-table">
          <thead>
            <tr>
              <th>#</th>
              <th>Exam Name</th>
              <th>Questions</th>
              <th>Marks</th>
              <th>Time</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <?php
            $result = mysqli_query($con, "SELECT * FROM quiz WHERE email='$email' ORDER BY date DESC") or die('Error');
            $c = 1;
            while ($row = mysqli_fetch_array($result)) {
              echo '<tr><td>' . $c++ . '</td><td>' . $row['title'] . '</td><td>' . $row['total'] . '</td><td>' . ($row['sahi'] * $row['total']) . '</td><td>' . $row['time'] . ' min</td>';
              echo '<td><a href="update.php?q=rmquiz&eid=' . $row['eid'] . '" class="btn-action btn-danger"><span class="material-icons" style="font-size:16px">delete</span> Remove</a></td></tr>';
            }
            ?>
          </tbody>
        </table>
      </div>
    <?php } ?>

    <!-- MANAGE QUIZ (Teacher) -->
    <?php if (@$_GET['q'] == 'manage_quiz') {
      $eid = @$_GET['eid'];
      $q = mysqli_query($con, "SELECT * FROM quiz WHERE eid='$eid' AND email='$email' ");
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
        $access_code = @$row['access_code'];
      }
      ?>
      <div class="page-header">
        <h1>Manage Exam: <?php echo $title; ?></h1>
        <p>Edit details and questions</p>
      </div>

      <!-- Access Code Display -->
      <div class="card" style="display:flex;align-items:center;gap:16px;padding:20px;border-left:4px solid var(--accent)">
        <span class="material-icons" style="font-size:28px;color:var(--accent)">vpn_key</span>
        <div>
          <div
            style="font-size:11px;text-transform:uppercase;letter-spacing:1px;color:var(--text-dimmed);margin-bottom:4px">
            Access Code</div>
          <div style="font-size:22px;font-weight:700;letter-spacing:3px;color:var(--text-primary);font-family:monospace">
            <?php echo htmlspecialchars($access_code ?: '—'); ?>
          </div>
        </div>
      </div>

      <!-- Exam Details Form -->
      <div class="card">
        <h3>Exam Details</h3>
        <form action="update.php?q=editquiz&eid=<?php echo $eid; ?>" method="POST">
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
      <?php $status = @$row['status'] ?: 'active'; ?>
      <div class="form-group"
        style="margin:20px 0;padding:16px;background:var(--bg-card-hover);border-radius:12px;border:1px solid var(--border-primary)">
        <div style="display:flex;justify-content:space-between;align-items:center">
          <div>
            <label style="margin-bottom:4px;display:block;font-weight:600">Exam Release Status</label>
            <div style="font-size:14px;color:var(--text-secondary)">
              Current Status: <span class="badge"
                style="background:<?php echo ($status == 'active' ? '#2ecc71' : '#e74c3c'); ?>;color:#fff;padding:4px 10px;border-radius:6px;text-transform:uppercase;font-size:11px;font-weight:700"><?php echo ucfirst($status); ?></span>
            </div>
          </div>
          <a href="update.php?q=toggle_exam_status&eid=<?php echo $eid; ?>&status=<?php echo ($status == 'active' ? 'disabled' : 'active'); ?>&from=teacher"
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
              <td><a href="dash.php?q=edit_question&qid=' . $qid . '" class="btn-action btn-primary"><span class="material-icons" style="font-size:16px">edit</span> Edit</a></td></tr>';
            }
            ?>
          </tbody>
        </table>
        <div style="margin-top:16px">
          <a href="dash.php?q=4&step=2&eid=<?php echo $eid; ?>&n=1" class="btn-action btn-primary"
            style="display:inline-block">+ Add Question</a>
        </div>
      </div>
    <?php } ?>

    <!-- EDIT QUESTION (Teacher) -->
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
        <form action="update.php?q=editqns&qid=<?php echo $qid; ?>" method="POST">
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
            // Need to map options to A, B, C, D... simpler to just list them inputs
            // But we need to identify which is correct.
            // Correct answer is in 'answer' table.
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
            echo '<textarea name="ans_text" required>' . $ans_text . '</textarea></div>';
          }
          ?>
          <button type="submit" class="btn-submit">Update Question</button>
        </form>
      </div>
    <?php } ?>

    <!-- Feedback reading -->
    <?php if (@$_GET['fid']) {
      $id = @$_GET['fid'];
      $result = mysqli_query($con, "SELECT * FROM feedback WHERE id='$id'") or die('Error');
      while ($row = mysqli_fetch_array($result)) {
        echo '<div class="card"><h2 style="text-align:center;color:#fff;margin-bottom:16px">' . $row['subject'] . '</h2>';
        echo '<p style="color:rgba(255,255,255,0.5);margin-bottom:8px"><b>Date:</b> ' . date("d-m-Y", strtotime($row['date'])) . ' &bull; <b>Time:</b> ' . $row['time'] . ' &bull; <b>By:</b> ' . $row['name'] . '</p>';
        echo '<div style="color:rgba(255,255,255,0.75);line-height:1.8">' . $row['feedback'] . '</div></div>';
      }
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