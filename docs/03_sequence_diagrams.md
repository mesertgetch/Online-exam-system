# ECUSTA — Sequence Diagrams & Interaction Flows

This document contains **sequence diagrams** for all major user interactions in the system.

---

## 1. Student Registration Flow

```mermaid
sequenceDiagram
    actor S as Student
    participant I as index.php
    participant JS as JavaScript (Validation)
    participant SN as includes/sign.php
    participant DB as MySQL Database
    participant A as account.php

    S->>I: Opens index.php
    S->>I: Clicks "Sign Up" tab
    S->>I: Fills form (name, gender, college, year, email, mobile, password)
    S->>JS: Clicks "Create Account"
    JS->>JS: validateSignUp()<br>Check name, email format, password 5-25 chars, passwords match
    alt Validation fails
        JS-->>I: showAlert("Error message", "error")
    else Validation passes
        JS->>SN: POST form data to includes/sign.php
        SN->>SN: ucwords(name), md5(password)
        SN->>DB: INSERT INTO user VALUES(...)
        alt Email already exists
            DB-->>SN: Error (duplicate PK)
            SN-->>I: Redirect: index.php?q7=Email Already Registered!!!
        else Success
            DB-->>SN: OK
            SN->>SN: session_start()<br>$_SESSION['email'] = email<br>$_SESSION['name'] = name
            SN-->>A: Redirect: account.php?q=1
        end
    end
```

---

## 2. Student Login Flow

```mermaid
sequenceDiagram
    actor S as Student
    participant I as index.php
    participant L as includes/login.php
    participant DB as MySQL Database
    participant A as account.php

    S->>I: Opens index.php
    S->>I: Enters email & password in "Sign In" tab
    S->>L: POST to includes/login.php?q=index.php
    L->>L: md5(password)
    L->>DB: SELECT name FROM user<br>WHERE email='...' AND password='...'
    alt Match found (count == 1)
        DB-->>L: Returns name
        L->>L: $_SESSION['name'] = name<br>$_SESSION['email'] = email
        L-->>A: Redirect: account.php?q=1
    else No match
        DB-->>L: 0 rows
        L-->>I: Redirect: index.php?w=Wrong Username or Password
        I->>I: showAlert() displays toast
    end
```

---

## 3. Admin / Teacher Login Flow

```mermaid
sequenceDiagram
    actor U as Admin or Teacher
    participant AL as admin_login.php
    participant H as includes/head.php
    participant AD as includes/admin.php
    participant DB as MySQL Database
    participant HD as headdash.php
    participant D as dash.php

    U->>AL: Opens admin_login.php
    alt Admin tab selected
        U->>AL: Enters email & password
        AL->>H: POST to includes/head.php
        H->>DB: SELECT email FROM admin<br>WHERE email='...' AND password='...'<br>AND role='head'
        alt Match found
            DB-->>H: 1 row
            H->>H: $_SESSION['name']='Admin'<br>$_SESSION['key']='prasanth123'<br>$_SESSION['email']=email
            H-->>HD: Redirect: headdash.php?q=0
        else No match
            H-->>AL: Redirect: admin_login.php?w=Access denied
        end
    else Teacher tab selected
        U->>AL: Enters email & password
        AL->>AD: POST to includes/admin.php
        AD->>DB: SELECT email FROM admin<br>WHERE email='...' AND password='...'<br>AND role='admin'
        alt Match found
            DB-->>AD: 1 row
            AD->>AD: $_SESSION['name']='Teacher'<br>$_SESSION['key']='prasanth123'<br>$_SESSION['email']=email
            AD-->>D: Redirect: dash.php?q=0
        else No match
            AD-->>AL: Redirect: admin_login.php?w=Access denied
        end
    end
```

---

## 4. Exam Creation Flow (Teacher / Admin)

```mermaid
sequenceDiagram
    actor T as Teacher
    participant D as dash.php
    participant U as update.php
    participant DB as MySQL Database

    T->>D: Navigates to "Add Exam" (dash.php?q=4)
    D->>D: Renders exam creation form
    T->>T: Fills: title, access code, total questions,<br>time, marks +/-, tag, target dept/year, description
    T->>U: POST to update.php?q=addquiz
    U->>U: ucwords(title), generate uniqid()
    U->>DB: INSERT INTO quiz VALUES(eid, title, sahi, wrong, total, time, desc, tag, NOW(), email, access_code, target_dept, target_year, 'active')
    DB-->>U: OK
    U-->>D: Redirect: dash.php?q=4&step=2&eid=...&n=total

    Note over D: Step 2 — Add Questions
    D->>D: Renders N question blocks with type selectors
    T->>T: For each question: select type,<br>enter text, options/answer

    T->>U: POST to update.php?q=addqns&n=N&eid=...

    loop For each question i = 1 to N
        U->>U: Generate qid = uniqid()
        U->>DB: INSERT INTO questions(eid, qid, qns, choice, sn, type)
        
        alt Type = MCQ or Code
            U->>DB: INSERT INTO options (4 rows: A, B, C, D)
            U->>DB: INSERT INTO answer (qid, correct optionid)
        else Type = Short Answer
            U->>DB: INSERT INTO answer (qid, answer_text)
        else Type = Match
            U->>DB: INSERT INTO options (4 pairs: left=option, right=optionid)
            U->>DB: INSERT INTO answer (qid, 'match_check_pairs')
        end
    end

    U-->>D: Redirect: dash.php?q=0
```

---

## 5. Student Takes Exam Flow (Full Lifecycle)

```mermaid
sequenceDiagram
    actor S as Student
    participant A as account.php
    participant U as update.php
    participant DB as MySQL Database
    participant JS as Browser JavaScript

    S->>A: Clicks "Start" on exam (account.php?q=1)
    A-->>A: Redirect: account.php?q=access&eid=...

    Note over A: Exam Instructions Page
    A->>DB: SELECT * FROM quiz WHERE eid=...
    DB-->>A: Exam details (title, time, marks, access_code)
    A->>A: Render instructions, rules, detail cards

    alt Exam has access code
        S->>S: Enters access code
        S->>U: POST to update.php?q=checkcode&eid=...
        U->>DB: SELECT access_code FROM quiz
        alt Code matches
            U-->>A: Redirect: account.php?q=exam&eid=...
        else Code wrong
            U-->>A: Redirect: account.php?q=access&eid=...&w=Wrong Access Code
        end
    else No access code
        S->>S: Checks confirmation box
        S->>A: Redirect: account.php?q=exam&eid=...
    end

    Note over A,JS: Full Exam UI Loads
    A->>DB: SELECT * FROM questions WHERE eid ORDER BY RAND()
    A->>DB: SELECT * FROM options WHERE qid (for each question)
    A->>A: JSON-encode questions & options into JavaScript
    A->>JS: init() called on window.load

    JS->>JS: Enter fullscreen mode
    JS->>JS: Start countdown timer
    JS->>JS: Render question grid & first question

    loop During Exam
        S->>JS: Answers questions (click option / type / match)
        JS->>JS: Store in answers{} object
        JS->>JS: Update grid status (answered/flagged)
        
        par Anti-Cheat Monitoring
            JS->>JS: visibilitychange → count violations
            JS->>JS: fullscreenchange → re-request fullscreen
            JS->>JS: keydown → block shortcuts
        and Time Sync
            JS->>U: GET update.php?q=check_time&eid=... (every 15s)
            U->>DB: SELECT time FROM quiz
            U-->>JS: Return current time value
            JS->>JS: If time extended → add diff to timeLeft
        end
    end

    alt Timer expires
        JS->>JS: submitExam() auto-called
    else Student clicks Submit
        JS->>JS: confirmSubmit() → submitExam()
    else 3 violations reached
        JS->>JS: submitExam() auto-called
    end

    JS->>JS: Exit fullscreen
    JS->>JS: Create hidden inputs for all answers
    JS->>U: POST form to update.php?q=submitexam&eid=...

    Note over U: Scoring Engine
    U->>DB: SELECT * FROM quiz (get marks per correct/wrong)
    U->>DB: SELECT * FROM questions WHERE eid
    
    loop For each question
        U->>DB: SELECT ansid FROM answer WHERE qid
        U->>U: Compare user answer vs correct answer
        alt MCQ/Code: user_ans == ansid
            U->>U: r++ (correct count)
        else Short: trim(lower(user)) == trim(lower(correct))
            U->>U: r++ (correct count)
        else Match: verify all 4 pairs in options table
            U->>U: r++ if all match
        else Wrong
            U->>U: w++ (wrong count)
        end
    end

    U->>U: score = (r × sahi) − (w × wrong)
    U->>DB: INSERT INTO history (email, eid, score, total, r, w, NOW())
    U->>DB: SELECT score FROM rank WHERE email
    alt First exam ever
        U->>DB: INSERT INTO rank (email, score, NOW())
    else Existing rank
        U->>DB: UPDATE rank SET score = score + new_score
    end

    U-->>A: Redirect: account.php?q=result&eid=...
    A->>DB: SELECT * FROM history (score, correct, wrong)
    A->>A: Render result cards
```

---

## 6. Admin Manages Users Flow

```mermaid
sequenceDiagram
    actor AD as Head Admin
    participant HD as headdash.php
    participant U as update.php
    participant DB as MySQL Database

    AD->>HD: Clicks "All Users" (headdash.php?q=1)
    HD->>DB: SELECT * FROM user
    DB-->>HD: User list
    HD->>HD: Render table with Edit & Delete buttons

    alt Delete User
        AD->>U: GET update.php?demail=user@email.com
        U->>U: Verify $_SESSION['key'] == 'prasanth123'
        U->>DB: DELETE FROM rank WHERE email=...
        U->>DB: DELETE FROM history WHERE email=...
        U->>DB: DELETE FROM user WHERE email=...
        U-->>HD: Redirect: headdash.php?q=1
    end

    alt Edit User
        AD->>HD: Clicks Edit → headdash.php?q=edit_user&email=...
        HD->>DB: SELECT * FROM user WHERE email=...
        HD->>HD: Render edit form (name, gender, college, year, mobile, password)
        AD->>U: POST to update.php?q=update_user
        U->>DB: UPDATE user SET name=..., gender=..., college=..., mob=...
        Note over U: Password only updated if provided (md5 hash)
        U-->>HD: Redirect: headdash.php?q=1
    end

    alt Register New User
        AD->>HD: Clicks "Register User" (headdash.php?q=4)
        AD->>U: POST to update.php?q=adduser
        U->>DB: INSERT INTO user VALUES(...)
        U-->>HD: Redirect: headdash.php?q=1
    end
```

---

## 7. Exam Management Flow (Admin)

```mermaid
sequenceDiagram
    actor AD as Head Admin
    participant HD as headdash.php
    participant U as update.php
    participant DB as MySQL Database

    AD->>HD: Clicks "Manage" on an exam
    HD->>DB: SELECT * FROM quiz WHERE eid=...
    HD->>HD: Render management page

    alt Toggle Exam Status
        AD->>U: GET update.php?q=toggle_exam_status&eid=...&status=disabled
        U->>DB: UPDATE quiz SET status='disabled' WHERE eid=...
        U-->>HD: Redirect back
    end

    alt Extend Time
        AD->>U: POST update.php?q=extendtime (eid, time_add)
        U->>DB: UPDATE quiz SET time = time + N WHERE eid=...
        U-->>HD: Redirect back
        Note over U: Active students detect this via polling
    end

    alt Reset User Attempt
        AD->>U: POST update.php?q=reset_user_exam (eid, email)
        U->>DB: SELECT score FROM history WHERE eid & email
        U->>DB: UPDATE rank SET score = score - old_score
        U->>DB: DELETE FROM history WHERE eid & email
        U-->>HD: Redirect back
    end

    alt Reset All Attempts
        AD->>U: POST update.php?q=reset_all_exam (eid)
        loop For each user in history
            U->>DB: UPDATE rank SET score = score - score
        end
        U->>DB: DELETE FROM history WHERE eid=...
        U-->>HD: Redirect back
    end

    alt Delete Question
        AD->>U: GET update.php?q=delete_question&qid=...&eid=...
        U->>DB: DELETE FROM options WHERE qid=...
        U->>DB: DELETE FROM answer WHERE qid=...
        U->>DB: DELETE FROM questions WHERE qid=...
        U->>DB: UPDATE quiz SET total = (new count)
        U-->>HD: Redirect back
    end
```

---

## 8. Department Management Flow

```mermaid
sequenceDiagram
    actor AD as Head Admin
    participant HD as headdash.php
    participant U as update.php
    participant DB as MySQL Database

    AD->>HD: Navigates to "Manage Departments"
    HD->>DB: SELECT * FROM departments ORDER BY dept_name
    HD->>HD: Render departments table

    alt Add Department
        AD->>HD: Fills dept name + year labels (e.g. "1st, 2nd, 3rd")
        AD->>U: POST to update.php?q=add_dept
        U->>DB: INSERT INTO departments (dept_name, year_labels)
        U-->>HD: Redirect: headdash.php?q=manage_dept
    end

    alt Delete Department
        AD->>U: GET update.php?q=delete_dept&id=...
        U->>DB: DELETE FROM departments WHERE dept_id=...
        U-->>HD: Redirect: headdash.php?q=manage_dept
    end
```

---

## 9. Feedback Flow

```mermaid
sequenceDiagram
    actor S as Student
    participant A as account.php
    participant F as includes/feed.php
    participant DB as MySQL Database
    actor AD as Head Admin
    participant HD as headdash.php
    participant U as update.php

    Note over S,F: Student Submits Feedback
    S->>A: Fills feedback form (name, email, subject, feedback)
    A->>F: POST to includes/feed.php
    F->>F: Generate uniqid(), get date/time
    F->>DB: INSERT INTO feedback VALUES(...)
    F-->>A: Redirect with success message

    Note over AD,U: Admin Reviews Feedback
    AD->>HD: Navigates to "Feedback" (headdash.php?q=3)
    HD->>DB: SELECT * FROM feedback ORDER BY date DESC
    HD->>HD: Render feedback table

    alt Read Feedback
        AD->>HD: Clicks "Read" → headdash.php?fid=...
        HD->>DB: SELECT * FROM feedback WHERE id=...
        HD->>HD: Display full feedback
    end

    alt Delete Feedback
        AD->>U: GET update.php?fdid=...
        U->>DB: DELETE FROM feedback WHERE id=...
        U-->>HD: Redirect: headdash.php?q=3
    end
```

---

## 10. Logout Flow

```mermaid
sequenceDiagram
    actor U as User (Any Role)
    participant L as logout.php
    participant Target as Target Page

    U->>L: GET logout.php?q=index.php (or admin_login.php)
    L->>L: session_start()
    L->>L: Check if $_SESSION['email'] is set
    L->>L: session_destroy()
    L->>L: Read ?q parameter for redirect target
    L-->>Target: Redirect: header("location: $ref")
```

---

## Activity Diagram — Complete Exam Lifecycle

```mermaid
graph TD
    A["Teacher creates exam<br>(dash.php → update.php?q=addquiz)"] --> B["Teacher adds questions<br>(update.php?q=addqns)"]
    B --> C{"Admin sets status?"}
    C -->|Active| D["Exam appears in student dashboard"]
    C -->|Disabled| E["Exam hidden from students"]
    
    D --> F["Student clicks 'Start'"]
    F --> G{"Access code required?"}
    G -->|Yes| H["Student enters code"]
    H --> I{"Code correct?"}
    I -->|No| H
    I -->|Yes| J["Exam UI loads"]
    G -->|No| J
    
    J --> K["Student answers questions"]
    K --> L{"How does exam end?"}
    L -->|Timer expires| M["Auto-submit"]
    L -->|3 violations| M
    L -->|Manual submit| M
    
    M --> N["update.php grades exam"]
    N --> O["Score saved to history"]
    O --> P["Rank updated"]
    P --> Q["Results shown"]
    
    style A fill:#4facfe,color:#000
    style M fill:#ff416c,color:#fff
    style Q fill:#38ef7d,color:#000
```
