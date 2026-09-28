# SIPORA v2 Development Data

Data in this document is for local development only. It is not production data, and the credentials must never be used in production.

## Local database

The canonical local database is `sipora`. Rebuild it with:

```bash
php artisan migrate:fresh --seed
```

The PHPUnit suite also targets `sipora` by project decision and uses `RefreshDatabase`. Running the full suite rebuilds the schema, so run `php artisan migrate:fresh --seed` afterward to restore the demo dataset.

The modular development seeders are skipped when the application environment is `production`.

## Demo accounts

All demo accounts use the development-only password `password`.

| Account | Email | Global role | Suggested scenario |
| --- | --- | --- | --- |
| Admin | `admin@sipora.test` | `admin` | Personal identity review |
| Verifier | `verifier@sipora.test` | `verifier` | Community and Activity review |
| Youth 1 | `youth1@sipora.test` | `youth` | Leader workflow, near-complete profile, verified identity |
| Youth 2 | `youth2@sipora.test` | `youth` | Manager and participant workflow, pending identity |
| Youth 3 | `youth3@sipora.test` | `youth` | Cross-Community authorization and revision identity |
| Youth 4 | `youth4@sipora.test` | `youth` | Pending membership and attendance examples |
| Youth 5 | `youth5@sipora.test` | `youth` | Rejected/left membership and cancelled registration examples |

Identity numbers and documents created by the seeders are explicitly fake development fixtures. Activity categories are temporary development values until official Dindikpora categories are approved.

## Phase 12 manual scenarios

- Sign in as `youth3@sipora.test`, open Pemuda Olahraga Pemalang, then open Bootcamp Digital Pemalang participants. Attendance evidence is available for three accepted participants. Youth 1 remains pending completion and can be finalized as completed or no-show.
- Sign in as `youth2@sipora.test` and open `/youth/passport`. Bootcamp Digital Pemalang appears as a completed, verified Activity experience.
- Sign in as `youth4@sipora.test` and open `/youth/passport`. The no-show Bootcamp record does not appear.
- Youth 3 has an accepted participation with pending completion on Workshop Web Development Pemula. It cannot be finalized while that Activity is still scheduled.

Attendance is supporting evidence only. The contextual leader or manager makes the final completion decision after the Activity execution is completed.

## Phase 13 certificate scenarios

- Sign in as `youth2@sipora.test` and open `/youth/certificates`. The verified Bootcamp certificate can be viewed and downloaded as a PDF.
- Open the public verification link from Youth 2's certificate in a guest browser. The page shows only the recipient display name, Activity, organizer, issue date, certificate number, and verification code.
- Sign in as `youth3@sipora.test`, manage Bootcamp Digital Pemalang, then issue the still-unissued certificate for Youth 1. Youth 1 is completed and eligible; issuing it twice is blocked.
- Youth 4 remains `no_show` and cannot receive a certificate.
