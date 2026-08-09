<?php
require 'database.php'; //[cite: 3]
$error = ""; //[cite: 3]
$success = ""; //[cite: 3]

// Support checking both POST and GET arrays so the role doesn't reset on refresh/redirect
$selected_role = isset($_POST['role']) ? $_POST['role'] : (isset($_GET['role']) ? $_GET['role'] : 'main_admin'); //[cite: 3]
$typed_email = isset($_POST['email']) ? trim($_POST['email']) : ''; //[cite: 3]
$typed_semester = isset($_POST['semester']) ? trim($_POST['semester']) : ''; //[cite: 3]
$typed_session = isset($_POST['session_name']) ? trim($_POST['session_name']) : ''; //[cite: 3]

if ($_SERVER['REQUEST_METHOD'] == 'POST') { //[cite: 3]
    
    // ==========================================
    // ACTION 1: HANDLE STUDENT SELF-REGISTRATION
    // ==========================================
    if (isset($_POST['action_type']) && $_POST['action_type'] == 'register') { //[cite: 3]
        $name = trim($_POST['reg_name']); //[cite: 3]
        $email = trim($_POST['reg_email']); //[cite: 3]
        $password = $_POST['reg_password']; //[cite: 3]
        $roll = trim($_POST['reg_roll']); //[cite: 3]
        $dept = trim($_POST['reg_dept']); //[cite: 3]
        $sem = trim($_POST['reg_semester']); //[cite: 3]
        $sess = trim($_POST['reg_session']); //[cite: 3]
        $phone = trim($_POST['reg_phone']); //[cite: 3]

        // Check if email already exists
        $check = $conn->prepare("SELECT id FROM users WHERE email = ?"); //[cite: 3]
        $check->bind_param("s", $email); //[cite: 3]
        $check->execute(); //[cite: 3]
        if ($check->get_result()->num_rows > 0) { //[cite: 3]
            $error = "This email address is already registered to an account."; //[cite: 3]
        } else {
            $conn->begin_transaction(); //[cite: 3]
            try {
                // Insert into central users table with 'student' role
                $hash = password_hash($password, PASSWORD_BCRYPT); //[cite: 3]
                $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'student')"); //[cite: 3]
                $stmt->bind_param("sss", $name, $email, $hash); //[cite: 3]
                $stmt->execute(); //[cite: 3]
                $new_user_id = $conn->insert_id; //[cite: 3]

                // Insert student specific profile meta configurations
                $stmt_stud = $conn->prepare("INSERT INTO students (user_id, roll_number, department, semester, session_name, phone_number) VALUES (?, ?, ?, ?, ?, ?)"); //[cite: 3]
                $stmt_stud->bind_param("isssss", $new_user_id, $roll, $dept, $sem, $sess, $phone); //[cite: 3]
                $stmt_stud->execute(); //[cite: 3]

                $conn->commit(); //[cite: 3]
                $success = "Woohoo! Account registration completed successfully! 🎉"; //[cite: 3]
                
                header("Location: index.php?role=student"); //[cite: 3]
                exit; //[cite: 3]
            } catch (Exception $e) {
                $conn->rollback(); //[cite: 3]
                $error = "System registration error: " . $e->getMessage(); //[cite: 3]
            }
        }
    }
    
    // ==========================================
    // ACTION 2: HANDLE TRADITIONAL PORTAL LOGIN
    // ==========================================
    elseif (isset($_POST['action_type']) && $_POST['action_type'] == 'login') { //[cite: 3]
        $password = $_POST['password']; //[cite: 3]

        if ($selected_role == 'student') { //[cite: 3]
            $stmt = $conn->prepare("SELECT u.*, s.semester, s.session_name FROM users u JOIN students s ON u.id = s.user_id WHERE u.email = ? AND u.role = 'student'"); //[cite: 3]
            $stmt->bind_param("s", $typed_email); //[cite: 3]
        } else {
            $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND role = ?"); //[cite: 3]
            $stmt->bind_param("ss", $typed_email, $selected_role); //[cite: 3]
        }
        
        $stmt->execute(); //[cite: 3]
        $res = $stmt->get_result(); //[cite: 3]

        if ($user = $res->fetch_assoc()) { //[cite: 3]
            if (password_verify($password, $user['password']) || $password === $user['password']) { //[cite: 3]
                
                if ($selected_role == 'student') { //[cite: 3]
                    if (strtolower($user['semester']) !== strtolower($typed_semester) || strtolower($user['session_name']) !== strtolower($typed_session)) { //[cite: 3]
                        $error = "Academic profile constraints (Semester/Session) do not match database records."; //[cite: 3]
                    }
                }
                
                if (empty($error)) { //[cite: 3]
                    $_SESSION = array(); //[cite: 3]
                    $_SESSION['user_id'] = $user['id']; //[cite: 3]
                    $_SESSION['role'] = $user['role']; //[cite: 3]
                    $_SESSION['name'] = $user['name']; //[cite: 3]
                    header("Location: dashboard.php"); //[cite: 3]
                    exit; //[cite: 3]
                }
            } else {
                $error = "Incorrect secure password credentials."; //[cite: 3]
            }
        } else {
            $error = "No active account matches the selected role and email identity."; //[cite: 3]
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Gateway — AttendancePro</title>
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
        /* Duolingo Typography Mapping Rules */
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
        input, select {
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
        function toggleFormMode(mode) {
            if (mode === 'register') {
                document.getElementById('login-form-block').classList.add('hidden');
                document.getElementById('register-form-block').classList.remove('hidden');
                document.getElementById('portal-subtitle').innerText = "REGISTER NEW STUDENT PROFILE";
                document.getElementById('portal-title').innerText = "create account";
            } else {
                document.getElementById('register-form-block').classList.add('hidden');
                document.getElementById('login-form-block').classList.remove('hidden');
                document.getElementById('portal-subtitle').innerText = "PROVIDE VALID AUTHORIZATION TOKENS";
                document.getElementById('portal-title').innerText = "log into portal";
            }
        }
        
        function checkRole(val) {
            document.getElementById('student-box').style.display = (val === 'student') ? 'block' : 'none';
        }
        
        window.addEventListener('DOMContentLoaded', () => {
            const currentRole = "<?php echo htmlspecialchars($selected_role); ?>";
            checkRole(currentRole);
            
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('role') === 'student') {
                toggleFormMode('login');
            }
        });
    </script>
</head>
<body class="bg-snow-white min-h-screen font-din-round text-almost-black antialiased font-medium m-0 p-0">

    <!-- Global Layout System: Sticky Main App Header Navigation -->
    <header class="w-full max-w-[1140px] mx-auto px-4 pt-6 sticky top-0 z-50 bg-snow-white/90 backdrop-blur-md">
        <div class="border-b-2 border-cloud-gray h-20 px-2 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-graduation-cap text-duo-green text-3xl"></i>
                <span class="font-feather text-2xl text-duo-green tracking-tight lowercase">attendance.pro</span>
                <span class="text-[11px] font-bold bg-sunshine-yellow text-almost-black px-2 py-0.5 rounded-full uppercase tracking-wider">PLUS</span>
            </div>
            <div class="flex items-center gap-6">
                <span class="text-[11px] font-bold tracking-wider text-silver uppercase hidden md:inline">SYSTEM VERSION 2026.1</span>
            </div>
        </div>
    </header>

    <!-- Centered Airy Layout Structure -->
    <div class="w-full max-w-[1140px] mx-auto px-6 py-12 grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
        
        <!-- SIDE A: ON-BRAND MASCOT/HERO ILLUSTRATION ARTWORK CONTEXT -->
        <div class="lg:col-span-6 flex flex-col items-center lg:items-end text-center lg:text-right space-y-6">
            <div class="w-64 h-64 sm:w-80 sm:h-80 bg-duo-green-light/40 rounded-full flex items-center justify-center text-[110px] sm:text-[140px] shadow-sm select-none animate-bounce duration-1000">
                🦉
            </div>
            <div class="max-w-md">
                <p id="portal-subtitle" class="text-[11px] font-extrabold tracking-[0.2em] text-silver mb-2 uppercase">
                    PROVIDE VALID AUTHORIZATION TOKENS
                </p>
                <h1 id="portal-title" class="font-feather text-4xl sm:text-5xl lg:text-6xl text-duo-green lowercase leading-tight transition-all duration-500">
                    log into portal
                </h1>
            </div>
        </div>

        <!-- SIDE B: THE APP CONTROL CARD INTERFACE CORE -->
        <div class="lg:col-span-6 w-full max-w-md mx-auto">
            
            <!-- Universal State Messaging Banners -->
            <?php if(!empty($error)): ?>
                <div class="mb-6 bg-bubblegum-pink/10 border-2 border-bubblegum-pink rounded-2xl p-4 flex items-center gap-3">
                    <span class="text-xl">⚠️</span>
                    <p class="text-xs font-bold text-almost-black tracking-wide"><?php echo $error; ?></p>
                </div>
            <?php endif; ?>
            
            <?php if(!empty($success)): ?>
                <div class="mb-6 bg-duo-green-light border-2 border-duo-green rounded-2xl p-4 flex items-center gap-3">
                    <span class="text-xl">🎉</span>
                    <p class="text-xs font-bold text-almost-black tracking-wide"><?php echo $success; ?></p>
                </div>
            <?php endif; ?>

            <!-- INTERFACE PANEL CONTAINER A: TRADITIONAL LOGIN -->
            <div id="login-form-block" class="bg-snow-white border-2 border-cloud-gray rounded-3xl p-8 shadow-sm">
                <form method="POST" class="space-y-5">
                    <input type="hidden" name="action_type" value="login">

                    <div class="space-y-1">
                        <label class="block text-xs font-bold text-charcoal uppercase tracking-wider">Access Level Role</label>
                        <select name="role" onchange="checkRole(this.value)" class="w-full bg-snow-white px-4 py-3 text-sm font-bold cursor-pointer" required>
                            <option value="main_admin" <?php if($selected_role == 'main_admin') echo 'selected'; ?>>Main Administrator</option>
                            <option value="teacher" <?php if($selected_role == 'teacher') echo 'selected'; ?>>Faculty Teacher</option>
                            <option value="student" <?php if($selected_role == 'student') echo 'selected'; ?>>Student Profile Check-in</option>
                        </select>
                    </div>

                    <div class="space-y-1">
                        <label class="block text-xs font-bold text-charcoal uppercase tracking-wider">Registered Email Address</label>
                        <input type="email" name="email" value="<?php echo htmlspecialchars($typed_email); ?>" placeholder="e.g. duo@domain.com" class="w-full bg-snow-white px-4 py-3 text-sm" required>
                    </div>

                    <div class="space-y-1">
                        <label class="block text-xs font-bold text-charcoal uppercase tracking-wider">Security Key Password</label>
                        <input type="password" name="password" placeholder="••••••••" class="w-full bg-snow-white px-4 py-3 text-sm" required>
                    </div>

                    <!-- Slide down Student Context verification inputs -->
                    <div id="student-box" class="space-y-4 border-t-2 border-dashed border-cloud-gray pt-4 mt-2" style="display: <?php echo ($selected_role == 'student') ? 'block' : 'none'; ?>;">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-1">
                                <label class="block text-xs font-bold text-charcoal uppercase tracking-wider">Active Semester</label>
                                <input type="text" name="semester" value="<?php echo htmlspecialchars($typed_semester); ?>" placeholder="e.g. 1st" class="w-full bg-snow-white px-4 py-3 text-sm">
                            </div>
                            <div class="space-y-1">
                                <label class="block text-xs font-bold text-charcoal uppercase tracking-wider">Academic Session</label>
                                <input type="text" name="session_name" value="<?php echo htmlspecialchars($typed_session); ?>" placeholder="e.g. Fall 2026" class="w-full bg-snow-white px-4 py-3 text-sm">
                            </div>
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full btn-3d-green text-sm font-bold uppercase tracking-wider py-4 rounded-xl">
                            Authorize Session
                        </button>
                    </div>
                </form>
                
                <div class="mt-6 pt-6 border-t-2 border-cloud-gray text-center text-xs text-graphite font-bold uppercase tracking-wider">
                    New Student? <button type="button" onclick="toggleFormMode('register')" class="text-sky-blue font-extrabold hover:underline ml-1">Create Student Account</button>
                </div>
            </div>

            <!-- INTERFACE PANEL CONTAINER B: STUDENT SELF-REGISTRATION -->
            <div id="register-form-block" class="hidden bg-snow-white border-2 border-cloud-gray rounded-3xl p-8 shadow-sm">
                <form method="POST" class="space-y-4">
                    <input type="hidden" name="action_type" value="register">

                    <div class="space-y-1">
                        <label class="block text-xs font-bold text-charcoal uppercase tracking-wider">Full Identity Name</label>
                        <input type="text" name="reg_name" placeholder="e.g. Active Learner" class="w-full bg-snow-white px-4 py-2.5 text-sm" required>
                    </div>

                    <div class="space-y-1">
                        <label class="block text-xs font-bold text-charcoal uppercase tracking-wider">Preferred Email Address</label>
                        <input type="email" name="reg_email" placeholder="e.g. student@domain.com" class="w-full bg-snow-white px-4 py-2.5 text-sm" required>
                    </div>

                    <div class="space-y-1">
                        <label class="block text-xs font-bold text-charcoal uppercase tracking-wider">Create Security Password</label>
                        <input type="password" name="reg_password" placeholder="••••••••" class="w-full bg-snow-white px-4 py-2.5 text-sm" required>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-3 border-t-2 border-dashed border-cloud-gray pt-4">
                        <div class="space-y-1">
                            <label class="block text-xs font-bold text-charcoal uppercase tracking-wider">Roll Number</label>
                            <input type="text" name="reg_roll" placeholder="e.g. 101" class="w-full bg-snow-white px-4 py-2.5 text-sm" required>
                        </div>
                        <div class="space-y-1">
                            <label class="block text-xs font-bold text-charcoal uppercase tracking-wider">Department</label>
                            <input type="text" name="reg_dept" placeholder="e.g. CSE" class="w-full bg-snow-white px-4 py-2.5 text-sm" required>
                        </div>
                        <div class="space-y-1">
                            <label class="block text-xs font-bold text-charcoal uppercase tracking-wider">Semester</label>
                            <input type="text" name="reg_semester" placeholder="e.g. 1st" class="w-full bg-snow-white px-4 py-2.5 text-sm" required>
                        </div>
                        <div class="space-y-1">
                            <label class="block text-xs font-bold text-charcoal uppercase tracking-wider">Session</label>
                            <input type="text" name="reg_session" placeholder="e.g. Fall 2026" class="w-full bg-snow-white px-4 py-2.5 text-sm" required>
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="block text-xs font-bold text-charcoal uppercase tracking-wider">Active Phone Contact Number</label>
                        <input type="text" name="reg_phone" placeholder="e.g. +8801..." class="w-full bg-snow-white px-4 py-2.5 text-sm" required>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full btn-3d-blue text-sm font-bold uppercase tracking-wider py-4 rounded-xl">
                            Complete Account Registration
                        </button>
                    </div>
                </form>

                <div class="mt-6 pt-6 border-t-2 border-cloud-gray text-center text-xs text-graphite font-bold uppercase tracking-wider">
                    Already registered? <button type="button" onclick="toggleFormMode('login')" class="text-sky-blue font-extrabold hover:underline ml-1">Back to Sign In</button>
                </div>
            </div>

        </div>
    </div>
</body>
</html>