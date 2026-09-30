# Phase 22 — Public Experience Completion

Phase 22 completes the guest-facing information architecture without adding database tables or columns. Public navigation now connects Landing, Activity, Community, Opportunity, Program, global search, public Youth Portfolio, and certificate verification.

## Public Program boundary

A Program is public only when all of these conditions are true:

- `programs.execution_status` is `running` or `completed`;
- the Program's latest Proposal version has status `approved`;
- the owning Community has `review_status = approved` and `operational_status = active`.

The public Program response exposes descriptive Program fields, category, date range, owning Community, and linked Activity records that are themselves approved and published. It does not render Proposal files, requested budgets, review notes, logbooks or evidence, E-LPJ data or receipts, evaluation notes, reviewer identities, or version history.

## Landing and public statistics

Landing uses database-backed Interests, Activity, Community, Opportunity, Program, and aggregate statistics. Public statistics are derived at request time from the same public eligibility rules. They are labeled as a factual public-data summary and are not presented as broader government impact claims.

The generic Youth Portfolio panel is the only presentation-only landing content. It describes feature structure and is explicitly not a real Youth profile, testimonial, statistic, or persisted record. No fictional youth stories remain.

## Cross-link rules

- Activity links to its active, approved Community and links to Program only when that Program passes the public rule.
- Community shows only approved, published Activity and eligible public Program records.
- Opportunity links to a provider Community only when that relationship exists and the Community is active and approved.
- Public Portfolio links to Activity only when the Activity and its Community are public.
- Certificate verification links to Activity and Community only when their public eligibility checks pass.

## Privacy and performance

All detail pages resolve records through domain public scopes before rendering. UUID ownership fields and internal workflow data remain outside public serialization. Listing queries paginate and eager-load their card relationships; landing queries use bounded featured collections. Phase 22 adds no tracking, ranking, recommendation model, analytics event storage, or speculative schema.
