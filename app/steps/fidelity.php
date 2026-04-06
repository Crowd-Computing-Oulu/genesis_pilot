<?php
$page_title = 'GENESIS Study — Review';
require_once __DIR__ . '/../llm.php';

if (!isset($_SESSION['participant_id'])) {
    header('Location: ?step=consent');
    exit;
}

$db = get_db();
$condition = $_SESSION['condition'];

// For conditions 1 & 2, extract gene now (not shown during input)
if ($condition !== 3 && !isset($_SESSION['fidelity_gene'])) {
    $gene = extract_gene($_SESSION['description']);
    if (!$gene) {
        $gene = [
            'technique' => ['value' => null, 'confidence' => 'low'],
            'dosage' => ['value' => null, 'confidence' => 'low'],
            'mode' => ['value' => null, 'confidence' => 'low'],
        ];
    }

    if (!$is_test) {
        $db = get_db();
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
    }

    $_SESSION['fidelity_gene'] = $gene;
}

$gene = $_SESSION['fidelity_gene'] ?? $_SESSION['current_gene'] ?? [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['fidelity_data'] = [
        'semantic_fidelity' => (int)($_POST['semantic_fidelity'] ?? 0),
        'forced_fit' => (int)($_POST['forced_fit'] ?? 0),
        'fidelity_feedback' => trim($_POST['fidelity_feedback'] ?? ''),
    ];
    header('Location: ?step=exploratory');
    exit;
}

require __DIR__ . '/../templates/header.php';
?>

<div class="progress-bar-custom">
    <div class="fill" style="width: <?= $progress ?>%"></div>
</div>

<div class="spinner-overlay" id="spinner">
    <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;"></div>
    <p class="text-muted">Processing your description...</p>
</div>

<div class="study-card">
    <h4 class="mb-3">Structured Summary of Your Practice</h4>
    <p>Based on what you described, here's a structured summary:</p>

    <div class="mb-4">
        <?php
        $dims = ['technique' => 'What you do', 'dosage' => 'How much / how often', 'mode' => 'In what way / setting'];
        foreach ($dims as $key => $label):
            $value = $gene[$key]['value'] ?? null;
        ?>
        <div class="gene-card">
            <div class="gene-label"><?= $label ?></div>
            <div class="gene-value">
                <?php if ($value): ?>
                    <?= htmlspecialchars($value) ?>
                <?php else: ?>
                    <span class="gene-missing">Not clearly captured</span>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <form method="post">
        <p class="fw-bold">How well does this capture your actual practice?</p>
        <div class="likert-group">
            <?php for ($i = 1; $i <= 7; $i++): ?>
            <label>
                <input type="radio" name="semantic_fidelity" value="<?= $i ?>" required>
                <?= $i ?>
            </label>
            <?php endfor; ?>
        </div>
        <div class="likert-endpoints">
            <span>Not at all</span>
            <span>Perfectly</span>
        </div>

        <p class="fw-bold">Did you have to leave out or distort anything important to describe your practice?</p>
        <div class="likert-group">
            <?php for ($i = 1; $i <= 7; $i++): ?>
            <label>
                <input type="radio" name="forced_fit" value="<?= $i ?>" required>
                <?= $i ?>
            </label>
            <?php endfor; ?>
        </div>
        <div class="likert-endpoints">
            <span>Not at all</span>
            <span>Very much</span>
        </div>

        <div class="mb-4">
            <label class="form-label">What's missing or wrong? (optional)</label>
            <textarea class="form-control" name="fidelity_feedback" rows="2"></textarea>
        </div>

        <button type="submit" class="btn btn-primary btn-lg w-100">Continue</button>
    </form>
</div>

<?php require __DIR__ . '/../templates/footer.php'; ?>
