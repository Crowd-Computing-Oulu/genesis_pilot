<?php
session_start();
require_once __DIR__ . '/db.php';

$step = $_GET['step'] ?? 'consent';

// Handle Prolific PID or generate web PID
if ($step === 'consent' && !isset($_SESSION['participant_id'])) {
    $prolific_pid = $_GET['PROLIFIC_PID'] ?? null;
    if ($prolific_pid) {
        $_SESSION['source'] = 'prolific';
        $_SESSION['prolific_pid'] = $prolific_pid;
    } else {
        $_SESSION['source'] = 'web';
        $_SESSION['prolific_pid'] = 'web_' . bin2hex(random_bytes(4));
    }
}

// Calculate progress for progress bar
$steps_order = ['consent', 'input', 'refinement', 'fidelity', 'exploratory', 'questionnaire', 'debrief'];
$current_index = array_search($step, $steps_order);
$total_steps = count($steps_order);
$progress = $current_index !== false ? (($current_index) / ($total_steps - 1)) * 100 : 0;

// Route to step
$step_file = __DIR__ . '/steps/' . basename($step) . '.php';
if (file_exists($step_file)) {
    require $step_file;
} else {
    header('Location: ?step=consent');
    exit;
}
