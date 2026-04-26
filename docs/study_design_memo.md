# ATLAS Pilot: Study Design Memo

**Date:** 2026-04-26 (locked design)
**Author:** Simo Hosio
**Status:** LOCKED. Design committed. Implementation tweaks may follow but the study design itself is fixed.

---

## Goal

This pilot is a self-contained empirical study targeting **HCOMP 2026 (full paper, archival)**. It measures how everyday self-care practices can be reliably elicited and structured in a human-AI partnership, and it produces a v0.1 seed practice atlas for stress and anxiety coping.

The study addresses three questions:

1. **Natural specificity:** at what specificity level (per dimension: Technique, Dosage, Mode) do people encode their self-care practices when describing them in their own words, with no scaffolding?
2. **Scaffolding effects:** does a minimal verbal nudge, or AI-assisted dialogue, change that specificity, and which dimensions respond most?
3. **Refinement trajectory:** within an AI-coach condition, how often do participants judge their free-text description as already complete, and how does specificity evolve when they do engage in refinement?

This is a concept-formation and data-collection study. It is independent of the ATLAS proposal's planned cross-cultural N≈1000 study, which is a separate later phase.

## Terminology

- **Primitive:** a single typed value on one dimension. Three primitive types: Technique (T), Dosage (D), Mode (M).
- **Practice:** a triple ⟨T, D, M⟩, one primitive of each type.
- **Practice report:** the raw participant submission. Practice plus context plus outcome.

This terminology is used consistently in pilot artefacts and is harmonised with the ATLAS proposal.

---

## Domain and Sample

### Practice scope

All participants describe a practice they use **specifically when feeling stressed or anxious**, not as part of their general wellbeing routine. The "specifically when" wording appears in the prompt itself. Rationale:

- Tightens the dataset so practices are more directly comparable at the atlas level.
- Excludes generic-wellbeing responses ("I just go to the gym") that are not stress-coping per se.
- Aligned with the HCOMP narrative: stress and anxiety coping carries clear wellbeing stakes for the alignment / fidelity story.

### Sample

Recruited via Prolific (also accessible via open web link, source tracked via PID prefix). No screening on mental health diagnosis or symptom severity.

PSS-4 (Perceived Stress Scale, 4-item; Cohen & Williamson 1988) administered at intake for sample characterisation. Used descriptively, not as eligibility gate, not as moderator in primary analysis.

**Why PSS-4:**
- ~30 seconds. Minimal burden.
- Normal-population measure with US/UK normative data; not a clinical screener (sidesteps the ethical question of mental-health screening on Prolific).
- Free, no licensing.
- 4 items on a 0-4 scale, summed to 0-16. Higher = more perceived stress in the past month.
- Limitation: lower internal consistency than PSS-10 (α ≈ 0.6 to 0.72). Acceptable for sample characterisation.

### Sample size

Round-number target ≥100 per condition (≥300 total). Final N decided after a small pilot launch confirms recruitment rate. Power analysis deferred to submission, reported retrospectively.

---

## Specificity Coding Scheme

Grounded in TIDieR (Hoffmann et al., 2014), the BCT taxonomy (Michie et al., 2013), and the Mode of Delivery Ontology (Marques et al., 2021). Dependent variable is **specificity per dimension**, ordinal 0-3.

We do **not** use a "computability threshold" in this paper. The earlier 6/9 threshold belonged to the proposal narrative. For the pilot, we report per-dimension specificity directly, with the simple sum T+D+M as a descriptive composite only.

### Technique Specificity (What you do)

| Level | Label | Description | Example |
|-------|-------|-------------|---------|
| 0 | Absent | No technique mentioned | "I just try to feel better" |
| 1 | Category | Broad category only | "relaxation", "exercise" |
| 2 | Named | Specific named practice | "box breathing", "running" |
| 3 | Parameterised | Practice with defining parameters | "4-4-4-4 box breathing", "interval running at 70% HR" |

### Dosage Specificity (How much)

| Level | Label | Description | Example |
|-------|-------|-------------|---------|
| 0 | Absent | No dosage information | (none) |
| 1 | Vague | Non-quantified | "sometimes", "when I need it", "a bit" |
| 2 | Single parameter | One of duration, frequency, or intensity | "20 minutes" or "3x/week" |
| 3 | Multi-parameter | Two or more | "20 min, 3x/week" or "5 min every morning" |

### Mode Specificity (In what form)

| Level | Label | Description | Example |
|-------|-------|-------------|---------|
| 0 | Absent | No mode information | (none) |
| 1 | Vague | Minimal context | "by myself", "at home" |
| 2 | Specified | Clear mode with one qualifier | "solo outdoors", "with a group in class" |
| 3 | Operationalised | Mode plus delivery mechanism or setting detail | "solo outdoors using Headspace app, in park near work" |

---

## Study Design: Three Conditions

Between-subjects, random assignment. ~10 minutes per participant.

### Condition 1: Pure Baseline (n≥100)

**Prompt:** "Think of something you do **specifically when you are feeling stressed or anxious** to help yourself feel better. Describe it in your own words. Tell us whatever feels important about what you do."

No scaffolding, no hints, no AI. Large free-text box.

Tests: at what specificity level do people naturally encode T, D, and M without prompting?

### Condition 2: Textual Nudge (n≥100)

**Prompt:** "Think of something you do **specifically when you are feeling stressed or anxious** to help yourself feel better. Try to describe: what exactly you do, how much or how often, and in what way or setting."

Same free-text box. Only difference is three clause-level hints in the prompt. No labels, no cards, no AI.

Tests: does a minimal verbal prompt push people up the specificity scale, and on which dimensions?

### Condition 3: AI-Assisted Iterative Refinement (n≥100)

**Phase 1 (free description):** same prompt as C1 (no nudge).

**Phase 2 (AI refinement, max 2 rounds with confirmation gate):**

After the participant submits the free description:

1. AI extracts a first-pass practice ⟨T, D, M⟩ from the text.
2. AI shows the extracted practice and asks: "Does this accurately summarise the practice you described? **[Yes, accurate]** / **[No, let me refine]**"
3. If Yes: exit C3. RoundsTaken = 0.
4. If No: AI asks a targeted follow-up to sharpen the lowest-specificity dimension. Participant responds. AI updates the extracted practice. Same gate is shown again.
5. Hard cap at 2 refinement rounds. After round 2 the participant proceeds regardless.

**Variable of interest: RoundsTaken ∈ {0, 1, 2}.** This is itself a finding: what fraction of free-text descriptions are participant-judged as already complete, and what fraction need one or two rounds of AI dialogue?

Tests: how does AI-assisted dialogue change per-dimension specificity, isolated from textual-scaffolding effects (via the C2 control)?

---

## Study Flow (as implemented in app)

1. Consent + eligibility + brief PSS-4
2. Practice description (condition-specific prompt)
3. [C3 only] AI coach refinement, max 2 rounds with confirmation gate
4. Fidelity check: C1 and C2 see their raw text back; C3 sees the structured practice
5. Context + outcome (exploratory practice-report fields)
6. Questionnaire (willingness, interest, feedback)
7. Debrief + completion code

---

## Measures

### Primary DVs

| Measure | Type | Applied to |
|---------|------|-----------|
| Per-dimension specificity (T, D, M each 0-3) | Coded by Prolific raters, blind | All conditions, on raw text (C1/C2) and per-round transcript (C3) |
| Per-round specificity gain | Coded + telemetry | C3 |
| RoundsTaken ∈ {0, 1, 2} | Telemetry | C3 |

### Secondary DVs

| Measure | Type | Purpose |
|---------|------|---------|
| Semantic fidelity (Likert 1-7) | Self-report | Does the structured/free-text representation capture intent? |
| Forced-fit (Likert 1-7) | Self-report | Boundary conditions: where does the structure mis-fit? |
| Sum specificity (T+D+M, 0-9) | Derived | Descriptive composite, no threshold |
| Time per round | Telemetry (C3) | Effort trajectory |
| Willingness to contribute (Likert 1-7) | Self-report | Atlas viability signal |
| Context and outcome triples | Open text | Atlas enrichment |
| PSS-4 (0-16) | Self-report | Sample characterisation only |

---

## Coding Protocol

### Specificity coding (primary DV)

Two-stage:

1. **Rubric pilot.** Take ~50 sample descriptions (drawn from a small pre-launch dry run, n≈10-15 across conditions). Recruit ~5 Prolific raters per item. Each rater applies the rubric, with worked examples shown inline. Compute pairwise kappa per dimension. **Target: kappa ≥ 0.6 on each dimension.** If below threshold, simplify rubric or add examples and re-pilot. If above, proceed.
2. **Full coding.** ≥3 Prolific raters per response, blind to condition. Final per-dimension specificity = modal label across raters. Cases with no majority resolved by expert (Hosio). Inter-rater kappa per dimension reported on the full set.

Rubric is operationalised as a decision tree with worked examples per level. Drafted before the rater pilot; locked when the pilot achieves the kappa threshold.

### Canonical-technique clustering (atlas)

Expert-coded by Hosio plus one coauthor, applying BCTO merge/split criteria (Marques et al., 2024): same mechanism of action → same canonical technique. Independent first pass, agreement reported as kappa, disagreements adjudicated jointly. Not crowd-codable work.

### Internal analysis plan

No public OSF pre-registration. Internal date-stamped analysis plan in `docs/analysis_plan_v1.md`, written before any data inspection. Anything not specified there is flagged as exploratory in the paper.

---

## Validity Controls

- Attention checks: 2-3 embedded items plus a minimum response time (90s for the description task).
- Practice-plausibility follow-up: requires consistent answer ("How long have you been doing this practice?").
- Demand-characteristic check: "Did you feel the study was trying to get you to respond in a particular way?"
- LLM fallback (C3): pre-generated extractions used if the API times out.
- Bot detection: Prolific native checks plus timing filters.
- PSS-4 attention: standard quality checks (response variance, time).

---

## Analysis Plan (summary)

Detailed plan in `docs/analysis_plan_v1.md`. High-level:

- **C1 vs C2.** Three ordinal logistic regressions (one per dimension). Bonferroni-corrected.
- **C1 vs C3-final.** Three ordinal logistic regressions, same structure. Bonferroni-corrected.
- **C3 within-subjects refinement.** Cumulative-link mixed-effects model on per-dimension specificity by round, restricted to participants who took ≥1 round. Random intercept per participant.
- **RoundsTaken distribution.** Descriptive (proportion at 0 / 1 / 2).
- **Dimension asymmetry.** Compare effect sizes across T vs D vs M within each contrast.
- **Atlas analyses.** Descriptive (canonical cluster count, frequency distribution, per-cluster specificity, dosage and mode value variation per cluster).
- **Power-law fit on technique frequency.** Attempted (Clauset-Shalizi-Newman); reported as exploratory given modest N.

Power analysis reported retrospectively at submission.

---

## What the Study Produces

### HCOMP paper contributions, ordered by HCOMP pillars

1. **Complementarity (lead).** A two-round AI dialogue increases per-dimension specificity from baseline level X to level Y, isolated from textual-scaffolding effects via the no-AI nudge control. The increase concentrates on [dimensions]; on [other dimensions], AI dialogue adds little beyond what a textual nudge already provides. RoundsTaken distribution shows what fraction of free-text descriptions are participant-judged as already complete.
2. **Alignment / fidelity.** Across refinement rounds, semantic-fidelity ratings show [pattern]; forced-fit ratings show [pattern]. The AI's extracted practice preserves participant intent on [dimensions] but distorts on [dimensions]. We characterise where AI-extracted structure aligns with self-report and where it diverges.
3. **Dataset (Human Contributions to AI).** A v0.1 seed practice atlas for stress and anxiety coping: N canonical practices contributed by Prolific participants under three scaffolding regimes, with primitive-level frequencies, per-dimension specificity profiles, and self-reported context-outcome triples.

### Seed Practice Atlas (v0.1)

Released alongside the paper on Zenodo / OSF, CC-BY. Scope:

- Raw practice reports across all conditions (free text plus AI-refined where applicable).
- Per-response specificity codings (Prolific raters, with kappa).
- Initial canonical clustering of Technique values, expert-coded with BCTO criteria, with kappa.
- Dosage and Mode values reported per cluster (not collapsed; variation IS the data).
- Context-outcome triples per response.

### Atlas-level descriptive analyses in the paper

- Number of canonical techniques after expert clustering.
- Empirical Technique frequency distribution; heavy-tail pattern reported descriptively.
- Per-cluster specificity profile across conditions.
- Coverage of stress/anxiety practice space discussed qualitatively.

---

## Open Items (parking lot, not blockers)

1. Final exact N (decided after pilot launch confirms recruitment rate).
2. Choice of LLM and exact prompt for C3 (frozen via internal pilot before launch).
3. Specific Prolific rater recruitment parameters (n_raters, payment, attention checks).
4. Ethics / IRB status.
5. Coauthor identification for canonical clustering.

---

## Implementation TODOs from these locks

The app and supporting docs need updates to match the locked design. None are technically blocking but all should ship before launch:

- `app/steps/input.php`: update C1 and C2 prompt text to include "specifically when you are feeling stressed or anxious".
- `app/steps/consent.php`: update eligibility text; add PSS-4 either at end of consent or as a new step before input.
- `app/steps/refinement.php`: add confirmation gate after each AI extraction (Yes-exit / No-refine). Hard cap at 2 refinement rounds. Track RoundsTaken in DB.
- `app/llm.php`: update the system prompt to use "practice" / "primitive" terminology rather than "behavioural gene".
- (Optional cleanup) Internal variable names (`$gene` → `$practice`), CSS class names (`.gene-card` → `.practice-card`), DB column comments. Schema (`gene_extractions` table) can stay; not user-facing.
- `paper/paper.tex`: switch venue declaration from CHI '27 to HCOMP '26 and `acmart` document class option from `manuscript` to `sigconf`.

---

## Key Literature

| Paper | Relevance | DOI |
|-------|-----------|-----|
| Hoffmann et al. (2014) — TIDieR Checklist | Grounds T/D/M specificity coding | 10.1136/bmj.g1687 |
| Michie et al. (2013) — BCT Taxonomy v1 | Technique classification framework | 10.1007/s12160-013-9486-6 |
| Marques et al. (2024) — BCT Ontology | Merge/split criteria for canonical clustering | 10.12688/wellcomeopenres.19363.2 |
| Marques et al. (2021) — Mode of Delivery Ontology | Authoritative ontology for the M dimension | 10.12688/wellcomeopenres.15906.2 |
| Yang et al. (2020) CHI | Isolate AI from interface contributions (motivates C2) | 10.1145/3313831.3376301 |
| Dhillon et al. (2024) CHI | Scaffolding effects in co-writing with LMs | 10.1145/3613904.3642134 |
| Iarygina et al. (2024) IJHCS | Demand characteristics in HCI experiments | 10.1016/j.ijhcs.2024.103379 |
| Rapp & Cena (2016) IJHCS | Scaffolding shapes self-reports | 10.1016/j.ijhcs.2016.05.006 |
| Douglas et al. (2023) PLOS ONE | Prolific data quality benchmarks | 10.1371/journal.pone.0279720 |
| Bhattacharjee et al. (2024) CHI | LLM scaffolding for behaviour change | 10.1145/3613904.3642081 |
| Snow et al. (2008) EMNLP | Crowd annotation matches expert quality with multiple raters | n/a |
| Cohen, Kamarck, Mermelstein (1983) JHSB | Original Perceived Stress Scale; PSS-4 used in our intake is the brief form derived from this | 10.2307/2136404 |
| West et al. (2023) | ML extraction reality check (F1=0.42) | 10.12688/wellcomeopenres.20000.1 |
