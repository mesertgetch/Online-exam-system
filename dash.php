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
  <title>ECUSTA — Teacher Dashboard</title>
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
    }

    .sidebar-label {
      padding: 20px 28px 8px;
      font-size: 10px;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 2px;
      color: rgba(255, 255, 255, 0.25);
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

    .card {
      background: rgba(255, 255, 255, 0.04);
      backdrop-filter: blur(10px);
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

    .badge-done {
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
      background: linear-gradient(135deg, #4facfe, #00f2fe);
      color: #0e1a2b;
    }

    .btn-primary:hover {
      transform: translateY(-1px);
      box-shadow: 0 4px 15px rgba(79, 172, 254, 0.3);
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
      background: linear-gradient(135deg, #4facfe, #00f2fe);
      color: #0e1a2b;
      transition: all 0.2s ease;
      margin-top: 8px;
    }

    .btn-submit:hover {
      transform: translateY(-1px);
      box-shadow: 0 6px 20px rgba(79, 172, 254, 0.3);
    }

    .form-group {
      margin-bottom: 18px;
    }

    .form-group label {
      display: block;
      font-size: 12px;
      font-weight: 500;
      color: rgba(255, 255, 255, 0.6);
      margin-bottom: 8px;
      letter-spacing: 0.5px;
      text-transform: uppercase;
    }

    .form-group input,
    .form-group textarea,
    .form-group select {
      width: 100%;
      padding: 13px 16px;
      background: rgba(255, 255, 255, 0.06);
      border: 1px solid rgba(255, 255, 255, 0.1);
      border-radius: 10px;
      color: #fff;
      font-family: 'Inter', sans-serif;
      font-size: 14px;
      outline: none;
      transition: all 0.2s ease;
    }

    .form-group input::placeholder,
    .form-group textarea::placeholder {
      color: rgba(255, 255, 255, 0.25);
    }

    .form-group input:focus,
    .form-group textarea:focus,
    .form-group select:focus {
      border-color: rgba(79, 172, 254, 0.5);
      background: rgba(255, 255, 255, 0.08);
    }

    .form-group textarea {
      resize: vertical;
      min-height: 100px;
    }

    .form-group select option {
      background: #132238;
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
      color: #4facfe;
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
    </div>
    <div class="sidebar-nav">
      <div class="sidebar-label">Dashboard</div>
      <a href="dash.php?q=0" class="nav-item <?php if (@$_GET['q'] == 0 && !@$_GET['step'])
        echo 'active'; ?>">
        <span class="material-icons">home</span> My Quizzes
      </a>
      <a href="dash.php?q=1" class="nav-item <?php if (@$_GET['q'] == 1)
        echo 'active'; ?>">
        <span class="material-icons">assessment</span> Scores
      </a>
      <a href="dash.php?q=2" class="nav-item <?php if (@$_GET['q'] == 2)
        echo 'active'; ?>">
        <span class="material-icons">leaderboard</span> Ranking
      </a>
      <div class="sidebar-label">Quiz Management</div>
      <a href="dash.php?q=4" class="nav-item <?php if (@$_GET['q'] == 4)
        echo 'active'; ?>">
        <span class="material-icons">add_circle</span> Add Quiz
      </a>
      <a href="dash.php?q=5" class="nav-item <?php if (@$_GET['q'] == 5)
        echo 'active'; ?>">
        <span class="material-icons">delete_sweep</span> Remove Quiz
      </a>
    </div>
    <div class="sidebar-footer">
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

    <!-- HOME: My Quizzes -->
    <?php if (@$_GET['q'] == 0) { ?>
      <div class="page-header">
        <h1>My Quizzes</h1>
        <p>Quizzes you have created</p>
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
            </tr>
          </thead>
          <tbody>
            <?php
            $result = mysqli_query($con, "SELECT * FROM quiz WHERE email='$email' ORDER BY date DESC") or die('Error');
            $c = 1;
            while ($row = mysqli_fetch_array($result)) {
              echo '<tr><td>' . $c++ . '</td><td>' . $row['title'] . '</td><td>' . $row['total'] . '</td><td>' . ($row['sahi'] * $row['total']) . '</td><td>' . $row['sahi'] . '</td><td>' . $row['wrong'] . '</td><td>' . $row['time'] . ' min</td></tr>';
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
        <p>Scores for your quizzes</p>
      </div>
      <div class="card">
        <table class="data-table">
          <thead>
            <tr>
              <th>#</th>
              <th>Quiz</th>
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
        <h1>Create New Quiz</h1>
        <p>Enter the quiz details below</p>
      </div>
      <div class="card" style="max-width:600px">
        <form action="update.php?q=addquiz" method="POST">
          <div class="form-group">
            <label>Quiz Title</label>
            <input name="name" type="text" placeholder="e.g. Data Structures Midterm" required>
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
          <div class="form-group">
            <label>Description</label>
            <textarea name="desc" placeholder="Write quiz description..."></textarea>
          </div>
          <button type="submit" class="btn-submit">Create Quiz &amp; Add Questions</button>
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

    <!-- REMOVE QUIZ -->
    <?php if (@$_GET['q'] == 5) { ?>
      <div class="page-header">
        <h1>Remove Quiz</h1>
        <p>Delete quizzes you've created</p>
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