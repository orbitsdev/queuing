📑 Kiosqueeing — Final Branch-Specific Settings Implementation Guide

✅ Purpose

This document explains how to manage per-branch ticket settings correctly: each branch can have its own real ticket prefix (like QUE) and other queue configurations. Defaults are seeded once and always cloned for new branches via an Observer.

✅ Database Structure

Table: settings
- id — PK
- branch_id — nullable FK (NULL = global)
- key — e.g., ticket_prefix
- value — the real prefix (like QUE)
- timestamps

Unique index: (branch_id, key)

✅ Keys & Expected Values

| Key | Example |
|-----|---------|
| ticket_prefix | QUE |
| print_logo | true |
| queue_reset_daily | true |
| queue_reset_time | 00:00 |
| default_break_message | On break, please proceed to another counter. |

✅ Seeder

- Seeds global default (branch_id = NULL)
- Copies defaults to all existing branches

✅ Observer

- Runs automatically when a new branch is created
- Clones all global defaults into the new branch scope

✅ Reading Logic

Always read branch-specific first → if not found → use global fallback

✅ Implementation

Migration:
- branch_id FK nullable
- key unique with branch_id

Seeder:
- Insert global (branch_id = NULL)
- Copy for each existing branch

BranchObserver:
- On created, get all global settings and copy them to the new branch

AppServiceProvider:
- Register observer: Branch::observe(BranchObserver::class);

Livewire / Controller:
- When reading: query where branch_id = ? or branch_id IS NULL and key = ?

✅ Result

- Each branch has its own ticket_prefix like QUE
- Ticket numbers look like QUE001, QUE002
- No placeholder literals like {branch}

✅ Final Note

This approach is robust, simple, and covers:
- Global defaults
- Branch-specific overrides
- Automatic branch onboarding
- Clean fallback logic
