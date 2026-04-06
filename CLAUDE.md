# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## EXTREMELY IMPORTANT: Scientific Rigour

**Never produce hand-wavy, theoretically indefensible claims.** This is a scientific project targeting CHI publication as part of an ERC Consolidator Grant. Every design choice, every mechanism, every causal claim must be theoretically grounded and defensible. If something cannot be justified rigorously, say so — do not dress it up in plausible-sounding language. If you are unsure whether a claim holds, flag it as uncertain rather than presenting it as fact. Simo is a professor and CHI subcommittee chair. Do not waste his time with nonsense.

## Project Overview

GENESIS Pilot — a three-condition between-subjects online experiment (N=300) that takes the first empirical step toward the GENESIS ERC application. The study validates whether behavioural genes ⟨Technique, Dosage, Mode⟩ are a natural cognitive structure for self-care, explores what minimal support helps people encode complete genes, and produces a seed gene corpus for a narrowly defined mental wellbeing domain (stress/anxiety coping).

See `docs/study_design_memo.md` for the current study design rationale and decisions.

### Conditions
- **Condition 1 (Pure baseline, n≥100):** Free-form text, no hints. Tests at what specificity level people naturally encode T/D/M.
- **Condition 2 (Textual nudge, n≥100):** Free-form text with verbal hints toward "what you do, how much, in what way." Tests whether minimal prompting increases specificity.
- **Condition 3 (AI-assisted refinement, n≥100):** Free text, then 2-3 rounds of iterative AI dialogue targeting the weakest dimension each round. Produces round-by-round telemetry of specificity improvement.

### Key Measurement: Specificity, Not Just Completeness
Each dimension (T, D, M) is coded on a 0-3 specificity scale (absent → vague → named → parameterised/computable), grounded in TIDieR and Michie ontologies. Gene computability score = sum of T+D+M (0-9). Threshold for "computable" gene: ≥6.

### Exploratory Component
After all primary measures: participants report context → gene → outcome triples (self-report, not causal inference). Collects the raw material that the future platform would aggregate at scale.

## Tech Stack

- **Backend:** PHP (no framework), SQLite database
- **Frontend:** Bootstrap
- **Hosting:** Railway
- **LLM Integration:** Claude API for real-time gene extraction (Condition 3)

## Key Domain Concepts

- **Behavioural Gene:** A structured unit ⟨T: Technique, D: Dosage, M: Mode⟩ representing a self-care practice
- **Gene Specificity:** Each dimension coded 0-3 (absent/vague/named/parameterised). Computability score = T+D+M (0-9).
- **Semantic Fidelity:** Whether captured T/D/M values accurately describe the practice (not just whether fields are filled)
- **DAGs:** Directed Acyclic Graphs expressing context → practice → outcome pathways. The ERC proposal uses these for causal reasoning at scale. The pilot collects self-reported triples as raw material — it does NOT construct or validate causal DAGs.
- **Ontology Collapse:** The risk that unconstrained natural language produces infinite gene variants. A critical open problem: how to normalise free-text descriptions into canonical gene forms.

## Study Parameters

- Target: 300 participants (100 per condition) is a starting point, not a ceiling. Suggest deviations if the design warrants it — more conditions, larger N, different splits are all fine if justified.
- Recruited via Prolific
- Domain: Stress/anxiety coping practices (narrowly defined)
- Duration: 9–13 minutes per participant
- Compensation: €3–4
- Primary DVs: Gene completeness (blind-coded), gap identification success rate, semantic fidelity
- Analysis: Chi-squared/ordinal logistic regression, binomial tests, reflexive thematic analysis
- Pre-registration: OSF before Prolific launch
