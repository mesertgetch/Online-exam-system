# ECUSTA Online Exam System — System Overview

> **ECUSTA** = Ethiopian Catholic University St. Thomas Aquinas

## 1. Purpose

ECUSTA is a web-based **Online Examination System** that enables:

- **Students** to register, browse available exams, take timed exams (MCQ, short answer, matching, code), and review results.
- **Teachers** to create and manage exams, view student scores, and monitor rankings.
- **Head Admins** to manage users, teachers, departments, feedback, and oversee all exams system-wide.

---

## 2. Technology Stack

| Layer      | Technology              |
| ---------- | ----------------------- |
| Backend    | **PHP 7+** (procedural) |
| Database   | **MariaDB / MySQL**     |
| Web Server | **XAMPP (Apache)**      |
| Frontend   | **HTML5, CSS3, JS**     |
| Fonts      | Google Fonts (Inter)    |
| Icons      | Material Icons          |
| Theming    | Custom CSS variables    |
V
---

## 3. User Roles

```mermaid
graph TD
    subgraph Roles
        S["🎓 Student"]Thank you very much for your kind willingness to write a strong letter of support for me. I truly appreciate it.

The position I am applying for is Networking and System Design Lab Instructor. The role mainly involves:

Conducting practical lab sessions in computer networking and system design

Assisting students with hands-on configuration, troubleshooting, and implementation

Supporting course delivery through lab demonstrations and technical guidance

Preparing lab materials and ensuring proper documentation

Evaluating student lab performance and providing feedback

The focus is on practical networking skills (such as network configuration, routing, switching, and basic server setup) as well as system design principles and implementation.

If possible, I would kindly need the letter within [insert deadline, e.g., one week / by March 1]. Please let me know if you need any additional information such as my CV or specific achievements to include.

Thank you again for your support — I truly appreciate your time and encouragemen
        T["👨‍🏫 Teacher (role=admin)"]
        H["🛡️ Head Admin (role=head)"]
    end
    S -->|"login via index.php"| A["account.php<br>Student Dashboard"]
    T -->|"login via admin_login.php"| D["dash.php<br>Teacher Dashboard"]
    H -->|"login via admin_login.php"| HD["headdash.php<br>Admin Dashboard"]
```

| Role       | DB Table | Login Handler       | Dashboard      |
| ---------- | -------- | ------------------- | -------------- |
| Student    | `user`   | `includes/login.php`  | `account.php`  |
| Teacher    | `admin` (role=`admin`) | `includes/admin.php`  | `dash.php`     |
| Head Admin | `admin` (role=`head`)  | `includes/head.php`   | `headdash.php` |

---

## 4. Use Case Diagram

```mermaid
graph LR
    subgraph Student
        A1["Register / Sign Up"]
        A2["Sign In"]
        A3["Browse Available Exams"]
        A4["View Exam Description"]
        A5["Enter Access Code"]
        A6["Take Exam (MCQ / Short / Match / Code)"]
        A7["View Results"]
        A8["View Exam History"]
        A9["Submit Feedback"]
        A10["Logout"]
    end

    subgraph Teacher
        B1["Sign In"]
        B2["View My Exams"]
        B3["Create New Exam"]
        B4["Add Questions (MCQ / Short / Match)"]
        B5["Edit Exam Details & Questions"]
        B6["Toggle Exam Status (Active/Disabled)"]
        B7["Remove Exam"]
        B8["View Student Scores"]
        B9["View Rankings"]
        B10["Manage Question"]
        B11["Logout"]
    end

    subgraph Head Admin
        C1["Sign In"]
        C2["Dashboard Statistics"]
        C3["Manage All Users (View / Edit / Delete)"]
        C4["Register New Users"]
        C5["Manage Teachers (Add / Delete)"]
        C6["Manage All Exams"]
        C7["Extend Exam Time"]
        C8["Reset User Exam Attempts"]
        C9["View / Delete Feedback"]
        C10["Manage Departments"]
        C11["Toggle Exam Release Status"]
        C12["Logout"]
    end
```

---

## 5. Database Schema (ER Diagram)

```mermaid
erDiagram
    USER {
        varchar name PK
        varchar gender
        varchar college
        varchar email PK
        bigint mob
        varchar password
        varchar year
    }

    ADMIN {
        varchar email PK
        varchar password
        varchar role "admin | head"
    }

    DEPARTMENTS {
        int dept_id PK
        varchar dept_name UK
        text year_labels
    }

    QUIZ {
        text eid PK
        varchar title
        int sahi "marks per correct"
        int wrong "marks per wrong"
        int total "total questions"
        bigint time "minutes"
        text intro
        varchar tag
        timestamp date
        varchar email FK "creator"
        varchar access_code
        varchar target_dept
        varchar target_year
        varchar status "active | disabled"
    }

    QUESTIONS {
        text eid FK
        text qid PK
        text qns "question text"
        int choice
        int sn "serial number"
        varchar question_type "mcq | short | code | match"
    }

    OPTIONS {
        varchar qid FK
        varchar option "option text or left-pair"
        text optionid "option ID or right-pair"
    }

    ANSWER {
        text qid FK
        text ansid "correct option ID or text"
    }

    HISTORY {
        varchar email FK
        text eid FK
        int score
        int level
        int sahi "correct count"
        int wrong_count "wrong count"
        timestamp date
    }

    RANK {
        varchar email FK
        int score "cumulative"
        timestamp time
    }

    FEEDBACK {
        text id PK
        varchar name
        varchar email
        varchar subject
        varchar feedback
        date date
        varchar time
    }

    USER ||--o{ HISTORY : "takes exams"
    USER ||--o| RANK : "has rank"
    QUIZ ||--o{ QUESTIONS : "contains"
    QUESTIONS ||--o{ OPTIONS : "has options"
    QUESTIONS ||--|| ANSWER : "has correct answer"
    QUIZ ||--o{ HISTORY : "records attempts"
    ADMIN ||--o{ QUIZ : "creates"
    USER ||--o{ FEEDBACK : "submits"
```

---

## 6. File Structure Overview

```
Online-exam-system/
├── index.php              # Student login & registration page
├── admin_login.php        # Admin (Head) & Teacher login page
├── account.php            # Student dashboard (exams, taking, results, history)
├── dash.php               # Teacher dashboard (CRUD exams, scores, rankings)
├── headdash.php           # Head Admin dashboard (users, teachers, depts, exams)
├── update.php             # Central action handler (all POST/GET operations)
├── logout.php             # Session destroy & redirect
│
├── config/
│   └── dbConnection.php   # MySQL connection ($con)
│
├── includes/
│   ├── login.php          # Student login logic
│   ├── sign.php           # Student registration logic
│   ├── admin.php          # Teacher login logic
│   ├── head.php           # Head Admin login logic
│   ├── signadmin.php      # Teacher registration logic (admin use)
│   └── feed.php           # Feedback submission handler
│
├── database/
│   ├── project1.sql       # Full database schema + seed data
│   ├── patch_schema.php   # Schema migration v1
│   ├── patch_schema_v2.php
│   ├── patch_schema_v3.php
│   └── patch_schema_v4.php
│
├── css/
│   └── theme.css          # Global CSS variables & theming (dark/light mode)
│
├── js/
│   └── theme.js           # Theme toggle JavaScript
│
├── assets/                # Images (logo, etc.)
└── fonts/                 # Custom fonts
```

---

## 7. Navigation Flow Summary

```mermaid
graph TD
    Start["Browser<br>/index.php"] --> StudentLogin["Student Login Form"]
    Start --> StudentSignup["Student Signup Form"]
    Start --> AdminLink["Admin & Teacher Portal Link"]

    StudentLogin -->|"includes/login.php"| AcctDash["account.php<br>Student Dashboard"]
    StudentSignup -->|"includes/sign.php"| AcctDash

    AdminLink --> AdminLoginPage["admin_login.php"]
    AdminLoginPage -->|"Head Admin tab"| HeadLogin["includes/head.php"]
    AdminLoginPage -->|"Teacher tab"| TeacherLogin["includes/admin.php"]

    HeadLogin --> HeadDash["headdash.php<br>Admin Dashboard"]
    TeacherLogin --> TeacherDash["dash.php<br>Teacher Dashboard"]

    AcctDash -->|"Take Exam"| ExamUI["account.php?q=exam<br>Full Exam UI"]
    ExamUI -->|"Submit"| UpdatePHP["update.php?q=submitexam"]
    UpdatePHP --> ResultPage["account.php?q=result"]

    TeacherDash -->|"Create Exam"| CreateExam["dash.php?q=4"]
    CreateExam -->|"Submit"| UpdatePHP2["update.php?q=addquiz"]
    UpdatePHP2 --> AddQuestions["dash.php?q=4&step=2"]
```
