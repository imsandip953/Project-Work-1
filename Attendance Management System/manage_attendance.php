<?php
require 'database.php';
if ($_SESSION['role'] != 'teacher') { header("Location: dashboard.php"); exit; }

$course_id = isset($_GET['course_id']) ? intval($_GET['course_id']) : 0;
$semester = isset($_GET['semester']) ? $_GET['semester'] : '';
$session_name = isset($_GET['session_name']) ? $_GET['session_name'] : '';

// Manage active target logging date context
$target_date = isset($_POST['attendance_date']) ? $_POST['attendance_date'] : date('Y-m-d');

// Handle background AJAX submission of individual records to prevent screen reloads
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['ajax_save'])) {
    $student_id = intval($_POST['student_id']);
    $status = $_POST['status'];
    $log_date = $_POST['log_date'];

    $stmt = $conn->prepare("INSERT INTO daily_attendance (student_id, course_id, date, status) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE status=?");
    $stmt->bind_param("iisss", $student_id, $course_id, $log_date, $status, $status);
    if($stmt->execute()) {
        echo "SUCCESS";
    } else {
        echo "ERROR";
    }
    exit;
}

// Fetch the full student roster matching this classroom criteria
$stmt = $conn->prepare("SELECT u.id, u.name, s.roll_number FROM users u JOIN students s ON u.id = s.user_id WHERE s.semester = ? AND s.session_name = ? ORDER BY CAST(s.roll_number AS UNSIGNED) ASC, s.roll_number ASC");
$stmt->bind_param("ss", $semester, $session_name);
$stmt->execute();
$roster = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Fetch details for the header context
$course_stmt = $conn->prepare("SELECT course_name, course_code FROM courses WHERE id = ?");
$course_stmt->bind_param("i", $course_id);
$course_stmt->execute();
$course_info = $course_stmt->get_result()->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roll Call — AttendancePro</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
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
        .font-feather {
            font-family: 'Fredoka', sans-serif;
            font-weight: 700;
            letter-spacing: -0.02em;
        }
        .font-din-round {
            font-family: 'Nunito Sans', sans-serif;
            letter-spacing: 0.053em;
        }
        
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
        
        .btn-3d-pink {
            background-color: #cc348d;
            color: #ffffff;
            box-shadow: 0 4px 0 #9e1f66;
            transition: all 0.1s ease;
        }
        .btn-3d-pink:active {
            transform: translateY(4px);
            box-shadow: 0 0px 0 #9e1f66;
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

        input {
            border: 2px solid #e5e5e5 !important;
            border-radius: 12px !important;
            font-family: 'Nunito Sans', sans-serif !important;
            letter-spacing: 0.053em !important;
            color: #3c3c3c !important;
        }
        input:focus {
            border-color: #1cb0f6 !important;
            outline: none;
        }
    </style>
</head>
<body class="bg-snow-white min-h-screen text-almost-black antialiased font-din-round font-medium p-0 m-0">

    <header class="w-full max-w-[1140px] mx-auto px-4 pt-6 sticky top-0 z-50 bg-snow-white/90 backdrop-blur-md">
        <div class="border-b-2 border-cloud-gray h-20 px-2 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <a href="dashboard.php" class="flex items-center gap-2">
                    <i class="fa-solid fa-graduation-cap text-duo-green text-3xl"></i>
                    <span class="font-feather text-2xl text-duo-green tracking-tight lowercase">attendance.pro</span>
                </a>
                <span class="text-[11px] font-bold bg-sunshine-yellow text-almost-black px-2 py-0.5 rounded-full uppercase tracking-wider">PLUS</span>
            </div>
            
            <div class="flex items-center gap-4">
                <div class="hidden sm:flex items-center gap-2 border-2 border-cloud-gray px-4 py-2 rounded-xl bg-cloud-gray/10">
                    <div class="w-6 h-6 rounded-full bg-sky-blue flex items-center justify-center text-snow-white text-xs font-bold uppercase">
                        <?php echo strtoupper(substr($_SESSION['name'], 0, 1)); ?>
                    </div>
                    <span class="text-xs font-extrabold text-charcoal tracking-wide uppercase">
                        <?php echo htmlspecialchars($_SESSION['name']); ?>
                    </span>
                </div>
                
                <a href="dashboard.php" class="btn-3d-outline px-5 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider text-center">
                    <i class="fa-solid fa-arrow-left mr-1"></i> Hub
                </a>
            </div>
        </div>
    </header>

    <main class="w-full max-w-[800px] mx-auto px-6 py-12">
        
        <div class="mb-10 text-center">
            <span class="text-xs font-extrabold px-3 py-1 bg-grape-soda/10 text-grape-soda rounded-full uppercase tracking-widest">
                <?php echo htmlspecialchars($course_info['course_code'] ?? 'Syllabus Module'); ?>
            </span>
            <h1 class="font-feather text-3xl md:text-4xl text-almost-black lowercase mt-2 mb-4">
                <?php echo htmlspecialchars($course_info['course_name'] ?? 'Classroom Workspace'); ?>
            </h1>
            <p class="text-graphite font-bold text-xs uppercase tracking-wider">
                Target Group: Term <?php echo htmlspecialchars($semester); ?> &bull; Session Frame <?php echo htmlspecialchars($session_name); ?>
            </p>
        </div>

        <div class="bg-snow-white border-2 border-cloud-gray rounded-3xl p-6 mb-8 shadow-sm">
            <form method="POST" id="date-form" class="m-0 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="text-2xl">📅</div>
                    <div>
                        <label class="block text-[10px] font-extrabold text-silver uppercase tracking-widest">Log Target Date</label>
                        <span class="text-xs font-bold text-charcoal uppercase tracking-wider">Processing Sequence Timestamp</span>
                    </div>
                </div>
                <input type="date" name="attendance_date" value="<?php echo $target_date; ?>" onchange="document.getElementById('date-form').submit();" class="px-4 py-2 text-sm font-bold w-full sm:w-auto">
            </form>
        </div>

        <div id="flashcard-panel" class="bg-snow-white border-2 border-cloud-gray rounded-3xl p-8 text-center relative min-h-[340px] flex flex-col justify-between items-center shadow-sm">
            
            <div class="w-full bg-cloud-gray h-4 rounded-full overflow-hidden relative mb-6">
                <div id="gauge-bar" class="bg-duo-green h-full tracking-wide transition-all duration-300" style="width: 0%;"></div>
            </div>

            <div class="my-auto space-y-4">
                <div class="w-16 h-16 bg-sky-blue/10 rounded-full mx-auto flex items-center justify-center text-sky-blue text-2xl font-bold">
                    👤
                </div>
                <div>
                    <span id="display-roll" class="inline-block text-xs font-mono font-extrabold bg-cloud-gray text-charcoal px-3 py-1 rounded-full uppercase tracking-wider">
                        Roll: --
                    </span>
                    <h2 id="display-name" class="font-feather text-3xl text-almost-black lowercase mt-2">
                        Loading Student...
                    </h2>
                </div>
            </div>

            <div class="w-full grid grid-cols-2 gap-4 mt-8">
                <button onclick="submitStudentStatus('Absent')" class="btn-3d-pink py-4 rounded-xl text-sm font-extrabold uppercase tracking-wider">
                    Absent <span class="hidden sm:inline font-mono text-xs opacity-70 ml-1">[Key 2]</span>
                </button>
                <button onclick="submitStudentStatus('Present')" class="btn-3d-green py-4 rounded-xl text-sm font-extrabold uppercase tracking-wider">
                    Present <span class="hidden sm:inline font-mono text-xs opacity-70 ml-1">[Key 1]</span>
                </button>
            </div>
        </div>

        <div id="completion-panel" class="hidden bg-snow-white border-2 border-cloud-gray rounded-3xl p-12 text-center shadow-sm animate-fade-in">
            <span class="text-6xl block mb-4 select-none animate-bounce">🦉</span>
            <h2 class="font-feather text-3xl text-duo-green lowercase mb-2">great job! checkpoint clear!</h2>
            <p class="text-sm font-bold text-charcoal tracking-wide max-w-md mx-auto mb-8">
                All assigned pipeline elements have been checked and committed safely to the local ledger matrix.
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="dashboard.php" class="btn-3d-green px-6 py-3 rounded-xl text-xs font-bold uppercase tracking-wider">
                    Return to Hub
                </a>
                <a href="view_attendance.php?course_id=<?php echo $course_id; ?>&target_date=<?php echo urlencode($target_date); ?>" class="btn-3d-outline px-6 py-3 rounded-xl text-xs font-bold uppercase tracking-wider">
                    Audit Log Ledger
                </a>
            </div>
        </div>

    </main>

    <script>
        const students = <?php echo json_encode($roster); ?>;
        const executionDate = <?php echo json_encode($target_date); ?>;
        let currentIndex = 0;

        function renderCurrentStudentCard() {
            if (currentIndex >= students.length) {
                document.getElementById('flashcard-panel').classList.add('hidden');
                document.getElementById('completion-panel').classList.remove('hidden');
                return;
            }

            const currentStudent = students[currentIndex];
            document.getElementById('display-name').innerText = currentStudent.name;
            document.getElementById('display-roll').innerText = "Roll: " + currentStudent.roll_number;

            // Step up progress metrics tracking bar width configurations
            const percentage = ((currentIndex) / students.length) * 100;
            document.getElementById('gauge-bar').style.width = percentage + "%";
        }

        function submitStudentStatus(selectedStatus) {
            if (currentIndex >= students.length) return;
            const currentStudent = students[currentIndex];

            const formData = new FormData();
            formData.append('ajax_save', '1');
            formData.append('student_id', currentStudent.id);
            formData.append('status', selectedStatus);
            formData.append('log_date', executionDate);

            // Execute asynchronous request context to process instantly
            fetch(window.location.href, {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                if(data.includes("SUCCESS")) {
                    currentIndex++;
                    renderCurrentStudentCard();
                } else {
                    alert("A database mapping pipeline entry exception occurred.");
                }
            });
        }

        // Support physical keyboard shortcut listeners to maximize speed parameters
        document.addEventListener('keydown', function(event) {
            if(!document.getElementById('flashcard-panel').classList.contains('hidden')) {
                if(event.key === '1') {
                    submitStudentStatus('Present');
                } else if(event.key === '2') {
                    submitStudentStatus('Absent');
                }
            }
        });

        // Initialize display configuration state loop
        renderCurrentStudentCard();
    </script>
</body>
</html>