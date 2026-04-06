# GENESIS Pilot: Study Design Memo

**Date:** 2026-04-06 (revised)
**Author:** Simo Hosio (with Claude research assistance)
**Status:** Working document — study design converging

---

## Goal

Run a pilot study that takes the first empirical step toward the GENESIS ERC application. The study must:

1. **Validate the gene concept at depth:** Not just whether T/D/M dimensions appear, but at what *specificity level* people naturally encode each dimension — and what it takes to reach computable resolution
2. **Characterise the refinement trajectory:** How much human-AI dialogue is needed to go from natural language to a computable gene?
3. **Produce a seed gene corpus:** An initial set of validated behavioural genes for stress/anxiety coping, with specificity profiles

This is a concept validation study, not a UI study.

---

## Why the Original Design Doesn't Work

The original A/B comparison (free text vs. structured T/D/M cards with LLM pre-filling) has critical problems:

1. **Tautological measurement** — Measuring "did 3 fields get filled" when one condition has 3 fields is circular. (Rapp & Cena, 2016.)
2. **Confounded manipulation** — Condition B bundles structural scaffold, input modality, and LLM pre-filling. Cannot attribute effects. (Yang et al., 2020, CHI.)
3. **Demand characteristics** — Condition B signals the hypothesis to participants. (Iarygina et al., 2024, IJHCS.)
4. **Binary measurement is too shallow** — Present/absent coding misses the real question: at what resolution do people encode, and is that resolution sufficient for computation?

---

## Domain Constraint: Stress and Anxiety Coping

All participants describe a practice within **stress/anxiety coping**. Rationale:
- **High prevalence:** Nearly all Prolific workers will have a relevant practice
- **T/D/M variability:** Ranges from highly structured (box breathing 5min daily) to emergent (going outside when overwhelmed) — tests boundary conditions
- **ERC relevance:** Mental wellbeing is central to the grant narrative
- **Corpus coherence:** 300+ genes in one domain enables convergence and coverage analysis

---

## Specificity Coding Scheme

Grounded in TIDieR (Hoffmann et al., 2014) and the Michie group's behavioural ontologies (BCTTv1, Mode of Delivery Ontology). The key shift: we don't just code presence/absence of T, D, M. We code **specificity** — how much detail is provided, and is it enough to make the gene computable and comparable?

### Technique Specificity (What you do)

| Level | Label | Description | Example | TIDieR alignment |
|-------|-------|-------------|---------|-----------------|
| 0 | Absent | No technique mentioned | "I just try to feel better" | Item 3: not reported |
| 1 | Category | Broad category only | "relaxation", "exercise" | Item 3: partial |
| 2 | Named | Specific named practice | "box breathing", "running" | Item 3: adequate |
| 3 | Parameterised | Practice with defining parameters | "4-4-4-4 box breathing", "interval running at 70% HR" | Items 3+4: replicable |

### Dosage Specificity (How much)

| Level | Label | Description | Example | TIDieR alignment |
|-------|-------|-------------|---------|-----------------|
| 0 | Absent | No dosage information | — | Items 6-8: not reported |
| 1 | Vague | Non-quantified | "sometimes", "when I need it", "a bit" | Items 6-8: partial |
| 2 | Single parameter | One of: duration, frequency, or intensity | "20 minutes" or "3x/week" | Items 6-8: partial |
| 3 | Multi-parameter | Two or more: duration + frequency, or including intensity | "20 min, 3x/week" or "5 min every morning" | Items 6-8: adequate |

### Mode Specificity (In what form)

| Level | Label | Description | Example | TIDieR alignment |
|-------|-------|-------------|---------|-----------------|
| 0 | Absent | No mode information | — | Items 5+9: not reported |
| 1 | Vague | Minimal context | "by myself", "at home" | Items 5+9: partial |
| 2 | Specified | Clear mode with one qualifier | "solo outdoors", "with a group in class" | Items 5+9: partial |
| 3 | Operationalised | Mode + delivery mechanism/setting | "solo outdoors using Headspace app, in park near work" | Items 5+9: adequate |

### Gene Computability Score

Sum of T+D+M specificity levels: 0-9 scale. Threshold for "computable" gene: ≥6 (average Level 2 across all dimensions). This is the point at which a gene is specific enough to be meaningfully compared to another gene.

---

## Study Design: Three Conditions

### Condition 1: Pure Baseline (n≥100)

**Prompt:** "Think of something specific you do to manage stress or anxiety. Describe it in your own words — tell us whatever feels important about what you do."

**What it tests:** At what specificity level do people naturally encode T, D, and M without any prompting?

**No scaffolding, no hints, no structure.** Large free-text box.

**Key measures:**
- Specificity profile (T, D, M each coded 0-3)
- Gene computability score (0-9)
- Semantic content beyond T/D/M (what else do people mention — context, motivation, affect?)
- Establishes the **natural resolution floor**

### Condition 2: Textual Nudge (n≥100)

**Prompt:** "Think of something specific you do to manage stress or anxiety. Try to describe: what exactly you do, how much or how often, and in what way or setting."

**What it tests:** Does a minimal verbal prompt push people up the specificity scale — not just from absent to present, but from vague to computable?

**Same free-text box.** Only difference is three clause-level hints in the prompt. No labels, no cards, no AI.

**Key measures:**
- Same specificity coding as Condition 1
- **Specificity gain over baseline** — the key comparison
- Which dimensions respond most to nudging? (Hypothesis: Technique is already high; Dosage and Mode benefit most)
- Quality of the additionally elicited detail — substantive or formulaic?

### Condition 3: AI-Assisted Iterative Refinement (n≥100)

**Phase 1 — Free description:** Same prompt as Condition 1 (no nudge). Participant writes freely.

**Phase 2 — Iterative AI refinement loop (2-3 rounds):**

1. AI extracts a first-pass gene from the free text (probably Level 1-2 on most dimensions)
2. AI presents the extraction and asks a targeted follow-up to sharpen the weakest dimension:
   > "You mentioned breathing exercises — can you describe exactly what kind and how you do it?"
3. Participant responds, AI updates the gene and presents the new version
4. Repeat: AI targets the next weakest dimension
5. Loop ends after 2-3 rounds or when participant confirms "that's accurate"

**What it tests:** The refinement trajectory from natural language to computable gene, measured through round-by-round telemetry.

**Telemetry per round:**
- Specificity level per dimension (before → after)
- What the AI asked (which dimension, what question)
- What the participant provided (elaboration vs. correction vs. "looks good")
- Time spent per round
- Whether participant corrected the AI's extraction (pushed back on errors)

**Key measures:**
- **Refinement curve:** How many rounds to reach computable resolution (≥6)?
- **Dimension resistance:** Which dimensions resist refinement even with AI prompting?
- **Plateau detection:** Where do people stop providing useful additional detail?
- **Correction rate:** How often do participants fix AI errors vs. accept them?

**What this tells the platform:** If 80% of genes reach Level 3 on Technique after 1 round but Dosage never gets past Level 2 after 3 rounds, the platform knows Dosage needs a fundamentally different elicitation approach.

---

## What the Study Produces

### Scientific Findings (CHI Paper)

1. **Natural encoding resolution:** "Unprompted, participants encoded Technique at Level X, Dosage at Level Y, Mode at Level Z. N% achieved computable resolution (≥6) without any support."
2. **Nudge effectiveness by dimension:** "A textual nudge increased Dosage specificity from Level Y to Y' but had no effect on Technique (already high). Mode showed [pattern]."
3. **Refinement trajectory:** "AI-assisted refinement reached computable resolution in a median of N rounds. Technique reached Level 3 after 1 round; Dosage plateaued at Level 2 after 2 rounds; Mode [pattern]."
4. **Dimension asymmetry:** Which dimensions of self-care practices are cognitively salient vs. which require active elicitation?
5. **Boundary conditions:** Which types of stress-coping practices resist gene encoding even with AI support?

### Seed Gene Atlas (Dataset Contribution + ERC Deliverable)

The paper contributes a structured, openly published dataset: the first gene atlas for stress/anxiety self-care.

**Merging methodology (reported in paper):**
1. Two researchers independently cluster all Technique descriptions into canonical groups using the BCTO criterion: same physical actions at same abstraction level → same technique. "Box breathing" and "square breathing" merge; "box breathing" and "deep breathing" stay distinct.
2. Inter-rater agreement on clustering reported (Cohen's kappa). Edge cases adjudicated by third rater.
3. For each canonical technique, Dosage and Mode variants are catalogued but not collapsed — the variation within a technique IS the data.

**Atlas structure (per canonical gene):**
- Canonical technique label + natural language variants that mapped to it
- Frequency (how many participants described this technique)
- Dosage range (min/max/modal observed values)
- Mode variants observed
- Mean specificity profile (T, D, M levels)
- Source condition distribution (baseline / nudge / AI-refined)
- Context and outcome triples (from exploratory component, self-reported)

**Atlas-level analyses reported in paper:**
- Number of canonical techniques (the ontological collapse test: is it 15 or 150?)
- Technique frequency distribution (power-law test — the ERC proposal claims this, we verify it)
- Coverage: what proportion of stress/anxiety coping is represented?
- Specificity by technique: which practices are naturally well-specified vs. which resist encoding?

**Publication:** Dataset published on Zenodo/OSF, CC-BY, alongside the paper. Raw anonymised descriptions included for re-coding/extension.

---

## Exploratory Component: Context-Outcome Triples

**After all primary measures** (to prevent contamination), all participants answer:
- "What situation typically leads you to do this practice?" (context)
- "What do you typically notice afterward?" (outcome)

This collects self-reported context → gene → outcome triples. These are **reported associations, not causal claims.** They are the raw material that the future GENESIS platform would aggregate at scale. Useful for:
- Illustrative figures in the paper
- Demonstrating the data format the platform would produce
- Feeding into Study 2 design (if a second study explores DAG-like visualisations)

---

## Measures

### Primary DVs
| Measure | Type | Applied to |
|---------|------|-----------|
| Specificity profile (T, D, M each 0-3) | Blind-coded from text | Conditions 1, 2 |
| Gene computability score (0-9) | Derived | All conditions |
| Refinement gain per round | Telemetry | Condition 3 |
| Rounds to computable resolution | Telemetry | Condition 3 |

### Secondary DVs
| Measure | Type | Purpose |
|---------|------|---------|
| Semantic fidelity ("this captures my practice") | Likert 1-7 | Does specificity come at the cost of accuracy? |
| Forced-fit rating ("I had to distort my practice") | Likert 1-7 | Boundary conditions of gene concept |
| Semantic richness beyond T/D/M | Qualitative coding | What does the gene structure miss? |
| AI correction rate | Telemetry (Cond. 3) | How reliable is LLM extraction? |
| Time on task per round | Telemetry | Effort trajectory |
| Willingness to contribute to platform | Likert 1-7 | Platform design implication |
| Context and outcome triples | Open text | Exploratory — raw DAG material |

---

## Coding Protocol

Must be written and piloted before data collection:

1. **Specificity coding manual:** Explicit rules with examples for each level (0-3) of each dimension (T, D, M), grounded in TIDieR items and Michie ontologies. Include boundary cases.
2. **Two independent raters** code 20% of responses; iterate manual until Cohen's kappa > 0.75 per dimension.
3. **Full coding:** Both raters code all responses independently; third rater adjudicates disagreements.
4. **Pre-register** the coding rubric on OSF before Prolific launch.

---

## Validity Controls

- **Attention checks:** 2-3 embedded items + minimum response time threshold (90s for description task)
- **Practice plausibility:** Follow-up question requiring consistent answer ("How long have you been doing this?")
- **Demand characteristic check:** "Did you feel the study was trying to get you to respond in a particular way?"
- **LLM fallback (Condition 3):** Pre-generated extractions for API timeouts
- **Bot detection:** Prolific native checks + timing filters

---

## Power and Analysis

**Design:** 3 conditions, between-subjects, N≥300 (≥100 per condition). Sample size is a floor, not a ceiling — increase if justified.

**Primary analyses:**
- Condition 1 vs. 2: Ordinal logistic regression on specificity levels per dimension. For medium effect with N=200, power ~0.88.
- Condition 3 refinement: Within-subjects analysis of specificity gain across rounds. N=100 with 2-3 repeated measurements is well-powered for detecting within-person improvement.
- Dimension-level comparisons: Which of T/D/M is naturally highest? Which responds most to nudging? Which resists AI refinement?

**Exploratory:**
- Practice subtype analysis: Do some stress-coping practices reach computability more easily than others?
- Qualitative: Reflexive thematic analysis on what people mention beyond T/D/M
- AI extraction quality: How accurate are first-pass LLM extractions?

**Pre-registration:** OSF, before Prolific launch.

---

## Open Questions

1. **Gene normalisation / ontology collapse:** How do we prevent 300 participants from producing 300 unique genes? Need a principled approach to mapping natural language to canonical gene forms. E.g., "box breathing", "4-4-4 breathing", "square breathing" should collapse to one canonical Technique. This is critical for corpus value. **Needs literature review and design work.**
2. **AI refinement prompt design:** What questions should the AI ask in Condition 3? Need to pilot the prompts. Too directive = demand characteristics. Too open = no refinement.
3. **Number of refinement rounds:** 2-3 is a guess. Pilot to determine where diminishing returns set in.
4. **Study 2:** Potential second study exploring DAG-like visualisations of aggregated corpus data. Design TBD — depends on Study 1 results and the ontology collapse solution.
5. **Pre-registration:** OSF before Prolific launch.
6. **Corpus format:** How will the seed gene set be stored? Needs to be reusable for the GENESIS platform.

---

## Key Literature

| Paper | Relevance | DOI |
|-------|-----------|-----|
| Hoffmann et al. (2014) — TIDieR Checklist | Grounds the T/D/M specificity coding scheme | 10.1136/bmj.g1687 |
| Michie et al. (2013) — BCT Taxonomy v1 | Technique classification framework | 10.1007/s12160-013-9486-6 |
| Marques et al. (2021) — Mode of Delivery Ontology | Mode dimension specificity | 10.12688/wellcomeopenres.16224.2 |
| Yang et al. (2020) CHI — Re-examining Human-AI Interaction Design | Isolate AI from interface contributions | 10.1145/3313831.3376301 |
| Dhillon et al. (2024) CHI — Scaffolding in Co-writing with LMs | Scaffolding effects on quality and ownership | 10.1145/3613904.3642134 |
| Iarygina et al. (2024) IJHCS — Demand Characteristics in HCI | Demand characteristic risk | 10.1016/j.ijhcs.2024.103379 |
| Rapp & Cena (2016) IJHCS — Personal Informatics | Scaffolding shapes reports | 10.1016/j.ijhcs.2016.05.006 |
| Douglas et al. (2023) PLOS ONE — Online Data Quality | Prolific quality benchmarks | 10.1371/journal.pone.0279720 |
| Bhattacharjee et al. (2024) CHI — LLM Scaffolding for Behaviour Change | LLM scaffolding for personal behaviour | 10.1145/3613904.3642081 |
