# GENESIS Pilot: Unlocking the Genome of Everyday Self-Care

Self-care is universal yet scientifically fragmented. Millions of people exercise, meditate, journal, and breathe intentionally — but there is no shared computational language to describe, compare, or combine these practices across people and contexts. The science of self-care remains stuck in anecdotes and isolated findings.

**GENESIS** proposes a structural analogy from biology: just as genomics gave life sciences a formal language for genes, we aim to establish a formal grammar for everyday self-care. We call the basic unit a **behavioural gene** — a composable, computable representation of a self-care practice defined by three dimensions:

- **Technique** — *What* you do (e.g., box breathing, running, journaling)
- **Dosage** — *How much* (e.g., 20 minutes, 3× per week)
- **Mode** — *In what form* (e.g., solo, app-guided, with a group)

A morning jog becomes `⟨T: Running, D: 20min, M: Solo⟩`. A guided breathing exercise becomes `⟨T: Box Breathing, D: 5min, M: App-Guided⟩`. The same formal structure captures both, making them comparable, combinable, and open to computational analysis.

## This Pilot Study

This is an exploratory first step. Before we can build a large-scale citizen-science platform, we need to answer a fundamental HCI question: **can ordinary people reliably co-create structured behavioural genes from their own lived experience, when supported by the right interface and real-time AI assistance?**

We run a between-subjects online experiment (N ≈ 300) comparing two ways of describing a recent self-care practice:

| | Condition A (Baseline) | Condition B (Gene-Structured) |
|---|---|---|
| **Input** | Free-form text box | Three visual cards: Technique, Dosage, Mode |
| **Rationale** | Current norm in digital health apps | The novel interface we're testing |

Both conditions then go through:
1. **AI validation** — An LLM generates a structured gene displayed as editable visual cards. Participants verify, edit, or accept.
2. **Gene-splicing preview** — Participants see their gene composed with another and reflect on the idea of computationally combining self-care practices.

We measure usability, perceived agency, trust in the AI encoding, gene completeness, and qualitative design feedback.

## Why This Matters

If self-care practices can be meaningfully decomposed into formal genes, the implications are significant:

- **For researchers:** Cross-study comparison becomes possible for the first time. Instead of parsing heterogeneous study descriptions, you query structured genes.
- **For practitioners and developers:** Evidence-informed recommendations grounded in context — not "try meditation" but specific practice parameters matched to specific situations.
- **For the field:** Self-care science becomes cumulative. Each contribution adds to a shared knowledge base rather than producing another isolated finding.

This is exploratory, high-risk work. Human self-care may be too contextual, too culturally embedded, or too personal to formalise without losing what matters. This pilot is how we start finding out.

## Tech Stack

- PHP + SQLite
- Bootstrap frontend
- Hosted on Railway

## Team

[Crowd Computing Group](https://www.oulu.fi/en/university/faculties-and-units/faculty-information-technology-and-electrical-engineering/crowd-computing), University of Oulu
