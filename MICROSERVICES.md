# Microservices Layout

This project is structured as four independently runnable services:

| Service | Responsibility | Port | Owns database |
| --- | --- | ---: | --- |
| `user-service` | Users, login, JWT issuing, profile/history UI | 8001 | `users_db` |
| `book-service` | Book catalog and stock | 8002 | `books_db` |
| `servisPeminjaman` | Borrowing and return transactions | 8003 | `borrowings_db` |
| `servisNotifikasi` | Notification logging endpoint | 8004 | none |

## Service Boundaries

- `user-service` owns the `users` table. Other services store only `id_anggota` as an external reference.
- `book-service` owns book data and exposes internal stock endpoints protected by `X-Service-Key`.
- `servisPeminjaman` coordinates borrowing by calling `user-service` for member validation and `book-service` for stock reservation/release.
- `servisPeminjaman` dispatches notification jobs asynchronously through Laravel's database queue.
- `servisNotifikasi` receives notification requests from the borrowing queue worker.

## Local URLs

- User API/UI: `http://localhost:8001`
- Book API: `http://localhost:8002`
- Borrowing API: `http://localhost:8003`
- Notification API: `http://localhost:8004`

Use the same `SERVICE_KEY` value in `user-service`, `book-service`, and `servisPeminjaman` for internal calls.

## Run With Docker

Start Docker Desktop, then run:

```sh
docker compose up -d --build
```

The Laravel containers wait for their own MySQL database, run migrations, then start their HTTP servers.
The root stack also starts `borrowing-worker`, which processes queued notification jobs.

Useful checks:

```sh
curl http://localhost:8001
curl http://localhost:8002/api/books
curl -X POST http://localhost:8004/api/notifications/send \
  -H 'Content-Type: application/json' \
  -H 'X-Service-Key: 2f3b9986e5cd631b726f80faa1ccbb8c8f404132a42fb41dc36ad9a45a1290a4' \
  -d '{"id_anggota":1,"pesan":"test"}'
```

To reset all service databases:

```sh
docker compose down -v
```

## Run One Service Only

Each service folder also has its own `docker-compose.yml`, so it can be cloned and started by itself:

```sh
cd user-service
docker compose up -d --build
```

```sh
cd book-service
docker compose up -d --build
```

```sh
cd servisPeminjaman
docker compose up -d --build
```

```sh
cd servisNotifikasi
docker compose up -d --build
```

Standalone mode starts the selected service and its own database when needed. Cross-service features still need the other services running, so use the root `docker-compose.yml` for the complete borrowing flow.

## Async Flow

Only notification delivery is asynchronous:

```text
servisPeminjaman
  -> validates user synchronously
  -> reserves/releases book stock synchronously
  -> writes notification job to borrowings_db.jobs
  -> borrowing-worker calls servisNotifikasi
```

The queue uses Laravel's `database` driver in `servisPeminjaman`. Failed notification jobs are recorded in `failed_jobs`.
