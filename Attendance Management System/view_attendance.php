<?php
require 'database.php';
if ($_SESSION['role'] != 'teacher') { header("Location: dashboard.php"); exit; }

$course_id = isset($_GET['course_id']) ? intval($_GET['course_id']) : 0;
$chosen_date = isset($_POST['target_date']) ? $_POST['target_date'] : date('Y-m-d');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['inline_toggle'])) {
    $stmt = $conn->prepare("UPDATE daily_attendance SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $_POST['new_status'], intval($_POST['record_id'])); $stmt->execute();
    header("Location: view_attendance.php?course_id=$course_id"); exit;
}

$stmt = $conn->prepare("SELECT da.id as rec_id, da.status, u.name, s.roll_number, s.department FROM daily_attendance da JOIN users u ON da.student_id = u.id JOIN students s ON u.id = s.user_id WHERE da.course_id = ? AND da.date = ? ORDER BY u.name ASC");
$stmt->bind_param("is", $course_id, $chosen_date); $stmt->execute(); $logs = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log Sheet — AttendancePro</title>
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
        
        /* 3D Interactive Tactical Components */
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
            color: #777777;
            border: 2px solid #e5e5e5;
            box-shadow: 0 4px 0 #e5e5e5;
            transition: all 0.1s ease;
        }
        .btn-3d-outline:active {
            transform: translateY(4px);
            box-shadow: 0 0px 0 #e5e5e5;
        }

        /* Form Controls */
        input[type="date"] {
            border: 2px solid #e5e5e5 !important;
            border-radius: 12px !important;
            font-family: 'Nunito Sans', sans-serif !important;
            letter-spacing: 0.053em !important;
            color: #3c3c3c !important;
        }
        input[type="date"]:focus {
            border-color: #1cb0f6 !important;
            outline: none;
        }
    </style>
</head>
<body class="bg-snow-white min-h-screen font-din-round text-almost-black antialiased font-medium m-0 p-0">

    <header class="w-full max-w-[1140px] mx-auto px-4 pt-6 sticky top-0 z-50 bg-snow-white/90 backdrop-blur-md">
        <div class="border-b-2 border-cloud-gray h-20 px-2 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-graduation-cap text-duo-green text-3xl"></i>
                <span class="font-feather text-2xl text-duo-green tracking-tight lowercase">attendance.pro</span>
            </div>
            <div>
                <a href="dashboard.php" class="btn-3d-outline inline-flex items-center gap-2 text-xs font-bold uppercase tracking-wider px-4 py-2.5 rounded-xl">
                    <i class="fa-solid fa-arrow-left"></i> Dashboard
                </a>
            </div>
        </div>
    </header>

    <main class="w-full max-w-[1140px] mx-auto px-6 py-12">
        
        <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-center border-b-2 border-cloud-gray pb-8 mb-8">
            <div class="md:col-span-7 flex items-center gap-6">
                <div class="w-20 h-20 bg-duo-green-light rounded-2xl flex items-center justify-center text-4xl select-none shrink-0">
                    📋
                </div>
                <div>
                    <p class="text-[11px] font-extrabold tracking-[0.2em] text-silver mb-1 uppercase">ARCHIVE AUDIT CHANNEL</p>
                    <h1 class="font-feather text-3xl sm:text-4xl text-duo-green lowercase leading-none">view attendance</h1>
                </div>
            </div>
            
            <div class="md:col-span-5 bg-snow-white border-2 border-cloud-gray rounded-2xl p-4">
                <form method="POST" class="flex items-center gap-3">
                    <div class="flex-1">
                        <label class="block text-[10px] font-extrabold text-charcoal uppercase tracking-wider mb-1">Target Assessment Date</label>
                        <input type="date" name="target_date" value="<?php echo htmlspecialchars($chosen_date); ?>" class="w-full px-3 py-2 text-sm font-bold bg-snow-white">
                    </div>
                    <button type="submit" class="btn-3d-blue px-5 py-3 rounded-xl font-bold text-xs uppercase tracking-wider self-end h-[44px]">
                        Query
                    </button>
                </form>
            </div>
        </div>

        <?php if ($logs->num_rows === 0): ?>
            <div class="text-center py-16 max-w-sm mx-auto space-y-4">
                <div class="text-6xl select-none">💤</div>
                <h3 class="font-feather text-2xl text-charcoal lowercase">no records found</h3>
                <p class="text-sm text-graphite tracking-wide">No check-in tokens found for this course criteria on the specified date block.</p>
            </div>
        <?php else: ?>
            <div class="bg-snow-white border-2 border-cloud-gray rounded-3xl overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse m-0">
                        <thead>
                            <tr class="border-b-2 border-cloud-gray bg-cloud-gray/10 text-[11px] font-extrabold text-charcoal uppercase tracking-wider">
                                <th class="px-6 py-4">Student Profile Context</th>
                                <th class="px-6 py-4">Roll Number</th>
                                <th class="px-6 py-4">Current Status</th>
                                <th class="px-6 py-4 text-right">Quick Override Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y-2 divide-cloud-gray">
                            <?php 
                            while ($l = $logs->fetch_assoc()): 
                                if ($l['status'] == 'Present') {
                                    $badge_styles = 'bg-grape-soda/10 text-grape-soda border-grape-soda';
                                    $target_toggle_status = 'Absent';
                                    $target_toggle_label = 'Mark Absent';
                                    $action_btn_class = 'hover:bg-bubblegum-pink/5 hover:text-bubblegum-pink hover:border-bubblegum-pink';
                                } else {
                                    $badge_styles = 'bg-bubblegum-pink/10 text-bubblegum-pink border-bubblegum-pink';
                                    $target_toggle_status = 'Present';
                                    $target_toggle_label = 'Mark Present';
                                    $action_btn_class = 'hover:bg-grape-soda/5 hover:text-grape-soda hover:border-grape-soda';
                                }
                            ?>
                                <tr class="hover:bg-cloud-gray/10 transition duration-150 items-center">
                                    <td class="px-6 py-4.5">
                                        <div class="font-bold text-almost-black text-sm tracking-wide"><?php echo htmlspecialchars($l['name']); ?></div>
                                        <div class="text-[10px] text-silver font-extrabold uppercase tracking-wider mt-0.5"><?php echo htmlspecialchars($l['department']); ?></div>
                                    </td>
                                    <td class="px-6 py-4.5 font-mono text-xs font-bold text-charcoal">
                                        #<?php echo htmlspecialchars($l['roll_number']); ?>
                                    </td>
                                    <td class="px-6 py-4.5">
                                        <span class="inline-block px-3 py-1 rounded-xl text-[11px] font-extrabold border-2 <?php echo $badge_styles; ?> uppercase tracking-wider">
                                            <?php echo $l['status'] == 'Present' ? '🌟 Present' : '❌ Absent'; ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4.5 text-right">
                                        <form method="POST" class="inline">
                                            <input type="hidden" name="inline_toggle" value="1">
                                            <input type="hidden" name="record_id" value="<?php echo $l['rec_id']; ?>">
                                            <input type="hidden" name="new_status" value="<?php echo $target_toggle_status; ?>">
                                            <button type="submit" class="btn-3d-outline text-[11px] font-extrabold px-3 py-1.5 rounded-xl uppercase tracking-wider border-2 <?php echo $action_btn_class; ?> transition-colors shadow-[0_2px_0_#e5e5e5] active:translate-y-[2px] active:shadow-none">
                                                <?php echo $target_toggle_label; ?>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
    </main>

</body>
</html>