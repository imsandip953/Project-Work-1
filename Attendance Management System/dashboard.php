<?php
require 'database.php';
if (!isset($_SESSION['user_id'])) { header("Location: index.php"); exit; }

$role = $_SESSION['role'];
$user_id = $_SESSION['user_id'];
$success_msg = "";

if ($role == 'main_admin' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    // 1. Existing System: Add New User Profile Account
    if (isset($_POST['add_user'])) {
        $hash = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $new_role = $_POST['new_role'];
        
        $conn->begin_transaction();
        try {
            $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $_POST['name'], $_POST['email'], $hash, $new_role);
            $stmt->execute();
            $new_user_id = $conn->insert_id;

            if ($new_role == 'student') {
                $stmt_stud = $conn->prepare("INSERT INTO students (user_id, roll_number, department, semester, session_name, phone_number) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt_stud->bind_param("isssss", $new_user_id, $_POST['roll_number'], $_POST['department'], $_POST['semester'], $_POST['session_name'], $_POST['phone_number']);
                $stmt_stud->execute();
            }
            $conn->commit();
            $success_msg = "Woohoo! Account profile initialized successfully! 🎉";
        } catch (Exception $e) {
            $conn->rollback();
            $success_msg = "Oh no! Error creating account: " . $e->getMessage();
        }
    }

    // 2. Existing System: Allocate Teacher Assignments
    if (isset($_POST['assign_course'])) {
        $stmt = $conn->prepare("INSERT INTO teacher_assignments (teacher_id, course_id, semester, session_name) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiss", $_POST['teacher_id'], $_POST['course_id'], $_POST['semester'], $_POST['session_name']);
        $stmt->execute();
        $success_msg = "Awesome! Course assigned to teacher successfully! ✨";
    }

    // 3. New Feature: Provision and Insert New Course Module
    if (isset($_POST['add_course'])) {
        $course_name = trim($_POST['course_name']);
        $course_code = strtoupper(trim($_POST['course_code']));

        $stmt = $conn->prepare("INSERT INTO courses (course_name, course_code) VALUES (?, ?)");
        $stmt->bind_param("ss", $course_name, $course_code);
        try {
            $stmt->execute();
            $success_msg = "Great job! New Course Module ($course_code) created successfully! 🚀";
        } catch (Exception $e) {
            $success_msg = "Oops! Error creating course: Unique Code Duplicate entry.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Hub — AttendancePro</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <!-- Duolingo Font Substitutes via Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@600;700&family=Nunito+Sans:wght@500;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'duo-green': '#58cc02',
                        'sky-blue': '#1cb0f6',
                        'duo-green-light': '#d7ffb8',
                        'sunshine-yellow': '#ffc700',
                        'grape-soda': '#a570ff',
                        'bubblegum-pink': '#cc348d',
                        'snow-white': '#ffffff',
                        'cloud-gray': '#e5e5e5',
                        'silver': '#afafaf',
                        'graphite': '#777777',
                        'charcoal': '#4b4b4b',
                        'almost-black': '#3c3c3c'
                    }
                }
            }
        }
    </script>
    <style>
        /* Duolingo Typography Rules */
        .font-feather {
            font-family: 'Fredoka', sans-serif;
            font-weight: 700;
            letter-spacing: -0.02em;
        }
        .font-din-round {
            font-family: 'Nunito Sans', sans-serif;
            letter-spacing: 0.053em;
        }
        
        /* Tactical Duolingo 3D Button Effects */
        .btn-3d-green {
            background-color: #58cc02;
            color: #ffffff;
            box-shadow: 0 4px 0 #3f8f01;
            transition: all 0.1s ease;
        }
        .btn-3d-green:active {
            transform: translateY(4px);
            box-shadow: 0 0px 0 #3f8f01;
        }
        
        .btn-3d-blue {
            background-color: #1cb0f6;
            color: #ffffff;
            box-shadow: 0 4px 0 #0c91d1;
            transition: all 0.1s ease;
        }
        .btn-3d-blue:active {
            transform: translateY(4px);
            box-shadow: 0 0px 0 #0c91d1;
        }

        .btn-3d-outline {
            background-color: #ffffff;
            color: #1cb0f6;
            border: 2px solid #e5e5e5;
            box-shadow: 0 4px 0 #e5e5e5;
            transition: all 0.1s ease;
        }
        .btn-3d-outline:active {
            transform: translateY(4px);
            box-shadow: 0 0px 0 #e5e5e5;
        }

        /* Standardized inputs */
        input, select, textarea {
            border: 2px solid #e5e5e5 !important;
            border-radius: 12px !important;
            font-family: 'Nunito Sans', sans-serif !important;
            letter-spacing: 0.053em !important;
            color: #3c3c3c !important;
        }
        input:focus, select:focus {
            border-color: #1cb0f6 !important;
            outline: none;
        }
    </style>
    <script>
        function checkNewUserRole(val) {
            document.getElementById('admin-student-fields').style.display = (val === 'student') ? 'grid' : 'none';
        }
    </script>
</head>
<body class="bg-snow-white min-h-screen text-almost-black antialiased font-din-round font-medium p-0 m-0">

    <!-- Sticky Main App Header Navigation -->
    <header class="w-full max-w-[1140px] mx-auto px-4 pt-6 sticky top-0 z-50 bg-snow-white/90 backdrop-blur-md">
        <div class="border-b-2 border-cloud-gray h-20 px-2 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-graduation-cap text-duo-green text-3xl"></i>
                <span class="font-feather text-2xl text-duo-green tracking-tight lowercase">attendance.pro</span>
                <span class="text-[11px] font-bold bg-sunshine-yellow text-almost-black px-2 py-0.5 rounded-full uppercase tracking-wider">PLUS</span>
            </div>
            
            <div class="flex items-center gap-4">
                <!-- Swapped out Streak for dynamic profile card chip -->
                <div class="hidden sm:flex items-center gap-2 border-2 border-cloud-gray px-4 py-2 rounded-xl bg-cloud-gray/10">
                    <div class="w-6 h-6 rounded-full bg-sky-blue flex items-center justify-center text-snow-white text-xs font-bold uppercase">
                        <?php echo strtoupper(substr($_SESSION['name'], 0, 1)); ?>
                    </div>
                    <span class="text-xs font-extrabold text-charcoal tracking-wide uppercase">
                        <?php echo htmlspecialchars($_SESSION['name']); ?>
                    </span>
                </div>
                
                <a href="logout.php" class="btn-3d-outline px-5 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider text-center">
                    Sign Out <i class="fa-solid fa-door-open ml-1"></i>
                </a>
            </div>
        </div>
    </header>

    <main class="w-full max-w-[1140px] mx-auto px-6 py-12">
        
        <!-- Animated System Motivational Log Banner -->
        <?php if(!empty($success_msg)): ?>
            <div class="mb-12 bg-duo-green-light border-2 border-duo-green rounded-2xl p-6 flex items-center gap-5 animate-bounce">
                <div class="w-12 h-12 rounded-full bg-duo-green flex items-center justify-center text-snow-white text-2xl shrink-0 shadow-md">
                    🦉
                </div>
                <div>
                    <h4 class="font-feather text-duo-green text-xl lowercase mb-0.5">Duo says:</h4>
                    <p class="text-almost-black text-sm font-bold tracking-wide"><?php echo $success_msg; ?></p>
                </div>
            </div>
        <?php endif; ?>

        <!-- ================================================================= -->
        <!-- SYSTEM COMPONENT: MAIN ADMINISTRATIVE SUITE                       -->
        <!-- ================================================================= -->
        <?php if($role == 'main_admin'): ?>
            <div class="mb-10 text-center lg:text-left">
                <h1 class="font-feather text-4xl lg:text-5xl text-duo-green lowercase mb-2">free. fun. effective. admin.</h1>
                <p class="text-charcoal font-bold text-base tracking-wider uppercase">Operational Control Center</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                
                <!-- Admin Module Box 01: Profile Management -->
                <div class="bg-snow-white border-2 border-cloud-gray rounded-2xl p-6 relative overflow-hidden">
                    <div class="flex justify-between items-center mb-6">
                        <span class="text-xs font-extrabold px-3 py-1 bg-cloud-gray rounded-full text-charcoal">
                            LEVEL 1
                        </span>
                        <span class="text-xs font-bold uppercase tracking-wider text-silver">PROVISIONS</span>
                    </div>
                    
                    <h3 class="font-feather text-2xl text-almost-black mb-6 lowercase">
                        Add New Accounts
                    </h3>
                    
                    <form method="POST" class="space-y-4">
                        <input type="hidden" name="add_user" value="1">
                        
                        <div class="space-y-1">
                            <label class="block text-xs font-bold text-charcoal uppercase tracking-wider">User Real Name</label>
                            <input type="text" name="name" placeholder="e.g. Professor Green" class="w-full bg-snow-white px-4 py-3 text-sm" required>
                        </div>
                        
                        <div class="space-y-1">
                            <label class="block text-xs font-bold text-charcoal uppercase tracking-wider">Email Track Address</label>
                            <input type="email" name="email" placeholder="e.g. duo@domain.com" class="w-full bg-snow-white px-4 py-3 text-sm" required>
                        </div>
                        
                        <div class="space-y-1">
                            <label class="block text-xs font-bold text-charcoal uppercase tracking-wider">Passphrase Lock</label>
                            <input type="password" name="password" placeholder="••••••••" class="w-full bg-snow-white px-4 py-3 text-sm" required>
                        </div>
                        
                        <div class="space-y-1">
                            <label class="block text-xs font-bold text-charcoal uppercase tracking-wider">Privilege Engine Tier</label>
                            <select name="new_role" onchange="checkNewUserRole(this.value)" class="w-full bg-snow-white px-4 py-3 text-sm font-bold cursor-pointer" required>
                                <option value="main_admin">System Main Admin</option>
                                <option value="teacher">Assigned Faculty Guide</option>
                                <option value="student">Active Student Learner</option>
                            </select>
                        </div>
                        
                        <!-- Collapsible Extension: Active Student Identity Payload -->
                        <div id="admin-student-fields" class="hidden grid-cols-2 gap-3 border-2 border-dashed border-cloud-gray p-4 rounded-xl bg-snow-white mt-2 animate-fade-in">
                            <div class="space-y-1">
                                <label class="block text-[10px] uppercase font-extrabold text-graphite">Roll Number</label>
                                <input type="text" name="roll_number" placeholder="1010" class="w-full px-3 py-2 text-xs">
                            </div>
                            <div class="space-y-1">
                                <label class="block text-[10px] uppercase font-extrabold text-graphite">Dept</label>
                                <input type="text" name="department" placeholder="CSE" class="w-full px-3 py-2 text-xs">
                            </div>
                            <div class="space-y-1">
                                <label class="block text-[10px] uppercase font-extrabold text-graphite">Semester</label>
                                <input type="text" name="semester" placeholder="4th" class="w-full px-3 py-2 text-xs">
                            </div>
                            <div class="space-y-1">
                                <label class="block text-[10px] uppercase font-extrabold text-graphite">Session</label>
                                <input type="text" name="session_name" placeholder="Fall 2026" class="w-full px-3 py-2 text-xs">
                            </div>
                            <div class="col-span-2 space-y-1">
                                <label class="block text-[10px] uppercase font-extrabold text-graphite">Phone Link Vector</label>
                                <input type="text" name="phone_number" placeholder="+880..." class="w-full px-3 py-2 text-xs">
                            </div>
                        </div>
                        
                        <div class="pt-4">
                            <button type="submit" class="w-full btn-3d-green text-sm font-bold uppercase tracking-wider py-3.5 rounded-xl">
                                Create Account Profile
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Admin Module Box 02: Core Course Registration Matrix -->
                <div class="bg-snow-white border-2 border-cloud-gray rounded-2xl p-6 relative overflow-hidden">
                    <div class="flex justify-between items-center mb-6">
                        <span class="text-xs font-extrabold px-3 py-1 bg-cloud-gray rounded-full text-charcoal">
                            LEVEL 2
                        </span>
                        <span class="text-xs font-bold uppercase tracking-wider text-silver">COURSES</span>
                    </div>
                    
                    <h3 class="font-feather text-2xl text-almost-black mb-6 lowercase">
                        Establish Courses
                    </h3>
                    
                    <form method="POST" class="space-y-4">
                        <input type="hidden" name="add_course" value="1">
                        
                        <div class="space-y-1">
                            <label class="block text-xs font-bold text-charcoal uppercase tracking-wider">Course Module Name</label>
                            <input type="text" name="course_name" placeholder="e.g. Introductory French Syntax" class="w-full bg-snow-white px-4 py-3 text-sm" required>
                        </div>
                        
                        <div class="space-y-1">
                            <label class="block text-xs font-bold text-charcoal uppercase tracking-wider">Alpha Index Identity Code</label>
                            <input type="text" name="course_code" placeholder="e.g. LAN-102" class="w-full bg-snow-white px-4 py-3 text-sm" required>
                        </div>
                        
                        <div class="pt-24">
                            <button type="submit" class="w-full btn-3d-blue text-sm font-bold uppercase tracking-wider py-3.5 rounded-xl">
                                Deploy Course Target
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Admin Module Box 03: Deployment & Mapping Grid Matrix -->
                <div class="bg-snow-white border-2 border-cloud-gray rounded-2xl p-6 relative overflow-hidden">
                    <div class="flex justify-between items-center mb-6">
                        <span class="text-xs font-extrabold px-3 py-1 bg-cloud-gray rounded-full text-charcoal">
                            LEVEL 3
                        </span>
                        <span class="text-xs font-bold uppercase tracking-wider text-silver">ROSTERS</span>
                    </div>
                    
                    <h3 class="font-feather text-2xl text-almost-black mb-6 lowercase">
                        Assign Instructors
                    </h3>
                    
                    <form method="POST" class="space-y-4">
                        <input type="hidden" name="assign_course" value="1">
                        
                        <div class="space-y-1">
                            <label class="block text-xs font-bold text-charcoal uppercase tracking-wider">Select Faculty Anchor</label>
                            <select name="teacher_id" class="w-full bg-snow-white px-4 py-3 text-sm font-bold cursor-pointer" required>
                                <?php 
                                $teachers = $conn->query("SELECT id, name FROM users WHERE role='teacher'");
                                while($t = $teachers->fetch_assoc()) echo "<option value='{$t['id']}'>{$t['name']}</option>";
                                ?>
                            </select>
                        </div>
                        
                        <div class="space-y-1">
                            <label class="block text-xs font-bold text-charcoal uppercase tracking-wider">Target Course Syllabus</label>
                            <select name="course_id" class="w-full bg-snow-white px-4 py-3 text-sm font-bold cursor-pointer" required>
                                <?php 
                                $courses = $conn->query("SELECT id, course_name, course_code FROM courses ORDER BY id DESC");
                                while($c = $courses->fetch_assoc()) echo "<option value='{$c['id']}'>[{$c['course_code']}] {$c['course_name']}</option>";
                                ?>
                            </select>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-1">
                                <label class="block text-xs font-bold text-charcoal uppercase tracking-wider">Semester Term</label>
                                <input type="text" name="semester" placeholder="4th" class="w-full bg-snow-white px-4 py-3 text-sm" required>
                            </div>
                            <div class="space-y-1">
                                <label class="block text-xs font-bold text-charcoal uppercase tracking-wider">Session Frame</label>
                                <input type="text" name="session_name" placeholder="Fall 2026" class="w-full bg-snow-white px-4 py-3 text-sm" required>
                            </div>
                        </div>
                        
                        <div class="pt-6">
                            <button type="submit" class="w-full btn-3d-green text-sm font-bold uppercase tracking-wider py-3.5 rounded-xl">
                                Intersect Allocation Map
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        <!-- ================================================================= -->
        <!-- SYSTEM COMPONENT: TEACHER MANAGEMENT ARCHITECTURE                -->
        <!-- ================================================================= -->
        <?php elseif($role == 'teacher'): ?>
            <div class="mb-12 text-center lg:text-left">
                <h1 class="font-feather text-4xl lg:text-5xl text-duo-green lowercase mb-2">assigned lecture hubs.</h1>
                <p class="text-charcoal font-bold text-base tracking-wider uppercase">Instructional Control Node</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php 
                $stmt = $conn->prepare("SELECT ta.*, c.course_name, c.course_code FROM teacher_assignments ta JOIN courses c ON ta.course_id = c.id WHERE ta.teacher_id = ?");
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                $allocations = $stmt->get_result();

                if($allocations->num_rows === 0):
                ?>
                    <div class="col-span-full text-center py-20 bg-snow-white border-2 border-cloud-gray border-dashed rounded-2xl">
                        <span class="text-5xl block mb-4">🦉</span>
                        <h3 class="font-feather text-2xl text-charcoal lowercase mb-2">No classrooms allocated yet!</h3>
                        <p class="text-sm text-silver font-bold uppercase tracking-wide">Contact your master administration node</p>
                    </div>
                <?php 
                endif;

                while($alloc = $allocations->fetch_assoc()):
                ?>
                    <div class="bg-snow-white border-2 border-cloud-gray rounded-2xl p-6 flex flex-col justify-between transition hover:-translate-y-1 duration-200">
                        <div>
                            <div class="flex justify-between items-center mb-4">
                                <span class="text-xs font-extrabold px-3 py-1 bg-duo-green-light text-duo-green rounded-full">
                                    <?php echo htmlspecialchars($alloc['course_code']); ?>
                                </span>
                                <span class="text-[11px] font-bold uppercase tracking-wider text-silver">ACTIVE TRACK</span>
                            </div>
                            <h3 class="font-feather text-2xl text-almost-black mb-4 leading-tight lowercase">
                                <?php echo htmlspecialchars($alloc['course_name']); ?>
                            </h3>
                            <div class="flex gap-4 border-t-2 border-cloud-gray/40 pt-3 text-xs font-bold text-charcoal">
                                <div>SEMESTER: <span class="text-sky-blue uppercase"><?php echo htmlspecialchars($alloc['semester']); ?></span></div>
                                <div>SESSION: <span class="text-grape-soda uppercase"><?php echo htmlspecialchars($alloc['session_name']); ?></span></div>
                            </div>
                        </div>
                        <div class="space-y-3 pt-8">
                            <a href="manage_attendance.php?course_id=<?php echo $alloc['course_id']; ?>&semester=<?php echo urlencode($alloc['semester']); ?>&session_name=<?php echo urlencode($alloc['session_name']); ?>" 
                               class="w-full btn-3d-green text-center py-3 rounded-xl block text-xs font-bold uppercase tracking-wider">
                                Track Session Roll Call
                            </a>
                            <a href="view_attendance.php?course_id=<?php echo $alloc['course_id']; ?>" 
                               class="w-full btn-3d-outline text-center py-2.5 rounded-xl block text-xs font-bold uppercase tracking-wider">
                                Audit Data Ledger Index
                            </a>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>

        <!-- ================================================================= -->
        <!-- SYSTEM COMPONENT: ACTIVE STUDENT LEDGER ANALYSIS                 -->
        <!-- ================================================================= -->
        <?php elseif($role == 'student'): 
            $profile_stmt = $conn->prepare("SELECT * FROM students WHERE user_id = ?");
            $profile_stmt->bind_param("i", $user_id);
            $profile_stmt->execute();
            $profile = $profile_stmt->get_result()->fetch_assoc();
            
            $filter_course_id = isset($_POST['selected_course']) ? $_POST['selected_course'] : 'all';
        ?>
            <!-- Large Brand Feature Typography -->
            <div class="mb-12 text-center lg:text-left">
                <h1 class="font-feather text-4xl lg:text-6xl text-duo-green tracking-tight lowercase leading-tight">
                    you're doing great! track your academic <span class="text-sky-blue">attendance logs</span>.
                </h1>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                
                <!-- Student Persona Stats Panel Cards -->
                <div class="space-y-6 lg:sticky lg:top-28">
                    <div class="bg-snow-white border-2 border-cloud-gray rounded-2xl p-6">
                        <div class="border-b-2 border-cloud-gray pb-4 mb-4 text-center lg:text-left">
                            <div class="text-3xl mb-2">⚡</div>
                            <div class="text-xs uppercase tracking-widest text-silver font-extrabold mb-1">Student Learner</div>
                            <h3 class="font-feather text-2xl text-almost-black lowercase"><?php echo htmlspecialchars($_SESSION['name']); ?></h3>
                            <span class="inline-block text-xs bg-sky-blue/10 text-sky-blue font-bold px-3 py-1 rounded-full mt-2 font-mono">
                                ID: <?php echo htmlspecialchars($profile['roll_number'] ?? 'UNASSIGNED'); ?>
                            </span>
                        </div>
                        
                        <div class="space-y-4 text-sm font-bold text-charcoal">
                            <div class="flex justify-between items-center border-b border-cloud-gray/40 pb-2">
                                <span>DEPARTMENT</span>
                                <span class="text-almost-black uppercase"><?php echo htmlspecialchars($profile['department'] ?? 'N/A'); ?></span>
                            </div>
                            <div class="flex justify-between items-center border-b border-cloud-gray/40 pb-2">
                                <span>CURRENT TERM</span>
                                <span class="text-almost-black uppercase"><?php echo htmlspecialchars($profile['semester'] ?? 'N/A'); ?></span>
                            </div>
                            <div class="flex justify-between items-center border-b border-cloud-gray/40 pb-2">
                                <span>SESSION BLOCK</span>
                                <span class="text-almost-black uppercase"><?php echo htmlspecialchars($profile['session_name'] ?? 'N/A'); ?></span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span>CONTACT MOBILE</span>
                                <span class="text-almost-black uppercase"><?php echo htmlspecialchars($profile['phone_number'] ?? 'N/A'); ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Data Surface: Course Filters and Progress Grid -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-snow-white border-2 border-cloud-gray rounded-2xl p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="text-sm font-extrabold text-almost-black uppercase tracking-wider flex items-center gap-2">
                            <i class="fa-solid fa-filter text-sky-blue"></i>
                            <span>Isolated Curriculum Ledgers</span>
                        </div>
                        
                        <form method="POST" id="filterForm" class="m-0">
                            <div class="relative">
                                <select name="selected_course" onchange="document.getElementById('filterForm').submit();" class="text-xs font-bold uppercase tracking-wider bg-snow-white px-4 py-2.5 pr-10 cursor-pointer appearance-none w-full sm:w-auto">
                                    <option value="all" <?php if($filter_course_id == 'all') echo 'selected'; ?>>Show All Registered Courses</option>
                                    <?php
                                    $c_list_stmt = $conn->prepare("SELECT id, course_name FROM courses WHERE id IN (SELECT DISTINCT course_id FROM teacher_assignments WHERE semester = ? AND session_name = ?)");
                                    $c_list_stmt->bind_param("ss", $profile['semester'], $profile['session_name']);
                                    $c_list_stmt->execute();
                                    $c_dropdown = $c_list_stmt->get_result();
                                    while($drop = $c_dropdown->fetch_assoc()) {
                                        $sel = ($filter_course_id == $drop['id']) ? 'selected' : '';
                                        echo "<option value='{$drop['id']}' $sel>".htmlspecialchars($drop['course_name'])."</option>";
                                    }
                                    ?>
                                </select>
                                <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-charcoal text-[11px]">
                                    <i class="fa-solid fa-chevron-down"></i>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Clean Gamified Ledger Analytics Table Wrapper -->
                    <div class="bg-snow-white border-2 border-cloud-gray rounded-2xl overflow-hidden shadow-sm">
                        <table class="w-full border-collapse text-left m-0">
                            <thead>
                                <tr class="bg-cloud-gray/30 border-b-2 border-cloud-gray text-charcoal font-extrabold text-xs uppercase tracking-wider">
                                    <th class="px-6 py-4">Course Module Description</th>
                                    <th class="px-6 py-4 text-center">Periods Attended</th>
                                    <th class="px-6 py-4 text-right">Status Metrics</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y-2 divide-cloud-gray/40 text-sm text-almost-black font-bold">
                                <?php 
                                $query_string = "
                                    SELECT c.course_name, 
                                           COUNT(CASE WHEN da.status = 'Present' THEN 1 END) as attended,
                                           COUNT(da.id) as total
                                    FROM courses c
                                    LEFT JOIN daily_attendance da ON c.id = da.course_id AND da.student_id = ?
                                    WHERE c.id IN (SELECT DISTINCT course_id FROM teacher_assignments WHERE semester = ? AND session_name = ?)";
                                
                                if ($filter_course_id !== 'all') {
                                    $query_string .= " AND c.id = " . intval($filter_course_id);
                                }
                                $query_string .= " GROUP BY c.id";

                                $metric_stmt = $conn->prepare($query_string);
                                $metric_stmt->bind_param("iss", $user_id, $profile['semester'], $profile['session_name']);
                                $metric_stmt->execute();
                                $metrics = $metric_stmt->get_result();

                                if($metrics->num_rows === 0) {
                                    echo "<tr><td colspan='3' class='px-6 py-12 text-center text-xs font-bold text-silver uppercase tracking-wider'>No registered metrics or active structures discovered.</td></tr>";
                                }

                                while($m = $metrics->fetch_assoc()):
                                    $pct = ($m['total'] > 0) ? round(($m['attended'] / $m['total']) * 100) : 0;
                                    
                                    // Adaptive color status based on attendance criteria 
                                    if ($pct >= 85) {
                                        $badge_class = 'bg-duo-green-light text-duo-green border-duo-green';
                                    } elseif ($pct >= 75) {
                                        $badge_class = 'bg-sky-blue/10 text-sky-blue border-sky-blue';
                                    } else {
                                        $badge_class = 'bg-bubblegum-pink/10 text-bubblegum-pink border-bubblegum-pink';
                                    }
                                ?>
                                    <tr class="hover:bg-cloud-gray/10 transition duration-150">
                                        <td class="px-6 py-5 font-feather text-lg text-almost-black lowercase"><?php echo htmlspecialchars($m['course_name']); ?></td>
                                        <td class="px-6 py-5 text-center font-mono text-charcoal text-xs">
                                            🌟 <?php echo $m['attended']; ?> / <?php echo $m['total']; ?> Checkpoints
                                        </td>
                                        <td class="px-6 py-5 text-right">
                                            <span class="inline-block px-3 py-1.5 rounded-xl text-xs font-extrabold border-2 <?php echo $badge_class; ?>">
                                                <?php echo $pct; ?>% Score
                                            </span>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>