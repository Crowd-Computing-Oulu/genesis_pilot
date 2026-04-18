# Ideas: Gene Normalisation and Ontology Design

**Status:** Research notes — not part of the study design, but critical for interpreting results and building the platform.

---

## The Problem

When 300 people describe stress-coping practices in free text, we'll get variants like:
- "box breathing" / "4-4-4 breathing" / "square breathing" / "deep breathing" / "breathwork"
- "20 minutes" / "about half an hour" / "when I feel like it"
- "alone" / "solo" / "by myself at home" / "in my room with headphones"

Some of these are genuinely different practices; others are the same practice described differently. The corpus is useless unless we can normalise — but over-normalising destroys meaningful distinctions. The ERC proposal calls this "ontological collapse."

## Key Literature

### The BCT Ontology provides merge/split criteria (most directly relevant)

Marques et al. (2024) transformed BCTTv1's 93 flat techniques into a 281-node hierarchy across five levels. Their documented split criteria:
1. Demonstrably different mechanism of action
2. Evidence that the distinction predicts different outcomes
3. Independent annotators can reliably distinguish them (kappa > 0.7)

If none of these hold, descriptions are synonyms, not distinct techniques.

**For ATLAS:** This gives us a principled decision rule. "Box breathing" and "square breathing" → synonyms (same mechanism, same outcomes, annotators can't distinguish). "Box breathing" and "diaphragmatic breathing" → possibly distinct (different instructions, potentially different mechanisms).

### The Gene Ontology's synonym taxonomy

GO defines four synonym scopes:
- **EXACT:** Fully interchangeable ("box breathing" = "square breathing")
- **BROAD:** Synonym is broader ("breathing exercise" for "box breathing")
- **NARROW:** Synonym is more specific ("4-second box breathing" for "box breathing")
- **RELATED:** Conceptually linked but not hierarchical ("mindful breathing" ↔ "diaphragmatic breathing")

**For ATLAS:** This vocabulary should be adopted for the coding manual. When two descriptions differ, classify the relationship before deciding to merge or split.

### The Human Behaviour-Change Project: ML extraction reality check

West et al. (2023) trained NER on 512 annotated RCT reports. Mean F1 for automated BCT extraction = 0.42. The problem: natural language descriptions of techniques are semantically diverse. Same technique, very different vocabulary.

**For ATLAS:** Automated normalisation at scale will be hard. The pilot's human coding phase is not just methodology — it's building the training set for future automation. Every coding decision is a versioned ontological commitment.

### Different dimensions have different normalisation difficulty

- **Technique:** Mid-granularity named entities. Some vocabulary exists (BCTTv1). LLM classification should work reasonably well.
- **Dosage:** Mix of precise numbers ("20 minutes, 3x/week") and vague references ("when I need it"). Parsing problem more than classification problem.
- **Mode:** Hardest. No prior vocabulary in any existing taxonomy. "Solo" / "app-guided" / "with therapist" / "outdoors" are structurally different types of modifiers. People may interpret the dimension differently from each other.

## Proposed Approach for the Pilot

**Don't try to solve normalisation in the pilot.** The pilot produces the raw data. Normalisation is a post-hoc analysis step.

**What to do:**
1. Collect raw free-text descriptions (all conditions) + structured genes (Conditions 2, 3)
2. Have two raters independently code specificity levels (the primary study measure)
3. As a secondary analysis: cluster Technique descriptions by semantic similarity
4. Report the distribution — how many unique techniques? Is it power-law? (The ERC claims it is, citing prior work — we need to verify or provide the first evidence)
5. For each cluster, apply the BCTO merge/split criteria to determine canonical labels
6. Report the resulting gene vocabulary as a study output

**What NOT to do:**
- Don't build a full ontology from 300 responses
- Don't claim automated normalisation works (West et al. 2023 shows it doesn't yet)
- Don't normalise Dosage and Mode into categories — report the raw specificity levels and let the patterns emerge

## For the Platform (Later)

The pilot data becomes the seed for a normalisation pipeline:
1. Embedding-based clustering to suggest synonym groups
2. LLM-assisted classification (MapperGPT-style: fast lexical pass, then LLM for ambiguous cases)
3. Human validation gate (two-tier: crowd first, expert for edge cases)
4. Iterative ontology enrichment as new data arrives

But this is platform engineering, not pilot study work.

## Open Questions

1. **Is Mode one dimension or three?** Delivery medium, social context, and physical setting might be distinct things that people conflate. The pilot data will tell us.
2. **Power-law verification:** The ERC proposal claims self-care follows a power-law distribution. The pilot should report the empirical distribution of techniques as a finding.
3. **Granularity floor:** What's the minimum specificity level needed for a gene to be "computable"? Our threshold of 6/9 is a starting hypothesis. The pilot data should validate or revise it.
