<?php
include_once 'dbConnection.php';
ob_start();
session_start();
file_put_contents("/opt/lampp/htdocs/Online-exam-system/debug_log.txt", "Update.php called. Q: " . @$_GET['q'] . " Key: " . @$_SESSION['key'] . " POST_EID: " . @$_POST['eid'] . " GET_EID: " . @$_GET['eid'] . "\n", FILE_APPEND);
$email = $_SESSION['email'];
//delete feedback
if (isset($_SESSION['key'])) {
  if (@$_GET['fdid'] && $_SESSION['key'] == 'prasanth123') {
    $id = @$_GET['fdid'];
    $result = mysqli_query($con, "DELETE FROM feedback WHERE id='$id' ") or die('Error');
    header("location:headdash.php?q=3");
  }
}

//delete user
if (isset($_SESSION['key'])) {
  if (@$_GET['demail'] && $_SESSION['key'] == 'prasanth123') {
    $demail = @$_GET['demail'];
    $r1 = mysqli_query($con, "DELETE FROM rank WHERE email='$demail' ") or die('Error');
    $r2 = mysqli_query($con, "DELETE FROM history WHERE email='$demail' ") or die('Error');
    $result = mysqli_query($con, "DELETE FROM user WHERE email='$demail' ") or die('Error');
    header("location:headdash.php?q=1");
  }
}

//delete admin

if (isset($_SESSION['key'])) {
  if (@$_GET['demail1'] && $_SESSION['key'] == 'prasanth123') {
    $demail1 = @$_GET['demail1'];
    $r1 = mysqli_query($con, "DELETE FROM rank WHERE email='$demail1' ") or die('Error');
    $r2 = mysqli_query($con, "DELETE FROM history WHERE email='$demail1' ") or die('Error');
    $result = mysqli_query($con, "DELETE FROM admin WHERE email='$demail1' and role ='admin' ") or die('Error');
    header("location:headdash.php?q=5");
  }
}



//remove quiz
if (isset($_SESSION['key'])) {
  if (@$_GET['q'] == 'rmquiz' && $_SESSION['key'] == 'prasanth123') {
    $eid = @$_GET['eid'];
    $result = mysqli_query($con, "SELECT * FROM questions WHERE eid='$eid'") or die('Error');
    while ($row = mysqli_fetch_array($result)) {
      $qid = $row['qid'];
      $r1 = mysqli_query($con, "DELETE FROM options WHERE qid='$qid'") or die('Error');
      $r2 = mysqli_query($con, "DELETE FROM answer WHERE qid='$qid' ") or die('Error');
    }
    $r3 = mysqli_query($con, "DELETE FROM questions WHERE eid='$eid' ") or die('Error');
    $r4 = mysqli_query($con, "DELETE FROM quiz WHERE eid='$eid' ") or die('Error');
    $r4 = mysqli_query($con, "DELETE FROM history WHERE eid='$eid' ") or die('Error');

    if (@$_GET['from'] == 'admin') {
      header("location:headdash.php?q=7");
    } else {
      header("location:dash.php?q=5");
    }
  }
}

//add quiz
if (isset($_SESSION['key'])) {
  if (@$_GET['q'] == 'addquiz' && ($_SESSION['key'] == 'prasanth123' || $_SESSION['key'] == 'sunny7785068889')) {
    $name = $_POST['name'];
    $name = ucwords(strtolower($name));
    $total = $_POST['total'];
    $sahi = $_POST['right'];
    $wrong = $_POST['wrong'];
    $time = $_POST['time'];
    $tag = $_POST['tag'];
    $desc = $_POST['desc'];
    $access_code = @$_POST['access_code'];
    $target_dept = @$_POST['target_dept'];
    $target_year = @$_POST['target_year'];
    $id = uniqid();
    $q3 = mysqli_query($con, "INSERT INTO quiz VALUES  ('$id','$name' , '$sahi' , '$wrong','$total','$time' ,'$desc','$tag', NOW() ,'$email', '$access_code', '$target_dept', '$target_year', 'active')");

    if (@$_GET['from'] == 'admin') {
      header("location:headdash.php?q=6&step=2&eid=$id&n=$total");
    } else {
      header("location:dash.php?q=4&step=2&eid=$id&n=$total");
    }
  }
}

//add question
if (isset($_SESSION['key'])) {
  file_put_contents("/opt/lampp/htdocs/Online-exam-system/debug_log.txt", "Session key is set: " . $_SESSION['key'] . "\n", FILE_APPEND);
  if (@$_GET['q'] == 'addqns' && ($_SESSION['key'] == 'prasanth123' || $_SESSION['key'] == 'sunny7785068889')) {
    $n = @$_GET['n'];
    $eid = @$_GET['eid'];
    $ch = @$_GET['ch'];
    file_put_contents("/opt/lampp/htdocs/Online-exam-system/debug_log.txt", "Processing addqns. N: $n, EID: $eid\n", FILE_APPEND);

    for ($i = 1; $i <= $n; $i++) {
      $qid = uniqid();
      $qns = $_POST['qns' . $i];
      $type = $_POST['type' . $i]; // Capture question type

      // Insert question with type
      if (!mysqli_query($con, "INSERT INTO questions VALUES  ('$eid','$qid','$qns' , '$ch' , '$i', '$type')")) {
        file_put_contents("/opt/lampp/htdocs/Online-exam-system/debug_log.txt", "SQL Error (Insert Q): " . mysqli_error($con) . "\n", FILE_APPEND);
      }

      if ($type == 'mcq' || $type == 'code' || empty($type)) {
        $a = $_POST[$i . '1'];
        $b = $_POST[$i . '2'];
        $c = $_POST[$i . '3'];
        $d = $_POST[$i . '4'];
        $oaid = uniqid();
        $obid = uniqid();
        $ocid = uniqid();
        $odid = uniqid();
        $qa = mysqli_query($con, "INSERT INTO options VALUES  ('$qid','$a','$oaid')") or die('Error61');
        $qb = mysqli_query($con, "INSERT INTO options VALUES  ('$qid','$b','$obid')") or die('Error62');
        $qc = mysqli_query($con, "INSERT INTO options VALUES  ('$qid','$c','$ocid')") or die('Error63');
        $qd = mysqli_query($con, "INSERT INTO options VALUES  ('$qid','$d','$odid')") or die('Error64');
        $e = $_POST['ans' . $i];
        switch ($e) {
          case 'a':
            $ansid = $oaid;
            break;
          case 'b':
            $ansid = $obid;
            break;
          case 'c':
            $ansid = $ocid;
            break;
          case 'd':
            $ansid = $odid;
            break;
          default:
            $ansid = $oaid;
        }
        $qans = mysqli_query($con, "INSERT INTO answer VALUES  ('$qid','$ansid')");

      } else if ($type == 'short' || $type == 'code') {
        $ans_text = $_POST['ans_text' . $i];
        // Escape text for safety
        $ans_text = mysqli_real_escape_string($con, $ans_text);
        $qans = mysqli_query($con, "INSERT INTO answer VALUES  ('$qid','$ans_text')");

      } else if ($type == 'match') {
        // Store pairs in options: option=Left, optionid=Right
        for ($k = 1; $k <= 4; $k++) {
          $left = $_POST['match_left_' . $i . '_' . $k];
          $right = $_POST['match_right_' . $i . '_' . $k];
          // Use prepared statements ideally, but sticking to style for now
          $left = mysqli_real_escape_string($con, $left);
          $right = mysqli_real_escape_string($con, $right);
          mysqli_query($con, "INSERT INTO options VALUES ('$qid', '$left', '$right')");
        }
        // Dummy answer entry
        mysqli_query($con, "INSERT INTO answer VALUES ('$qid', 'match_check_pairs')");
      }
    }
    if (@$_GET['from'] == 'admin') {
      header("location:headdash.php?q=0");
    } else {
      header("location:dash.php?q=0");
    }
  }
}

//quiz start
if (@$_GET['q'] == 'quiz' && @$_GET['step'] == 2) {
  $eid = @$_GET['eid'];
  $sn = @$_GET['n'];
  $total = @$_GET['t'];
  $ans = $_POST['ans'];
  $qid = @$_GET['qid'];
  $q = mysqli_query($con, "SELECT * FROM answer WHERE qid='$qid' ");
  while ($row = mysqli_fetch_array($q)) {
    $ansid = $row['ansid'];
  }
  if ($ans == $ansid) {
    $q = mysqli_query($con, "SELECT * FROM quiz WHERE eid='$eid' ");
    while ($row = mysqli_fetch_array($q)) {
      $sahi = $row['sahi'];
    }
    if ($sn == 1) {
      $q = mysqli_query($con, "INSERT INTO history VALUES('$email','$eid' ,'0','0','0','0',NOW())") or die('Error');
    }
    $q = mysqli_query($con, "SELECT * FROM history WHERE eid='$eid' AND email='$email' ") or die('Error115');

    while ($row = mysqli_fetch_array($q)) {
      $s = $row['score'];
      $r = $row['sahi'];
    }
    $r++;
    $s = $s + $sahi;
    $q = mysqli_query($con, "UPDATE `history` SET `score`=$s,`level`=$sn,`sahi`=$r, date= NOW()  WHERE  email = '$email' AND eid = '$eid'") or die('Error124');

  } else {
    $q = mysqli_query($con, "SELECT * FROM quiz WHERE eid='$eid' ") or die('Error129');

    while ($row = mysqli_fetch_array($q)) {
      $wrong = $row['wrong'];
    }
    if ($sn == 1) {
      $q = mysqli_query($con, "INSERT INTO history VALUES('$email','$eid' ,'0','0','0','0',NOW() )") or die('Error137');
    }
    $q = mysqli_query($con, "SELECT * FROM history WHERE eid='$eid' AND email='$email' ") or die('Error139');
    while ($row = mysqli_fetch_array($q)) {
      $s = $row['score'];
      $w = $row['wrong'];
    }
    $w++;
    $s = $s - $wrong;
    $q = mysqli_query($con, "UPDATE `history` SET `score`=$s,`level`=$sn,`wrong`=$w, date=NOW() WHERE  email = '$email' AND eid = '$eid'") or die('Error147');
  }
  if ($sn != $total) {
    $sn++;
    header("location:account.php?q=quiz&step=2&eid=$eid&n=$sn&t=$total") or die('Error152');
  } else if ($_SESSION['key'] != 'prasanth123') {
    $q = mysqli_query($con, "SELECT score FROM history WHERE eid='$eid' AND email='$email'") or die('Error156');
    while ($row = mysqli_fetch_array($q)) {
      $s = $row['score'];
    }
    $q = mysqli_query($con, "SELECT * FROM rank WHERE email='$email'") or die('Error161');
    $rowcount = mysqli_num_rows($q);
    if ($rowcount == 0) {
      $q2 = mysqli_query($con, "INSERT INTO rank VALUES('$email','$s',NOW())") or die('Error165');
    } else {
      while ($row = mysqli_fetch_array($q)) {
        $sun = $row['score'];
      }
      $sun = $s + $sun;
      $q = mysqli_query($con, "UPDATE `rank` SET `score`=$sun ,time=NOW() WHERE email= '$email'") or die('Error174');
    }
    header("location:account.php?q=result&eid=$eid");
  } else {
    header("location:account.php?q=result&eid=$eid");
  }
}

//restart quiz
if (@$_GET['q'] == 'quizre' && @$_GET['step'] == 25) {
  $eid = @$_GET['eid'];
  $n = @$_GET['n'];
  $t = @$_GET['t'];
  $q = mysqli_query($con, "SELECT score FROM history WHERE eid='$eid' AND email='$email'") or die('Error156');
  while ($row = mysqli_fetch_array($q)) {
    $s = $row['score'];
  }
  $q = mysqli_query($con, "DELETE FROM `history` WHERE eid='$eid' AND email='$email' ") or die('Error184');
  $q = mysqli_query($con, "SELECT * FROM rank WHERE email='$email'") or die('Error161');
  while ($row = mysqli_fetch_array($q)) {
    $sun = $row['score'];
  }
  $sun = $sun - $s;
  $q = mysqli_query($con, "UPDATE `rank` SET `score`=$sun ,time=NOW() WHERE email= '$email'") or die('Error174');
  header("location:account.php?q=quiz&step=2&eid=$eid&n=1&t=$t");
}

//add user (admin)
if (isset($_SESSION['key'])) {
  if (@$_GET['q'] == 'adduser' && $_SESSION['key'] == 'prasanth123') {
    $name = $_POST['name'];
    $gender = $_POST['gender'];
    $college = $_POST['college'];
    $uemail = $_POST['email'];
    $mob = $_POST['mob'];
    $password = md5($_POST['password']);
    $year = $_POST['year'];
    $q3 = mysqli_query($con, "INSERT INTO user VALUES ('$name','$gender','$college','$uemail','$mob','$password', '$year')");
    header("location:headdash.php?q=1");
  }
}


// Check Access Code
if (@$_GET['q'] == 'checkcode') {
  $eid = @$_GET['eid'];
  $code = $_POST['access_code'];
  $q = mysqli_query($con, "SELECT access_code FROM quiz WHERE eid='$eid'");
  while ($row = mysqli_fetch_array($q)) {
    $dbcode = $row['access_code'];
  }
  if (empty($dbcode) || $code == $dbcode) {
    header("location:account.php?q=exam&eid=$eid");
  } else {
    header("location:account.php?q=access&eid=$eid&w=Wrong Access Code");
  }
}

// Submit Full Exam
if (@$_GET['q'] == 'submitexam') {
  $eid = @$_GET['eid'];

  // Get quiz details
  $q = mysqli_query($con, "SELECT * FROM quiz WHERE eid='$eid'");
  while ($row = mysqli_fetch_array($q)) {
    $sahi = $row['sahi'];
    $wrong = $row['wrong'];
    $total_qs_quiz = $row['total'];
  }

  $r = 0;
  $w = 0;
  $q = mysqli_query($con, "SELECT * FROM questions WHERE eid='$eid'");
  while ($row = mysqli_fetch_array($q)) {
    $qid = $row['qid'];

    $type = $row['question_type']; // Get type

    // Get correct answer (for MCQ/Short)
    $q2 = mysqli_query($con, "SELECT * FROM answer WHERE qid='$qid'");
    while ($row2 = mysqli_fetch_array($q2)) {
      $ansid = $row2['ansid'];
    }

    // Check user answer
    if (isset($_POST['ans_' . $qid])) {
      $user_ans = $_POST['ans_' . $qid];

      $is_correct = false;

      if ($type == 'mcq' || $type == 'code' || empty($type)) {
        if ($user_ans == $ansid)
          $is_correct = true;
      } else if ($type == 'short') {
        // Normalize strings: trim, lowercase
        if (trim(strtolower($user_ans)) == trim(strtolower($ansid)))
          $is_correct = true;
      } else if ($type == 'match') {
        // Verify pairs
        if (is_array($user_ans) && count($user_ans) >= 1) {
          $all_match = true;
          foreach ($user_ans as $left => $right) {
            $left_esc = mysqli_real_escape_string($con, $left);
            $right_esc = mysqli_real_escape_string($con, $right);
            // Check if this pair exists in options
            $check = mysqli_query($con, "SELECT * FROM options WHERE qid='$qid' AND `option`='$left_esc' AND optionid='$right_esc'");
            if (mysqli_num_rows($check) == 0) {
              $all_match = false;
              break;
            }
          }
          if ($all_match && count($user_ans) == 4)
            $is_correct = true; // Assume 4 pairs required strictly
        }
      }

      if ($is_correct) {
        $r++;
      } else {
        $w++;
      }
    }
  }

  $score = ($r * $sahi) - ($w * $wrong);

  // Insert history
  // using total_qs_quiz for 'level' column which seems to track progress/count
  $q = mysqli_query($con, "INSERT INTO history VALUES('$email','$eid' ,'$score','$total_qs_quiz','$r','$w',NOW())");

  // Update rank
  $q = mysqli_query($con, "SELECT * FROM rank WHERE email='$email'");
  $rowcount = mysqli_num_rows($q);
  if ($rowcount == 0) {
    $q2 = mysqli_query($con, "INSERT INTO rank VALUES('$email','$score',NOW())");
  } else {
    while ($row = mysqli_fetch_array($q)) {
      $sun = $row['score'];
    }
    $sun = $score + $sun;
    $q = mysqli_query($con, "UPDATE `rank` SET `score`=$sun ,time=NOW() WHERE email= '$email'");
  }

  header("location:account.php?q=result&eid=$eid");
}
// EDIT QUIZ
if (isset($_SESSION['key'])) {
  if (@$_GET['q'] == 'editquiz' && ($_SESSION['key'] == 'prasanth123' || $_SESSION['key'] == 'sunny7785068889')) {
    $eid = @$_GET['eid'];
    $name = $_POST['name'];
    $name = ucwords(strtolower($name));
    $time = $_POST['time'];
    $target_dept = @$_POST['target_dept'];
    $target_year = @$_POST['target_year'];
    $q = mysqli_query($con, "UPDATE quiz SET title='$name', time='$time', target_dept='$target_dept', target_year='$target_year' WHERE eid='$eid'");
    if (@$_GET['from'] == 'admin') {
      header("location:headdash.php?q=manage_quiz&eid=$eid");
    } else {
      header("location:dash.php?q=manage_quiz&eid=$eid");
    }
  }
}

// EDIT QUESTION
if (isset($_SESSION['key'])) {
  if (@$_GET['q'] == 'editqns' && ($_SESSION['key'] == 'prasanth123' || $_SESSION['key'] == 'sunny7785068889')) {
    $qid = @$_GET['qid'];
    $qns = $_POST['qns'];
    $eid = $_POST['eid'];
    $qns = addslashes($qns);
    mysqli_query($con, "UPDATE questions SET qns='$qns' WHERE qid='$qid'");
    if (isset($_POST['option'])) {
      $options = $_POST['option'];
      $oids = $_POST['oid'];
      foreach ($options as $index => $opt) {
        $oid = $oids[$index];
        $opt = addslashes($opt);
        mysqli_query($con, "UPDATE options SET option='$opt' WHERE optionid='$oid'");
      }
      $ans = $_POST['ans'];
      $ans = addslashes($ans);
      mysqli_query($con, "UPDATE answer SET ansid='$ans' WHERE qid='$qid'");
    }
    if (isset($_POST['ans_text'])) {
      $ans_text = $_POST['ans_text'];
      $ans_text = addslashes($ans_text);
      mysqli_query($con, "UPDATE answer SET ansid='$ans_text' WHERE qid='$qid'");
    }
    if (@$_GET['from'] == 'admin') {
      header("location:headdash.php?q=manage_quiz&eid=$eid");
    } else {
      header("location:dash.php?q=manage_quiz&eid=$eid");
    }
  }
}

// EXTEND TIME (Admin)
if (@$_GET['q'] == 'extendtime') {
  if (isset($_SESSION['key']) && ($_SESSION['key'] == 'sunny7785068889' || $_SESSION['key'] == 'prasanth123')) {
    $eid = $_POST['eid'];
    $time_add = (int) $_POST['time_add'];
    if ($time_add > 0) {
      mysqli_query($con, "UPDATE quiz SET time = time + $time_add WHERE eid='$eid'");
    }
    header("location:headdash.php?q=manage_quiz&eid=$eid");
    exit;
  } else {
    header("location:admin_login.php?w=Session expired. Please login again.");
    exit;
  }
}

// RESET USER EXAM (Admin)
if (@$_GET['q'] == 'reset_user_exam') {
  if (isset($_SESSION['key']) && ($_SESSION['key'] == 'sunny7785068889' || $_SESSION['key'] == 'prasanth123')) {
    $eid = $_POST['eid'];
    $email = trim($_POST['email']);

    $q = mysqli_query($con, "SELECT score FROM history WHERE eid='$eid' AND email='$email'");

    while ($row = mysqli_fetch_array($q)) {
      $score = $row['score'];
      mysqli_query($con, "UPDATE rank SET score = score - $score WHERE email='$email'");
    }
    mysqli_query($con, "DELETE FROM history WHERE eid='$eid' AND email='$email'");
    header("location:headdash.php?q=manage_quiz&eid=$eid");
    exit;
  } else {
    header("location:admin_login.php?w=Session expired. Please login again.");
    exit;
  }
}

// ADD DEPARTMENT (Admin)
if (isset($_SESSION['key'])) {
  if (@$_GET['q'] == 'add_dept' && ($_SESSION['key'] == 'sunny7785068889' || $_SESSION['key'] == 'prasanth123')) {
    $dept_name = $_POST['dept_name'];
    $year_labels = $_POST['year_labels'];
    $stmt = $con->prepare("INSERT INTO departments (dept_name, year_labels) VALUES (?, ?)");
    $stmt->bind_param("ss", $dept_name, $year_labels);
    $stmt->execute();
    header("location:headdash.php?q=manage_dept");
  }
}

// DELETE DEPARTMENT (Admin)
if (@$_GET['q'] == 'delete_dept') {
  if (isset($_SESSION['key']) && ($_SESSION['key'] == 'sunny7785068889' || $_SESSION['key'] == 'prasanth123')) {
    $id = @$_GET['id'];
    mysqli_query($con, "DELETE FROM departments WHERE dept_id='$id'");
    header("location:headdash.php?q=manage_dept");
    exit;
  } else {
    header("location:admin_login.php?w=Session expired. Please login again.");
    exit;
  }
}

// RESET ALL USER EXAM (Admin)
if (@$_GET['q'] == 'reset_all_exam') {
  if (isset($_SESSION['key']) && ($_SESSION['key'] == 'sunny7785068889' || $_SESSION['key'] == 'prasanth123')) {
    $eid = $_POST['eid'];
    $q = mysqli_query($con, "SELECT email, score FROM history WHERE eid='$eid'");
    while ($row = mysqli_fetch_array($q)) {
      $uemail = $row['email'];
      $score = $row['score'];
      mysqli_query($con, "UPDATE rank SET score = score - $score WHERE email='$uemail'");
    }
    mysqli_query($con, "DELETE FROM history WHERE eid='$eid'");
    header("location:headdash.php?q=manage_quiz&eid=$eid");
    exit;
  } else {
    header("location:admin_login.php?w=Session expired. Please login again.");
    exit;
  }
}

// TOGGLE EXAM STATUS (Admin/Teacher)
if (@$_GET['q'] == 'toggle_exam_status') {
  if (isset($_SESSION['key']) && ($_SESSION['key'] == 'sunny7785068889' || $_SESSION['key'] == 'prasanth123')) {
    $eid = @$_GET['eid'];
    $status = @$_GET['status'];
    mysqli_query($con, "UPDATE quiz SET status='$status' WHERE eid='$eid'");
    if (@$_GET['from'] == 'admin') {
      header("location:headdash.php?q=manage_quiz&eid=$eid");
    } else {
      header("location:dash.php?q=manage_quiz&eid=$eid");
    }
    exit;
  } else {
    header("location:admin_login.php?w=Session expired. Please login again.");
    exit;
  }
}

// DELETE QUESTION (Admin)
if (@$_GET['q'] == 'delete_question') {
  if (isset($_SESSION['key']) && ($_SESSION['key'] == 'sunny7785068889' || $_SESSION['key'] == 'prasanth123')) {
    $qid = @$_GET['qid'];
    $eid = @$_GET['eid'];
    // Delete options, answer, then question
    mysqli_query($con, "DELETE FROM options WHERE qid='$qid'");
    mysqli_query($con, "DELETE FROM answer WHERE qid='$qid'");
    mysqli_query($con, "DELETE FROM questions WHERE qid='$qid'");
    // Update quiz total count
    $count_q = mysqli_query($con, "SELECT COUNT(*) as cnt FROM questions WHERE eid='$eid'");
    $count_row = mysqli_fetch_array($count_q);
    $new_total = $count_row['cnt'];
    mysqli_query($con, "UPDATE quiz SET total='$new_total' WHERE eid='$eid'");
    if (@$_GET['from'] == 'admin') {
      header("location:headdash.php?q=manage_quiz&eid=$eid");
    } else {
      header("location:dash.php?q=manage_quiz&eid=$eid");
    }
    exit;
  } else {
    header("location:admin_login.php?w=Session expired. Please login again.");
    exit;
  }
}

// UPDATE USER (Admin)
if (@$_GET['q'] == 'update_user') {
  if (isset($_SESSION['key']) && ($_SESSION['key'] == 'sunny7785068889' || $_SESSION['key'] == 'prasanth123')) {
    $uemail = $_POST['email'];
    $name = $_POST['name'];
    $gender = $_POST['gender'];
    $college = $_POST['college'];
    $year = @$_POST['year'];
    $mob = $_POST['mob'];
    $new_password = @$_POST['new_password'];

    $sql = "UPDATE user SET name='$name', gender='$gender', college='$college', mob='$mob'";
    if ($year) {
      $sql .= ", year='$year'";
    }
    if (!empty($new_password)) {
      $hashed = md5($new_password);
      $sql .= ", password='$hashed'";
    }
    $sql .= " WHERE email='$uemail'";
    mysqli_query($con, $sql);
    header("location:headdash.php?q=1");
    exit;
  } else {
    header("location:admin_login.php?w=Session expired. Please login again.");
    exit;
  }
}

// CHECK TIME (Polling)
if (@$_GET['q'] == 'check_time' && @$_GET['eid']) {
  $eid = @$_GET['eid'];
  $q = mysqli_query($con, "SELECT time FROM quiz WHERE eid='$eid'");
  if ($row = mysqli_fetch_array($q)) {
    echo $row['time'];
  }
  exit;
}