<?php
$page_title = 'ATLAS Study — Welcome';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_POST['consent']) || empty($_POST['eligible_age']) || empty($_POST['eligible_practice'])) {
        $error = 'Please confirm all items to continue.';
    } else {
        // Use forced condition if set (test links), otherwise random
        $condition = $_SESSION['forced_condition'] ?? random_int(1, 3);
        $completion_code = 'ATLAS-' . strtoupper(bin2hex(random_bytes(3)));

        if (!$is_test) {
            $db = get_db();
            $stmt = $db->prepare('SELECT id, condition_num, completion_code FROM participants WHERE prolific_pid = :pid');
            $stmt->bindValue(':pid', $_SESSION['prolific_pid']);
            $existing = $stmt->execute()->fetchArray(SQLITE3_ASSOC);

            if ($existing) {
                $_SESSION['participant_id'] = (int)$existing['id'];
                $condition = (int)$existing['condition_num'];
                $completion_code = $existing['completion_code'];
            } else {
                $stmt = $db->prepare('INSERT INTO participants (prolific_pid, source, condition_num, completion_code) VALUES (:pid, :source, :cond, :code)');
                $stmt->bindValue(':pid', $_SESSION['prolific_pid']);
                $stmt->bindValue(':source', $_SESSION['source']);
                $stmt->bindValue(':cond', $condition);
                $stmt->bindValue(':code', $completion_code);
                $stmt->execute();
                $_SESSION['participant_id'] = $db->lastInsertRowID();
            }
        } else {
            $_SESSION['participant_id'] = -1; // Dummy ID for test sessions
        }

        $_SESSION['condition'] = $condition;
        $_SESSION['completion_code'] = $completion_code;

        header('Location: ?step=input');
        exit;
    }
}

require __DIR__ . '/../templates/header.php';
?>

<div class="study-card">
    <h2 class="mb-3">Welcome to the ATLAS Study</h2>
    <p>We are researchers at the University of Oulu studying how people describe their everyday self-care practices. Your input will help us understand how to build better tools for self-care science.</p>

    <h5 class="mt-4">What you'll do</h5>
    <p>You will describe a stress or anxiety coping practice that you use, answer a few brief questions about your experience, and provide feedback. The study takes approximately <strong>10 minutes</strong>.</p>

    <h5 class="mt-4">Your data</h5>
    <p>Your responses are anonymised and stored securely. They may be used in published research and shared as part of an open dataset. No personally identifying information is collected beyond your Prolific ID (used only for compensation).</p>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post" class="mt-4">
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" name="consent" value="1" id="consent">
            <label class="form-check-label" for="consent">I have read the above information and agree to participate in this study.</label>
        </div>
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" name="eligible_age" value="1" id="eligible_age">
            <label class="form-check-label" for="eligible_age">I am 18 years of age or older.</label>
        </div>
        <div class="form-check mb-4">
            <input class="form-check-input" type="checkbox" name="eligible_practice" value="1" id="eligible_practice">
            <label class="form-check-label" for="eligible_practice">I have a stress or anxiety coping practice that I've used in the past month.</label>
        </div>

        <button type="submit" class="btn btn-primary btn-lg w-100">Continue</button>
    </form>
</div>

<?php require __DIR__ . '/../templates/footer.php'; ?>
