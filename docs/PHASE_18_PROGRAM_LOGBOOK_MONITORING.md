# Phase 18 — Program Logbook & Monitoring

Phase 18 implements deliberate Program execution start, Program Logbooks, private supporting media, factual monitoring, and the approved Verifier Logbook review workflow. The schema follows `sipora_v2_mysql_backend_driven_clean_v2.sql` exactly.

## Execution and lifecycle

A contextual leader or manager may move a Program from `planned` to `running` only when its latest Proposal is `approved` and at least one Activity is linked. Starting a Program never changes Proposal or Activity state. Program completion remains deferred.

Logbooks use `draft`, `submitted`, `revision`, and `approved`. Managers may mutate only draft or revision entries while the Program is running. Verifiers may approve or request revision for submitted entries; revision requires a note. SQL and the PRD explicitly provide review fields and the `review logbooks` responsibility, so the minimal review workflow is included.

## Monitoring and progress

`ProgramProgressService` returns factual counts: linked Activities, completed Activities, Logbook count, latest Logbook date, and the latest manager-reported `progress_percent`. It does not calculate a hidden score, apply weighting, mutate state, or complete a Program.

## Media and privacy

Logbook media is stored on the private `program_logbook_media` disk with randomized filenames. Uploads are restricted to JPG, PNG, WebP, and PDF up to 8 MB. Files are delivered only through authorized routes with private/no-store and `nosniff` headers. Paths are hidden from serialization.

Financial reports, financial items, E-LPJ, Program evaluation, public Program discovery, and completion remain outside Phase 18.
