# Research notes: practice normalisation and ontology design

**Status:** Research notes, not part of the locked study design. These notes inform interpretation of pilot results and shape the post-pilot atlas-construction step. The locked design is in `study_design_memo.md`; the analysis plan is in `analysis_plan_v1.md`.

**Last updated:** 2026-04-26

---

## The problem

When ≥300 people describe stress-coping practices in free text, we will get variants like:

- "box breathing" / "4-4-4 breathing" / "square breathing" / "deep breathing" / "breathwork"
- "20 minutes" / "about half an hour" / "when I feel like it"
- "alone" / "solo" / "by myself at home" / "in my room with headphones"

Some of these are genuinely different practices; others are the same practice described differently. The corpus is useless unless we can normalise, but over-normalising destroys meaningful distinctions. The ATLAS proposal calls this risk "ontological collapse."

## Key literature

### The BCT Ontology provides merge/split criteria (most directly relevant)

Marques et al. (2024) transformed BCTTv1's 93 flat techniques into a 281-node hierarchy across five levels. Their documented split criteria:

1. Demonstrably different mechanism of action.
2. Evidence that the distinction predicts different outcomes.
3. Independent annotators can reliably distinguish them (kappa > 0.7).

If none of these hold, descriptions are synonyms, not distinct techniques.

For ATLAS: this gives us a principled decision rule. "Box breathing" and "square breathing" → synonyms (same mechanism, same outcomes, annotators cannot reliably distinguish). "Box breathing" and "diaphragmatic breathing" → possibly distinct (different instructions, potentially different mechanisms).

### The Gene Ontology's synonym taxonomy

GO defines four synonym scopes that we adopt as coding vocabulary:

- **EXACT:** fully interchangeable ("box breathing" = "square breathing")
- **BROAD:** synonym is broader ("breathing exercise" for "box breathing")
- **NARROW:** synonym is more specific ("4-second box breathing" for "box breathing")
- **RELATED:** conceptually linked but not hierarchical ("mindful breathing" ↔ "diaphragmatic breathing")

For ATLAS: this vocabulary should appear in the canonical-clustering coding manual. When two descriptions differ, classify the relationship before deciding to merge or split.

### The Human Behaviour-Change Project: ML extraction reality check

West et al. (2023) trained NER on 512 annotated RCT reports. Mean F1 for automated BCT extraction = 0.42. The problem: natural-language descriptions of techniques are semantically diverse. Same technique, very different vocabulary.

For ATLAS: automated normalisation at scale is hard. The pilot's expert clustering phase is not just methodology, it is building the seed for any future automation. Every coding decision is a versioned ontological commitment.

### Different dimensions have different normalisation difficulty

- **Technique:** mid-granularity named entities. Some prior vocabulary exists (BCTTv1). LLM classification should work reasonably well.
- **Dosage:** mix of precise numbers ("20 minutes, 3x/week") and vague references ("when I need it"). Parsing problem more than classification problem.
- **Mode:** hardest. No prior vocabulary in any existing taxonomy. "Solo" / "app-guided" / "with therapist" / "outdoors" are structurally different types of modifiers; people interpret the dimension differently from each other.

## Approach for the pilot

Do not try to solve normalisation in the pilot. The pilot produces the raw data. Normalisation happens as a post-hoc analysis step.

What to do:

1. Collect raw free-text practice descriptions across all conditions, plus per-round AI-extracted practices for C3.
2. Have Prolific raters code per-dimension specificity (the primary study measure; see `study_design_memo.md`).
3. As an atlas-construction step: cluster Technique descriptions by semantic similarity.
4. Apply BCTO merge/split criteria (Marques 2024) to determine canonical labels. Two expert raters (Hosio + one coauthor); kappa reported; disagreements adjudicated jointly.
5. Report the resulting practice vocabulary and frequency distribution as a study output.

What NOT to do:

- Do not build a full ontology from 300 responses.
- Do not claim automated normalisation works (West et al. 2023 shows it does not, yet).
- Do not normalise Dosage and Mode into categories. Report the raw specificity levels and let patterns emerge.

## For the future ATLAS platform (out of scope here)

The pilot data becomes a seed for a normalisation pipeline that the broader ATLAS programme would build:

1. Embedding-based clustering to suggest synonym groups.
2. LLM-assisted classification (MapperGPT-style: fast lexical pass, then LLM for ambiguous cases).
3. Human validation gate (two-tier: crowd first, expert for edge cases).
4. Iterative ontology enrichment as new data arrives.

This is platform engineering, not pilot study work.

## Open questions

1. **Is Mode one dimension or three?** Delivery medium, social context, and physical setting may be distinct things people conflate. The pilot data will inform this.
2. **Power-law verification.** The proposal claims self-care follows a power-law distribution. The pilot will report the empirical distribution of Techniques after canonical clustering. With ≥300 responses and likely 20-50 canonical techniques, we treat power-law fit as exploratory rather than primary.
