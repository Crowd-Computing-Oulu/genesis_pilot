# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

GENESIS Pilot — a between-subjects online experiment (N≈300) testing whether a structured "behavioural gene" interface (Technique + Dosage + Mode) helps people encode self-care practices into computable units better than free-form text input. Part of an ERC Consolidator Grant application, targeting a CHI publication.

The pilot has two conditions:
- **Condition A (baseline):** Free-form text input
- **Condition B (gene-structured):** Three visual cards (Technique, Dosage, Mode) with LLM pre-filling and editable cards

Both conditions include an LLM validation step (editable gene cards) and a gene-splicing preview task.

See `docs/pilot_idea.md.txt` for the full study design document.

## Tech Stack

- **Backend:** PHP (no framework), SQLite database
- **Frontend:** Bootstrap
- **Hosting:** Railway
- **LLM Integration:** Claude API for real-time gene generation/pre-filling

## Key Domain Concepts

- **Behavioural Gene:** A structured unit ⟨T: Technique, D: Dosage, M: Mode⟩ representing a self-care practice
- **Gene Splicing:** Composing two behavioural genes into a hybrid routine (forward-looking feature previewed in the study)
- **Gene Completeness:** Primary measure — how many of the 3 dimensions (T/D/M) are captured (0–3 scale)

## Study Parameters

- Target: 300 participants (150 per condition), recruited via Prolific
- Duration: 9–13 minutes per participant
- Compensation: €3–4
- Measures: SUS usability scale, gene completeness, interaction logs, Likert scales (agency, trust, enjoyment), qualitative feedback
- Analysis: χ², t-tests, equivalence testing, reflexive thematic analysis
