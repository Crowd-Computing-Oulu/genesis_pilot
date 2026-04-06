# GENESIS Pilot — PHP Prototype Spec

**Date:** 2026-04-06
**Status:** Draft for review

---

## Purpose

A minimal working prototype of the GENESIS pilot study. Three conditions, participant flow from consent to debrief, AI-assisted refinement in Condition 3, SQLite storage, and an admin dashboard. Built to be looked at and iterated on — not production-ready for Prolific launch yet.

---

## Entry Point and Participant Routing

Single entry: `index.php`

**Prolific integration:**
- If `?PROLIFIC_PID=xxx` is present, store as participant ID
- If no PID, generate `web_<random8chars>` — easy to separate data sources later
- On completion, redirect to Prolific completion URL if Prolific PID, otherwise show a thank-you page

**Condition assignment:**
- Random 1/2/3 on first visit, stored in session + database
- Session cookie tracks participant state throughout the study
- Step-based routing via `?step=` parameter (consent, input, refinement, questionnaire, debrief)

---

## Participant Flow

### Step 1: Consent + Eligibility

- Brief information sheet (what the study is about, how data is used, GENESIS framing)
- Consent checkbox: "I agree to participate"
- Eligibility: "Are you 18 or older?" + "Do you have a stress/anxiety coping practice you've used in the past month?"
- If not eligible, polite exit screen

### Step 2: Practice Description (Condition-Specific)

**Common preamble (all conditions):**
> "Think of something specific you do to manage stress or anxiety."

**Condition 1 (Pure Baseline):**
> "Describe this practice in your own words. Tell us whatever feels important about what you do."
- Large free-text textarea, no hints, no structure
- Submit button

**Condition 2 (Textual Nudge):**
> "Describe this practice in your own words. Try to describe: what exactly you do, how much or how often, and in what way or setting."
- Same large free-text textarea
- Submit button

**Condition 3 (AI Coach — Round 0):**
> "Describe this practice in your own words. Tell us whatever feels important about what you do."
- Same prompt as Condition 1 (no nudge — we want the baseline free text first)
- Submit button → triggers AI extraction

### Step 3: AI Coach Refinement (Condition 3 Only)

**Round 1:**
- Screen shows: "Based on your description, here's what we captured about your practice:"
- Three labelled fields displayed (read-only):
  - **What you do:** [extracted technique or "not clearly captured"]
  - **How much/how often:** [extracted dosage or "not clearly captured"]
  - **In what way:** [extracted mode or "not clearly captured"]
- The weakest dimension is visually highlighted (amber/empty)
- Below: AI coach prompt targeting the weakest dimension:
  > "We'd love to know more about [specific aspect]. Can you describe [targeted question]?"
- Text input for participant's response
- Submit → AI updates extraction

**Round 2:**
- Same layout, updated extraction shown
- Next weakest dimension highlighted
- Another targeted question
- Submit → AI updates extraction

**Round 3 (Final confirmation):**
- "Here's your complete practice description:"
- All three fields shown (should be more complete now)
- Participant can: "Looks good" or "I'd like to adjust something" (free-text edit box)
- Submit → final version stored

**Telemetry logged per round:**
- Round number
- Which dimension was targeted
- AI question shown
- Participant response text
- T/D/M extraction before and after
- Timestamp

### Step 4: Semantic Fidelity Check (All Conditions)

For Conditions 1 and 2, the AI extracts T/D/M from the free text (not shown to participant during input — extracted post-submission for researcher analysis). Then all participants see:

> "Based on what you described, here's a structured summary of your practice:"
> **What you do:** [T] | **How much/how often:** [D] | **In what way:** [M]

- "How well does this capture your actual practice?" (Likert 1-7: Not at all — Perfectly)
- "Did you have to leave out or distort anything important to describe your practice?" (Likert 1-7: Not at all — Very much)
- Optional: "What's missing or wrong?" (free text)

### Step 5: Context and Outcome (Exploratory, All Conditions)

> "What situation typically leads you to do this practice?"
- Free text

> "What do you typically notice afterward?"
- Free text

### Step 6: Brief Questionnaire (All Conditions)

- "How willing would you be to contribute descriptions like this to a public self-care knowledge base?" (Likert 1-7)
- "How interesting did you find this activity?" (Likert 1-7)
- Optional: "Any thoughts on the experience?" (free text)

### Step 7: Debrief

- Brief explanation of the GENESIS project and how their contribution helps
- Completion code displayed
- If Prolific PID: auto-redirect link to Prolific completion URL
- If web participant: thank-you message

---

## Data Model (SQLite)

```sql
CREATE TABLE participants (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    prolific_pid TEXT UNIQUE NOT NULL,
    source TEXT NOT NULL DEFAULT 'web',  -- 'prolific' or 'web'
    condition INTEGER NOT NULL,          -- 1, 2, or 3
    started_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    completed_at DATETIME,
    completion_code TEXT NOT NULL
);

CREATE TABLE responses (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    participant_id INTEGER NOT NULL,
    step TEXT NOT NULL,           -- 'initial_description', 'refinement_r1', 'refinement_r2', 'refinement_r3', 'fidelity_missing'
    prompt_shown TEXT NOT NULL,
    response_text TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (participant_id) REFERENCES participants(id)
);

CREATE TABLE gene_extractions (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    participant_id INTEGER NOT NULL,
    round INTEGER NOT NULL,      -- 0 = initial extraction, 1-3 = refinement rounds
    technique TEXT,
    dosage TEXT,
    mode TEXT,
    targeted_dimension TEXT,      -- which dimension the AI asked about (NULL for round 0)
    ai_question TEXT,             -- the question shown to participant (NULL for round 0)
    raw_llm_response TEXT,        -- full LLM API response for debugging
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (participant_id) REFERENCES participants(id)
);

CREATE TABLE questionnaire (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    participant_id INTEGER NOT NULL,
    semantic_fidelity INTEGER,    -- 1-7
    forced_fit INTEGER,           -- 1-7
    willingness INTEGER,          -- 1-7
    interest INTEGER,             -- 1-7
    context_text TEXT,            -- "what situation leads you to do this"
    outcome_text TEXT,            -- "what do you notice afterward"
    fidelity_feedback TEXT,       -- optional "what's missing or wrong"
    general_feedback TEXT,        -- optional "any thoughts"
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (participant_id) REFERENCES participants(id)
);
```

---

## Admin Dashboard

**Access:** `/admin.php?key=<configured_password>`

Password stored in a `config.php` file (gitignored). If key doesn't match, show 403.

### Overview Page
- Total participants, N per condition, completion rate
- Average completion time per condition
- Simple bar chart or table — no JS charting library needed, HTML/CSS bars are fine

### Participants Table
- Sortable table: PID, source, condition, started, completed, completion time
- Click any row → detail view

### Participant Detail View
- Full response chain: initial description → each refinement round (Cond 3) → questionnaire
- Gene extractions per round shown side by side (shows the refinement trajectory)
- Raw LLM responses expandable (for debugging)

### Export
- **Participants CSV:** All participant metadata
- **Responses CSV:** All responses with participant info joined
- **Gene Extractions CSV:** All extractions with participant info joined
- **Questionnaire CSV:** All questionnaire data with participant info joined
- One "Export All" button that downloads a ZIP of all four CSVs

---

## File Structure

```
app/
  index.php              -- Entry point, step router
  admin.php              -- Admin dashboard
  config.php             -- DB path, API key, admin password (gitignored)
  config.example.php     -- Template for config
  db.php                 -- SQLite connection + schema init
  llm.php                -- Claude API wrapper (extraction + refinement prompts)
  steps/
    consent.php          -- Consent + eligibility screen
    input.php            -- Practice description (all conditions)
    refinement.php       -- AI coach rounds (Condition 3 only)
    fidelity.php         -- Semantic fidelity check
    exploratory.php      -- Context + outcome
    questionnaire.php    -- Likert scales + feedback
    debrief.php          -- Completion code + redirect
  templates/
    header.php           -- Bootstrap head, nav
    footer.php           -- Bootstrap scripts, closing tags
  assets/
    style.css            -- Minimal custom styles
```

---

## Tech Decisions

- **PHP 8+**, no framework, no Composer dependencies (except possibly for HTTP client if PHP's built-in stream context isn't enough for Claude API)
- **SQLite** — single file, no setup, Railway-compatible
- **Bootstrap 5 via CDN** — no build step
- **Claude API** — direct HTTP calls from `llm.php`, structured JSON prompts for extraction
- **Sessions** — PHP native sessions for participant state tracking
- **No JS framework** — vanilla JS only where needed (e.g., form validation, progress indication during API calls)

---

## LLM Integration (Condition 3)

### Extraction Prompt (Round 0)

Given a free-text description, extract T/D/M. Return JSON:
```json
{
  "technique": {"value": "...", "confidence": "high|medium|low"},
  "dosage": {"value": "...", "confidence": "high|medium|low"},
  "mode": {"value": "...", "confidence": "high|medium|low"}
}
```

The lowest-confidence dimension becomes the target for Round 1.

### Refinement Prompt (Rounds 1-2)

Given: original description, current extraction, participant's latest response, and which dimension to target.

Returns: updated extraction (same JSON format) + a natural-language question for the next round targeting the next weakest dimension.

### Fidelity Extraction (Conditions 1-2)

Same extraction prompt as Round 0, run after the participant submits. Not shown during input — only shown in the fidelity check step. This ensures Conditions 1-2 participants also get gene extractions for the corpus, without the extraction influencing their input.

---

## Not In This Build

- Attention checks (add before Prolific launch)
- LLM timeout fallback / retry logic (add before launch)
- Rate limiting
- GDPR-specific data handling (consent text needs legal review)
- Practice type categorisation dropdown
- Demand characteristic check question
- Timer/minimum response time enforcement
- Mobile-specific optimisation (Bootstrap handles basics)
