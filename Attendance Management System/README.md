# 🎓 AttendancePro — Gamified Student Attendance Management System

**AttendancePro** is a full-stack, role-based PHP & MySQL web application designed to manage student attendance, course allocations, and academic rosters. Featuring a vibrant, Duolingo-inspired UI with interactive flashcard roll calls and real-time statistics, it streamlines class management for administrators, teachers, and students.

---

## ✨ Key Features

### 👑 1. Main Administrator Hub
* **User Provisioning:** Create and manage accounts for Main Admins, Faculty Guides, and Active Students.
* **Course Registration:** Deploy new course modules with unique alpha-numeric course codes.
* **Instructor Allocation:** Map assigned teachers to specific courses, terms/semesters, and sessions.

### 👨‍🏫 2. Faculty / Teacher Portal
* **Flashcard-Style Roll Call:** Interactive student card interface with an animated progress gauge.
* **Keyboard Shortcuts:** Fast attendance marking (`1` for **Present**, `2` for **Absent**) with background AJAX submissions.
* **Audit & Overrides:** Filter historical log sheets by date and execute inline status overrides.

### 🎓 3. Student Portal
* **Self-Registration:** New students can register their profiles with roll numbers, departments, semesters, and sessions.
* **Attendance Ledger:** View detailed attendance percentages across all registered courses.
* **Dynamic Color Indicators:** Visual status badges based on attendance percentage thresholds (e.g., green for ≥85%, blue for ≥75%, pink for <75%).

---

## 🛠️ Tech Stack

* **Backend:** PHP 8.2+ with MySQLi
* **Database:** MariaDB / MySQL
* **Frontend:** HTML5, Tailwind CSS (via CDN), Font Awesome 6, Vanilla JavaScript (AJAX / Event Listeners)
* **Design Language:** Duolingo-styled UI with 3D button effects, custom fonts (`Fredoka` & `Nunito Sans`), and responsive components

---

## 🗄️ Database Architecture

The system uses a relational database schema consisting of the following key tables:

* `users`: Central table for managing authentication tokens and access roles (`main_admin`, `teacher`, `student`).
* `students`: Profile metadata linked to users (roll number, department, semester, session, phone).
* `courses`: Course module directory storing course names and unique codes.
* `teacher_assignments`: Intersect mapping connecting instructors to courses, semesters, and sessions.
* `daily_attendance`: Log table recording individual student attendance entries per course and date.

---

## 🚀 Getting Started

### Prerequisites
* A local web server stack like **XAMPP**, **WAMP**, or **MAMP** (PHP 8.2+ and MariaDB/MySQL).

### Installation Steps

1. **Clone the repository:**
   ```bash
   git clone [https://github.com/imsandip953/Project-Work-1.git](https://github.com/imsandip953/Project-Work-1.git)
   cd Project-Work-1
