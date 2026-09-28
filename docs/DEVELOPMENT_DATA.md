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
