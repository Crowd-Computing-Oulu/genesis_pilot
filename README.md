# GENESIS Pilot: Unlocking the Genome of Everyday Self-Care

Self-care is universal yet scientifically fragmented. Millions of people exercise, meditate, journal, and breathe intentionally — but there is no shared computational language to describe, compare, or combine these practices across people and contexts. The science of self-care remains stuck in anecdotes and isolated findings.

**GENESIS** proposes a structural analogy from biology: just as genomics gave life sciences a formal language for genes, we aim to establish a formal grammar for everyday self-care. We call the basic unit a **behavioural gene** — a composable, computable representation of a self-care practice defined by three dimensions:

- **Technique** — *What* you do (e.g., box breathing, running, journaling)
- **Dosage** — *How much* (e.g., 20 minutes, 3x per week, moderate intensity)
- **Mode** — *In what form* (e.g., solo outdoors, app-guided, in a group class)

A morning jog becomes `⟨T: Running, D: 20min 3x/week, M: Solo outdoors⟩`. A guided breathing exercise becomes `⟨T: Box Breathing 4-4-4-4, D: 5min daily, M: Solo with timer app⟩`. The same formal structure captures both, making them comparable, combinable, and open to computational analysis.

## This Pilot Study

This pilot takes the first empirical step toward the GENESIS vision. We ask: **at what level of specificity do people naturally encode self-care practices, and what does it take to reach computable resolution through human-AI collaboration?**

We run a three-condition between-subjects online experiment (N≥300) focused on **stress and anxiety coping practices**.

### Conditions

| Condition | What participants do | What it tests |
|---|---|---|
| **1. Pure Baseline** (n≥100) | Describe a stress-coping practice in free text, no hints | At what specificity level do people naturally encode T, D, M? |
| **2. Textual Nudge** (n≥100) | Same free text, but prompt hints at "what you do, how much, in what way" | Does a minimal prompt push specificity from vague to computable? |
| **3. AI-Assisted Refinement** (n≥100) | Free text, then 2-3 rounds of iterative AI dialogue that targets the weakest dimension each round | What's the refinement trajectory? How many rounds to reach computable resolution? |

### Key Innovation: Specificity, Not Just Completeness

We don't just code whether T, D, M are present. We code **specificity** on a 0-3 scale per dimension (grounded in TIDieR and the Michie ontologies): from absent (0) to vague (1) to named (2) to fully parameterised/computable (3). A gene like `⟨Breathing, sometimes, alone⟩` scores 3/9. A gene like `⟨Box Breathing 4-4-4-4, 5min 2x/daily, Solo with timer app⟩` scores 9/9. The difference is what makes a gene computable.

### What We're After

1. **Natural encoding resolution:** How specific are people without any help? Where does natural specificity break down?
2. **Nudge vs. AI refinement:** What's the minimum intervention to reach computable genes?
3. **Refinement telemetry:** Round-by-round improvement data from Condition 3 — which dimensions respond to AI dialogue, which resist?
4. **A seed gene corpus:** Validated behavioural genes for stress/anxiety coping with full specificity profiles — the first tangible piece of the behavioural genome.

## Why This Matters

If self-care practices can be encoded at computable resolution through human-AI collaboration, the implications are significant:

- **For researchers:** Cross-study comparison becomes possible. Instead of parsing heterogeneous descriptions, you query structured genes at known specificity levels.
- **For the GENESIS platform:** We learn exactly how the AI assistant should work — which dimensions need active elicitation, how many refinement rounds to build into the flow, where people plateau.
- **For the field:** Self-care science becomes cumulative. Each contribution adds structured, comparable knowledge rather than another isolated anecdote.

This is exploratory, high-risk work. The proposal acknowledges the risk of "ontological collapse" — self-care may be too variable to formalise without losing what matters. This pilot is how we start finding out.

## Tech Stack

- PHP + SQLite
- Bootstrap frontend
- Claude API for real-time gene extraction and iterative refinement (Condition 3)
- Hosted on Railway

## Study Documents

- `docs/study_design_memo.md` — Detailed study design rationale and decisions
- `docs/pilot_idea.md.txt` — Original study concept (superseded by memo)
- `docs/proposal.tex` — ERC Consolidator Grant proposal (B1)

## Team

[Crowd Computing Group](https://www.oulu.fi/en/university/faculties-and-units/faculty-information-technology-and-electrical-engineering/crowd-computing), University of Oulu
