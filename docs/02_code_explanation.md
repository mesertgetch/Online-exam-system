# ECUSTA — File-by-File Code Explanation

This document explains **how each file works** and how it contributes to the system's purpose.

---

## Config Layer

### `config/dbConnection.php`
**Purpose:** Establishes a MySQL database connection.

Creates a `mysqli` connection object `$con` to the `project1` database on `localhost` using the `root` user. Every other PHP file includes this to interact with the database.

```php
$con = new mysqli('localhost', 'root', '', 'project1');
```

---

## Authentication Includes (`includes/`)

### `includes/login.php` — Student Login
**Purpose:** Authenticates students.

1. Receives `email` and `password` via POST.
2. Hashes the password with MD5.
3. Queries the `user` table for a matching row.
4. If found → sets `$_SESSION['name']` and `$_SESSION['email']`, redirects to `account.php?q=1`.
5. If not found → redirects back with an error message `?w=Wrong Username or Password`.

---

### `includes/sign.php` — Student Registration
**Purpose:** Registers a new student account.

1. Receives name, gender, college, email, mobile, password, year via POST.
2. Capitalizes the name (`ucwords`), hashes the password with MD5.
3. Inserts a new row into the `user` table.
4. On success → starts a session and redirects to `account.php`.
5. On failure (duplicate email) → redirects back with error.

---

### `includes/admin.php` — Teacher Login
**Purpose:** Authenticates teachers.

1. Queries the `admin` table where `role = 'admin'`.
2. On match → sets session variables including a secret key `$_SESSION['key'] = 'prasanth123'` and redirects to `dash.php`.
3. On failure → redirects to `admin_login.php` with "Access denied".

> The session key `prasanth123` is used throughout the system to authorize privileged operations.

---

### `includes/head.php` — Head Admin Login
**Purpose:** Authenticates the head administrator.

Nearly identical to `admin.php` but queries `role = 'head'` and redirects to `headdash.php`. Sets `$_SESSION['name'] = 'Admin'`.

---

### `includes/signadmin.php` — Register Teacher
**Purpose:** Inserts a new teacher/admin account.

Receives email and password via POST and inserts into the `admin` table with `role = 'admin'`. This is used from the head admin dashboard.

---

### `includes/feed.php` — Submit Feedback
**Purpose:** Stores student feedback.

Receives name, email, subject, and feedback text via POST. Generates a unique ID, captures date/time, and inserts into the `feedback` table.

---

## Frontend Pages

### `index.php` — Student Portal (Login & Registration)
**Purpose:** The landing page with tabbed Sign In / Sign Up forms.

**How it works:**

1. **Sign In Tab** → Form posts to `includes/login.php?q=index.php`.
2. **Sign Up Tab** → Form posts to `includes/sign.php?q=account.php`.
3. JavaScript `validateSignUp()` performs client-side validation (name, email format, password length, password match).
4. `updateRegYears()` dynamically populates the Year dropdown based on the selected Department (using `data-years` attribute from the `departments` table).
5. Error messages from the server are shown as toast alerts via `showAlert()`.
6. Includes a link to `admin_login.php` for teachers/admins.

---

### `admin_login.php` — Admin & Teacher Portal
**Purpose:** Login page with tabbed Admin / Teacher forms.

**How it works:**

1. **Admin Tab** → Form posts to `includes/head.php` (head admin login).
2. **Teacher Tab** → Form posts to `includes/admin.php` (teacher login).
3. Error messages from redirects (`?w=...`) are displayed as toast alerts.
4. Includes a "Back to Student Portal" link.

---

### `account.php` — Student Dashboard *(1926 lines)*
**Purpose:** The complete student experience after login.

**Session Guard:** Redirects to `index.php` if not logged in.

**Sections (controlled by `?q=` parameter):**

| URL Parameter | Section | Description |
|---|---|---|
| `?q=1` | **Home / Available Exams** | Lists all active exams the student is eligible for (filtered by department & year). Shows "Done" badge for completed exams. |
| `?q=1&fid=...` | **Exam Description** | Shows the exam's title, date, and introduction text. |
| `?q=access&eid=...` | **Exam Instructions** | Moodle-style pre-exam page with rules, exam details grid (questions, duration, marks), access code input (if required), and a confirmation checkbox. |
| `?q=exam&eid=...` | **Full Exam UI** | The complete exam-taking interface (see Exam UI below). |
| `?q=result&eid=...` | **Results** | Shows score breakdown: total questions, correct, wrong, score, and overall ranking score. |
| `?q=2` | **History** | Lists all previously completed exams with scores. |

**Full Exam UI (`?q=exam`) — Key Features:**

- **Question Navigation Panel** — Grid of numbered buttons showing status (not visited, current, answered, flagged).
- **Timer** — Countdown from the exam's time limit. Warns (pulse animation) when < 2 minutes. Auto-submits when time expires.
- **Anti-Cheat System:**
  - Enters fullscreen mode on start.
  - Detects tab switching (`visibilitychange` event) and counts violations.
  - After 3 violations → auto-submits the exam.
  - Blocks right-click, F11, Escape, Ctrl+W/T/N, Alt+Tab.
  - Blocks browser back button via `history.pushState`.
  - Shows a blur overlay with warning on violation.
- **Question Types:**
  - **MCQ** — Clickable option cards with A/B/C/D indicators.
  - **Code** — Syntax-highlighted code blocks with MCQ-style options.
  - **Short Answer** — Textarea with monospace styling.
  - **Matching** — Left items with dropdown selectors for right matches (shuffled).
- **Time Sync** — Polls `update.php?q=check_time` every 15 seconds to detect if admin extended time.
- **Submit** — Injects all answers as hidden form fields and submits to `update.php?q=submitexam`.

---

### `dash.php` — Teacher Dashboard *(1067 lines)*
**Purpose:** Exam management interface for teachers.

**Session Guard:** Requires both `email` and `key` session variables.

**Sections:**

| URL Parameter | Section | Description |
|---|---|---|
| `?q=0` | **My Exams** | Lists all exams created by this teacher with access code, status, question count, marks, and "Manage" button. |
| `?q=1` | **Scores** | Shows all student scores for the teacher's exams (joins `user`, `history`, `quiz` tables). |
| `?q=2` | **Rankings** | Overall leaderboard sorted by cumulative score from the `rank` table. |
| `?q=4` | **Create Exam (Step 1)** | Form for exam metadata: title, access code, total questions, time limit, marks per correct/wrong, tag, target department, target year, description. |
| `?q=4&step=2` | **Create Exam (Step 2)** | Dynamic form for adding N questions. Each question has a type selector (MCQ, Short Answer, Code, Matching) that toggles the appropriate input fields via `toggleQuestionType()`. |
| `?q=5` | **Remove Exam** | Lists teacher's exams with delete buttons. |
| `?q=manage_quiz` | **Manage Exam** | Edit exam details (title, time, access code, target dept/year), toggle active/disabled status, view/add/edit questions. |
| `?q=edit_question` | **Edit Question** | Edit question text, options, correct answer. |

---

### `headdash.php` — Head Admin Dashboard *(1715 lines)*
**Purpose:** Full administrative control panel.

**Includes everything from `dash.php` plus:**

| Section | Description |
|---|---|
| **Dashboard** | Statistics overview (user count, exam count, feedback count, teacher count). |
| **All Users** | View all students with edit and delete options. |
| **Register User** | Form to add a new student account. |
| **Feedback** | Read and delete student feedback. |
| **All Teachers** | View all teacher accounts, add new teachers via `signadmin.php`. |
| **Add Exam** | Same as teacher's create exam but with `?from=admin` routing. |
| **All Exams** | View and delete any exam in the system. |
| **Manage Departments** | Add/delete academic departments with year labels. |
| **Manage Exam** | Extended management: extend time, reset individual/all user attempts, edit questions, remove questions, edit user data. |

---

### `update.php` — Central Action Handler *(588 lines)*
**Purpose:** Processes all form submissions and actions via GET/POST parameters.

This is the **backbone of the system** — a single file that handles 20+ distinct operations:

| `?q=` Parameter | Action | Access |
|---|---|---|
| `?fdid=...` | Delete feedback | Admin |
| `?demail=...` | Delete user (+ rank, history) | Admin |
| `?demail1=...` | Delete teacher (+ rank, history) | Admin |
| `rmquiz` | Delete exam (+ questions, options, answers, history) | Admin/Teacher |
| `addquiz` | Create new exam (insert into `quiz` table) | Admin/Teacher |
| `addqns` | Add questions to exam (with options, answers based on type) | Admin/Teacher |
| `quiz&step=2` | Process individual question answer (legacy per-question flow) | Student |
| `quizre&step=25` | Restart exam attempt (delete history, adjust rank) | Student |
| `adduser` | Register new user (admin use) | Admin |
| `checkcode` | Verify exam access code | Student |
| `submitexam` | Grade full exam submission (MCQ/short/match scoring) | Student |
| `editquiz` | Update exam details (title, time, targets) | Admin/Teacher |
| `editqns` | Update question text, options, correct answer | Admin/Teacher |
| `extendtime` | Add minutes to exam timer | Admin |
| `reset_user_exam` | Reset one user's attempt (adjust rank) | Admin |
| `add_dept` | Add academic department | Admin |
| `delete_dept` | Remove department | Admin |
| `reset_all_exam` | Reset all user attempts for an exam | Admin |
| `toggle_exam_status` | Switch exam between active/disabled | Admin/Teacher |
| `delete_question` | Remove a question (+ update quiz total) | Admin/Teacher |
| `update_user` | Edit user profile data | Admin |
| `check_time` | Return current exam time (polling endpoint) | Student JS |

**Scoring Algorithm (`submitexam`):**
```
For each question in the exam:
  1. Get the correct answer from the `answer` table
  2. Compare with user's submitted answer:
     - MCQ/Code: exact optionid match
     - Short: case-insensitive trimmed string match
     - Match: verify all 4 left-right pairs exist in options table
  3. Count correct (r) and wrong (w)
  
Final score = (r × marks_per_correct) - (w × marks_per_wrong)

Insert into `history` table
Update cumulative score in `rank` table
```

---

### `logout.php` — Session Termination
**Purpose:** Destroys the session and redirects.

Takes a `?q=` parameter specifying the redirect target (e.g., `index.php` or `admin_login.php`).

---

## Theme & Styling

### `css/theme.css`
Defines CSS custom properties for dark/light mode theming: colors, backgrounds, borders, gradients, shadows. Used by all pages via `var(--property-name)`.

### `js/theme.js`
Manages the theme toggle button. Reads from `localStorage`, toggles `data-theme` attribute on `<html>`, updates the toggle button icon. Initializes on `DOMContentLoaded`.

---

## Database Schema (`database/project1.sql`)

Contains `CREATE TABLE` statements and seed data for all 10 tables. Patch files (`patch_schema*.php`) handle incremental migrations (adding `question_type`, `access_code`, `target_dept`, `target_year`, `status` columns and the `departments` table).
