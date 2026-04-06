<?php

function get_db(): SQLite3 {
    $config = require __DIR__ . '/config.php';
    $dir = dirname($config['db_path']);
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }

    $db = new SQLite3($config['db_path']);
    $db->enableExceptions(true);
    $db->exec('PRAGMA journal_mode=WAL');
    $db->exec('PRAGMA foreign_keys=ON');

    init_schema($db);
    return $db;
}

function init_schema(SQLite3 $db): void {
    $db->exec("
        CREATE TABLE IF NOT EXISTS participants (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            prolific_pid TEXT UNIQUE NOT NULL,
            source TEXT NOT NULL DEFAULT 'web',
            condition_num INTEGER NOT NULL,
            started_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            completed_at DATETIME,
            completion_code TEXT NOT NULL
        );

        CREATE TABLE IF NOT EXISTS responses (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            participant_id INTEGER NOT NULL,
            step TEXT NOT NULL,
            prompt_shown TEXT NOT NULL,
            response_text TEXT NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (participant_id) REFERENCES participants(id)
        );

        CREATE TABLE IF NOT EXISTS gene_extractions (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            participant_id INTEGER NOT NULL,
            round INTEGER NOT NULL,
            technique TEXT,
            dosage TEXT,
            mode TEXT,
            technique_confidence TEXT,
            dosage_confidence TEXT,
            mode_confidence TEXT,
            targeted_dimension TEXT,
            ai_question TEXT,
            raw_llm_response TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (participant_id) REFERENCES participants(id)
        );

        CREATE TABLE IF NOT EXISTS questionnaire (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            participant_id INTEGER NOT NULL,
            semantic_fidelity INTEGER,
            forced_fit INTEGER,
            willingness INTEGER,
            interest INTEGER,
            context_text TEXT,
            outcome_text TEXT,
            fidelity_feedback TEXT,
            general_feedback TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (participant_id) REFERENCES participants(id)
        );
    ");
}
