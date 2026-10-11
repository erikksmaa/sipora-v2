# SIPORA competition demo

Run on the local development database `sipora`:

```sh
php artisan migrate
php artisan db:seed --class=CompetitionDemoSeeder
```

The orchestrator refuses production/non-local environments and non-`sipora` databases. Testing is allowed for an isolated test database. It bootstraps taxonomy and missing Admin/Verifier accounts, then calls `DemoYouthSeeder`, `DemoEcosystemSeeder`, and `DemoDiscussionSeeder`. It does not run the broad default `DatabaseSeeder` or change application rules/schema.

Demo account: `youth1@sipora.test`, password `password`. Other new accounts are `demo.youth01@sipora.test` through `demo.youth23@sipora.test` with the same password. Existing account passwords are preserved. These are local demo credentials only.

## Seed-owned data

| Domain | Records |
| --- | ---: |
| Youth profiles | 24 |
| Community | 15 |
| Contextual active memberships | 81 |
| Program | 15 (7 running, 4 planned, 3 completed, 1 cancelled) |
| Program proposals | 15 |
| Activity | 15 (5 completed, 1 ongoing, 9 scheduled) |
| Activity sessions | 27 |
| Accepted participations | 144 |
| Opportunity | 15 (13 available, 2 expired) |
| Forum categories | 9 existing taxonomy categories |
| Forum threads / replies / helpful reactions | 17 / 46 / 48 |
| Social posts / likes / comments | 17 / 57 / 33 |
| Social follows | 30 |
| Forum / Social reports | 3 / 2 |
| Certificates issued by existing action | 12 |
| Additional explicit notifications | 5, plus 12 certificate notifications on first issue |

Totals in an existing database include earlier seeders. Natural keys are used for domain records; interaction keys enforce unique likes, follows, and reactions. Discussion content is created once so moderation state is preserved on rerun. The main seed runs in a database transaction. Proposal PDF storage is repeatable and reused. Dates are relative to the seed day, keeping available recommendations useful; attendance is only recorded for sessions whose end has passed. Existing sample records with the canonical Community/Program/Activity slugs are enriched rather than duplicated. An older archived seminar can retain the same display title as the Passport seminar: it is a separate historical record and is not deleted or republished.

No real identity number or identity document is created. Twenty Youth fixtures are verified; four are unverified, and some lack bio/portfolio baseline. Public/private portfolios vary, existing privacy projections remain authoritative, and the public statistics/map threshold remains `<5`.

## Primary journey

Erik manages Komunitas Pemuda Digital Pemalang with a contextual membership. Workshop Web Development untuk Pemuda is linked to Pemalang Youth Digital Empowerment 2026. The five completed histories are derived through `ActivityPassportService`, not stored as Passport rows:

| Activity | Erik attendance | Certificate |
| --- | --- | --- |
| Workshop Web Development untuk Pemuda | 3/3 | Yes |
| Pelatihan Public Speaking Dasar | 2/2 | Yes |
| Volunteer Pemalang Youth Festival | 1/1, volunteer | Yes |
| Seminar Kewirausahaan Digital | 1/1 | Yes |
| Bootcamp Desain Konten Digital | 3/4 | No |

The existing certificate action requires accepted/completed participation and certificate-enabled Activity; there is **no percentage-attendance certificate threshold** in the current domain. The Bootcamp intentionally disables certificates. Other completed participants and pending completion cases remain available for operational testing. Workshop attendance for the 20 seed-owned participants is 16/2/2, 18/1/1, and 17/3/0 (present/absent/excused). Earlier participants are retained, so development-table totals may be larger. The workshop quota is 60 to accommodate existing fixtures.

Erik has interests/skills and unjoined, unregistered future items available for Pathway. Follow clusters separate tech, design, volunteering, and entrepreneurship. Community post authors are the corresponding contextual leaders. Reactions and replies have varied distributions, including threads without replies and posts without comments. Only a few reports/notifications are added.

## Validation

`CompetitionDemoSeederTest` seeds twice and checks unchanged entity/interaction/notification counts, production refusal, contextual roles, identity privacy, Passport/certificate eligibility, exact seed-owned workshop attendance, Pathway recommendations, different all/following feed totals, aggregate metrics, and HTTP responses for the demo pages.

Run tests only on an isolated MySQL database. The existing repository test configuration defaults to `sipora` with `RefreshDatabase`, so do not run the default suite against the demo database.

Manual checks: `/feed`, `/forum`, `/activities`, `/communities`, `/opportunities`, `/youth/development-pathway`, `/youth/passport`, Passport detail, `/statistics`, and `/ecosystem-map`. The local populated development database produced 61 all-feed entries versus 5 following entries for Erik, and 4 recommendations in each Pathway group.

Development query sanity check (single local run; database already contains previous fixtures):

| Query layer | Queries | SQL time | Wall time |
| --- | ---: | ---: | ---: |
| Public statistics | 30 | 145 ms | 218 ms |
| Development Pathway | 32 | 59 ms | 139 ms |
| All feed page | 8 | 21 ms | 39 ms |
| Following feed page | 15 | 35 ms | 59 ms |

These timings are local observations, not production performance guarantees. No application query logic or frontend dependencies were changed.

Final checks: PHP syntax passed for all four new seeders and the new test. Targeted suite: 2 tests / 64 assertions passed. Full regression: 288 tests / 2323 assertions passed on isolated sipora_attendance_test (5m 7s). Vite production build passed (77 modules). Development migrations were already current; repeat seeding completed without FK or unique constraint errors. Browser smoke checks, Passport detail, certificate link, and map rendering passed. No schema migration was added.
