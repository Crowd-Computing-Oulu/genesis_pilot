# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## EXTREMELY IMPORTANT: Scientific Rigour

**Never produce hand-wavy, theoretically indefensible claims.** This is a scientific project targeting CHI publication as part of an ERC Consolidator Grant. Every design choice, every mechanism, every causal claim must be theoretically grounded and defensible. If something cannot be justified rigorously, say so — do not dress it up in plausible-sounding language. If you are unsure whether a claim holds, flag it as uncertain rather than presenting it as fact. Simo is a professor and CHI subcommittee chair. Do not waste his time with nonsense.

## Project Overview

ATLAS Pilot — a three-condition between-subjects online experiment (N≥300) that takes the first empirical step toward the ATLAS ERC application. The study validates whether behavioural genes ⟨Technique, Dosage, Mode⟩ are a natural cognitive structure for self-care, explores what minimal support helps people encode complete genes, and produces a seed gene atlas for stress/anxiety coping.

### Conditions
- **Condition 1 (Pure baseline):** Free-form text, no hints, no AI. Tests at what specificity level people naturally encode T/D/M.
- **Condition 2 (Textual nudge):** Free-form text with verbal hints toward "what you do, how much, in what way." No AI interaction. Tests whether minimal prompting increases specificity.
- **Condition 3 (AI coach):** Free text (same prompt as Cond 1), then 2 rounds of iterative AI dialogue. AI extracts a gene, highlights the weakest dimension, asks a targeted follow-up. Participant responds, AI refines. Produces round-by-round telemetry.

**Key difference:** Conditions 1-2 have NO AI interaction during the study. AI extraction for those conditions happens post-hoc by researchers. Only Condition 3 involves live AI.

### Study Flow (as implemented in app)
1. Consent + eligibility
2. Practice description (condition-specific prompt)
3. [Condition 3 only] AI coach refinement (2 rounds)
4. Fidelity check — Cond 1-2 see their raw text back; Cond 3 sees structured gene
5. Context + outcome (exploratory, self-reported triples)
6. Questionnaire (willingness, interest, feedback)
7. Debrief + completion code

### Key Measurement: Specificity, Not Just Completeness
Each dimension (T, D, M) is coded on a 0-3 specificity scale (absent → vague → named → parameterised/computable), grounded in TIDieR and Michie ontologies. Gene computability score = sum of T+D+M (0-9). Threshold for "computable" gene: ≥6. Coding is done post-hoc by researchers, not by the app.

### Dataset Contribution: Seed Gene Atlas
The paper contributes a structured, openly published dataset. Post-collection, researchers cluster technique descriptions into canonical groups using BCTO merge/split criteria, catalogue dosage/mode variants, and publish on Zenodo/OSF.

## Key Files and Folders

```
app/                        -- PHP web application
  index.php                 -- Entry point, step router, session management
  admin.php                 -- Admin dashboard (stats, participant details, exports, DB download)
  config.php                -- Local config: DB path, API key, admin password (GITIGNORED)
  config.example.php        -- Template for config
  db.php                    -- SQLite connection + schema init
  llm.php                   -- Claude API wrapper (extraction + refinement prompts)
  steps/                    -- One PHP file per study step
    consent.php             -- Consent + eligibility + condition assignment
    input.php               -- Practice description (all conditions)
    refinement.php          -- AI coach rounds (Condition 3 only)
    fidelity.php            -- Review + fidelity Likert scales
    exploratory.php         -- Context + outcome triples
    questionnaire.php       -- Final Likert scales + feedback
    debrief.php             -- Completion code + Prolific redirect
  templates/                -- header.php, footer.php (Bootstrap layout)
  assets/style.css          -- Custom styles
  data/                     -- SQLite database file (GITIGNORED)

docs/
  study_design_memo.md      -- Current study design rationale and decisions
  ideas_ontology_normalisation.md -- Research notes on gene normalisation
  pilot_idea.md.txt         -- Original study concept (superseded)

paper/                      -- CHI paper (separate git repo, syncs with Overleaf)
  paper.tex                 -- Paper structure with section outlines
  references.bib            -- Scratchpad bibliography
  context/                  -- All the related work of the paper here as .md files
```

## Tech Stack

- **Backend:** PHP 8+ (no framework), SQLite database
- **Frontend:** Bootstrap 5 via CDN
- **Hosting:** Railway
- **LLM Integration:** Claude API for Condition 3 only (real-time extraction + refinement)

## Admin Dashboard

Access: `/admin.php?key=<admin_key>`
- Overview stats, per-condition breakdown
- Test links for all 3 conditions (test_ prefix = no DB writes)
- Participant table with delete button (removes all associated data)
- Detail view per participant (full response chain, gene extraction trajectory)
- CSV exports (participants, responses, gene_extractions, questionnaire)
- Raw SQLite DB download

## Key Domain Concepts

- **Behavioural Gene:** A structured unit ⟨T: Technique, D: Dosage, M: Mode⟩ representing a self-care practice
- **Gene Specificity:** Each dimension coded 0-3 (absent/vague/named/parameterised). Computability score = T+D+M (0-9).
- **Semantic Fidelity:** Whether captured T/D/M values accurately describe the practice
- **Ontology Collapse:** The risk that unconstrained natural language produces infinite gene variants. Addressed by post-hoc merging using BCTO criteria.

## Study Parameters

- Target: ≥300 participants (≥100 per condition), not a ceiling
- Recruited via Prolific + web (dual source tracked via PID prefix)
- Domain: Stress/anxiety coping practices
- Duration: ~10 minutes per participant
- Compensation: €3-4
- Pre-registration: OSF before Prolific launch
