#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/../.." && pwd)"

cd "$ROOT_DIR"

BUILD_IMAGES=0
if [[ "${1:-}" == "--build" ]]; then
    BUILD_IMAGES=1
fi

echo "[autotests] Resetting docker environment"
docker compose down -v --remove-orphans

if [[ "$BUILD_IMAGES" -eq 1 ]]; then
    echo "[autotests] Starting services with rebuild"
    docker compose up -d --build
else
    echo "[autotests] Starting services"
    docker compose up -d
fi

echo "[autotests] Waiting for database health"
for _ in $(seq 1 90); do
    if docker inspect -f '{{if .State.Health}}{{.State.Health.Status}}{{else}}{{.State.Status}}{{end}}' crm_db 2>/dev/null | grep -qx 'healthy'; then
        break
    fi
    sleep 2
done

DB_STATUS="$(docker inspect -f '{{if .State.Health}}{{.State.Health.Status}}{{else}}{{.State.Status}}{{end}}' crm_db 2>/dev/null || true)"
if [[ "$DB_STATUS" != "healthy" ]]; then
    echo "[autotests] Database did not become healthy"
    docker compose ps
    exit 1
fi

echo "[autotests] Waiting for backend container"
for _ in $(seq 1 60); do
    if docker compose ps --status running --services | grep -qx 'backend'; then
        break
    fi
    sleep 2
done

if ! docker compose ps --status running --services | grep -qx 'backend'; then
    echo "[autotests] Backend is not running"
    docker compose ps
    exit 1
fi

echo "[autotests] Waiting for backend availability after migrations"
for _ in $(seq 1 90); do
    if curl -sS "http://localhost:8020" >/dev/null 2>&1; then
        break
    fi
    sleep 2
done

if ! curl -sS "http://localhost:8020" >/dev/null 2>&1; then
    echo "[autotests] Backend did not become available"
    docker compose ps
    docker compose logs backend --tail=200
    exit 1
fi

echo "[autotests] Waiting for frontend availability"
for _ in $(seq 1 60); do
    if curl -sS "http://localhost:8050" >/dev/null 2>&1; then
        break
    fi
    sleep 2
done

if ! curl -sS "http://localhost:8050" >/dev/null 2>&1; then
    echo "[autotests] Frontend did not become available"
    docker compose ps
    docker compose logs frontend --tail=200
    exit 1
fi

echo "[autotests] Environment is ready"
