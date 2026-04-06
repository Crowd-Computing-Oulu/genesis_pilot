<?php
$page_title = 'GENESIS Study — Refining Your Practice';
require_once __DIR__ . '/../llm.php';

if (!isset($_SESSION['participant_id']) || $_SESSION['condition'] !== 3) {
    header('Location: ?step=consent');
    exit;
}

$db = get_db();
$round = (int)($_GET['round'] ?? 0);

// Round 0: Initial extraction
if ($round === 0) {
    $gene = extract_gene($_SESSION['description']);
    if (!$gene) {
        // Fallback if LLM fails
        $gene = [
            'technique' => ['value' => null, 'confidence' => 'low'],
            'dosage' => ['value' => null, 'confidence' => 'low'],
            'mode' => ['value' => null, 'confidence' => 'low'],
        ];
    }

    // Save extraction
    $stmt = $db->prepare('INSERT INTO gene_extractions (participant_id, round, technique, dosage, mode, technique_confidence, dosage_confidence, mode_confidence, raw_llm_response) VALUES (:pid, 0, :t, :d, :m, :tc, :dc, :mc, :raw)');
    $stmt->bindValue(':pid', $_SESSION['participant_id']);
    $stmt->bindValue(':t', $gene['technique']['value']);
    $stmt->bindValue(':d', $gene['dosage']['value']);
    $stmt->bindValue(':m', $gene['mode']['value']);
    $stmt->bindValue(':tc', $gene['technique']['confidence']);
    $stmt->bindValue(':dc', $gene['dosage']['confidence']);
    $stmt->bindValue(':mc', $gene['mode']['confidence']);
    $stmt->bindValue(':raw', $gene['_raw'] ?? '');
    $stmt->execute();

    $_SESSION['current_gene'] = $gene;

    $initial = get_initial_question($gene);
    $_SESSION['current_target'] = $initial['dimension'];
    $_SESSION['current_question'] = $initial['question'];

    header('Location: ?step=refinement&round=1');
    exit;
}

// Rounds 1-2: Refinement
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $round >= 1 && $round <= 2) {
    $response = trim($_POST['response'] ?? '');
    if (strlen($response) < 5) {
        $error = 'Please provide a bit more detail.';
    } else {
        $target = $_SESSION['current_target'];

        // Save participant response
        $stmt = $db->prepare('INSERT INTO responses (participant_id, step, prompt_shown, response_text) VALUES (:pid, :step, :prompt, :text)');
        $stmt->bindValue(':pid', $_SESSION['participant_id']);
        $stmt->bindValue(':step', 'refinement_r' . $round);
        $stmt->bindValue(':prompt', $_SESSION['current_question']);
        $stmt->bindValue(':text', $response);
        $stmt->execute();

        // Call LLM to refine
        $refined = refine_gene(
            $_SESSION['description'],
            $_SESSION['current_gene'],
            $response,
            $target
        );

        if ($refined) {
            $_SESSION['current_gene'] = $refined;
            $next_target = $refined['next_target'] ?? 'mode';
            $next_question = $refined['next_question'] ?? "Can you tell us more about how you do this practice?";
        } else {
            // Fallback — keep current gene, ask generic question
            $next_target = 'mode';
            $next_question = "Can you tell us more about how you do this practice?";
            $refined = $_SESSION['current_gene'];
        }

        // Save refined extraction
        $stmt = $db->prepare('INSERT INTO gene_extractions (participant_id, round, technique, dosage, mode, technique_confidence, dosage_confidence, mode_confidence, targeted_dimension, ai_question, raw_llm_response) VALUES (:pid, :round, :t, :d, :m, :tc, :dc, :mc, :td, :aq, :raw)');
        $stmt->bindValue(':pid', $_SESSION['participant_id']);
        $stmt->bindValue(':round', $round);
        $stmt->bindValue(':t', $refined['technique']['value'] ?? null);
        $stmt->bindValue(':d', $refined['dosage']['value'] ?? null);
        $stmt->bindValue(':m', $refined['mode']['value'] ?? null);
        $stmt->bindValue(':tc', $refined['technique']['confidence'] ?? 'low');
        $stmt->bindValue(':dc', $refined['dosage']['confidence'] ?? 'low');
        $stmt->bindValue(':mc', $refined['mode']['confidence'] ?? 'low');
        $stmt->bindValue(':td', $target);
        $stmt->bindValue(':aq', $_SESSION['current_question']);
        $stmt->bindValue(':raw', $refined['_raw'] ?? '');
        $stmt->execute();

        if ($round < 2) {
            $_SESSION['current_target'] = $next_target;
            $_SESSION['current_question'] = $next_question;
            header('Location: ?step=refinement&round=' . ($round + 1));
        } else {
            header('Location: ?step=refinement&round=3');
        }
        exit;
    }
}

// Round 3: Final confirmation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $round === 3) {
    // User confirmed or adjusted
    $adjustment = trim($_POST['adjustment'] ?? '');
    if ($adjustment) {
        $stmt = $db->prepare('INSERT INTO responses (participant_id, step, prompt_shown, response_text) VALUES (:pid, :step, :prompt, :text)');
        $stmt->bindValue(':pid', $_SESSION['participant_id']);
        $stmt->bindValue(':step', 'refinement_r3_adjustment');
        $stmt->bindValue(':prompt', 'Final adjustment');
        $stmt->bindValue(':text', $adjustment);
        $stmt->execute();
    }

    header('Location: ?step=fidelity');
    exit;
}

$gene = $_SESSION['current_gene'] ?? [];

require __DIR__ . '/../templates/header.php';
?>

<div class="progress-bar-custom">
    <div class="fill" style="width: <?= $progress ?>%"></div>
</div>

<div class="spinner-overlay" id="spinner">
    <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;"></div>
    <p class="text-muted">Analysing your response...</p>
</div>

<?php if ($round >= 1 && $round <= 2): ?>

<div class="study-card">
    <h4 class="mb-3">Let's refine your practice description</h4>
    <p class="text-muted">Round <?= $round ?> of 2</p>

    <div class="mb-4">
        <?php
        $dims = [
            'technique' => 'What you do',
            'dosage' => 'How much / how often',
            'mode' => 'In what way / setting'
        ];
        foreach ($dims as $key => $label):
            $value = $gene[$key]['value'] ?? null;
            $confidence = $gene[$key]['confidence'] ?? 'low';
            $is_target = ($key === $_SESSION['current_target']);
            $card_class = $is_target ? 'gene-weak' : ($confidence === 'high' ? 'gene-strong' : '');
        ?>
        <div class="gene-card <?= $card_class ?>">
            <div class="gene-label"><?= $label ?></div>
            <div class="gene-value">
                <?php if ($value): ?>
                    <?= htmlspecialchars($value) ?>
                <?php else: ?>
                    <span class="gene-missing">Not clearly captured yet</span>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="alert alert-info">
        <?= htmlspecialchars($_SESSION['current_question']) ?>
    </div>

    <form method="post" onsubmit="document.getElementById('spinner').classList.add('active')">
        <textarea class="form-control mb-3" name="response" rows="3" placeholder="Your response..."><?= htmlspecialchars($_POST['response'] ?? '') ?></textarea>
        <button type="submit" class="btn btn-primary btn-lg w-100">Continue</button>
    </form>
</div>

<?php elseif ($round === 3): ?>

<div class="study-card">
    <h4 class="mb-3">Here's your complete practice description</h4>

    <div class="mb-4">
        <?php
        $dims = ['technique' => 'What you do', 'dosage' => 'How much / how often', 'mode' => 'In what way / setting'];
        foreach ($dims as $key => $label):
            $value = $gene[$key]['value'] ?? null;
        ?>
        <div class="gene-card gene-strong">
            <div class="gene-label"><?= $label ?></div>
            <div class="gene-value">
                <?php if ($value): ?>
                    <?= htmlspecialchars($value) ?>
                <?php else: ?>
                    <span class="gene-missing">Not captured</span>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <form method="post">
        <p>Does this look right?</p>
        <textarea class="form-control mb-3" name="adjustment" rows="2" placeholder="If anything is wrong or missing, tell us here (optional)"></textarea>
        <button type="submit" class="btn btn-primary btn-lg w-100">Looks good — Continue</button>
    </form>
</div>

<?php endif; ?>

<?php require __DIR__ . '/../templates/footer.php'; ?>
